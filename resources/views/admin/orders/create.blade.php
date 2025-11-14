@extends('layouts.admin')

@section('title', 'Create New Order')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up">
        <div class="lg:flex space-y-4 justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-[#201E1F] mb-2">Create New Order</h1>
                <p class="text-[#201E1F]/60">Set up a new order for your IPTV services</p>
            </div>
            <div class="flex items-center space-x-3">
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

    <!-- Main Form -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md animate-fade-in-up" style="animation-delay: 0.1s;">
        <div class="px-6 py-5 border-b border-[#D63613]/10">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-[#201E1F]">Order Details</h3>
            </div>
        </div>
        
        <form action="{{ route('admin.orders.store') }}" method="POST" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Customer Selection -->
                <div class="md:col-span-2">
                    <label for="user_id" class="block text-sm font-semibold text-[#201E1F] mb-2">Customer</label>
                    <select id="user_id" 
                            name="user_id" 
                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] transition-all duration-200 @error('user_id') border-red-500 @enderror"
                            required>
                        <option value="">Select Customer</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id', request('user_id')) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }}) - {{ ucfirst($user->role) }}
                        </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Source Selection -->
                <div class="md:col-span-2">
                    <label for="source" class="block text-sm font-semibold text-[#201E1F] mb-2">Source (Optional)</label>
                    <select id="source" 
                            name="source" 
                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] transition-all duration-200 @error('source') border-red-500 @enderror">
                        <option value="">No Source</option>
                        @foreach($sources as $source)
                        <option value="{{ $source->name }}" {{ old('source') == $source->name ? 'selected' : '' }}>
                            {{ $source->name }}@if(!$source->is_active) (Inactive)@endif
                        </option>
                        @endforeach
                    </select>
                    @error('source')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-[#201E1F]/50">Select the source where this order originated from</p>
                </div>

                <!-- Pricing Plan (for clients) -->
                <div class="md:col-span-2" id="pricing_plan_container">
                    <label for="pricing_plan_id" class="block text-sm font-semibold text-[#201E1F] mb-2">Pricing Plan</label>
                    <select id="pricing_plan_id" 
                            name="pricing_plan_id" 
                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] transition-all duration-200 @error('pricing_plan_id') border-red-500 @enderror">
                        <option value="">Select Pricing Plan</option>
                        @foreach($pricingPlans as $plan)
                        <option value="{{ $plan->id }}" 
                                data-price="{{ $plan->price }}"
                                data-role="client"
                                {{ old('pricing_plan_id') == $plan->id ? 'selected' : '' }}>
                            {{ $plan->display_name }} - ${{ number_format($plan->price, 2) }}
                        </option>
                        @endforeach
                    </select>
                    @error('pricing_plan_id')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Reseller Credit Pack (for resellers) -->
                <div class="md:col-span-2 hidden" id="reseller_credit_pack_container">
                    <label for="reseller_credit_pack_id" class="block text-sm font-semibold text-[#201E1F] mb-2">Reseller Credit Pack</label>
                    <select id="reseller_credit_pack_id" 
                            name="reseller_credit_pack_id" 
                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] transition-all duration-200 @error('reseller_credit_pack_id') border-red-500 @enderror">
                        <option value="">Select Credit Pack</option>
                        @foreach($resellerCreditPacks as $pack)
                        <option value="{{ $pack->id }}" 
                                data-price="{{ $pack->price }}"
                                data-role="reseller"
                                {{ old('reseller_credit_pack_id') == $pack->id ? 'selected' : '' }}>
                            {{ $pack->name }} - ${{ number_format($pack->price, 2) }} ({{ number_format($pack->credits_amount) }} Credits)
                        </option>
                        @endforeach
                    </select>
                    @error('reseller_credit_pack_id')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Payment Method -->
                <div>
                    <label for="payment_method" class="block text-sm font-semibold text-[#201E1F] mb-2">Payment Method</label>
                    <select id="payment_method" 
                            name="payment_method" 
                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] transition-all duration-200 @error('payment_method') border-red-500 @enderror"
                            required>
                        <option value="">Select Payment Method</option>
                        <option value="email_link" {{ old('payment_method') == 'email_link' ? 'selected' : '' }}>Email Link</option>
                        <option value="stripe" {{ old('payment_method') == 'stripe' ? 'selected' : '' }}>Stripe</option>
                        <option value="paypal" {{ old('payment_method') == 'paypal' ? 'selected' : '' }}>PayPal</option>
                        <option value="crypto" {{ old('payment_method') == 'crypto' ? 'selected' : '' }}>USDT(TRC20)</option>
                        <option value="manual" {{ old('payment_method') == 'manual' ? 'selected' : '' }}>Manual Payment</option>
                    </select>
                    @error('payment_method')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Order Status -->
                <div>
                    <label for="status" class="block text-sm font-semibold text-[#201E1F] mb-2">Order Status</label>
                    <select id="status" 
                            name="status" 
                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] transition-all duration-200 @error('status') border-red-500 @enderror"
                            required>
                        <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="expired" {{ old('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                        <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('status')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Custom Amount -->
                <div>
                    <label for="amount" class="block text-sm font-semibold text-[#201E1F] mb-2">Amount ($)</label>
                    <input type="number" 
                           id="amount" 
                           name="amount" 
                           value="{{ old('amount') }}"
                           min="0" 
                           step="0.01"
                           class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] placeholder-[#201E1F]/40 transition-all duration-200 @error('amount') border-red-500 @enderror"
                           placeholder="Will be auto-filled from plan">
                    @error('amount')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-[#201E1F]/50">Leave blank to use plan price, or enter custom amount</p>
                </div>

                <!-- Start Date -->
                <div>
                    <label for="starts_at" class="block text-sm font-semibold text-[#201E1F] mb-2">Start Date</label>
                    <input type="datetime-local" 
                           id="starts_at" 
                           name="starts_at" 
                           value="{{ old('starts_at', now()->format('Y-m-d\TH:i')) }}"
                           class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] transition-all duration-200 @error('starts_at') border-red-500 @enderror">
                    @error('starts_at')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Custom Expiry Date -->
            <div class="mt-6">
                <label for="expires_at" class="block text-sm font-semibold text-[#201E1F] mb-2">Expiry Date (Optional)</label>
                <input type="datetime-local" 
                       id="expires_at" 
                       name="expires_at" 
                       value="{{ old('expires_at') }}"
                       class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] transition-all duration-200 @error('expires_at') border-red-500 @enderror">
                @error('expires_at')
                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-sm text-[#201E1F]/50">Leave blank to calculate from plan duration, or set custom expiry date</p>
            </div>

            <!-- Service Credentials -->
            <div class="mt-8 border-t border-gray-200 pt-8">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-orange-400 to-orange-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-[#201E1F]">Service Credentials (Optional)</h3>
                </div>

                <!-- Client Credentials Container (shown when client is selected) -->
                <div id="clientCredentialsContainer" class="hidden">
                    <!-- Single Device Container (default for 1 device plans) -->
                    <div id="singleDeviceContainer" class="bg-white rounded-lg border border-gray-200 p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Subscription Username -->
                            <div class="space-y-2">
                                <label for="subscription_username" class="block text-sm font-medium text-[#201E1F]/60">IPTV Username</label>
                                <input type="text"
                                       id="subscription_username"
                                       name="subscription_username"
                                       value="{{ old('subscription_username') }}"
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
                                       value="{{ old('subscription_password') }}"
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
                                       value="{{ old('subscription_url') }}"
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
                </div>

                <!-- Reseller Credentials Container (shown when reseller is selected or credit pack is selected) -->
                <div id="resellerCredentialsContainer" class="hidden">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                        <h4 class="text-lg font-medium text-green-800 mb-4">Reseller Panel Credentials</h4>
                        <p class="text-sm text-green-700 mb-4">
                            Enter the reseller panel credentials for this order.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="reseller_login_url" class="block text-sm font-medium text-green-800">Panel URL</label>
                                <input type="url"
                                       id="reseller_login_url"
                                       name="reseller_login_url"
                                       value="{{ old('reseller_login_url') }}"
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
                                       value="{{ old('reseller_username') }}"
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
                                       value="{{ old('reseller_password') }}"
                                       class="w-full px-4 py-3 bg-white border border-green-300 rounded-lg text-green-900 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all duration-300"
                                       placeholder="Enter password">
                                @error('reseller_password')
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Admin Notes -->
            <div class="mt-6">
                <label for="admin_notes" class="block text-sm font-semibold text-[#201E1F] mb-2">Admin Notes (Optional)</label>
                <textarea id="admin_notes" 
                          name="admin_notes" 
                          rows="3"
                          class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] placeholder-[#201E1F]/40 transition-all duration-200 resize-none"
                          placeholder="Internal notes about this order...">{{ old('admin_notes') }}</textarea>
                @error('admin_notes')
                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email Notifications -->
            <div class="mt-8 border-t border-gray-200 pt-8">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-[#201E1F]">Email Notifications</h3>
                </div>
                
                <div class="space-y-4 bg-white p-4 rounded-lg border border-gray-200">
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="send_order_confirmation" 
                               name="send_order_confirmation" 
                               value="1"
                               {{ old('send_order_confirmation', '1') ? '' : 'checked' }}
                               class="h-4 w-4 text-[#D63613] focus:ring-[#D63613] border-gray-300 rounded bg-white">
                        <label for="send_order_confirmation" class="ml-3 block text-sm font-medium text-[#201E1F]">
                            Send order confirmation email to customer
                        </label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="send_payment_instructions" 
                               name="send_payment_instructions" 
                               value="1"
                               {{ old('send_payment_instructions', '1') ? '' : 'checked' }}
                               class="h-4 w-4 text-[#D63613] focus:ring-[#D63613] border-gray-300 rounded bg-white">
                        <label for="send_payment_instructions" class="ml-3 block text-sm font-medium text-[#201E1F]">
                            Send payment instructions (if payment method requires it)
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('admin.orders.index') }}" 
                   class="px-6 py-3 bg-white border border-gray-200 text-[#201E1F] font-semibold rounded-lg hover:bg-gray-50 transition-all duration-300">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-gradient-to-r from-[#D63613] to-[#D63613]/80 text-white font-semibold rounded-lg hover:from-[#D63613]/90 hover:to-[#D63613]/70 transition-all duration-300 shadow-md hover:shadow-lg">
                    Create Order
                </button>
            </div>
        </form>
    </div>

    <!-- Order Preview -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up" style="animation-delay: 0.2s;">
        <div class="flex items-center space-x-3 mb-4">
            <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-[#201E1F]">Order Preview</h3>
        </div>
        
        <div id="order-preview" class="bg-white rounded-lg p-4 border border-gray-200 space-y-3">
            <div class="flex justify-between items-center">
                <span class="text-sm font-medium text-[#201E1F]/60">Customer:</span>
                <span id="preview-customer" class="text-sm font-semibold text-[#201E1F]">Select a customer</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm font-medium text-[#201E1F]/60">Plan:</span>
                <span id="preview-plan" class="text-sm font-semibold text-[#201E1F]">Select a pricing plan</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm font-medium text-[#201E1F]/60">Amount:</span>
                <span id="preview-amount" class="text-sm font-semibold text-[#D63613]">$0.00</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm font-medium text-[#201E1F]/60">Duration:</span>
                <span id="preview-duration" class="text-sm font-semibold text-[#201E1F]">N/A</span>
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
</style>

<script>
// Get pricing plan device counts for dynamic field generation
const pricingPlans = @json(\App\Models\PricingPlan::where('is_active', true)->get()->mapWithKeys(function($plan) {
    return [$plan->id => $plan->device_count];
}));

// Get user role from option text
function getUserRole(userSelect) {
    if (!userSelect.value) return null;
    const selectedOption = userSelect.options[userSelect.selectedIndex];
    const roleText = selectedOption.textContent;
    if (roleText.includes('Reseller')) {
        return 'reseller';
    } else if (roleText.includes('Client')) {
        return 'client';
    }
    return null;
}

// Toggle credentials sections based on user role
function toggleCredentialsSections() {
    const userSelect = document.getElementById('user_id');
    const userRole = getUserRole(userSelect);
    const clientCredentialsContainer = document.getElementById('clientCredentialsContainer');
    const resellerCredentialsContainer = document.getElementById('resellerCredentialsContainer');
    
    if (userRole === 'reseller') {
        // Show reseller credentials, hide client credentials
        if (clientCredentialsContainer) clientCredentialsContainer.classList.add('hidden');
        if (resellerCredentialsContainer) resellerCredentialsContainer.classList.remove('hidden');
    } else if (userRole === 'client') {
        // Show client credentials, hide reseller credentials
        if (clientCredentialsContainer) clientCredentialsContainer.classList.remove('hidden');
        if (resellerCredentialsContainer) resellerCredentialsContainer.classList.add('hidden');
        // Update device fields when client is selected
        updateDeviceFields();
    } else {
        // Hide both if no user selected
        if (clientCredentialsContainer) clientCredentialsContainer.classList.add('hidden');
        if (resellerCredentialsContainer) resellerCredentialsContainer.classList.add('hidden');
    }
}

// Function to generate device fields based on device count
function generateDeviceFields(deviceCount) {
    const container = document.getElementById('dynamicDevicesContainer');
    if (!container) return;
    
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
        // Disable single device fields
        const singleDeviceFields = singleDeviceContainer.querySelectorAll('input');
        singleDeviceFields.forEach(field => {
            field.disabled = true;
            field.removeAttribute('name');
        });
    }
    
    // Ensure dynamic devices container is visible
    container.classList.remove('hidden');
    
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
        
        const deviceDiv = document.createElement('div');
        deviceDiv.className = 'bg-gray-50 border border-gray-200 rounded-lg p-6';
        
        deviceDiv.innerHTML = `
            <h4 class="text-lg font-medium text-[#201E1F] mb-4">Device ${deviceNumber} Credentials</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="device_${deviceIndex}_username" class="block text-sm font-medium text-[#201E1F]/60">Username</label>
                    <input type="text"
                           id="device_${deviceIndex}_username"
                           name="devices[${deviceIndex}][username]"
                           value=""
                           class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300"
                           placeholder="Username for device ${deviceNumber}">
                </div>

                <div class="space-y-2">
                    <label for="device_${deviceIndex}_password" class="block text-sm font-medium text-[#201E1F]/60">Password</label>
                    <input type="text"
                           id="device_${deviceIndex}_password"
                           name="devices[${deviceIndex}][password]"
                           value=""
                           class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300"
                           placeholder="Password for device ${deviceNumber}">
                </div>

                <div class="md:col-span-2 space-y-2">
                    <label for="device_${deviceIndex}_url" class="block text-sm font-medium text-[#201E1F]/60">Server URL</label>
                    <input type="url"
                           id="device_${deviceIndex}_url"
                           name="devices[${deviceIndex}][url]"
                           value=""
                           class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300"
                           placeholder="http://server${deviceNumber}.example.com:8080">
                </div>
            </div>
        `;
        
        container.appendChild(deviceDiv);
    }
}

// Function to update device fields based on pricing plan
function updateDeviceFields() {
    const pricingPlanSelect = document.getElementById('pricing_plan_id');
    const singleDeviceContainer = document.getElementById('singleDeviceContainer');
    const dynamicDevicesContainer = document.getElementById('dynamicDevicesContainer');
    
    // Determine device count from pricing plan
    let deviceCount = 1;
    
    if (pricingPlanSelect && pricingPlanSelect.value) {
        const planId = parseInt(pricingPlanSelect.value);
        deviceCount = pricingPlans[planId] || 1;
    }
    
    if (deviceCount > 1) {
        // Hide single device container and show multi-device fields
        if (singleDeviceContainer) {
            singleDeviceContainer.classList.add('hidden');
            // Disable single device fields
            const singleDeviceFields = singleDeviceContainer.querySelectorAll('input');
            singleDeviceFields.forEach(field => {
                field.disabled = true;
                field.removeAttribute('name');
            });
        }
        if (dynamicDevicesContainer) {
            dynamicDevicesContainer.classList.remove('hidden');
            generateDeviceFields(deviceCount);
        }
    } else {
        // Show single device container for 1 device plans
        if (singleDeviceContainer) {
            singleDeviceContainer.classList.remove('hidden');
            // Re-enable single device fields
            const singleDeviceFields = singleDeviceContainer.querySelectorAll('input');
            singleDeviceFields.forEach(field => {
                field.disabled = false;
                const fieldId = field.id;
                if (fieldId.includes('subscription_username')) {
                    field.setAttribute('name', 'subscription_username');
                } else if (fieldId.includes('subscription_password')) {
                    field.setAttribute('name', 'subscription_password');
                } else if (fieldId.includes('subscription_url')) {
                    field.setAttribute('name', 'subscription_url');
                }
            });
        }
        if (dynamicDevicesContainer) {
            dynamicDevicesContainer.classList.add('hidden');
            dynamicDevicesContainer.innerHTML = '';
        }
    }
}

// Toggle between pricing plans and reseller credit packs based on user role
function togglePlanSelection() {
    const userSelect = document.getElementById('user_id');
    const userRole = getUserRole(userSelect);
    const pricingPlanContainer = document.getElementById('pricing_plan_container');
    const resellerCreditPackContainer = document.getElementById('reseller_credit_pack_container');
    const pricingPlanSelect = document.getElementById('pricing_plan_id');
    const resellerCreditPackSelect = document.getElementById('reseller_credit_pack_id');
    
    if (userRole === 'reseller') {
        // Show reseller credit packs, hide pricing plans
        pricingPlanContainer.classList.add('hidden');
        resellerCreditPackContainer.classList.remove('hidden');
        pricingPlanSelect.removeAttribute('required');
        resellerCreditPackSelect.setAttribute('required', 'required');
        pricingPlanSelect.value = '';
    } else {
        // Show pricing plans, hide reseller credit packs
        pricingPlanContainer.classList.remove('hidden');
        resellerCreditPackContainer.classList.add('hidden');
        pricingPlanSelect.setAttribute('required', 'required');
        resellerCreditPackSelect.removeAttribute('required');
        resellerCreditPackSelect.value = '';
    }
    
    // Update credentials sections
    toggleCredentialsSections();
    updatePreview();
}

// Update preview when selections change
function updatePreview() {
    const userSelect = document.getElementById('user_id');
    const planSelect = document.getElementById('pricing_plan_id');
    const creditPackSelect = document.getElementById('reseller_credit_pack_id');
    const amountInput = document.getElementById('amount');
    
    // Update customer preview
    const selectedUser = userSelect.options[userSelect.selectedIndex];
    const customerText = selectedUser.value ? selectedUser.textContent.split(' (')[0] : 'Select a customer';
    document.getElementById('preview-customer').textContent = customerText;
    
    // Update plan/credit pack preview based on user role
    const userRole = getUserRole(userSelect);
    let selectedItem = null;
    let itemText = 'Select a plan';
    
    if (userRole === 'reseller') {
        selectedItem = creditPackSelect.options[creditPackSelect.selectedIndex];
        itemText = 'Select a credit pack';
    } else {
        selectedItem = planSelect.options[planSelect.selectedIndex];
        itemText = 'Select a pricing plan';
    }
    
    if (selectedItem && selectedItem.value) {
        const itemPrice = selectedItem.getAttribute('data-price');
        const itemDisplayText = selectedItem.textContent.split(' - $')[0];
        document.getElementById('preview-plan').textContent = itemDisplayText;
        
        // Always update amount when plan/credit pack changes
        if (amountInput && itemPrice) {
            amountInput.value = itemPrice;
        }
        
        document.getElementById('preview-amount').textContent = '$' + (itemPrice || '0.00');
    } else {
        document.getElementById('preview-plan').textContent = itemText;
        document.getElementById('preview-amount').textContent = '$0.00';
        // Clear amount if no plan selected
        if (amountInput) {
            amountInput.value = '';
        }
    }
}

// Add event listeners
document.addEventListener('DOMContentLoaded', function() {
    const userSelect = document.getElementById('user_id');
    const pricingPlanSelect = document.getElementById('pricing_plan_id');
    
    if (userSelect) {
        userSelect.addEventListener('change', function() {
            togglePlanSelection();
        });
    }
    
    if (pricingPlanSelect) {
        pricingPlanSelect.addEventListener('change', function() {
            updateDeviceFields();
            updatePreview();
        });
    }
    
    const creditPackSelect = document.getElementById('reseller_credit_pack_id');
    if (creditPackSelect) {
        creditPackSelect.addEventListener('change', function() {
            updatePreview();
        });
    }
    
    // Don't auto-update amount when user manually types in it
    // But still update preview display
    const amountInput = document.getElementById('amount');
    if (amountInput) {
        let isManualEdit = false;
        amountInput.addEventListener('input', function() {
            isManualEdit = true;
            updatePreview();
        });
        amountInput.addEventListener('focus', function() {
            isManualEdit = false; // Reset on focus so plan changes can update it again
        });
    }
    
    // Initial setup
    togglePlanSelection();
});
</script>
@endsection