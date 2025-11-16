@extends('layouts.admin')

@section('title', 'Edit Custom Product')

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
                    <h1 class="text-3xl font-bold text-[#201E1F]">Edit Custom Product</h1>
                    <p class="text-[#201E1F]/60 mt-1">Update product information</p>
                </div>
            </div>
            <a href="{{ route('custom-product.checkout.show', $customProduct->slug) }}" target="_blank"
               class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                </svg>
                View Checkout Page
            </a>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.custom-products.update', $customProduct) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6">
            <h2 class="text-xl font-semibold text-[#201E1F] mb-6">Product Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Product Name -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-[#201E1F] mb-2">
                        Product Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $customProduct->name) }}" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#D63613]/20 focus:border-[#D63613] @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Slug -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-[#201E1F] mb-2">
                        URL Slug <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="slug" value="{{ old('slug', $customProduct->slug) }}" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#D63613]/20 focus:border-[#D63613] @error('slug') border-red-500 @enderror">
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
                        <option value="service" {{ old('product_type', $customProduct->product_type) === 'service' ? 'selected' : '' }}>Service</option>
                        <option value="digital" {{ old('product_type', $customProduct->product_type) === 'digital' ? 'selected' : '' }}>Digital</option>
                        <option value="other" {{ old('product_type', $customProduct->product_type) === 'other' ? 'selected' : '' }}>Other</option>
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
                        <input type="number" name="price" value="{{ old('price', $customProduct->price) }}" step="0.01" min="0" required
                               class="w-full pl-8 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#D63613]/20 focus:border-[#D63613] @error('price') border-red-500 @enderror">
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
                    <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $customProduct->stock_quantity) }}" min="0"
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#D63613]/20 focus:border-[#D63613] @error('stock_quantity') border-red-500 @enderror"
                           placeholder="Unlimited">
                    @error('stock_quantity')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Active Status -->
                <div class="flex items-center">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $customProduct->is_active) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#D63613]/20 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#D63613]"></div>
                        <span class="ms-3 text-sm font-medium text-[#201E1F]">Active (available for purchase)</span>
                    </label>
                </div>

                <!-- Short Description -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-[#201E1F] mb-2">
                        Short Description
                    </label>
                    <input type="text" name="short_description" value="{{ old('short_description', $customProduct->short_description) }}" maxlength="500"
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#D63613]/20 focus:border-[#D63613] @error('short_description') border-red-500 @enderror">
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
                              class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#D63613]/20 focus:border-[#D63613] @error('description') border-red-500 @enderror">{{ old('description', $customProduct->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Custom Fields Section -->
        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl font-semibold text-[#201E1F]">Custom Input Fields</h2>
                    <p class="text-sm text-[#201E1F]/60 mt-1">Add custom fields that will appear in the checkout form</p>
                </div>
                <button type="button" id="add-custom-field" 
                        class="px-4 py-2 bg-[#D63613] text-white rounded-lg hover:bg-[#D63613]/90 transition-colors text-sm font-medium">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Field
                </button>
            </div>
            
            <div id="custom-fields-container" class="space-y-4">
                <!-- Custom fields will be added here dynamically -->
            </div>
        </div>

        <!-- Order Statistics -->
        @if($customProduct->orders->count() > 0)
        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6">
            <h2 class="text-xl font-semibold text-[#201E1F] mb-4">Order Statistics</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <div class="text-sm text-[#201E1F]/60 mb-1">Total Orders</div>
                    <div class="text-2xl font-bold text-[#201E1F]">{{ $customProduct->orders->count() }}</div>
                </div>
                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <div class="text-sm text-[#201E1F]/60 mb-1">Total Revenue</div>
                    <div class="text-2xl font-bold text-[#D63613]">${{ number_format($customProduct->orders->sum('amount'), 2) }}</div>
                </div>
                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <div class="text-sm text-[#201E1F]/60 mb-1">Last Order</div>
                    <div class="text-2xl font-bold text-[#201E1F]">{{ $customProduct->orders->sortByDesc('created_at')->first()->created_at->diffForHumans() }}</div>
                </div>
            </div>
        </div>
        @endif

        <!-- Form Actions -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.custom-products.index') }}" 
                   class="px-6 py-3 border border-gray-200 text-[#201E1F]/80 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-gradient-to-r from-[#D63613] to-[#D63613]/80 text-white rounded-lg hover:from-[#D63613]/90 hover:to-[#D63613]/70 transition-all shadow-md hover:shadow-lg">
                    Update Product
                </button>
            </div>
        </div>
    </form>

    <!-- Delete Form (separate from update form) -->
    <div class="bg-red-50 border border-red-200 rounded-xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-red-800">Danger Zone</h3>
                <p class="text-red-600 text-sm mt-1">Once you delete a product, there is no going back. Please be certain.</p>
            </div>
            <form action="{{ route('admin.custom-products.destroy', $customProduct) }}" method="POST" 
                  onsubmit="return confirm('Are you sure you want to delete this product? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    Delete Product
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('custom-fields-container');
    const addButton = document.getElementById('add-custom-field');
    let fieldIndex = 0;

    // Load existing fields from product or old input
    const existingFields = @json(old('custom_fields', $customProduct->custom_fields ?? []));
    if (existingFields && existingFields.length > 0) {
        existingFields.forEach(field => {
            const options = field.options ? (Array.isArray(field.options) ? field.options.join('\n') : field.options) : '';
            const width = field.width || 'full';
            const layout = field.layout || 'vertical';
            addCustomField(field.label || '', field.type || 'text', field.required || false, options, width, layout);
        });
    }

    addButton.addEventListener('click', function() {
        addCustomField('', 'text', false, '', 'full', 'vertical');
    });

    function addCustomField(label = '', type = 'text', required = false, options = '', width = 'full', layout = 'vertical') {
        const fieldDiv = document.createElement('div');
        fieldDiv.className = 'bg-white p-4 rounded-lg border border-gray-200';
        fieldDiv.dataset.index = fieldIndex;

        const showOptions = (type === 'select' || type === 'radio' || type === 'checkbox');
        const showLayout = (type === 'radio' || type === 'checkbox');
        
        fieldDiv.innerHTML = `
            <div class="flex items-start gap-4">
                <div class="flex-1 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-[#201E1F] mb-2">Field Label *</label>
                            <input type="text" name="custom_fields[${fieldIndex}][label]" value="${label}" required
                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#D63613]/20 focus:border-[#D63613]"
                                   placeholder="e.g., Device Model, Serial Number">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#201E1F] mb-2">Field Type *</label>
                            <select name="custom_fields[${fieldIndex}][type]" required
                                    class="field-type-select w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#D63613]/20 focus:border-[#D63613]"
                                    data-index="${fieldIndex}">
                                <option value="text" ${type === 'text' ? 'selected' : ''}>Text</option>
                                <option value="textarea" ${type === 'textarea' ? 'selected' : ''}>Textarea</option>
                                <option value="email" ${type === 'email' ? 'selected' : ''}>Email</option>
                                <option value="number" ${type === 'number' ? 'selected' : ''}>Number</option>
                                <option value="select" ${type === 'select' ? 'selected' : ''}>Select (Dropdown)</option>
                                <option value="radio" ${type === 'radio' ? 'selected' : ''}>Radio Buttons</option>
                                <option value="checkbox" ${type === 'checkbox' ? 'selected' : ''}>Checkbox</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="custom_fields[${fieldIndex}][required]" value="1" ${required ? 'checked' : ''}
                                       class="w-4 h-4 text-[#D63613] border-gray-300 rounded focus:ring-[#D63613]">
                                <span class="ml-2 text-sm text-[#201E1F]">Required field</span>
                            </label>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-[#201E1F] mb-2">Field Width</label>
                            <select name="custom_fields[${fieldIndex}][width]"
                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#D63613]/20 focus:border-[#D63613]">
                                <option value="full" ${width === 'full' ? 'selected' : ''}>Full Width</option>
                                <option value="half" ${width === 'half' ? 'selected' : ''}>Half Width</option>
                                <option value="third" ${width === 'third' ? 'selected' : ''}>One Third</option>
                                <option value="quarter" ${width === 'quarter' ? 'selected' : ''}>Quarter Width</option>
                            </select>
                        </div>
                        <div class="field-layout-container" style="display: ${showLayout ? 'block' : 'none'};">
                            <label class="block text-sm font-medium text-[#201E1F] mb-2">Layout</label>
                            <select name="custom_fields[${fieldIndex}][layout]"
                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#D63613]/20 focus:border-[#D63613]">
                                <option value="vertical" ${layout === 'vertical' ? 'selected' : ''}>Vertical</option>
                                <option value="horizontal" ${layout === 'horizontal' ? 'selected' : ''}>Horizontal</option>
                            </select>
                        </div>
                    </div>
                    <div class="field-options-container" style="display: ${showOptions ? 'block' : 'none'};">
                        <label class="block text-sm font-medium text-[#201E1F] mb-2">Options (one per line) *</label>
                        <textarea name="custom_fields[${fieldIndex}][options]" rows="3"
                                  class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#D63613]/20 focus:border-[#D63613]"
                                  placeholder="Option 1&#10;Option 2&#10;Option 3">${options}</textarea>
                        <p class="text-xs text-[#201E1F]/60 mt-1">Enter each option on a new line</p>
                    </div>
                </div>
                <button type="button" class="remove-field px-3 py-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;

        container.appendChild(fieldDiv);

        // Add remove functionality
        fieldDiv.querySelector('.remove-field').addEventListener('click', function() {
            fieldDiv.remove();
        });

        // Add type change handler
        const typeSelect = fieldDiv.querySelector('.field-type-select');
        const optionsContainer = fieldDiv.querySelector('.field-options-container');
        const layoutContainer = fieldDiv.querySelector('.field-layout-container');
        typeSelect.addEventListener('change', function() {
            const showOptions = (this.value === 'select' || this.value === 'radio' || this.value === 'checkbox');
            const showLayout = (this.value === 'radio' || this.value === 'checkbox');
            
            optionsContainer.style.display = showOptions ? 'block' : 'none';
            layoutContainer.style.display = showLayout ? 'block' : 'none';
            
            const optionsTextarea = optionsContainer.querySelector('textarea');
            if (showOptions && !optionsTextarea.hasAttribute('required')) {
                optionsTextarea.setAttribute('required', 'required');
            } else if (!showOptions) {
                optionsTextarea.removeAttribute('required');
            }
        });

        fieldIndex++;
    }
});
</script>
@endpush
@endsection

