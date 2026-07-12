<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Pays, opérateurs et codes provider KPay (Mobile Money)
    |--------------------------------------------------------------------------
    |
    | Mapping utilisé pour router un paiement KPay vers le bon pays/opérateur :
    |   - `currency` : devise LOCALE réellement débitée par KPay pour ce pays.
    |   - `dial`     : indicatif téléphonique (préfixé au numéro local saisi).
    |   - `operators`: opérateur interne (envoyé par le mobile) => code provider
    |                  EXACT attendu par KPay.
    |
    | ⚠️ IMPORTANT — les codes provider suivent le format {OPÉRATEUR}_{ISO3}
    | observé pour le Cameroun (MTN_MOMO_CMR, ORANGE_CMR). VÉRIFIEZ-les contre la
    | liste réelle des providers de VOTRE compte KPay :
    |     php artisan kpay:providers
    | puis corrigez ici toute valeur qui ne correspondrait pas. Un code erroné
    | fait échouer (ou mal router) un vrai paiement.
    |
    | Basé sur la pricelist KPay des pays/opérateurs utilisés par ABBEV.
    */
    'countries' => [
        'BJ' => [
            'name' => 'Bénin',
            'currency' => 'XOF',
            'dial' => '229',
            'operators' => [
                'MTN_MONEY' => 'MTN_MOMO_BEN',
                'MOOV_MONEY' => 'MOOV_BEN',
            ],
        ],
        'CM' => [
            'name' => 'Cameroun',
            'currency' => 'XAF',
            'dial' => '237',
            'operators' => [
                'MTN_MONEY' => 'MTN_MOMO_CMR',
                'ORANGE_MONEY' => 'ORANGE_CMR',
            ],
        ],
        'CI' => [
            'name' => "Côte d'Ivoire",
            'currency' => 'XOF',
            'dial' => '225',
            'operators' => [
                'MTN_MONEY' => 'MTN_MOMO_CIV',
                'ORANGE_MONEY' => 'ORANGE_CIV',
            ],
        ],
        'CD' => [
            'name' => 'RD Congo',
            'currency' => 'CDF',
            'dial' => '243',
            'operators' => [
                'VODACOM_MONEY' => 'VODACOM_MPESA_COD',
                'AIRTEL_MONEY' => 'AIRTEL_COD',
                'ORANGE_MONEY' => 'ORANGE_COD',
            ],
        ],
        'GA' => [
            'name' => 'Gabon',
            'currency' => 'XAF',
            'dial' => '241',
            'operators' => [
                'AIRTEL_MONEY' => 'AIRTEL_GAB',
            ],
        ],
        'CG' => [
            'name' => 'Congo',
            'currency' => 'XAF',
            'dial' => '242',
            'operators' => [
                'AIRTEL_MONEY' => 'AIRTEL_COG',
                'MTN_MONEY' => 'MTN_MOMO_COG',
            ],
        ],
        'RW' => [
            'name' => 'Rwanda',
            'currency' => 'RWF',
            'dial' => '250',
            'operators' => [
                'AIRTEL_MONEY' => 'AIRTEL_RWA',
                'MTN_MONEY' => 'MTN_MOMO_RWA',
            ],
        ],
        'SN' => [
            'name' => 'Sénégal',
            'currency' => 'XOF',
            'dial' => '221',
            'operators' => [
                'FREE_MONEY' => 'FREE_SEN',
                'ORANGE_MONEY' => 'ORANGE_SEN',
            ],
        ],
        'SL' => [
            'name' => 'Sierra Leone',
            'currency' => 'SLE',
            'dial' => '232',
            'operators' => [
                'ORANGE_MONEY' => 'ORANGE_SLE',
            ],
        ],
        'UG' => [
            'name' => 'Ouganda',
            'currency' => 'UGX',
            'dial' => '256',
            'operators' => [
                'AIRTEL_MONEY' => 'AIRTEL_UGA',
                'MTN_MONEY' => 'MTN_MOMO_UGA',
            ],
        ],
        'ZM' => [
            'name' => 'Zambie',
            'currency' => 'ZMW',
            'dial' => '260',
            'operators' => [
                'AIRTEL_MONEY' => 'AIRTEL_ZMB',
                'MTN_MONEY' => 'MTN_MOMO_ZMB',
                'ZAMTEL_MONEY' => 'ZAMTEL_ZMB',
            ],
        ],
    ],

    /*
    | Libellés lisibles des opérateurs internes (pour l'UI mobile).
    */
    'operator_labels' => [
        'MTN_MONEY' => 'MTN MoMo',
        'ORANGE_MONEY' => 'Orange Money',
        'MOOV_MONEY' => 'Moov Money',
        'AIRTEL_MONEY' => 'Airtel Money',
        'VODACOM_MONEY' => 'Vodacom M-Pesa',
        'FREE_MONEY' => 'Free Money',
        'ZAMTEL_MONEY' => 'Zamtel Kwacha',
    ],
];
