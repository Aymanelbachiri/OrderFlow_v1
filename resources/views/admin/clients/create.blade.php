@extends('layouts.admin')

@section('title', 'Create New Client')

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
                    <h1 class="text-3xl font-bold text-[#201E1F] mb-1">Create New Client</h1>
                    <p class="text-[#201E1F]/60">Add a new client to your IPTV platform</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.clients.index') }}" 
                   class="text-[#201E1F]/80 hover:text-[#201E1F] border border-gray-200 px-6 py-3 rounded-lg text-sm font-semibold flex items-center space-x-2 transition-all duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Back to Clients</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Form -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md animate-fade-in-up" style="animation-delay: 0.1s;">
        <div class="px-6 py-5 border-b border-[#D63613]/10">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-[#201E1F]">Client Information</h2>
                    <p class="text-sm text-[#201E1F]/60">Fill in the details for the new client account</p>
                </div>
            </div>
        </div>
        
        <form action="{{ route('admin.clients.store') }}" method="POST" class="p-6">
            @csrf
            
            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="space-y-2">
                    <label for="name" class="block text-sm font-medium text-[#201E1F]/60">Full Name *</label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}"
                           class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] placeholder-[#201E1F]/40 transition-all duration-200 @error('name') border-red-500 @enderror"
                           placeholder="Enter full name"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="space-y-2">
                    <label for="email" class="block text-sm font-medium text-[#201E1F]/60">Email Address *</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}"
                           class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] placeholder-[#201E1F]/40 transition-all duration-200 @error('email') border-red-500 @enderror"
                           placeholder="Enter email address"
                           required>
                    @error('email')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div class="space-y-2">
                    <label for="phone" class="block text-sm font-medium text-[#201E1F]/60">Phone Number</label>
                    <input type="text" 
                           id="phone" 
                           name="phone" 
                           value="{{ old('phone') }}"
                           class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] placeholder-[#201E1F]/40 transition-all duration-200 @error('phone') border-red-500 @enderror"
                           placeholder="Enter phone number">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div class="space-y-2">
                    <label for="is_active" class="block text-sm font-medium text-[#201E1F]/60">Account Status</label>
                    <select id="is_active" 
                            name="is_active" 
                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] transition-all duration-200">
                        <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('is_active')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- <!-- Password Section -->
            <div class="mt-8 border-t border-gray-200 pt-8">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-[#201E1F]">Security Credentials</h3>
                        <p class="text-sm text-[#201E1F]/60">Set up login credentials for the new client</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Password -->
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-medium text-[#201E1F]/60">Password *</label>
                        <input type="password" 
                               id="password" 
                               name="password"
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] placeholder-[#201E1F]/40 transition-all duration-200 @error('password') border-red-500 @enderror"
                               placeholder="Enter secure password"
                               required>
                        @error('password')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-[#201E1F]/50">Minimum 8 characters with uppercase, lowercase, number, and special character</p>
                    </div>

                    <!-- Confirm Password -->
                    <div class="space-y-2">
                        <label for="password_confirmation" class="block text-sm font-medium text-[#201E1F]/60">Confirm Password *</label>
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation"
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] placeholder-[#201E1F]/40 transition-all duration-200"
                               placeholder="Confirm password"
                               required>
                    </div>
                </div>
            </div> --}}

            <!-- Account Settings -->
            <div class="mt-8 p-6 bg-white rounded-lg border border-gray-200">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-8 h-8 bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h4 class="text-lg font-semibold text-[#201E1F]">Account Settings</h4>
                </div>
                
                <div class="space-y-4">
                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm text-blue-800">
                            <strong>Note:</strong> Clients do not require email verification or passwords. The account will be automatically verified upon creation.
                        </p>
                    </div>

                    <!-- Send Welcome Email -->
                    <div class="flex items-start space-x-3">
                        <div class="flex items-center h-5">
                            <input type="checkbox" 
                                   id="send_welcome_email" 
                                   name="send_welcome_email" 
                                   value="1"
                                   {{ old('send_welcome_email', '1') ? '' : 'checked' }}
                                   class="h-4 w-4 text-[#D63613] focus:ring-[#D63613] border-gray-300 rounded">
                        </div>
                        <div>
                            <label for="send_welcome_email" class="text-sm font-medium text-[#201E1F]">
                                Send welcome email
                            </label>
                            <p class="text-sm text-[#201E1F]/60">Client will receive an email with their account details</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="mt-8 space-y-2">
                <label for="notes" class="block text-sm font-medium text-[#201E1F]/60">Admin Notes (Optional)</label>
                <textarea id="notes" 
                          name="notes" 
                          rows="4"
                          class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] placeholder-[#201E1F]/40 transition-all duration-200 resize-none"
                          placeholder="Internal notes about this client...">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="mt-8 flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.clients.index') }}" 
                   class="px-6 py-3 text-[#201E1F]/80 hover:text-[#201E1F] border border-gray-200 font-semibold rounded-lg hover:bg-gray-50 transition-all duration-300">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-gradient-to-r from-[#D63613] to-[#D63613]/80 text-white font-semibold rounded-lg hover:from-[#D63613]/90 hover:to-[#D63613]/70 transition-all duration-300 shadow-md hover:shadow-lg flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span>Create Client</span>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
@keyframes fade-in-up {
    0% {
        opacity: 0;
        transform: translateY(20px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-up {
    animation: fade-in-up 0.6s ease-out forwards;
}

/* Password strength indicator */
.password-strength-weak { border-color: #ef4444 !important; }
.password-strength-medium { border-color: #f59e0b !important; }
.password-strength-strong { border-color: #10b981 !important; }
</style>

@endsection