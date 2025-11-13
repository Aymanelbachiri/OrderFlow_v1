@extends('layouts.admin')

@section('title', 'Create New Reseller')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up">
        <div class="lg:flex space-y-4 justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-[#201E1F] mb-2">Create New Reseller</h1>
                <p class="text-[#201E1F]/60">Set up a new reseller account with permissions and credentials</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.resellers.index') }}" 
                   class="bg-white hover:bg-gray-50 text-[#201E1F]/80 hover:text-[#201E1F] border border-gray-200 px-6 py-3 rounded-lg text-sm font-semibold flex items-center space-x-2 transition-all duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Back to Resellers</span>
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.resellers.store') }}" method="POST" class="space-y-5">
        @csrf
        
        <!-- Basic Information -->
        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md animate-fade-in-up" style="animation-delay: 0.1s;">
            <div class="px-6 py-5 border-b border-[#D63613]/10">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-[#201E1F]">Basic Information</h3>
                </div>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-[#201E1F] mb-2">Full Name</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}"
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] placeholder-[#201E1F]/40 transition-all duration-200 @error('name') border-red-500 @enderror"
                               placeholder="Enter full name"
                               required>
                        @error('name')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-[#201E1F] mb-2">Email Address</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] placeholder-[#201E1F]/40 transition-all duration-200 @error('email') border-red-500 @enderror"
                               placeholder="Enter email address"
                               required>
                        @error('email')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-[#201E1F] mb-2">Phone Number</label>
                        <input type="text" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone') }}"
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] placeholder-[#201E1F]/40 transition-all duration-200 @error('phone') border-red-500 @enderror"
                               placeholder="Enter phone number">
                        @error('phone')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Settings -->
        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md animate-fade-in-up" style="animation-delay: 0.2s;">
            <div class="px-6 py-5 border-b border-[#D63613]/10">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-[#201E1F]">Account Settings</h3>
                </div>
            </div>
            
            <div class="p-6">
                <div class="bg-white p-4 rounded-lg border border-gray-200 space-y-4">
                    <!-- Email Verification -->
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="email_verified" 
                               name="email_verified" 
                               value="1"
                               {{ old('email_verified', '1') == '1' ? 'checked' : '' }}
                               class="h-4 w-4 text-[#D63613] focus:ring-[#D63613] border-gray-300 rounded bg-white">
                        <label for="email_verified" class="ml-3 block text-sm font-medium text-[#201E1F]">
                            Mark email as verified
                        </label>
                    </div>

                    <!-- Send Welcome Email -->
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="send_welcome_email" 
                               name="send_welcome_email" 
                               value="1"
                               {{ old('send_welcome_email', '1') ? '' : 'checked' }}
                               class="h-4 w-4 text-[#D63613] focus:ring-[#D63613] border-gray-300 rounded bg-white">
                        <label for="send_welcome_email" class="ml-3 block text-sm font-medium text-[#201E1F]">
                            Send welcome email with reseller credentials
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reseller Permissions -->
        {{-- <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md animate-fade-in-up" style="animation-delay: 0.4s;">
            <div class="px-6 py-5 border-b border-[#D63613]/10">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-[#201E1F]">Reseller Permissions</h3>
                        <p class="text-sm text-[#201E1F]/60">Configure what actions this reseller can perform</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="can_create_clients" 
                                   name="can_create_clients" 
                                   value="1"
                                   {{ old('can_create_clients', '1') ? 'checked' : '' }}
                                   class="h-4 w-4 text-[#D63613] focus:ring-[#D63613] border-gray-300 rounded bg-white">
                            <label for="can_create_clients" class="ml-3 block text-sm font-medium text-[#201E1F]">
                                Can create client accounts
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="can_manage_orders" 
                                   name="can_manage_orders" 
                                   value="1"
                                   {{ old('can_manage_orders', '1') ? 'checked' : '' }}
                                   class="h-4 w-4 text-[#D63613] focus:ring-[#D63613] border-gray-300 rounded bg-white">
                            <label for="can_manage_orders" class="ml-3 block text-sm font-medium text-[#201E1F]">
                                Can manage client orders
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="can_view_reports" 
                                   name="can_view_reports" 
                                   value="1"
                                   {{ old('can_view_reports', '1') ? 'checked' : '' }}
                                   class="h-4 w-4 text-[#D63613] focus:ring-[#D63613] border-gray-300 rounded bg-white">
                            <label for="can_view_reports" class="ml-3 block text-sm font-medium text-[#201E1F]">
                                Can view sales reports
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="can_process_payments" 
                                   name="can_process_payments" 
                                   value="1"
                                   {{ old('can_process_payments', '0') ? 'checked' : '' }}
                                   class="h-4 w-4 text-[#D63613] focus:ring-[#D63613] border-gray-300 rounded bg-white">
                            <label for="can_process_payments" class="ml-3 block text-sm font-medium text-[#201E1F]">
                                Can process manual payments
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

        <!-- Admin Notes -->
        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md animate-fade-in-up" style="animation-delay: 0.4s;">
            <div class="px-6 py-5 border-b border-[#D63613]/10">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-gray-400 to-gray-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-[#201E1F]">Admin Notes</h3>
                        <p class="text-sm text-[#201E1F]/60">Optional internal notes about this reseller</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <label for="notes" class="block text-sm font-semibold text-[#201E1F] mb-2">Internal Notes</label>
                <textarea id="notes" 
                          name="notes" 
                          rows="3"
                          class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] placeholder-[#201E1F]/40 transition-all duration-200 resize-none"
                          placeholder="Internal notes about this reseller...">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Reseller Preview -->
        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md animate-fade-in-up" style="animation-delay: 0.5s;">
            <div class="px-6 py-5 border-b border-[#D63613]/10">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-[#201E1F]">Reseller Preview</h3>
                </div>
            </div>
            
            <div class="p-6">
                <div id="reseller-preview" class="bg-white rounded-lg p-4 border border-gray-200 space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-[#201E1F]/60">Name:</span>
                        <span id="preview-name" class="text-sm font-semibold text-[#201E1F]">Enter name above</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-[#201E1F]/60">Email:</span>
                        <span id="preview-email" class="text-sm font-semibold text-[#201E1F]">Enter email above</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-[#201E1F]/60">Phone:</span>
                        <span id="preview-phone" class="text-sm font-semibold text-[#201E1F]">Not provided</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-[#201E1F]/60">Email Verified:</span>
                        <span id="preview-verified" class="text-sm font-semibold text-green-600">Yes</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-[#201E1F]/60">Welcome Email:</span>
                        <span id="preview-welcome" class="text-sm font-semibold text-blue-600">Will be sent</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end space-x-4 animate-fade-in-up" style="animation-delay: 0.6s;">
            <a href="{{ route('admin.resellers.index') }}" 
               class="px-6 py-3 bg-white border border-gray-200 text-[#201E1F] font-semibold rounded-lg hover:bg-gray-50 transition-all duration-300">
                Cancel
            </a>
            <button type="submit" 
                    class="px-6 py-3 bg-gradient-to-r from-[#D63613] to-[#D63613]/80 text-white font-semibold rounded-lg hover:from-[#D63613]/90 hover:to-[#D63613]/70 transition-all duration-300 shadow-md hover:shadow-lg">
                Create Reseller
            </button>
        </div>
    </form>
</div>

<style>
@keyframes fade-in-up {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-up {
    animation: fade-in-up 0.6s ease-out forwards;
}
</style>

<script>
// Update preview when form fields change
function updatePreview() {
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const phoneInput = document.getElementById('phone');
    const emailVerifiedCheckbox = document.getElementById('email_verified');
    const welcomeEmailCheckbox = document.getElementById('send_welcome_email');
    
    // Update name preview
    const nameText = nameInput.value || 'Enter name above';
    document.getElementById('preview-name').textContent = nameText;
    
    // Update email preview
    const emailText = emailInput.value || 'Enter email above';
    document.getElementById('preview-email').textContent = emailText;
    
    // Update phone preview
    const phoneText = phoneInput.value || 'Not provided';
    document.getElementById('preview-phone').textContent = phoneText;
    
    // Update email verified status
    const verifiedElement = document.getElementById('preview-verified');
    if (emailVerifiedCheckbox.checked) {
        verifiedElement.textContent = 'Yes';
        verifiedElement.className = 'text-sm font-semibold text-green-600';
    } else {
        verifiedElement.textContent = 'No';
        verifiedElement.className = 'text-sm font-semibold text-red-600';
    }
    
    // Update welcome email status
    const welcomeElement = document.getElementById('preview-welcome');
    if (welcomeEmailCheckbox.checked) {
        welcomeElement.textContent = 'Will be sent';
        welcomeElement.className = 'text-sm font-semibold text-blue-600';
    } else {
        welcomeElement.textContent = 'Will not be sent';
        welcomeElement.className = 'text-sm font-semibold text-gray-600';
    }
}

// Add event listeners
document.getElementById('name').addEventListener('input', updatePreview);
document.getElementById('email').addEventListener('input', updatePreview);
document.getElementById('phone').addEventListener('input', updatePreview);
document.getElementById('email_verified').addEventListener('change', updatePreview);
document.getElementById('send_welcome_email').addEventListener('change', updatePreview);

// Initial preview update
updatePreview();
</script>
@endsection