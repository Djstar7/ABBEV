<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Housekeeping des uploads Bunny (lignes bloquées + fichiers temporaires orphelins).
Schedule::command('bunny:uploads:cleanup')->hourly();

// Auto-conversion des vidéos locales non-MP4 (ex. .webm) : enfile la
// conversion sur la file « bunny » (traitée par queue:work --queue=bunny
// --timeout=0). Idempotent : ignore ce qui est déjà en MP4.
Schedule::command('videos:transcode-local')
    ->everyThirtyMinutes()
    ->withoutOverlapping();

// Taux de change live (ExchangeRate-API met à jour ~1×/jour) : on rafraîchit
// currencies.rate_from_xof chaque jour pour une conversion d'affichage ET de
// paiement (PayPal/crypto/KPay local) toujours à jour.
Schedule::command('rates:update')->dailyAt('05:00')->withoutOverlapping();
