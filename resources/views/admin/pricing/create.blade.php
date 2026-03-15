@extends('layouts.admin')

@section('title', 'Create Pricing Plan')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up">
        <div class="lg:flex space-y-4 justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-[#201E1F] mb-2">Create New Pricing Plan</h1>
                <p class="text-[#201E1F]/60">Configure a new pricing plan for your customers</p>
            </div>
            <div class="lg:flex  items-center lg:space-x-3 space-y-2">
                <a href="{{ route('admin.pricing.index') }}" 
                   class="bg-white hover:bg-gray-50 text-[#201E1F]/80 hover:text-[#201E1F] border border-gray-200 px-6 py-3 rounded-lg text-sm font-semibold flex items-center space-x-2 transition-all duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Back to Plans</span>
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Form Section -->
        <div class="lg:col-span-2">
            <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md animate-fade-in-up" style="animation-delay: 0.2s;">
                <div class="px-6 py-5 border-b border-[#D63613]/10">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-[#201E1F]">Plan Configuration</h2>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.pricing.store') }}" class="p-6 space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Server Type -->
                        <div>
                            <label for="server_type" class="block text-sm font-semibold text-[#201E1F] mb-2">Server Type</label>
                            <div class="relative">
                                <select id="server_type" name="server_type" required 
                                        class="w-full bg-white border border-gray-200 rounded-lg px-4 py-3 text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300 appearance-none">
                                    <option value="">Select Server Type</option>
                                    <option value="basic" {{ old('server_type') === 'basic' ? 'selected' : '' }}>Basic</option>
                                    <option value="premium" {{ old('server_type') === 'premium' ? 'selected' : '' }}>Premium</option>
                                    <option value="generic" {{ old('server_type') === 'generic' ? 'selected' : '' }}>Generic (Custom Name)</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-[#201E1F]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('server_type')
                                <p class="mt-2 text-sm text-red-600 flex items-center space-x-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>{{ $message }}</span>
                                </p>
                            @enderror
                        </div>

                        <!-- Custom Label (shown when Generic is selected) -->
                        <div id="custom-label-wrapper" style="{{ old('server_type') === 'generic' ? '' : 'display: none;' }}">
                            <label for="custom_label" class="block text-sm font-semibold text-[#201E1F] mb-2">Plan Display Name <span class="text-red-500">*</span></label>
                            <input type="text" id="custom_label" name="custom_label" value="{{ old('custom_label') }}" 
                                   class="w-full bg-white border border-gray-200 rounded-lg px-4 py-3 text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300"
                                   placeholder="e.g. Standard, Gold, Enterprise">
                            <p class="mt-1 text-xs text-[#201E1F]/50">This name will be displayed in checkout and pricing pages</p>
                            @error('custom_label')
                                <p class="mt-2 text-sm text-red-600 flex items-center space-x-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>{{ $message }}</span>
                                </p>
                            @enderror
                        </div>

                        <!-- Plan Type -->
                        <div>
                            <label for="plan_type" class="block text-sm font-semibold text-[#201E1F] mb-2">Plan Type</label>
                            <div class="relative">
                                <select id="plan_type" name="plan_type" required 
                                        class="w-full bg-white border border-gray-200 rounded-lg px-4 py-3 text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300 appearance-none">
                                    <option value="">Select Plan Type</option>
                                    <option value="regular" {{ old('plan_type') === 'regular' ? 'selected' : '' }}>Regular Client Plan</option>
                                    <option value="reseller" {{ old('plan_type') === 'reseller' ? 'selected' : '' }}>Reseller Plan</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-[#201E1F]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('plan_type')
                                <p class="mt-2 text-sm text-red-600 flex items-center space-x-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>{{ $message }}</span>
                                </p>
                            @enderror
                        </div>

                        <!-- Device Count -->
                        <div>
                            <label for="device_count" class="block text-sm font-semibold text-[#201E1F] mb-2">Number of Devices</label>
                            <div class="relative">
                                <select id="device_count" name="device_count" required 
                                        class="w-full bg-white border border-gray-200 rounded-lg px-4 py-3 text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300 appearance-none">
                                    <option value="">Select Device Count</option>
                                    @for($i = 1; $i <= 4; $i++)
                                        <option value="{{ $i }}" {{ old('device_count') == $i ? 'selected' : '' }}>{{ $i }} Device{{ $i > 1 ? 's' : '' }}</option>
                                    @endfor
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-[#201E1F]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('device_count')
                                <p class="mt-2 text-sm text-red-600 flex items-center space-x-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>{{ $message }}</span>
                                </p>
                            @enderror
                        </div>

                        <!-- Duration -->
                        <div>
                            <label for="duration_months" class="block text-sm font-semibold text-[#201E1F] mb-2">Duration (Months)</label>
                            <div class="relative">
                                <select id="duration_months" name="duration_months" required 
                                        class="w-full bg-white border border-gray-200 rounded-lg px-4 py-3 text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300 appearance-none">
                                    <option value="">Select Duration</option>
                                    @foreach([1, 3, 6, 12] as $duration)
                                        <option value="{{ $duration }}" {{ old('duration_months') == $duration ? 'selected' : '' }}>{{ $duration }} Month{{ $duration > 1 ? 's' : '' }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-[#201E1F]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('duration_months')
                                <p class="mt-2 text-sm text-red-600 flex items-center space-x-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>{{ $message }}</span>
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Price -->
                    <div>
                        <label for="price" class="block text-sm font-semibold text-[#201E1F] mb-2">Price ($)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-[#201E1F]/60 font-medium">$</span>
                            </div>
                            <input type="number" step="0.01" id="price" name="price" value="{{ old('price') }}" required 
                                   class="w-full bg-white border border-gray-200 rounded-lg pl-8 pr-4 py-3 text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300"
                                   placeholder="0.00">
                        </div>
                        @error('price')
                            <p class="mt-2 text-sm text-red-600 flex items-center space-x-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <!-- Features -->
                    <div>
                        <label class="block text-sm font-semibold text-[#201E1F] mb-3">Plan Features</label>
                        <div class="bg-white p-4 rounded-lg border border-gray-200">
                            <div id="features-container" class="space-y-3">
                                @if(old('features'))
                                    @foreach(old('features') as $index => $feature)
                                        <div class="flex items-center space-x-3 feature-row">
                                            <div class="w-6 h-6 bg-gradient-to-br from-green-400 to-green-600 rounded-md flex items-center justify-center flex-shrink-0">
                                                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                            <input type="text" name="features[]" value="{{ $feature }}" 
                                                   class="flex-1 bg-white border border-gray-200 rounded-lg px-3 py-2 text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300"
                                                   placeholder="Enter feature">
                                            <button type="button" onclick="removeFeature(this)" 
                                                    class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-3 py-2 rounded-lg text-sm font-semibold transition-all duration-300 shadow-md hover:shadow-lg">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="flex items-center space-x-3 feature-row">
                                        <div class="w-6 h-6 bg-gradient-to-br from-green-400 to-green-600 rounded-md flex items-center justify-center flex-shrink-0">
                                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <input type="text" name="features[]" placeholder="Enter feature" 
                                               class="flex-1 bg-white border border-gray-200 rounded-lg px-3 py-2 text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300">
                                        <button type="button" onclick="removeFeature(this)" 
                                                class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-3 py-2 rounded-lg text-sm font-semibold transition-all duration-300 shadow-md hover:shadow-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <button type="button" onclick="addFeature()" 
                                    class="mt-4 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-4 py-2 rounded-lg text-sm font-semibold flex items-center space-x-2 transition-all duration-300 shadow-md hover:shadow-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                <span>Add Feature</span>
                            </button>
                        </div>
                    </div>

                    <!-- Payment Link -->
                    <div>
                        <label for="payment_link" class="block text-sm font-semibold text-[#201E1F] mb-2">Custom Payment Link (Optional)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-[#201E1F]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                </svg>
                            </div>
                            <input type="url" id="payment_link" name="payment_link" value="{{ old('payment_link') }}" 
                                   class="w-full bg-white border border-gray-200 rounded-lg pl-10 pr-4 py-3 text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300"
                                   placeholder="https://example.com/payment">
                        </div>
                        @error('payment_link')
                            <p class="mt-2 text-sm text-red-600 flex items-center space-x-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <!-- Active Status -->
                    <div class="bg-white p-4 rounded-lg border border-gray-200">
                        <div class="flex items-center space-x-3">
                            <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                   class="w-5 h-5 text-[#D63613] bg-white border-gray-300 rounded focus:ring-[#D63613] focus:ring-2 transition-all duration-300">
                            <label for="is_active" class="flex items-center space-x-2 text-[#201E1F] font-medium cursor-pointer">
                                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Active (visible to customers)</span>
                            </label>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-between pt-6 border-t border-[#D63613]/10">
                        <a href="{{ route('admin.pricing.index') }}" 
                           class="bg-white hover:bg-gray-50 text-[#201E1F]/80 hover:text-[#201E1F] border border-gray-200 px-8 py-3 rounded-lg font-semibold flex items-center space-x-2 transition-all duration-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <span>Cancel</span>
                        </a>
                        <button type="submit" 
                                class="bg-gradient-to-r from-[#D63613] to-[#D63613]/80 hover:from-[#D63613]/90 hover:to-[#D63613] text-white px-8 py-3 rounded-lg font-semibold flex items-center space-x-2 transition-all duration-300 shadow-md hover:shadow-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Create Plan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Live Preview Sidebar -->
        <div class="lg:col-span-1 space-y-8">
            <!-- Live Preview -->
            <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md animate-fade-in-up" style="animation-delay: 0.3s;">
                <div class="px-6 py-5 border-b border-[#D63613]/10">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-[#201E1F]">Live Preview</h2>
                    </div>
                </div>
                
                <div class="p-6">
                    <!-- Preview Card -->
                    <div id="preview-card" class="bg-white border-2 border-gray-200 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
                        <div class="text-center mb-6">
                            <h3 id="preview-name" class="text-xl font-bold text-[#201E1F] mb-3">New Plan</h3>
                            <div class="flex items-baseline justify-center space-x-1">
                                <span id="preview-price" class="text-4xl font-bold text-[#D63613]">$0.00</span>
                                <span id="preview-duration" class="text-[#201E1F]/60">/ 1 month</span>
                            </div>
                            <p id="preview-monthly" class="text-sm text-[#201E1F]/60 mt-2">$0.00/month</p>
                        </div>
                        
                        <div class="space-y-3 text-sm mb-6">
                            <div class="flex justify-between items-center p-2 bg-gray-50 rounded-lg">
                                <span class="text-[#201E1F]/70">Server:</span>
                                <span id="preview-server" class="font-semibold text-[#201E1F]">-</span>
                            </div>
                            <div class="flex justify-between items-center p-2 bg-gray-50 rounded-lg">
                                <span class="text-[#201E1F]/70">Devices:</span>
                                <span id="preview-devices" class="font-semibold text-[#201E1F]">-</span>
                            </div>
                            <div class="flex justify-between items-center p-2 bg-gray-50 rounded-lg">
                                <span class="text-[#201E1F]/70">Type:</span>
                                <span id="preview-type" class="font-semibold text-[#201E1F]">-</span>
                            </div>
                        </div>

                        <div id="preview-features-container" class="mb-6 pt-4 border-t border-gray-200" style="display: none;">
                            <ul id="preview-features" class="space-y-2">
                                <!-- Features will be dynamically added here -->
                            </ul>
                        </div>
                        
                        <button class="w-full bg-gray-100 text-[#201E1F]/60 py-3 px-4 rounded-lg text-sm font-semibold cursor-not-allowed flex items-center justify-center space-x-2" disabled>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <span>Preview Only</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Help & Tips -->
            <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md animate-fade-in-up" style="animation-delay: 0.4s;">
                <div class="px-6 py-5 border-b border-[#D63613]/10">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-amber-400 to-amber-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-[#201E1F]">Tips & Guidelines</h2>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-start space-x-3">
                                <svg class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <h4 class="font-semibold text-blue-700 text-sm mb-1">Pricing Strategy</h4>
                                    <p class="text-blue-600 text-sm">Consider offering discounts for longer duration plans to encourage customer loyalty.</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-start space-x-3">
                                <svg class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <h4 class="font-semibold text-green-700 text-sm mb-1">Features</h4>
                                    <p class="text-green-600 text-sm">Add clear, concise features that highlight the value proposition of each plan.</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                            <div class="flex items-start space-x-3">
                                <svg class="w-5 h-5 text-amber-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <h4 class="font-semibold text-amber-700 text-sm mb-1">Server Types</h4>
                                    <p class="text-amber-600 text-sm">Premium servers typically offer better performance and should be priced accordingly.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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

/* Custom select arrow styling */
select {
    background-image: none;
}

/* Focus states for form elements */
input:focus, select:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(214, 54, 19, 0.1);
}

/* Checkbox styling */
input[type="checkbox"]:checked {
    background-color: #D63613;
    border-color: #D63613;
}
</style>

<script>
function addFeature() {
    const container = document.getElementById('features-container');
    const newRow = document.createElement('div');
    newRow.className = 'flex items-center space-x-3 feature-row';
    newRow.innerHTML = `
        <div class="w-6 h-6 bg-gradient-to-br from-green-400 to-green-600 rounded-md flex items-center justify-center flex-shrink-0">
            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <input type="text" name="features[]" placeholder="Enter feature" 
               class="flex-1 bg-white border border-gray-200 rounded-lg px-3 py-2 text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300" 
               oninput="updatePreview()">
        <button type="button" onclick="removeFeature(this)" 
                class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-3 py-2 rounded-lg text-sm font-semibold transition-all duration-300 shadow-md hover:shadow-lg">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
        </button>
    `;
    container.appendChild(newRow);
    updatePreview();
}

function removeFeature(button) {
    const container = document.getElementById('features-container');
    if (container.children.length > 1) {
        button.parentElement.remove();
        updatePreview();
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
    // Get form values
    const serverType = document.getElementById('server_type').value;
    const customLabel = document.getElementById('custom_label').value;
    const planType = document.getElementById('plan_type').value;
    const deviceCount = document.getElementById('device_count').value;
    const duration = document.getElementById('duration_months').value;
    const price = document.getElementById('price').value;

    toggleCustomLabel();

    const serverLabel = serverType === 'generic'
        ? (customLabel || 'Custom')
        : (serverType ? serverType.charAt(0).toUpperCase() + serverType.slice(1) : '-');
    
    // Update preview elements
    document.getElementById('preview-server').textContent = serverLabel;
    document.getElementById('preview-devices').textContent = deviceCount ? deviceCount : '-';
    document.getElementById('preview-type').textContent = planType ? (planType === 'regular' ? 'Regular' : 'Reseller') : '-';
    
    // Update price and duration
    const priceValue = parseFloat(price) || 0;
    const durationValue = parseInt(duration) || 1;
    
    document.getElementById('preview-price').textContent = '$' + priceValue.toFixed(2);
    document.getElementById('preview-duration').textContent = '/ ' + durationValue + ' month' + (durationValue > 1 ? 's' : '');
    document.getElementById('preview-monthly').textContent = '$' + (priceValue / durationValue).toFixed(2) + '/month';
    
    // Update plan name based on selections
    let planName = 'New Plan';
    if (serverType && deviceCount && duration) {
        planName = serverLabel + ' ' + deviceCount + ' Device' + (deviceCount > 1 ? 's' : '') + ' - ' + duration + ' Month' + (duration > 1 ? 's' : '');
    }
    document.getElementById('preview-name').textContent = planName;
    
    // Update preview card border for 12-month plans
    const previewCard = document.getElementById('preview-card');
    if (duration == 12) {
        previewCard.className = 'bg-white border-2 border-[#D63613] rounded-xl p-6 hover:shadow-lg transition-all duration-300 relative';
        // Add best value badge if not exists
        if (!previewCard.querySelector('.best-value-badge')) {
            const badge = document.createElement('div');
            badge.className = 'absolute -top-3 left-1/2 transform -translate-x-1/2 best-value-badge';
            badge.innerHTML = '<span class="bg-gradient-to-r from-[#D63613] to-[#D63613]/80 text-white px-4 py-1 rounded-full text-xs font-semibold shadow-md">Best Value</span>';
            previewCard.appendChild(badge);
        }
    } else {
        previewCard.className = 'bg-white border-2 border-gray-200 rounded-xl p-6 hover:shadow-lg transition-all duration-300';
        // Remove best value badge if exists
        const badge = previewCard.querySelector('.best-value-badge');
        if (badge) badge.remove();
    }
    
    // Update features
    const featureInputs = document.querySelectorAll('input[name="features[]"]');
    const previewFeatures = document.getElementById('preview-features');
    const featuresContainer = document.getElementById('preview-features-container');
    
    previewFeatures.innerHTML = '';
    let hasFeatures = false;
    
    featureInputs.forEach(input => {
        if (input.value.trim()) {
            hasFeatures = true;
            const li = document.createElement('li');
            li.className = 'flex items-center text-sm text-[#201E1F]';
            li.innerHTML = `
                <svg class="w-4 h-4 text-green-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
                ${input.value.trim()}
            `;
            previewFeatures.appendChild(li);
        }
    });
    
    featuresContainer.style.display = hasFeatures ? 'block' : 'none';
}

// Add event listeners for real-time preview updates
document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners to all form inputs
    const formInputs = document.querySelectorAll('select, input[type="number"], input[name="features[]"], #custom_label');
    formInputs.forEach(input => {
        input.addEventListener('input', updatePreview);
        input.addEventListener('change', updatePreview);
    });
    
    // Initial preview update
    updatePreview();
});
</script>
@endsection