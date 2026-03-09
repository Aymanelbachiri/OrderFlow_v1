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

                    @if($order->referral_code)
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Traffic Source</label>
                        <div class="bg-white rounded-lg px-4 py-3 border border-gray-200">
                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-purple-50 text-purple-700 border border-purple-200">
                                Referred
                            </span>
                            <div class="mt-2">
                                <p class="text-xs text-gray-500 mb-1">Referral Code:</p>
                                <p class="text-sm font-mono text-[#201E1F]">{{ $order->referral_code }}</p>
                                @if($order->affiliateReferral)
                                    <p class="text-xs text-gray-500 mt-2">
                                        Affiliate: 
                                        <a href="{{ route('admin.affiliates.show', $order->affiliateReferral->affiliate) }}" 
                                           class="text-[#D63613] hover:underline">
                                            {{ $order->affiliateReferral->affiliate->email }}
                                        </a>
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        Status: 
                                        @if($order->affiliateReferral->status === 'pending')
                                            <span class="text-yellow-600">Pending</span>
                                        @elseif($order->affiliateReferral->status === 'approved')
                                            <span class="text-green-600">Approved</span>
                                            @if($order->affiliateReferral->reward_granted)
                                                <span class="text-green-600">(Reward Granted)</span>
                                            @endif
                                        @else
                                            <span class="text-red-600">Rejected</span>
                                        @endif
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Traffic Source</label>
                        <div class="bg-white rounded-lg px-4 py-3 border border-gray-200">
                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-gray-50 text-gray-700 border border-gray-200">
                                Organic
                            </span>
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
                                {{ $order->customProduct->product_type === 'hotplayer_activation' ? 'bg-orange-100 text-orange-700' : '' }}
                                {{ $order->customProduct->product_type === 'other' ? 'bg-gray-100 text-gray-700' : '' }}">
                                {{ $order->customProduct->product_type === 'hotplayer_activation' ? 'HotPlayer Activation' : ucfirst($order->customProduct->product_type) }}
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

                @php
                    // Get HotPlayer data from payment_details or payment intent
                    $macAddress = null;
                    $activationPlan = null;
                    if ($order->payment_details) {
                        $macAddress = $order->payment_details['mac_address'] ?? null;
                        $activationPlan = $order->payment_details['activation_plan'] ?? null;
                    }
                    if (!$macAddress) {
                        $paymentIntent = \App\Models\PaymentIntent::where('payment_intent_id', $order->payment_id)->first();
                        if ($paymentIntent && $paymentIntent->order_data) {
                            $macAddress = $paymentIntent->order_data['mac_address'] ?? null;
                            $activationPlan = $paymentIntent->order_data['activation_plan'] ?? null;
                        }
                    }
                @endphp

                @if($order->customProduct->product_type === 'hotplayer_activation' && $macAddress)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-[#201E1F] mb-4">HotPlayer Activation Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-[#201E1F]/60">MAC Address</label>
                            <p class="text-sm text-[#201E1F] bg-white rounded-lg px-4 py-3 border border-gray-200 font-mono">{{ $macAddress }}</p>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-[#201E1F]/60">Activation Plan</label>
                            <p class="text-sm text-[#201E1F] bg-white rounded-lg px-4 py-3 border border-gray-200">
                                <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full 
                                    {{ $activationPlan === 'FOREVER' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                                    {{ $activationPlan === 'FOREVER' ? 'Lifetime' : '1 Year' }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                @php
                    // Get custom fields from payment_details or order_data
                    $customFieldsData = [];
                    if ($order->payment_details && isset($order->payment_details['custom_fields'])) {
                        $customFieldsData = $order->payment_details['custom_fields'];
                    } elseif ($order->customProduct && $order->customProduct->custom_fields) {
                        // Try to get values from payment intent if available
                        $paymentIntent = \App\Models\PaymentIntent::where('payment_intent_id', $order->payment_id)->first();
                        if ($paymentIntent && isset($paymentIntent->order_data['custom_fields'])) {
                            $customFieldsData = $paymentIntent->order_data['custom_fields'];
                        }
                    }
                @endphp

                @if($order->customProduct->custom_fields && count($order->customProduct->custom_fields) > 0 && !empty($customFieldsData))
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-[#201E1F] mb-4">Custom Field Responses</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($order->customProduct->custom_fields as $index => $field)
                            @if(isset($customFieldsData[$index]) && !empty($customFieldsData[$index]))
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-[#201E1F]/60">{{ $field['label'] }}</label>
                                @php
                                    $fieldValue = $customFieldsData[$index];
                                    $fieldType = $field['type'] ?? 'text';
                                @endphp
                                @if($fieldType === 'checkbox' && is_array($fieldValue))
                                    <p class="text-sm text-[#201E1F] bg-white rounded-lg px-4 py-3 border border-gray-200">
                                        {{ implode(', ', $fieldValue) }}
                                    </p>
                                @else
                                    <p class="text-sm text-[#201E1F] bg-white rounded-lg px-4 py-3 border border-gray-200">{{ $fieldValue }}</p>
                                @endif
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endif
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
                        @php
                            $deviceM3u = $device['m3u_url'] ?? null;
                            if (!$deviceM3u && !empty($device['url']) && !empty($device['username']) && !empty($device['password'])) {
                                $deviceM3u = \App\Models\Order::buildM3uUrl($device['url'], $device['username'], $device['password']);
                            }
                        @endphp
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
                            @if($deviceM3u)
                            <div class="md:col-span-3 space-y-2">
                                <label class="block text-sm font-medium text-[#201E1F]/60">Device {{ $loop->iteration }} M3U Link</label>
                                <p class="text-sm text-[#201E1F] bg-gray-50 rounded-lg px-4 py-3 border border-gray-200 break-all"><a href="{{ $deviceM3u }}" target="_blank" rel="noopener" class="text-[#D63613] hover:underline">{{ $deviceM3u }}</a></p>
                            </div>
                            @endif
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
                        @if($order->order_type === 'custom_product')
                        <button type="button"
                                id="activate-custom-product-btn-{{ $order->id }}"
                                class="activate-custom-product-btn w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold py-3 px-4 rounded-lg flex items-center justify-center space-x-2 transition-all duration-300 shadow-md hover:shadow-lg"
                                data-custom-touch="true"
                                data-order-id="{{ $order->id }}"
                                data-order-number="{{ $order->order_number }}"
                                data-customer-name="{{ $order->user->name }}"
                                data-product-name="{{ $order->customProduct->name ?? 'Custom Product' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Activate Order</span>
                        </button>
                        <script>
                        (function() {
                            const btn = document.getElementById('activate-custom-product-btn-{{ $order->id }}');
                            if (btn) {
                                let touchMoved = false;

                                function openModal() {
                                    const orderId = btn.getAttribute('data-order-id');
                                    const orderNumber = btn.getAttribute('data-order-number');
                                    const customerName = btn.getAttribute('data-customer-name');
                                    const productName = btn.getAttribute('data-product-name');
                                    if (window.openCustomProductActivateModal) {
                                        window.openCustomProductActivateModal(orderId, orderNumber, customerName, productName);
                                    } else {
                                        setTimeout(function() {
                                            if (window.openCustomProductActivateModal) {
                                                window.openCustomProductActivateModal(orderId, orderNumber, customerName, productName);
                                            } else {
                                                alert('Function not available. Please refresh the page.');
                                            }
                                        }, 100);
                                    }
                                }

                                btn.addEventListener('click', function(e) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                    openModal();
                                });

                                // Mobile touch handlers
                                btn.addEventListener('touchstart', function(e) {
                                    touchMoved = false;
                                    this.style.opacity = '0.9';
                                    this.style.transform = 'scale(0.98)';
                                }, { passive: true });

                                btn.addEventListener('touchmove', function(e) {
                                    touchMoved = true;
                                }, { passive: true });

                                btn.addEventListener('touchend', function(e) {
                                    this.style.opacity = '1';
                                    this.style.transform = 'scale(1)';
                                    if (!touchMoved) {
                                        openModal();
                                    }
                                }, { passive: true });
                            }
                        })();
                        </script>
                        @else
                        <button type="button"
                                id="activate-order-btn-{{ $order->id }}"
                                data-custom-touch="true"
                                class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold py-3 px-4 rounded-lg flex items-center justify-center space-x-2 transition-all duration-300 shadow-md hover:shadow-lg"
                                data-order-type="{{ $order->order_type ?? 'subscription' }}"
                                data-order-id="{{ $order->id }}"
                                data-order-number="{{ $order->order_number }}"
                                data-customer-name="{{ $order->user->name }}"
                                data-service-name="{{ $order->order_type === 'credit_pack' ? ($order->resellerCreditPack->name ?? 'Credit Pack') : ($order->pricingPlan->display_name ?? 'IPTV Service') }}"
                                data-device-count="{{ $order->pricingPlan->device_count ?? 1 }}"
                                data-plan-type="{{ $order->pricingPlan->plan_type ?? 'regular' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Activate Order</span>
                        </button>
                        <script>
                        (function() {
                            const btn = document.getElementById('activate-order-btn-{{ $order->id }}');
                            if (btn) {
                                let touchMoved = false;

                                function openModal() {
                                    const orderId = btn.getAttribute('data-order-id');
                                    const orderNumber = btn.getAttribute('data-order-number');
                                    const customerName = btn.getAttribute('data-customer-name');
                                    const serviceName = btn.getAttribute('data-service-name');
                                    const deviceCount = parseInt(btn.getAttribute('data-device-count'));
                                    const planType = btn.getAttribute('data-plan-type');
                                    const orderType = btn.getAttribute('data-order-type');
                                    openActivateModal(orderId, orderNumber, customerName, serviceName, deviceCount, planType, orderType);
                                }

                                btn.addEventListener('click', function(e) {
                                    e.preventDefault();
                                    openModal();
                                });

                                // Mobile touch handlers
                                btn.addEventListener('touchstart', function(e) {
                                    touchMoved = false;
                                    this.style.opacity = '0.9';
                                    this.style.transform = 'scale(0.98)';
                                }, { passive: true });

                                btn.addEventListener('touchmove', function(e) {
                                    touchMoved = true;
                                }, { passive: true });

                                btn.addEventListener('touchend', function(e) {
                                    this.style.opacity = '1';
                                    this.style.transform = 'scale(1)';
                                    if (!touchMoved) {
                                        openModal();
                                    }
                                }, { passive: true });
                            }
                        })();
                        </script>
                        @endif
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
                        <div id="modal-service-row" class="col-span-2">
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
                            <!-- Fill from M3U - all devices (when same server) -->
                            <div id="fillFromM3uSection" class="mb-4 p-4 bg-indigo-50 border border-indigo-200 rounded-lg">
                                <h4 class="text-sm font-medium text-indigo-800 mb-3">Fill All Devices from M3U URL</h4>
                                <p class="text-xs text-indigo-600 mb-3">Paste an M3U URL to auto-fill Server URL, Username, and Password for all devices (use when all devices share the same server). Each device also has its own Fill from M3U below.</p>
                                <div class="flex gap-2">
                                    <input type="url" id="m3u_url_input"
                                           class="flex-1 px-3 py-2 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300"
                                           placeholder="http://server.com/get.php?username=xxx&password=yyy&type=m3u_plus&output=ts">
                                    <button type="button" id="fillFromM3uBtn"
                                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                                        Fill All
                                    </button>
                                </div>
                            </div>
                            <div id="deviceFieldsContainer">
                                <!-- Device fields will be dynamically generated here -->
                            </div>
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

<!-- Custom Product Activation Modal -->
<div id="customProductActivateModal" class="fixed inset-0 bg-black bg-opacity-75 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-2/3 shadow-2xl rounded-xl bg-white max-h-[90vh] overflow-y-auto">
        <div class="mt-3">
            <!-- Modal Header -->
            <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-[#201E1F]">Activate Custom Product Order</h3>
                </div>
                <button type="button" onclick="closeCustomProductActivateModal()" class="text-[#201E1F]/60 hover:text-[#201E1F] transition-colors duration-200">
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
                            <span id="custom-product-modal-order-number" class="font-medium"></span>
                        </div>
                        <div>
                            <span class="text-blue-700">Customer:</span>
                            <span id="custom-product-modal-customer-name" class="font-medium"></span>
                        </div>
                        <div class="col-span-2">
                            <span class="text-blue-700">Product:</span>
                            <span id="custom-product-modal-product-name" class="font-medium"></span>
                        </div>
                    </div>
                </div>

                <form id="customProductActivateForm" method="POST">
                    @csrf
                    <div class="space-y-6">
                        <!-- Email Subject -->
                        <div>
                            <label for="email_subject" class="block text-sm font-medium text-[#201E1F] mb-2">
                                Email Subject <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="email_subject" 
                                   name="email_subject" 
                                   required
                                   value="Order Update - {{ $order->order_number ?? '' }}"
                                   class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300"
                                   placeholder="Enter email subject">
                            <p class="mt-1 text-xs text-gray-500">You can use variables like @{{order_number}}, @{{customer_name}}, @{{product_name}}, etc.</p>
                        </div>

                        <!-- Email Content -->
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <label for="email_content" class="block text-sm font-medium text-[#201E1F]">
                                    Email Content <span class="text-red-500">*</span>
                                </label>
                                <button type="button" 
                                        onclick="showVariableHelper()" 
                                        class="text-xs text-blue-600 hover:text-blue-800 underline">
                                    View Available Variables
                                </button>
                            </div>
                            <textarea id="email_content" 
                                      name="email_content" 
                                      required
                                      rows="12"
                                      class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300 font-mono text-sm"
                                      placeholder="Compose your email here. Use variables like @{{customer_name}}, @{{order_number}}, @{{product_name}}, etc."></textarea>
                            <p class="mt-1 text-xs text-gray-500">The footer with company information will be automatically added.</p>
                        </div>

                        <!-- Variable Helper -->
                        <div id="variableHelper" class="hidden bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-[#201E1F] mb-3">Available Variables</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-xs">
                                <div>
                                    <p class="font-semibold mb-2 text-gray-700">Source Variables:</p>
                                    <ul class="space-y-1 text-gray-600">
                                        <li><code class="bg-white px-1 py-0.5 rounded">@{{company_name}}</code></li>
                                        <li><code class="bg-white px-1 py-0.5 rounded">@{{contact_email}}</code></li>
                                        <li><code class="bg-white px-1 py-0.5 rounded">@{{website}}</code></li>
                                        <li><code class="bg-white px-1 py-0.5 rounded">@{{phone_number}}</code></li>
                                        <li><code class="bg-white px-1 py-0.5 rounded">@{{team_name}}</code></li>
                                    </ul>
                                </div>
                                <div>
                                    <p class="font-semibold mb-2 text-gray-700">Order Variables:</p>
                                    <ul class="space-y-1 text-gray-600">
                                        <li><code class="bg-white px-1 py-0.5 rounded">@{{order_number}}</code></li>
                                        <li><code class="bg-white px-1 py-0.5 rounded">@{{order_amount}}</code></li>
                                        <li><code class="bg-white px-1 py-0.5 rounded">@{{order_date}}</code></li>
                                        <li><code class="bg-white px-1 py-0.5 rounded">@{{order_status}}</code></li>
                                        <li><code class="bg-white px-1 py-0.5 rounded">@{{payment_method}}</code></li>
                                        <li><code class="bg-white px-1 py-0.5 rounded">@{{product_name}}</code></li>
                                        <li><code class="bg-white px-1 py-0.5 rounded">@{{product_type}}</code></li>
                                        <li><code class="bg-white px-1 py-0.5 rounded">@{{product_price}}</code></li>
                                    </ul>
                                </div>
                                <div>
                                    <p class="font-semibold mb-2 text-gray-700">Client Variables:</p>
                                    <ul class="space-y-1 text-gray-600">
                                        <li><code class="bg-white px-1 py-0.5 rounded">@{{customer_name}}</code></li>
                                        <li><code class="bg-white px-1 py-0.5 rounded">@{{customer_email}}</code></li>
                                        <li><code class="bg-white px-1 py-0.5 rounded">@{{customer_phone}}</code></li>
                                    </ul>
                                </div>
                            </div>
                            <button type="button" 
                                    onclick="insertVariable()" 
                                    class="mt-3 text-xs text-blue-600 hover:text-blue-800 underline">
                                Click on a variable to insert it
                            </button>
                        </div>

                        <!-- Send Email Checkbox -->
                        <div class="border-t border-gray-200 pt-4">
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center">
                                    <input type="checkbox"
                                           id="send_email"
                                           name="send_email"
                                           value="1"
                                           checked
                                           class="h-4 w-4 text-[#D63613] focus:ring-[#D63613] border-gray-300 rounded">
                                    <label for="send_email" class="ml-2 block text-sm text-[#201E1F]">
                                        Send update email to customer
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 mt-6">
                        <button type="button"
                                onclick="closeCustomProductActivateModal()"
                                class="bg-white hover:bg-gray-50 text-[#201E1F]/80 hover:text-[#201E1F] border border-gray-200 px-6 py-3 rounded-lg text-sm font-medium transition-all duration-300">
                            Cancel
                        </button>
                        <button type="submit"
                                class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-6 py-3 rounded-lg text-sm font-semibold transition-all duration-300 shadow-md hover:shadow-lg">
                            Activate & Send Email
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@verbatim
<script>
function openActivateModal(orderId, orderNumber, customerName, serviceName, deviceCount, planType = 'regular', orderType = 'subscription') {
    // Set form action
    document.getElementById('activateForm').action = `/admin/orders/${orderId}/activate`;

    // Fill order information
    document.getElementById('modal-order-number').textContent = orderNumber;
    document.getElementById('modal-customer-name').textContent = customerName;
    
    // Show/hide service row based on order type
    const serviceRow = document.getElementById('modal-service-row');
    if (orderType === 'credit_pack') {
        // Hide service row for credit pack orders
        serviceRow.classList.add('hidden');
    } else {
        // Show service row and set service name for other order types
        serviceRow.classList.remove('hidden');
        document.getElementById('modal-service-name').textContent = serviceName;
    }

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

        // Clear Fill from M3U fields
        const m3uInput = document.getElementById('m3u_url_input');
        if (m3uInput) m3uInput.value = '';

        // Generate device fields
        generateDeviceFields(deviceCount);
    }

    // Reset email checkbox
    document.getElementById('send_credentials_email').checked = true;

    // Show modal
    document.getElementById('activateModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function parseM3uUrl(m3uUrl) {
    try {
        const url = new URL(m3uUrl);
        const username = url.searchParams.get('username') || '';
        const password = url.searchParams.get('password') || '';
        const baseUrl = url.origin + url.pathname.substring(0, url.pathname.lastIndexOf('/') + 1);
        return { url: baseUrl, username, password, m3uUrl: m3uUrl };
    } catch (e) {
        return null;
    }
}

function fillFromM3u() {
    const m3uInput = document.getElementById('m3u_url_input');
    const m3uUrl = (m3uInput?.value || '').trim();
    if (!m3uUrl) {
        alert('Please enter an M3U URL first.');
        return;
    }
    const parsed = parseM3uUrl(m3uUrl);
    if (!parsed || !parsed.username || !parsed.password) {
        alert('Could not parse M3U URL. Make sure it contains username and password parameters.');
        return;
    }
    const deviceFieldsContainer = document.getElementById('deviceFieldsContainer');
    const deviceInputs = deviceFieldsContainer ? deviceFieldsContainer.querySelectorAll('input[id^="device_"]') : [];
    deviceInputs.forEach(function(input) {
        const id = input.id;
        const match = id.match(/device_(\d+)_(username|password|url|m3u_url)/);
        if (match) {
            const field = match[2];
            if (field === 'username') input.value = parsed.username;
            else if (field === 'password') input.value = parsed.password;
            else if (field === 'url') input.value = parsed.url;
            else if (field === 'm3u_url') input.value = parsed.m3uUrl;
        }
    });
}

function fillDeviceFromM3u(deviceIndex) {
    const m3uInput = document.getElementById('device_' + deviceIndex + '_m3u_input');
    const m3uUrl = (m3uInput?.value || '').trim();
    if (!m3uUrl) {
        alert('Please enter an M3U URL for this device first.');
        return;
    }
    const parsed = parseM3uUrl(m3uUrl);
    if (!parsed || !parsed.username || !parsed.password) {
        alert('Could not parse M3U URL. Make sure it contains username and password parameters.');
        return;
    }
    const usernameInput = document.getElementById('device_' + deviceIndex + '_username');
    const passwordInput = document.getElementById('device_' + deviceIndex + '_password');
    const urlInput = document.getElementById('device_' + deviceIndex + '_url');
    const m3uUrlInput = document.getElementById('device_' + deviceIndex + '_m3u_url');
    if (usernameInput) usernameInput.value = parsed.username;
    if (passwordInput) passwordInput.value = parsed.password;
    if (urlInput) urlInput.value = parsed.url;
    if (m3uUrlInput) m3uUrlInput.value = parsed.m3uUrl;
}

function generateDeviceFields(deviceCount) {
    const container = document.getElementById('deviceFieldsContainer');
    if (!container) return;
    container.innerHTML = '';

    for (let i = 0; i < deviceCount; i++) {
        const deviceNumber = i + 1; // Display number (1-based for user display)
        const deviceIndex = i; // Array index (0-based for form submission)
        const deviceDiv = document.createElement('div');
        deviceDiv.className = 'bg-gray-50 border border-gray-200 rounded-lg p-4';

        deviceDiv.innerHTML = `
            <h4 class="text-lg font-medium text-[#201E1F] mb-4">Device ${deviceNumber} Credentials</h4>
            <div class="mb-3 p-2 bg-indigo-50/50 rounded border border-indigo-100">
                <p class="text-xs text-indigo-600 mb-2">Fill this device from M3U URL (each device has its own URL):</p>
                <div class="flex gap-2">
                    <input type="url" id="device_${deviceIndex}_m3u_input"
                           class="flex-1 px-2 py-1.5 text-sm bg-white border border-gray-200 rounded-lg"
                           placeholder="http://server.com/get.php?username=xxx&password=yyy&type=m3u_plus">
                    <button type="button" class="device-fill-m3u-btn px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded-lg" data-device-index="${deviceIndex}">
                        Fill from M3U
                    </button>
                </div>
            </div>
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
                <input type="hidden" name="devices[${deviceIndex}][m3u_url]" id="device_${deviceIndex}_m3u_url" value="">
            </div>
        `;

        container.appendChild(deviceDiv);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const fillFromM3uBtn = document.getElementById('fillFromM3uBtn');
    if (fillFromM3uBtn) {
        fillFromM3uBtn.addEventListener('click', fillFromM3u);
    }
    document.getElementById('deviceFieldsContainer')?.addEventListener('click', function(e) {
        const btn = e.target.closest('.device-fill-m3u-btn');
        if (btn) {
            e.preventDefault();
            const deviceIndex = btn.getAttribute('data-device-index');
            if (deviceIndex !== null) fillDeviceFromM3u(parseInt(deviceIndex, 10));
        }
    });
});

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
        closeCustomProductActivateModal();
    }
});

// Custom Product Activation Modal Functions
window.openCustomProductActivateModal = function(orderId, orderNumber, customerName, productName) {
    try {
        // Set form action
        const form = document.getElementById('customProductActivateForm');
        if (!form) {
            console.error('Form element not found: customProductActivateForm');
            return;
        }
        form.action = '/admin/orders/' + orderId + '/activate';

        // Fill order information
        const orderNumberEl = document.getElementById('custom-product-modal-order-number');
        const customerNameEl = document.getElementById('custom-product-modal-customer-name');
        const productNameEl = document.getElementById('custom-product-modal-product-name');
        
        if (orderNumberEl) orderNumberEl.textContent = orderNumber;
        if (customerNameEl) customerNameEl.textContent = customerName;
        if (productNameEl) productNameEl.textContent = productName;

        // Set default email subject
        const subjectEl = document.getElementById('email_subject');
        if (subjectEl) {
            subjectEl.value = 'Order Update - ' + orderNumber;
        }

        // Set default email content - use String.fromCharCode to avoid Blade parsing issues
        const openBrace = String.fromCharCode(123);
        const closeBrace = String.fromCharCode(125);
        const varStart = openBrace + openBrace;
        const varEnd = closeBrace + closeBrace;
        const defaultContent = 'Dear ' + varStart + 'customer_name' + varEnd + ',\n\n' +
            'Thank you for your order! Your custom product order has been processed and activated.\n\n' +
            'Order Details:\n' +
            '- Order Number: ' + varStart + 'order_number' + varEnd + '\n' +
            '- Product: ' + varStart + 'product_name' + varEnd + '\n' +
            '- Amount: $' + varStart + 'order_amount' + varEnd + '\n' +
            '- Order Date: ' + varStart + 'order_date' + varEnd + '\n\n' +
            'Your order is now active and ready for use.\n\n' +
            'If you have any questions, please don\'t hesitate to contact us at ' + varStart + 'contact_email' + varEnd + '.\n\n' +
            'Best regards,\n' +
            varStart + 'team_name' + varEnd;
        
        const contentEl = document.getElementById('email_content');
        if (contentEl) {
            contentEl.value = defaultContent;
        }

        // Show modal
        const modal = document.getElementById('customProductActivateModal');
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        } else {
            console.error('Modal element not found: customProductActivateModal');
        }
    } catch (e) {
        console.error('Error in openCustomProductActivateModal:', e);
        alert('An error occurred while opening the activation modal. Please check the browser console for details.');
    }
}

function closeCustomProductActivateModal() {
    document.getElementById('customProductActivateModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function showVariableHelper() {
    const helper = document.getElementById('variableHelper');
    helper.classList.toggle('hidden');
}

// Insert variable into email content at cursor position
function insertVariable() {
    const variables = document.querySelectorAll('#variableHelper code');
    variables.forEach(variable => {
        variable.style.cursor = 'pointer';
        variable.addEventListener('click', function() {
            const textarea = document.getElementById('email_content');
            const variableText = this.textContent.trim();
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const text = textarea.value;
            const before = text.substring(0, start);
            const after = text.substring(end);
            textarea.value = before + variableText + after;
            textarea.focus();
            textarea.setSelectionRange(start + variableText.length, start + variableText.length);
        });
    });
}

// Initialize variable insertion on page load and attach button listeners
function initCustomProductButtons() {
    insertVariable();
    
    // Add event listeners for custom product activate buttons
    const customProductButtons = document.querySelectorAll('.activate-custom-product-btn');
    
    if (customProductButtons.length === 0) {
        // Try again after a short delay
        setTimeout(initCustomProductButtons, 500);
        return;
    }
    
    customProductButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const orderId = this.getAttribute('data-order-id');
            const orderNumber = this.getAttribute('data-order-number');
            const customerName = this.getAttribute('data-customer-name');
            const productName = this.getAttribute('data-product-name');
            
            if (typeof window.openCustomProductActivateModal === 'function') {
                window.openCustomProductActivateModal(orderId, orderNumber, customerName, productName);
            } else {
                console.error('openCustomProductActivateModal function not found!');
                alert('Error: openCustomProductActivateModal function not found. Please check the console.');
            }
        });
    });
}

// Try multiple ways to ensure it runs
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        initCustomProductButtons();
    });
} else {
    initCustomProductButtons();
}

// Also try after a delay as fallback
setTimeout(function() {
    initCustomProductButtons();
}, 1000);

// Close custom product modal when clicking outside
document.getElementById('customProductActivateModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeCustomProductActivateModal();
    }
});
</script>
@endverbatim

@if ($errors->any())
<script>
    // Re-open activation modal with correct context after validation errors
    document.addEventListener('DOMContentLoaded', function() {
        openActivateModal(
            {{ $order->id }},
            @json($order->order_number),
            @json($order->user->name),
            @json($order->order_type === 'credit_pack' ? ($order->resellerCreditPack->name ?? 'Credit Pack') : ($order->pricingPlan->display_name ?? 'IPTV Service')),
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