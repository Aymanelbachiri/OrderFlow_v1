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
            $table->foreignId('shield_domain_id')->nullable()->after('is_active')->constrained('shield_domains')->onDelete('set null');
            $table->boolean('use_shield_domain')->default(false)->after('shield_domain_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sources', function (Blueprint $table) {
            $table->dropForeign(['shield_domain_id']);
            $table->dropColumn(['shield_domain_id', 'use_shield_domain']);
        });
    }
};
