<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind Cloudflare services
        $this->app->singleton(\App\Services\Cloudflare\CloudflareApiClient::class);
        $this->app->singleton(\App\Services\Cloudflare\ZoneService::class, function ($app) {
            return new \App\Services\Cloudflare\ZoneService(
                $app->make(\App\Services\Cloudflare\CloudflareApiClient::class)
            );
        });
        $this->app->singleton(\App\Services\Cloudflare\DNSService::class, function ($app) {
            return new \App\Services\Cloudflare\DNSService(
                $app->make(\App\Services\Cloudflare\CloudflareApiClient::class)
            );
        });
        $this->app->singleton(\App\Services\ShieldDomainService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
