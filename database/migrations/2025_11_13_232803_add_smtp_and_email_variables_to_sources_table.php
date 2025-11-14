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
        Schema::table('sources', function (Blueprint $table) {
            // SMTP Configuration
            $table->string('smtp_mailer')->default('smtp')->after('is_active');
            $table->string('smtp_host')->nullable()->after('smtp_mailer');
            $table->integer('smtp_port')->nullable()->after('smtp_host');
            $table->string('smtp_username')->nullable()->after('smtp_port');
            $table->string('smtp_password')->nullable()->after('smtp_username');
            $table->string('smtp_encryption')->nullable()->after('smtp_password');
            $table->string('smtp_from_address')->nullable()->after('smtp_encryption');
            $table->string('smtp_from_name')->nullable()->after('smtp_from_address');
            
            // Email Template Variables
            $table->string('company_name')->nullable()->after('smtp_from_name');
            $table->string('contact_email')->nullable()->after('company_name');
            $table->string('website')->nullable()->after('contact_email');
            $table->string('phone_number')->nullable()->after('website');
            $table->string('team_name')->nullable()->after('phone_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sources', function (Blueprint $table) {
            $table->dropColumn([
                'smtp_mailer',
                'smtp_host',
                'smtp_port',
                'smtp_username',
                'smtp_password',
                'smtp_encryption',
                'smtp_from_address',
                'smtp_from_name',
                'company_name',
                'contact_email',
                'website',
                'phone_number',
                'team_name',
            ]);
        });
    }
};
