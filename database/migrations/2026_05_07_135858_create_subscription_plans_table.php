<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nom du pack (Basic, Premium, VIP)
            $table->text('description')->nullable(); // Description du pack
            $table->decimal('price', 10, 2); // Prix en XAF
            $table->integer('duration_days'); // Durée de validité en jours
            $table->json('features')->nullable(); // Fonctionnalités (JSON)
            $table->boolean('is_active')->default(true); // Actif/Inactif
            $table->boolean('is_popular')->default(false); // Badge "Populaire"
            $table->integer('order')->default(0); // Ordre d'affichage
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
