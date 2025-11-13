<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Client;

class ClientCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $client;
    public $orders;
    public $loginUrl;
    public $source;

    /**
     * Create a new message instance.
     */
    public function __construct(Client $client, $orders)
    {
        $this->client = $client;
        $this->orders = is_array($orders) ? collect($orders) : $orders;
        $this->loginUrl = route('login');
        
        // Get source from first order if available
        $firstOrder = $this->orders->first();
        $this->source = $firstOrder && method_exists($firstOrder, 'sourceModel') ? $firstOrder->sourceModel() : null;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $companyName = $this->source ? $this->source->getCompanyName() : config('app.name');
        return new Envelope(
            subject: 'Your IPTV Service Credentials - ' . $companyName,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.client-credentials',
            with: [
                'client' => $this->client,
                'orders' => $this->orders,
                'loginUrl' => $this->loginUrl,
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
