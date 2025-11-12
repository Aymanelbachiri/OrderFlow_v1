<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For SQLite, we need to recreate the table to modify the ENUM constraint
        if (DB::getDriverName() === 'sqlite') {
            // Create a temporary table with the new structure
            Schema::create('payment_intents_temp', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('pricing_plan_id')->nullable()->constrained()->onDelete('cascade');
                $table->foreignId('reseller_credit_pack_id')->nullable()->constrained()->onDelete('cascade');
                $table->string('payment_intent_id')->unique();
                $table->string('payment_method');
                $table->decimal('amount', 10, 2);
                $table->string('currency', 3)->default('USD');
                $table->enum('status', ['pending', 'completed', 'failed', 'expired', 'processed'])->default('pending');
                $table->enum('order_type', ['subscription', 'credit_pack', 'custom_product'])->default('subscription');
                $table->json('order_data');
                $table->json('gateway_response')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();

                // Indexes
                $table->index(['user_id', 'status']);
                $table->index(['payment_method', 'status']);
                $table->index(['status', 'expires_at']);
            });

            // Copy data from old table to new table
            DB::statement('INSERT INTO payment_intents_temp SELECT * FROM payment_intents');

            // Drop old table
            Schema::dropIfExists('payment_intents');

            // Rename new table
            Schema::rename('payment_intents_temp', 'payment_intents');
        } else {
            // For other databases, modify the ENUM constraint
            DB::statement("ALTER TABLE payment_intents MODIFY COLUMN order_type ENUM('subscription', 'credit_pack', 'custom_product') DEFAULT 'subscription'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // For SQLite, recreate the table with the old structure
        if (DB::getDriverName() === 'sqlite') {
            // Create a temporary table with the old structure
            Schema::create('payment_intents_temp', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('pricing_plan_id')->nullable()->constrained()->onDelete('cascade');
                $table->foreignId('reseller_credit_pack_id')->nullable()->constrained()->onDelete('cascade');
                $table->string('payment_intent_id')->unique();
                $table->string('payment_method');
                $table->decimal('amount', 10, 2);
                $table->string('currency', 3)->default('USD');
                $table->enum('status', ['pending', 'completed', 'failed', 'expired', 'processed'])->default('pending');
                $table->enum('order_type', ['subscription', 'credit_pack'])->default('subscription');
                $table->json('order_data');
                $table->json('gateway_response')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();

                // Indexes
                $table->index(['user_id', 'status']);
                $table->index(['payment_method', 'status']);
                $table->index(['status', 'expires_at']);
            });

            // Copy data from current table to temp table (excluding custom_product orders)
            DB::statement("INSERT INTO payment_intents_temp SELECT * FROM payment_intents WHERE order_type != 'custom_product'");

            // Drop current table
            Schema::dropIfExists('payment_intents');

            // Rename temp table
            Schema::rename('payment_intents_temp', 'payment_intents');
        } else {
            // For other databases, modify the ENUM constraint back
            DB::statement("ALTER TABLE payment_intents MODIFY COLUMN order_type ENUM('subscription', 'credit_pack') DEFAULT 'subscription'");
        }
    }
};