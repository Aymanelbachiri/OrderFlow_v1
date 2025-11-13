<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class AccountRenewedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $originalOrder;
    public $source;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order, ?Order $originalOrder = null)
    {
        $this->order = $order;
        $this->originalOrder = $originalOrder;
        $this->source = $order->sourceModel();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $companyName = $this->source ? $this->source->getCompanyName() : config('app.name');
        return new Envelope(
            subject: 'Account Renewed - ' . $this->order->order_number . ' - ' . $companyName,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.account-renewed',
            with: [
                'order' => $this->order,
                'customer' => $this->order->customer,
                'plan' => $this->order->pricingPlan,
                'originalOrder' => $this->originalOrder,
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

