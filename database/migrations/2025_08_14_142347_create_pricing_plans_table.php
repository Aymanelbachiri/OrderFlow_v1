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
        Schema::create('pricing_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Basic 1 Device 1 Month"
            $table->enum('server_type', ['basic', 'premium']);
            $table->enum('plan_type', ['regular', 'reseller'])->default('regular');
            $table->integer('device_count'); // 1, 2, 3, 4
            $table->integer('duration_months'); // 1, 3, 6, 12
            $table->decimal('price', 10, 2);
            $table->text('features')->nullable(); // JSON or text
            $table->string('payment_link')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pricing_plans');
    }
};
