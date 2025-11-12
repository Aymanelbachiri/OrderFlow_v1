<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if column already exists
        if (!Schema::hasColumn('orders', 'custom_product_id')) {
            Schema::table('orders', function (Blueprint $table) {
                // Add custom_product_id column
                $table->foreignId('custom_product_id')->nullable()->after('pricing_plan_id')->constrained()->onDelete('cascade');
            });
        }
        
        // For SQLite, we can't modify enum directly, so we'll just let the application handle validation
        // The order_type column already allows any string value in SQLite
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['custom_product_id']);
            $table->dropColumn('custom_product_id');
        });
    }
};
