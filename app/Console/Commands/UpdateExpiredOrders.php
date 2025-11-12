<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateExpiredOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:update-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update expired orders status from active to expired';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for expired orders...');

        // Update expired orders
        $updatedCount = DB::table('orders')
            ->where('status', 'active')
            ->where('expires_at', '<=', now())
            ->update([
                'status' => 'expired',
                'updated_at' => now()
            ]);

        if ($updatedCount > 0) {
            $this->info("Updated {$updatedCount} expired order(s) to 'expired' status.");

            // Log the activity
            Log::info("Updated {$updatedCount} expired orders", [
                'command' => 'orders:update-expired',
                'timestamp' => now(),
                'updated_count' => $updatedCount
            ]);
        } else {
            $this->info('No expired orders found to update.');
        }

        return Command::SUCCESS;
    }
}
