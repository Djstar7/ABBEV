<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        // Offre unique ABBEV : un seul abonnement à 2500 FCFA / mois qui
        // donne accès à tout le catalogue. `apple_product_id` mappe ce plan
        // à l'abonnement auto-renouvelable déclaré dans App Store Connect.
        SubscriptionPlan::updateOrCreate(
            ['name' => 'ABBEV'],
            [
                'description' => 'Accès complet à tout le catalogue ABBEV',
                'price' => 2500,
                'duration_days' => 30,
                'features' => [
                    'Catalogue complet (films & séries)',
                    'Sans publicité',
                    'Visionnage Full HD',
                    'Téléchargement hors-ligne illimité',
                    'Nouveautés et exclusivités',
                ],
                'is_active' => true,
                'is_popular' => true,
                'order' => 1,
                'apple_product_id' => 'com.abbev.sub.monthly',
            ]
        );
    }
}
