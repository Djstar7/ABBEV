<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Crée les deux rubriques de base décrites au cahier des charges :
 *   - « Œuvre adaptable » : livres/documents d'inspiration (type oeuvre).
 *   - « A.Premiere » : contenu rare mis en avant (type media, filtre 'rare').
 * Idempotent : n'insère que si le slug n'existe pas déjà.
 */
return new class extends Migration
{
    public function up(): void
    {
        $now = now();
        $rows = [
            [
                'name' => 'Œuvre adaptable',
                'slug' => 'oeuvre-adaptable',
                'description' => "Livres et documents d'inspiration à lire dans l'app.",
                'content_type' => 'oeuvre',
                'source_filter' => null,
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'A.Premiere',
                'slug' => 'premier-plan',
                'description' => 'Contenus rares mis en avant.',
                'content_type' => 'media',
                'source_filter' => 'rare',
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($rows as $row) {
            $exists = DB::table('rubriques')->where('slug', $row['slug'])->exists();
            if (! $exists) {
                DB::table('rubriques')->insert($row);
            }
        }
    }

    public function down(): void
    {
        DB::table('rubriques')->whereIn('slug', ['oeuvre-adaptable', 'premier-plan'])->delete();
    }
};
