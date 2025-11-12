<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Source;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class IframeCookieMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check multiple indicators that we're in an iframe context
        $referer = $request->header('Referer');
        $origin = $request->header('Origin');
        $secFetchSite = $request->header('Sec-Fetch-Site');
        $secFetchMode = $request->header('Sec-Fetch-Mode');
        $userAgent = $request->userAgent() ?? '';
        
        $allowedDomains = $this->getAllowedDomains();
        
        // Detect iframe context more reliably
        $isIframeContext = 
            ($referer && $this->isFromAllowedDomain($referer, $allowedDomains)) || 
            ($origin && $this->isFromAllowedDomain($origin, $allowedDomains)) ||
            $secFetchSite === 'cross-site' ||
            $secFetchMode === 'nested';

        // Detect Safari (including iOS Safari)
        $isSafari = $this->isSafari($userAgent);

        if ($isIframeContext) {
            // Adjust session configuration BEFORE session middleware runs
            // These settings are required for cookies to work in cross-site iframes
            \Illuminate\Support\Facades\Config::set('session.same_site', 'none');
            \Illuminate\Support\Facades\Config::set('session.secure', true);
            \Illuminate\Support\Facades\Config::set('session.http_only', true);
            
            // For cross-site iframes, don't set a cookie domain (let browser use default)
            // Setting a domain can cause issues with Safari's strict cookie policies
            \Illuminate\Support\Facades\Config::set('session.domain', null);
            
            // Safari doesn't support partitioned cookies well, so disable for Safari
            // For other browsers, enable partitioned cookies for better support
            if (!$isSafari) {
                \Illuminate\Support\Facades\Config::set('session.partitioned', true);
            } else {
                \Illuminate\Support\Facades\Config::set('session.partitioned', false);
            }
            
            // Increase session lifetime for iframe contexts to reduce expiration issues
            // Safari's ITP can be aggressive, so longer sessions help
            \Illuminate\Support\Facades\Config::set('session.lifetime', 240); // 4 hours instead of 2
        }

        $response = $next($request);

        // Modify cookies in response to ensure they work in iframe context
        if ($isIframeContext) {
            $this->modifyCookiesForIframe($response, $isSafari);
            
            // Ensure session is saved (but don't regenerate to avoid invalidating CSRF tokens)
            // This helps maintain session state in iframe contexts
            if ($request->hasSession() && $request->session()->isStarted()) {
                $request->session()->save();
                
                // Cache CSRF token for iframe validation fallback
                // This helps when cookies are blocked (Safari)
                $token = $request->session()->token();
                if ($token) {
                    $cacheKey = 'iframe_csrf_tokens';
                    $validTokens = Cache::get($cacheKey, []);
                    $validTokens[$token] = time();
                    
                    // Keep only tokens from last 2 hours
                    $validTokens = array_filter($validTokens, function($timestamp) {
                        return (time() - $timestamp) < 7200;
                    });
                    
                    Cache::put($cacheKey, $validTokens, 7200);
                }
            }
        }

        return $response;
    }

    /**
     * Detect if the user agent is Safari (including iOS Safari)
     */
    private function isSafari(string $userAgent): bool
    {
        if (empty($userAgent)) {
            return false;
        }
        
        // Check for Safari (but not Chrome which also contains Safari in UA string)
        return (
            (stripos($userAgent, 'Safari') !== false && stripos($userAgent, 'Chrome') === false) ||
            stripos($userAgent, 'iPhone') !== false ||
            stripos($userAgent, 'iPad') !== false ||
            stripos($userAgent, 'iPod') !== false
        );
    }

    /**
     * Get allowed domains from active sources
     * 
     * @return array
     */
    private function getAllowedDomains(): array
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

    /**
     * Check if the URL is from an allowed domain
     */
    private function isFromAllowedDomain(?string $url, array $allowedDomains): bool
    {
        if (!$url || empty($allowedDomains)) {
            return false;
        }

        foreach ($allowedDomains as $domain) {
            if (str_contains($url, $domain)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Modify all cookies to work in iframe context
     */
    private function modifyCookiesForIframe(Response $response, bool $isSafari = false): void
    {
        $cookies = $response->headers->getCookies();
        
        // Modify ALL cookies for iframe compatibility
        foreach ($cookies as $cookie) {
            $cookieName = $cookie->getName();
            
            // Remove old cookie
            $response->headers->removeCookie(
                $cookieName,
                $cookie->getPath(),
                $cookie->getDomain()
            );
            
            // Safari doesn't support partitioned cookies well, so don't use them for Safari
            // For other browsers, use partitioned cookies for better third-party cookie support
            $usePartitioned = !$isSafari;
            
            // For cross-site iframes, don't set a cookie domain
            // Setting a domain can cause Safari and other browsers to reject the cookie
            // Let the browser use the default (current domain) which works better for cross-site
            $cookieDomain = null;
            
            // Create new cookie with SameSite=None, Secure, and optionally Partitioned
            // SameSite=None and Secure are required for cross-site iframes
            // Domain is set to null to let browser handle it (works better for cross-site)
            $newCookie = new \Symfony\Component\HttpFoundation\Cookie(
                $cookieName,
                $cookie->getValue(),
                $cookie->getExpiresTime(),
                $cookie->getPath() ?: '/',
                $cookieDomain, // null - let browser use default domain for better cross-site support
                true, // secure - required for SameSite=None
                $cookie->isHttpOnly(),
                false, // raw
                'none', // sameSite - required for cross-site iframes
                $usePartitioned // partitioned - helps with third-party cookie restrictions (but not Safari)
            );
            
            $response->headers->setCookie($newCookie);
        }
    }
}