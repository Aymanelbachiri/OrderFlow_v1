<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Copy existing SMTP settings to all existing sources
     */
    public function up(): void
    {
        // Only run if both tables exist
        if (!Schema::hasTable('sources') || !Schema::hasTable('smtp_settings')) {
            return;
        }

        // Check if sources table has the new SMTP columns
        if (!Schema::hasColumn('sources', 'smtp_host')) {
            return;
        }

        // Get existing SMTP settings
        $smtpSetting = DB::table('smtp_settings')->first();

        if ($smtpSetting) {
            // Update all existing sources with the SMTP settings
            DB::table('sources')->update([
                'smtp_mailer' => $smtpSetting->mailer ?? 'smtp',
                'smtp_host' => $smtpSetting->host,
                'smtp_port' => $smtpSetting->port,
                'smtp_username' => $smtpSetting->username,
                'smtp_password' => $smtpSetting->password,
                'smtp_encryption' => $smtpSetting->encryption,
                'smtp_from_address' => $smtpSetting->from_address,
                'smtp_from_name' => $smtpSetting->from_name,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration only copies data, no need to reverse
        // The columns will be dropped by the previous migration's down() method
    }
};
