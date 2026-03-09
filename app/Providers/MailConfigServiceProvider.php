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
     * Note: SMTP settings are now configured per-source for client emails.
     * This method sets up a default mailer for admin emails and fallback scenarios.
     * Individual sources configure their own SMTP when sending client emails.
     */
    protected function configureMailSettings(): void
    {
        // Try to get SMTP settings from database first (for backward compatibility)
        $smtpSetting = SmtpSetting::getFirst();

        if ($smtpSetting && $smtpSetting->host && $smtpSetting->from_address) {
            // Use database SMTP settings as default (for admin emails)
        Config::set('mail.mailers.smtp', [
                'transport' => $smtpSetting->mailer ?? 'smtp',
                'host' => $smtpSetting->host,
            'port' => $smtpSetting->port ?? env('MAIL_PORT', 587),
            'encryption' => $smtpSetting->encryption ?? env('MAIL_ENCRYPTION', 'tls'),
                'username' => $smtpSetting->username,
                'password' => $smtpSetting->password,
            'timeout' => env('MAIL_TIMEOUT', 60),
            'local_domain' => env('MAIL_EHLO_DOMAIN'),
            'verify_peer' => env('MAIL_VERIFY_PEER', true),
        ]);

            Config::set('mail.from', [
                'address' => $smtpSetting->from_address,
                'name' => $smtpSetting->from_name ?? config('app.name'),
            ]);

            Config::set('mail.default', $smtpSetting->mailer ?? 'smtp');
        } else {
            // Fallback to environment variables
        Config::set('mail.from', [
                'address' => env('MAIL_FROM_ADDRESS', 'noreply@example.com'),
                'name' => env('MAIL_FROM_NAME', config('app.name')),
        ]);

            // Ensure default SMTP mailer is configured from env
            if (!Config::has('mail.mailers.smtp.host')) {
                Config::set('mail.mailers.smtp', [
                    'transport' => env('MAIL_MAILER', 'smtp'),
                    'host' => env('MAIL_HOST', 'smtp.mailtrap.io'),
                    'port' => env('MAIL_PORT', 587),
                    'encryption' => env('MAIL_ENCRYPTION', 'tls'),
                    'username' => env('MAIL_USERNAME'),
                    'password' => env('MAIL_PASSWORD'),
                    'timeout' => env('MAIL_TIMEOUT', 60),
                    'local_domain' => env('MAIL_EHLO_DOMAIN'),
                    'verify_peer' => env('MAIL_VERIFY_PEER', true),
                ]);
            }
        }
    }
}
