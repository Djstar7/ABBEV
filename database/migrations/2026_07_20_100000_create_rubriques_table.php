<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Rubriques : sections thématiques gérées depuis l'admin, dont l'accès est
 * débloqué par les forfaits d'abonnement (pivot plan_rubrique).
 *
 * content_type :
 *   - 'oeuvre' : la rubrique contient des œuvres (livres/documents PDF).
 *   - 'media'  : la rubrique surface des films/séries existants.
 * source_filter (pour les rubriques 'media') :
 *   - 'rare' : alimente automatiquement la rubrique avec le contenu flaggé rare.
 *   - null   : contenu assigné manuellement (non utilisé pour l'instant).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rubriques', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('cover_path')->nullable();
            $table->enum('content_type', ['oeuvre', 'media'])->default('oeuvre');
            $table->string('source_filter')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rubriques');
    }
};
