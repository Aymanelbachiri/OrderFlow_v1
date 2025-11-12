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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('pricing_plan_id')->constrained()->onDelete('cascade');
            $table->string('source')->nullable();
            $table->string('order_number')->unique();
            $table->enum('order_type', ['subscription', 'credit_pack'])->default('subscription');
            $table->enum('status', ['pending', 'active', 'expired', 'canceled', 'completed'])->default('pending');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method')->nullable(); // stripe, paypal, crypto, email_link
            $table->boolean('payment_email_sent')->default(false);
            $table->string('payment_id')->nullable(); // External payment ID
            $table->json('payment_details')->nullable(); // Store payment gateway response
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->string('subscription_username')->nullable();
            $table->string('subscription_password')->nullable();
            $table->string('subscription_url')->nullable();
            $table->json('devices')->nullable();
            $table->string('reseller_username')->nullable(); // For reseller orders
            $table->string('reseller_password')->nullable();
            $table->string('reseller_login_url')->nullable();
            $table->boolean('credentials_sent')->default(false);
            $table->timestamp('credentials_sent_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
