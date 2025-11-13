@extends('layouts.admin')

@section('title', 'Order Details - ' . $order->order_number)

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up">
        <div class="lg:flex space-y-4 justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-gradient-to-br from-[#D63613] to-[#D63613]/80 rounded-xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-[#201E1F] mb-1">Order Details</h1>
                    <p class="text-[#201E1F]/60 font-mono">{{ $order->order_number }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.orders.edit', $order) }}" 
                   class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-3 rounded-lg text-sm font-semibold flex items-center space-x-2 transition-all duration-300 shadow-md hover:shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <span>Edit Order</span>
                </a>
                <a href="{{ route('admin.orders.index') }}" 
                   class="bg-white hover:bg-gray-50 text-[#201E1F]/80 hover:text-[#201E1F] border border-gray-200 px-6 py-3 rounded-lg text-sm font-semibold flex items-center space-x-2 transition-all duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Back to Orders</span>
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Order Information -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Order Information Card -->
            <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up" style="animation-delay: 0.1s;">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-[#201E1F]">Order Information</h2>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Order Number</label>
                        <p class="text-sm text-[#201E1F] bg-white rounded-lg px-4 py-3 border border-gray-200 font-mono">{{ $order->order_number }}</p>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Status</label>
                        <div class="bg-white rounded-lg px-4 py-3 border border-gray-200">
                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full border
                                @if($order->status === 'active') bg-green-50 text-green-700 border-green-200
                                @elseif($order->status === 'pending') bg-yellow-50 text-yellow-700 border-yellow-200
                                @elseif($order->status === 'expired') bg-red-50 text-red-700 border-red-200
                                @elseif($order->status === 'cancelled') bg-gray-50 text-gray-700 border-gray-200
                                @else bg-blue-50 text-blue-700 border-blue-200 @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Amount</label>
                        <p class="text-sm text-[#201E1F] bg-white rounded-lg px-4 py-3 border border-gray-200 font-semibold">${{ number_format($order->amount, 2) }}</p>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Payment Method</label>
                        <p class="text-sm text-[#201E1F] bg-white rounded-lg px-4 py-3 border border-gray-200 capitalize">{{ str_replace('_', ' ', $order->payment_method) }}</p>
                    </div>
                    
                    @if($order->order_type === 'subscription')
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Subscription Type</label>
                        <div class="bg-white rounded-lg px-4 py-3 border border-gray-200">
                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full border
                                @if($order->subscription_type === 'new') bg-green-50 text-green-700 border-green-200
                                @elseif($order->subscription_type === 'renewal') bg-blue-50 text-blue-700 border-blue-200
                                @else bg-gray-50 text-gray-700 border-gray-200 @endif">
                                {{ $order->subscription_type_display }}
                            </span>
                        </div>
                    </div>
                    @endif
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Transaction ID</label>
                        <p class="text-sm text-[#201E1F] bg-white rounded-lg px-4 py-3 border border-gray-200 font-mono">{{ $order->payment_id ?: 'N/A' }}</p>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Created Date</label>
                        <p class="text-sm text-[#201E1F] bg-white rounded-lg px-4 py-3 border border-gray-200">{{ $order->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Start Date</label>
                        <p class="text-sm text-[#201E1F] bg-white rounded-lg px-4 py-3 border border-gray-200">
                            {{ $order->starts_at ? $order->starts_at->format('M d, Y H:i') : 'Immediately' }}
                        </p>
                    </div>
                    
                    @if(($order->order_type ?? 'subscription') !== 'credit_pack' && (!$order->reseller_username && !$order->reseller_password))
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Expiry Date</label>
                        <p class="text-sm text-[#201E1F] bg-white rounded-lg px-4 py-3 border border-gray-200">
                            {{ $order->expires_at ? $order->expires_at->format('M d, Y H:i') : 'No expiry' }}
                        </p>
                    </div>
                    @endif
                    
                    @if(($order->order_type ?? 'subscription') !== 'credit_pack' && (!$order->reseller_username && !$order->reseller_password))
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Time Remaining</label>
                        <div class="bg-white rounded-lg px-4 py-3 border border-gray-200">
                            @if($order->expires_at)
                                @if($order->expires_at->isPast())
                                    <span class="text-sm text-red-600 font-medium">Expired {{ $order->expires_at->diffForHumans() }}</span>
                                @else
                                    <span class="text-sm text-green-600 font-medium">{{ $order->expires_at->diffForHumans() }}</span>
                                @endif
                            @else
                                <span class="text-sm text-gray-500">No expiry</span>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>

                @if($order->admin_notes)
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <label class="block text-sm font-medium text-[#201E1F]/60 mb-3">Admin Notes</label>
                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                        <p class="text-sm text-[#201E1F]">{{ $order->admin_notes }}</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Customer Information -->
            <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up" style="animation-delay: 0.2s;">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-[#201E1F]">Customer Information</h2>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Name</label>
                        <div class="bg-white rounded-lg px-4 py-3 border border-gray-200">
                            <a href="{{ route('admin.clients.show', $order->user) }}" class="text-sm text-[#D63613] hover:text-[#D63613]/80 font-medium transition-colors duration-200">
                                {{ $order->user->name }}
                            </a>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Email</label>
                        <p class="text-sm text-[#201E1F] bg-white rounded-lg px-4 py-3 border border-gray-200">{{ $order->user->email }}</p>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Phone</label>
                        <p class="text-sm text-[#201E1F] bg-white rounded-lg px-4 py-3 border border-gray-200">{{ $order->user->phone ?: 'Not provided' }}</p>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Role</label>
                        <div class="bg-white rounded-lg px-4 py-3 border border-gray-200">
                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                                {{ ucfirst($order->user->role) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing Plan Information -->
            @if($order->reseller_username || $order->reseller_password)
            <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up" style="animation-delay: 0.3s;">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-[#201E1F]">Reseller Credit Pack Details</h2>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Credit Pack Name</label>
                        <p class="text-sm text-[#201E1F] bg-white rounded-lg px-4 py-3 border border-gray-200 font-medium">{{ $order->resellerCreditPack->name ?? 'N/A' }}</p>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Credits Amount</label>
                        <p class="text-sm text-[#201E1F] bg-white rounded-lg px-4 py-3 border border-gray-200 font-medium">{{ $order->resellerCreditPack ? number_format($order->resellerCreditPack->credits_amount) : 'N/A' }}@if($order->resellerCreditPack) Credits @endif</p>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Panel URL</label>
                        <p class="text-sm text-[#201E1F] bg-white rounded-lg px-4 py-3 border border-gray-200 font-medium">
                            @if(!empty($order->reseller_login_url))
                                <a href="{{ $order->reseller_login_url }}" target="_blank" class="text-green-600 hover:text-green-700 hover:underline">{{ $order->reseller_login_url }}</a>
                            @else
                                <span class="text-gray-400">Not set</span>
                            @endif
                        </p>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Username</label>
                        <p class="text-sm text-[#201E1F] bg-white rounded-lg px-4 py-3 border border-gray-200 font-medium">{{ $order->reseller_username ?? 'N/A' }}</p>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Password</label>
                        <p class="text-sm text-[#201E1F] bg-white rounded-lg px-4 py-3 border border-gray-200 font-medium">{{ $order->reseller_password ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
            @elseif($order->isCustomProduct() && $order->customProduct)
            <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up" style="animation-delay: 0.3s;">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-[#201E1F]">Custom Product Details</h2>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Product Name</label>
                        <p class="text-sm text-[#201E1F] bg-white rounded-lg px-4 py-3 border border-gray-200 font-medium">{{ $order->customProduct->name }}</p>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Product Type</label>
                        <p class="text-sm text-[#201E1F] bg-white rounded-lg px-4 py-3 border border-gray-200">
                            <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full 
                                {{ $order->customProduct->product_type === 'service' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $order->customProduct->product_type === 'digital' ? 'bg-purple-100 text-purple-700' : '' }}
                                {{ $order->customProduct->product_type === 'other' ? 'bg-gray-100 text-gray-700' : '' }}">
                                {{ ucfirst($order->customProduct->product_type) }}
                            </span>
                        </p>
                    </div>
                    @if($order->customProduct->short_description)
                    <div class="space-y-2 col-span-full">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Short Description</label>
                        <p class="text-sm text-[#201E1F] bg-white rounded-lg px-4 py-3 border border-gray-200">{{ $order->customProduct->short_description }}</p>
                    </div>
                    @endif
                    @if($order->customProduct->description)
                    <div class="space-y-2 col-span-full">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Full Description</label>
                        <div class="text-sm text-[#201E1F] bg-white rounded-lg px-4 py-3 border border-gray-200">{{ $order->customProduct->description }}</div>
                    </div>
                    @endif
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Product Price</label>
                        <p class="text-sm text-[#201E1F] bg-white rounded-lg px-4 py-3 border border-gray-200 font-semibold">${{ number_format($order->customProduct->price, 2) }}</p>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Product Status</label>
                        <p class="text-sm text-[#201E1F] bg-white rounded-lg px-4 py-3 border border-gray-200">
                            <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full border {{ $order->customProduct->is_active ? 'bg-green-100 text-green-700 border-green-200' : 'bg-red-100 text-red-700 border-red-200' }}">
                                {{ $order->customProduct->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
            @elseif($order->devices && count($order->devices) > 0 || $order->subscription_username || $order->subscription_password || $order->subscription_url)
            <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up" style="animation-delay: 0.3s;">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-[#201E1F]">Subscription Details</h2>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($order->devices && count($order->devices) > 0)
                        @foreach($order->devices as $device)
                        <div class="col-span-full grid grid-cols-1 md:grid-cols-3 gap-6 mb-4 p-4 bg-white rounded-lg border border-gray-200">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-[#201E1F]/60">Device {{ $loop->iteration }} Username</label>
                                <p class="text-sm text-[#201E1F] bg-gray-50 rounded-lg px-4 py-3 border border-gray-200 font-medium">{{ $device['username'] ?? 'N/A' }}</p>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-[#201E1F]/60">Device {{ $loop->iteration }} Password</label>
                                <p class="text-sm text-[#201E1F] bg-gray-50 rounded-lg px-4 py-3 border border-gray-200 font-medium">{{ $device['password'] ?? 'N/A' }}</p>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-[#201E1F]/60">Device {{ $loop->iteration }} URL</label>
                                <p class="text-sm text-[#201E1F] bg-gray-50 rounded-lg px-4 py-3 border border-gray-200 font-medium">{{ $device['url'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="col-span-full grid grid-cols-1 md:grid-cols-3 gap-6 mb-4 p-4 bg-white rounded-lg border border-gray-200">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-[#201E1F]/60">Username</label>
                                <p class="text-sm text-[#201E1F] bg-gray-50 rounded-lg px-4 py-3 border border-gray-200 font-medium">{{ $order->subscription_username ?? 'N/A' }}</p>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-[#201E1F]/60">Password</label>
                                <p class="text-sm text-[#201E1F] bg-gray-50 rounded-lg px-4 py-3 border border-gray-200 font-medium">{{ $order->subscription_password ?? 'N/A' }}</p>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-[#201E1F]/60">URL</label>
                                <p class="text-sm text-[#201E1F] bg-gray-50 rounded-lg px-4 py-3 border border-gray-200 font-medium">{{ $order->subscription_url ?? 'N/A' }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @elseif($order->pricingPlan && $order->pricingPlan->plan_type !== 'reseller')
            <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up" style="animation-delay: 0.3s;">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-[#201E1F]">Pricing Plan Details</h2>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Plan Name</label>
                        <p class="text-sm text-[#201E1F] bg-white rounded-lg px-4 py-3 border border-gray-200 font-medium">{{ $order->pricingPlan->display_name }}</p>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Server Type</label>
                        <p class="text-sm text-[#201E1F] bg-white rounded-lg px-4 py-3 border border-gray-200 capitalize">{{ $order->pricingPlan->server_type }}</p>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Device Count</label>
                        <p class="text-sm text-[#201E1F] bg-white rounded-lg px-4 py-3 border border-gray-200 font-medium">{{ $order->pricingPlan->device_count }} device(s)</p>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Duration</label>
                        <p class="text-sm text-[#201E1F] bg-white rounded-lg px-4 py-3 border border-gray-200 font-medium">{{ $order->pricingPlan->duration }} month(s)</p>
                    </div>
                    
                    <div class="md:col-span-2 space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Features</label>
                        <div class="bg-white rounded-lg px-4 py-3 border border-gray-200">
                            <ul class="list-disc list-inside text-sm text-[#201E1F]">
                                @foreach($order->pricingPlan->features as $feature)
                                    <li>{{ $feature }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Actions Sidebar -->
        <div class="lg:col-span-1 space-y-8">
            <!-- Quick Actions -->
            <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up" style="animation-delay: 0.4s;">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-orange-400 to-orange-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-[#201E1F]">Quick Actions</h3>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <a href="{{ route('admin.orders.edit', $order) }}" 
                       class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-3 px-4 rounded-lg text-center flex items-center justify-center space-x-2 transition-all duration-300 shadow-md hover:shadow-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        <span>Edit Order</span>
                    </a>
                    
                    @if($order->status === 'pending')
                    <button type="button"
                            onclick="openActivateModal({{ $order->id }}, '{{ $order->order_number }}', '{{ $order->user->name }}', '{{ $order->pricingPlan->display_name ?? ($order->resellerCreditPack->name ?? 'IPTV Service') }}', {{ $order->pricingPlan->device_count ?? 1 }}, '{{ $order->pricingPlan->plan_type ?? 'regular' }}', '{{ $order->order_type ?? 'subscription' }}')"
                            class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold py-3 px-4 rounded-lg flex items-center justify-center space-x-2 transition-all duration-300 shadow-md hover:shadow-lg"
                            data-order-type="{{ $order->order_type ?? 'subscription' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Activate Order</span>
                    </button>
                    @endif
                    
                    
                    
                    <a href="{{ route('admin.clients.show', $order->user) }}" 
                       class="w-full bg-white hover:bg-gray-50 text-[#201E1F]/80 hover:text-[#201E1F] border border-gray-200 font-semibold py-3 px-4 rounded-lg text-center flex items-center justify-center space-x-2 transition-all duration-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span>View Customer</span>
                    </a>
                </div>
            </div>

            <!-- Order Timeline -->
            <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up" style="animation-delay: 0.5s;">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-[#201E1F]">Order Timeline</h3>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-3 h-3 bg-blue-500 rounded-full mt-2"></div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-[#201E1F]">Order Created</p>
                            <p class="text-sm text-[#201E1F]/60">{{ $order->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                    
                    @if($order->starts_at && $order->starts_at->isPast())
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-3 h-3 bg-green-500 rounded-full mt-2"></div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-[#201E1F]">Service Started</p>
                            <p class="text-sm text-[#201E1F]/60">{{ $order->starts_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($order->status === 'active')
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-3 h-3 bg-green-500 rounded-full mt-2"></div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-[#201E1F]">Order Activated</p>
                            <p class="text-sm text-[#201E1F]/60">{{ $order->updated_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($order->expires_at)
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-3 h-3 {{ $order->expires_at->isPast() ? 'bg-red-500' : 'bg-yellow-500' }} rounded-full mt-2"></div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-[#201E1F]">
                                {{ $order->expires_at->isPast() ? 'Service Expired' : 'Service Expires' }}
                            </p>
                            <p class="text-sm text-[#201E1F]/60">{{ $order->expires_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Activation Modal -->
<div id="activateModal" class="fixed inset-0 bg-black bg-opacity-75 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-2xl rounded-xl bg-white">
        <div class="mt-3">
            <!-- Modal Header -->
            <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-[#201E1F]">Activate Order</h3>
                </div>
                <button type="button" onclick="closeActivateModal()" class="text-[#201E1F]/60 hover:text-[#201E1F] transition-colors duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="py-4">
                @if ($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <h4 class="font-medium text-red-900 mb-2">Please fix the following errors:</h4>
                    <ul class="list-disc list-inside text-sm text-red-800">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <h4 class="font-medium text-blue-900 mb-2">Order Information</h4>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-blue-700">Order #:</span>
                            <span id="modal-order-number" class="font-medium"></span>
                        </div>
                        <div>
                            <span class="text-blue-700">Customer:</span>
                            <span id="modal-customer-name" class="font-medium"></span>
                        </div>
                        <div class="col-span-2">
                            <span class="text-blue-700">Service:</span>
                            <span id="modal-service-name" class="font-medium"></span>
                        </div>
                    </div>
                </div>

                <form id="activateForm" method="POST">
                    @csrf
                    <div class="space-y-6">
                        <!-- Device Credentials Section (for regular orders) -->
                        <div id="devicesContainer">
                            <!-- Device fields will be dynamically generated here -->
                        </div>

                        <!-- Reseller Credentials Section (for reseller orders) -->
                        <div id="resellerContainer" class="space-y-4 hidden">
                            <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-orange-800 mb-3">Reseller Panel Credentials</h4>

                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <label for="reseller_panel_url" class="block text-sm font-medium text-[#201E1F] mb-1">Reseller Panel URL</label>
                                        <input type="url" id="reseller_panel_url" name="reseller_panel_url"
                                               class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300"
                                               placeholder="https://panel.example.com">
                                    </div>

                                    <div>
                                        <label for="reseller_username" class="block text-sm font-medium text-[#201E1F] mb-1">Username</label>
                                        <input type="text" id="reseller_username" name="reseller_username"
                                               class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300"
                                               placeholder="Enter reseller username">
                                    </div>

                                    <div>
                                        <label for="reseller_password" class="block text-sm font-medium text-[#201E1F] mb-1">Password</label>
                                        <input type="text" id="reseller_password" name="reseller_password"
                                               class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300"
                                               placeholder="Enter reseller password">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Credit Pack IPTV Panel Section (for credit pack orders) -->
                        <div id="creditPackContainer" class="space-y-4 hidden">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-blue-800 mb-3">IPTV Panel Access for Credit Pack</h4>
                                <p class="text-xs text-blue-600 mb-3">Provide IPTV panel access details for this credit pack purchase.</p>

                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <label for="iptv_panel_url" class="block text-sm font-medium text-[#201E1F] mb-1">IPTV Panel URL</label>
                                        <input type="url" id="iptv_panel_url" name="iptv_panel_url"
                                               class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300"
                                               placeholder="https://iptv-panel.example.com">
                                    </div>

                                    <div>
                                        <label for="iptv_panel_username" class="block text-sm font-medium text-[#201E1F] mb-1">IPTV Username</label>
                                        <input type="text" id="iptv_panel_username" name="iptv_panel_username"
                                               class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300"
                                               placeholder="Enter IPTV username">
                                    </div>

                                    <div>
                                        <label for="iptv_panel_password" class="block text-sm font-medium text-[#201E1F] mb-1">IPTV Password</label>
                                        <input type="text" id="iptv_panel_password" name="iptv_panel_password"
                                               class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300"
                                               placeholder="Enter IPTV password">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-4">
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center">
                                    <input type="checkbox"
                                           id="send_credentials_email"
                                           name="send_credentials_email"
                                           value="1"
                                           checked
                                           class="h-4 w-4 text-[#D63613] focus:ring-[#D63613] border-gray-300 rounded">
                                    <label for="send_credentials_email" class="ml-2 block text-sm text-[#201E1F]">
                                        Send credentials email to customer
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 mt-6">
                        <button type="button"
                                onclick="closeActivateModal()"
                                class="bg-white hover:bg-gray-50 text-[#201E1F]/80 hover:text-[#201E1F] border border-gray-200 px-6 py-3 rounded-lg text-sm font-medium transition-all duration-300">
                            Cancel
                        </button>
                        <button type="submit"
                                class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-6 py-3 rounded-lg text-sm font-semibold transition-all duration-300 shadow-md hover:shadow-lg">
                            Activate Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openActivateModal(orderId, orderNumber, customerName, serviceName, deviceCount, planType = 'regular', orderType = 'subscription') {
    // Set form action
    document.getElementById('activateForm').action = `/admin/orders/${orderId}/activate`;

    // Fill order information
    document.getElementById('modal-order-number').textContent = orderNumber;
    document.getElementById('modal-customer-name').textContent = customerName;
    document.getElementById('modal-service-name').textContent = serviceName;

    // Show/hide appropriate sections based on order type and plan type
    const devicesContainer = document.getElementById('devicesContainer');
    const resellerContainer = document.getElementById('resellerContainer');
    const creditPackContainer = document.getElementById('creditPackContainer');

    // Hide all containers first
    devicesContainer.classList.add('hidden');
    resellerContainer.classList.add('hidden');
    creditPackContainer.classList.add('hidden');

    if (orderType === 'credit_pack') {
        // Show credit pack IPTV panel section
        creditPackContainer.classList.remove('hidden');

        // Clear credit pack fields
        document.getElementById('iptv_panel_url').value = '';
        document.getElementById('iptv_panel_username').value = '';
        document.getElementById('iptv_panel_password').value = '';
    } else if (planType === 'reseller') {
        // Show reseller credentials section
        resellerContainer.classList.remove('hidden');

        // Clear reseller fields
        document.getElementById('reseller_panel_url').value = '';
        document.getElementById('reseller_username').value = '';
        document.getElementById('reseller_password').value = '';
    } else {
        // Show device credentials section
        devicesContainer.classList.remove('hidden');

        // Generate device fields
        generateDeviceFields(deviceCount);
    }

    // Reset email checkbox
    document.getElementById('send_credentials_email').checked = true;

    // Show modal
    document.getElementById('activateModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function generateDeviceFields(deviceCount) {
    const container = document.getElementById('devicesContainer');
    container.innerHTML = '';

    for (let i = 0; i < deviceCount; i++) {
        const deviceNumber = i + 1; // Display number (1-based for user display)
        const deviceIndex = i; // Array index (0-based for form submission)
        const deviceDiv = document.createElement('div');
        deviceDiv.className = 'bg-gray-50 border border-gray-200 rounded-lg p-4';

        deviceDiv.innerHTML = `
            <h4 class="text-lg font-medium text-[#201E1F] mb-4">Device ${deviceNumber} Credentials</h4>
            <div class="space-y-4">
                <div>
                    <label for="device_${deviceIndex}_username" class="block text-sm font-medium text-[#201E1F] mb-2">
                        Username <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="device_${deviceIndex}_username"
                           name="devices[${deviceIndex}][username]"
                           required
                           class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300"
                           placeholder="Enter username for device ${deviceNumber}">
                </div>

                <div>
                    <label for="device_${deviceIndex}_password" class="block text-sm font-medium text-[#201E1F] mb-2">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="device_${deviceIndex}_password"
                           name="devices[${deviceIndex}][password]"
                           required
                           class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300"
                           placeholder="Enter password for device ${deviceNumber}">
                </div>

                <div>
                    <label for="device_${deviceIndex}_url" class="block text-sm font-medium text-[#201E1F] mb-2">
                        Server URL <span class="text-red-500">*</span>
                    </label>
                    <input type="url"
                           id="device_${deviceIndex}_url"
                           name="devices[${deviceIndex}][url]"
                           required
                           class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300"
                           placeholder="http://server${deviceNumber}.example.com:8080">
                </div>
            </div>
        `;

        container.appendChild(deviceDiv);
    }
}

function closeActivateModal() {
    document.getElementById('activateModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside
document.getElementById('activateModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeActivateModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeActivateModal();
    }
});
</script>

@if ($errors->any())
<script>
    // Re-open activation modal with correct context after validation errors
    document.addEventListener('DOMContentLoaded', function() {
        openActivateModal(
            {{ $order->id }},
            @json($order->order_number),
            @json($order->user->name),
            @json($order->pricingPlan->display_name ?? ($order->resellerCreditPack->name ?? 'IPTV Service')),
            {{ $order->pricingPlan->device_count ?? 1 }},
            @json($order->pricingPlan->plan_type ?? 'regular'),
            @json($order->order_type ?? 'subscription')
        );
    });
}</script>
@endif

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