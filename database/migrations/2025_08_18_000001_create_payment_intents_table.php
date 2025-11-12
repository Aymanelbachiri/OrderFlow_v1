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
        Schema::create('payment_intents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('pricing_plan_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('reseller_credit_pack_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('payment_intent_id')->unique(); // Gateway payment intent ID
            $table->string('payment_method'); // stripe, paypal, crypto, etc.
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->enum('status', ['pending', 'completed', 'failed', 'expired', 'processed'])->default('pending');
            $table->enum('order_type', ['subscription', 'credit_pack'])->default('subscription');
            $table->json('order_data'); // Store order details to create later
            $table->json('gateway_response')->nullable(); // Store gateway response data
            $table->timestamp('expires_at')->nullable(); // When payment intent expires
            $table->timestamp('completed_at')->nullable(); // When payment was completed
            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'status']);
            $table->index(['payment_method', 'status']);
            $table->index(['status', 'expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_intents');
    }
};
