<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Queue;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\Facades\Log;

class QueueServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Process jobs immediately after they're queued
        // Queue::after(function (JobProcessed $event) {
        //     // Job was processed successfully
        //     Log::info('Queue job processed successfully', [
        //         'job' => $event->job->resolveName(),
        //         'queue' => $event->job->getQueue(),
        //     ]);
        // });

        // Queue::failing(function (JobFailed $event) {
        //     // Job failed
        //     Log::error('Queue job failed', [
        //         'job' => $event->job->resolveName(),
        //         'queue' => $event->job->getQueue(),
        //         'exception' => $event->exception->getMessage(),
        //     ]);
        // });
    }
}
