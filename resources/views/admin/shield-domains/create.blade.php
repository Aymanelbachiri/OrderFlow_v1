@extends('layouts.admin')

@section('title', 'Create Shield Domain')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up">
        <div class="lg:flex space-y-4 justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-gradient-to-br from-[#D63613] to-[#D63613]/80 rounded-xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-[#201E1F] mb-1">Create Shield Domain</h1>
                    <p class="text-[#201E1F]/60">Add a new static frontend domain for white-label checkout</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.shield-domains.index') }}" 
                   class="bg-white hover:bg-gray-50 text-[#201E1F]/80 hover:text-[#201E1F] border border-gray-200 px-6 py-3 rounded-lg text-sm font-semibold flex items-center space-x-2 transition-all duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Back</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md animate-fade-in-up" style="animation-delay: 0.1s;">
        <form method="POST" action="{{ route('admin.shield-domains.store') }}" class="p-6">
            @csrf

            <!-- Domain -->
            <div class="mb-6">
                <label for="domain" class="block text-sm font-medium text-[#201E1F]/60 mb-2">Domain *</label>
                <input type="text" 
                       id="domain" 
                       name="domain" 
                       value="{{ old('domain') }}"
                       class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] placeholder-[#201E1F]/40 transition-all duration-200 @error('domain') border-red-500 @enderror"
                       placeholder="example.com"
                       required>
                <p class="mt-1 text-xs text-[#201E1F]/60">The domain that will be used as the shield domain (e.g., shield1.com)</p>
                @error('domain')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Template -->
            <div class="mb-6">
                <label for="template_name" class="block text-sm font-medium text-[#201E1F]/60 mb-2">Template *</label>
                <select id="template_name" 
                        name="template_name" 
                        class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] @error('template_name') border-red-500 @enderror"
                        required>
                    <option value="">Select a template</option>
                    @foreach($templates as $template)
                        <option value="{{ $template }}" {{ old('template_name') == $template ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('-', ' ', $template)) }}
                        </option>
                    @endforeach
                </select>
                <p class="mt-1 text-xs text-[#201E1F]/60">The template that will be displayed on this shield domain</p>
                @error('template_name')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>


            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="text-sm text-blue-800">
                        <p class="font-semibold mb-1">What happens next?</p>
                        <ul class="list-disc list-inside space-y-1 text-xs">
                            <li>Domain record will be created</li>
                            <li>Click "Check Status" on the edit page to create Cloudflare zone</li>
                            <li>Configure the provided nameservers at your domain registrar</li>
                            <li>Click "Check Status" again to verify DNS and activate the domain</li>
                            <li>The domain will be bound to your Cloudflare Pages project</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.shield-domains.index') }}" 
                   class="px-6 py-3 border border-gray-200 rounded-lg text-[#201E1F]/80 hover:bg-gray-50 transition-all duration-200">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-[#D63613] text-white rounded-lg hover:bg-[#b42f11] font-semibold transition-all duration-200">
                    Create Shield Domain
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

