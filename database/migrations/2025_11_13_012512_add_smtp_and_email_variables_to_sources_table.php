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
        Schema::table('sources', function (Blueprint $table) {
            // SMTP Configuration (JSON)
            $table->json('smtp_config')->nullable()->after('is_active');
            
            // Email Template Variables (JSON)
            $table->json('email_variables')->nullable()->after('smtp_config');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sources', function (Blueprint $table) {
            $table->dropColumn(['smtp_config', 'email_variables']);
        });
    }
};
