@extends('layouts.checkout')

@section('title', 'Affiliate Registration')

@section('content')
    <div class="min-h-screen flex items-center py-12 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <!-- Header -->
            <div class="text-center mb-10 animate-fade-in-up">
                <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    Join Our Affiliate Program
                </h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2 text-lg">
                    Earn free months by referring new customers
                </p>
            </div>

            <!-- Login Section for Existing Affiliates -->
            <div class="mb-8 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-xl p-6">
                <div class="text-center">
                    <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-2">
                        Already an Affiliate?
                    </h3>
                    <p class="text-blue-800 dark:text-blue-200 mb-4">
                        Access your dashboard to view referrals and track rewards
                    </p>
                    <div class="flex flex-col sm:flex-row gap-3 justify-center items-center">
                        <input type="email" id="login-email" placeholder="Enter your email address"
                            class="w-full sm:w-64 rounded-lg border-blue-300 dark:border-blue-600 bg-white dark:bg-gray-700 px-4 py-2 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <button type="button" id="login-btn"
                            class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                            Go to Dashboard
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-8">
                @if ($errors->any())
                    <div
                        class="mb-6 p-4 border border-red-200 bg-red-50 dark:bg-red-900/20 dark:border-red-800 rounded-xl text-red-700 dark:text-red-300">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div
                        class="mb-6 p-4 border border-green-200 bg-green-50 dark:bg-green-900/20 dark:border-green-800 rounded-xl">
                        <div class="text-green-800 dark:text-green-300 mb-4">
                            <p class="font-semibold text-lg mb-2">Registration Successful!</p>
                            <p class="mb-3">Your referral code: <strong
                                    class="text-xl">{{ session('referral_code') }}</strong></p>
                            <p class="mt-4 text-sm">Use your referral code to earn rewards when friends sign up!</p>
                            <p class="mt-3 text-sm">Save your referral code and email address to access your affiliate
                                information later.</p>
                        </div>
                        <a href="{{ route('affiliate.dashboard', ['email' => session('affiliate_email')]) }}"
                            class="inline-block mt-4 bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-colors">
                            Go to Dashboard
                        </a>
                    </div>
                @else
                    <form method="POST" action="{{ route('affiliate.store') }}" class="space-y-6" id="affiliateForm">
                        @csrf

                        <!-- Email Input -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Email Address *
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="your@email.com">
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Enter the email address associated with your subscription
                            </p>
                        </div>

                        <!-- Subscription Selector -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Select Subscription *
                            </label>
                            <div id="subscriptions-container" class="space-y-3">
                                <div
                                    class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-xl">
                                    <p class="text-sm text-blue-800 dark:text-blue-300">
                                        <strong>Note:</strong> Enter your email address above to see your active
                                        subscriptions.
                                    </p>
                                </div>
                            </div>
                            <input type="hidden" name="selected_order_id" id="selected_order_id"
                                value="{{ old('selected_order_id') }}" required>
                            <input type="hidden" name="selected_device_id" id="selected_device_id"
                                value="{{ old('selected_device_id') }}">
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Select the subscription that will receive rewards
                            </p>
                        </div>

                        <!-- Device Selection (shown when subscription has multiple devices) -->
                        <div id="device-selection-container" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Select Device *
                            </label>
                            <div id="devices-container" class="space-y-2">
                                <!-- Device options will be populated by JavaScript -->
                            </div>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Select which device will receive the referral rewards
                            </p>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                            class="w-full bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-300 shadow-md hover:shadow-lg">
                            Register as Affiliate
                        </button>
                    </form>
                @endif
            </div>

            <!-- Info Section -->
            <div
                class="mt-8 bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-700 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-indigo-900 dark:text-indigo-100 mb-3">How It Works</h3>
                <ul class="space-y-2 text-sm text-indigo-800 dark:text-indigo-200">
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-2 mt-0.5 text-indigo-600 dark:text-indigo-400" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>Only customers with active subscriptions can join</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-2 mt-0.5 text-indigo-600 dark:text-indigo-400" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>Earn 1 free month per valid referral</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-2 mt-0.5 text-indigo-600 dark:text-indigo-400" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>Rewards are granted manually after admin approval</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const emailInput = document.getElementById('email');
                const subscriptionsContainer = document.getElementById('subscriptions-container');
                const selectedOrderInput = document.getElementById('selected_order_id');
                const selectedDeviceInput = document.getElementById('selected_device_id');
                const deviceSelectionContainer = document.getElementById('device-selection-container');
                const devicesContainer = document.getElementById('devices-container');
                const oldSelectedOrderId = '{{ old('selected_order_id') }}'; // Get old value from PHP
                const oldSelectedDeviceId = '{{ old('selected_device_id') }}'; // Get old device value from PHP
                let debounceTimer;

                // Login functionality for existing affiliates
                const loginEmailInput = document.getElementById('login-email');
                const loginBtn = document.getElementById('login-btn');

                loginBtn.addEventListener('click', function() {
                    const email = loginEmailInput.value.trim();
                    if (!email || !email.includes('@')) {
                        alert('Please enter a valid email address');
                        return;
                    }

                    // Use the main affiliate dashboard route
                    const dashboardRoute = '{{ route('affiliate.dashboard', ['email' => 'EMAIL_PLACEHOLDER']) }}'.replace('EMAIL_PLACEHOLDER', encodeURIComponent(email));
                    window.location.href = dashboardRoute;
                });

                // Allow Enter key to trigger login
                loginEmailInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        loginBtn.click();
                    }
                });

                emailInput.addEventListener('blur', function() {
                    const email = this.value.trim();
                    if (!email || !email.includes('@')) {
                        return;
                    }

                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => {
                        fetchSubscriptions(email);
                    }, 500);
                });

                function fetchSubscriptions(email) {
                    subscriptionsContainer.innerHTML =
                        '<div class="p-4 text-center text-gray-500">Loading subscriptions...</div>';

                    // Use the main affiliate fetch subscriptions route
                    const fetchUrl = '{{ route('affiliate.fetch-subscriptions') }}';

                    console.log('Fetching subscriptions from:', fetchUrl);

                    fetch(fetchUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content'),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                email: email
                            }),
                            credentials: 'include'
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data.subscriptions.length > 0) {
                                let html = '<div class="space-y-3">';
                                data.subscriptions.forEach(sub => {
                                    const isActive = sub.is_active;
                                    const isExpired = sub.is_expired;
                                    const statusClass = isActive ?
                                        'border-green-500 bg-green-50 dark:bg-green-900/20' :
                                        isExpired ? 'border-gray-300 bg-gray-50 dark:bg-gray-700' :
                                        'border-yellow-500 bg-yellow-50 dark:bg-yellow-900/20';

                                    html += `
                                <label class="subscription-option block cursor-pointer">
                                    <input type="radio" name="subscription_radio" value="${sub.id}" 
                                           class="sr-only subscription-radio" 
                                           ${!isActive && !isExpired ? 'disabled' : ''}
                                           ${oldSelectedOrderId == sub.id ? 'checked' : ''}>
                                    <div class="p-4 border-2 ${statusClass} rounded-xl transition-all duration-200">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="font-semibold text-gray-900 dark:text-white">${sub.plan_name}</p>
                                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                                    Order: ${sub.order_number}
                                                </p>
                                                ${sub.expires_at ? `<p class="text-xs text-gray-500 dark:text-gray-500 mt-1">Expires: ${sub.expires_at}</p>` : ''}
                                            </div>
                                            <div class="text-right">
                                                ${isActive ? '<span class="inline-block px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 rounded">Active</span>' : ''}
                                                ${isExpired ? '<span class="inline-block px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 rounded">Expired</span>' : ''}
                                                ${!isActive && !isExpired ? '<span class="inline-block px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 rounded">Pending</span>' : ''}
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            `;
                                });
                                html += '</div>';
                                subscriptionsContainer.innerHTML = html;

                                // Add event listeners to radio buttons
                                document.querySelectorAll('.subscription-radio').forEach(radio => {
                                    radio.addEventListener('change', function() {
                                        if (this.checked) {
                                            selectedOrderInput.value = this.value;
                                            document.querySelectorAll('.subscription-option')
                                                .forEach(opt => {
                                                    opt.querySelector('div').classList.remove(
                                                        'ring-2', 'ring-indigo-500');
                                                });
                                            this.closest('.subscription-option').querySelector(
                                                'div').classList.add('ring-2',
                                                'ring-indigo-500');

                                            // Handle device selection
                                            const selectedSub = data.subscriptions.find(sub => sub
                                                .id == this.value);
                                            showDeviceSelection(selectedSub);
                                        }
                                    });
                                });

                                // Set initial selection if old value exists
                                const oldValue = selectedOrderInput.value;
                                if (oldValue) {
                                    const radio = document.querySelector(`input[value="${oldValue}"]`);
                                    if (radio) {
                                        radio.checked = true;
                                        radio.dispatchEvent(new Event('change'));
                                    }
                                }
                            } else {
                                subscriptionsContainer.innerHTML = `
                            <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-xl">
                                <p class="text-sm text-yellow-800 dark:text-yellow-300">
                                    No active subscriptions found for this email address. You must have an active subscription to join the affiliate program.
                                </p>
                            </div>
                        `;
                                selectedOrderInput.value = '';
                            }
                        })
                        .catch(error => {
                            console.error('Fetch Error Details:', error);
                            console.error('Error message:', error.message);
                            console.error('Error stack:', error.stack);
                            subscriptionsContainer.innerHTML = `
                        <div class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-xl">
                            <p class="text-sm text-red-800 dark:text-red-300">
                                Error loading subscriptions: ${error.message}. Please try again.
                            </p>
                        </div>
                    `;
                        });
                }

                function showDeviceSelection(subscription) {
                    if (subscription && subscription.devices && subscription.devices.length > 1) {
                        // Show device selection for subscriptions with multiple devices
                        let deviceHtml = '';
                        subscription.devices.forEach((device, index) => {
                            const deviceId = device.id ||
                                index; // Use device.id if available, otherwise use index
                            const deviceName = device.name || device.username || `Device ${index + 1}`;
                            const isSelected = oldSelectedDeviceId == deviceId;

                            deviceHtml += `
                                <label class="device-option block cursor-pointer">
                                    <input type="radio" name="device_radio" value="${deviceId}" 
                                           class="sr-only device-radio" ${isSelected ? 'checked' : ''}>
                                    <div class="p-3 border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-lg transition-all duration-200 hover:border-indigo-300">
                                        <div class="flex items-center">
                                            <div class="flex-1">
                                                <p class="font-medium text-gray-900 dark:text-white">${deviceName}</p>
                                                ${device.username ? `<p class="text-sm text-gray-600 dark:text-gray-400">Username: ${device.username}</p>` : ''}
                                            </div>
                                            <div class="ml-3">
                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="9 12l2 2 4-4"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            `;
                        });

                        devicesContainer.innerHTML = deviceHtml;
                        deviceSelectionContainer.classList.remove('hidden');

                        // Add event listeners to device radio buttons
                        document.querySelectorAll('.device-radio').forEach(radio => {
                            radio.addEventListener('change', function() {
                                if (this.checked) {
                                    selectedDeviceInput.value = this.value;
                                    document.querySelectorAll('.device-option').forEach(opt => {
                                        opt.querySelector('div').classList.remove('ring-2',
                                            'ring-indigo-500', 'border-indigo-500');
                                        opt.querySelector('div').classList.add(
                                            'border-gray-300', 'dark:border-gray-600');
                                    });
                                    this.closest('.device-option').querySelector('div').classList
                                        .remove('border-gray-300', 'dark:border-gray-600');
                                    this.closest('.device-option').querySelector('div').classList.add(
                                        'ring-2', 'ring-indigo-500', 'border-indigo-500');
                                }
                            });
                        });

                        // Set initial device selection if old value exists
                        if (oldSelectedDeviceId) {
                            const deviceRadio = document.querySelector(
                                `input[name="device_radio"][value="${oldSelectedDeviceId}"]`);
                            if (deviceRadio) {
                                deviceRadio.checked = true;
                                deviceRadio.dispatchEvent(new Event('change'));
                            }
                        }
                    } else {
                        // Hide device selection for single device subscriptions
                        deviceSelectionContainer.classList.add('hidden');
                        selectedDeviceInput.value = subscription && subscription.devices && subscription.devices
                            .length === 1 ?
                            (subscription.devices[0].id || 0) : '';
                    }
                }
            });
        </script>
    @endpush
@endsection
