<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class RateLimitMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $key = 'global', int $maxAttempts = 60, int $decayMinutes = 1): Response
    {
        $identifier = $this->resolveRequestSignature($request, $key);

        if (RateLimiter::tooManyAttempts($identifier, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($identifier);
            
            return response()->json([
                'message' => 'Too many requests. Please try again in ' . $seconds . ' seconds.',
                'retry_after' => $seconds,
            ], 429);
        }

        RateLimiter::hit($identifier, $decayMinutes * 60);

        $response = $next($request);

        // Add rate limit headers
        $response->headers->add([
            'X-RateLimit-Limit' => $maxAttempts,
            'X-RateLimit-Remaining' => RateLimiter::remaining($identifier, $maxAttempts),
            'X-RateLimit-Reset' => now()->addMinutes($decayMinutes)->timestamp,
        ]);

        return $response;
    }

    /**
     * Resolve the request signature for rate limiting
     */
    protected function resolveRequestSignature(Request $request, string $key): string
    {
        $user = $request->user();
        
        switch ($key) {
            case 'login':
                return 'login:' . $request->ip();
            case 'api':
                return $user ? 'api:user:' . $user->id : 'api:ip:' . $request->ip();
            case 'payment':
                return $user ? 'payment:user:' . $user->id : 'payment:ip:' . $request->ip();
            default:
                return $user ? 'global:user:' . $user->id : 'global:ip:' . $request->ip();
        }
    }
}
