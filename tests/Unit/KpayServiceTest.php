<?php

namespace Tests\Unit;

use App\Services\KpayService;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Tests unitaires PURS (sans base ni HTTP) de la logique KPay :
 *  - mapping opérateur interne → code provider KPay ;
 *  - normalisation du numéro Mobile Money au format international.
 *
 * Ces deux points sont exactement ceux exigés par la spec KPay :
 * le champ `provider` doit valoir ex. ORANGE_CMR / MTN_MOMO_CMR, et le
 * `phoneNumber` doit être au format international (2376XXXXXXXX).
 */
class KpayServiceTest extends TestCase
{
    public function test_provider_for_mappe_les_operateurs_internes_vers_les_codes_kpay(): void
    {
        $svc = new KpayService();

        $this->assertSame('ORANGE_CMR', $svc->providerFor('ORANGE_MONEY'));
        $this->assertSame('MTN_MOMO_CMR', $svc->providerFor('MTN_MONEY'));
    }

    public function test_provider_for_laisse_passer_un_code_kpay_ou_inconnu(): void
    {
        $svc = new KpayService();

        // Déjà un code KPay valide → inchangé.
        $this->assertSame('ORANGE_CMR', $svc->providerFor('ORANGE_CMR'));
        // Inconnu → renvoyé tel quel (KPay tranchera avec un 400 explicite).
        $this->assertSame('FOO_BAR', $svc->providerFor('FOO_BAR'));
    }

    #[DataProvider('msisdnProvider')]
    public function test_normalize_msisdn(string $input, string $expected): void
    {
        $this->assertSame($expected, KpayService::normalizeMsisdn($input));
    }

    public function test_extract_error_message_privilegie_message_sur_error(): void
    {
        // Enveloppe réelle KPay : `error` générique, `message` exploitable.
        $decoded = [
            'statusCode' => 400,
            'error' => 'Bad Request',
            'code' => 'BAD_REQUEST',
            'message' => 'Le montant minimum autorisé pour un paiement est de 100 XAF',
            'path' => '/api/v1/payments/init',
        ];

        $this->assertSame(
            'Le montant minimum autorisé pour un paiement est de 100 XAF',
            KpayService::extractErrorMessage($decoded),
        );
    }

    public function test_extract_error_message_replis(): void
    {
        // Pas de `message` → on retombe sur `error`.
        $this->assertSame('Unauthorized', KpayService::extractErrorMessage([
            'error' => 'Unauthorized',
        ]));
        // Corps non exploitable → libellé générique.
        $this->assertSame('KPay error', KpayService::extractErrorMessage(null));
        $this->assertSame('KPay error', KpayService::extractErrorMessage('<html>'));
    }

    public static function msisdnProvider(): array
    {
        return [
            'local 9 chiffres'      => ['670000001', '237670000001'],
            'local avec espaces'    => ['6 70 00 00 01', '237670000001'],
            'deja international'     => ['237670000001', '237670000001'],
            'avec prefixe +'        => ['+237 670000001', '237670000001'],
            'trunk 0 en tete'       => ['0670000001', '237670000001'],
            'numero de test sandbox'=> ['237653456789', '237653456789'],
            'vide'                  => ['', ''],
        ];
    }
}
