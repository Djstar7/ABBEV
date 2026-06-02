<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Configuration;

/**
 * Client HTTP Stripe (API REST directe, sans package — même approche que
 * PayPalService / KpayService).
 *
 * Périmètre : encaissement par carte (VISA / Mastercard) via PaymentIntent,
 * consommé côté mobile par le PaymentSheet flutter_stripe.
 *   - Tickets de séance : Android + iOS (service physique, hors IAP Apple).
 *   - Abonnements        : Android uniquement (iOS = Apple IAP).
 *
 * Les credentials (clé publishable, clé secrète, secret de webhook) et le
 * mode (test/live) proviennent du dashboard (table configurations), pas du
 * .env — cohérent avec PayPal/KPay.
 */
class StripeService
{
    private const BASE_URL = 'https://api.stripe.com';
    private const TIMEOUT_SEC = 30;

    /**
     * Devises « zéro-décimale » Stripe : le montant est exprimé en unité
     * entière (pas en centimes). XAF/XOF en font partie — un montant de
     * 3000 FCFA s'envoie tel quel (3000), surtout pas ×100.
     *
     * Réf : https://docs.stripe.com/currencies#zero-decimal
     */
    private const ZERO_DECIMAL_CURRENCIES = [
        'bif', 'clp', 'djf', 'gnf', 'jpy', 'kmf', 'krw', 'mga', 'pyg',
        'rwf', 'ugx', 'vnd', 'vuv', 'xaf', 'xof', 'xpf',
    ];

    /**
     * Les clés Stripe sont-elles renseignées (dashboard → Configuration) ?
     */
    public function isConfigured(): bool
    {
        return $this->secretKey() !== '' && $this->publishableKey() !== '';
    }

    /**
     * Clé publishable à transmettre au client (init du SDK flutter_stripe).
     */
    public function publishableKey(): string
    {
        return (string) Configuration::getValue('stripe_publishable_key', '');
    }

    /**
     * Devise des paiements Stripe (minuscule, ISO 4217). Défaut : XAF.
     */
    public function currency(): string
    {
        return strtolower((string) Configuration::getValue('stripe_currency', 'xaf'));
    }

    /**
     * Crée un PaymentIntent et renvoie le client_secret pour le PaymentSheet.
     *
     * @param array{amount: int|float, metadata?: array<string,mixed>, description?: string, currency?: string} $params
     * @return array{success: bool, payment_intent_id?: string, client_secret?: string, publishable_key?: string, message?: string}
     */
    public function createPaymentIntent(array $params): array
    {
        if (! $this->isConfigured()) {
            Log::error('[StripeService] Clés Stripe manquantes (publishable/secret)');

            return [
                'success' => false,
                'message' => 'Stripe non configuré (clés manquantes dans le dashboard).',
            ];
        }

        $currency = strtolower($params['currency'] ?? $this->currency());
        $amount   = $this->toStripeAmount((float) $params['amount'], $currency);

        // form-encoded : les clés imbriquées (metadata, automatic_payment_methods)
        // utilisent la notation crochets attendue par l'API Stripe.
        $form = [
            'amount'                              => $amount,
            'currency'                            => $currency,
            'automatic_payment_methods[enabled]'  => 'true',
            // Pas de redirection (3DS hors-app) : le PaymentSheet gère le 3DS
            // nativement, on évite les méthodes nécessitant un retour navigateur.
            'automatic_payment_methods[allow_redirects]' => 'never',
        ];

        if (! empty($params['description'])) {
            $form['description'] = $params['description'];
        }

        foreach (($params['metadata'] ?? []) as $key => $value) {
            if ($value !== null) {
                $form["metadata[{$key}]"] = (string) $value;
            }
        }

        try {
            $response = Http::asForm()
                ->withToken($this->secretKey())
                ->timeout(self::TIMEOUT_SEC)
                ->post(self::BASE_URL . '/v1/payment_intents', $form);

            if (! $response->successful()) {
                Log::error('[StripeService] PaymentIntent creation failed', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);

                return [
                    'success' => false,
                    'message' => $response->json('error.message') ?? 'Échec de la création du paiement Stripe.',
                ];
            }

            $data = $response->json();

            Log::info('[StripeService] PaymentIntent created', [
                'id'       => $data['id'] ?? null,
                'amount'   => $amount,
                'currency' => $currency,
            ]);

            return [
                'success'           => true,
                'payment_intent_id' => $data['id'] ?? null,
                'client_secret'     => $data['client_secret'] ?? null,
                'publishable_key'   => $this->publishableKey(),
            ];
        } catch (\Exception $e) {
            Log::error('[StripeService] Exception creating PaymentIntent', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Erreur lors de la création du paiement Stripe.',
            ];
        }
    }

