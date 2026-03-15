<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            // SQLite enforces CHECK constraints from enum(). We need to
            // recreate the column as a plain string to allow 'agent'.
            DB::statement('PRAGMA foreign_keys = OFF');
            DB::statement('PRAGMA writable_schema = ON');

            // Get current CREATE TABLE SQL and replace the CHECK constraint
            $schema = DB::selectOne("SELECT sql FROM sqlite_master WHERE type='table' AND name='users'");
            if ($schema) {
                $oldSql = $schema->sql;
                // Replace the enum check constraint to include 'agent'
                $newSql = str_replace(
                    "check (\"role\" in ('admin', 'client', 'reseller'))",
                    "check (\"role\" in ('admin', 'client', 'reseller', 'agent'))",
                    $oldSql
                );

                if ($newSql !== $oldSql) {
                    DB::statement("UPDATE sqlite_master SET sql = ? WHERE type='table' AND name='users'", [$newSql]);
                }
            }

            DB::statement('PRAGMA writable_schema = OFF');
            DB::statement('PRAGMA integrity_check');
            DB::statement('PRAGMA foreign_keys = ON');
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF');
            DB::statement('PRAGMA writable_schema = ON');

            $schema = DB::selectOne("SELECT sql FROM sqlite_master WHERE type='table' AND name='users'");
            if ($schema) {
                $oldSql = $schema->sql;
                $newSql = str_replace(
                    "check (\"role\" in ('admin', 'client', 'reseller', 'agent'))",
                    "check (\"role\" in ('admin', 'client', 'reseller'))",
                    $oldSql
                );

                if ($newSql !== $oldSql) {
                    DB::statement("UPDATE sqlite_master SET sql = ? WHERE type='table' AND name='users'", [$newSql]);
                }
            }

            DB::statement('PRAGMA writable_schema = OFF');
            DB::statement('PRAGMA integrity_check');
            DB::statement('PRAGMA foreign_keys = ON');
        }
    }
};
