<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Affiliate;
use App\Models\Order;
use App\Services\SourceMailService;

class AffiliateCongratulationsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $affiliate;
    public $order;
    public $linkedDevice;
    protected SourceMailService $sourceMailService;
    protected $source;
    public $mailerName;

    /**
     * Create a new message instance.
     */
    public function __construct(Affiliate $affiliate, Order $order)
    {
        $this->affiliate = $affiliate;
        $this->order = $order;
        
        // Configure source-specific SMTP
        $this->sourceMailService = new SourceMailService();
        $this->source = $this->sourceMailService->getSource(null, $order);
        $this->mailerName = $this->sourceMailService->configureMailForSource($this->source);
        
        // Find the linked device
        $this->linkedDevice = null;
        if ($order->devices && is_array($order->devices)) {
            foreach ($order->devices as $index => $device) {
                if ($affiliate->selected_device_id == ($device['id'] ?? $index)) {
                    $this->linkedDevice = $device;
                    $this->linkedDevice['number'] = $index + 1;
                    break;
                }
            }
            // If no specific device selected, use the first one
            if (!$this->linkedDevice && count($order->devices) > 0) {
                $this->linkedDevice = $order->devices[0];
                $this->linkedDevice['number'] = 1;
            }
        }
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $envelope = new Envelope(
            subject: 'Congratulations! Your Referral Reward Has Been Confirmed! 🎉',
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
        return new Content(
            view: 'emails.affiliate-congratulations',
            with: [
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
