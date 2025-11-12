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
            // Ensure any leftover temp table is removed from a previous failed attempt
            try { Schema::dropIfExists('orders_temp'); } catch (\Throwable $e) {}

            // Create a temporary table with the new structure
            Schema::create('orders_temp', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('pricing_plan_id')->nullable()->constrained()->onDelete('cascade');
                $table->foreignId('custom_product_id')->nullable()->constrained()->onDelete('cascade');
                $table->foreignId('reseller_credit_pack_id')->nullable()->constrained()->onDelete('cascade');
                $table->string('source')->nullable();
                $table->string('order_number')->unique();
                $table->enum('order_type', ['subscription', 'credit_pack', 'custom_product'])->default('subscription');
                $table->enum('status', ['pending', 'active', 'expired', 'canceled', 'completed'])->default('pending');
                $table->decimal('amount', 10, 2);
                $table->string('payment_method')->nullable();
                $table->boolean('payment_email_sent')->default(false);
                $table->string('payment_id')->nullable();
                $table->json('payment_details')->nullable();
                $table->timestamp('starts_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->string('subscription_username')->nullable();
                $table->string('subscription_password')->nullable();
                $table->string('subscription_url')->nullable();
                $table->json('devices')->nullable();
                $table->string('reseller_username')->nullable();
                $table->string('reseller_password')->nullable();
                $table->string('reseller_login_url')->nullable();
                $table->boolean('credentials_sent')->default(false);
                $table->timestamp('credentials_sent_at')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });

            // Copy data from old table to new table (match only common columns to avoid mismatch)
            try {
                // Attempt direct copy first
                DB::statement('INSERT INTO orders_temp SELECT * FROM orders');
            } catch (\Throwable $e) {
                // Fallback: compute common columns and copy only those
                $existingCols = collect(DB::select("PRAGMA table_info('orders')"))->pluck('name')->toArray();
                $tempCols = collect(DB::select("PRAGMA table_info('orders_temp')"))->pluck('name')->toArray();
                $common = array_values(array_intersect($tempCols, $existingCols));

                if (!empty($common)) {
                    $colsList = '"' . implode('", "', $common) . '"';
                    DB::statement("INSERT INTO orders_temp ($colsList) SELECT $colsList FROM orders");
                }
            }

            // Drop old table
            Schema::dropIfExists('orders');

            // Rename new table
            Schema::rename('orders_temp', 'orders');
        } else {
            // For other databases, modify the ENUM constraint
            DB::statement("ALTER TABLE orders MODIFY COLUMN order_type ENUM('subscription', 'credit_pack', 'custom_product') DEFAULT 'subscription'");
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
            Schema::create('orders_temp', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('pricing_plan_id')->constrained()->onDelete('cascade');
                $table->foreignId('custom_product_id')->nullable()->constrained()->onDelete('cascade');
                $table->foreignId('reseller_credit_pack_id')->nullable()->constrained()->onDelete('cascade');
                $table->string('source')->nullable();
                $table->string('order_number')->unique();
                $table->enum('order_type', ['subscription', 'credit_pack'])->default('subscription');
                $table->enum('status', ['pending', 'active', 'expired', 'canceled', 'completed'])->default('pending');
                $table->decimal('amount', 10, 2);
                $table->string('payment_method')->nullable();
                $table->boolean('payment_email_sent')->default(false);
                $table->string('payment_id')->nullable();
                $table->json('payment_details')->nullable();
                $table->timestamp('starts_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->string('subscription_username')->nullable();
                $table->string('subscription_password')->nullable();
                $table->string('subscription_url')->nullable();
                $table->json('devices')->nullable();
                $table->string('reseller_username')->nullable();
                $table->string('reseller_password')->nullable();
                $table->string('reseller_login_url')->nullable();
                $table->boolean('credentials_sent')->default(false);
                $table->timestamp('credentials_sent_at')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });

            // Copy data from current table to temp table (match only common columns)
            try {
                DB::statement("INSERT INTO orders_temp SELECT * FROM orders WHERE order_type != 'custom_product'");
            } catch (\Throwable $e) {
                $existingCols = collect(DB::select("PRAGMA table_info('orders')"))->pluck('name')->toArray();
                $tempCols = collect(DB::select("PRAGMA table_info('orders_temp')"))->pluck('name')->toArray();
                $common = array_values(array_intersect($tempCols, $existingCols));
                if (!empty($common)) {
                    $colsList = '"' . implode('", "', $common) . '"';
                    DB::statement("INSERT INTO orders_temp ($colsList) SELECT $colsList FROM orders WHERE order_type != 'custom_product'");
                }
            }

            // Drop current table
            Schema::dropIfExists('orders');

            // Rename temp table
            Schema::rename('orders_temp', 'orders');
        } else {
            // For other databases, modify the ENUM constraint back
            DB::statement("ALTER TABLE orders MODIFY COLUMN order_type ENUM('subscription', 'credit_pack') DEFAULT 'subscription'");
        }
    }
};