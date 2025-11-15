<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Foundation\Http\Middleware\Concerns\ExcludesPaths;
use Illuminate\Session\TokenMismatchException;
use App\Models\Source;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Closure;

class VerifyCsrfTokenIframe extends Middleware
{
    use ExcludesPaths;
    
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'webhooks/*',
    ];
    
    /**
     * The globally ignored URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected static $neverVerify = [];

    /**
     * Determine if the session and input CSRF tokens match.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function tokensMatch($request)
    {
        $isIframe = $this->isIframeContext($request);
        $userAgent = $request->userAgent() ?? '';
        $isIOS = $this->isIOSDevice($userAgent);
        $method = $request->method();
        $url = $request->fullUrl();
        $tokenFromInput = $request->input('_token');
        $tokenFromHeader = $request->header('X-CSRF-TOKEN');
        
        // Log CSRF validation attempt
        Log::info('CSRF token validation started', [
            'method' => $method,
            'url' => $url,
            'is_iframe' => $isIframe,
            'is_ios' => $isIOS,
            'has_token_input' => !empty($tokenFromInput),
            'has_token_header' => !empty($tokenFromHeader),
            'referer' => $request->header('Referer'),
            'origin' => $request->header('Origin'),
            'sec_fetch_site' => $request->header('Sec-Fetch-Site'),
            'sec_fetch_mode' => $request->header('Sec-Fetch-Mode'),
            'user_agent' => substr($userAgent, 0, 100),
        ]);
        
        // For iframe contexts OR iOS devices, use our custom validation first
        // This is critical for mobile Safari where cookies are blocked
        if ($isIframe || $isIOS) {
            Log::info('CSRF validation: Iframe/iOS context detected, using custom validation', [
                'url' => $url,
                'is_ios' => $isIOS,
            ]);
            
            $iframeMatch = $this->validateIframeToken($request);
            if ($iframeMatch) {
                Log::info('CSRF validation: Iframe/iOS token validation succeeded', [
                    'url' => $url,
                ]);
                return true;
            }
            
            Log::warning('CSRF validation: Iframe/iOS token validation failed, trying standard validation', [
                'url' => $url,
            ]);
        }

        // Fallback to standard token matching (for non-iframe or when cookies work)
        $standardMatch = parent::tokensMatch($request);
        
        if ($standardMatch) {
            Log::info('CSRF validation: Standard token validation succeeded', [
                'url' => $url,
                'is_iframe' => $isIframe,
            ]);
            
            // If standard matching works, also cache the token for iframe contexts
            if ($isIframe) {
                $token = $tokenFromInput ?: $tokenFromHeader;
                if ($token) {
                    $this->cacheToken($token);
                    Log::info('CSRF validation: Token cached for iframe context', [
                        'url' => $url,
                        'token_preview' => substr($token, 0, 10) . '...',
                    ]);
                }
            }
            return true;
        }

        // Log final failure
        Log::error('CSRF validation: All validation methods failed', [
            'method' => $method,
            'url' => $url,
            'is_iframe' => $isIframe,
            'has_token_input' => !empty($tokenFromInput),
            'has_token_header' => !empty($tokenFromHeader),
            'has_session' => $request->hasSession(),
            'session_id' => $request->hasSession() ? $request->session()->getId() : null,
            'referer' => $request->header('Referer'),
            'origin' => $request->header('Origin'),
        ]);

        return false;
    }

    /**
     * Check if request is from an iframe context
     */
    protected function isIframeContext($request): bool
    {
        $referer = $request->header('Referer');
        $origin = $request->header('Origin');
        $secFetchSite = $request->header('Sec-Fetch-Site');
        $secFetchMode = $request->header('Sec-Fetch-Mode');
        
        $allowedDomains = $this->getAllowedDomains();
        
        // Check if from allowed domain
        $fromAllowedDomain = false;
        if ($referer) {
            foreach ($allowedDomains as $domain) {
                if (str_contains($referer, $domain)) {
                    $fromAllowedDomain = true;
                    break;
                }
            }
        }
        
        return $fromAllowedDomain ||
               ($origin && $this->isFromAllowedDomain($origin, $allowedDomains)) ||
               $secFetchSite === 'cross-site' ||
               $secFetchMode === 'nested';
    }

    /**
     * Validate CSRF token for iframe contexts
     * Uses a more lenient approach when cookies are blocked
     */
    protected function validateIframeToken($request): bool
    {
        $token = $request->input('_token') ?: $request->header('X-CSRF-TOKEN');
        $url = $request->fullUrl();
        
        Log::info('CSRF iframe validation: Starting validation', [
            'url' => $url,
            'has_token_input' => !empty($request->input('_token')),
            'has_token_header' => !empty($request->header('X-CSRF-TOKEN')),
            'token_length' => $token ? strlen($token) : 0,
        ]);
        
        // If no token provided, fail
        if (!$token || !is_string($token) || strlen($token) < 20) {
            Log::warning('CSRF iframe validation: No valid token provided', [
                'url' => $url,
                'has_token' => !empty($token),
                'token_type' => gettype($token),
                'token_length' => $token ? strlen($token) : 0,
                'token_preview' => $token ? substr($token, 0, 20) : 'null',
                'referer' => $request->header('Referer'),
                'all_inputs' => array_keys($request->all()),
                'all_headers' => array_keys($request->headers->all()),
            ]);
            return false;
        }

        // Check cache first
        $cacheKey = 'iframe_csrf_tokens';
        $cachedTokens = Cache::get($cacheKey, []);
        Log::info('CSRF iframe validation: Checking cache', [
            'url' => $url,
            'cache_count' => count($cachedTokens),
            'token_preview' => substr($token, 0, 10) . '...',
        ]);
        
        // First, check if token is in our cache (works even when cookies are blocked)
        // This is the primary validation method for iframe contexts
        if ($this->validateFreshToken($token)) {
            Log::info('CSRF iframe validation: Token validated from cache', [
                'url' => $url,
                'token_preview' => substr($token, 0, 10) . '...',
            ]);
            return true;
        }

        Log::info('CSRF iframe validation: Token not in cache, checking session', [
            'url' => $url,
            'has_session' => $request->hasSession(),
        ]);

        // Fallback: Try to get session token (if cookies are working)
        // This is secondary validation for when cookies work
        $session = $request->session();
        if ($session && $session->token()) {
            $sessionToken = $session->token();
            Log::info('CSRF iframe validation: Session token found', [
                'url' => $url,
                'session_token_preview' => substr($sessionToken, 0, 10) . '...',
                'request_token_preview' => substr($token, 0, 10) . '...',
                'tokens_match' => hash_equals($sessionToken, $token),
            ]);
            
            if (hash_equals($sessionToken, $token)) {
                // Also cache this token for future requests
                $this->cacheToken($token);
                Log::info('CSRF iframe validation: Token validated from session and cached', [
                    'url' => $url,
                    'token_preview' => substr($token, 0, 10) . '...',
                ]);
                return true;
            } else {
                Log::warning('CSRF iframe validation: Session token does not match request token', [
                    'url' => $url,
                    'session_token_preview' => substr($sessionToken, 0, 10) . '...',
                    'request_token_preview' => substr($token, 0, 10) . '...',
                ]);
            }
        } else {
            Log::warning('CSRF iframe validation: No session or session token', [
                'url' => $url,
                'has_session' => $session !== null,
                'session_has_token' => $session && $session->token() ? true : false,
            ]);
        }

        // Log detailed failure information
        Log::error('CSRF iframe validation: All validation methods failed', [
            'url' => $url,
            'token_preview' => substr($token, 0, 10) . '...',
            'token_length' => strlen($token),
            'has_session' => $session !== null,
            'session_id' => $session ? $session->getId() : null,
            'session_token' => $session && $session->token() ? substr($session->token(), 0, 10) . '...' : null,
            'cache_count' => count($cachedTokens),
            'cache_tokens_preview' => array_slice(array_keys($cachedTokens), 0, 5),
            'referer' => $request->header('Referer'),
            'origin' => $request->header('Origin'),
            'user_agent' => substr($request->userAgent() ?? '', 0, 100),
        ]);

        // If neither method works, token is invalid
        return false;
    }

    /**
     * Validate a fresh token (from page meta tag)
     */
    protected function validateFreshToken(string $token): bool
    {
        // For iframe contexts, we'll accept tokens that were generated
        // in the last 2 hours and stored in cache
        // This is a compromise for Safari's cookie blocking
        $cacheKey = 'iframe_csrf_tokens';
        $validTokens = Cache::get($cacheKey, []);
        
        if (empty($validTokens)) {
            return false;
        }
        
        // Clean old tokens (older than 2 hours) and check if token exists
        $now = time();
        $found = false;
        $cleanedTokens = [];
        
        foreach ($validTokens as $cachedToken => $timestamp) {
            if (($now - $timestamp) < 7200) { // 2 hours
                $cleanedTokens[$cachedToken] = $timestamp;
                if (hash_equals($cachedToken, $token)) {
                    $found = true;
                }
            }
        }
        
        // Update cache with cleaned tokens
        if (count($cleanedTokens) !== count($validTokens)) {
            Cache::put($cacheKey, $cleanedTokens, 7200);
        }
        
        return $found;
    }

    /**
     * Get allowed domains from active sources
     */
    protected function getAllowedDomains(): array
    {
        return Cache::remember('allowed_iframe_domains', 3600, function () {
            try {
                $sources = Source::where('is_active', true)
                    ->whereNotNull('return_url')
                    ->pluck('return_url')
                    ->map(function ($url) {
                        $parsed = parse_url($url);
                        return $parsed['host'] ?? null;
                    })
                    ->filter()
                    ->unique()
                    ->values()
                    ->toArray();

                return $sources;
            } catch (\Exception $e) {
                return [];
            }
        });
    }

    /**
     * Check if URL is from allowed domain
     */
    protected function isFromAllowedDomain(?string $url, array $allowedDomains): bool
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
     * Handle an incoming request.
     * Override to cache tokens for iframe contexts on all requests
     */
    public function handle($request, Closure $next)
    {
        // Detect iOS/Safari devices
        $userAgent = $request->userAgent() ?? '';
        $isIOS = $this->isIOSDevice($userAgent);
        $isIframe = $this->isIframeContext($request);
        
        // Cache token for iframe contexts OR iOS devices BEFORE processing
        // iOS Safari blocks cookies aggressively, so we need to cache tokens
        if ($isIframe || $isIOS) {
            Log::info('CSRF middleware: Iframe/iOS context detected, caching tokens', [
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'has_session' => $request->hasSession(),
                'is_ios' => $isIOS,
                'is_iframe' => $isIframe,
                'user_agent' => substr($userAgent, 0, 100),
            ]);
            
            // Try to get token from session (if cookies work)
            if ($request->hasSession()) {
                $session = $request->session();
                if ($session && $session->token()) {
                    $token = $session->token();
                    $this->cacheToken($token);
                    Log::info('CSRF middleware: Session token cached', [
                        'url' => $request->fullUrl(),
                        'token_preview' => substr($token, 0, 10) . '...',
                    ]);
                } else {
                    Log::warning('CSRF middleware: Session exists but no token', [
                        'url' => $request->fullUrl(),
                        'has_session' => true,
                    ]);
                }
            } else {
                Log::warning('CSRF middleware: No session in iframe/iOS context', [
                    'url' => $request->fullUrl(),
                ]);
            }
            
            // For GET requests, also check if token is provided in header (from meta tag)
            // This is critical for iOS where cookies are blocked
            if ($request->isMethod('GET')) {
                $requestToken = $request->header('X-CSRF-TOKEN');
                if ($requestToken && is_string($requestToken) && strlen($requestToken) >= 40) {
                    // Cache token from meta tag (for GET requests)
                    // This ensures tokens from page loads are cached
                    $this->cacheToken($requestToken);
                    Log::info('CSRF middleware: Token from header cached (GET request)', [
                        'url' => $request->fullUrl(),
                        'token_preview' => substr($requestToken, 0, 10) . '...',
                    ]);
                }
            }
        }
        
        return parent::handle($request, $next);
    }
    
    /**
     * Detect iOS devices (iPhone, iPad, iPod)
     */
    protected function isIOSDevice(string $userAgent): bool
    {
        if (empty($userAgent)) {
            return false;
        }
        
        return (
            stripos($userAgent, 'iPhone') !== false ||
            stripos($userAgent, 'iPad') !== false ||
            stripos($userAgent, 'iPod') !== false ||
            (stripos($userAgent, 'Safari') !== false && stripos($userAgent, 'Mobile') !== false && stripos($userAgent, 'Chrome') === false)
        );
    }

    /**
     * Add the CSRF token to the response cookies.
     * Override to cache tokens for iframe contexts
     */
    protected function addCookieToResponse($request, $response)
    {
        parent::addCookieToResponse($request, $response);
        
        // For iframe contexts, also cache the token so it can be validated
        // even if cookies are blocked
        if ($this->isIframeContext($request)) {
            $session = $request->session();
            if ($session && $session->token()) {
                $token = $session->token();
                $this->cacheToken($token);
            }
        }
    }

    /**
     * Cache a CSRF token for iframe validation
     */
    protected function cacheToken(string $token): void
    {
        $cacheKey = 'iframe_csrf_tokens';
        $validTokens = Cache::get($cacheKey, []);
        $validTokens[$token] = time();
        
        // Keep only tokens from last 2 hours
        $now = time();
        $cleanedTokens = [];
        foreach ($validTokens as $cachedToken => $timestamp) {
            if (($now - $timestamp) < 7200) { // 2 hours
                $cleanedTokens[$cachedToken] = $timestamp;
            }
        }
        
        Cache::put($cacheKey, $cleanedTokens, 7200); // 2 hours
        
        Log::debug('CSRF token cached', [
            'token_preview' => substr($token, 0, 10) . '...',
            'total_cached_tokens' => count($cleanedTokens),
        ]);
    }
}

