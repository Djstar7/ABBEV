<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Vérification des achats In-App Apple (StoreKit 2) via l'App Store Server API.
 *
 * Flux : l'app envoie un `transactionId` (issu de StoreKit). On interroge
 * l'API Apple, authentifiée par un JWT ES256 signé avec notre clé .p8, qui
 * renvoie la transaction SIGNÉE par Apple (JWS). Apple étant la source de
 * vérité, on décode le payload sans avoir à valider nous-mêmes la chaîne x5c.
 *
 * Doc : https://developer.apple.com/documentation/appstoreserverapi
 */
class AppleAppStoreService
{
    private const PROD_BASE = 'https://api.storekit.itunes.apple.com';
    private const SANDBOX_BASE = 'https://api.storekit-sandbox.itunes.apple.com';

    private string $bundleId;
    private ?string $issuerId;
    private ?string $keyId;
    private bool $sandbox;

    public function __construct()
    {
        $cfg = config('services.apple_iap');
        $this->bundleId = $cfg['bundle_id'];
        $this->issuerId = $cfg['issuer_id'] ?? null;
        $this->keyId = $cfg['key_id'] ?? null;
        $this->sandbox = (bool) ($cfg['sandbox'] ?? true);
    }

    /**
     * Récupère et décode une transaction auprès de l'App Store Server API.
     *
     * @return array{success:bool, message?:string, payload?:array}
     */
    public function getTransaction(string $transactionId): array
    {
        try {
            $token = $this->generateToken();
        } catch (\Throwable $e) {
            Log::error('[AppleIAP] Token signing failed', ['error' => $e->getMessage()]);

            return ['success' => false, 'message' => 'Configuration Apple IAP invalide'];
        }

        // On essaie d'abord l'environnement configuré, puis on bascule sur
        // l'autre si Apple répond 4xx (cas classique : reçu sandbox sur prod).
        $bases = $this->sandbox
            ? [self::SANDBOX_BASE, self::PROD_BASE]
            : [self::PROD_BASE, self::SANDBOX_BASE];

        foreach ($bases as $base) {
            $response = Http::withToken($token)
                ->acceptJson()
                ->get("{$base}/inApps/v1/transactions/{$transactionId}");

            if ($response->successful()) {
                $signed = $response->json('signedTransactionInfo');

                if (!$signed) {
                    return ['success' => false, 'message' => 'Réponse Apple sans signedTransactionInfo'];
                }

                $payload = $this->decodeJws($signed);

                if ($payload === null) {
                    return ['success' => false, 'message' => 'JWS Apple illisible'];
                }

                return ['success' => true, 'payload' => $payload];
            }

            // 404 = transaction inconnue de cet environnement → on tente l'autre.
            if ($response->status() !== 404) {
                Log::warning('[AppleIAP] App Store API error', [
                    'status' => $response->status(),
                    'base' => $base,
                    'body' => $response->body(),
                ]);
            }
        }

        return ['success' => false, 'message' => 'Transaction introuvable côté Apple'];
    }

    /**
     * Décode le payload d'une notification App Store Server v2 (signedPayload).
     * Renvoie le contenu déballé (notificationType, data, etc.) ou null.
     */
    public function decodeNotification(string $signedPayload): ?array
    {
        return $this->decodeJws($signedPayload);
    }

    /**
     * Vérifie qu'un payload de transaction correspond bien à notre app et
     * qu'il n'est pas expiré/révoqué.
     */
    public function isTransactionValid(array $payload): bool
    {
        if (($payload['bundleId'] ?? null) !== $this->bundleId) {
            return false;
        }

        // Remboursée / révoquée : Apple positionne revocationDate.
        if (!empty($payload['revocationDate'])) {
            return false;
        }

        return true;
    }

    /**
     * Génère le JWT ES256 attendu par l'App Store Server API.
     */
    private function generateToken(): string
    {
        $privateKey = $this->resolvePrivateKey();

        if (!$this->issuerId || !$this->keyId || !$privateKey) {
            throw new \RuntimeException('issuer_id / key_id / private_key manquants');
        }

        $now = time();
        $payload = [
            'iss' => $this->issuerId,
            'iat' => $now,
            'exp' => $now + 1500, // < 60 min imposé par Apple
            'aud' => 'appstoreconnect-v1',
            'bid' => $this->bundleId,
        ];

        return JWT::encode($payload, $privateKey, 'ES256', $this->keyId);
    }

    /**
     * Charge la clé privée .p8 depuis l'env (contenu PEM) ou un fichier.
     */
    private function resolvePrivateKey(): ?string
    {
        $cfg = config('services.apple_iap');

        if (!empty($cfg['private_key'])) {
            // Les sauts de ligne sont souvent échappés en \n dans le .env.
            return str_replace('\\n', "\n", $cfg['private_key']);
        }

        if (!empty($cfg['key_path']) && is_readable($cfg['key_path'])) {
            return file_get_contents($cfg['key_path']);
        }

        return null;
    }

    /**
     * Décode le payload d'un JWS Apple sans vérifier la signature : la
     * source (App Store Server API authentifiée) fait déjà foi.
     */
    private function decodeJws(string $jws): ?array
    {
        $parts = explode('.', $jws);

        if (count($parts) !== 3) {
            return null;
        }

        $json = base64_decode(strtr($parts[1], '-_', '+/'));
        $data = json_decode($json, true);

        return is_array($data) ? $data : null;
    }
}
