@extends('layouts.admin')

@section('title', 'Source Management')

@section('content')
<div class="space-y-8">
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-[#201E1F] mb-1">Sources</h1>
                <p class="text-[#201E1F]/60">Manage checkout sources and return URLs</p>
            </div>
            <a href="{{ route('admin.sources.create') }}" class="px-4 py-2 bg-[#D63613] text-white rounded-lg hover:bg-[#b42f11]">Add Source</a>
        </div>
        <form method="GET" class="mt-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search sources..." class="w-full md:w-80 px-3 py-2 border rounded-lg" />
        </form>
    </div>

    @if(!empty($schemaMissing))
    <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-xl p-4">
        <div class="font-semibold mb-1">Sources table not found</div>
        <p class="text-sm">Run your migrations to create the table:</p>
        <pre class="mt-2 p-3 bg-yellow-100 rounded text-xs">php artisan migrate</pre>
    </div>
    @endif

    <div class="bg-white rounded-xl border shadow-sm p-4 overflow-x-auto">
        <table class="min-w-full">
            <thead>
                <tr class="text-left">
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Return URL</th>
                    <th class="px-4 py-2">Active</th>
                    <th class="px-4 py-2 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sources as $source)
                <tr class="border-t">
                    <td class="px-4 py-2 font-medium">{{ $source->name }}</td>
                    <td class="px-4 py-2 text-sm text-gray-600">{{ $source->return_url }}</td>
                    <td class="px-4 py-2">{{ $source->is_active ? 'Yes' : 'No' }}</td>
                    <td class="px-4 py-2 text-right space-x-2">
                        <a href="{{ route('admin.sources.edit', $source) }}" class="px-3 py-1 border rounded">Edit</a>
                        <form method="POST" action="{{ route('admin.sources.destroy', $source) }}" class="inline" onsubmit="return confirm('Delete this source?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1 border rounded text-red-600">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-8 text-center text-gray-500">No sources found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $sources->links() }}</div>
    </div>
</div>
@endsection


