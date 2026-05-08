<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\Transaction;
use App\Models\UserSubscription;
use App\Services\PayPalService;
use App\Services\FreemopayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SubscriptionPaymentController extends Controller
{
    protected PayPalService $paypalService;
    protected FreemopayService $freemopayService;

    public function __construct(PayPalService $paypalService, FreemopayService $freemopayService)
    {
        $this->paypalService = $paypalService;
        $this->freemopayService = $freemopayService;
    }

    /**
     * Initier un paiement d'abonnement
     * POST /api/subscription-payment/initiate
     */
    public function initiate(Request $request)
    {
        $validated = $request->validate([
            'subscription_plan_id' => 'required|exists:subscription_plans,id',
            'payment_method' => 'required|in:paypal,freemopay',
            'phone_number' => 'required_if:payment_method,freemopay|string',
        ]);

        try {
            $plan = SubscriptionPlan::findOrFail($validated['subscription_plan_id']);
            $user = auth()->user();

            // Créer la transaction en pending
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'transaction_id' => 'TXN-' . strtoupper(Str::random(12)),
                'payment_method' => $validated['payment_method'],
                'type' => 'subscription',
                'amount' => $plan->price,
                'net_amount' => $plan->price,
                'currency' => 'XAF',
                'description' => "Abonnement {$plan->name}",
                'status' => 'pending',
                'metadata' => [
                    'subscription_plan_id' => $plan->id,
                    'plan_name' => $plan->name,
                    'duration_days' => $plan->duration_days,
                ],
            ]);

            if ($validated['payment_method'] === 'paypal') {
                // Initier paiement PayPal
                $result = $this->paypalService->createOrder([
                    'amount' => $plan->price,
                    'user_id' => $user->id,
                    'return_url' => url('/api/subscription-payment/paypal/success'),
                    'cancel_url' => url('/api/subscription-payment/paypal/cancel'),
                ]);

                if (!$result['success']) {
                    $transaction->update(['status' => 'failed']);
                    return response()->json([
                        'success' => false,
                        'message' => $result['message'],
                    ], 400);
                }

                // Mettre à jour la transaction avec l'order_id PayPal
                $transaction->update([
                    'external_reference' => $result['order_id'],
                    'metadata' => array_merge($transaction->metadata, [
                        'paypal_order_id' => $result['order_id'],
                        'amount_usd' => $result['amount_usd'],
                    ]),
                ]);

                return response()->json([
                    'success' => true,
                    'transaction_id' => $transaction->transaction_id,
                    'payment_method' => 'paypal',
                    'approval_url' => $result['approval_url'],
                    'order_id' => $result['order_id'],
                ]);

            } else {
                // Initier paiement FreeMoPay
                $result = $this->freemopayService->initializePayment([
                    'amount' => $plan->price,
                    'phone_number' => $validated['phone_number'],
                    'external_reference' => $transaction->transaction_id,
                    'description' => "Abonnement {$plan->name}",
                    'callback_url' => url('/api/webhooks/freemopay'),
                ]);

                if (!$result['success']) {
                    $transaction->update(['status' => 'failed']);
                    return response()->json([
                        'success' => false,
                        'message' => $result['message'],
                    ], 400);
                }

                // Mettre à jour la transaction avec la référence FreeMoPay
                $transaction->update([
                    'external_reference' => $result['reference'],
                    'metadata' => array_merge($transaction->metadata, [
                        'freemopay_reference' => $result['reference'],
                    ]),
                ]);

                return response()->json([
                    'success' => true,
                    'transaction_id' => $transaction->transaction_id,
                    'payment_method' => 'freemopay',
                    'reference' => $result['reference'],
                    'status' => $result['status'],
                    'message' => 'Veuillez composer le code USSD affiché sur votre téléphone',
                ]);
            }

        } catch (\Exception $e) {
            Log::error('[SubscriptionPayment] Error initiating payment', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'initialisation du paiement',
            ], 500);
        }
    }

    /**
     * Capturer un paiement PayPal après approbation
     * POST /api/subscription-payment/paypal/capture
     */
    public function capturePayPal(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|string',
        ]);

        try {
            // Trouver la transaction
            $transaction = Transaction::where('external_reference', $validated['order_id'])
                ->where('payment_method', 'paypal')
                ->firstOrFail();

            // Capturer le paiement
            $result = $this->paypalService->captureOrder($validated['order_id']);

            if (!$result['success']) {
                $transaction->update(['status' => 'failed']);
                return response()->json([
                    'success' => false,
                    'message' => $result['message'],
                ], 400);
            }

            // Mettre à jour la transaction
            $transaction->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            // Créer l'abonnement
            $this->createSubscription($transaction);

            return response()->json([
                'success' => true,
                'message' => 'Paiement effectué avec succès',
                'transaction_id' => $transaction->transaction_id,
            ]);

        } catch (\Exception $e) {
            Log::error('[SubscriptionPayment] Error capturing PayPal payment', [
                'error' => $e->getMessage(),
                'order_id' => $validated['order_id'],
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la capture du paiement',
            ], 500);
        }
    }

    /**
     * Vérifier le statut d'un paiement FreeMoPay
     * GET /api/subscription-payment/freemopay/status/{reference}
     */
    public function checkFreeMoPayStatus($reference)
    {
        try {
            $result = $this->freemopayService->checkStatus($reference);

            // Trouver la transaction
            $transaction = Transaction::where('external_reference', $reference)
                ->where('payment_method', 'freemopay')
                ->first();

            if (!$transaction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction introuvable',
                ], 404);
            }

            $status = strtoupper($result['status']);

            // Si le paiement est réussi
            if (in_array($status, ['SUCCESS', 'SUCCESSFUL', 'COMPLETED'])) {
                if ($transaction->status !== 'completed') {
                    $transaction->update([
                        'status' => 'completed',
                        'completed_at' => now(),
                    ]);

                    // Créer l'abonnement
                    $this->createSubscription($transaction);
                }

                return response()->json([
                    'success' => true,
                    'status' => 'completed',
                    'message' => 'Paiement effectué avec succès',
                    'transaction_id' => $transaction->transaction_id,
                ]);
            }

            // Si le paiement a échoué
            if (in_array($status, ['FAILED', 'FAILURE', 'ERROR', 'REJECTED', 'CANCELLED'])) {
                $transaction->update(['status' => 'failed']);

                return response()->json([
                    'success' => false,
                    'status' => 'failed',
                    'message' => 'Le paiement a échoué',
                    'reason' => $result['reason'] ?? null,
                ]);
            }

            // Sinon, toujours en attente
            return response()->json([
                'success' => true,
                'status' => 'pending',
                'message' => 'Paiement en cours de traitement',
            ]);

        } catch (\Exception $e) {
            Log::error('[SubscriptionPayment] Error checking FreeMoPay status', [
                'error' => $e->getMessage(),
                'reference' => $reference,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la vérification du statut',
            ], 500);
        }
    }

    /**
     * Créer l'abonnement après paiement réussi
     */
    protected function createSubscription(Transaction $transaction)
    {
        $planId = $transaction->metadata['subscription_plan_id'] ?? null;

        if (!$planId) {
            Log::error('[SubscriptionPayment] No plan ID in transaction metadata', [
                'transaction_id' => $transaction->id,
            ]);
            return;
        }

        $plan = SubscriptionPlan::find($planId);

        if (!$plan) {
            Log::error('[SubscriptionPayment] Plan not found', [
                'plan_id' => $planId,
            ]);
            return;
        }

        // Créer l'abonnement
        UserSubscription::create([
            'user_id' => $transaction->user_id,
            'subscription_plan_id' => $plan->id,
            'transaction_id' => $transaction->id,
            'starts_at' => now(),
            'expires_at' => now()->addDays($plan->duration_days),
            'status' => 'active',
        ]);

        Log::info('[SubscriptionPayment] Subscription created', [
            'user_id' => $transaction->user_id,
            'plan_id' => $plan->id,
            'transaction_id' => $transaction->id,
        ]);
    }

    /**
     * Webhook FreeMoPay (optionnel)
     * POST /api/webhooks/freemopay
     */
    public function freemopayWebhook(Request $request)
    {
        Log::info('[FreeMoPay Webhook] Received', $request->all());

        $reference = $request->input('reference');

        if (!$reference) {
            return response()->json(['message' => 'No reference provided'], 400);
        }

        // Vérifier le statut via l'API
        $this->checkFreeMoPayStatus($reference);

        return response()->json(['message' => 'Webhook processed']);
    }
}
