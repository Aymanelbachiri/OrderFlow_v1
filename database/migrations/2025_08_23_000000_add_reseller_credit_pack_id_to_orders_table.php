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
        // Check if column already exists
        if (!Schema::hasColumn('orders', 'reseller_credit_pack_id')) {
            Schema::table('orders', function (Blueprint $table) {
                // Add reseller_credit_pack_id column
                $table->foreignId('reseller_credit_pack_id')->nullable()->after('pricing_plan_id')->constrained()->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['reseller_credit_pack_id']);
            $table->dropColumn('reseller_credit_pack_id');
        });
    }
};
