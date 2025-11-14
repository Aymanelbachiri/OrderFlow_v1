<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Services\SourceMailService;

class ClientCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $client;
    public $orders;
    public $loginUrl;
    protected SourceMailService $sourceMailService;
    protected $source;
    public $mailerName;

    /**
     * Create a new message instance.
     */
    public function __construct(User $client, $orders)
    {
        $this->client = $client;
        $this->orders = is_array($orders) ? collect($orders) : $orders;
        $this->loginUrl = route('login');
        $this->sourceMailService = new SourceMailService();
        
        // Get source from first order if available
        $firstOrder = $this->orders->first();
        if ($firstOrder) {
            $this->source = $this->sourceMailService->getSource(null, $firstOrder);
            $this->mailerName = $this->sourceMailService->configureMailForSource($this->source);
        }
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $sourceVars = $this->sourceMailService->getEmailVariables($this->source);
        $companyName = $sourceVars['company_name'] ?? config('app.name');
        
        $envelope = new Envelope(
            subject: 'Your IPTV Service Credentials - ' . $companyName,
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
            view: 'emails.client-credentials',
            with: [
                'client' => $this->client,
                'orders' => $this->orders,
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
