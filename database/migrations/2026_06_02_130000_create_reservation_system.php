<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // La séance n'a plus un stock global unique : le stock est porté par
        // chaque catégorie de place (ticket_types). On retire donc `seats`.
        Schema::table('screenings', function (Blueprint $table) {
            if (Schema::hasColumn('screenings', 'seats')) {
                $table->dropColumn('seats');
            }
        });

        // Catégories de places d'une séance : Standard, VIP, ...
        // Chacune a son prix et sa capacité ; sold_seats suit les ventes.
        Schema::create('ticket_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('screening_id')->constrained('screenings')->cascadeOnDelete();
            $table->string('name');                       // Standard, VIP, Loge...
            $table->decimal('price', 15, 2)->default(0);  // Prix par place
            $table->string('currency', 3)->default('XAF');
            $table->unsignedInteger('capacity')->default(0);    // Places totales
            $table->unsignedInteger('sold_seats')->default(0);  // Places vendues
            $table->timestamps();

            $table->index('screening_id');
        });

        // Réservation d'un utilisateur sur une catégorie de place d'une séance.
        // Le paiement passe par la table transactions existante (type=purchase).
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();        // Code de réservation (ABBEV-XXXX)
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('screening_id')->constrained('screenings')->cascadeOnDelete();
            $table->foreignId('ticket_type_id')->constrained('ticket_types')->cascadeOnDelete();
            $table->foreignId('transaction_id')->nullable()->constrained('transactions')->nullOnDelete();

            $table->unsignedInteger('quantity')->default(1);    // Nombre de places
            $table->decimal('unit_price', 15, 2)->default(0);   // Prix unitaire au moment de la résa
            $table->decimal('total_amount', 15, 2)->default(0); // quantity * unit_price
            $table->string('currency', 3)->default('XAF');

            // pending  : créée, paiement non confirmé (stock PAS encore décrémenté)
            // confirmed: payée, places décomptées du stock
            // canceled : annulée / paiement échoué
            $table->enum('status', ['pending', 'confirmed', 'canceled'])->default('pending');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('screening_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
        Schema::dropIfExists('ticket_types');

        Schema::table('screenings', function (Blueprint $table) {
            if (! Schema::hasColumn('screenings', 'seats')) {
                $table->unsignedInteger('seats')->default(0);
            }
        });
    }
};
