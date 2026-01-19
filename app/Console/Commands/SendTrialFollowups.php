<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TrialRequest;
use App\Models\Source;
use App\Mail\TrialFollowupMail;
use App\Services\SourceMailService;
use Illuminate\Support\Facades\Mail;

class SendTrialFollowups extends Command
{
    protected $signature = 'trials:send-followups';

    protected $description = 'Send follow-up emails to expired trial users asking if they want to subscribe';

    public function handle()
    {
        $this->info('Starting trial follow-up process...');

        // Get expired trials that haven't received follow-up yet
        $expiredTrials = TrialRequest::where('status', 'approved')
            ->where('credentials_sent', true)
            ->where('followup_sent', false)
            ->where(function ($query) {
                // Trial expired based on trial_expires_at
                $query->whereNotNull('trial_expires_at')
                    ->where('trial_expires_at', '<', now());
            })
            ->orWhere(function ($query) {
                // Fallback: If no expiry set, check if approved more than 24 hours ago
                $query->where('status', 'approved')
                    ->where('credentials_sent', true)
                    ->where('followup_sent', false)
                    ->whereNull('trial_expires_at')
                    ->where('processed_at', '<', now()->subHours(24));
            })
            ->get();

        $this->info("Found {$expiredTrials->count()} expired trials to follow up");

        $sent = 0;
        $failed = 0;

        foreach ($expiredTrials as $trial) {
            try {
                // Get source for SMTP
                $source = Source::where('name', $trial->source)->first();

                if (!$source || !$source->smtp_host || !$source->smtp_from_address) {
                    $this->warn("  ⊘ Skipping {$trial->email} - No SMTP configured for source: {$trial->source}");
                    continue;
                }

                // Configure mailer for source
                $sourceMailService = new SourceMailService();
                $mailerName = $sourceMailService->configureMailForSource($source);

                if ($mailerName) {
                    Mail::mailer($mailerName)->to($trial->email)->send(
                        new TrialFollowupMail($trial, $source)
                    );
                } else {
                    Mail::to($trial->email)->send(
                        new TrialFollowupMail($trial, $source)
                    );
                }

                // Mark as sent
                $trial->update([
                    'followup_sent' => true,
                    'followup_sent_at' => now(),
                ]);

                $sent++;
                $this->line("  ✓ Sent follow-up to {$trial->email}");

            } catch (\Exception $e) {
                $failed++;
                $this->error("  ✗ Failed to send to {$trial->email}: " . $e->getMessage());
                \Log::error("Trial follow-up failed", [
                    'trial_id' => $trial->id,
                    'email' => $trial->email,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->info("Trial follow-up process completed. Sent: {$sent}, Failed: {$failed}");
        return 0;
    }
}