    /**
     * Récupère un PaymentIntent (vérification du statut côté serveur).
     *
     * @return array{success: bool, status?: string, data?: array, message?: string}
     */
    public function retrievePaymentIntent(string $id): array
    {
        if (! $this->isConfigured()) {
            return ['success' => false, 'message' => 'Stripe non configuré.'];
        }

        try {
            $response = Http::withToken($this->secretKey())
                ->timeout(self::TIMEOUT_SEC)
                ->get(self::BASE_URL . '/v1/payment_intents/' . rawurlencode($id));

            if (! $response->successful()) {
                return [
                    'success' => false,
                    'message' => $response->json('error.message') ?? 'PaymentIntent introuvable.',
                ];
            }

            $data = $response->json();

            return [
                'success' => true,
                'status'  => $data['status'] ?? null,
                'data'    => $data,
            ];
        } catch (\Exception $e) {
            Log::error('[StripeService] Exception retrieving PaymentIntent', [
                'id'    => $id,
                'error' => $e->getMessage(),
            ]);

            return ['success' => false, 'message' => 'Erreur lors de la vérification Stripe.'];
        }
    }

    /**
     * Vérifie la signature d'un webhook Stripe (en-tête `Stripe-Signature`).
     *
     * Implémente le schéma officiel : on recompose `{timestamp}.{payload}`,
     * on calcule un HMAC-SHA256 avec le webhook signing secret, et on le
     * compare (temps constant) à la signature `v1` de l'en-tête. Tolérance
     * de 5 min sur le timestamp pour bloquer les rejeux.
     *
     * Réf : https://docs.stripe.com/webhooks#verify-manually
     *
     * @param string $payload Corps brut de la requête (non décodé).
     */
    public function verifyWebhookSignature(string $payload, ?string $sigHeader): bool
    {
        $secret = $this->webhookSecret();

        if ($secret === '' || ! $sigHeader) {
            return false;
        }

        $timestamp = null;
        $signatures = [];

        foreach (explode(',', $sigHeader) as $part) {
            [$k, $v] = array_pad(explode('=', trim($part), 2), 2, null);
            if ($k === 't') {
                $timestamp = $v;
            } elseif ($k === 'v1' && $v !== null) {
                $signatures[] = $v;
            }
        }

        if (! $timestamp || empty($signatures)) {
            return false;
        }

        // Anti-rejeu : on rejette un timestamp trop ancien (> 5 min).
        if (abs(time() - (int) $timestamp) > 300) {
            Log::warning('[StripeService] Webhook timestamp hors tolérance');

            return false;
        }

        $expected = hash_hmac('sha256', "{$timestamp}.{$payload}", $secret);

        foreach ($signatures as $sig) {
            if (hash_equals($expected, $sig)) {
                return true;
            }
        }

        Log::warning('[StripeService] Webhook signature invalide');

        return false;
    }

    /**
     * Convertit un montant (unité majeure, ex: 3000 FCFA ou 9.99 EUR) vers
     * l'entier attendu par Stripe selon la devise.
     */
    private function toStripeAmount(float $amount, string $currency): int
    {
        if (in_array($currency, self::ZERO_DECIMAL_CURRENCIES, true)) {
            return (int) round($amount);
        }

        return (int) round($amount * 100);
    }

    private function secretKey(): string
    {
        return (string) Configuration::getValue('stripe_secret_key', '');
    }

    private function webhookSecret(): string
    {
        return (string) Configuration::getValue('stripe_webhook_secret', '');
    }
}
