<?php

namespace App\Providers;

use App\Support\RuntimeMailConfig;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Applique la config email pilotée depuis le dashboard (groupe « email »)
        // par-dessus le .env. Résilient si la table n'existe pas encore.
        RuntimeMailConfig::apply();
    }
}
