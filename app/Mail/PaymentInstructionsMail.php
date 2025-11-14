<?php

namespace App\Mail;

use App\Services\SourceMailService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class PaymentInstructionsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $paymentUrl;
    public $loginUrl;
    protected SourceMailService $sourceMailService;
    protected $source;
    public $mailerName;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->loginUrl = route('login');
        $this->sourceMailService = new SourceMailService();
        $this->source = $this->sourceMailService->getSource(null, $order);
        $this->mailerName = $this->sourceMailService->configureMailForSource($this->source);
        
        // Generate payment URL based on payment method
        $this->paymentUrl = $this->generatePaymentUrl($order);
    }

    /**
     * Generate payment URL based on payment method
     */
    private function generatePaymentUrl(Order $order): string
    {
        // For manually created orders, create a PaymentIntent first
        $paymentIntent = $this->createPaymentIntentForOrder($order);
        
        switch ($order->payment_method) {
            case 'stripe':
                return route('public.payment.stripe', $paymentIntent);
            case 'paypal':
                return route('public.payment.paypal', $paymentIntent);
            case 'crypto':
                return route('public.payment.crypto', $paymentIntent);
            default:
                return route('public.payment.paypal', $paymentIntent); // Default to PayPal
        }
    }

    /**
     * Create a PaymentIntent for manually created orders
     */
    private function createPaymentIntentForOrder(Order $order): \App\Models\PaymentIntent
    {
        // Check if a PaymentIntent already exists for this order
        $existingIntent = \App\Models\PaymentIntent::where('order_data->order_id', $order->id)->first();
        if ($existingIntent) {
            return $existingIntent;
        }

        // Create new PaymentIntent
        $paymentIntent = \App\Models\PaymentIntent::create([
            'user_id' => $order->user_id,
            'pricing_plan_id' => $order->pricing_plan_id,
            'reseller_credit_pack_id' => $order->reseller_credit_pack_id,
            'payment_intent_id' => 'pi_manual_' . uniqid(),
            'payment_method' => $order->payment_method,
            'amount' => $order->amount,
            'currency' => 'USD',
            'status' => 'pending',
            'order_type' => $order->order_type,
            'order_data' => [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'user_id' => $order->user_id,
                'pricing_plan_id' => $order->pricing_plan_id,
                'reseller_credit_pack_id' => $order->reseller_credit_pack_id,
                'custom_product_id' => $order->custom_product_id,
                'source' => 'admin_manual',
                'order_type' => $order->order_type,
                'customer' => [
                    'name' => $order->user->name,
                    'email' => $order->user->email,
                ],
            ],
            'expires_at' => now()->addDays(7), // Give 7 days to complete payment
        ]);

        return $paymentIntent;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $sourceVars = $this->sourceMailService->getEmailVariables($this->source);
        $companyName = $sourceVars['company_name'] ?? config('app.name');
        
        $envelope = new Envelope(
            subject: 'Payment Instructions - ' . $this->order->order_number . ' - ' . $companyName,
        );

        if ($this->source && $this->source->smtp_from_address) {
            $envelope->from(
                $this->source->smtp_from_address,
                $this->source->smtp_from_name ?? $this->source->company_name ?? config('app.name')
            );
        }

        return $envelope;
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $sourceVars = $this->sourceMailService->getEmailVariables($this->source);
        
        return new Content(
            view: 'emails.payment-instructions',
            with: [
                'order' => $this->order,
                'paymentUrl' => $this->paymentUrl,
                'loginUrl' => $this->loginUrl,
            ] + $sourceVars,
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
