<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use App\Models\SmtpSetting;
use Illuminate\Support\Facades\Log;

class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        try {
            $this->configureMailSettings();
        } catch (\Exception $e) {
            // Log error but don't break the application if database is not ready
            Log::warning('Failed to load SMTP settings from database: ' . $e->getMessage());
        }
    }

    /**
     * Configure mail settings from database
     */
    protected function configureMailSettings(): void
    {
        $smtpSetting = SmtpSetting::getFirst();

        if (!$smtpSetting) {
            return;
        }

        // Update mail configuration
        Config::set('mail.mailers.smtp', [
            'transport' => $smtpSetting->mailer,
            'host' => $smtpSetting->host ?? env('MAIL_HOST'),
            'port' => $smtpSetting->port ?? env('MAIL_PORT', 587),
            'encryption' => $smtpSetting->encryption ?? env('MAIL_ENCRYPTION', 'tls'),
            'username' => $smtpSetting->username ?? env('MAIL_USERNAME'),
            'password' => $smtpSetting->password ?? env('MAIL_PASSWORD'),
            'timeout' => env('MAIL_TIMEOUT', 60),
            'local_domain' => env('MAIL_EHLO_DOMAIN'),
        ]);

        // Update mail from settings
        Config::set('mail.from', [
            'address' => $smtpSetting->from_address ?? env('MAIL_FROM_ADDRESS'),
            'name' => $smtpSetting->from_name ?? env('MAIL_FROM_NAME'),
        ]);

        // Update default mailer
        Config::set('mail.default', $smtpSetting->mailer);
    }
}
