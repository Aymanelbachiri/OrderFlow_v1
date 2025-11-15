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
        Schema::create('shield_domains', function (Blueprint $table) {
            $table->id();
            $table->string('domain')->unique(); // e.g., "shield1.com"
            $table->string('template_name'); // e.g., "template-1", "template-2", "template-3"
            $table->enum('status', ['pending', 'active', 'inactive', 'failed'])->default('pending');
            
            // Cloudflare configuration
            $table->string('cloudflare_zone_id')->nullable();
            $table->string('cloudflare_pages_project_id')->nullable();
            $table->json('cloudflare_nameservers')->nullable(); // Array of nameservers
            
            // DNS status
            $table->boolean('dns_configured')->default(false);
            $table->timestamp('dns_configured_at')->nullable();
            
            // Configuration
            $table->json('config')->nullable(); // Custom config per domain (colors, etc.)
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shield_domains');
    }
};
