<?php

namespace App\Mail;

use App\Models\Order;
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
    public $source;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->source = $order->sourceModel();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $companyName = $this->source ? $this->source->getCompanyName() : config('app.name');
        return new Envelope(
            subject: 'Reseller Panel Account Setup - Order #' . $this->order->order_number . ' - ' . $companyName,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reseller-order-confirmation',
            with: [
                'order' => $this->order,
                'user' => $this->order->customer,
                'creditPack' => $this->order->resellerCreditPack,
                'source' => $this->source,
            ],
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

