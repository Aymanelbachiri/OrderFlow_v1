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
        Schema::create('admin_permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id')->unique();
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('cascade');
            
            // Permission flags
            $table->boolean('can_manage_sources')->default(false);
            $table->boolean('can_create_custom_products')->default(false);
            $table->boolean('can_send_renewal_emails')->default(false);
            $table->boolean('can_manage_pricing_plans')->default(true);
            $table->boolean('can_manage_reseller_credit_packs')->default(false);
            $table->boolean('can_manage_payment_config')->default(true);
            $table->boolean('can_view_orders')->default(true);
            $table->boolean('can_manage_orders')->default(true);
            
            // Limits
            $table->integer('max_sources')->nullable();
            $table->integer('max_custom_products')->nullable();
            $table->integer('max_reseller_credit_packs')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_permissions');
    }
};
