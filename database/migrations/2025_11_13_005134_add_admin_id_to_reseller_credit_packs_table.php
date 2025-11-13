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
        Schema::table('reseller_credit_packs', function (Blueprint $table) {
            $table->unsignedBigInteger('admin_id')->nullable()->after('id');
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('admin_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reseller_credit_packs', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
            $table->dropIndex(['admin_id']);
            $table->dropColumn('admin_id');
        });
    }
};
