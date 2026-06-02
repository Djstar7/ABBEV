<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Le modèle « annonce push » d'origine utilisait les statuts
 * draft/scheduled/sent/canceled et des colonnes notify_at/notified_at.
 * Le modèle « réservation » les remplace par draft/published/canceled et
 * n'a plus besoin des colonnes push. Sur SQLite l'enum est une contrainte
 * CHECK figée : on reconstruit donc la colonne status.
 */
return new class extends Migration
{
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        // L'index (status, notify_at) de l'ancien modèle push référence
        // notify_at : le retirer avant de supprimer la colonne.
        if ($driver === 'sqlite') {
            DB::statement('DROP INDEX IF EXISTS screenings_status_notify_at_index');
        } else {
            try {
                Schema::table('screenings', fn ($t) => $t->dropIndex('screenings_status_notify_at_index'));
            } catch (\Throwable $e) {
                // index déjà absent
            }
        }

        // Retirer les colonnes push devenues inutiles.
        foreach (['notify_at', 'notified_at'] as $col) {
            if (Schema::hasColumn('screenings', $col)) {
                Schema::table('screenings', function ($table) use ($col) {
                    $table->dropColumn($col);
                });
            }
        }

        if ($driver === 'sqlite') {
            // Garde-fou : si un précédent passage a laissé status_new, le retirer.
            if (Schema::hasColumn('screenings', 'status_new')) {
                Schema::table('screenings', function ($table) {
                    $table->dropColumn('status_new');
                });
            }

            Schema::table('screenings', function ($table) {
                $table->string('status_new')->default('draft');
            });

            DB::statement("UPDATE screenings SET status_new = CASE status
                WHEN 'scheduled' THEN 'published'
                WHEN 'sent'      THEN 'published'
                WHEN 'published' THEN 'published'
                WHEN 'canceled'  THEN 'canceled'
                ELSE 'draft' END");

            Schema::table('screenings', function ($table) {
                $table->dropColumn('status');
            });
            Schema::table('screenings', function ($table) {
                $table->renameColumn('status_new', 'status');
            });
        } else {
            DB::table('screenings')->whereIn('status', ['scheduled', 'sent'])->update(['status' => 'published']);
            DB::statement("ALTER TABLE screenings MODIFY status ENUM('draft','published','canceled') NOT NULL DEFAULT 'draft'");
        }
    }

    public function down(): void
    {
        // Pas de retour arrière utile (l'ancien modèle push est abandonné).
    }
};
