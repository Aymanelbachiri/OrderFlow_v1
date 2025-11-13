<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminDataScopeMiddleware
{
    /**
     * Handle an incoming request.
     * Automatically scopes queries to admin's data (unless super admin).
     * Super admins can see all data.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->isAdmin() && !$user->isSuperAdmin()) {
            // Set admin_id in request for controllers to use
            $request->merge(['admin_id' => $user->id]);
            
            // Store admin_id in session for use in queries
            session(['current_admin_id' => $user->id]);
        } elseif ($user && $user->isSuperAdmin()) {
            // Super admin can see all data - no scoping
            session()->forget('current_admin_id');
        }

        return $next($request);
    }
}
