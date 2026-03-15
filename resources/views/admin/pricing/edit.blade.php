@extends('layouts.admin')

@section('title', 'Edit Pricing Plan - ' . $pricingPlan->display_name)

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up">
        <div class="lg:flex space-y-4 justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-gradient-to-br from-[#D63613] to-[#D63613]/80 rounded-xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-[#201E1F] mb-1">Edit Pricing Plan</h1>
                    <p class="text-[#201E1F]/60">{{ $pricingPlan->display_name }}</p>
                </div>
            </div>
            <div class="lg:flex items-center lg:space-x-3 space-y-2">
                <a href="{{ route('admin.pricing.show', $pricingPlan) }}" 
                   class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-3 rounded-lg text-sm font-semibold flex items-center space-x-2 transition-all duration-300 shadow-md hover:shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    <span>View Plan</span>
                </a>
                <a href="{{ route('admin.pricing.index') }}" 
                   class="bg-white hover:bg-gray-50 text-[#201E1F]/80 hover:text-[#201E1F] border border-gray-200 px-6 py-3 rounded-lg text-sm font-semibold flex items-center space-x-2 transition-all duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Back to Pricing Plans</span>
                </a>
            </div>
        </div>
    </div>

    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md animate-fade-in-up" style="animation-delay: 0.1s;">
        <form action="{{ route('admin.pricing.update', $pricingPlan) }}" method="POST" class="p-6 space-y-8">
            @csrf
            @method('PUT')
            
            <!-- Basic Plan Configuration -->
            <div>
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-[#201E1F]">Basic Configuration</h3>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Server Type -->
                    <div class="space-y-2">
                        <label for="server_type" class="block text-sm font-medium text-[#201E1F]/60">Server Type</label>
                        <select id="server_type" 
                                name="server_type" 
                                class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300 @error('server_type') border-red-300 @enderror"
                                required>
                            <option value="">Select Server Type</option>
                            <option value="basic" {{ old('server_type', $pricingPlan->server_type) == 'basic' ? 'selected' : '' }}>Basic</option>
                            <option value="premium" {{ old('server_type', $pricingPlan->server_type) == 'premium' ? 'selected' : '' }}>Premium</option>
                            <option value="generic" {{ old('server_type', $pricingPlan->server_type) == 'generic' ? 'selected' : '' }}>Generic (Custom Name)</option>
                        </select>
                        @error('server_type')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Custom Label (shown when Generic is selected) -->
                    <div id="custom-label-wrapper" class="space-y-2" style="{{ old('server_type', $pricingPlan->server_type) === 'generic' ? '' : 'display: none;' }}">
                        <label for="custom_label" class="block text-sm font-medium text-[#201E1F]/60">Plan Display Name <span class="text-red-500">*</span></label>
                        <input type="text" 
                               id="custom_label" 
                               name="custom_label" 
                               value="{{ old('custom_label', $pricingPlan->custom_label) }}"
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300 @error('custom_label') border-red-300 @enderror"
                               placeholder="e.g. Standard, Gold, Enterprise">
                        <p class="text-xs text-[#201E1F]/50">This name will be displayed in checkout and pricing pages</p>
                        @error('custom_label')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Plan Type -->
                    <div class="space-y-2">
                        <label for="plan_type" class="block text-sm font-medium text-[#201E1F]/60">Plan Type</label>
                        <select id="plan_type"
                                name="plan_type"
                                class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300 @error('plan_type') border-red-300 @enderror"
                                required>
                            <option value="">Select Plan Type</option>
                            <option value="regular" {{ old('plan_type', $pricingPlan->plan_type) == 'regular' ? 'selected' : '' }}>Regular Client Plan</option>
                            <option value="reseller" {{ old('plan_type', $pricingPlan->plan_type) == 'reseller' ? 'selected' : '' }}>Reseller Plan</option>
                        </select>
                        @error('plan_type')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Device Count -->
                    <div class="space-y-2">
                        <label for="device_count" class="block text-sm font-medium text-[#201E1F]/60">Number of Devices</label>
                        <input type="number" 
                               id="device_count" 
                               name="device_count" 
                               value="{{ old('device_count', $pricingPlan->device_count) }}"
                               min="1" 
                               max="10"
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300 @error('device_count') border-red-300 @enderror"
                               required>
                        @error('device_count')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Duration -->
                    <div class="space-y-2">
                        <label for="duration_months" class="block text-sm font-medium text-[#201E1F]/60">Duration (Months)</label>
                        <select id="duration_months" 
                                name="duration_months" 
                                class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300 @error('duration_months') border-red-300 @enderror"
                                required>
                            <option value="">Select Duration</option>
                            <option value="1" {{ old('duration_months', $pricingPlan->duration_months) == '1' ? 'selected' : '' }}>1 Month</option>
                            <option value="3" {{ old('duration_months', $pricingPlan->duration_months) == '3' ? 'selected' : '' }}>3 Months</option>
                            <option value="6" {{ old('duration_months', $pricingPlan->duration_months) == '6' ? 'selected' : '' }}>6 Months</option>
                            <option value="12" {{ old('duration_months', $pricingPlan->duration_months) == '12' ? 'selected' : '' }}>12 Months</option>
                            <option value="24" {{ old('duration_months', $pricingPlan->duration_months) == '24' ? 'selected' : '' }}>24 Months</option>
                        </select>
                        @error('duration_months')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Price -->
                    <div class="space-y-2">
                        <label for="price" class="block text-sm font-medium text-[#201E1F]/60">Price ($)</label>
                        <input type="number" 
                               id="price" 
                               name="price" 
                               value="{{ old('price', $pricingPlan->price) }}"
                               min="0" 
                               step="0.01"
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300 @error('price') border-red-300 @enderror"
                               required>
                        @error('price')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Payment Link -->
                <div class="mt-6 space-y-2">
                    <label for="payment_link" class="block text-sm font-medium text-[#201E1F]/60">Payment Link (Optional)</label>
                    <input type="url" 
                           id="payment_link" 
                           name="payment_link" 
                           value="{{ old('payment_link', $pricingPlan->payment_link) }}"
                           class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300 @error('payment_link') border-red-300 @enderror"
                           placeholder="https://example.com/payment-link">
                    @error('payment_link')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="text-sm text-[#201E1F]/60">External payment link for this plan (if using third-party payment processor)</p>
                </div>
            </div>

            <!-- Features Section -->
            <div class="border-t border-gray-200 pt-8">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-[#201E1F]">Plan Features</h3>
                    </div>
                    <button type="button" onclick="addFeature()" 
                            class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-4 py-2 rounded-lg text-sm font-semibold flex items-center space-x-2 transition-all duration-300 shadow-md hover:shadow-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span>Add Feature</span>
                    </button>
                </div>
                
                <div id="features-container" class="space-y-3">
                    @if(old('features') || $pricingPlan->features)
                        @foreach(old('features', $pricingPlan->features ?? []) as $index => $feature)
                        <div class="feature-item flex items-center space-x-3">
                            <input type="text" 
                                   name="features[]" 
                                   value="{{ $feature }}"
                                   class="flex-1 px-4 py-3 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300"
                                   placeholder="Enter feature description">
                            <button type="button" onclick="removeFeature(this)" 
                                    class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-4 py-3 rounded-lg text-sm font-semibold transition-all duration-300 shadow-md hover:shadow-lg">
                                Remove
                            </button>
                        </div>
                        @endforeach
                    @else
                        <div class="feature-item flex items-center space-x-3">
                            <input type="text" 
                                   name="features[]" 
                                   class="flex-1 px-4 py-3 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300"
                                   placeholder="Enter feature description">
                            <button type="button" onclick="removeFeature(this)" 
                                    class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-4 py-3 rounded-lg text-sm font-semibold transition-all duration-300 shadow-md hover:shadow-lg">
                                Remove
                            </button>
                        </div>
                    @endif
                </div>
                @error('features')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status & Information -->
            <div class="border-t border-gray-200 pt-8 space-y-6">
                <!-- Status Toggle -->
                <div>
                    <div class="flex items-center space-x-3">
                        <input type="checkbox" 
                               id="is_active" 
                               name="is_active" 
                               value="1"
                               {{ old('is_active', $pricingPlan->is_active) ? 'checked' : '' }}
                               class="h-5 w-5 text-[#D63613] focus:ring-[#D63613] border-gray-300 rounded">
                        <label for="is_active" class="text-sm font-medium text-[#201E1F]">
                            Active (visible to customers)
                        </label>
                    </div>
                    @error('is_active')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Plan Information -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h4 class="text-lg font-medium text-[#201E1F] mb-4">Plan Statistics</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-[#201E1F]">{{ $pricingPlan->created_at->format('M d, Y') }}</div>
                            <div class="text-sm text-[#201E1F]/60">Created</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-[#201E1F]">{{ $pricingPlan->updated_at->format('M d, Y') }}</div>
                            <div class="text-sm text-[#201E1F]/60">Last Updated</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-[#201E1F]">{{ $pricingPlan->orders_count ?? 0 }}</div>
                            <div class="text-sm text-[#201E1F]/60">Total Orders</div>
                        </div>
                    </div>
                </div>

                <!-- Preview -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <h4 class="text-lg font-medium text-blue-900 mb-4">Plan Preview</h4>
                    <div id="plan-preview" class="space-y-2 text-sm">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <span class="font-medium text-blue-800">Current Name:</span>
                                <span class="ml-2 text-blue-900">{{ $pricingPlan->name }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-blue-800">Current Display Name:</span>
                                <span class="ml-2 text-blue-900">{{ $pricingPlan->display_name }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-blue-800">New Name:</span>
                                <span id="preview-name" class="ml-2 text-blue-900">{{ $pricingPlan->name }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-blue-800">New Display Name:</span>
                                <span id="preview-display-name" class="ml-2 text-blue-900">{{ $pricingPlan->display_name }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="border-t border-gray-200 pt-8 flex justify-end space-x-4">
                <a href="{{ route('admin.pricing.show', $pricingPlan) }}" 
                   class="bg-white hover:bg-gray-50 text-[#201E1F]/80 hover:text-[#201E1F] border border-gray-200 px-6 py-3 rounded-lg text-sm font-semibold transition-all duration-300">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-gradient-to-r from-[#D63613] to-[#D63613]/80 hover:from-[#D63613]/90 hover:to-[#D63613] text-white px-6 py-3 rounded-lg text-sm font-semibold transition-all duration-300 shadow-md hover:shadow-lg">
                    Update Pricing Plan
                </button>
            </div>
        </form>
    </div>

    <!-- Danger Zone -->
    <div class="bg-red-50 border border-red-200 rounded-xl p-6 animate-fade-in-up" style="animation-delay: 0.2s;">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-red-400 to-red-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-semibold text-red-900">Danger Zone</h3>
                    <p class="text-sm text-red-700">These actions are irreversible. Please be certain before proceeding.</p>
                </div>
            </div>
        </div>
        
        <form action="{{ route('admin.pricing.destroy', $pricingPlan) }}" method="POST" 
              onsubmit="return confirm('Are you sure you want to delete this pricing plan? This action cannot be undone and will affect all associated orders.')">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    class="bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white px-6 py-3 rounded-lg text-sm font-semibold flex items-center space-x-2 transition-all duration-300 shadow-md hover:shadow-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                <span>Delete Pricing Plan</span>
            </button>
        </form>
    </div>
</div>

<script>
function addFeature() {
    const container = document.getElementById('features-container');
    const featureItem = document.createElement('div');
    featureItem.className = 'feature-item flex items-center space-x-3';
    featureItem.innerHTML = `
        <input type="text" 
               name="features[]" 
               class="flex-1 px-4 py-3 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300"
               placeholder="Enter feature description">
        <button type="button" onclick="removeFeature(this)" 
                class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-4 py-3 rounded-lg text-sm font-semibold transition-all duration-300 shadow-md hover:shadow-lg">
            Remove
        </button>
    `;
    container.appendChild(featureItem);
}

function removeFeature(button) {
    const container = document.getElementById('features-container');
    if (container.children.length > 1) {
        button.parentElement.remove();
    }
}

function toggleCustomLabel() {
    const serverType = document.getElementById('server_type').value;
    const wrapper = document.getElementById('custom-label-wrapper');
    if (serverType === 'generic') {
        wrapper.style.display = '';
    } else {
        wrapper.style.display = 'none';
    }
}

function updatePreview() {
    const serverType = document.getElementById('server_type').value;
    const customLabel = document.getElementById('custom_label').value;
    const deviceCount = document.getElementById('device_count').value;
    const duration = document.getElementById('duration_months').value;

    toggleCustomLabel();

    const serverLabel = serverType === 'generic'
        ? (customLabel || 'Custom')
        : (serverType ? serverType.charAt(0).toUpperCase() + serverType.slice(1) : '');
    
    if (serverLabel && deviceCount && duration) {
        const name = `${serverLabel} - ${deviceCount} Device${deviceCount > 1 ? 's' : ''} - ${duration} Month${duration > 1 ? 's' : ''}`;
        document.getElementById('preview-name').textContent = name;
        document.getElementById('preview-display-name').textContent = name;
    }
}

// Add event listeners for preview updates
document.getElementById('server_type').addEventListener('change', updatePreview);
document.getElementById('custom_label').addEventListener('input', updatePreview);
document.getElementById('device_count').addEventListener('input', updatePreview);
document.getElementById('duration_months').addEventListener('change', updatePreview);

// Initial preview update
updatePreview();
</script>

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
@endsection