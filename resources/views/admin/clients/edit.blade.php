@extends('layouts.admin')

@section('title', 'Edit Client - ' . $client->name)

@section('content')
<div class="space-y-8">
    <div class="lg:flex space-y-4 items-center justify-between animate-fade-in-up">
        <div>
            <h1 class="text-3xl font-bold text-[#201E1F] mb-2">Edit Client</h1>
            <p class="text-[#201E1F]/60">Update client information and account settings</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.clients.show', $client) }}" 
               class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-300 shadow-md hover:shadow-lg">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                View Client
            </a>
            <a href="{{ route('admin.clients.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 text-[#201E1F] font-semibold rounded-xl hover:bg-gray-50 transition-all duration-300">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Clients
            </a>
        </div>
    </div>

    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md animate-fade-in-up" style="animation-delay: 0.1s;">
        <div class="px-6 py-5 border-b border-[#D63613]/10">
            <h3 class="text-xl font-semibold text-[#201E1F]">Client Information</h3>
        </div>
        
        <form action="{{ route('admin.clients.update', $client) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-semibold text-[#201E1F] mb-2">Full Name</label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $client->name) }}"
                           class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] placeholder-[#201E1F]/40 transition-all duration-200 @error('name') border-red-500 @enderror"
                           required>
                    @error('name')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-[#201E1F] mb-2">Email Address</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', $client->email) }}"
                           class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] placeholder-[#201E1F]/40 transition-all duration-200 @error('email') border-red-500 @enderror"
                           required>
                    @error('email')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-semibold text-[#201E1F] mb-2">Phone Number</label>
                    <input type="text" 
                           id="phone" 
                           name="phone" 
                           value="{{ old('phone', $client->phone) }}"
                           class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] placeholder-[#201E1F]/40 transition-all duration-200 @error('phone') border-red-500 @enderror">
                    @error('phone')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="source" class="block text-sm font-semibold text-[#201E1F] mb-2">Source (Optional)</label>
                    <select id="source" 
                            name="source" 
                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] transition-all duration-200 @error('source') border-red-500 @enderror">
                        <option value="">No Source</option>
                        @foreach($sources as $source)
                        <option value="{{ $source->name }}" {{ old('source', $client->source) == $source->name ? 'selected' : '' }}>
                            {{ $source->name }}@if(!$source->is_active) (Inactive)@endif
                        </option>
                        @endforeach
                    </select>
                    @error('source')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-[#201E1F]/50">Select the source where this client originated from</p>
                </div>

                <div>
                    <label for="role" class="block text-sm font-semibold text-[#201E1F] mb-2">Client Role</label>
                    <div class="text-xs text-gray-500 mb-2">
                        Current: {{ ucfirst($client->role) }}
                    </div>
                    <select id="role" 
                            name="role" 
                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] transition-all duration-200 @error('role') border-red-500 @enderror"
                            required>
                        <option value="client" {{ old('role', $client->role) == 'client' ? 'selected' : '' }}>Client</option>
                        <option value="reseller" {{ old('role', $client->role) == 'reseller' ? 'selected' : '' }}>Reseller</option>
                    </select>
                    @error('role')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">
                        <span class="text-orange-600">⚠️</span> Changing the role may affect access to different features and pricing plans.
                    </p>
                </div>

                <div>
                    <label for="is_active" class="block text-sm font-semibold text-[#201E1F] mb-2">Account Status</label>
                    <select id="is_active" 
                            name="is_active" 
                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] transition-all duration-200">
                        <option value="1" {{ old('is_active', $client->is_active) == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('is_active', $client->is_active) == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('is_active')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            @if($client->isClient())
            <div class="mt-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-sm text-blue-800">
                    <strong>Note:</strong> Clients do not require email verification or passwords. Email verification is automatically handled for client accounts.
                </p>
            </div>
            @else
            <div class="mt-8 p-4 bg-white rounded-lg border border-gray-200">
                <label class="block text-sm font-semibold text-[#201E1F] mb-3">Email Verification Status</label>
                <div class="flex items-center space-x-6">
                    <div class="flex items-center">
                        <input type="radio" 
                               id="email_verified_yes" 
                               name="email_verified" 
                               value="1"
                               {{ old('email_verified', $client->email_verified_at ? '1' : '0') == '1' ? 'checked' : '' }}
                               class="h-4 w-4 text-[#D63613] focus:ring-[#D63613] border-gray-300 bg-white">
                        <label for="email_verified_yes" class="ml-2 block text-sm text-[#201E1F]">Verified</label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" 
                               id="email_verified_no" 
                               name="email_verified" 
                               value="0"
                               {{ old('email_verified', $client->email_verified_at ? '1' : '0') == '0' ? 'checked' : '' }}
                               class="h-4 w-4 text-[#D63613] focus:ring-[#D63613] border-gray-300 bg-white">
                        <label for="email_verified_no" class="ml-2 block text-sm text-[#201E1F]">Unverified</label>
                    </div>
                </div>
                <p class="mt-3 text-sm text-[#201E1F]/60">
                    Current status: 
                    <span class="font-medium {{ $client->email_verified_at ? 'text-green-600' : 'text-yellow-600' }}">
                        {{ $client->email_verified_at ? 'Verified on ' . $client->email_verified_at->format('M d, Y') : 'Unverified' }}
                    </span>
                </p>
            </div>
            @endif

            <div class="mt-8">
                <label for="notes" class="block text-sm font-semibold text-[#201E1F] mb-2">Admin Notes</label>
                <textarea id="notes" 
                          name="notes" 
                          rows="4"
                          class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] placeholder-[#201E1F]/40 transition-all duration-200 resize-none"
                          placeholder="Internal notes about this client...">{{ old('notes', $client->notes) }}</textarea>
                @error('notes')
                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-8 p-6 bg-gradient-to-r from-[#D63613]/10 to-[#D63613]/5 rounded-lg border border-[#D63613]/20">
                <h4 class="text-lg font-semibold text-[#201E1F] mb-4 flex items-center">
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    Account Information
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="flex flex-col">
                        <span class="text-sm text-[#201E1F]/60 mb-1">Member Since</span>
                        <span class="font-semibold text-[#201E1F]">{{ $client->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-sm text-[#201E1F]/60 mb-1">Last Login</span>
                        <span class="font-semibold text-[#201E1F]">{{ $client->last_login_at ? $client->last_login_at->format('M d, Y H:i') : 'Never' }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-sm text-[#201E1F]/60 mb-1">Total Orders</span>
                        <span class="font-semibold text-[#201E1F]">{{ $client->orders->count() }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('admin.clients.show', $client) }}" 
                   class="px-6 py-3 bg-white border border-gray-200 text-[#201E1F] font-semibold rounded-lg hover:bg-gray-50 transition-all duration-300">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-gradient-to-r from-[#D63613] to-[#D63613]/80 text-white font-semibold rounded-lg hover:from-[#D63613]/90 hover:to-[#D63613]/70 transition-all duration-300 shadow-md hover:shadow-lg">
                    Update Client
                </button>
            </div>
        </form>
    </div>

    <div class="bg-gradient-to-r from-red-50 to-red-50/50 border border-red-200 rounded-xl p-6 animate-fade-in-up" style="animation-delay: 0.3s;">
        <div class="flex items-center mb-4">
            <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-red-600">Danger Zone</h3>
                <p class="text-sm text-red-500">These actions are irreversible. Please be certain before proceeding.</p>
            </div>
        </div>
        
        <form id="delete-client-form" action="{{ route('admin.clients.destroy', $client) }}" method="POST">
            @csrf
            @method('DELETE')
        </form>

        <button type="button" 
                id="open-delete-modal-button"
                class="px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold rounded-lg hover:from-red-700 hover:to-red-800 transition-all duration-300 shadow-md hover:shadow-lg">
            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
            Delete Client
        </button>
    </div>
</div>


<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden animate-fade-in">
    <div class="bg-white rounded-xl border border-red-200 shadow-2xl p-8 max-w-md w-full m-4 animate-scale-in">
        <div class="flex items-start">
            <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-full flex items-center justify-center mr-4 shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-xl font-bold text-red-600">Confirm Deletion</h3>
                <p class="mt-2 text-[#201E1F]/80">Are you sure you want to delete this client? This action is irreversible and will permanently delete all associated orders and data.</p>
            </div>
        </div>
        <div class="mt-8 flex justify-end space-x-4">
            <button type="button" id="cancelDelete" class="px-6 py-2 bg-white border border-gray-200 text-[#201E1F] font-semibold rounded-lg hover:bg-gray-50 transition-all duration-300">
                Cancel
            </button>
            <button type="button" id="confirmDelete" class="px-6 py-2 bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold rounded-lg hover:from-red-700 hover:to-red-800 transition-all duration-300 shadow-md hover:shadow-lg">
                Yes, Delete Client
            </button>
        </div>
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

@keyframes fade-in {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes scale-in {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.animate-fade-in-up {
    animation: fade-in-up 0.6s ease-out forwards;
}

.animate-fade-in {
    animation: fade-in 0.3s ease-out forwards;
}

.animate-scale-in {
    animation: scale-in 0.3s ease-out forwards;
}
</style>

<script>

// ===== NEW SCRIPT FOR MODAL =====
document.addEventListener('DOMContentLoaded', function () {
    const deleteModal = document.getElementById('deleteModal');
    const openModalButton = document.getElementById('open-delete-modal-button');
    const cancelDeleteButton = document.getElementById('cancelDelete');
    const confirmDeleteButton = document.getElementById('confirmDelete');
    const deleteForm = document.getElementById('delete-client-form');

    if (openModalButton) {
        openModalButton.addEventListener('click', function () {
            deleteModal.classList.remove('hidden');
        });
    }
    
    if (cancelDeleteButton) {
        cancelDeleteButton.addEventListener('click', function () {
            deleteModal.classList.add('hidden');
        });
    }
    
    // Also hide modal if clicking on the background overlay
    if (deleteModal) {
        deleteModal.addEventListener('click', function(event) {
            if (event.target === deleteModal) {
                deleteModal.classList.add('hidden');
            }
        });
    }

    if (confirmDeleteButton) {
        confirmDeleteButton.addEventListener('click', function () {
            if(deleteForm) {
                deleteForm.submit();
            }
        });
    }
});
</script>
@endsection