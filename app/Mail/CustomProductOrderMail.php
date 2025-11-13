<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomProductOrderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $customer;
    public $product;
    public $source;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->customer = $order->customer;
        $this->product = $order->customProduct;
        $this->source = $order->sourceModel();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $companyName = $this->source ? $this->source->getCompanyName() : config('app.name');
        return new Envelope(
            subject: 'Order Confirmation - ' . $this->product->name . ' - ' . $companyName,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.custom-product-order',
            with: [
                'order' => $this->order,
                'customer' => $this->customer,
                'product' => $this->product,
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

