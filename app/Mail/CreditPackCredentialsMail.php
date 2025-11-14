<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\User;
use App\Services\SourceMailService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CreditPackCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $user;
    protected SourceMailService $sourceMailService;
    protected $source;
    public $mailerName;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order, User $user)
    {
        $this->order = $order;
        $this->user = $user;
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
            subject: 'IPTV Panel Access - Credit Pack Order #' . $this->order->order_number . ' - ' . $companyName,
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
            view: 'emails.credit-pack-credentials',
            with: [
                'order' => $this->order,
                'user' => $this->user,
                'panelUrl' => $this->order->reseller_login_url,
                'panelUsername' => $this->order->reseller_username,
                'panelPassword' => $this->order->reseller_password,
                'creditPack' => $this->order->resellerCreditPack,
            ] + $sourceVars
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

