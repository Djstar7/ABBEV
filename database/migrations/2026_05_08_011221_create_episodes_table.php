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
        Schema::create('episodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('season_id')->constrained('seasons')->onDelete('cascade');
            $table->integer('episode_number'); // Numéro de l'épisode (1, 2, 3...)
            $table->string('title'); // Titre de l'épisode
            $table->text('description')->nullable();
            $table->integer('duration')->nullable(); // Durée en secondes
            $table->string('video_path'); // Chemin vers la vidéo (REQUIS pour épisode)
            $table->string('thumbnail_path')->nullable(); // Vignette de l'épisode
            $table->timestamp('published_at')->nullable();
            $table->integer('views_count')->default(0);
            $table->timestamps();

            // Index pour optimiser les requêtes
            $table->unique(['season_id', 'episode_number']);
            $table->index('video_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('episodes');
    }
};
