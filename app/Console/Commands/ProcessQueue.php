<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue:process {--timeout=60 : The number of seconds a child process can run} {--memory=128 : The memory limit in megabytes} {--tries=3 : Number of times to attempt a job before logging it failed} {--sleep=3 : Number of seconds to sleep when no job is available}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process queue jobs continuously with auto-restart and monitoring';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting queue processor...');
        $this->info('Press Ctrl+C to stop the worker.');
        
        $timeout = (int) $this->option('timeout');
        $memory = (int) $this->option('memory');
        $tries = (int) $this->option('tries');
        $sleep = (int) $this->option('sleep');
        
        $processedJobs = 0;
        $startTime = time();
        
        while (true) {
            try {
                // Check if there are any jobs in the queue
                $jobCount = DB::table('jobs')->count();
                
                if ($jobCount > 0) {
                    $this->info("Processing {$jobCount} job(s) in queue...");
                    
                    // Process one job
                    $exitCode = $this->call('queue:work', [
                        '--once' => true,
                        '--timeout' => $timeout,
                        '--memory' => $memory,
                        '--tries' => $tries,
                        '--sleep' => 0, // No sleep when processing
                    ]);
                    
                    if ($exitCode === 0) {
                        $processedJobs++;
                        $this->info("Job processed successfully. Total processed: {$processedJobs}");
                    } else {
                        $this->error("Job processing failed with exit code: {$exitCode}");
                    }
                } else {
                    // No jobs available, sleep for a bit
                    $this->comment("No jobs in queue. Sleeping for {$sleep} seconds...");
                    sleep($sleep);
                }
                
                // Log queue status every 10 minutes
                if (($processedJobs % 100 === 0 && $processedJobs > 0) || (time() - $startTime) % 600 === 0) {
                    Log::info('Queue processor status', [
                        'processed_jobs' => $processedJobs,
                        'uptime_minutes' => round((time() - $startTime) / 60, 2),
                        'jobs_in_queue' => DB::table('jobs')->count(),
                    ]);
                }
                
            } catch (\Exception $e) {
                $this->error("Queue processor error: " . $e->getMessage());
                Log::error('Queue processor error', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                
                // Wait a bit before retrying
                sleep(5);
            }
        }
    }
}
