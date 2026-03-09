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
                            <option value="completed" {{ old('status', $order->status) == 'completed' ? 'selected' : '' }}>Completed</option>
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

                    <!-- Source -->
                    <div class="space-y-2">
                        <label for="source" class="block text-sm font-medium text-[#201E1F]/60">Source (Optional)</label>
                        <select id="source" 
                                name="source" 
                                class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300 @error('source') border-red-300 @enderror">
                            <option value="">No Source</option>
                            @foreach($sources as $source)
                            <option value="{{ $source->name }}" {{ old('source', $order->source) == $source->name ? 'selected' : '' }}>
                                {{ $source->name }}@if(!$source->is_active) (Inactive)@endif
                            </option>
                            @endforeach
                        </select>
                        @error('source')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Select the source where this order originated from</p>
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

            <!-- Customer (Editable) -->
            <div class="border-t border-gray-200 pt-8">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-[#201E1F]">Customer</h3>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2 space-y-2">
                        <label for="user_id" class="block text-sm font-medium text-[#201E1F]/60">Customer</label>
                        <select id="user_id" 
                                name="user_id" 
                                class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300 @error('user_id') border-red-300 @enderror"
                                required>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id', $order->user_id) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }}) - {{ ucfirst($user->role) }}
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="referral_code" class="block text-sm font-medium text-[#201E1F]/60">Referral Code</label>
                        <input type="text" 
                               id="referral_code"
                               name="referral_code"
                               value="{{ old('referral_code', $order->referral_code) }}"
                               maxlength="12"
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300 @error('referral_code') border-red-300 @enderror"
                               placeholder="Affiliate referral code">
                        @error('referral_code')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Custom Product (for custom product orders) -->
            @if($order->order_type === 'custom_product')
            <div class="border-t border-gray-200 pt-8">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-amber-400 to-amber-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-[#201E1F]">Custom Product</h3>
                    </div>
                </div>
                
                <div class="space-y-2">
                    <label for="custom_product_id" class="block text-sm font-medium text-[#201E1F]/60">Product</label>
                    <select id="custom_product_id" 
                            name="custom_product_id" 
                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300 @error('custom_product_id') border-red-300 @enderror">
                        @foreach($customProducts as $product)
                            <option value="{{ $product->id }}" 
                                    data-price="{{ $product->price }}"
                                    {{ old('custom_product_id', $order->custom_product_id) == $product->id ? 'selected' : '' }}>
                                {{ $product->name }} - ${{ number_format($product->price, 2) }}
                            </option>
                        @endforeach
                    </select>
                    @error('custom_product_id')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Changing the product will update the order amount to match the new product's price.</p>
                </div>
            </div>

            <!-- Custom Fields (MAC address, custom field responses) for custom product orders -->
            @if($order->order_type === 'custom_product' && $order->customProduct)
            @php
                $paymentDetails = $order->payment_details ?? [];
                $macAddress = $paymentDetails['mac_address'] ?? null;
                $activationPlan = $paymentDetails['activation_plan'] ?? null;
                if (!$macAddress && $order->payment_id) {
                    $paymentIntent = \App\Models\PaymentIntent::where('payment_intent_id', $order->payment_id)->first();
                    if ($paymentIntent && $paymentIntent->order_data) {
                        $macAddress = $macAddress ?? ($paymentIntent->order_data['mac_address'] ?? null);
                        $activationPlan = $activationPlan ?? ($paymentIntent->order_data['activation_plan'] ?? null);
                    }
                }
                $customFieldsData = $paymentDetails['custom_fields'] ?? [];
                if (empty($customFieldsData) && $order->payment_id) {
                    $paymentIntent = $paymentIntent ?? \App\Models\PaymentIntent::where('payment_intent_id', $order->payment_id)->first();
                    if ($paymentIntent && isset($paymentIntent->order_data['custom_fields'])) {
                        $customFieldsData = $paymentIntent->order_data['custom_fields'];
                    }
                }
            @endphp
            <div class="border-t border-gray-200 pt-8">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-teal-400 to-teal-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-[#201E1F]">Custom Fields</h3>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($order->customProduct->product_type === 'hotplayer_activation')
                    <div class="space-y-2">
                        <label for="mac_address" class="block text-sm font-medium text-[#201E1F]/60">MAC Address</label>
                        <input type="text" id="mac_address" name="mac_address"
                               value="{{ old('mac_address', $macAddress) }}"
                               placeholder="XX:XX:XX:XX:XX:XX"
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300 font-mono">
                        @error('mac_address')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500">Format: XX:XX:XX:XX:XX:XX</p>
                    </div>
                    <div class="space-y-2">
                        <label for="activation_plan" class="block text-sm font-medium text-[#201E1F]/60">Activation Plan</label>
                        <select id="activation_plan" name="activation_plan"
                                class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300">
                            <option value="YEAR_1" {{ old('activation_plan', $activationPlan) === 'YEAR_1' ? 'selected' : '' }}>1 Year</option>
                            <option value="FOREVER" {{ old('activation_plan', $activationPlan) === 'FOREVER' ? 'selected' : '' }}>Lifetime</option>
                        </select>
                    </div>
                    @endif

                    @if($order->customProduct->custom_fields && count($order->customProduct->custom_fields) > 0)
                        @foreach($order->customProduct->custom_fields as $index => $field)
                        @php
                            $fieldType = $field['type'] ?? 'text';
                            $fieldName = "custom_fields[{$index}]";
                            $fieldValue = $customFieldsData[$index] ?? '';
                            if (is_array($fieldValue)) {
                                $fieldValue = $fieldType === 'checkbox' ? $fieldValue : implode(', ', $fieldValue);
                            }
                            $options = $field['options'] ?? [];
                            if (is_string($options)) {
                                $options = array_filter(array_map('trim', explode("\n", $options)));
                            }
                        @endphp
                        <div class="space-y-2 {{ ($field['width'] ?? 'full') === 'half' ? 'md:col-span-1' : 'md:col-span-2' }}">
                            <label for="custom_field_{{ $index }}" class="block text-sm font-medium text-[#201E1F]/60">{{ $field['label'] }}</label>
                            @if($fieldType === 'textarea')
                                <textarea id="custom_field_{{ $index }}" name="{{ $fieldName }}" rows="3"
                                          class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300">{{ old('custom_fields.' . $index, $fieldValue) }}</textarea>
                            @elseif($fieldType === 'select' && !empty($options))
                                <select id="custom_field_{{ $index }}" name="{{ $fieldName }}"
                                        class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300">
                                    <option value="">— Select —</option>
                                    @foreach($options as $option)
                                        <option value="{{ $option }}" {{ old('custom_fields.' . $index, $fieldValue) === $option ? 'selected' : '' }}>{{ $option }}</option>
                                    @endforeach
                                </select>
                            @elseif($fieldType === 'radio' && !empty($options))
                                <div class="flex flex-wrap gap-4">
                                    @foreach($options as $option)
                                        <label class="flex items-center">
                                            <input type="radio" name="{{ $fieldName }}" value="{{ $option }}"
                                                   {{ old('custom_fields.' . $index, $fieldValue) === $option ? 'checked' : '' }}
                                                   class="w-4 h-4 text-[#D63613] border-gray-300 focus:ring-[#D63613]">
                                            <span class="ml-2 text-sm text-[#201E1F]">{{ $option }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @elseif($fieldType === 'checkbox')
                                @if(!empty($options))
                                <div class="flex flex-wrap gap-4">
                                    @foreach($options as $option)
                                        @php
                                            $checkedValues = is_array($fieldValue) ? $fieldValue : (is_string($fieldValue) ? array_map('trim', explode(',', $fieldValue)) : []);
                                            $isChecked = in_array($option, $checkedValues);
                                            $oldVal = old('custom_fields.' . $index);
                                            if ($oldVal !== null) {
                                                $isChecked = is_array($oldVal) ? in_array($option, $oldVal) : ($oldVal === $option);
                                            }
                                        @endphp
                                        <label class="flex items-center">
                                            <input type="checkbox" name="{{ $fieldName }}[]" value="{{ $option }}"
                                                   {{ $isChecked ? 'checked' : '' }}
                                                   class="w-4 h-4 text-[#D63613] border-gray-300 rounded focus:ring-[#D63613]">
                                            <span class="ml-2 text-sm text-[#201E1F]">{{ $option }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @else
                                <label class="flex items-center">
                                    <input type="checkbox" name="{{ $fieldName }}" value="1"
                                           {{ old('custom_fields.' . $index, $fieldValue) ? 'checked' : '' }}
                                           class="w-4 h-4 text-[#D63613] border-gray-300 rounded focus:ring-[#D63613]">
                                    <span class="ml-2 text-sm text-[#201E1F]">Yes</span>
                                </label>
                                @endif
                            @elseif($fieldType === 'number')
                                <input type="number" id="custom_field_{{ $index }}" name="{{ $fieldName }}"
                                       value="{{ old('custom_fields.' . $index, $fieldValue) }}"
                                       class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300">
                            @elseif($fieldType === 'email')
                                <input type="email" id="custom_field_{{ $index }}" name="{{ $fieldName }}"
                                       value="{{ old('custom_fields.' . $index, $fieldValue) }}"
                                       class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300">
                            @else
                                <input type="text" id="custom_field_{{ $index }}" name="{{ $fieldName }}"
                                       value="{{ old('custom_fields.' . $index, $fieldValue) }}"
                                       class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300">
                            @endif
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
            @endif
            @endif

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
                    <!-- Fill from M3U (fills all devices when same server) -->
                    <div class="mb-4 p-4 bg-indigo-50 border border-indigo-200 rounded-lg">
                        <h4 class="text-sm font-medium text-indigo-800 mb-3">Fill All Devices from M3U URL</h4>
                        <p class="text-xs text-indigo-600 mb-3">Paste an M3U URL to auto-fill Server URL, Username, and Password for all devices (use when all share the same server). Each device also has its own Fill from M3U below.</p>
                        <div class="flex gap-2">
                            <input type="url" id="m3u_url_input_edit"
                                   class="flex-1 px-3 py-2 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300"
                                   placeholder="http://server.com/get.php?username=xxx&password=yyy&type=m3u_plus&output=ts">
                            <button type="button" id="fillFromM3uBtnEdit"
                                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                                Fill All
                            </button>
                        </div>
                    </div>

                    <!-- Device Credentials Container -->
                    <div id="deviceCredentialsContainer">
                        @php
                            $hasExistingDevices = $order->devices && is_array($order->devices) && count($order->devices) > 0;
                            $deviceCount = $order->pricingPlan ? $order->pricingPlan->device_count : 1;
                        @endphp
                        @if($hasExistingDevices)
                            <!-- Multi-Device Credentials (Existing) -->
                            <div class="space-y-6" id="existingDevicesContainer">
                                @foreach($order->devices as $index => $device)
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                                    <h4 class="text-lg font-medium text-[#201E1F] mb-4">Device {{ ($device['device_number'] ?? $index) + 1 }} Credentials</h4>
                                    <div class="mb-4 p-2 bg-indigo-50/50 rounded border border-indigo-100">
                                        <p class="text-xs text-indigo-600 mb-2">Fill this device from M3U URL (each device has its own URL):</p>
                                        <div class="flex gap-2">
                                            <input type="url" id="device_edit_{{ $device['device_number'] ?? $index }}_m3u_input"
                                                   class="flex-1 px-2 py-1.5 text-sm bg-white border border-gray-200 rounded-lg"
                                                   placeholder="http://server.com/get.php?username=xxx&password=yyy&type=m3u_plus">
                                            <button type="button" class="device-fill-m3u-btn-edit px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded-lg" data-device-index="{{ $device['device_number'] ?? $index }}">
                                                Fill from M3U
                                            </button>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="space-y-2">
                                            <label for="device_{{ $device['device_number'] ?? $index }}_username" class="block text-sm font-medium text-[#201E1F]/60">Username</label>
                                            <input type="text"
                                                   id="device_{{ $device['device_number'] ?? $index }}_username"
                                                   name="devices[{{ $device['device_number'] ?? $index }}][username]"
                                                   value="{{ old('devices.' . ($device['device_number'] ?? $index) . '.username', $device['username'] ?? '') }}"
                                                   class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300"
                                                   placeholder="Username for device {{ ($device['device_number'] ?? $index) + 1 }}">
                                            @error('devices.' . ($device['device_number'] ?? $index) . '.username')
                                                <p class="text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="space-y-2">
                                            <label for="device_{{ $device['device_number'] ?? $index }}_password" class="block text-sm font-medium text-[#201E1F]/60">Password</label>
                                            <input type="text"
                                                   id="device_{{ $device['device_number'] ?? $index }}_password"
                                                   name="devices[{{ $device['device_number'] ?? $index }}][password]"
                                                   value="{{ old('devices.' . ($device['device_number'] ?? $index) . '.password', $device['password'] ?? '') }}"
                                                   class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300"
                                                   placeholder="Password for device {{ ($device['device_number'] ?? $index) + 1 }}">
                                            @error('devices.' . ($device['device_number'] ?? $index) . '.password')
                                                <p class="text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="md:col-span-2 space-y-2">
                                            <label for="device_{{ $device['device_number'] ?? $index }}_url" class="block text-sm font-medium text-[#201E1F]/60">Server URL</label>
                                            <input type="url"
                                                   id="device_{{ $device['device_number'] ?? $index }}_url"
                                                   name="devices[{{ $device['device_number'] ?? $index }}][url]"
                                                   value="{{ old('devices.' . ($device['device_number'] ?? $index) . '.url', $device['url'] ?? '') }}"
                                                   class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300"
                                                   placeholder="http://server{{ ($device['device_number'] ?? $index) + 1 }}.example.com:8080">
                                            @error('devices.' . ($device['device_number'] ?? $index) . '.url')
                                                <p class="text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <!-- Single Device (Backward Compatibility) or Dynamic Multi-Device -->
                            <div id="singleDeviceContainer" class="bg-white rounded-lg border border-gray-200 p-6">
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
                            
                            <!-- Dynamic Multi-Device Container (hidden by default) -->
                            <div id="dynamicDevicesContainer" class="space-y-6 hidden"></div>
                        @endif
                        
                        <!-- Always include dynamic container for plan changes (even if existing devices are shown) -->
                        @if($hasExistingDevices)
                            <div id="dynamicDevicesContainer" class="space-y-6 hidden"></div>
                        @endif
                    </div>
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

<script>
// Fill from M3U - parse URL and populate credential fields
function parseM3uUrlEdit(m3uUrl) {
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

function fillFromM3uEdit() {
    const m3uInput = document.getElementById('m3u_url_input_edit');
    const m3uUrl = (m3uInput?.value || '').trim();
    if (!m3uUrl) {
        alert('Please enter an M3U URL first.');
        return;
    }
    const parsed = parseM3uUrlEdit(m3uUrl);
    if (!parsed || !parsed.username || !parsed.password) {
        alert('Could not parse M3U URL. Make sure it contains username and password parameters.');
        return;
    }
    // Fill single device fields
    const usernameEl = document.getElementById('subscription_username');
    const passwordEl = document.getElementById('subscription_password');
    const urlEl = document.getElementById('subscription_url');
    if (usernameEl) usernameEl.value = parsed.username;
    if (passwordEl) passwordEl.value = parsed.password;
    if (urlEl) urlEl.value = parsed.url;
    // Fill device fields (existing or dynamic)
    document.querySelectorAll('[id^="device_"]').forEach(function(input) {
        const match = input.id.match(/device_(\d+)_(username|password|url)/);
        if (match) {
            if (match[2] === 'username') input.value = parsed.username;
            else if (match[2] === 'password') input.value = parsed.password;
            else if (match[2] === 'url') input.value = parsed.url;
        }
    });
}

function fillDeviceFromM3uEdit(deviceIndex) {
    const m3uInput = document.getElementById('device_edit_' + deviceIndex + '_m3u_input');
    if (!m3uInput) return;
    const m3uUrl = (m3uInput.value || '').trim();
    if (!m3uUrl) {
        alert('Please enter an M3U URL for this device first.');
        return;
    }
    const parsed = parseM3uUrlEdit(m3uUrl);
    if (!parsed || !parsed.username || !parsed.password) {
        alert('Could not parse M3U URL. Make sure it contains username and password parameters.');
        return;
    }
    const usernameInput = document.getElementById('device_' + deviceIndex + '_username');
    const passwordInput = document.getElementById('device_' + deviceIndex + '_password');
    const urlInput = document.getElementById('device_' + deviceIndex + '_url');
    if (usernameInput) usernameInput.value = parsed.username;
    if (passwordInput) passwordInput.value = parsed.password;
    if (urlInput) urlInput.value = parsed.url;
}

document.addEventListener('DOMContentLoaded', function() {
    const fillBtn = document.getElementById('fillFromM3uBtnEdit');
    if (fillBtn) fillBtn.addEventListener('click', fillFromM3uEdit);
    document.getElementById('deviceCredentialsContainer')?.addEventListener('click', function(e) {
        const btn = e.target.closest('.device-fill-m3u-btn-edit');
        if (btn) {
            e.preventDefault();
            const deviceIndex = btn.getAttribute('data-device-index');
            if (deviceIndex !== null) fillDeviceFromM3uEdit(parseInt(deviceIndex, 10));
        }
    });
});

// Get pricing plan device counts for dynamic field generation
const pricingPlans = @json(\App\Models\PricingPlan::where('is_active', true)->get()->mapWithKeys(function($plan) {
    return [$plan->id => $plan->device_count];
}));

// Get current order's pricing plan device count (if exists)
const currentOrderDeviceCount = @json($order->pricingPlan ? $order->pricingPlan->device_count : 1);

// Get existing devices data from order (if any)
const existingDevices = @json($order->devices && is_array($order->devices) && count($order->devices) > 0 ? $order->devices : []);

// Function to generate device fields based on device count
function generateDeviceFields(deviceCount) {
    const container = document.getElementById('dynamicDevicesContainer');
    if (!container) {
        console.error('dynamicDevicesContainer not found');
        return;
    }
    
    container.innerHTML = '';
    
    if (deviceCount <= 1) {
        container.classList.add('hidden');
        const singleDeviceContainer = document.getElementById('singleDeviceContainer');
        if (singleDeviceContainer) {
            singleDeviceContainer.classList.remove('hidden');
        }
        return;
    }
    
    // Hide single device container
    const singleDeviceContainer = document.getElementById('singleDeviceContainer');
    if (singleDeviceContainer) {
        singleDeviceContainer.classList.add('hidden');
    }
    
    // Ensure dynamic devices container is visible
    container.classList.remove('hidden');
    
    // Create a map of existing devices by device_number for easy lookup
    const devicesMap = {};
    if (existingDevices && Array.isArray(existingDevices)) {
        existingDevices.forEach(device => {
            const deviceNum = parseInt(device.device_number) || 0;
            devicesMap[deviceNum] = device;
        });
    }
    
    // Escape HTML to prevent XSS
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Generate fields for each device (0-based indexing)
    for (let i = 0; i < deviceCount; i++) {
        const deviceNumber = i + 1; // Display number (1-based for user display)
        const deviceIndex = i; // Array index (0-based for form submission)
        
        // Get existing device data if available
        const existingDevice = devicesMap[deviceIndex] || null;
        const existingUsername = existingDevice ? (existingDevice.username || '') : '';
        const existingPassword = existingDevice ? (existingDevice.password || '') : '';
        const existingUrl = existingDevice ? (existingDevice.url || '') : '';
        
        const deviceDiv = document.createElement('div');
        deviceDiv.className = 'bg-gray-50 border border-gray-200 rounded-lg p-6';
        
        deviceDiv.innerHTML = `
            <h4 class="text-lg font-medium text-[#201E1F] mb-4">Device ${deviceNumber} Credentials</h4>
            <div class="mb-4 p-2 bg-indigo-50/50 rounded border border-indigo-100">
                <p class="text-xs text-indigo-600 mb-2">Fill this device from M3U URL (each device has its own URL):</p>
                <div class="flex gap-2">
                    <input type="url" id="device_edit_${deviceIndex}_m3u_input"
                           class="flex-1 px-2 py-1.5 text-sm bg-white border border-gray-200 rounded-lg"
                           placeholder="http://server.com/get.php?username=xxx&password=yyy&type=m3u_plus">
                    <button type="button" class="device-fill-m3u-btn-edit px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded-lg" data-device-index="${deviceIndex}">
                        Fill from M3U
                    </button>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="device_${deviceIndex}_username" class="block text-sm font-medium text-[#201E1F]/60">Username</label>
                    <input type="text"
                           id="device_${deviceIndex}_username"
                           name="devices[${deviceIndex}][username]"
                           value="${escapeHtml(existingUsername)}"
                           class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300"
                           placeholder="Username for device ${deviceNumber}">
                </div>

                <div class="space-y-2">
                    <label for="device_${deviceIndex}_password" class="block text-sm font-medium text-[#201E1F]/60">Password</label>
                    <input type="text"
                           id="device_${deviceIndex}_password"
                           name="devices[${deviceIndex}][password]"
                           value="${escapeHtml(existingPassword)}"
                           class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300"
                           placeholder="Password for device ${deviceNumber}">
                </div>

                <div class="md:col-span-2 space-y-2">
                    <label for="device_${deviceIndex}_url" class="block text-sm font-medium text-[#201E1F]/60">Server URL</label>
                    <input type="url"
                           id="device_${deviceIndex}_url"
                           name="devices[${deviceIndex}][url]"
                           value="${escapeHtml(existingUrl)}"
                           class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300"
                           placeholder="http://server${deviceNumber}.example.com:8080">
                </div>
            </div>
        `;
        
        container.appendChild(deviceDiv);
    }
}

// Function to update device fields based on pricing plan (regardless of status)
function updateDeviceFields() {
    const pricingPlanSelect = document.getElementById('pricing_plan_id');
    const existingDevicesContainer = document.getElementById('existingDevicesContainer');
    const singleDeviceContainer = document.getElementById('singleDeviceContainer');
    const dynamicDevicesContainer = document.getElementById('dynamicDevicesContainer');
    
    // Determine device count from pricing plan (regardless of status)
    let deviceCount = 1;
    
    if (pricingPlanSelect && pricingPlanSelect.value) {
        const planId = parseInt(pricingPlanSelect.value);
        deviceCount = pricingPlans[planId] || 1;
    } else if (currentOrderDeviceCount) {
        // Use current order's device count if pricing plan select doesn't exist
        deviceCount = currentOrderDeviceCount;
    }
    
    // Always update device fields when plan changes, even if existing devices are shown
    // Hide existing devices container if we're switching to a different device count
    if (existingDevicesContainer && existingDevicesContainer.children.length > 0) {
        const currentDeviceCount = existingDevices.length > 0 ? existingDevices.length : currentOrderDeviceCount;
        
        // If device count changed, hide existing devices and show dynamic fields
        if (deviceCount !== currentDeviceCount) {
            // Hide existing devices container
            existingDevicesContainer.classList.add('hidden');
            // Clear existing devices container so we can regenerate
            existingDevicesContainer.innerHTML = '';
            // Continue to generate new fields below
        } else {
            // Device count matches, keep existing devices visible
            if (deviceCount > 1) {
                if (singleDeviceContainer) {
                    singleDeviceContainer.classList.add('hidden');
                }
                if (dynamicDevicesContainer) {
                    dynamicDevicesContainer.classList.add('hidden');
                }
            } else {
                // Single device - show single container, hide others
                if (singleDeviceContainer) {
                    singleDeviceContainer.classList.remove('hidden');
                }
                if (dynamicDevicesContainer) {
                    dynamicDevicesContainer.classList.add('hidden');
                }
            }
            return; // Don't override existing devices display if count matches
        }
    }
    
    if (deviceCount > 1) {
        // Hide single device container and show multi-device fields
        if (singleDeviceContainer) {
            singleDeviceContainer.classList.add('hidden');
            // Remove single device fields from form submission by making them disabled
            const singleDeviceFields = singleDeviceContainer.querySelectorAll('input');
            singleDeviceFields.forEach(field => {
                field.disabled = true;
                // Also remove the name attribute so they're not submitted
                field.removeAttribute('name');
            });
        }
        if (dynamicDevicesContainer) {
            // Ensure dynamic devices container is visible
            dynamicDevicesContainer.classList.remove('hidden');
            // Generate fields with existing device data preserved
            generateDeviceFields(deviceCount);
            // Double-check it's visible after generation
            if (dynamicDevicesContainer.classList.contains('hidden')) {
                dynamicDevicesContainer.classList.remove('hidden');
            }
        }
    } else {
        // Show single device container for 1 device plans
        if (singleDeviceContainer) {
            singleDeviceContainer.classList.remove('hidden');
            // Re-enable single device fields
            const singleDeviceFields = singleDeviceContainer.querySelectorAll('input');
            singleDeviceFields.forEach(field => {
                field.disabled = false;
                // Restore original name attribute
                const fieldId = field.id;
                if (fieldId.includes('subscription_username')) {
                    field.setAttribute('name', 'subscription_username');
                    // Pre-fill with first device's data if available
                    if (existingDevices && existingDevices.length > 0 && existingDevices[0].username) {
                        field.value = existingDevices[0].username;
                    }
                } else if (fieldId.includes('subscription_password')) {
                    field.setAttribute('name', 'subscription_password');
                    // Pre-fill with first device's data if available
                    if (existingDevices && existingDevices.length > 0 && existingDevices[0].password) {
                        field.value = existingDevices[0].password;
                    }
                } else if (fieldId.includes('subscription_url')) {
                    field.setAttribute('name', 'subscription_url');
                    // Pre-fill with first device's data if available
                    if (existingDevices && existingDevices.length > 0 && existingDevices[0].url) {
                        field.value = existingDevices[0].url;
                    }
                }
            });
        }
        if (dynamicDevicesContainer) {
            dynamicDevicesContainer.classList.add('hidden');
            // Clear dynamic devices container
            dynamicDevicesContainer.innerHTML = '';
        }
    }
}

// Add event listeners
document.addEventListener('DOMContentLoaded', function() {
    const pricingPlanSelect = document.getElementById('pricing_plan_id');
    const existingDevicesContainer = document.getElementById('existingDevicesContainer');
    const singleDeviceContainer = document.getElementById('singleDeviceContainer');
    const dynamicDevicesContainer = document.getElementById('dynamicDevicesContainer');
    
    // Update device fields when pricing plan changes (regardless of status)
    if (pricingPlanSelect) {
        pricingPlanSelect.addEventListener('change', updateDeviceFields);
    }
    
    // Initial check - show correct number of device fields based on current plan
    // If no existing devices and plan has multiple devices, show dynamic fields
    if (!existingDevicesContainer || existingDevicesContainer.children.length === 0) {
        updateDeviceFields();
    } else {
        // Existing devices are shown, just ensure containers are properly configured
        const deviceCount = currentOrderDeviceCount;
        if (deviceCount > 1) {
            if (singleDeviceContainer) {
                singleDeviceContainer.classList.add('hidden');
            }
            if (dynamicDevicesContainer) {
                dynamicDevicesContainer.classList.add('hidden');
            }
        }
    }
    
    // Debug: Log form data before submission
    const form = document.querySelector('form[action*="orders"]');
    if (form) {
        form.addEventListener('submit', function(e) {
            const formData = new FormData(form);
            const devicesData = {};
            for (let [key, value] of formData.entries()) {
                if (key.startsWith('devices[')) {
                    const match = key.match(/devices\[(\d+)\]\[(\w+)\]/);
                    if (match) {
                        const deviceNum = match[1];
                        const field = match[2];
                        if (!devicesData[deviceNum]) {
                            devicesData[deviceNum] = {};
                        }
                        devicesData[deviceNum][field] = value;
                    }
                }
            }
            console.log('Form submission - Devices data:', devicesData);
            console.log('Form submission - All form data:', Object.fromEntries(formData));
        });
    }
});
</script>
@endsection

