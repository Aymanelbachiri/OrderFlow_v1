<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'security.headers' => \App\Http\Middleware\SecurityHeadersMiddleware::class,
            'rate.limit' => \App\Http\Middleware\RateLimitMiddleware::class,
        ]);

        // Apply iframe cookie middleware early (before session middleware)
        $middleware->web(prepend: [
            \App\Http\Middleware\IframeCookieMiddleware::class,
        ]);

        // Apply security headers to all web routes
        $middleware->web(append: [
            \App\Http\Middleware\SecurityHeadersMiddleware::class,
        ]);

        // Replace default CSRF with custom middleware that handles iframe contexts (Safari)
        // This must be done BEFORE validateCsrfTokens to ensure replacement works
        $middleware->web(
            append: [],
            prepend: [],
            remove: [],
            replace: [
                \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class => \App\Http\Middleware\VerifyCsrfTokenIframe::class,
            ]
        );
        
        // Exclude webhook routes from CSRF protection
        // Note: The except array in VerifyCsrfTokenIframe already includes 'webhooks/*'
        // But we also configure it via the static method for consistency
        \App\Http\Middleware\VerifyCsrfTokenIframe::except([
            'webhooks/*',
        ]);

        // Apply rate limiting to API routes
        $middleware->api(append: [
            'rate.limit:api,60,1',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
