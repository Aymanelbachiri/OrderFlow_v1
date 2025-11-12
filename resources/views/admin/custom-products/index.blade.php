@extends('layouts.admin')

@section('title', 'Custom Products')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up">
        <div class="lg:flex space-y-4 justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-[#201E1F] mb-2">Custom Products</h1>
                <p class="text-[#201E1F]/60">Manage service-based and digital products</p>
            </div>
            <a href="{{ route('admin.custom-products.create') }}" 
               class="bg-gradient-to-r from-[#D63613] to-[#D63613]/80 text-white px-6 py-3 rounded-lg text-sm font-semibold hover:from-[#D63613]/90 hover:to-[#D63613]/70 transition-all duration-300 shadow-md hover:shadow-lg flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Add New Product</span>
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up" style="animation-delay: 0.1s;">
        <form method="GET" action="{{ route('admin.custom-products.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-[#201E1F] mb-2">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search products..." 
                       class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#D63613]/20 focus:border-[#D63613]">
            </div>
            <div>
                <label class="block text-sm font-medium text-[#201E1F] mb-2">Product Type</label>
                <select name="type" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#D63613]/20 focus:border-[#D63613]">
                    <option value="">All Types</option>
                    <option value="service" {{ request('type') === 'service' ? 'selected' : '' }}>Service</option>
                    <option value="digital" {{ request('type') === 'digital' ? 'selected' : '' }}>Digital</option>
                    <option value="other" {{ request('type') === 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-[#201E1F] mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#D63613]/20 focus:border-[#D63613]">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 bg-[#D63613] text-white px-4 py-2 rounded-lg hover:bg-[#D63613]/90 transition-colors">
                    Filter
                </button>
                <a href="{{ route('admin.custom-products.index') }}" class="px-4 py-2 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Products List -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md animate-fade-in-up" style="animation-delay: 0.2s;">
        @if($products->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-[#D63613]/5 to-[#D63613]/10">
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">Product</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">Type</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">Price</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">Stock</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">Orders</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-[#201E1F] uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($products as $product)
                            <tr class="bg-white hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="text-sm font-semibold text-[#201E1F]">{{ $product->name }}</div>
                                        <div class="text-xs text-[#201E1F]/60">{{ $product->slug }}</div>
                                        @if($product->short_description)
                                            <div class="text-xs text-[#201E1F]/50 mt-1">{{ Str::limit($product->short_description, 50) }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full 
                                        {{ $product->product_type === 'service' ? 'bg-blue-100 text-blue-700' : '' }}
                                        {{ $product->product_type === 'digital' ? 'bg-purple-100 text-purple-700' : '' }}
                                        {{ $product->product_type === 'other' ? 'bg-gray-100 text-gray-700' : '' }}">
                                        {{ ucfirst($product->product_type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-semibold text-[#D63613]">${{ number_format($product->price, 2) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-[#201E1F]/70">
                                        {{ $product->stock_quantity === null ? 'Unlimited' : $product->stock_quantity }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <form action="{{ route('admin.custom-products.toggle-status', $product) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full border transition-colors
                                            {{ $product->is_active ? 'bg-green-100 text-green-700 border-green-200 hover:bg-green-200' : 'bg-red-100 text-red-700 border-red-200 hover:bg-red-200' }}">
                                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-[#201E1F]/70">{{ $product->orders->count() }}</span>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <a href="{{ route('custom-product.checkout.show', $product->slug) }}" target="_blank"
                                       class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-700 text-xs font-medium rounded-lg hover:bg-blue-100 transition-colors">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                        View
                                    </a>
                                    <a href="{{ route('admin.custom-products.edit', $product) }}" 
                                       class="inline-flex items-center px-3 py-1.5 bg-[#D63613]/10 text-[#D63613] text-xs font-medium rounded-lg hover:bg-[#D63613]/20 transition-colors">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.custom-products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-700 text-xs font-medium rounded-lg hover:bg-red-100 transition-colors">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $products->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <h3 class="text-lg font-semibold text-[#201E1F] mb-2">No products found</h3>
                <p class="text-[#201E1F]/60 mb-4">Get started by creating your first custom product</p>
                <a href="{{ route('admin.custom-products.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-[#D63613] text-white rounded-lg hover:bg-[#D63613]/90 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add New Product
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

