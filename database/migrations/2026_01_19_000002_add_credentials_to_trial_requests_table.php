<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trial_requests', function (Blueprint $table) {
            $table->string('trial_username')->nullable()->after('notes');
            $table->string('trial_password')->nullable()->after('trial_username');
            $table->string('trial_url')->nullable()->after('trial_password');
            $table->boolean('credentials_sent')->default(false)->after('trial_url');
            $table->timestamp('credentials_sent_at')->nullable()->after('credentials_sent');
        });
    }

    public function down(): void
    {
        Schema::table('trial_requests', function (Blueprint $table) {
            $table->dropColumn(['trial_username', 'trial_password', 'trial_url', 'credentials_sent', 'credentials_sent_at']);
        });
    }
};
