<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Renomme la rubrique « Premier plan » en « Exclusivités » (libellé plus
 * attrayant) pour les installations existantes. Le slug reste inchangé
 * (premier-plan) afin de ne rien casser côté références.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('rubriques')
            ->where('slug', 'premier-plan')
            ->update([
                'name' => 'Exclusivités',
                'description' => 'Pépites rares et contenus mis en avant, rien que pour vous.',
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        DB::table('rubriques')
            ->where('slug', 'premier-plan')
            ->update([
                'name' => 'Premier plan',
                'description' => 'Contenus rares mis en avant.',
                'updated_at' => now(),
            ]);
    }
};
