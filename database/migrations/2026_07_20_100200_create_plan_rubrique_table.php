<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Pivot forfait ↔ rubrique : quels forfaits d'abonnement débloquent quelles
 * rubriques. Un utilisateur accède à une rubrique si son abonnement actif est
 * lié à un forfait présent dans ce pivot pour la rubrique.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plan_rubrique', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_plan_id')->constrained('subscription_plans')->cascadeOnDelete();
            $table->foreignId('rubrique_id')->constrained('rubriques')->cascadeOnDelete();
            $table->unique(['subscription_plan_id', 'rubrique_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_rubrique');
    }
};
