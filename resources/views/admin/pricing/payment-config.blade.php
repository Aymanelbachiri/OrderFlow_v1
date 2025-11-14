@extends('layouts.admin')

@section('title', 'Payment Configuration')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header Section -->
    <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Payment Configuration</h1>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Configure payment gateways and methods for your customers</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <form method="POST" action="{{ route('admin.payment.update-config') }}" id="payment-config-form">
            @csrf
            
            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-8 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-green-800 dark:text-green-200">Configuration Updated!</h3>
                            <div class="mt-2 text-sm text-green-700 dark:text-green-300">
                                {{ session('success') }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Error Display -->
            @if ($errors->any())
                <div class="mb-8 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800 dark:text-red-200">Please fix the following errors:</h3>
                            <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Configuration Panel (Left - 2/3 width) -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- Primary Payment Method Selection -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Primary Payment Method</h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Choose the default payment method for your customers</p>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach([
                                    'stripe' => ['name' => 'Stripe', 'description' => 'Credit & Debit Cards', 'icon' => 'credit-card', 'color' => 'blue'],
                                    'paypal' => ['name' => 'PayPal', 'description' => 'PayPal & Guest Checkout', 'icon' => 'globe', 'color' => 'indigo'],
                                    'crypto' => ['name' => 'USDT(TRC20)', 'description' => 'USDT TRC20', 'icon' => 'currency-bitcoin', 'color' => 'amber'],
                                    'coinbase_commerce' => ['name' => 'Crypto', 'description' => 'Pay with Crypto', 'icon' => 'currency-bitcoin', 'color' => 'purple'],
                                    'multiple' => ['name' => 'Multiple Methods', 'description' => 'All Available Options', 'icon' => 'collection', 'color' => 'green']
                                ] as $value => $config)
                                    <label class="relative cursor-pointer group">
                                        <input type="radio" name="primary_payment_method" value="{{ $value }}" 
                                               {{ old('primary_payment_method', $settings['primary_payment_method']) === $value ? 'checked' : '' }}
                                               class="sr-only peer">
                                        <div class="relative p-4 border-2 border-gray-200 dark:border-gray-600 rounded-xl transition-all duration-200 peer-checked:border-{{ $config['color'] }}-500 peer-checked:bg-{{ $config['color'] }}-50 dark:peer-checked:bg-{{ $config['color'] }}-900/20 group-hover:border-gray-300 dark:group-hover:border-gray-500">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-10 h-10 bg-gradient-to-r from-{{ $config['color'] }}-500 to-{{ $config['color'] }}-600 rounded-lg flex items-center justify-center">
                                                    @if($config['icon'] === 'credit-card')
                                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                                        </svg>
                                                    @elseif($config['icon'] === 'globe')
                                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9V3"></path>
                                                        </svg>
                                                    @elseif($config['icon'] === 'currency-bitcoin')
                                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                                        </svg>
                                                    @else
                                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14-7v9a2 2 0 01-2 2H7a2 2 0 01-2-2V4a2 2 0 012-2h10a2 2 0 012 2zM7 11v4a1 1 0 001 1h8a1 1 0 001-1v-4"></path>
                                                        </svg>
                                                    @endif
                                                </div>
                                                <div class="flex-1">
                                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $config['name'] }}</h3>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $config['description'] }}</p>
                                                </div>
                                                <div class="w-5 h-5 border-2 border-gray-300 dark:border-gray-600 rounded-full peer-checked:border-{{ $config['color'] }}-500 peer-checked:bg-{{ $config['color'] }}-500 flex items-center justify-center">
                                                    <div class="w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100 transition-opacity duration-200"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Payment Gateway Configurations -->
                    <div class="space-y-6">
                        <!-- Stripe Configuration -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Stripe</h3>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Credit and debit card payments</p>
                                        </div>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="stripe_enabled" value="1" 
                                               {{ old('stripe_enabled', $settings['stripe_enabled']) ? 'checked' : '' }}
                                               class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 dark:bg-gray-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>
                            </div>
                            <div class="p-6 space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="stripe_public_key" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Publishable Key</label>
                                        <div class="relative">
                                            <input type="text" id="stripe_public_key" name="stripe_public_key" 
                                                   value="{{ old('stripe_public_key', $settings['stripe_public_key']) }}"
                                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-colors duration-200 {{ $errors->has('stripe_public_key') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : '' }}"
                                                   placeholder="pk_live_...">
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        @error('stripe_public_key')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="stripe_secret_key" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Secret Key</label>
                                        <div class="relative">
                                            <input type="password" id="stripe_secret_key" name="stripe_secret_key" 
                                                   value="{{ old('stripe_secret_key', $settings['stripe_secret_key']) }}"
                                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-colors duration-200 {{ $errors->has('stripe_secret_key') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : '' }}"
                                                   placeholder="sk_live_...">
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                                <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 8a6 6 0 01-7.743 5.743L10 14l-1 1-1 1H6v1H2v-4l4.257-4.257A6 6 0 1118 8zm-6-4a1 1 0 100 2 2 2 0 012 2 1 1 0 102 0 4 4 0 00-4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        @error('stripe_secret_key')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                        <div class="ml-3">
                                            <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200">Stripe Setup</h4>
                                            <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">Get your API keys from the <a href="https://dashboard.stripe.com/apikeys" target="_blank" class="underline hover:no-underline">Stripe Dashboard</a>. Use test keys for development and live keys for production.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- PayPal Configuration -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9V3"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">PayPal</h3>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">PayPal and guest checkout</p>
                                        </div>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="paypal_enabled" value="1" 
                                               {{ old('paypal_enabled', $settings['paypal_enabled']) ? 'checked' : '' }}
                                               class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 dark:bg-gray-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                                    </label>
                                </div>
                            </div>
                            <div class="p-6 space-y-6">
                                <!-- Environment Switcher -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Environment Mode</label>
                                    <div class="flex items-center space-x-4">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="radio" name="paypal_mode" value="sandbox" 
                                                   {{ old('paypal_mode', $settings['paypal_mode']) === 'sandbox' ? 'checked' : '' }}
                                                   class="sr-only peer">
                                            <div class="relative p-3 border-2 border-gray-200 dark:border-gray-600 rounded-lg transition-all duration-200 peer-checked:border-orange-500 peer-checked:bg-orange-50 dark:peer-checked:bg-orange-900/20 hover:border-gray-300 dark:hover:border-gray-500">
                                                <div class="flex items-center space-x-2">
                                                    <div class="w-6 h-6 bg-gradient-to-r from-orange-500 to-orange-600 rounded-md flex items-center justify-center">
                                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <span class="text-sm font-medium text-gray-900 dark:text-white">Sandbox</span>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">Testing Environment</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="radio" name="paypal_mode" value="live" 
                                                   {{ old('paypal_mode', $settings['paypal_mode']) === 'live' ? 'checked' : '' }}
                                                   class="sr-only peer">
                                            <div class="relative p-3 border-2 border-gray-200 dark:border-gray-600 rounded-lg transition-all duration-200 peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-900/20 hover:border-gray-300 dark:hover:border-gray-500">
                                                <div class="flex items-center space-x-2">
                                                    <div class="w-6 h-6 bg-gradient-to-r from-green-500 to-green-600 rounded-md flex items-center justify-center">
                                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <span class="text-sm font-medium text-gray-900 dark:text-white">Live</span>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">Production Environment</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    @error('paypal_mode')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Sandbox Credentials -->
                                <div class="border border-orange-200 dark:border-orange-800 rounded-lg p-4 bg-orange-50 dark:bg-orange-900/10">
                                    <div class="flex items-center space-x-2 mb-4">
                                        <div class="w-5 h-5 bg-orange-500 rounded-md flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                            </svg>
                                        </div>
                                        <h4 class="text-sm font-semibold text-orange-800 dark:text-orange-200">Sandbox Credentials</h4>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="paypal_sandbox_client_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sandbox Client ID</label>
                                            <input type="text" id="paypal_sandbox_client_id" name="paypal_sandbox_client_id"
                                                   value="{{ old('paypal_sandbox_client_id', $settings['paypal_sandbox_client_id']) }}"
                                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-700 dark:text-white transition-colors duration-200 {{ $errors->has('paypal_sandbox_client_id') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : '' }}"
                                                   placeholder="Sandbox Client ID">
                                            @error('paypal_sandbox_client_id')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="paypal_sandbox_client_secret" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sandbox Client Secret</label>
                                            <input type="password" id="paypal_sandbox_client_secret" name="paypal_sandbox_client_secret"
                                                   value="{{ old('paypal_sandbox_client_secret', $settings['paypal_sandbox_client_secret']) }}"
                                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-700 dark:text-white transition-colors duration-200 {{ $errors->has('paypal_sandbox_client_secret') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : '' }}"
                                                   placeholder="Sandbox Client Secret">
                                            @error('paypal_sandbox_client_secret')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Live Credentials -->
                                <div class="border border-green-200 dark:border-green-800 rounded-lg p-4 bg-green-50 dark:bg-green-900/10">
                                    <div class="flex items-center space-x-2 mb-4">
                                        <div class="w-5 h-5 bg-green-500 rounded-md flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <h4 class="text-sm font-semibold text-green-800 dark:text-green-200">Live Credentials</h4>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="paypal_live_client_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Live Client ID</label>
                                            <input type="text" id="paypal_live_client_id" name="paypal_live_client_id"
                                                   value="{{ old('paypal_live_client_id', $settings['paypal_live_client_id']) }}"
                                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white transition-colors duration-200 {{ $errors->has('paypal_live_client_id') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : '' }}"
                                                   placeholder="Live Client ID">
                                            @error('paypal_live_client_id')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="paypal_live_client_secret" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Live Client Secret</label>
                                            <input type="password" id="paypal_live_client_secret" name="paypal_live_client_secret"
                                                   value="{{ old('paypal_live_client_secret', $settings['paypal_live_client_secret']) }}"
                                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white transition-colors duration-200 {{ $errors->has('paypal_live_client_secret') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : '' }}"
                                                   placeholder="Live Client Secret">
                                            @error('paypal_live_client_secret')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Help Information -->
                                <div class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800 rounded-lg p-4">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-indigo-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                        <div class="ml-3">
                                            <h4 class="text-sm font-medium text-indigo-800 dark:text-indigo-200">PayPal Setup</h4>
                                            <p class="text-sm text-indigo-700 dark:text-indigo-300 mt-1">Create separate apps for sandbox and live environments in the <a href="https://developer.paypal.com/developer/applications/" target="_blank" class="underline hover:no-underline">PayPal Developer Dashboard</a>. Both sets of credentials are required for seamless environment switching.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- USDT(TRC20) Configuration -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-gradient-to-r from-amber-500 to-amber-600 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">USDT(TRC20)</h3>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">USDT TRC20 payments</p>
                                        </div>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="crypto_enabled" value="1" 
                                               {{ old('crypto_enabled', $settings['crypto_enabled']) ? 'checked' : '' }}
                                               class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 dark:bg-gray-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-amber-300 dark:peer-focus:ring-amber-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-amber-600"></div>
                                    </label>
                                </div>
                            </div>
                            <div class="p-6 space-y-4">
                                <div>
                                    <label for="crypto_wallet_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">USDT(TRC20) Wallet Address</label>
                                    <div class="relative">
                                        <input type="text" id="crypto_wallet_address" name="crypto_wallet_address" 
                                               value="{{ old('crypto_wallet_address', $settings['crypto_wallet_address']) }}"
                                               class="w-full px-4 py-3 pl-10 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 dark:bg-gray-700 dark:text-white transition-colors duration-200 font-mono text-sm {{ $errors->has('crypto_wallet_address') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : '' }}"
                                               placeholder="1A1zP1eP5QGefi2DMPTfTL5SLmv7DivfNa">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    @error('crypto_wallet_address')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-4">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-amber-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                        <div class="ml-3">
                                            <h4 class="text-sm font-medium text-amber-800 dark:text-amber-200">USDT(TRC20) Setup</h4>
                                            <p class="text-sm text-amber-700 dark:text-amber-300 mt-1">Enter your USDT(TRC20) wallet address where you want to receive payments. Make sure to use a secure wallet and keep your private keys safe.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Crypto Configuration -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Crypto</h3>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Accept cryptocurrency payments</p>
                                        </div>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="coinbase_commerce_enabled" value="1" 
                                               {{ old('coinbase_commerce_enabled', $settings['coinbase_commerce_enabled']) ? 'checked' : '' }}
                                               class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 dark:bg-gray-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 dark:peer-focus:ring-purple-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-purple-600"></div>
                                    </label>
                                </div>
                            </div>
                            <div class="p-6 space-y-4">
                                <div>
                                    <label for="coinbase_commerce_api_key" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">API Key</label>
                                    <div class="relative">
                                        <input type="text" id="coinbase_commerce_api_key" name="coinbase_commerce_api_key" 
                                               value="{{ old('coinbase_commerce_api_key', $settings['coinbase_commerce_api_key']) }}"
                                               class="w-full px-4 py-3 pl-10 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white transition-colors duration-200 font-mono text-sm {{ $errors->has('coinbase_commerce_api_key') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : '' }}"
                                               placeholder="Your Coinbase Commerce API Key">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    @error('coinbase_commerce_api_key')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="coinbase_commerce_webhook_secret" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Webhook Secret</label>
                                    <div class="relative">
                                        <input type="text" id="coinbase_commerce_webhook_secret" name="coinbase_commerce_webhook_secret" 
                                               value="{{ old('coinbase_commerce_webhook_secret', $settings['coinbase_commerce_webhook_secret']) }}"
                                               class="w-full px-4 py-3 pl-10 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white transition-colors duration-200 font-mono text-sm {{ $errors->has('coinbase_commerce_webhook_secret') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : '' }}"
                                               placeholder="Your Coinbase Commerce Webhook Secret">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    @error('coinbase_commerce_webhook_secret')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-4">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-purple-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                    <div class="ml-3 flex-1 min-w-0">
                                        <h4 class="text-sm font-medium text-purple-800 dark:text-purple-200">Crypto Setup</h4>
                                        <p class="text-sm text-purple-700 dark:text-purple-300 mt-1 break-words">Get your API key and webhook secret from your Coinbase Commerce dashboard. Webhook URL: <code class="bg-purple-100 dark:bg-purple-800 px-1 rounded break-all">{{ route('webhooks.coinbase-commerce') }}</code></p>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar (Right - 1/3 width) -->
                <div class="lg:col-span-1 space-y-6">
                    
                    <!-- Payment Status -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Payment Status</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="w-2 h-2 bg-{{ $settings['stripe_enabled'] ? 'green' : 'gray' }}-500 rounded-full"></div>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">Stripe</span>
                                </div>
                                <span class="text-xs font-semibold px-2 py-1 rounded-full {{ $settings['stripe_enabled'] ? 'bg-green-100 text-green-700 dark:bg-green-900/20 dark:text-green-300' : 'bg-gray-100 text-gray-600 dark:bg-gray-600 dark:text-gray-400' }}">
                                    {{ $settings['stripe_enabled'] ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="w-2 h-2 bg-{{ $settings['paypal_enabled'] ? 'green' : 'gray' }}-500 rounded-full"></div>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">PayPal</span>
                                </div>
                                <span class="text-xs font-semibold px-2 py-1 rounded-full {{ $settings['paypal_enabled'] ? 'bg-green-100 text-green-700 dark:bg-green-900/20 dark:text-green-300' : 'bg-gray-100 text-gray-600 dark:bg-gray-600 dark:text-gray-400' }}">
                                    {{ $settings['paypal_enabled'] ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="w-2 h-2 bg-{{ $settings['crypto_enabled'] ? 'green' : 'gray' }}-500 rounded-full"></div>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">USDT(TRC20)</span>
                                </div>
                                <span class="text-xs font-semibold px-2 py-1 rounded-full {{ $settings['crypto_enabled'] ? 'bg-green-100 text-green-700 dark:bg-green-900/20 dark:text-green-300' : 'bg-gray-100 text-gray-600 dark:bg-gray-600 dark:text-gray-400' }}">
                                    {{ $settings['crypto_enabled'] ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="w-2 h-2 bg-{{ $settings['coinbase_commerce_enabled'] ? 'green' : 'gray' }}-500 rounded-full"></div>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">Crypto</span>
                                </div>
                                <span class="text-xs font-semibold px-2 py-1 rounded-full {{ $settings['coinbase_commerce_enabled'] ? 'bg-green-100 text-green-700 dark:bg-green-900/20 dark:text-green-300' : 'bg-gray-100 text-gray-600 dark:bg-gray-600 dark:text-gray-400' }}">
                                    {{ $settings['coinbase_commerce_enabled'] ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Test Payment -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Test Payment</h3>
                        </div>
                        <div class="p-6">
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-4">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div class="ml-3">
                                        <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200">Test Your Setup</h4>
                                        <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">Verify that your payment configuration is working correctly before going live.</p>
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('checkout.show') }}" 
                               class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-4 py-3 rounded-lg font-semibold flex items-center justify-center space-x-2 transition-all duration-200 shadow-md hover:shadow-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Test Payment Flow</span>
                            </a>
                        </div>
                    </div>
                
                    <!-- Security Tips -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Security Tips</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div class="ml-3">
                                        <h4 class="text-sm font-medium text-red-800 dark:text-red-200">API Keys Security</h4>
                                        <p class="text-sm text-red-700 dark:text-red-300 mt-1">Never share your secret keys. Store them securely and use environment variables in production.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-4">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-amber-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div class="ml-3">
                                        <h4 class="text-sm font-medium text-amber-800 dark:text-amber-200">Test First</h4>
                                        <p class="text-sm text-amber-700 dark:text-amber-300 mt-1">Always test in sandbox mode before switching to live payments.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div class="ml-3">
                                        <h4 class="text-sm font-medium text-green-800 dark:text-green-200">SSL Required</h4>
                                        <p class="text-sm text-green-700 dark:text-green-300 mt-1">Ensure your website has a valid SSL certificate for secure transactions.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex items-center justify-between">
                <a href="{{ route('admin.pricing.index') }}" 
                   class="inline-flex items-center px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Cancel
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Save Configuration
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation and real-time feedback
    const form = document.getElementById('payment-config-form');
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    const radios = document.querySelectorAll('input[type="radio"]');
    
    // Handle checkbox changes for visual feedback
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const section = this.closest('.bg-white');
            if (section) {
                if (this.checked) {
                    section.classList.add('ring-2', 'ring-blue-500', 'ring-opacity-50');
                } else {
                    section.classList.remove('ring-2', 'ring-blue-500', 'ring-opacity-50');
                }
            }
        });
        
        // Initialize state
        if (checkbox.checked) {
            const section = checkbox.closest('.bg-white');
            if (section) {
                section.classList.add('ring-2', 'ring-blue-500', 'ring-opacity-50');
            }
        }
    });

    // Handle radio button changes
    radios.forEach(radio => {
        radio.addEventListener('change', function() {
            // Remove active state from all radio options
            document.querySelectorAll('input[name="primary_payment_method"]').forEach(r => {
                const div = r.nextElementSibling;
                if (div) {
                    div.classList.remove('ring-2', 'ring-blue-500', 'ring-opacity-50');
                }
            });
            
            // Add active state to selected option
            if (this.checked) {
                const div = this.nextElementSibling;
                if (div) {
                    div.classList.add('ring-2', 'ring-blue-500', 'ring-opacity-50');
                }
            }
        });
        
        // Initialize state
        if (radio.checked) {
            const div = radio.nextElementSibling;
            if (div) {
                div.classList.add('ring-2', 'ring-blue-500', 'ring-opacity-50');
            }
        }
    });

    // Form submission with loading state
    form.addEventListener('submit', function(e) {
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.innerHTML = `
            <svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            Saving...
        `;
        submitBtn.disabled = true;
        
        // Re-enable after 3 seconds in case of error
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 3000);
    });

    // Auto-save draft functionality (optional)
    let saveTimeout;
    const inputs = form.querySelectorAll('input, select');
    
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            clearTimeout(saveTimeout);
            // Auto-save functionality can be implemented here if needed
        });
    });
});
</script>
@endsection
