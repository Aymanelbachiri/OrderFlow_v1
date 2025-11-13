<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait AdminScopesData
{
    /**
     * Scope query to current admin's data (unless super admin)
     */
    protected function scopeToAdmin(Builder $query, string $adminIdColumn = 'admin_id'): Builder
    {
        $user = auth()->user();
        
        if ($user && $user->isSuperAdmin()) {
            // Super admin sees all data
            return $query;
        }
        
        if ($user && $user->isAdmin()) {
            // Regular admin sees only their data
            return $query->where($adminIdColumn, $user->id);
        }
        
        // Not an admin - return empty query
        return $query->whereRaw('1 = 0');
    }

    /**
     * Get current admin ID (null for super admin)
     */
    protected function getCurrentAdminId(): ?int
    {
        $user = auth()->user();
        
        if ($user && $user->isSuperAdmin()) {
            return null; // Super admin doesn't have admin_id
        }
        
        if ($user && $user->isAdmin()) {
            return $user->id;
        }
        
        return null;
    }

    /**
     * Check if current user is super admin
     */
    protected function isSuperAdmin(): bool
    {
        $user = auth()->user();
        return $user && $user->isSuperAdmin();
    }
}

