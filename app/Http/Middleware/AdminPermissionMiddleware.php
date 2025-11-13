<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminPermissionMiddleware
{
    /**
     * Handle an incoming request.
     * Checks if admin has required permission.
     * Usage: middleware('admin.permission:can_manage_sources')
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Super admin has all permissions
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Check if user is admin
        if (!$user->isAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }

        // Check specific permission
        if (!$user->hasPermission($permission)) {
            abort(403, "Access denied. You don't have permission to {$permission}.");
        }

        return $next($request);
    }
}
