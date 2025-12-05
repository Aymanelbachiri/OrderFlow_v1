<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Source;
use App\Models\CustomProduct;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RequireIframeFromSourceMiddleware
{
    /**
     * Handle an incoming request.
     * Blocks direct access and only allows iframe access from authorized source domains
     * Exception: Custom products with allow_direct_checkout enabled can be accessed directly
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if this is a custom product checkout route
        if ($request->route()->hasParameter('product')) {
            $product = $request->route('product');

            // If product is a CustomProduct model instance and allows direct checkout, bypass iframe check
            if ($product instanceof CustomProduct && $product->allow_direct_checkout) {
                Log::debug('RequireIframeFromSource: Allowed direct access for custom product with allow_direct_checkout enabled', [
                    'product_id' => $product->id,
                    'product_slug' => $product->slug,
                    'path' => $request->path(),
                ]);
                return $next($request);
            }
        }

        // Allow local development access (localhost, 127.0.0.1)
        $currentHost = $request->getHost();
        if ($this->isLocalDevelopment($currentHost)) {
            Log::debug('RequireIframeFromSource: Allowed local development access', [
                'host' => $currentHost,
                'path' => $request->path(),
            ]);
            return $next($request);
        }

        // Get allowed domains from active sources
        $allowedDomains = $this->getAllowedIframeDomains();

        // If no sources are configured, block all access for security
        if (empty($allowedDomains)) {
            Log::warning('RequireIframeFromSource: No allowed domains configured', [
                'path' => $request->path(),
                'referer' => $request->header('Referer'),
            ]);
            abort(404);
        }

        // Check multiple indicators for iframe context (browsers often strip Referer header)
        $referer = $request->header('Referer');
        $origin = $request->header('Origin');
        $secFetchSite = $request->header('Sec-Fetch-Site');
        $secFetchMode = $request->header('Sec-Fetch-Mode');
        $currentHost = $request->getHost();
        
        // Detect iframe context using multiple indicators
        $isIframeContext = 
            $secFetchSite === 'cross-site' || 
            $secFetchMode === 'nested' ||
            ($referer && parse_url($referer, PHP_URL_HOST) !== $currentHost) ||
            ($origin && parse_url($origin, PHP_URL_HOST) !== $currentHost);
        
        // Try to get domain from Referer first, then fallback to Origin
        $sourceDomain = null;
        $sourceUrl = null;
        
        if ($referer) {
            $sourceUrl = $referer;
            $sourceDomain = parse_url($referer, PHP_URL_HOST);
        } elseif ($origin) {
            $sourceUrl = $origin;
            $sourceDomain = parse_url($origin, PHP_URL_HOST);
        }
        
        // If no domain info available
        if (!$sourceDomain) {
            // If we have clear cross-site iframe indicators, try to validate using source parameter
            if ($isIframeContext && $secFetchSite === 'cross-site') {
                // Check if source parameter is provided and matches an active source
                $sourceParam = $request->query('source');
                if ($sourceParam) {
                    try {
                        $source = Source::where('name', $sourceParam)
                            ->where('is_active', true)
                            ->first();
                        
                        if ($source) {
                            // Source exists and is active - allow the request
                            // This is a fallback when headers are stripped by browser
                            Log::info('RequireIframeFromSource: Allowed cross-site iframe with valid source parameter', [
                                'path' => $request->path(),
                                'source_name' => $sourceParam,
                                'source_id' => $source->id,
                                'sec_fetch_site' => $secFetchSite,
                                'sec_fetch_mode' => $secFetchMode,
                                'ip' => $request->ip(),
                            ]);
                            return $next($request);
                        }
                    } catch (\Exception $e) {
                        Log::warning('RequireIframeFromSource: Error checking source parameter', [
                            'error' => $e->getMessage(),
                            'source_param' => $sourceParam,
                        ]);
                    }
                }
                
                // No valid source parameter - block it
                Log::warning('RequireIframeFromSource: Cross-site iframe detected but no domain info and no valid source parameter', [
                    'path' => $request->path(),
                    'sec_fetch_site' => $secFetchSite,
                    'sec_fetch_mode' => $secFetchMode,
                    'referer' => $referer,
                    'origin' => $origin,
                    'source_param' => $sourceParam,
                    'ip' => $request->ip(),
                ]);
                abort(404);
            }
            
            // If no iframe indicators and no domain, likely direct access
            if (!$isIframeContext) {
                Log::info('RequireIframeFromSource: Blocked direct access (no iframe indicators, no domain)', [
                    'path' => $request->path(),
                    'ip' => $request->ip(),
                    'sec_fetch_site' => $secFetchSite,
                    'sec_fetch_mode' => $secFetchMode,
                ]);
                abort(404);
            }
            
            // Same-origin iframe (same domain) - allow it
            if ($secFetchSite === 'same-origin' || $secFetchMode === 'nested') {
                Log::debug('RequireIframeFromSource: Allowed same-origin iframe access', [
                    'path' => $request->path(),
                    'sec_fetch_site' => $secFetchSite,
                    'sec_fetch_mode' => $secFetchMode,
                ]);
                return $next($request);
            }
        }

        // Check if same-origin (source domain matches current host) - allow same-origin iframes
        if ($sourceDomain && $this->normalizeDomain($sourceDomain) === $this->normalizeDomain($currentHost)) {
            Log::debug('RequireIframeFromSource: Allowed same-origin iframe access', [
                'source_domain' => $sourceDomain,
                'current_host' => $currentHost,
                'path' => $request->path(),
            ]);
            return $next($request);
        }

        // Normalize source domain (remove www prefix for comparison)
        $sourceDomainNormalized = $this->normalizeDomain($sourceDomain);

        // Check if source domain is in allowed domains
        $isAllowed = false;
        foreach ($allowedDomains as $allowedDomain) {
            // Normalize allowed domain too
            $allowedDomainNormalized = $this->normalizeDomain($allowedDomain);
            
            // Exact match, subdomain match, or www variant match
            if ($sourceDomainNormalized === $allowedDomainNormalized || 
                $sourceDomain === $allowedDomain ||
                str_ends_with($sourceDomain, '.' . $allowedDomain) ||
                str_ends_with($sourceDomainNormalized, '.' . $allowedDomainNormalized)) {
                $isAllowed = true;
                Log::debug('RequireIframeFromSource: Domain matched', [
                    'source_domain' => $sourceDomain,
                    'source_normalized' => $sourceDomainNormalized,
                    'source_url' => $sourceUrl,
                    'allowed_domain' => $allowedDomain,
                    'allowed_normalized' => $allowedDomainNormalized,
                ]);
                break;
            }
        }

        if (!$isAllowed) {
            // Source domain not in allowed sources
            Log::warning('RequireIframeFromSource: Blocked unauthorized domain', [
                'referer' => $referer,
                'origin' => $origin,
                'source_domain' => $sourceDomain,
                'source_normalized' => $sourceDomainNormalized,
                'allowed_domains' => $allowedDomains,
                'allowed_domains_normalized' => array_map([$this, 'normalizeDomain'], $allowedDomains),
                'sec_fetch_site' => $secFetchSite,
                'sec_fetch_mode' => $secFetchMode,
                'path' => $request->path(),
                'ip' => $request->ip(),
            ]);
            abort(404);
        }

        // All checks passed - allow the request
        Log::debug('RequireIframeFromSource: Allowed iframe access', [
            'source_domain' => $sourceDomain,
            'source_url' => $sourceUrl,
            'path' => $request->path(),
        ]);

        return $next($request);
    }

    /**
     * Get allowed iframe domains from active sources
     * Extracts domains from both return_url and website fields
     */
    private function getAllowedIframeDomains(): array
    {
        return Cache::remember('allowed_iframe_domains', 3600, function () {
            try {
                $sources = Source::where('is_active', true)->get();
                $domains = [];
                
                foreach ($sources as $source) {
                    // Extract domain from return_url
                    if ($source->return_url) {
                        $parsed = parse_url($source->return_url);
                        if (isset($parsed['host'])) {
                            $domains[] = $parsed['host'];
                        }
                    }
                    
                    // Extract domain from website field (if it exists)
                    if ($source->website) {
                        $parsed = parse_url($source->website);
                        if (isset($parsed['host'])) {
                            $domains[] = $parsed['host'];
                        }
                    }
                }
                
                // Remove duplicates and normalize
                $domains = array_unique(array_filter($domains));
                $domains = array_values($domains);

                Log::debug('RequireIframeFromSource: Loaded allowed domains', [
                    'domains' => $domains,
                    'count' => count($domains),
                    'sources_checked' => $sources->count(),
                ]);

                return $domains;
            } catch (\Exception $e) {
                // Fallback to empty array if sources table doesn't exist or error occurs
                Log::warning('Failed to fetch allowed iframe domains from sources: ' . $e->getMessage());
                return [];
            }
        });
    }

    /**
     * Normalize domain by removing www prefix
     * This helps match www.example.com with example.com
     */
    private function normalizeDomain(string $domain): string
    {
        // Remove www. prefix if present
        if (str_starts_with(strtolower($domain), 'www.')) {
            return substr($domain, 4);
        }
        return $domain;
    }

    /**
     * Check if the current host is a local development environment
     */
    private function isLocalDevelopment(string $host): bool
    {
        $localHosts = [
            'localhost',
            '127.0.0.1',
            '::1',
        ];

        // Check exact match
        if (in_array($host, $localHosts)) {
            return true;
        }

        // Check if host starts with localhost: (e.g., localhost:8000)
        if (str_starts_with($host, 'localhost:')) {
            return true;
        }

        // Check if host starts with 127.0.0.1: (e.g., 127.0.0.1:8000)
        if (str_starts_with($host, '127.0.0.1:')) {
            return true;
        }

        return false;
    }
}

