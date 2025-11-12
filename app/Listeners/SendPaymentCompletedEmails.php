<?php

namespace App\Listeners;

use App\Events\PaymentCompleted;
use App\Services\EmailService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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

        try {
            $emailService = new EmailService();

            // Check order type and send appropriate emails
            if ($order->order_type === 'credit_pack') {
                // Send reseller-specific order confirmation
                Mail::to($order->user->email)->send(new \App\Mail\ResellerOrderConfirmationMail($order));
                
                // Send reseller-specific admin notification
                $adminEmails = $emailService->getAdminEmails();
                foreach ($adminEmails as $adminEmail) {
                    Mail::to($adminEmail)->send(new \App\Mail\NewResellerOrderAdminMail($order));
                }
            } elseif ($order->order_type === 'custom_product') {
                // Send custom product order confirmation
                Mail::to($order->user->email)->send(new \App\Mail\CustomProductOrderMail($order));
                
                // Send custom product admin notification
                $adminEmails = $emailService->getAdminEmails();
                foreach ($adminEmails as $adminEmail) {
                    Mail::to($adminEmail)->send(new \App\Mail\CustomProductOrderAdminMail($order));
                }
            } else {
                // Send standard order confirmation email to client
                Mail::to($order->user->email)->send(new \App\Mail\NewOrderClientMail($order));

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
     * Fallback email method using EmailService
     */
    private function sendFallbackEmails($order, $paymentIntent)
    {
        try {
            $emailService = new EmailService();

            // Check order type and send appropriate emails
            if ($order->order_type === 'credit_pack') {
                // Send reseller-specific order confirmation
                Mail::to($order->user->email)->send(new \App\Mail\ResellerOrderConfirmationMail($order));
                
                // Send reseller-specific admin notification
                $adminEmails = $emailService->getAdminEmails();
                foreach ($adminEmails as $adminEmail) {
                    Mail::to($adminEmail)->send(new \App\Mail\NewResellerOrderAdminMail($order));
                }
            } elseif ($order->order_type === 'custom_product') {
                // Send custom product order confirmation
                Mail::to($order->user->email)->send(new \App\Mail\CustomProductOrderMail($order));
                
                // Send custom product admin notification
                $adminEmails = $emailService->getAdminEmails();
                foreach ($adminEmails as $adminEmail) {
                    Mail::to($adminEmail)->send(new \App\Mail\CustomProductOrderAdminMail($order));
                }
            } else {
                // Fallback email to customer using Mailable
                Mail::to($order->user->email)->send(new \App\Mail\NewOrderClientMail($order));

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