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
        Schema::create('admin_configs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id')->unique();
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('cascade');
            
            // Payment configuration (JSON)
            $table->json('payment_config')->nullable();
            
            // SMTP configuration (JSON)
            $table->json('smtp_config')->nullable();
            
            // Other admin-specific settings (JSON)
            $table->json('settings')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_configs');
    }
};
