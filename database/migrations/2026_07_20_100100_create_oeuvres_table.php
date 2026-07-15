<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Œuvres : contenus de type livre/document (PDF) rattachés à une rubrique
 * 'oeuvre'. Le fichier est stocké sur le disque public (servi avec CORS via
 * /media/img/... comme les autres médias).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('oeuvres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rubrique_id')->constrained('rubriques')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('author')->nullable();
            $table->string('cover_path')->nullable();
            $table->string('file_path')->nullable(); // PDF
            $table->unsignedInteger('pages')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('oeuvres');
    }
};
