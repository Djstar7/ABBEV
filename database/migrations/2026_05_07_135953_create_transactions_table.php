<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('transaction_id')->unique();
            $table->enum('payment_method', ['cash', 'card', 'mobile', 'paypal', 'freemopay', 'fedapay'])->default('mobile');
            $table->enum('type', ['subscription', 'purchase', 'refund'])->default('subscription');
            $table->decimal('amount', 15, 2);
            $table->decimal('fees', 15, 2)->default(0);
            $table->decimal('net_amount', 15, 2);
            $table->string('currency', 3)->default('XAF');
            $table->string('external_reference')->nullable(); // Ref PayPal/FreeMoPay
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();
            $table->string('payer_email')->nullable();
            $table->string('payer_name')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
