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
        
        // Auto-fill amount if not manually set
        if (!amountInput.value) {
            amountInput.value = itemPrice;
        }
        
        document.getElementById('preview-amount').textContent = '$' + (amountInput.value || itemPrice);
    } else {
        document.getElementById('preview-plan').textContent = itemText;
        document.getElementById('preview-amount').textContent = '$0.00';
    }
}

// Add event listeners
document.getElementById('user_id').addEventListener('change', function() {
    togglePlanSelection();
});
document.getElementById('pricing_plan_id').addEventListener('change', updatePreview);
document.getElementById('reseller_credit_pack_id').addEventListener('change', updatePreview);
document.getElementById('amount').addEventListener('input', updatePreview);

// Initial setup
togglePlanSelection();
</script>
@endsection