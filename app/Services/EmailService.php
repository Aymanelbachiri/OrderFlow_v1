<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailService
{
    protected SourceMailService $sourceMailService;

    public function __construct()
    {
        $this->sourceMailService = new SourceMailService();
    }

    /**
     * Send email using simple HTML content
     * If order is provided, uses source-specific configuration
     */
    public function sendEmail(string $to, string $subject, string $view, array $data = [], ?string $name = null, ?Order $order = null): void
    {
        if ($order) {
            $this->sourceMailService->sendEmailWithOrder($order, $to, $subject, $view, $data, $name);
            return;
        }

        try {
            Mail::send($view, $data, function ($message) use ($to, $subject, $name) {
                $message->to($to, $name)->subject($subject);
            });
        } catch (\Exception $e) {
            Log::error("Failed to send email to {$to}: " . $e->getMessage());
        }
    }
    public function sendEmailToMultiple(string $subject, string $view, array $data, array $recipients): array
    {
        $results = [];
        foreach ($recipients as $recipient) {
            $to = is_array($recipient) ? $recipient['email'] : $recipient;
            $name = is_array($recipient) && isset($recipient['name']) ? $recipient['name'] : null;
            $this->sendEmail($to, $subject, $view, $data, $name);
            $results[] = ['to' => $to, 'status' => 'attempted'];
        }
        return $results;
    }

    /**
     * Send welcome email to new client
     */
    public function sendWelcomeClient(string $clientEmail, string $clientName, string $username, string $password): void
    {
        $subject = 'Welcome to Our Service!';
        $data = [
            'clientName' => $clientName,
            'username' => $username,
            'password' => $password,
        ];
        $this->sendEmail($clientEmail, $subject, 'emails.welcome-client', $data, $clientName);
    }

    /**
     * Send client credentials email
     */
    public function sendClientCredentials(string $clientEmail, string $clientName, string $username, string $password, ?Order $order = null): void
    {
        $subject = 'Your Account Credentials';
        $data = [
            'clientName' => $clientName,
            'username' => $username,
            'password' => $password,
        ];
        $this->sendEmail($clientEmail, $subject, 'emails.client-credentials', $data, $clientName, $order);
    }

    /**
     * Send order confirmation email to client
     */
    public function sendOrderConfirmation(string $clientEmail, string $clientName, string $orderNumber, string $amount, string $paymentMethod, string $planName, string $expiryDate): void
    {
        $subject = 'Order Confirmation - #' . $orderNumber;
        $data = [
            'clientName' => $clientName,
            'orderNumber' => $orderNumber,
            'amount' => $amount,
            'paymentMethod' => $paymentMethod,
            'planName' => $planName,
            'expiryDate' => $expiryDate,
        ];
        $this->sendEmail($clientEmail, $subject, 'emails.order-confirmation', $data, $clientName);
    }

    /**
     * Send new order notification to client
     */
    public function sendNewOrderClient(string $clientEmail, string $clientName, string $orderNumber, string $amount, string $paymentMethod, string $planName, string $orderType, string $status, ?Order $order = null): void
    {
        $subject = 'New Order Received - #' . $orderNumber;
        
        // Use the working order-confirmation template instead
        $data = [
            'order' => (object) [
                'order_number' => $orderNumber,
                'amount' => (float) str_replace(['$', ','], '', $amount),
                'payment_method' => $paymentMethod,
                'order_type' => $orderType,
                'status' => $status,
                'created_at' => (object) ['format' => function($format) { return now()->format($format); }],
                'expires_at' => null,
                'user' => (object) ['name' => $clientName],
                'pricingPlan' => (object) [
                    'display_name' => $planName,
                    'duration_months' => 1,
                    'device_count' => 1,
                    'features' => [],
                ],
            ],
            'loginUrl' => route('login'),
        ];
        
        $this->sendEmail($clientEmail, $subject, 'emails.order-confirmation', $data, $clientName, $order);
    }


    /**
     * Send order status update email
     */
    public function sendOrderStatusUpdate(string $clientEmail, string $clientName, string $orderNumber, string $oldStatus, string $newStatus, string $planName, string $expiryDate, ?Order $order = null): void
    {
        $subject = 'Order Status Update - #' . $orderNumber;
        $data = [
            'clientName' => $clientName,
            'orderNumber' => $orderNumber,
            'oldStatus' => $oldStatus,
            'newStatus' => $newStatus,
            'planName' => $planName,
            'expiryDate' => $expiryDate,
        ];
        $this->sendEmail($clientEmail, $subject, 'emails.order-status-update', $data, $clientName, $order);
    }

    /**
     * Send renewal reminder email
     */
    public function sendRenewalReminder(string $clientEmail, string $clientName, string $orderNumber, string $planName, string $expiryDate, string $renewalUrl, ?Order $order = null): void
    {
        $subject = 'Renewal Reminder - Your IPTV Service Expires Soon';
        $data = [
            'clientName' => $clientName,
            'orderNumber' => $orderNumber,
            'planName' => $planName,
            'expiryDate' => $expiryDate,
            'renewalUrl' => $renewalUrl,
        ];
        $this->sendEmail($clientEmail, $subject, 'emails.renewal-reminder', $data, $clientName, $order);
    }

    /**
     * Send payment confirmation email
     */
    public function sendPaymentConfirmation(string $clientEmail, string $clientName, string $orderNumber, string $amount, string $paymentMethod, string $planName, ?Order $order = null): void
    {
        $subject = 'Payment Confirmed - Order #' . $orderNumber;
        $data = [
            'clientName' => $clientName,
            'orderNumber' => $orderNumber,
            'amount' => $amount,
            'paymentMethod' => $paymentMethod,
            'planName' => $planName,
        ];
        $this->sendEmail($clientEmail, $subject, 'emails.payment-confirmation', $data, $clientName, $order);
    }

    /**
     * Send payment instructions email
     */
    public function sendPaymentInstructions(string $clientEmail, string $clientName, string $orderNumber, string $amount, string $paymentMethod, string $paymentDetails, string $expiryDate, ?Order $order = null): void
    {
        $subject = 'Payment Instructions - Order #' . $orderNumber;
        $data = [
            'clientName' => $clientName,
            'orderNumber' => $orderNumber,
            'amount' => $amount,
            'paymentMethod' => $paymentMethod,
            'paymentDetails' => $paymentDetails,
            'expiryDate' => $expiryDate,
        ];
        $this->sendEmail($clientEmail, $subject, 'emails.payment-instructions', $data, $clientName, $order);
    }

    /**
     * Send password reset email
     */
    public function sendPasswordReset(string $clientEmail, string $clientName, string $resetUrl): void
    {
        $subject = 'Password Reset Request - ' . config('app.name', 'IPTV Platform');
        $data = [
            'clientName' => $clientName,
            'resetUrl' => $resetUrl,
            'siteName' => config('app.name', 'IPTV Platform'),
        ];
        $this->sendEmail($clientEmail, $subject, 'emails.password-reset', $data, $clientName);
    }

    /**
     * Send reseller credentials email
     */
    public function sendResellerCredentials(string $resellerEmail, string $resellerName, string $username, string $password, string $portalUrl): void
    {
        $subject = 'Your Reseller Account Credentials - ' . config('app.name', 'IPTV Platform');
        $data = [
            'resellerName' => $resellerName,
            'username' => $username,
            'password' => $password,
            'portalUrl' => $portalUrl,
            'siteName' => config('app.name', 'IPTV Platform'),
        ];
        $this->sendEmail($resellerEmail, $subject, 'emails.reseller-credentials', $data, $resellerName);
    }

    /**
     * Send reseller order activated email
     */
    public function sendResellerOrderActivated(string $resellerEmail, string $resellerName, string $orderNumber, string $creditPackName, string $creditsAmount, string $expiryDate): void
    {
        $subject = 'Order Activated - #' . $orderNumber;
        $data = [
            'resellerName' => $resellerName,
            'orderNumber' => $orderNumber,
            'creditPackName' => $creditPackName,
            'creditsAmount' => $creditsAmount,
            'expiryDate' => $expiryDate,
        ];
        $this->sendEmail($resellerEmail, $subject, 'emails.reseller-order-activated', $data, $resellerName);
    }

    /**
     * Send new order notification to admin
     */
    public function sendNewOrderAdmin(string $adminEmail, string $customerName, string $customerEmail, string $orderNumber, string $amount, string $paymentMethod, string $planName, string $orderType, string $status): void
    {
        $subject = 'New Order Received - #' . $orderNumber;
        
        // Create simple data structure that matches template expectations
        $data = [
            'customer' => (object) [
                'name' => $customerName,
                'email' => $customerEmail,
                'id' => 0, // Default customer ID
                'created_at' => (object) ['format' => function($format) { return now()->format($format); }],
                'orders' => (object) ['count' => function() { return 1; }],
            ],
            'order' => (object) [
                'id' => 0, // Default order ID
                'order_number' => $orderNumber,
                'amount' => (float) str_replace(['$', ','], '', $amount),
                'payment_method' => $paymentMethod,
                'order_type' => $orderType,
                'status' => $status,
                'payment_id' => null,
                'created_at' => (object) ['format' => function($format) { return now()->format($format); }],
            ],
            'plan' => (object) [
                'display_name' => $planName,
                'duration_months' => 1,
                'device_count' => 1,
                'features' => [],
            ],
        ];
        
        $this->sendEmail($adminEmail, $subject, 'emails.new-order-admin', $data);
    }

    /**
     * Send payment completed notification to admin
     */
    public function sendPaymentCompletedAdmin(string $adminEmail, string $customerName, string $customerEmail, string $orderNumber, string $amount, string $paymentMethod, string $planName, string $orderType, string $paymentId): void
    {
        $subject = 'Payment Completed - Order #' . $orderNumber;
        $data = [
            'customerName' => $customerName,
            'customerEmail' => $customerEmail,
            'orderNumber' => $orderNumber,
            'amount' => $amount,
            'paymentMethod' => $paymentMethod,
            'planName' => $planName,
            'orderType' => $orderType,
            'paymentId' => $paymentId,
        ];
        $this->sendEmail($adminEmail, $subject, 'emails.payment-completed-admin', $data);
    }


    /**
     * Get admin email addresses
     */
    public function getAdminEmails(): array
    {
        $adminEmail = \App\Models\SystemSetting::get('admin_email', '');
        
        if ($adminEmail) {
            return [$adminEmail];
        }

        // Fallback to all admin users
        return \App\Models\User::where('role', 'admin')
                              ->pluck('email')
                              ->toArray();
    }

    /**
     * Resolve admin notification recipients for an order.
     * Returns ['emails' => [...], 'source' => Source|null].
     * If the order's source has its own notify email, returns that; otherwise global admin emails.
     */
    public function resolveAdminRecipientsForOrder(Order $order): array
    {
        if ($order->source) {
            $source = \App\Models\Source::where('name', $order->source)->first();
            if ($source) {
                $notifyEmail = $source->getNotifyEmail();
                if ($notifyEmail) {
                    return [
                        'emails' => [$notifyEmail],
                        'source' => $source,
                    ];
                }
            }
        }

        return [
            'emails' => $this->getAdminEmails(),
            'source' => null,
        ];
    }

    /**
     * Send email to all admins
     */
    public function sendToAdmins(string $subject, string $view, array $data): array
    {
        $adminEmails = $this->getAdminEmails();
        return $this->sendEmailToMultiple($subject, $view, $data, $adminEmails);
    }
}
