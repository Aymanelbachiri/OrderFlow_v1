@extends('layouts.admin')

@section('title', 'Create Custom Product')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6">
        <div class="lg:flex space-y-4 items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.custom-products.index') }}" 
                   class="text-[#201E1F]/60 hover:text-[#201E1F] transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-[#201E1F]">Create Custom Product</h1>
                    <p class="text-[#201E1F]/60 mt-1">Add a new service or digital product</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.custom-products.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6">
            <h2 class="text-xl font-semibold text-[#201E1F] mb-6">Product Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Product Name -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-[#201E1F] mb-2">
                        Product Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#D63613]/20 focus:border-[#D63613] @error('name') border-red-500 @enderror"
                           placeholder="e.g., Device Activation Service">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Slug -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-[#201E1F] mb-2">
                        URL Slug <span class="text-[#201E1F]/60 text-xs">(leave empty to auto-generate)</span>
                    </label>
                    <input type="text" name="slug" value="{{ old('slug') }}"
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#D63613]/20 focus:border-[#D63613] @error('slug') border-red-500 @enderror"
                           placeholder="device-activation-service">
                    @error('slug')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Product Type -->
                <div>
                    <label class="block text-sm font-medium text-[#201E1F] mb-2">
                        Product Type <span class="text-red-500">*</span>
                    </label>
                    <select name="product_type" required
                            class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#D63613]/20 focus:border-[#D63613] @error('product_type') border-red-500 @enderror">
                        <option value="service" {{ old('product_type') === 'service' ? 'selected' : '' }}>Service</option>
                        <option value="digital" {{ old('product_type') === 'digital' ? 'selected' : '' }}>Digital</option>
                        <option value="other" {{ old('product_type') === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('product_type')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Price -->
                <div>
                    <label class="block text-sm font-medium text-[#201E1F] mb-2">
                        Price (USD) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#201E1F]/60">$</span>
                        <input type="number" name="price" value="{{ old('price') }}" step="0.01" min="0" required
                               class="w-full pl-8 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#D63613]/20 focus:border-[#D63613] @error('price') border-red-500 @enderror"
                               placeholder="0.00">
                    </div>
                    @error('price')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Stock Quantity -->
                <div>
                    <label class="block text-sm font-medium text-[#201E1F] mb-2">
                        Stock Quantity <span class="text-[#201E1F]/60 text-xs">(leave empty for unlimited)</span>
                    </label>
                    <input type="number" name="stock_quantity" value="{{ old('stock_quantity') }}" min="0"
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#D63613]/20 focus:border-[#D63613] @error('stock_quantity') border-red-500 @enderror"
                           placeholder="Unlimited">
                    @error('stock_quantity')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Active Status -->
                <div class="flex items-center">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#D63613]/20 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#D63613]"></div>
                        <span class="ms-3 text-sm font-medium text-[#201E1F]">Active (available for purchase)</span>
                    </label>
                </div>

                <!-- Short Description -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-[#201E1F] mb-2">
                        Short Description
                    </label>
                    <input type="text" name="short_description" value="{{ old('short_description') }}" maxlength="500"
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#D63613]/20 focus:border-[#D63613] @error('short_description') border-red-500 @enderror"
                           placeholder="Brief description for listings">
                    @error('short_description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Full Description -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-[#201E1F] mb-2">
                        Full Description
                    </label>
                    <textarea name="description" rows="8"
                              class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#D63613]/20 focus:border-[#D63613] @error('description') border-red-500 @enderror"
                              placeholder="Detailed description of the product, what's included, delivery time, etc.">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end space-x-4">
            <a href="{{ route('admin.custom-products.index') }}" 
               class="px-6 py-3 border border-gray-200 text-[#201E1F]/80 rounded-lg hover:bg-gray-50 transition-colors">
                Cancel
            </a>
            <button type="submit" 
                    class="px-6 py-3 bg-gradient-to-r from-[#D63613] to-[#D63613]/80 text-white rounded-lg hover:from-[#D63613]/90 hover:to-[#D63613]/70 transition-all shadow-md hover:shadow-lg">
                Create Product
            </button>
        </div>
    </form>
</div>
@endsection

