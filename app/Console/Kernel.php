<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Process queue jobs every minute to prevent them from getting stuck
        $schedule->command('queue:work --stop-when-empty --timeout=60 --memory=128 --tries=3')
                 ->everyMinute()
                 ->withoutOverlapping()
                 ->runInBackground();
                 
        // Clean up failed jobs older than 24 hours
        $schedule->command('queue:prune-failed --hours=24')
                 ->daily();
                 
        // Monitor queue health
        $schedule->call(function () {
            $jobCount = \DB::table('jobs')->count();
            $failedCount = \DB::table('failed_jobs')->count();
            
            \Log::info('Queue health check', [
                'jobs_in_queue' => $jobCount,
                'failed_jobs' => $failedCount,
                'timestamp' => now(),
            ]);
            
            // If there are too many jobs, process them immediately
            if ($jobCount > 10) {
                \Artisan::call('queue:work', ['--stop-when-empty' => true]);
            }
        })->everyFiveMinutes();
        
        // Send renewal reminders daily at 9 AM
        $schedule->command('iptv:send-renewal-reminders')
                 ->daily()
                 ->at('09:00')
                 ->withoutOverlapping()
                 ->runInBackground();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}