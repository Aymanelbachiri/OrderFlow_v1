@extends('layouts.admin')

@section('title', 'Edit Agent')

@section('content')
<div class="space-y-8 max-w-3xl mx-auto">
    <!-- Header -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.agents.index') }}" class="text-[#201E1F]/60 hover:text-[#D63613] transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-[#201E1F]">Edit Agent</h1>
                <p class="text-[#201E1F]/60 mt-1">Update agent details and source assignments for <strong>{{ $agent->name }}</strong></p>
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-xl p-4">
            <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up" style="animation-delay: 0.1s;">
        <form method="POST" action="{{ route('admin.agents.update', $agent) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-[#201E1F]/60 mb-2">Full Name *</label>
                    <input type="text" name="name" value="{{ old('name', $agent->name) }}" required
                           class="w-full rounded-lg bg-white border border-gray-200 text-[#201E1F] placeholder-[#201E1F]/40 focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300 @error('name') border-red-300 @enderror"
                           placeholder="Agent name">
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#201E1F]/60 mb-2">Email Address *</label>
                    <input type="email" name="email" value="{{ old('email', $agent->email) }}" required
                           class="w-full rounded-lg bg-white border border-gray-200 text-[#201E1F] placeholder-[#201E1F]/40 focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300 @error('email') border-red-300 @enderror"
                           placeholder="agent@example.com">
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#201E1F]/60 mb-2">New Password</label>
                    <input type="password" name="password"
                           class="w-full rounded-lg bg-white border border-gray-200 text-[#201E1F] placeholder-[#201E1F]/40 focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300 @error('password') border-red-300 @enderror"
                           placeholder="Leave blank to keep current">
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#201E1F]/60 mb-2">Confirm New Password</label>
                    <input type="password" name="password_confirmation"
                           class="w-full rounded-lg bg-white border border-gray-200 text-[#201E1F] placeholder-[#201E1F]/40 focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300"
                           placeholder="Re-enter new password">
                </div>
            </div>

            <div>
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1"
                           {{ old('is_active', $agent->is_active) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-[#D63613] focus:ring-[#D63613]/20">
                    <span class="text-sm font-medium text-[#201E1F]">Active</span>
                </label>
                <p class="text-xs text-[#201E1F]/40 mt-1">Inactive agents cannot log in.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-[#201E1F]/60 mb-3">Assign Sources *</label>
                <p class="text-xs text-[#201E1F]/40 mb-3">Select the sources this agent will be allowed to manage.</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    @forelse($sources as $source)
                        <label class="flex items-center space-x-3 p-3 rounded-lg border border-gray-200 bg-white hover:border-[#D63613]/40 transition-colors cursor-pointer">
                            <input type="checkbox" name="sources[]" value="{{ $source->id }}"
                                   {{ in_array($source->id, old('sources', $assignedSourceIds)) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-[#D63613] focus:ring-[#D63613]/20">
                            <span class="text-sm font-medium text-[#201E1F]">{{ $source->name }}</span>
                        </label>
                    @empty
                        <p class="col-span-full text-sm text-[#201E1F]/40">No active sources found.</p>
                    @endforelse
                </div>
                @error('sources')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.agents.index') }}" class="px-6 py-3 text-[#201E1F]/60 hover:text-[#201E1F] transition-colors font-medium">Cancel</a>
                <button type="submit" class="bg-gradient-to-r from-[#D63613] to-[#D63613]/80 hover:from-[#D63613]/90 hover:to-[#D63613] text-white px-8 py-3 rounded-lg text-sm font-semibold transition-all duration-300 shadow-md hover:shadow-lg">
                    Update Agent
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
