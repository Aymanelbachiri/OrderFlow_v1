<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;
use App\Services\SourceMailService;

class OrderStatusUpdateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
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
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $sourceVars = $this->sourceMailService->getEmailVariables($this->source);
        $companyName = $sourceVars['company_name'] ?? config('app.name');
        
        $envelope = new Envelope(
            subject: 'Order Status Update - ' . $this->order->order_number . ' - ' . $companyName,
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
            view: 'emails.order-status-update',
            with: [
                'order' => $this->order,
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
