<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add 'agent' to users.role enum
        // SQLite doesn't enforce enums, but for MySQL compatibility we rebuild the column
        if (DB::getDriverName() === 'sqlite') {
            // SQLite ignores enum constraints; the column is already a string under the hood
        } else {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'client', 'reseller', 'agent') DEFAULT 'client'");
        }

        // Create agent_source pivot table
        Schema::create('agent_source', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('source_id')->constrained('sources')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'source_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agent_source');

        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'client', 'reseller') DEFAULT 'client'");
        }
    }
};
