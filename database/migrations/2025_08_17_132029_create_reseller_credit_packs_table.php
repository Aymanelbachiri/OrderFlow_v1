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
        Schema::create('reseller_credit_packs', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "100 Credits Pack"
            $table->decimal('price', 10, 2); // Price of the credit pack
            $table->json('features')->nullable();
            $table->json('payment_methods')->nullable();
            $table->integer('credits_amount'); // Number of credits in the pack
            $table->boolean('is_active')->default(true); // Whether the pack is available for purchase
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reseller_credit_packs');
    }
};
