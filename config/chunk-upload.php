<?php
/**
 * Config du package pion/laravel-chunk-upload (réception chunkée des vidéos
 * uploadées vers la Bunny Library). Publiée et adaptée pour ABBEV.
 *
 * @see https://github.com/pionl/laravel-chunk-upload
 */

return [
    /*
     * Stockage des chunks partiels : storage/app/chunks, disque local.
     */
    'storage' => [
        'chunks' => 'chunks',
        'disk'   => 'local',
    ],

    'clear' => [
        /*
         * On gère nous-mêmes les morceaux (réception maison résumable) et leur
         * purge via la commande bunny:uploads:cleanup. On désactive donc le
         * nettoyage planifié du package pour qu'il ne touche pas nos morceaux
         * pendant un upload lent de plusieurs Go.
         */
        'timestamp' => '-24 HOURS',
        'schedule'  => [
            'enabled' => false,
        ],
    ],

    'chunk' => [
        /*
         * Nommage déterministe des chunks (sans session ni IP/navigateur) :
         * le nom original + l'identifiant resumable suffisent à l'unicité par
         * upload. Évite que les chunks deviennent introuvables si la session
         * est régénérée pendant un long upload.
         */
        'name' => [
            'use' => [
                'session' => false,
                'browser' => false,
            ],
        ],
    ],

    'handlers' => [
        'custom'   => [],
        'override' => [],
    ],
];
