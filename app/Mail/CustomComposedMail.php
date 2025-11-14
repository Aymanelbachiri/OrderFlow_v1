<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\Source;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomComposedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $subject;
    public $htmlContent;
    protected $source;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order, string $subject, string $htmlContent, ?Source $source = null)
    {
        $this->order = $order;
        $this->subject = $subject;
        $this->htmlContent = $htmlContent;
        $this->source = $source;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $envelope = new Envelope(
            subject: $this->subject,
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
            htmlString: $this->htmlContent,
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

