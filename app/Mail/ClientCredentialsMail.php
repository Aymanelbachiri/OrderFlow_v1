<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class ClientCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $client;
    public $orders;
    public $loginUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(User $client, $orders)
    {
        $this->client = $client;
        $this->orders = is_array($orders) ? collect($orders) : $orders;
        $this->loginUrl = route('login');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your IPTV Service Credentials - ' . config('app.name'),
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
