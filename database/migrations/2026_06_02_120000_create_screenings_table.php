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
        Schema::create('screenings', function (Blueprint $table) {
            $table->id();
            // Film du catalogue (optionnel : on peut annoncer un film hors-catalogue)
            $table->foreignId('media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->string('movie_title');          // Nom du film affiché dans l'annonce
            $table->string('cinema_name');          // Nom du cinéma
            $table->string('location');             // Lieu / adresse
            $table->dateTime('starts_at');          // Date + heure de la séance
            $table->unsignedInteger('seats')->default(0); // Nombre de places (informatif)

            // Envoi différé de la notification push
            $table->dateTime('notify_at')->nullable();    // Quand la notif doit partir
            $table->dateTime('notified_at')->nullable();  // Quand elle est réellement partie

            $table->enum('status', ['draft', 'scheduled', 'sent', 'canceled'])
                  ->default('draft');

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['status', 'notify_at']);
            $table->index('starts_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('screenings');
    }
};
