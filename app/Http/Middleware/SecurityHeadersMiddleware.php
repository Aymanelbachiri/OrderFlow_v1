<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Source;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Basic security headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        // X-Frame-Options removed - using CSP frame-ancestors instead (more flexible and modern)
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');
        
        // Prevent search engine indexing
        $response->headers->set('X-Robots-Tag', 'noindex, nofollow, noarchive, nosnippet');

        $isDev = app()->environment('local');

        // Get allowed iframe domains from active sources
        $allowedDomains = $this->getAllowedIframeDomains();
        
        // Always include smarters-proiptv.com as it's the main iframe host
        if (!in_array('smarters-proiptv.com', $allowedDomains)) {
            $allowedDomains[] = 'smarters-proiptv.com';
        }
        
        // Also include checkout.controlweb.dev for same-origin iframe embedding
        if (!in_array('checkout.controlweb.dev', $allowedDomains)) {
            $allowedDomains[] = 'checkout.controlweb.dev';
        }

        // Build frame-ancestors directive
        $frameAncestors = "'self'";
        foreach ($allowedDomains as $domain) {
            $frameAncestors .= " https://{$domain} http://{$domain}";
        }

        if ($isDev) {
            // ✅ Development CSP - permissive for development with comprehensive PayPal support
            $csp = "
                default-src 'self' http://localhost:* http://127.0.0.1:*;
                script-src 'self' 'unsafe-inline' 'unsafe-eval' http://localhost:* http://127.0.0.1:* https://js.stripe.com https://www.paypal.com https://www.sandbox.paypal.com https://unpkg.com https://cdnjs.cloudflare.com https://static.cloudflareinsights.com https://checkout.smarters-proiptv.com https://api.coinbase.com https://api.commerce.coinbase.com https://commerce.coinbase.com https://js.crypto.com https://widget.crypto.com https://www.paypalobjects.com https://www.sandbox.paypalobjects.com;
                style-src 'self' 'unsafe-inline' http://localhost:* http://127.0.0.1:* https://fonts.googleapis.com https://fonts.bunny.net https://cdnjs.cloudflare.com https://www.paypal.com https://www.sandbox.paypal.com https://www.paypalobjects.com https://www.sandbox.paypalobjects.com;
                font-src 'self' data: http://localhost:* http://127.0.0.1:* https://fonts.gstatic.com https://fonts.bunny.net https://cdnjs.cloudflare.com https://www.paypal.com https://www.sandbox.paypal.com https://www.paypalobjects.com https://www.sandbox.paypalobjects.com;
                img-src 'self' data: blob: http: https: https://www.paypal.com https://www.sandbox.paypal.com https://www.paypalobjects.com https://www.sandbox.paypalobjects.com;
                connect-src 'self' ws://localhost:* ws://127.0.0.1:* https://api.stripe.com https://api.paypal.com https://api.sandbox.paypal.com https://www.paypal.com https://www.sandbox.paypal.com https://checkout.smarters-proiptv.com https://api.coinbase.com https://api.commerce.coinbase.com https://commerce.coinbase.com https://api.crypto.com https://pay.crypto.com https://www.paypalobjects.com https://www.sandbox.paypalobjects.com https://xoplatform.paypal.com https://xoplatform.sandbox.paypal.com https://verify.walletconnect.com https://verify.walletconnect.org https://chain-proxy.wallet.coinbase.com;
                frame-src 'self' https://js.stripe.com https://www.paypal.com https://www.sandbox.paypal.com https://api.commerce.coinbase.com https://commerce.coinbase.com https://widget.crypto.com https://www.paypalobjects.com https://www.sandbox.paypalobjects.com https://verify.walletconnect.com https://verify.walletconnect.org;
                frame-ancestors {$frameAncestors};
                object-src 'none';
                base-uri 'self';
            ";
        } else {
            // ✅ Production CSP - balanced security and functionality with comprehensive PayPal support
            $csp = "
                default-src 'self';
                script-src 'self' 'unsafe-inline' 'unsafe-eval' https://js.stripe.com https://www.paypal.com https://www.sandbox.paypal.com https://unpkg.com https://cdnjs.cloudflare.com https://static.cloudflareinsights.com https://checkout.smarters-proiptv.com https://api.coinbase.com https://api.commerce.coinbase.com https://commerce.coinbase.com https://js.crypto.com https://widget.crypto.com https://www.paypalobjects.com https://www.sandbox.paypalobjects.com;
                style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://fonts.bunny.net https://cdnjs.cloudflare.com https://www.paypal.com https://www.sandbox.paypal.com https://www.paypalobjects.com https://www.sandbox.paypalobjects.com;
                font-src 'self' data: https://fonts.gstatic.com https://fonts.bunny.net https://cdnjs.cloudflare.com https://www.paypal.com https://www.sandbox.paypal.com https://www.paypalobjects.com https://www.sandbox.paypalobjects.com;
                img-src 'self' data: blob: https: https://www.paypal.com https://www.sandbox.paypal.com https://www.paypalobjects.com https://www.sandbox.paypalobjects.com;
                connect-src 'self' https://api.stripe.com https://api.paypal.com https://api.sandbox.paypal.com https://www.paypal.com https://www.sandbox.paypal.com https://checkout.smarters-proiptv.com https://api.coinbase.com https://api.commerce.coinbase.com https://commerce.coinbase.com https://api.crypto.com https://pay.crypto.com https://www.paypalobjects.com https://www.sandbox.paypalobjects.com https://xoplatform.paypal.com https://xoplatform.sandbox.paypal.com https://verify.walletconnect.com https://verify.walletconnect.org https://chain-proxy.wallet.coinbase.com;
                frame-src 'self' https://js.stripe.com https://www.paypal.com https://www.sandbox.paypal.com https://api.commerce.coinbase.com https://commerce.coinbase.com https://widget.crypto.com https://www.paypalobjects.com https://www.sandbox.paypalobjects.com https://verify.walletconnect.com https://verify.walletconnect.org;
                frame-ancestors {$frameAncestors};
                object-src 'none';
                base-uri 'self';
            ";
        }

        // Enable CSP with frame-ancestors to allow iframe embedding from allowed domains
        $response->headers->set('Content-Security-Policy', preg_replace('/\s+/', ' ', trim($csp)));

        // HSTS (only if HTTPS)
        if ($request->secure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        return $response;
    }

    /**
     * Get allowed iframe domains from active sources
     * 
     * @return array
     */
    private function getAllowedIframeDomains(): array
    {
        return Cache::remember('allowed_iframe_domains', 3600, function () {
            try {
                $sources = Source::where('is_active', true)
                    ->whereNotNull('return_url')
                    ->pluck('return_url')
                    ->map(function ($url) {
                        // Extract domain from URL
                        $parsed = parse_url($url);
                        if (isset($parsed['host'])) {
                            return $parsed['host'];
                        }
                        return null;
                    })
                    ->filter()
                    ->unique()
                    ->values()
                    ->toArray();

                return $sources;
            } catch (\Exception $e) {
                // Fallback to empty array if sources table doesn't exist or error occurs
                Log::warning('Failed to fetch allowed iframe domains from sources: ' . $e->getMessage());
                return [];
            }
        });
    }
}
