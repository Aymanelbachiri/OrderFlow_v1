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
            $currentSql = DB::selectOne("SELECT sql FROM sqlite_master WHERE type='table' AND name='pricing_plans'")->sql;
            $newSql = str_replace(
                "\"server_type\" in ('basic', 'premium')",
                "\"server_type\" in ('basic', 'premium', 'generic')",
                $currentSql
            );
            DB::statement('PRAGMA writable_schema = ON');
            DB::statement("UPDATE sqlite_master SET sql = ? WHERE type = 'table' AND name = 'pricing_plans'", [$newSql]);
            DB::statement('PRAGMA writable_schema = OFF');
            DB::statement('PRAGMA integrity_check');
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

        if (DB::getDriverName() === 'sqlite') {
            $currentSql = DB::selectOne("SELECT sql FROM sqlite_master WHERE type='table' AND name='pricing_plans'")->sql;
            $newSql = str_replace(
                "\"server_type\" in ('basic', 'premium', 'generic')",
                "\"server_type\" in ('basic', 'premium')",
                $currentSql
            );
            DB::statement('PRAGMA writable_schema = ON');
            DB::statement("UPDATE sqlite_master SET sql = ? WHERE type = 'table' AND name = 'pricing_plans'", [$newSql]);
            DB::statement('PRAGMA writable_schema = OFF');
            DB::statement('PRAGMA integrity_check');
        } else {
            DB::statement("ALTER TABLE pricing_plans MODIFY COLUMN server_type ENUM('basic', 'premium') NOT NULL");
        }
    }
};
