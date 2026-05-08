<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configurations', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Clé unique (paypal_client_id, freemopay_app_key, etc.)
            $table->text('value')->nullable(); // Valeur
            $table->string('group')->default('general'); // Groupe (paypal, freemopay, nexah_sms, whatsapp, promo)
            $table->text('description')->nullable();
            $table->boolean('is_secret')->default(false); // Si c'est un secret (ne pas afficher en clair)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configurations');
    }
};
