<?php

namespace App\Mail;

use App\Models\Order;
use App\Services\SourceMailService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResellerOrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    protected SourceMailService $sourceMailService;
    protected $source;
    public $mailerName;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->sourceMailService = new SourceMailService();
        $this->source = $this->sourceMailService->getSource(null, $order);
        $this->mailerName = $this->sourceMailService->configureMailForSource($this->source);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $sourceVars = $this->sourceMailService->getEmailVariables($this->source);
        $companyName = $sourceVars['company_name'] ?? config('app.name');
        
        $envelope = new Envelope(
            subject: 'Reseller Panel Account Setup - Order #' . $this->order->order_number . ' - ' . $companyName,
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
            view: 'emails.reseller-order-confirmation',
            with: [
                'order' => $this->order,
                'user' => $this->order->user,
                'creditPack' => $this->order->resellerCreditPack,
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

