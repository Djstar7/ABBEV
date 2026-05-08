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
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['movie', 'series'])->default('movie'); // Film ou Série
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->integer('duration')->nullable(); // en secondes
            $table->year('release_year')->nullable(); // Année de sortie
            $table->integer('seasons')->nullable(); // Nombre de saisons (pour séries)
            $table->string('video_path')->nullable();
            $table->string('thumbnail_path')->nullable(); // Vignette
            $table->string('banner_path')->nullable(); // Bannière
            $table->string('cover_path')->nullable(); // Couverture/Affiche
            $table->timestamp('published_at')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('views_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
