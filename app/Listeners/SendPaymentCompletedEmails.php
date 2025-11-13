<?php

namespace App\Listeners;

use App\Events\PaymentCompleted;
use App\Services\EmailService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class SendPaymentCompletedEmails
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PaymentCompleted $event): void
    {
        $order = $event->order;
        $paymentIntent = $event->paymentIntent;
        $adminId = $order->admin_id; // Get admin_id from order

        try {
            $emailService = new EmailService();

            // Check order type and send appropriate emails
            if ($order->order_type === 'credit_pack') {
                // Send reseller-specific order confirmation (with admin SMTP if available)
                $this->sendMailWithAdminConfig($order->user->email, new \App\Mail\ResellerOrderConfirmationMail($order), $adminId);
                
                // Send reseller-specific admin notification
                $adminEmails = $emailService->getAdminEmails();
                foreach ($adminEmails as $adminEmail) {
                    Mail::to($adminEmail)->send(new \App\Mail\NewResellerOrderAdminMail($order));
                }
            } elseif ($order->order_type === 'custom_product') {
                // Send custom product order confirmation (with admin SMTP if available)
                $this->sendMailWithAdminConfig($order->user->email, new \App\Mail\CustomProductOrderMail($order), $adminId);
                
                // Send custom product admin notification
                $adminEmails = $emailService->getAdminEmails();
                foreach ($adminEmails as $adminEmail) {
                    Mail::to($adminEmail)->send(new \App\Mail\CustomProductOrderAdminMail($order));
                }
            } else {
                // Send standard order confirmation email to client (with admin SMTP if available)
                $this->sendMailWithAdminConfig($order->user->email, new \App\Mail\NewOrderClientMail($order), $adminId);

                // Send standard new order notification email to admin(s)
                $adminEmails = $emailService->getAdminEmails();
                foreach ($adminEmails as $adminEmail) {
                    Mail::to($adminEmail)->send(new \App\Mail\NewOrderAdminMail($order));
                }
            }

            Log::info('Payment completed emails sent successfully', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'payment_intent_id' => $paymentIntent->payment_intent_id ?? null,
                'customer_email' => $order->user->email,
                'payment_method' => $paymentIntent->payment_method ?? 'unknown',
                'amount' => $order->amount,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send payment completed emails: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'payment_intent_id' => $paymentIntent->payment_intent_id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Try fallback email method
            $this->sendFallbackEmails($order, $paymentIntent);
        }
    }

    /**
     * Send mail with admin-specific SMTP configuration
     */
    private function sendMailWithAdminConfig(string $to, $mailable, ?int $adminId = null): void
    {
        if (!$adminId) {
            // No admin_id, use default
            Mail::to($to)->send($mailable);
            return;
        }

        $admin = User::find($adminId);
        if (!$admin || !$admin->isAdmin()) {
            // Fallback to default
            Mail::to($to)->send($mailable);
            return;
        }

        $config = $admin->getConfig();
        $smtpConfig = $config->smtp_config ?? null;

        if (!$smtpConfig || empty($smtpConfig)) {
            // Fallback to default
            Mail::to($to)->send($mailable);
            return;
        }

        try {
            // Temporarily override mail config for this admin
            $originalConfig = config('mail');
            
            \Illuminate\Support\Facades\Config::set('mail.mailers.smtp', [
                'transport' => $smtpConfig['mailer'] ?? 'smtp',
                'host' => $smtpConfig['host'] ?? config('mail.mailers.smtp.host'),
                'port' => $smtpConfig['port'] ?? config('mail.mailers.smtp.port', 587),
                'encryption' => $smtpConfig['encryption'] ?? config('mail.mailers.smtp.encryption', 'tls'),
                'username' => $smtpConfig['username'] ?? config('mail.mailers.smtp.username'),
                'password' => $smtpConfig['password'] ?? config('mail.mailers.smtp.password'),
                'timeout' => config('mail.mailers.smtp.timeout', 60),
            ]);

            \Illuminate\Support\Facades\Config::set('mail.from', [
                'address' => $smtpConfig['from_address'] ?? config('mail.from.address'),
                'name' => $smtpConfig['from_name'] ?? config('mail.from.name'),
            ]);

            // Send email
            Mail::to($to)->send($mailable);

            // Restore original config
            \Illuminate\Support\Facades\Config::set('mail', $originalConfig);
        } catch (\Exception $e) {
            Log::error("Failed to send email to {$to} using admin SMTP config: " . $e->getMessage());
            // Fallback to default
            Mail::to($to)->send($mailable);
        }
    }

    /**
     * Fallback email method using EmailService
     */
    private function sendFallbackEmails($order, $paymentIntent)
    {
        try {
            $emailService = new EmailService();
            $adminId = $order->admin_id;

            // Check order type and send appropriate emails
            if ($order->order_type === 'credit_pack') {
                // Send reseller-specific order confirmation
                $this->sendMailWithAdminConfig($order->user->email, new \App\Mail\ResellerOrderConfirmationMail($order), $adminId);
                
                // Send reseller-specific admin notification
                $adminEmails = $emailService->getAdminEmails();
                foreach ($adminEmails as $adminEmail) {
                    Mail::to($adminEmail)->send(new \App\Mail\NewResellerOrderAdminMail($order));
                }
            } elseif ($order->order_type === 'custom_product') {
                // Send custom product order confirmation
                $this->sendMailWithAdminConfig($order->user->email, new \App\Mail\CustomProductOrderMail($order), $adminId);
                
                // Send custom product admin notification
                $adminEmails = $emailService->getAdminEmails();
                foreach ($adminEmails as $adminEmail) {
                    Mail::to($adminEmail)->send(new \App\Mail\CustomProductOrderAdminMail($order));
                }
            } else {
                // Fallback email to customer using Mailable
                $this->sendMailWithAdminConfig($order->user->email, new \App\Mail\NewOrderClientMail($order), $adminId);

                // Fallback email to admin using Mailable
                $adminEmails = $emailService->getAdminEmails();
                foreach ($adminEmails as $adminEmail) {
                    Mail::to($adminEmail)->send(new \App\Mail\NewOrderAdminMail($order));
                }
            }

            Log::info('Fallback payment completed emails sent successfully', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
            ]);

        } catch (\Exception $e) {
            Log::error('Fallback payment completed emails also failed: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'error' => $e->getMessage(),
            ]);
        }
    }
}