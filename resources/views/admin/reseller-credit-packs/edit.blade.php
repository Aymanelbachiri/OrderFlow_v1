@extends('layouts.admin')

@section('title', 'Edit Reseller Credit Pack')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up">
        <div class="lg:flex space-y-4 justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-[#201E1F] mb-2">Edit Credit Pack</h1>
                <p class="text-[#201E1F]/60">Modify credit package details and configuration</p>
            </div>
            <div class="lg:flex  items-center lg:space-x-3 space-y-2">
                <a href="{{ route('admin.reseller-credit-packs.show', $resellerCreditPack) }}" 
                   class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-3 rounded-lg text-sm font-semibold flex items-center space-x-2 transition-all duration-300 shadow-md hover:shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    <span>View Pack</span>
                </a>
                <a href="{{ route('admin.reseller-credit-packs.index') }}" 
                   class="bg-white hover:bg-gray-50 text-[#201E1F]/80 hover:text-[#201E1F] border border-gray-200 px-6 py-3 rounded-lg text-sm font-semibold flex items-center space-x-2 transition-all duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Back to Credit Packs</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Error Messages -->
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-lg animate-fade-in-up" style="animation-delay: 0.1s;">
            <div class="flex items-start">
                <svg class="w-5 h-5 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h4 class="font-semibold mb-1">Please fix the following errors:</h4>
                    <ul class="list-disc list-inside space-y-1 text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.reseller-credit-packs.update', $resellerCreditPack) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')
        
        <!-- Basic Information -->
        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md animate-fade-in-up" style="animation-delay: 0.2s;">
            <div class="px-6 py-5 border-b border-[#D63613]/10">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-[#201E1F]">Basic Information</h3>
                </div>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Pack Name -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-[#201E1F] mb-2">Pack Name</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $resellerCreditPack->name) }}"
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] placeholder-[#201E1F]/40 transition-all duration-200 @error('name') border-red-500 @enderror"
                               placeholder="e.g., 100 Credits Pack"
                               required>
                        @error('name')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Number of Credits -->
                    <div>
                        <label for="credits_amount" class="block text-sm font-semibold text-[#201E1F] mb-2">Number of Credits</label>
                        <input type="number" 
                               id="credits_amount" 
                               name="credits_amount" 
                               value="{{ old('credits_amount', $resellerCreditPack->credits_amount) }}"
                               min="1"
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] placeholder-[#201E1F]/40 transition-all duration-200 @error('credits_amount') border-red-500 @enderror"
                               placeholder="100"
                               required>
                        @error('credits_amount')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Price -->
                    <div>
                        <label for="price" class="block text-sm font-semibold text-[#201E1F] mb-2">Price ($)</label>
                        <input type="number" 
                               id="price" 
                               name="price" 
                               value="{{ old('price', $resellerCreditPack->price) }}"
                               min="0"
                               step="0.01"
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] placeholder-[#201E1F]/40 transition-all duration-200 @error('price') border-red-500 @enderror"
                               placeholder="99.99"
                               required>
                        @error('price')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="is_active" class="block text-sm font-semibold text-[#201E1F] mb-2">Status</label>
                        <select id="is_active" 
                                name="is_active" 
                                class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] transition-all duration-200">
                            <option value="1" {{ old('is_active', $resellerCreditPack->is_active) == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active', $resellerCreditPack->is_active) == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('is_active')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md animate-fade-in-up" style="animation-delay: 0.3s;">
            <div class="px-6 py-5 border-b border-[#D63613]/10">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-[#201E1F]">Package Features</h3>
                        <p class="text-sm text-[#201E1F]/60">Add features and benefits for this credit pack</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <div id="features-container" class="space-y-3">
                        @if(old('features', $resellerCreditPack->features))
                            @foreach(old('features', $resellerCreditPack->features) as $index => $feature)
                                <div class="feature-input flex items-center space-x-3">
                                    <div class="flex-1">
                                        <input type="text"
                                               name="features[]"
                                               value="{{ $feature }}"
                                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] placeholder-[#201E1F]/40 transition-all duration-200"
                                               placeholder="e.g., Priority Support">
                                    </div>
                                    <button type="button" class="remove-feature bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-4 py-3 rounded-lg font-semibold transition-all duration-300 flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        <span>Remove</span>
                                    </button>
                                </div>
                            @endforeach
                        @else
                            <div class="feature-input flex items-center space-x-3">
                                <div class="flex-1">
                                    <input type="text"
                                           name="features[]"
                                           class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] placeholder-[#201E1F]/40 transition-all duration-200"
                                           placeholder="e.g., Priority Support">
                                </div>
                                <button type="button" class="remove-feature bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-4 py-3 rounded-lg font-semibold transition-all duration-300 flex items-center space-x-2" style="display: none;">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    <span>Remove</span>
                                </button>
                            </div>
                        @endif
                    </div>
                    <button type="button" id="add-feature" class="mt-4 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-4 py-2 rounded-lg font-semibold transition-all duration-300 flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>Add Feature</span>
                    </button>
                    @error('features')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Payment Methods Section -->
        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md animate-fade-in-up" style="animation-delay: 0.4s;">
            <div class="px-6 py-5 border-b border-[#D63613]/10">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-cyan-400 to-cyan-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-[#201E1F]">Payment Methods</h3>
                        <p class="text-sm text-[#201E1F]/60">Select accepted payment options</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <div class="bg-white p-6 rounded-lg border border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-150">
                            <input type="checkbox" name="payment_methods[]" value="stripe"
                                   {{ in_array('stripe', old('payment_methods', $resellerCreditPack->payment_methods ?? [])) ? 'checked' : '' }}
                                   class="h-4 w-4 text-[#D63613] focus:ring-[#D63613] border-gray-300 rounded bg-white">
                            <div class="ml-3 flex items-center">
                                <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                                <span class="text-sm font-medium text-[#201E1F]">Stripe</span>
                            </div>
                        </label>
                        
                        <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-150">
                            <input type="checkbox" name="payment_methods[]" value="paypal"
                                   {{ in_array('paypal', old('payment_methods', $resellerCreditPack->payment_methods ?? [])) ? 'checked' : '' }}
                                   class="h-4 w-4 text-[#D63613] focus:ring-[#D63613] border-gray-300 rounded bg-white">
                            <div class="ml-3 flex items-center">
                                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span class="text-sm font-medium text-[#201E1F]">PayPal</span>
                            </div>
                        </label>
                        
                        <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-150">
                            <input type="checkbox" name="payment_methods[]" value="crypto"
                                   {{ in_array('crypto', old('payment_methods', $resellerCreditPack->payment_methods ?? [])) ? 'checked' : '' }}
                                   class="h-4 w-4 text-[#D63613] focus:ring-[#D63613] border-gray-300 rounded bg-white">
                            <div class="ml-3 flex items-center">
                                <svg class="w-5 h-5 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                                <span class="text-sm font-medium text-[#201E1F]">Crypto</span>
                            </div>
                        </label>
                        
                        <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-150">
                            <input type="checkbox" name="payment_methods[]" value="bank_transfer"
                                   {{ in_array('bank_transfer', old('payment_methods', $resellerCreditPack->payment_methods ?? [])) ? 'checked' : '' }}
                                   class="h-4 w-4 text-[#D63613] focus:ring-[#D63613] border-gray-300 rounded bg-white">
                            <div class="ml-3 flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                                </svg>
                                <span class="text-sm font-medium text-[#201E1F]">Bank Transfer</span>
                            </div>
                        </label>
                    </div>
                    @error('payment_methods')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end space-x-4 animate-fade-in-up" style="animation-delay: 0.5s;">
            <a href="{{ route('admin.reseller-credit-packs.index') }}" 
               class="px-6 py-3 bg-white border border-gray-200 text-[#201E1F] font-semibold rounded-lg hover:bg-gray-50 transition-all duration-300">
                Cancel
            </a>
            <button type="submit" 
                    class="px-6 py-3 bg-gradient-to-r from-[#D63613] to-[#D63613]/80 text-white font-semibold rounded-lg hover:from-[#D63613]/90 hover:to-[#D63613]/70 transition-all duration-300 shadow-md hover:shadow-lg flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                </svg>
                <span>Update Credit Pack</span>
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
document.addEventListener('DOMContentLoaded', function() {
    // Features management
    const featuresContainer = document.getElementById('features-container');
    const addFeatureBtn = document.getElementById('add-feature');

    function updateRemoveButtons() {
        const featureInputs = featuresContainer.querySelectorAll('.feature-input');
        featureInputs.forEach((input, index) => {
            const removeBtn = input.querySelector('.remove-feature');
            if (featureInputs.length > 1) {
                removeBtn.style.display = 'flex';
            } else {
                removeBtn.style.display = 'none';
            }
        });
    }

    addFeatureBtn.addEventListener('click', function() {
        const newFeatureInput = document.createElement('div');
        newFeatureInput.className = 'feature-input flex items-center space-x-3';
        newFeatureInput.innerHTML = `
            <div class="flex-1">
                <input type="text"
                       name="features[]"
                       class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] placeholder-[#201E1F]/40 transition-all duration-200"
                       placeholder="e.g., Priority Support">
            </div>
            <button type="button" class="remove-feature bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-4 py-3 rounded-lg font-semibold transition-all duration-300 flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                <span>Remove</span>
            </button>
        `;

        featuresContainer.appendChild(newFeatureInput);
        updateRemoveButtons();

        // Add event listener to the new remove button
        newFeatureInput.querySelector('.remove-feature').addEventListener('click', function() {
            newFeatureInput.remove();
            updateRemoveButtons();
        });
    });

    // Add event listeners to existing remove buttons
    featuresContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-feature')) {
            e.target.closest('.feature-input').remove();
            updateRemoveButtons();
        }
    });

    updateRemoveButtons();
});
</script>
@endsection