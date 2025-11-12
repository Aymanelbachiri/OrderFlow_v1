<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Source;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;

class SourceController extends Controller
{
    public function index(Request $request)
    {
        // Handle case when migration hasn't been run yet
        if (!Schema::hasTable('sources')) {
            $sources = collect();
            $schemaMissing = true;
            return view('admin.sources.index', compact('sources', 'schemaMissing'));
        }

        $query = Source::query();

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('return_url', 'like', "%{$search}%");
            });
        }

        $sources = $query->latest()->paginate(20);
        return view('admin.sources.index', compact('sources'));
    }

    public function create()
    {
        return view('admin.sources.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:sources,name',
            'return_url' => 'required|url|max:2048',
            'is_active' => 'sometimes|boolean',
        ]);

        $validated['is_active'] = (bool) ($validated['is_active'] ?? true);

        Source::create($validated);
        
        // Clear iframe domains cache
        Cache::forget('allowed_iframe_domains');

        return redirect()->route('admin.sources.index')
            ->with('success', 'Source created successfully.');
    }

    public function edit(Source $source)
    {
        return view('admin.sources.edit', compact('source'));
    }

    public function update(Request $request, Source $source)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:sources,name,' . $source->id,
            'return_url' => 'required|url|max:2048',
            'is_active' => 'sometimes|boolean',
        ]);

        $validated['is_active'] = (bool) ($validated['is_active'] ?? $source->is_active);

        $source->update($validated);
        
        // Clear iframe domains cache
        Cache::forget('allowed_iframe_domains');

        return redirect()->route('admin.sources.index')
            ->with('success', 'Source updated successfully.');
    }

    public function destroy(Source $source)
    {
        $source->delete();
        
        // Clear iframe domains cache
        Cache::forget('allowed_iframe_domains');
        
        return redirect()->route('admin.sources.index')
            ->with('success', 'Source deleted successfully.');
    }
}


