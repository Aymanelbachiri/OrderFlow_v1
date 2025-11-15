<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Source;
use Symfony\Component\HttpFoundation\Response;

class ShieldDomainRedirectMiddleware
{
    /**
     * Handle an incoming request.
     * If source has an active shield domain, redirect to it
     */
    public function handle(Request $request, Closure $next): Response
    {
        $sourceName = $request->query('source');
        
        if (!$sourceName) {
            return $next($request);
        }

        // Find source
        $source = Source::where('name', $sourceName)
            ->where('is_active', true)
            ->where('use_shield_domain', true)
            ->with('shieldDomain')
            ->first();

        // Check if source should use shield domain
        if ($source && $source->shouldUseShieldDomain()) {
            $shieldDomain = $source->shieldDomain;
            $shieldUrl = $shieldDomain->getUrl();
            
            // Get current path and query parameters
            $path = $request->path();
            $query = $request->query();
            
            // Build redirect URL
            $redirectUrl = $shieldUrl . '/' . $path;
            
            // Add query parameters (keep source parameter)
            if (!empty($query)) {
                $redirectUrl .= '?' . http_build_query($query);
            }
            
            return redirect($redirectUrl);
        }

        return $next($request);
    }
}

