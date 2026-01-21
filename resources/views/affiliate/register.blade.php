@extends('layouts.checkout')

@section('title', 'Affiliate Registration')

@section('content')
    <div class="min-h-screen py-12 bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full space-y-8">
            
            <!-- Page Header -->
            <div class="text-center mb-10 animate-fade-in-up">
                <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    Join Our Affiliate Program
                </h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2 text-lg">
                    Earn free months by referring new customers
                </p>
            </div>

            <!-- Main Grid Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8  ">
                
                <!-- LEFT COLUMN: Registration (7 Columns) -->
                    <!-- Registration Card -->
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-xl overflow-hidden">
                        <div class="p-6 sm:p-8">
                            <div class="flex items-center gap-3 mb-6 border-b border-gray-100 dark:border-gray-700 pb-4">
                                <div class="bg-indigo-100 dark:bg-indigo-900/50 p-2 rounded-lg">
                                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                    </svg>
                                </div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">New Affiliate Registration</h2>
                            </div>

                            @if ($errors->any())
                                <div class="mb-6 p-4 border border-red-200 bg-red-50 dark:bg-red-900/20 dark:border-red-800 rounded-xl text-red-700 dark:text-red-300">
                                    <div class="flex items-center gap-2 mb-2 font-semibold">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Please fix the following errors:
                                    </div>
                                    <ul class="list-disc pl-5 space-y-1 text-sm">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if (session('success'))
                                <div class="mb-6 p-6 border border-green-200 bg-green-50 dark:bg-green-900/20 dark:border-green-800 rounded-xl text-center">
                                    <div class="w-16 h-16 bg-green-100 dark:bg-green-800 text-green-600 dark:text-green-200 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-green-800 dark:text-green-300 mb-2">Registration Successful!</h3>
                                    <p class="text-green-700 dark:text-green-400 mb-4">Your referral code is ready.</p>
                                    
                                    <div class="bg-white dark:bg-gray-900 border border-green-200 dark:border-green-800 rounded-lg p-4 mb-4">
                                        <span class="block text-xs text-gray-500 uppercase tracking-wider mb-1">Your Code</span>
                                        <strong class="text-3xl font-mono text-gray-800 dark:text-gray-100 select-all">{{ session('referral_code') }}</strong>
                                    </div>

                                    <a href="{{ route('affiliate.dashboard', ['email' => session('affiliate_email')]) }}"
                                        class="inline-flex items-center justify-center w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-8 rounded-lg transition-colors shadow-lg shadow-green-600/30">
                                        Go to Dashboard
                                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                                    </a>
                                </div>
                            @else
                                <form method="POST" action="{{ route('affiliate.store') }}" class="space-y-6" id="affiliateForm">
                                    @csrf

                                    <!-- Step 1: Email -->
                                    <div class="relative">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Step 1: Verify Subscription Email
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                            </div>
                                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow sm:text-sm"
                                                placeholder="Enter the email associated with your subscription">
                                        </div>
                                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                            We'll automatically fetch your active subscriptions.
                                        </p>
                                    </div>

                                    <!-- Step 2: Subscription Selector -->
                                    <div class="animate-fade-in">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Step 2: Select Subscription to Link
                                        </label>
                                        <div id="subscriptions-container" class="space-y-3 min-h-[100px]">
                                            <div class="p-6 bg-gray-50 dark:bg-gray-700/50 border border-dashed border-gray-300 dark:border-gray-600 rounded-xl text-center">
                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                    Enter your email address above to see your available subscriptions.
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <!-- Hidden Inputs -->
                                        <input type="hidden" name="selected_order_id" id="selected_order_id" value="{{ old('selected_order_id') }}" required>
                                        <input type="hidden" name="selected_device_id" id="selected_device_id" value="{{ old('selected_device_id') }}">
                                    </div>

                                    <!-- Step 3: Device Selection (Conditional) -->
                                    <div id="device-selection-container" class="hidden animate-fade-in">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Step 3: Select Target Device
                                        </label>
                                        <div id="devices-container" class="space-y-2">
                                            <!-- Populated by JS -->
                                        </div>
                                        <p class="mt-2 text-xs text-yellow-600 dark:text-yellow-400 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                            Rewards will be applied specifically to this device.
                                        </p>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="pt-4">
                                        <button type="submit"
                                            class="w-full group relative flex justify-center py-3 px-4 border border-transparent text-sm font-semibold rounded-lg text-white bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-lg shadow-indigo-500/30 transition-all duration-300 transform hover:-translate-y-0.5">
                                            
                                                <svg class="h-5 w-5 mr-4 text-indigo-300 group-hover:text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                                            
                                            Create Affiliate Account
                                        </button>
                                        
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                

                <!-- RIGHT COLUMN: Login & Info (5 Columns) -->
                    <!-- Login Section (Top Priority) -->
                    <div class=" flex flex-col justify-center   dark:bg-gray-800 border border-blue-100 dark:border-gray-700 rounded-2xl shadow-lg p-6 sm:p-8">
                        <div class="text-center mb-6">
                            <h3 class="text-xl font-bold text-blue-900 dark:text-white">
                                Already an Affiliate?
                            </h3>
                            <p class="text-sm text-blue-700 dark:text-gray-300 mt-1">
                                Access your dashboard to track earnings
                            </p>
                        </div>
                        
                        <div class="space-y-3">
                            <div>
                                <label for="login-email" class="sr-only">Email address</label>
                                <input type="email" id="login-email" placeholder="Enter your registered email"
                                    class="w-full rounded-lg border-blue-200 dark:border-blue-600 bg-white dark:bg-gray-800 px-4 py-3 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            </div>
                            <button type="button" id="login-btn"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition-colors flex items-center justify-center gap-2">
                                <span>Login to Dashboard</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                            </button>
                        </div>
                    </div>
            </div>

            <!-- How It Works & Full Documentation -->
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-md overflow-hidden">
                <div class="p-6 sm:p-8 space-y-8">
                    
                    <!-- Header -->
                    <div class="flex items-center gap-3">
                        <div class="bg-indigo-100 dark:bg-indigo-900/50 p-2 rounded-full">
                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Program Guide</h3>
                    </div>

                    <!-- 1. Who Can Join -->
                    <div class="prose dark:prose-invert text-sm text-gray-600 dark:text-gray-300">
                        <h4 class="text-base font-semibold text-gray-900 dark:text-white mb-2">Who Can Join?</h4>
                        <ul class="list-disc pl-4 space-y-1">
                            <li>Existing clients with an <strong>active subscription</strong>.</li>
                            <li>Clients with one or multiple devices/accounts.</li>
                            <li>No technical knowledge required.</li>
                        </ul>
                    </div>

                    <!-- 2. Registration Process -->
                    <div class="bg-blue-50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-900/30 rounded-lg p-4">
                        <h4 class="text-sm font-bold text-blue-900 dark:text-blue-900 mb-2">Registration Process</h4>
                        <ol class="list-decimal pl-4 space-y-1 text-sm text-blue-800 dark:text-blue-800">
                            <li>Verify your email to load subscriptions.</li>
                            <li>Choose <strong>ONE</strong> account to attach to the program.</li>
                            <li>Referral rewards will be applied to this specific account.</li>
                        </ol>
                        <div class="mt-3 flex items-start gap-2 text-xs text-yellow-700 dark:text-yellow-900 bg-yellow-50 dark:bg-yellow-900/20 p-2 rounded">
                            <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <p><strong>Warning:</strong> Even if you own multiple devices, only the selected account receives rewards.</p>
                        </div>
                    </div>

                    <!-- 3. How Referrals Work -->
                    <div class="space-y-3">
                        <h4 class="text-xl text-center font-semibold text-gray-900 dark:text-white">How Referrals Work</h4>
                        <div class="flex items-center justify-between text-xs text-center text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center">
                                <div class="w-10 h-10 bg-indigo-50 dark:bg-indigo-900/30 rounded-full flex items-center justify-center mb-1 text-indigo-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                                </div>
                                <span>Share Referral Code</span>
                            </div>
                            <div class="h-px w-8 bg-gray-300 dark:bg-gray-600"></div>
                            <div class="flex flex-col items-center">
                                <div class="w-10 h-10 bg-indigo-50 dark:bg-indigo-900/30 rounded-full flex items-center justify-center mb-1 text-indigo-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                </div>
                                <span>Friend Buys</span>
                            </div>
                            <div class="h-px w-8 bg-gray-300 dark:bg-gray-600"></div>
                            <div class="flex flex-col items-center">
                                <div class="w-10 h-10 bg-indigo-50 dark:bg-indigo-900/30 rounded-full flex items-center justify-center mb-1 text-indigo-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <span>Wait Review</span>
                            </div>
                            <div class="h-px w-8 bg-gray-300 dark:bg-gray-600"></div>
                            <div class="flex flex-col items-center">
                                <div class="w-10 h-10 bg-green-50 dark:bg-green-900/30 rounded-full flex items-center justify-center mb-1 text-green-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <span>Get Month</span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <!-- 4. Rewards Breakdown -->
                    <div class="grid grid-cols-1 gap-4">
                        <div class="border border-gray-100 dark:border-gray-700 rounded-lg p-4">
                            <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-2">Rewards Explained</h4>
                            <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-300">
                                <li class="flex items-center text-green-600 dark:text-green-900">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    +1 month per valid referral
                                </li>
                                
                                <li class="flex items-center text-red-500 dark:text-red-400">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    Self-referrals disallowed
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- 6. Dashboard & Footer -->
                    <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-4 text-sm text-gray-600 dark:text-gray-300">
                        <h4 class="font-bold text-gray-900 dark:text-white mb-2">Affiliate Dashboard</h4>
                        <p class="mb-2">Once registered, you'll access a dashboard to track total referrals and rewards.</p>
                        <div class="border-t border-gray-200 dark:border-gray-600 mt-3 pt-3">
                            <p class="text-xs italic">
                                <strong>Need Help?</strong> Contact our support team if you have questions about rewards or account selection.
                            </p>
                        </div>
                    </div>
                    </div>

                </div>
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

                if(loginBtn && loginEmailInput) {
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
                }

                if(emailInput) {
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
                }

                function fetchSubscriptions(email) {
                    subscriptionsContainer.innerHTML =
                        '<div class="p-6 text-center text-gray-500 dark:text-gray-400 flex flex-col items-center"><svg class="animate-spin h-8 w-8 text-indigo-600 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Searching for subscriptions...</div>';

                    // Use the main affiliate fetch subscriptions route
                    const fetchUrl = '{{ route('affiliate.fetch-subscriptions') }}';

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
                                        'border-green-500 bg-green-50 dark:bg-green-900/20 ring-1 ring-green-500/30' :
                                        isExpired ? 'border-gray-300 bg-gray-50 dark:bg-gray-800' :
                                        'border-yellow-500 bg-yellow-50 dark:bg-yellow-900/20';

                                    html += `
                                <label class="subscription-option block cursor-pointer group">
                                    <input type="radio" name="subscription_radio" value="${sub.id}" 
                                           class="sr-only subscription-radio" 
                                           ${!isActive && !isExpired ? 'disabled' : ''}
                                           ${oldSelectedOrderId == sub.id ? 'checked' : ''}>
                                    <div class="p-4 border rounded-xl transition-all duration-200 hover:shadow-md ${statusClass}">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2">
                                                    <p class="font-bold text-gray-900 dark:text-white text-lg">${sub.plan_name}</p>
                                                    ${isActive ? '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Active</span>' : ''}
                                                </div>
                                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                                    Order #${sub.order_number}
                                                </p>
                                                ${sub.expires_at ? `<p class="text-xs text-gray-500 dark:text-gray-500 mt-1 ml-5">Expires: ${sub.expires_at}</p>` : ''}
                                            </div>
                                            <div class="text-right">
                                                ${!isActive && !isExpired ? '<span class="inline-block px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 rounded">Pending</span>' : ''}
                                                ${isExpired ? '<span class="inline-block px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 rounded">Expired</span>' : ''}
                                                <div class="mt-2 radio-indicator w-5 h-5 rounded-full border-2 border-gray-300 dark:border-gray-500 ml-auto group-hover:border-indigo-500 flex items-center justify-center">
                                                    <div class="w-2.5 h-2.5 rounded-full bg-indigo-600 hidden check-dot"></div>
                                                </div>
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
                                            
                                            // Reset visual state
                                            document.querySelectorAll('.subscription-option').forEach(opt => {
                                                const div = opt.querySelector('div');
                                                div.classList.remove('ring-2', 'ring-indigo-500', 'border-indigo-500');
                                                div.querySelector('.check-dot').classList.add('hidden');
                                            });
                                            
                                            // Set active state
                                            const activeDiv = this.closest('.subscription-option').querySelector('div');
                                            activeDiv.classList.add('ring-2', 'ring-indigo-500', 'border-indigo-500');
                                            activeDiv.querySelector('.check-dot').classList.remove('hidden');

                                            // Handle device selection
                                            const selectedSub = data.subscriptions.find(sub => sub.id == this.value);
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
                            <div class="p-6 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-xl text-center">
                                <svg class="w-10 h-10 text-yellow-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                <p class="text-sm text-yellow-800 dark:text-yellow-300 font-medium">
                                    No active subscriptions found.
                                </p>
                                <p class="text-xs text-yellow-700 dark:text-yellow-400 mt-1">
                                    You must have an active subscription to join the affiliate program.
                                </p>
                            </div>
                        `;
                                selectedOrderInput.value = '';
                            }
                        })
                        .catch(error => {
                            console.error('Fetch Error:', error);
                            subscriptionsContainer.innerHTML = `
                        <div class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-xl">
                            <p class="text-sm text-red-800 dark:text-red-300">
                                Error loading subscriptions. Please check your connection and try again.
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
                            const deviceId = device.id || index; // Use device.id if available, otherwise use index
                            const deviceName = device.name || device.username || `Device ${index + 1}`;
                            const isSelected = oldSelectedDeviceId == deviceId;

                            deviceHtml += `
                                <label class="device-option block cursor-pointer group">
                                    <input type="radio" name="device_radio" value="${deviceId}" 
                                           class="sr-only device-radio" ${isSelected ? 'checked' : ''}>
                                    <div class="p-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-lg transition-all duration-200 hover:border-indigo-400 flex items-center">
                                        <div class="flex-1">
                                            <p class="font-medium text-gray-900 dark:text-white">${deviceName}</p>
                                            ${device.username ? `<p class="text-xs text-gray-500 dark:text-gray-400">User: ${device.username}</p>` : ''}
                                        </div>
                                        <div class="w-5 h-5 rounded-full border border-gray-400 flex items-center justify-center">
                                            <div class="w-2.5 h-2.5 rounded-full bg-indigo-600 hidden device-dot"></div>
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
                                    
                                    // Reset UI
                                    document.querySelectorAll('.device-option').forEach(opt => {
                                        opt.querySelector('div').classList.remove('ring-2', 'ring-indigo-500', 'border-indigo-500');
                                        opt.querySelector('.device-dot').classList.add('hidden');
                                        opt.querySelector('div').classList.add('border-gray-300', 'dark:border-gray-600');
                                    });
                                    
                                    // Set Active UI
                                    const activeDiv = this.closest('.device-option').querySelector('div');
                                    activeDiv.classList.remove('border-gray-300', 'dark:border-gray-600');
                                    activeDiv.classList.add('ring-2', 'ring-indigo-500', 'border-indigo-500');
                                    activeDiv.querySelector('.device-dot').classList.remove('hidden');
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