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
        if (Schema::hasTable('personal_access_tokens')) {
            Schema::table('personal_access_tokens', function (Blueprint $table) {
                if (!Schema::hasColumn('personal_access_tokens', 'source_id')) {
                    $table->foreignId('source_id')->nullable()->after('tokenable_id')->constrained('sources')->onDelete('set null');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('personal_access_tokens') && Schema::hasColumn('personal_access_tokens', 'source_id')) {
            Schema::table('personal_access_tokens', function (Blueprint $table) {
                $table->dropForeign(['source_id']);
                $table->dropColumn('source_id');
            });
        }
    }
};
