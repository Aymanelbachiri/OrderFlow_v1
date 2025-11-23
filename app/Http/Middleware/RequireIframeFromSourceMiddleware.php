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
        // Get allowed domains from active sources
        $allowedDomains = $this->getAllowedIframeDomains();
        
        // If no sources are configured, block all access for security
        if (empty($allowedDomains)) {
            Log::warning('RequireIframeFromSource: No allowed domains configured', [
                'path' => $request->path(),
                'referer' => $request->header('Referer'),
            ]);
            return response()->view('errors.iframe-required', [
                'message' => 'No authorized sources configured. Please contact the administrator.'
            ], 403);
        }

        // Check Referer header - must exist and be from an allowed domain
        $referer = $request->header('Referer');
        
        if (!$referer) {
            // No referer = direct access, block it
            Log::info('RequireIframeFromSource: Blocked direct access (no referer)', [
                'path' => $request->path(),
                'ip' => $request->ip(),
            ]);
            return response()->view('errors.iframe-required', [
                'message' => 'This checkout page can only be accessed through an embedded iframe on an authorized website.'
            ], 403);
        }

        // Extract domain from referer
        $refererHost = parse_url($referer, PHP_URL_HOST);
        
        if (!$refererHost) {
            // Invalid referer URL
            Log::warning('RequireIframeFromSource: Invalid referer URL', [
                'referer' => $referer,
                'path' => $request->path(),
            ]);
            return response()->view('errors.iframe-required', [
                'message' => 'Invalid referer. This checkout page can only be accessed through an embedded iframe on an authorized website.'
            ], 403);
        }

        // Normalize referer host (remove www prefix for comparison)
        $refererHostNormalized = $this->normalizeDomain($refererHost);

        // Check if referer domain is in allowed domains
        $isAllowed = false;
        foreach ($allowedDomains as $allowedDomain) {
            // Normalize allowed domain too
            $allowedDomainNormalized = $this->normalizeDomain($allowedDomain);
            
            // Exact match, subdomain match, or www variant match
            if ($refererHostNormalized === $allowedDomainNormalized || 
                $refererHost === $allowedDomain ||
                str_ends_with($refererHost, '.' . $allowedDomain) ||
                str_ends_with($refererHostNormalized, '.' . $allowedDomainNormalized)) {
                $isAllowed = true;
                Log::debug('RequireIframeFromSource: Domain matched', [
                    'referer_host' => $refererHost,
                    'referer_normalized' => $refererHostNormalized,
                    'allowed_domain' => $allowedDomain,
                    'allowed_normalized' => $allowedDomainNormalized,
                ]);
                break;
            }
        }

        if (!$isAllowed) {
            // Referer domain not in allowed sources
            Log::warning('RequireIframeFromSource: Blocked unauthorized domain', [
                'referer' => $referer,
                'referer_host' => $refererHost,
                'referer_normalized' => $refererHostNormalized,
                'allowed_domains' => $allowedDomains,
                'allowed_domains_normalized' => array_map([$this, 'normalizeDomain'], $allowedDomains),
                'path' => $request->path(),
                'ip' => $request->ip(),
            ]);
            return response()->view('errors.iframe-required', [
                'message' => 'This checkout page can only be accessed from authorized partner websites. Your domain is not authorized.'
            ], 403);
        }

        // All checks passed - allow the request
        Log::debug('RequireIframeFromSource: Allowed iframe access', [
            'referer_host' => $refererHost,
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
}

