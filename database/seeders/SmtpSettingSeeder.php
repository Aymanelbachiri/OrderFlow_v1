<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SmtpSetting;

class SmtpSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default SMTP settings if none exist
        SmtpSetting::updateOrCreate(
            ['id' => 1], // Use ID to ensure only one record exists
            [
                'mailer' => 'smtp',
                'host' => env('MAIL_HOST', 'smtp.gmail.com'),
                'port' => env('MAIL_PORT', 587),
                'username' => env('MAIL_USERNAME', ''),
                'password' => env('MAIL_PASSWORD', ''),
                'encryption' => env('MAIL_ENCRYPTION', 'tls'),
                'from_address' => env('MAIL_FROM_ADDRESS', 'noreply@yourdomain.com'),
                'from_name' => env('MAIL_FROM_NAME', config('app.name', 'IPTV Platform')),
            ]
        );
    }
}
