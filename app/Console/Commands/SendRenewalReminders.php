<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\RenewalNotification;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Mail;
use App\Services\SourceMailService;

class SendRenewalReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'iptv:send-renewal-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send renewal reminder emails to customers with expiring subscriptions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting renewal reminder process...');

        // Get reminder days from settings (default: 7,3,0)
        $reminderDays = explode(',', SystemSetting::get('renewal_reminder_days', '7,3,0'));
        $reminderDays = array_map('trim', $reminderDays);
        $reminderDays = array_map('intval', $reminderDays);

        $this->info('Configured reminder days: ' . implode(', ', $reminderDays));
        $totalSent = 0;

        foreach ($reminderDays as $days) {
            $this->info("Processing {$days}-day reminders...");

            if ($days === 0) {
                // Handle expired orders (0 days) - orders that expire TODAY or are already expired
                $orders = Order::where('status', 'active')
                    ->whereDate('expires_at', '<=', now()->toDateString())
                    ->where('order_type', 'subscription') // Only subscription orders, not credit packs
                    ->with(['user', 'pricingPlan'])
                    ->get();
            } else {
                // Handle future expiry reminders - orders that expire in approximately X days
                // Use a range to handle fractional days
                $startDate = now()->addDays($days - 0.9)->toDateString();
                $endDate = now()->addDays($days + 0.9)->toDateString();
                
                $orders = Order::where('status', 'active')
                    ->whereBetween('expires_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                    ->where('order_type', 'subscription') // Only subscription orders, not credit packs
                    ->with(['user', 'pricingPlan'])
                    ->get();
            }
            
            // Filter out orders that have been renewed
            // An order is considered renewed if:
            // 1. It's already marked as completed, OR
            // 2. There's a newer active/pending renewal order for the same user
            $orders = $orders->filter(function ($order) {
                // Skip if already completed
                if ($order->status === 'completed') {
                    return false;
                }
                
                // Skip if this order itself is a renewal (renewal orders don't need renewal reminders)
                if ($order->subscription_type === 'renewal') {
                    return false;
                }
                
                // Check if there's a newer renewal order for this user that is active or pending
                $hasRenewal = Order::where('user_id', $order->user_id)
                    ->where('subscription_type', 'renewal')
                    ->where('id', '>', $order->id) // Newer order
                    ->whereIn('status', ['active', 'pending']) // Active or pending renewal
                    ->exists();
                
                return !$hasRenewal;
            });

            $this->info("Found " . $orders->count() . " orders for {$days}-day reminder");

            foreach ($orders as $order) {
                // Calculate actual days until expiry
                $actualDays = now()->diffInDays($order->expires_at, false);
                
                // Check if this order should get this specific reminder
                if ($days === 0) {
                    // For 0-day (expired) reminders, check if order is expired or expires today
                    if ($actualDays > 0) {
                        continue; // Skip if not expired yet
                    }
                } else {
                    // For future reminders, check if actual days is within acceptable range
                    // Accept orders that expire within days ± 1 day (e.g., for 3-day reminder, accept 2.0 to 4.0 days)
                    $minDays = $days - 1.0;
                    $maxDays = $days + 1.0;
                    
                    if ($actualDays < $minDays || $actualDays > $maxDays) {
                        continue; // Skip if not within acceptable range
                    }
                }

                // Check if reminder already sent for this specific day
                $existingNotification = RenewalNotification::where('order_id', $order->id)
                    ->where('days_before_expiry', $days)
                    ->where('sent', true)
                    ->first();

                if ($existingNotification) {
                    $this->line("  ✓ Skipping {$order->user->email} - already sent {$days}-day reminder");
                    continue; // Already sent this specific reminder
                }

                // Send reminder email
                if ($this->sendRenewalReminder($order, $days)) {
                    // Record the notification
                    RenewalNotification::create([
                        'order_id' => $order->id,
                        'days_before_expiry' => $days,
                        'sent' => true,
                        'sent_at' => now(),
                    ]);

                    $totalSent++;
                    $this->line("  ✓ Sent {$days}-day reminder to {$order->user->email} (Order #{$order->order_number})");
                } else {
                    $this->error("  ✗ Failed to send {$days}-day reminder to {$order->user->email}");
                }
            }
        }

        $this->info("Renewal reminder process completed. Sent {$totalSent} reminders total.");
        return 0;
    }

    /**
     * Send renewal reminder email
     */
    private function sendRenewalReminder(Order $order, int $days): bool
    {
        try {
            $sourceMailService = new SourceMailService();
            $source = $sourceMailService->getSource(null, $order);
            $sourceVars = $sourceMailService->getEmailVariables($source);
            
            $customerName = $order->user->name;
            $orderNumber = $order->order_number;
            $expiresAt = $order->expires_at->format('M d, Y');
            $planName = $order->pricingPlan->display_name;
            $companyName = $sourceVars['company_name'] ?? config('app.name');
            $contactEmail = $sourceVars['contact_email'] ?? 'contact@smarters-proiptv.com';
            $website = $sourceVars['website'] ?? config('app.url', 'http://smarters-proiptv.com');
            $websiteHost = parse_url($website, PHP_URL_HOST) ?: $website;
            
            // Get renewal link from settings or use default
            $customRenewalUrl = SystemSetting::get('renewal_link_url', '');
            if (!empty($customRenewalUrl)) {
                $renewalLink = $customRenewalUrl;
            } else {
                // Use the new public renewal route, preserving source if available
                $renewalLink = route('renewal.show', [
                    'orderNumber' => $order->order_number,
                    'email' => $order->user->email,
                    'source' => $order->source ?? null
                ]);
            }

            // Determine email content based on days
            if ($days === 0) {
                $subject = 'Service Expired - Renew Your IPTV Subscription';
                $daysText = 'Your service has expired today';
                $urgencyText = 'Your IPTV service has expired. Please renew immediately to restore access.';
            } else {
                $subject = 'Renewal Reminder - Your IPTV Service Expires in ' . $days . ' day' . ($days > 1 ? 's' : '');
                $daysText = "Your service expires in {$days} day" . ($days > 1 ? 's' : '');
                $urgencyText = 'Please renew your subscription to continue enjoying our services.';
            }

            $body = "
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset=\"utf-8\">
                    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
                    <title>Subscription Renewal Notice</title>
                </head>
                <body style=\"margin: 0; padding: 0; background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;\">
                <div style=\"max-width: 600px; margin: 20px auto; background: #ffffff; border: 1px solid #e9ecef; border-radius: 4px; overflow: hidden;\">
                
                <!-- Header -->
                <div style=\"background-color: #495057; padding: 20px; text-align: center;\">
                    <h1 style=\"color: #ffffff; margin: 0; font-size: 20px; font-weight: 600;\">{$companyName}</h1>
                </div>
                
                <!-- Content -->
                <div style=\"padding: 30px 25px;\">
                    <h2 style=\"color: #212529; font-size: 18px; margin: 0 0 20px 0; font-weight: 600;\">Subscription Renewal Notice</h2>
                    
                    <p style=\"font-size: 14px; line-height: 1.5; color: #495057; margin: 0 0 15px 0;\">Dear {$customerName},</p>
                    
                    <p style=\"font-size: 14px; line-height: 1.5; color: #495057; margin: 0 0 20px 0;\">We hope this message finds you well. This is a friendly reminder regarding your subscription with us.</p>
                    
                    <div style=\"background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 4px; padding: 15px; margin: 20px 0;\">
                        <h3 style=\"color: #495057; font-size: 14px; margin: 0 0 10px 0; font-weight: 600;\">Account Information</h3>
                        <p style=\"font-size: 13px; color: #6c757d; margin: 2px 0;\"><strong>Order Reference:</strong> {$orderNumber}</p>
                        <p style=\"font-size: 13px; color: #6c757d; margin: 2px 0;\"><strong>Service Plan:</strong> {$planName}</p>
                        <p style=\"font-size: 13px; color: #6c757d; margin: 2px 0;\"><strong>Expiration Date:</strong> {$expiresAt}</p>
                    </div>
                    
                    <p style=\"font-size: 14px; line-height: 1.5; color: #495057; margin: 0 0 20px 0;\">{$urgencyText}</p>
                    
                    <div style=\"text-align: center; margin: 25px 0;\">
                        <a href=\"{$renewalLink}\" style=\"display: inline-block; background-color: #007bff; color: #ffffff; text-decoration: none; font-size: 14px; font-weight: 500; padding: 12px 30px; border-radius: 4px; border: none;\">Continue Service</a>
                    </div>
                    
                    <p style=\"font-size: 14px; line-height: 1.5; color: #495057; margin: 0 0 20px 0;\">If you have any questions or need assistance, please don't hesitate to reach out to our support team.</p>
                    
                    <div style=\"border-top: 1px solid #dee2e6; padding-top: 15px; margin-top: 25px;\">
                        <p style=\"font-size: 13px; color: #6c757d; margin: 5px 0;\">Support Email: <a href=\"mailto:{$contactEmail}\" style=\"color: #007bff; text-decoration: none;\">{$contactEmail}</a></p>
                        <p style=\"font-size: 13px; color: #6c757d; margin: 5px 0;\">Website: <a href=\"{$website}\" style=\"color: #007bff; text-decoration: none;\">{$websiteHost}</a></p>
                    </div>
                </div>
                
                <!-- Footer -->
                <div style=\"background-color: #f8f9fa; padding: 15px 25px; text-align: center; border-top: 1px solid #dee2e6;\">
                    <p style=\"font-size: 12px; color: #6c757d; margin: 0;\">This is an automated message. If you have already renewed your subscription, please disregard this notice.</p>
                    <p style=\"font-size: 12px; color: #6c757d; margin: 5px 0 0 0;\">&copy; 2025 {$companyName}. All rights reserved.</p>
                </div>
                
                </div>
                </body>
                </html>
            ";

            // Configure mailer for source and send email
            $mailerName = $sourceMailService->configureMailForSource($source);
            
            if ($mailerName) {
                // Use source-specific mailer
                Mail::mailer($mailerName)->html($body, function ($message) use ($order, $subject, $source, $sourceVars) {
                    $message->to($order->user->email)->subject($subject);
                    
                    // Set from address if source is configured
                    if ($source && $source->smtp_from_address) {
                        $message->from(
                            $source->smtp_from_address,
                            $source->smtp_from_name ?? $source->company_name ?? $sourceVars['company_name'] ?? config('app.name')
                        );
                    }
                });
            } else {
                // Fallback to default mailer
                Mail::html($body, function ($message) use ($order, $subject) {
                    $message->to($order->user->email)->subject($subject);
                });
            }

            return true;
        } catch (\Exception $e) {
            $this->error("Failed to send reminder to {$order->user->email}: " . $e->getMessage());
            return false;
        }
    }
}