<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResellerCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $user;
    public $source;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order, Client $user)
    {
        $this->order = $order;
        $this->user = $user;
        $this->source = $order->sourceModel();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $companyName = $this->source ? $this->source->getCompanyName() : config('app.name');
        return new Envelope(
            subject: 'Your IPTV Reseller Credentials - Order #' . $this->order->order_number . ' - ' . $companyName,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reseller-credentials',
            with: [
                'order' => $this->order,
                'user' => $this->user,
                'panelUrl' => $this->order->reseller_login_url,
                'panelUsername' => $this->order->reseller_username,
                'panelPassword' => $this->order->reseller_password,
                'creditPack' => $this->order->resellerCreditPack,
                'source' => $this->source,
            ]
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
