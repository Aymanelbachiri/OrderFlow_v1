@extends('layouts.admin')

@section('title', 'Edit Order - ' . $order->order_number)

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up">
        <div class="lg:flex space-y-4 justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-gradient-to-br from-[#D63613] to-[#D63613]/80 rounded-xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-[#201E1F] mb-1">Edit Order</h1>
                    <p class="text-[#201E1F]/60 font-mono">{{ $order->order_number }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.orders.show', $order) }}" 
                   class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-3 rounded-lg text-sm font-semibold flex items-center space-x-2 transition-all duration-300 shadow-md hover:shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    <span>View Order</span>
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

    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md animate-fade-in-up" style="animation-delay: 0.1s;">
        <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="p-6 space-y-8">
            @csrf
            @method('PUT')
            
            <!-- Order Information -->
            <div>
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-[#201E1F]">Order Information</h3>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Order Number (Read-only) -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Order Number</label>
                        <input type="text" 
                               value="{{ $order->order_number }}"
                               class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-lg text-[#201E1F] font-mono"
                               readonly>
                    </div>

                    <!-- Status -->
                    <div class="space-y-2">
                        <label for="status" class="block text-sm font-medium text-[#201E1F]/60">Order Status</label>
                        <select id="status" 
                                name="status" 
                                class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300 @error('status') border-red-300 @enderror"
                                required>
                            <option value="pending" {{ old('status', $order->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="active" {{ old('status', $order->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="expired" {{ old('status', $order->status) == 'expired' ? 'selected' : '' }}>Expired</option>
                            <option value="cancelled" {{ old('status', $order->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Amount -->
                    <div class="space-y-2">
                        <label for="amount" class="block text-sm font-medium text-[#201E1F]/60">Amount ($)</label>
                        <input type="number" 
                               id="amount" 
                               name="amount" 
                               value="{{ old('amount', $order->amount) }}"
                               min="0" 
                               step="0.01"
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300 @error('amount') border-red-300 @enderror"
                               required>
                        @error('amount')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Subscription Type (Only for subscription orders) -->
                    @if($order->order_type === 'subscription')
                    <div class="space-y-2">
                        <label for="subscription_type" class="block text-sm font-medium text-[#201E1F]/60">Subscription Type</label>
                        <select id="subscription_type" 
                                name="subscription_type" 
                                class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300 @error('subscription_type') border-red-300 @enderror"
                                required>
                            <option value="new" {{ old('subscription_type', $order->subscription_type) == 'new' ? 'selected' : '' }}>New Subscription</option>
                            <option value="renewal" {{ old('subscription_type', $order->subscription_type) == 'renewal' ? 'selected' : '' }}>Renewal</option>
                        </select>
                        @error('subscription_type')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    @endif

                    <!-- Pricing Plan (Only for regular subscription orders) -->
                    @if($order->order_type === 'subscription')
                    <div class="space-y-2">
                        <label for="pricing_plan_id" class="block text-sm font-medium text-[#201E1F]/60">Pricing Plan</label>
                        <div class="text-xs text-gray-500 mb-2">
                            Current: {{ $order->pricingPlan->display_name }} - ${{ number_format($order->pricingPlan->price, 2) }}
                        </div>
                        <select id="pricing_plan_id" 
                                name="pricing_plan_id" 
                                class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300 @error('pricing_plan_id') border-red-300 @enderror"
                                required>
                            @foreach(\App\Models\PricingPlan::where('is_active', true)->orderBy('display_name')->get() as $plan)
                                <option value="{{ $plan->id }}" {{ old('pricing_plan_id', $order->pricing_plan_id) == $plan->id ? 'selected' : '' }}>
                                    {{ $plan->display_name }} - ${{ number_format($plan->price, 2) }}
                                    @if($plan->plan_type === 'reseller')
                                        (Reseller)
                                    @elseif($plan->plan_type === 'credit_pack')
                                        (Credit Pack)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('pricing_plan_id')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">
                            <span class="text-orange-600">⚠️</span> Changing the plan will automatically update the order amount to match the new plan's price.
                        </p>
                    </div>
                    @endif

                    <!-- Credit Pack Selection (Only for credit pack orders) -->
                    @if($order->order_type === 'credit_pack')
                    <div class="space-y-2">
                        <label for="reseller_credit_pack_id" class="block text-sm font-medium text-[#201E1F]/60">Credit Pack</label>
                        <div class="text-xs text-gray-500 mb-2">
                            Current: {{ $order->resellerCreditPack->name ?? 'Unknown Credit Pack' }} - ${{ number_format($order->amount, 2) }}
                        </div>
                        <select id="reseller_credit_pack_id" 
                                name="reseller_credit_pack_id" 
                                class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300 @error('reseller_credit_pack_id') border-red-300 @enderror"
                                required>
                            @foreach(\App\Models\ResellerCreditPack::where('is_active', true)->orderBy('name')->get() as $creditPack)
                                <option value="{{ $creditPack->id }}" {{ old('reseller_credit_pack_id', $order->pricing_plan_id) == $creditPack->id ? 'selected' : '' }}>
                                    {{ $creditPack->name }} - ${{ number_format($creditPack->price, 2) }}
                                    ({{ $creditPack->credits }} credits)
                                </option>
                            @endforeach
                        </select>
                        @error('reseller_credit_pack_id')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">
                            <span class="text-orange-600">⚠️</span> Changing the credit pack will automatically update the order amount to match the new pack's price.
                        </p>
                    </div>
                    @endif

                    <!-- Payment Method -->
                    <div class="space-y-2">
                        <label for="payment_method" class="block text-sm font-medium text-[#201E1F]/60">Payment Method</label>
                        <select id="payment_method" 
                                name="payment_method" 
                                class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300 @error('payment_method') border-red-300 @enderror"
                                required>
                            <option value="email_link" {{ old('payment_method', $order->payment_method) == 'email_link' ? 'selected' : '' }}>Email Link</option>
                            <option value="stripe" {{ old('payment_method', $order->payment_method) == 'stripe' ? 'selected' : '' }}>Stripe</option>
                            <option value="paypal" {{ old('payment_method', $order->payment_method) == 'paypal' ? 'selected' : '' }}>PayPal</option>
                            <option value="crypto" {{ old('payment_method', $order->payment_method) == 'crypto' ? 'selected' : '' }}>USDT(TRC20)</option>
                            <option value="manual" {{ old('payment_method', $order->payment_method) == 'manual' ? 'selected' : '' }}>Manual Payment</option>
                        </select>
                        @error('payment_method')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Start Date -->
                    <div class="space-y-2">
                        <label for="starts_at" class="block text-sm font-medium text-[#201E1F]/60">Start Date</label>
                        <input type="datetime-local" 
                               id="starts_at" 
                               name="starts_at" 
                               value="{{ old('starts_at', $order->starts_at ? $order->starts_at->format('Y-m-d\TH:i') : '') }}"
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300 @error('starts_at') border-red-300 @enderror">
                        @error('starts_at')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Expiry Date -->
                    <div class="space-y-2">
                        <label for="expires_at" class="block text-sm font-medium text-[#201E1F]/60">Expiry Date</label>
                        <input type="datetime-local" 
                               id="expires_at" 
                               name="expires_at" 
                               value="{{ old('expires_at', $order->expires_at ? $order->expires_at->format('Y-m-d\TH:i') : '') }}"
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300 @error('expires_at') border-red-300 @enderror">
                        @error('expires_at')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Customer Information (Read-only) -->
            <div class="border-t border-gray-200 pt-8">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-[#201E1F]">Customer Information</h3>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Customer Name</label>
                        <input type="text" 
                               value="{{ $order->user->name }}"
                               class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-lg text-[#201E1F]"
                               readonly>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Email Address</label>
                        <input type="text" 
                               value="{{ $order->user->email }}"
                               class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-lg text-[#201E1F]"
                               readonly>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Customer Role</label>
                        <input type="text" 
                               value="{{ ucfirst($order->user->role) }}"
                               class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-lg text-[#201E1F]"
                               readonly>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Phone Number</label>
                        <input type="text" 
                               value="{{ $order->user->phone ?: 'Not provided' }}"
                               class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-lg text-[#201E1F]"
                               readonly>
                    </div>
                </div>
            </div>

            <!-- Credit Pack Information (Read-only) for Reseller Orders -->
            @if($order->order_type === 'credit_pack' && $order->resellerCreditPack)
            <div class="border-t border-gray-200 pt-8">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-[#201E1F]">Credit Pack Information</h3>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Credit Pack Name</label>
                        <input type="text" 
                               value="{{ $order->resellerCreditPack->name }}"
                               class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-lg text-[#201E1F]"
                               readonly>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Credits Amount</label>
                        <input type="text" 
                               value="{{ number_format($order->resellerCreditPack->credits_amount) }} Credits"
                               class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-lg text-[#201E1F]"
                               readonly>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Pack Price</label>
                        <input type="text" 
                               value="${{ number_format($order->resellerCreditPack->price, 2) }}"
                               class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-lg text-[#201E1F]"
                               readonly>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Pack Status</label>
                        <input type="text" 
                               value="{{ $order->resellerCreditPack->is_active ? 'Active' : 'Inactive' }}"
                               class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-lg text-[#201E1F]"
                               readonly>
                    </div>
                </div>
            </div>
            @elseif($order->pricingPlan && $order->pricingPlan->plan_type === 'regular')
            <!-- Pricing Plan Information (Read-only) for Regular Client Orders Only -->
            <div class="border-t border-gray-200 pt-8">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-[#201E1F]">Pricing Plan Information</h3>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Plan Name</label>
                        <input type="text" 
                               value="{{ $order->pricingPlan->display_name }}"
                               class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-lg text-[#201E1F]"
                               readonly>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Server Type</label>
                        <input type="text" 
                               value="{{ ucfirst($order->pricingPlan->server_type) }}"
                               class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-lg text-[#201E1F]"
                               readonly>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Device Count</label>
                        <input type="text" 
                               value="{{ $order->pricingPlan->device_count }} device(s)"
                               class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-lg text-[#201E1F]"
                               readonly>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Duration</label>
                        <input type="text" 
                               value="{{ $order->pricingPlan->duration_months }} month(s)"
                               class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-lg text-[#201E1F]"
                               readonly>
                    </div>
                </div>
            </div>
            @endif

            <!-- Service Credentials -->
            <div class="border-t border-gray-200 pt-8">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-orange-400 to-orange-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-[#201E1F]">Service Credentials</h3>
                    </div>
                </div>

                @if($order->user->role === 'client')
                    @if($order->devices && count($order->devices) > 0)
                        <!-- Multi-Device Credentials -->
                        <div class="space-y-6">
                            @foreach($order->devices as $index => $device)
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                                <h4 class="text-lg font-medium text-[#201E1F] mb-4">Device {{ $device['device_number'] }} Credentials</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label for="device_{{ $device['device_number'] }}_username" class="block text-sm font-medium text-[#201E1F]/60">Username</label>
                                        <input type="text"
                                               id="device_{{ $device['device_number'] }}_username"
                                               name="devices[{{ $device['device_number'] }}][username]"
                                               value="{{ old('devices.' . $device['device_number'] . '.username', $device['username']) }}"
                                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300"
                                               placeholder="Username for device {{ $device['device_number'] }}">
                                        @error('devices.' . $device['device_number'] . '.username')
                                            <p class="text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="space-y-2">
                                        <label for="device_{{ $device['device_number'] }}_password" class="block text-sm font-medium text-[#201E1F]/60">Password</label>
                                        <input type="text"
                                               id="device_{{ $device['device_number'] }}_password"
                                               name="devices[{{ $device['device_number'] }}][password]"
                                               value="{{ old('devices.' . $device['device_number'] . '.password', $device['password']) }}"
                                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300"
                                               placeholder="Password for device {{ $device['device_number'] }}">
                                        @error('devices.' . $device['device_number'] . '.password')
                                            <p class="text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="md:col-span-2 space-y-2">
                                        <label for="device_{{ $device['device_number'] }}_url" class="block text-sm font-medium text-[#201E1F]/60">Server URL</label>
                                        <input type="url"
                                               id="device_{{ $device['device_number'] }}_url"
                                               name="devices[{{ $device['device_number'] }}][url]"
                                               value="{{ old('devices.' . $device['device_number'] . '.url', $device['url']) }}"
                                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300"
                                               placeholder="http://server{{ $device['device_number'] }}.example.com:8080">
                                        @error('devices.' . $device['device_number'] . '.url')
                                            <p class="text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Single Device (Backward Compatibility) -->
                        <div class="bg-white rounded-lg border border-gray-200 p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Subscription Username -->
                                <div class="space-y-2">
                                    <label for="subscription_username" class="block text-sm font-medium text-[#201E1F]/60">IPTV Username</label>
                                    <input type="text"
                                           id="subscription_username"
                                           name="subscription_username"
                                           value="{{ old('subscription_username', $order->subscription_username) }}"
                                           class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300"
                                           placeholder="IPTV service username">
                                    @error('subscription_username')
                                        <p class="text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Subscription Password -->
                                <div class="space-y-2">
                                    <label for="subscription_password" class="block text-sm font-medium text-[#201E1F]/60">IPTV Password</label>
                                    <input type="text"
                                           id="subscription_password"
                                           name="subscription_password"
                                           value="{{ old('subscription_password', $order->subscription_password) }}"
                                           class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300"
                                           placeholder="IPTV service password">
                                    @error('subscription_password')
                                        <p class="text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Subscription URL -->
                                <div class="md:col-span-2 space-y-2">
                                    <label for="subscription_url" class="block text-sm font-medium text-[#201E1F]/60">IPTV Server URL</label>
                                    <input type="url"
                                           id="subscription_url"
                                           name="subscription_url"
                                           value="{{ old('subscription_url', $order->subscription_url) }}"
                                           class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300"
                                           placeholder="http://your-server.com:8080">
                                    @error('subscription_url')
                                        <p class="text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endif
                @endif

                @if($order->order_type === 'credit_pack' || ($order->pricingPlan && $order->pricingPlan->plan_type === 'reseller'))
                <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                    <h4 class="text-lg font-medium text-green-800 mb-4">Reseller Panel Credentials</h4>
                    <p class="text-sm text-green-700 mb-4">
                        Edit the reseller panel credentials for this order.
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="reseller_login_url" class="block text-sm font-medium text-green-800">Panel URL</label>
                            <input type="url"
                                   id="reseller_login_url"
                                   name="reseller_login_url"
                                   value="{{ old('reseller_login_url', $order->reseller_login_url) }}"
                                   class="w-full px-4 py-3 bg-white border border-green-300 rounded-lg text-green-900 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all duration-300"
                                   placeholder="https://panel.example.com">
                            @error('reseller_login_url')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="reseller_username" class="block text-sm font-medium text-green-800">Username</label>
                            <input type="text"
                                   id="reseller_username"
                                   name="reseller_username"
                                   value="{{ old('reseller_username', $order->reseller_username) }}"
                                   class="w-full px-4 py-3 bg-white border border-green-300 rounded-lg text-green-900 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all duration-300"
                                   placeholder="Enter username">
                            @error('reseller_username')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2 space-y-2">
                            <label for="reseller_password" class="block text-sm font-medium text-green-800">Password</label>
                            <input type="text"
                                   id="reseller_password"
                                   name="reseller_password"
                                   value="{{ old('reseller_password', $order->reseller_password) }}"
                                   class="w-full px-4 py-3 bg-white border border-green-300 rounded-lg text-green-900 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all duration-300"
                                   placeholder="Enter password">
                            @error('reseller_password')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Admin Notes -->
            <div class="mb-6 border-t pt-6">
                <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-2">Admin Notes</label>
                <textarea id="admin_notes"
                          name="admin_notes"
                          rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Internal notes about this order...">{{ old('admin_notes', $order->notes) }}</textarea>
                @error('admin_notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email Notifications -->
            <div class="mb-6 border-t pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Email Notifications</h3>
                
                <div class="space-y-3">
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="send_status_update" 
                               name="send_status_update" 
                               value="1"
                               {{ old('send_status_update', false) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="send_status_update" class="ml-2 block text-sm text-gray-900">
                            Send status update email to customer
                        </label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="send_credentials" 
                               name="send_credentials" 
                               value="1"
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="send_credentials" class="ml-2 block text-sm text-gray-900">
                            Send service credentials (if order is activated)
                        </label>
                    </div>
                </div>
            </div>

            <!-- Order History -->
            <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                <h4 class="text-sm font-medium text-gray-900 mb-2">Order History</h4>
                <div class="text-sm text-gray-600 space-y-1">
                    <p><strong>Created:</strong> {{ $order->created_at->format('M d, Y H:i') }}</p>
                    <p><strong>Last Updated:</strong> {{ $order->updated_at->format('M d, Y H:i') }}</p>
                    @if($order->starts_at)
                    <p><strong>Service Start:</strong> {{ $order->starts_at->format('M d, Y H:i') }}</p>
                    @endif
                    @if($order->expires_at)
                    <p><strong>Service Expiry:</strong> {{ $order->expires_at->format('M d, Y H:i') }}</p>
                    @endif
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.orders.show', $order) }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-6 rounded">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                    Update Order
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

