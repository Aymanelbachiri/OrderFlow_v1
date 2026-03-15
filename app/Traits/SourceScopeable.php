<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait SourceScopeable
{
    /**
     * Apply source-based scoping to a query.
     * Admins see everything; agents see only records matching their assigned sources.
     *
     * @param  Builder  $query
     * @param  string   $sourceColumn  Column name holding the source value (default: 'source')
     * @return Builder
     */
    protected function scopeBySource(Builder $query, string $sourceColumn = 'source'): Builder
    {
        $user = auth()->user();

        if (!$user || $user->isAdmin()) {
            return $query;
        }

        $allowedSources = $user->getAllowedSourceNames();

        if (empty($allowedSources)) {
            $query->whereRaw('0 = 1');
            return $query;
        }

        return $query->whereIn($sourceColumn, $allowedSources);
    }

    /**
     * Check if the current user can access a specific record by its source.
     * Aborts 403 if agent tries to access a record outside their assigned sources.
     */
    protected function authorizeSourceAccess(?string $sourceName): void
    {
        $user = auth()->user();

        if (!$user || $user->isAdmin()) {
            return;
        }

        if (!$user->canAccessSource($sourceName)) {
            abort(403, 'You do not have access to this source.');
        }
    }
}
