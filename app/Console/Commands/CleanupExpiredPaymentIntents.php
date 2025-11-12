<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PaymentIntent;
use Illuminate\Support\Facades\Log;

class CleanupExpiredPaymentIntents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment-intents:cleanup {--dry-run : Show what would be deleted without actually deleting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired payment intents';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');

        // Find expired payment intents that are still pending
        $expiredIntents = PaymentIntent::where('status', 'pending')
            ->where('expires_at', '<', now())
            ->get();

        if ($expiredIntents->isEmpty()) {
            $this->info('No expired payment intents found.');
            return 0;
        }

        $this->info("Found {$expiredIntents->count()} expired payment intents:");

        foreach ($expiredIntents as $intent) {
            $this->line("- ID: {$intent->id}, User: {$intent->user->name}, Amount: $" . number_format($intent->amount, 2) . ", Expired: {$intent->expires_at->diffForHumans()}");
        }

        if ($dryRun) {
            $this->warn('DRY RUN: No payment intents were actually deleted.');
            return 0;
        }

        if (!$this->confirm('Do you want to delete these expired payment intents?')) {
            $this->info('Operation cancelled.');
            return 0;
        }

        $deletedCount = 0;
        foreach ($expiredIntents as $intent) {
            try {
                // Update status to expired instead of deleting
                $intent->update(['status' => 'expired']);
                $deletedCount++;
                
                Log::info('Payment intent marked as expired', [
                    'payment_intent_id' => $intent->id,
                    'user_id' => $intent->user_id,
                    'amount' => $intent->amount,
                    'expired_at' => $intent->expires_at,
                ]);
            } catch (\Exception $e) {
                $this->error("Failed to update payment intent {$intent->id}: " . $e->getMessage());
                Log::error('Failed to mark payment intent as expired', [
                    'payment_intent_id' => $intent->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->info("Successfully marked {$deletedCount} payment intents as expired.");
        
        return 0;
    }
}
