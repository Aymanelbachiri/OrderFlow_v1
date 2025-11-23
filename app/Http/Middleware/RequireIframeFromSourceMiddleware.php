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

        // Check if referer domain is in allowed domains
        $isAllowed = false;
        foreach ($allowedDomains as $allowedDomain) {
            // Exact match or subdomain match
            if ($refererHost === $allowedDomain || 
                str_ends_with($refererHost, '.' . $allowedDomain)) {
                $isAllowed = true;
                break;
            }
        }

        if (!$isAllowed) {
            // Referer domain not in allowed sources
            Log::warning('RequireIframeFromSource: Blocked unauthorized domain', [
                'referer_host' => $refererHost,
                'allowed_domains' => $allowedDomains,
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
     * Reuses the same logic as SecurityHeadersMiddleware
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

