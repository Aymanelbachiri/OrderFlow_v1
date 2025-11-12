@extends('layouts.admin')

@section('title', 'Edit Source')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-xl border shadow p-6 max-w-2xl">
        <h1 class="text-2xl font-bold mb-4">Edit Source</h1>
        <form method="POST" action="{{ route('admin.sources.update', $source) }}" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium mb-1">Name</label>
                <input type="text" name="name" value="{{ old('name', $source->name) }}" class="w-full px-3 py-2 border rounded" required>
                <p class="text-xs text-gray-500 mt-1">Used in checkout URL as ?source=NAME</p>
                @error('name')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Return URL</label>
                <input type="url" name="return_url" value="{{ old('return_url', $source->return_url) }}" class="w-full px-3 py-2 border rounded" required>
                <p class="text-xs text-gray-500 mt-1">Where "Back to Home" should point after checkout</p>
                @error('return_url')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="flex items-center space-x-2">
                <input id="is_active" type="checkbox" name="is_active" value="1" class="rounded" {{ old('is_active', $source->is_active) ? 'checked' : '' }}>
                <label for="is_active" class="text-sm">Active</label>
            </div>
            <div class="pt-2">
                <button type="submit" class="px-4 py-2 bg-[#D63613] text-white rounded hover:bg-[#b42f11]">Save</button>
                <a href="{{ route('admin.sources.index') }}" class="ml-2 px-4 py-2 border rounded">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection


