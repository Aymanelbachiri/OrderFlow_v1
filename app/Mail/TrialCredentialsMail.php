<?php

namespace App\Mail;

use App\Models\TrialRequest;
use App\Models\Source;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TrialCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public TrialRequest $trialRequest,
        public Source $source
    ) {}

    public function envelope(): Envelope
    {
        $companyName = $this->source->company_name ?? $this->source->name ?? config('app.name');
        
        return new Envelope(
            from: new \Illuminate\Mail\Mailables\Address(
                $this->source->smtp_from_address,
                $this->source->smtp_from_name ?? $companyName
            ),
            subject: "Your Trial Credentials - {$companyName}",
        );
    }

    public function content(): Content
    {
        $companyName = $this->source->company_name ?? $this->source->name ?? config('app.name');
        
        return new Content(
            view: 'emails.trial-credentials',
            with: [
                'trial_username' => $this->trialRequest->trial_username,
                'trial_password' => $this->trialRequest->trial_password,
                'trial_url' => $this->trialRequest->trial_url,
                'trial_duration' => $this->trialRequest->trial_duration ?? '24 hours',
                'server_type' => $this->trialRequest->server_type,
                'expires_at' => $this->trialRequest->trial_expires_at?->format('M d, Y H:i'),
                'company_name' => $companyName,
                'team_name' => $this->source->team_name ?? $companyName . ' Team',
                'contact_email' => $this->source->contact_email ?? $this->source->smtp_from_address,
                'website' => $this->source->website ?? '',
                'phone_number' => $this->source->phone_number ?? '',
            ],
        );
    }
}
