<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sources', function (Blueprint $table) {
            $table->boolean('use_own_notify_email')->default(false)->after('is_active');
            $table->string('notify_email')->nullable()->after('use_own_notify_email');
        });
    }

    public function down(): void
    {
        Schema::table('sources', function (Blueprint $table) {
            $table->dropColumn(['use_own_notify_email', 'notify_email']);
        });
    }
};
