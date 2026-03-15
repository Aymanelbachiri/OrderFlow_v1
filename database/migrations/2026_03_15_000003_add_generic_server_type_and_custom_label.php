<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            // SQLite ignores enum constraints; the column is already a string
        } else {
            DB::statement("ALTER TABLE pricing_plans MODIFY COLUMN server_type ENUM('basic', 'premium', 'generic') NOT NULL");
        }

        Schema::table('pricing_plans', function (Blueprint $table) {
            $table->string('custom_label')->nullable()->after('server_type');
        });
    }

    public function down(): void
    {
        Schema::table('pricing_plans', function (Blueprint $table) {
            $table->dropColumn('custom_label');
        });

        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE pricing_plans MODIFY COLUMN server_type ENUM('basic', 'premium') NOT NULL");
        }
    }
};
