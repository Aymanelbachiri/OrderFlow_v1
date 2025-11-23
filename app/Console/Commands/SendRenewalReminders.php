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
                    // Use date comparison to catch orders expiring today regardless of time
                    $expiresToday = $order->expires_at->isToday();
                    $isExpired = $order->expires_at->isPast();
                    
                    if (!$expiresToday && !$isExpired) {
                        $this->line("  ⊘ Skipping {$order->user->email} (Order #{$order->order_number}) - expires at {$order->expires_at->format('Y-m-d H:i:s')}, not today and not expired");
                        continue; // Skip if not expired and not expiring today
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
                try {
                    $sent = $this->sendRenewalReminder($order, $days);
                    if ($sent) {
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
                        $this->error("  ✗ Failed to send {$days}-day reminder to {$order->user->email} (Order #{$order->order_number})");
                        $this->line("    Reason: sendRenewalReminder returned false - check logs for details");
                    }
                } catch (\Exception $e) {
                    $this->error("  ✗ Exception sending {$days}-day reminder to {$order->user->email} (Order #{$order->order_number})");
                    $this->line("    Error: " . $e->getMessage());
                    \Log::error("Renewal reminder exception", [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                        'email' => $order->user->email,
                        'days' => $days,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
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
            // Validate order has required data
            if (!$order->user) {
                $this->error("    Order #{$order->order_number} has no user associated");
                \Log::error("Renewal reminder: Order missing user", ['order_id' => $order->id]);
                return false;
            }

            if (!$order->pricingPlan && $order->order_type === 'subscription') {
                $this->error("    Order #{$order->order_number} has no pricing plan");
                \Log::error("Renewal reminder: Order missing pricing plan", ['order_id' => $order->id]);
                return false;
            }

            $sourceMailService = new SourceMailService();
            $source = $sourceMailService->getSource(null, $order);
            $sourceVars = $sourceMailService->getEmailVariables($source);
            
            $customerName = $order->user->name;
            $orderNumber = $order->order_number;
            $expiresAt = $order->expires_at ? $order->expires_at->format('M d, Y') : 'N/A';
            $planName = $order->pricingPlan ? $order->pricingPlan->display_name : ($order->customProduct ? $order->customProduct->name : 'N/A');
            
            // Use source data, fallback to app config only if no source
            $companyName = $sourceVars['company_name'] ?? config('app.name');
            $contactEmail = $sourceVars['contact_email'] ?? config('mail.from.address', '');
            $website = $sourceVars['website'] ?? config('app.url', '');
            $phoneNumber = $sourceVars['phone_number'] ?? '';
            $teamName = $sourceVars['team_name'] ?? ($companyName . ' Team');
            $websiteHost = parse_url($website, PHP_URL_HOST) ?: $website;
            
            // Get renewal link - priority: source renewal_url > source return_url + /renew > system setting > default route
            $renewalLink = null;
            
            // First, check if source has a custom renewal_url
            if ($source && !empty($source->renewal_url)) {
                $renewalLink = $source->renewal_url;
            } elseif ($source && !empty($source->return_url)) {
                // Fallback to return_url + /renew if renewal_url not set
                $renewalLink = rtrim($source->return_url, '/') . '/renew';
            } else {
                // Fallback to system setting
                $customRenewalUrl = SystemSetting::get('renewal_link_url', '');
                if (!empty($customRenewalUrl)) {
                    $renewalLink = $customRenewalUrl;
                } else {
                    // Use the default public renewal route, preserving source if available
                    $renewalLink = route('renewal.show', [
                        'orderNumber' => $order->order_number,
                        'email' => $order->user->email,
                        'source' => $order->source ?? null
                    ]);
                }
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

            // Build contact information section from source data
            $contactSection = '';
            if ($contactEmail) {
                $contactSection .= "<p style=\"font-size: 13px; color: #6c757d; margin: 5px 0;\">Support Email: <a href=\"mailto:{$contactEmail}\" style=\"color: #007bff; text-decoration: none;\">{$contactEmail}</a></p>";
            }
            if ($website) {
                $contactSection .= "<p style=\"font-size: 13px; color: #6c757d; margin: 5px 0;\">Website: <a href=\"{$website}\" style=\"color: #007bff; text-decoration: none;\">{$websiteHost}</a></p>";
            }
            if ($phoneNumber) {
                $contactSection .= "<p style=\"font-size: 13px; color: #6c757d; margin: 5px 0;\">Phone: <a href=\"tel:{$phoneNumber}\" style=\"color: #007bff; text-decoration: none;\">{$phoneNumber}</a></p>";
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
                    
                    <p style=\"font-size: 14px; line-height: 1.5; color: #495057; margin: 0 0 20px 0;\">If you have any questions or need assistance, please don't hesitate to reach out to {$teamName}.</p>
                    
                    <div style=\"border-top: 1px solid #dee2e6; padding-top: 15px; margin-top: 25px;\">
                        {$contactSection}
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

            // Skip sending emails in testing environment (unless Mail is faked for testing)
            // The MAIL_MAILER=array in phpunit.xml should prevent actual sending,
            // but we check here to be extra safe
            if (app()->environment('testing') && config('mail.default') !== 'array') {
                // In testing but mail is not configured to use array driver
                // This shouldn't happen if phpunit.xml is correct, but we skip to be safe
                $this->line("  [TEST MODE] Skipping email send to {$order->user->email} (testing environment)");
                return true; // Return true so notification is still recorded
            }
            
            // Configure mailer for source and send email
            $mailerName = $sourceMailService->configureMailForSource($source);
            
            if ($mailerName) {
                // Use source-specific mailer
                $this->line("    Using source mailer: {$mailerName} for source: " . ($source->name ?? 'N/A'));
                
                try {
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
                } catch (\Exception $e) {
                    $this->error("    Failed to send via source mailer {$mailerName}: " . $e->getMessage());
                    \Log::error("Renewal reminder: Source mailer failed", [
                        'order_id' => $order->id,
                        'email' => $order->user->email,
                        'mailer' => $mailerName,
                        'source' => $source->name ?? null,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                    return false;
                }
            } else {
                // Fallback to default mailer
                $this->line("    Using default mailer (no source SMTP configured)");
                
                try {
                    Mail::html($body, function ($message) use ($order, $subject) {
                        $message->to($order->user->email)->subject($subject);
                    });
                } catch (\Exception $e) {
                    $this->error("    Failed to send via default mailer: " . $e->getMessage());
                    \Log::error("Renewal reminder: Default mailer failed", [
                        'order_id' => $order->id,
                        'email' => $order->user->email,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                    return false;
                }
            }

            return true;
        } catch (\Exception $e) {
            $this->error("    Exception in sendRenewalReminder: " . $e->getMessage());
            \Log::error("Renewal reminder: General exception", [
                'order_id' => $order->id ?? null,
                'order_number' => $order->order_number ?? null,
                'email' => $order->user->email ?? null,
                'days' => $days,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return false;
        }
    }
}