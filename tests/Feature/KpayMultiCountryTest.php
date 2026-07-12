<?php

namespace Tests\Feature;

use App\Services\KpayService;
use Tests\TestCase;

/**
 * KPay multi-pays (#devises) : résolution du code provider par pays, préfixe
 * téléphonique local, et intégrité du mapping config/kpay.php.
 */
class KpayMultiCountryTest extends TestCase
{
    private KpayService $kpay;

    protected function setUp(): void
    {
        parent::setUp();
        $this->kpay = new KpayService();
    }

    public function test_resout_le_bon_provider_selon_le_pays(): void
    {
        $this->assertSame('MTN_MOMO_CMR', $this->kpay->providerFor('MTN_MONEY', 'CM'));
        $this->assertSame('ORANGE_CMR', $this->kpay->providerFor('ORANGE_MONEY', 'CM'));
        $this->assertSame('MTN_MOMO_BEN', $this->kpay->providerFor('MTN_MONEY', 'BJ'));
        $this->assertSame('AIRTEL_COD', $this->kpay->providerFor('AIRTEL_MONEY', 'CD'));
        $this->assertSame('VODACOM_MPESA_COD', $this->kpay->providerFor('VODACOM_MONEY', 'CD'));
        $this->assertSame('ORANGE_SLE', $this->kpay->providerFor('ORANGE_MONEY', 'SL'));
    }

    public function test_repli_legacy_cameroun_sans_pays(): void
    {
        $this->assertSame('MTN_MOMO_CMR', $this->kpay->providerFor('MTN_MONEY'));
        $this->assertSame('ORANGE_CMR', $this->kpay->providerFor('ORANGE_MONEY'));
    }

    public function test_operateurs_par_pays(): void
    {
        $this->assertArrayHasKey('VODACOM_MONEY', $this->kpay->operatorsFor('CD'));
        $this->assertArrayHasKey('AIRTEL_MONEY', $this->kpay->operatorsFor('CD'));
        $this->assertCount(3, $this->kpay->operatorsFor('ZM')); // Airtel, MTN, Zamtel
        $this->assertSame([], $this->kpay->operatorsFor('XX')); // pays inconnu
    }

    public function test_prefixe_telephone_par_pays(): void
    {
        // RDC (+243)
        $this->assertSame('243812345678', KpayService::normalizeMsisdn('0812345678', '243'));
        // Sénégal (+221), numéro déjà international
        $this->assertSame('221771234567', KpayService::normalizeMsisdn('221771234567', '221'));
        // Cameroun par défaut
        $this->assertSame('237691234567', KpayService::normalizeMsisdn('691234567'));
    }

    public function test_integrite_du_mapping_config(): void
    {
        $countries = config('kpay.countries');
        $this->assertNotEmpty($countries);

        foreach ($countries as $iso => $c) {
            $this->assertSame(2, strlen($iso), "ISO2 attendu pour {$iso}");
            $this->assertNotEmpty($c['currency'], "Devise manquante pour {$iso}");
            $this->assertNotEmpty($c['dial'], "Indicatif manquant pour {$iso}");
            $this->assertNotEmpty($c['operators'], "Opérateurs manquants pour {$iso}");
            foreach ($c['operators'] as $op => $provider) {
                $this->assertNotEmpty($provider, "Code provider vide pour {$iso}/{$op}");
            }
        }
    }
}
