<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        try {
            if (Schema::hasTable('orders_temp')) {
                DB::statement('DROP TABLE IF EXISTS orders_temp');
            }
        } catch (\Throwable $e) {
            // ignore
        }
    }

    public function down(): void
    {
        // no-op
    }
};


