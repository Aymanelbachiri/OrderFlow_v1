<?php

namespace App\Listeners;

use App\Events\PaymentCompleted;
use App\Models\Order;
use App\Services\EmailService;
use App\Services\SourceMailService;
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
                try {
                    $resellerMail = new \App\Mail\ResellerOrderConfirmationMail($order);
                    if ($resellerMail->mailerName) {
                        Mail::mailer($resellerMail->mailerName)->to($order->user->email)->send($resellerMail);
                    } else {
                        Mail::to($order->user->email)->send($resellerMail);
                    }
                } catch (\Exception $resellerEmailError) {
                    Log::error('Failed to send reseller order confirmation email: ' . $resellerEmailError->getMessage(), [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                    ]);
                }
                
                // Send reseller-specific admin notification
                try {
                    $this->sendAdminNotification($order, new \App\Mail\NewResellerOrderAdminMail($order));
                } catch (\Exception $adminEmailError) {
                    Log::error('Failed to send reseller admin notification email: ' . $adminEmailError->getMessage(), [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                    ]);
                }
            } elseif ($order->order_type === 'custom_product') {
                // Send custom product order confirmation
                try {
                    $customProductMail = new \App\Mail\CustomProductOrderMail($order);
                    if ($customProductMail->mailerName) {
                        Mail::mailer($customProductMail->mailerName)->to($order->user->email)->send($customProductMail);
                    } else {
                        Mail::to($order->user->email)->send($customProductMail);
                    }
                } catch (\Exception $customProductEmailError) {
                    Log::error('Failed to send custom product order confirmation email: ' . $customProductEmailError->getMessage(), [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                    ]);
                }
                
                // Send custom product admin notification
                try {
                    $this->sendAdminNotification($order, new \App\Mail\CustomProductOrderAdminMail($order));
                } catch (\Exception $adminEmailError) {
                    Log::error('Failed to send custom product admin notification email: ' . $adminEmailError->getMessage(), [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                    ]);
                }
            } else {
                // Send standard order confirmation email to client
                try {
                    $clientMail = new \App\Mail\NewOrderClientMail($order);
                    if ($clientMail->mailerName) {
                        Mail::mailer($clientMail->mailerName)->to($order->user->email)->send($clientMail);
                    } else {
                        Mail::to($order->user->email)->send($clientMail);
                    }
                } catch (\Exception $clientEmailError) {
                    Log::error('Failed to send client order confirmation email: ' . $clientEmailError->getMessage(), [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                    ]);
                }

                // Send standard new order notification email to admin(s)
                try {
                    $this->sendAdminNotification($order, new \App\Mail\NewOrderAdminMail($order));
                } catch (\Exception $adminEmailError) {
                    Log::error('Failed to send admin order notification email: ' . $adminEmailError->getMessage(), [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                        'error' => $adminEmailError->getMessage(),
                    ]);
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
     * Send admin notification email, routing to source-specific email when configured.
     */
    private function sendAdminNotification(Order $order, \Illuminate\Mail\Mailable $mailable, string $context = ''): void
    {
        $emailService = new EmailService();
        $recipients = $emailService->resolveAdminRecipientsForOrder($order);
        $adminEmails = $recipients['emails'];
        $orderSource = $recipients['source'];

        if (empty($adminEmails)) {
            Log::warning("{$context}No admin emails found to send notification to", [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
            ]);
            return;
        }

        $sourceMailService = new SourceMailService();

        if ($orderSource) {
            $mailerName = $sourceMailService->configureMailForSource($orderSource);
        } else {
            $mainSource = \App\Models\Source::where('name', 'main')->first();
            $mailerName = $sourceMailService->configureMailForSource($mainSource);
        }

        foreach ($adminEmails as $adminEmail) {
            try {
                Log::info("{$context}Sending admin email to: {$adminEmail}", [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'using_source' => $orderSource ? $orderSource->name : ($mailerName ? 'main' : 'default'),
                ]);

                $freshMailable = clone $mailable;

                if ($mailerName) {
                    Mail::mailer($mailerName)->to($adminEmail)->send($freshMailable);
                } else {
                    Mail::to($adminEmail)->send($freshMailable);
                }

                Log::info("{$context}Admin email sent successfully to: {$adminEmail}", [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                ]);
            } catch (\Exception $e) {
                Log::error("{$context}Failed to send admin email to {$adminEmail}: " . $e->getMessage(), [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'admin_email' => $adminEmail,
                    'error' => $e->getMessage(),
                ]);
            }
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
                try {
                    $resellerMail = new \App\Mail\ResellerOrderConfirmationMail($order);
                    if ($resellerMail->mailerName) {
                        Mail::mailer($resellerMail->mailerName)->to($order->user->email)->send($resellerMail);
                    } else {
                        Mail::to($order->user->email)->send($resellerMail);
                    }
                } catch (\Exception $resellerEmailError) {
                    Log::error('Fallback: Failed to send reseller order confirmation email: ' . $resellerEmailError->getMessage());
                }
                
                // Send reseller-specific admin notification
                try {
                    $this->sendAdminNotification($order, new \App\Mail\NewResellerOrderAdminMail($order), 'Fallback: ');
                } catch (\Exception $adminEmailError) {
                    Log::error('Fallback: Failed to send reseller admin notification email: ' . $adminEmailError->getMessage());
                }
            } elseif ($order->order_type === 'custom_product') {
                // Send custom product order confirmation
                try {
                    $customProductMail = new \App\Mail\CustomProductOrderMail($order);
                    if ($customProductMail->mailerName) {
                        Mail::mailer($customProductMail->mailerName)->to($order->user->email)->send($customProductMail);
                    } else {
                        Mail::to($order->user->email)->send($customProductMail);
                    }
                } catch (\Exception $customProductEmailError) {
                    Log::error('Fallback: Failed to send custom product order confirmation email: ' . $customProductEmailError->getMessage());
                }
                
                // Send custom product admin notification
                try {
                    $this->sendAdminNotification($order, new \App\Mail\CustomProductOrderAdminMail($order), 'Fallback: ');
                } catch (\Exception $adminEmailError) {
                    Log::error('Fallback: Failed to send custom product admin notification email: ' . $adminEmailError->getMessage());
                }
            } else {
                // Fallback email to customer using Mailable
                try {
                    $clientMail = new \App\Mail\NewOrderClientMail($order);
                    if ($clientMail->mailerName) {
                        Mail::mailer($clientMail->mailerName)->to($order->user->email)->send($clientMail);
                    } else {
                        Mail::to($order->user->email)->send($clientMail);
                    }
                } catch (\Exception $clientEmailError) {
                    Log::error('Fallback: Failed to send client order confirmation email: ' . $clientEmailError->getMessage());
                }

                // Fallback email to admin using Mailable
                try {
                    $this->sendAdminNotification($order, new \App\Mail\NewOrderAdminMail($order), 'Fallback: ');
                } catch (\Exception $adminEmailError) {
                    Log::error('Fallback: Failed to send admin order notification email: ' . $adminEmailError->getMessage(), [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                        'error' => $adminEmailError->getMessage(),
                    ]);
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