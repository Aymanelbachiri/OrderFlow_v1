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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_super_admin')->default(false)->after('role');
            $table->unsignedBigInteger('admin_id')->nullable()->after('is_super_admin');
            $table->unsignedBigInteger('created_by_admin_id')->nullable()->after('admin_id');
            
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('created_by_admin_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
            $table->dropForeign(['created_by_admin_id']);
            $table->dropColumn(['is_super_admin', 'admin_id', 'created_by_admin_id']);
        });
    }
};
