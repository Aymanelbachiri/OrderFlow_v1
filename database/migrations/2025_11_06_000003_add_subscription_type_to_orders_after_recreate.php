<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Check if column already exists
        if (DB::getDriverName() === 'sqlite') {
            $columns = collect(DB::select("PRAGMA table_info('orders')"))->pluck('name')->toArray();
            if (!in_array('subscription_type', $columns)) {
                // SQLite doesn't support AFTER clause, so add without position
                DB::statement("ALTER TABLE orders ADD COLUMN subscription_type VARCHAR CHECK(subscription_type IN ('new', 'renewal')) DEFAULT 'new'");
            }
        } else {
            if (!Schema::hasColumn('orders', 'subscription_type')) {
                Schema::table('orders', function (Blueprint $table) {
                    $table->enum('subscription_type', ['new', 'renewal'])->default('new')->after('order_type');
                });
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('orders', 'subscription_type')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('subscription_type');
            });
        }
    }
};

