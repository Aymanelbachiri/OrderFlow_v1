@extends('layouts.checkout')

@section('title', 'Renew Your Subscription')

@section('content')
<div class="min-h-screen flex items-center py-12 bg-gray-50 dark:bg-gray-900">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-10 animate-fade-in-up">
            <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                Renew Your Subscription
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2 text-lg">
                Continue enjoying our service
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Current Subscription Info -->
            <aside class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm sticky top-10 overflow-hidden">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center space-x-2 mb-6">
                            <span class="inline-flex w-8 h-8 rounded-md bg-gradient-to-br from-indigo-500 to-blue-500 items-center justify-center text-white">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </span>
                            <span>Current Subscription</span>
                        </h2>

                        <div class="space-y-4">
                            <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-700/50">
                                <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Order Number</div>
                                <div class="font-semibold text-gray-900 dark:text-white">{{ $order->order_number }}</div>
                            </div>

                            <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-700/50">
                                <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Current Plan</div>
                                <div class="font-semibold text-gray-900 dark:text-white">
                                    {{ $order->pricingPlan->display_name ?? 'N/A' }}
                                </div>
                            </div>

                            @if($order->expires_at)
                                <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-xl 
                                    @if($isExpired) bg-red-50 dark:bg-red-900/20 border-red-300 dark:border-red-800
                                    @elseif($daysUntilExpiry <= 7) bg-yellow-50 dark:bg-yellow-900/20 border-yellow-300 dark:border-yellow-800
                                    @else bg-gray-50 dark:bg-gray-700/50
                                    @endif">
                                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Expires</div>
                                    <div class="font-semibold 
                                        @if($isExpired) text-red-700 dark:text-red-400
                                        @elseif($daysUntilExpiry <= 7) text-yellow-700 dark:text-yellow-400
                                        @else text-gray-900 dark:text-white
                                        @endif">
                                        {{ $order->expires_at->format('M d, Y') }}
                                    </div>
                                    @if($daysUntilExpiry !== null)
                                        <div class="text-xs mt-1 
                                            @if($isExpired) text-red-600 dark:text-red-400
                                            @elseif($daysUntilExpiry <= 7) text-yellow-600 dark:text-yellow-400
                                            @else text-gray-500 dark:text-gray-400
                                            @endif">
                                            @if($isExpired)
                                                Expired {{ ceil(abs($daysUntilExpiry)) }} day(s) ago
                                            @else
                                                {{ ceil($daysUntilExpiry) }} day(s) remaining
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endif

                            @if($samePlan)
                                <div class="mt-6 p-4 bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800 rounded-xl">
                                    <div class="text-sm font-medium text-indigo-900 dark:text-indigo-300 mb-2">
                                        Quick Renew Available
                                    </div>
                                    <a href="{{ route('renewal.quick', ['orderNumber' => $order->order_number, 'email' => $order->user->email, 'source' => $source ?? $order->source ?? null]) }}" 
                                        class="block w-full text-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                                        Renew Same Plan (One Click)
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-gray-100 dark:bg-gray-700 py-3 px-6 text-sm text-center text-gray-600 dark:text-gray-300">
                        Secure & encrypted renewal
                    </div>
                </div>
            </aside>

            <!-- Right Column: Renewal Form -->
            <main class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-8">
                    @if ($errors->any())
                        <div class="mb-6 p-4 border border-red-200 bg-red-50 dark:bg-red-900/20 dark:border-red-800 rounded-xl text-red-700 dark:text-red-400">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                        <form method="POST" action="{{ route('renewal.submit', $order->order_number) }}" class="space-y-6" id="renewal-form">
                        @csrf
                        <input type="hidden" name="email" value="{{ $order->user->email }}">
                        <input type="hidden" name="subscription_type" value="renewal">
                        <input type="hidden" name="source" value="{{ $source ?? request('source') ?? 'renewal' }}">

                        <!-- Customer Info (Pre-filled) -->
                        <section>
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Your Information</h2>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Full Name *
                                    </label>
                                    <input type="text" name="full_name" value="{{ old('full_name', $order->user->name) }}" required
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Email Address *
                                        </label>
                                        <input type="email" value="{{ $order->user->email }}" disabled
                                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 px-4 py-3 text-gray-500 dark:text-gray-400 cursor-not-allowed">
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Email cannot be changed</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Phone Number *
                                        </label>
                                        <input type="text" name="phone" value="{{ old('phone', $order->user->phone) }}" required
                                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- Plan Selection -->
                        <section class="border-t border-gray-200 dark:border-gray-700 pt-8">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Select Plan</h2>
                            
                            @if($samePlan)
                                <div class="mb-6 p-4 bg-indigo-50 dark:bg-indigo-900/20 border-2 border-indigo-300 dark:border-indigo-700 rounded-xl">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <div class="font-semibold text-gray-900 dark:text-white mb-1">
                                                Current Plan (Quick Renew)
                                            </div>
                                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ $samePlan->display_name }}
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-lg font-bold text-indigo-600 dark:text-indigo-400">
                                                ${{ number_format($samePlan->price, 2) }}
                                            </div>
                                            <a href="{{ route('renewal.quick', ['orderNumber' => $order->order_number, 'email' => $order->user->email, 'source' => $source ?? $order->source ?? null]) }}" 
                                                class="text-xs text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 underline">
                                                Quick Renew
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                    Or customize your renewal plan:
                                </div>
                            @endif

                            <!-- Plan Customization -->
                            <div class="space-y-4">
                                <!-- Server Type -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Server Type *
                                    </label>
                                    <select name="server_type" id="server_type" required
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">Select Type</option>
                                        @foreach($availableServerTypes as $type)
                                            <option value="{{ $type }}" 
                                                {{ old('server_type', $samePlan?->server_type) === $type ? 'selected' : '' }}>
                                                {{ ucfirst($type) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Device Count -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Number of Devices *
                                    </label>
                                    <select name="device_count" id="device_count" required
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">Select Devices</option>
                                        @foreach($availableDeviceCounts as $count)
                                            <option value="{{ $count }}" 
                                                {{ old('device_count', $samePlan?->device_count) == $count ? 'selected' : '' }}>
                                                {{ $count }} Device{{ $count > 1 ? 's' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Duration -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Duration *
                                    </label>
                                    <select name="duration_months" id="duration_months" required
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">Select Duration</option>
                                        @foreach($availableDurations as $months)
                                            <option value="{{ $months }}" 
                                                {{ old('duration_months', $samePlan?->duration_months) == $months ? 'selected' : '' }}>
                                                {{ $months }} Month{{ $months > 1 ? 's' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Selected Plan Display -->
                                <div id="selected-plan-display" class="hidden p-4 bg-green-50 dark:bg-green-900/20 border-2 border-green-300 dark:border-green-700 rounded-xl">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <div class="font-semibold text-gray-900 dark:text-white" id="selected-plan-name">
                                                Selected Plan
                                            </div>
                                            <div class="text-sm text-gray-600 dark:text-gray-400" id="selected-plan-details">
                                                -
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-xl font-bold text-green-600 dark:text-green-400" id="selected-plan-price">
                                                $0.00
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Error Message -->
                                <div id="plan-error" class="hidden p-4 bg-red-50 dark:bg-red-900/20 border-2 border-red-300 dark:border-red-700 rounded-xl">
                                    <p class="text-sm text-red-700 dark:text-red-400">
                                        No pricing plan available for the selected combination. Please choose different options.
                                    </p>
                                </div>

                                <!-- Hidden input for pricing_plan_id -->
                                <input type="hidden" name="pricing_plan_id" id="pricing_plan_id" value="{{ old('pricing_plan_id', $samePlan?->id ?? '') }}">
                            </div>
                        </section>

                        <!-- Payment Method -->
                        <section class="border-t border-gray-200 dark:border-gray-700 pt-8">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Payment Method</h2>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                @foreach ($availablePaymentMethods as $method)
                                    <label class="payment-method-card relative cursor-pointer group">
                                        <input type="radio" name="payment_method" value="{{ $method['key'] ?? '' }}"
                                            class="sr-only payment-method-radio"
                                            {{ old('payment_method', $defaultPaymentMethod) === ($method['key'] ?? '') ? 'checked' : '' }}>
                                        <div class="p-4 border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-800 hover:border-indigo-500 transition-all duration-200 flex flex-col items-center justify-center text-center">
                                            <div class="w-12 h-12 mb-3 flex items-center justify-center rounded-lg bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400">
                                                @switch($method['key'])
                                                    @case('stripe')
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                        </svg>
                                                    @break
                                                    @case('paypal')
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                                            <path d="M14.06 3.713c.12-1.071-.093-1.832-.702-2.526C12.628.356 11.312 0 9.626 0H4.734a.7.7 0 0 0-.691.59L2.005 13.509a.42.42 0 0 0 .415.486h2.756l-.202 1.28a.628.628 0 0 0 .62.726H8.14c.429 0 .793-.31.862-.731l.025-.13.48-3.043.03-.164.001-.007a.35.35 0 0 1 .348-.297h.38c1.266 0 2.425-.256 3.345-.91q.57-.403.993-1.005a4.94 4.94 0 0 0 .88-2.195c.242-1.246.13-2.356-.57-3.154a2.7 2.7 0 0 0-.76-.59l-.094-.061ZM6.543 8.82a.7.7 0 0 1 .321-.079H8.3c2.82 0 5.027-1.144 5.672-4.456l.003-.016q.326.186.548.438c.546.623.679 1.535.45 2.71-.272 1.397-.866 2.307-1.663 2.874-.802.57-1.842.815-3.043.815h-.38a.87.87 0 0 0-.863.734l-.03.164-.48 3.043-.024.13-.001.004a.35.35 0 0 1-.348.296H5.595a.106.106 0 0 1-.105-.123l.208-1.32z"/>
                                                        </svg>
                                                    @break
                                                    @case('crypto')
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                                            <path d="M5.5 13v1.25c0 .138.112.25.25.25h1a.25.25 0 0 0 .25-.25V13h.5v1.25c0 .138.112.25.25.25h1a.25.25 0 0 0 .25-.25V13h.084c1.992 0 3.416-1.033 3.416-2.82 0-1.502-1.007-2.323-2.186-2.44v-.088c.97-.242 1.683-.974 1.683-2.19C11.997 3.93 10.847 3 9.092 3H9V1.75a.25.25 0 0 0-.25-.25h-1a.25.25 0 0 0-.25.25V3h-.573V1.75a.25.25 0 0 0-.25-.25H5.75a.25.25 0 0 0-.25.25V3l-1.998.011a.25.25 0 0 0-.25.25v.989c0 .137.11.25.248.25l.755-.005a.75.75 0 0 1 .745.75v5.505a.75.75 0 0 1-.75.75l-.748.011a.25.25 0 0 0-.25.25v1c0 .138.112.25.25.25zm1.427-8.513h1.719c.906 0 1.438.498 1.438 1.312 0 .871-.575 1.362-1.877 1.362h-1.28zm0 4.051h1.84c1.137 0 1.756.58 1.756 1.524 0 .953-.626 1.45-2.158 1.45H6.927z"/>
                                                        </svg>
                                                    @break
                                                @endswitch
                                            </div>
                                            <p class="font-medium text-gray-900 dark:text-gray-100 text-sm">
                                                {{ $method['name'] ?? ucfirst($method['key'] ?? 'method') }}
                                            </p>
                                            <div class="payment-method-check hidden absolute top-3 right-3 w-6 h-6 bg-indigo-600 rounded-full text-white flex items-center justify-center">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </section>

                        <!-- Submit -->
                        <section class="border-t border-gray-200 dark:border-gray-700 pt-8">
                            <button type="submit" data-custom-touch="true"
                                class="w-full py-4 text-lg font-semibold rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white shadow-lg transition-all duration-300 flex items-center justify-center space-x-3 touch-manipulation"
                                style="-webkit-tap-highlight-color: transparent;">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                <span>Renew Subscription</span>
                            </button>
                        </section>
                    </form>
                </div>
            </main>
        </div>
    </div>
</div>

<script>
    // Payment method selection styling
    document.addEventListener('DOMContentLoaded', function() {
        const paymentRadios = document.querySelectorAll('.payment-method-radio');
        paymentRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.payment-method-card').forEach(card => {
                    const cardRadio = card.querySelector('.payment-method-radio');
                    const check = card.querySelector('.payment-method-check');
                    if (cardRadio.checked) {
                        card.querySelector('div').classList.add('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/20');
                        check.classList.remove('hidden');
                    } else {
                        card.querySelector('div').classList.remove('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/20');
                        check.classList.add('hidden');
                    }
                });
            });
            if (radio.checked) radio.dispatchEvent(new Event('change'));
        });

        // Plan selection logic
        const plans = {!! $plansJson !!};
        const serverTypeSelect = document.getElementById('server_type');
        const deviceCountSelect = document.getElementById('device_count');
        const durationSelect = document.getElementById('duration_months');
        const planIdInput = document.getElementById('pricing_plan_id');
        const planDisplay = document.getElementById('selected-plan-display');
        const planError = document.getElementById('plan-error');
        const planName = document.getElementById('selected-plan-name');
        const planDetails = document.getElementById('selected-plan-details');
        const planPrice = document.getElementById('selected-plan-price');

        function findMatchingPlan() {
            const serverType = serverTypeSelect.value;
            const deviceCount = parseInt(deviceCountSelect.value);
            const duration = parseInt(durationSelect.value);

            if (!serverType || !deviceCount || !duration) {
                planDisplay.classList.add('hidden');
                planError.classList.add('hidden');
                planIdInput.value = '';
                return;
            }

            // Find matching plan
            const matchingPlan = plans.find(plan => 
                plan.server_type === serverType &&
                plan.device_count === deviceCount &&
                plan.duration_months === duration
            );

            if (matchingPlan) {
                planIdInput.value = matchingPlan.id;
                planName.textContent = matchingPlan.display_name;
                planDetails.textContent = `${matchingPlan.device_count} Device(s) · ${matchingPlan.duration_months} Month(s)`;
                planPrice.textContent = '$' + parseFloat(matchingPlan.price).toFixed(2);
                planDisplay.classList.remove('hidden');
                planError.classList.add('hidden');
            } else {
                planIdInput.value = '';
                planDisplay.classList.add('hidden');
                planError.classList.remove('hidden');
            }
        }

        // Add event listeners
        if (serverTypeSelect && deviceCountSelect && durationSelect) {
            serverTypeSelect.addEventListener('change', findMatchingPlan);
            deviceCountSelect.addEventListener('change', findMatchingPlan);
            durationSelect.addEventListener('change', findMatchingPlan);
            
            // Initialize on page load if all values are set
            if (serverTypeSelect.value && deviceCountSelect.value && durationSelect.value) {
                findMatchingPlan();
            } else if (planIdInput.value) {
                // If plan ID is set (from same plan), find and display it
                const planId = parseInt(planIdInput.value);
                const existingPlan = plans.find(p => parseInt(p.id) === planId);
                if (existingPlan) {
                    planName.textContent = existingPlan.display_name;
                    planDetails.textContent = `${existingPlan.device_count} Device(s) · ${existingPlan.duration_months} Month(s)`;
                    planPrice.textContent = '$' + parseFloat(existingPlan.price).toFixed(2);
                    planDisplay.classList.remove('hidden');
                }
            }
        }

        // Form validation
        const renewalForm = document.getElementById('renewal-form');
        const submitBtn = renewalForm?.querySelector('button[type="submit"]');

        if (renewalForm) {
            renewalForm.addEventListener('submit', function(e) {
                const planId = planIdInput.value;
                if (!planId) {
                    e.preventDefault();
                    alert('Please select a valid plan by choosing Server Type, Number of Devices, and Duration.');
                    planError.classList.remove('hidden');
                    return false;
                }
            });
        }

        // MOBILE FIX: Trigger form submission on touchend
        if (submitBtn && renewalForm) {
            let isSubmitting = false;
            let touchMoved = false;

            submitBtn.addEventListener('touchstart', function(e) {
                touchMoved = false;
                this.style.opacity = '0.9';
                this.style.transform = 'scale(0.98)';
            }, { passive: true });

            submitBtn.addEventListener('touchmove', function(e) {
                touchMoved = true;
            }, { passive: true });

            submitBtn.addEventListener('touchend', function(e) {
                this.style.opacity = '1';
                this.style.transform = 'scale(1)';

                if (!touchMoved && !isSubmitting) {
                    e.preventDefault();
                    isSubmitting = true;

                    if (typeof renewalForm.requestSubmit === 'function') {
                        renewalForm.requestSubmit(this);
                    } else {
                        renewalForm.submit();
                    }
                    setTimeout(() => { isSubmitting = false; }, 3000);
                }
            });

            // Desktop click handler
            submitBtn.addEventListener('click', function(e) {
                if (isSubmitting) {
                    e.preventDefault();
                    return;
                }
                isSubmitting = true;
                setTimeout(() => { isSubmitting = false; }, 3000);
            });
        }
    });
</script>
@endsection

