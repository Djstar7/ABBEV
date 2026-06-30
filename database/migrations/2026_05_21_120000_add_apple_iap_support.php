<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Support de l'In-App Purchase Apple (abonnement auto-renouvelable) :
 *  - ajoute `apple_iap` aux moyens de paiement de `transactions` ;
 *  - ajoute `apple_product_id` sur `subscription_plans` pour mapper un
 *    plan à son product ID App Store Connect.
 *
 * Même approche que la migration KPay : SQLite ne sait pas modifier une
 * contrainte CHECK, on recrée donc la colonne enum via table temporaire.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->string('apple_product_id')->nullable()->after('order');
        });

        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            $this->rebuildSqlite([
                'payment_method' => ['cash', 'card', 'mobile', 'paypal', 'freemopay', 'fedapay', 'kpay', 'apple_iap'],
                'type'           => ['subscription', 'purchase', 'refund', 'withdrawal'],
            ]);

            return;
        }

        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE transactions DROP CONSTRAINT IF EXISTS transactions_payment_method_check");
            DB::statement("ALTER TABLE transactions ADD CONSTRAINT transactions_payment_method_check CHECK (payment_method::text = ANY (ARRAY['cash','card','mobile','paypal','freemopay','fedapay','kpay','apple_iap']::text[]))");

            return;
        }

        DB::statement("ALTER TABLE transactions MODIFY payment_method ENUM('cash','card','mobile','paypal','freemopay','fedapay','kpay','apple_iap') NOT NULL DEFAULT 'mobile'");
    }

    public function down(): void
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->dropColumn('apple_product_id');
        });

        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            $this->rebuildSqlite([
                'payment_method' => ['cash', 'card', 'mobile', 'paypal', 'freemopay', 'fedapay', 'kpay'],
                'type'           => ['subscription', 'purchase', 'refund', 'withdrawal'],
            ]);

            return;
        }

        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE transactions DROP CONSTRAINT IF EXISTS transactions_payment_method_check");
            DB::statement("ALTER TABLE transactions ADD CONSTRAINT transactions_payment_method_check CHECK (payment_method::text = ANY (ARRAY['cash','card','mobile','paypal','freemopay','fedapay','kpay']::text[]))");

            return;
        }

        DB::statement("ALTER TABLE transactions MODIFY payment_method ENUM('cash','card','mobile','paypal','freemopay','fedapay','kpay') NOT NULL DEFAULT 'mobile'");
    }

    /**
     * Recrée la table transactions sous SQLite avec de nouvelles
     * contraintes enum, en préservant les données.
     *
     * @param array<string,array<int,string>> $enums
     */
    private function rebuildSqlite(array $enums): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('transactions_tmp', function (Blueprint $table) use ($enums) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('transaction_id');
            $table->enum('payment_method', $enums['payment_method'])->default('mobile');
            $table->enum('type', $enums['type'])->default('subscription');
            $table->decimal('amount', 12, 2);
            $table->decimal('fees', 12, 2)->default(0);
            $table->decimal('net_amount', 12, 2);
            $table->string('currency')->default('XAF');
            $table->string('external_reference')->nullable();
            $table->text('description')->nullable();
            $table->text('metadata')->nullable();
            $table->string('payer_email')->nullable();
            $table->string('payer_name')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();
        });

        $columns = 'id, user_id, transaction_id, payment_method, type, amount, fees, net_amount, currency, external_reference, description, metadata, payer_email, payer_name, status, completed_at, created_at, updated_at';
        DB::statement("INSERT INTO transactions_tmp ($columns) SELECT $columns FROM transactions");

        Schema::drop('transactions');
        Schema::rename('transactions_tmp', 'transactions');

        Schema::enableForeignKeyConstraints();
    }
};
