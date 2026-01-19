<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trial_requests', function (Blueprint $table) {
            $table->timestamp('trial_expires_at')->nullable()->after('credentials_sent_at');
            $table->boolean('followup_sent')->default(false)->after('trial_expires_at');
            $table->timestamp('followup_sent_at')->nullable()->after('followup_sent');
        });
    }

    public function down(): void
    {
        Schema::table('trial_requests', function (Blueprint $table) {
            $table->dropColumn(['trial_expires_at', 'followup_sent', 'followup_sent_at']);
        });
    }
};
