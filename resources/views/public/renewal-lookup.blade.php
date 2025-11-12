@extends('layouts.checkout')

@section('title', 'Renew Your Subscription')

@section('content')
<div class="min-h-screen flex items-center py-12 bg-gray-50 dark:bg-gray-900">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-10 animate-fade-in-up">
            <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                Renew Your Subscription
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2 text-lg">
                Find your subscription using your email or order number
            </p>
        </div>

        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-8">
            @if (session('error'))
                <div class="mb-6 p-4 border border-red-200 bg-red-50 dark:bg-red-900/20 dark:border-red-800 rounded-xl text-red-700 dark:text-red-400">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('success'))
                <div class="mb-6 p-4 border border-green-200 bg-green-50 dark:bg-green-900/20 dark:border-green-800 rounded-xl text-green-700 dark:text-green-400">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Lookup Form -->
            <form method="GET" action="{{ route('renewal.lookup') }}" class="space-y-6">
                @if($source ?? null)
                    <input type="hidden" name="source" value="{{ $source }}">
                @endif
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Email Address
                        </label>
                        <input type="email" name="email" value="{{ $email ?? old('email') }}" 
                            placeholder="your@email.com"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Enter the email used for your subscription</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Order Number (Optional)
                        </label>
                        <input type="text" name="order_number" value="{{ $orderNumber ?? old('order_number') }}" 
                            placeholder="ORD-2025-000001"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">If you know your order number</p>
                    </div>
                </div>

                <div class="flex justify-center">
                    <button type="submit" 
                        class="w-full md:w-auto px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md transition-colors duration-200">
                        Find My Subscription
                    </button>
                </div>
            </form>

            <!-- Results -->
            @if ($subscriptions && $subscriptions->count() > 0)
                <div class="mt-10 border-t border-gray-200 dark:border-gray-700 pt-8">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Your Subscriptions</h2>
                    
                    <div class="space-y-4">
                        @foreach ($subscriptions as $subscription)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-6 hover:shadow-md transition-shadow">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                                {{ $subscription->pricingPlan->display_name ?? 'Subscription' }}
                                            </h3>
                                            <span class="px-3 py-1 text-xs font-medium rounded-full
                                                @if($subscription->isActive()) bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                                @elseif($subscription->isExpired()) bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                                @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                                @endif">
                                                @if($subscription->isActive())
                                                    Active
                                                @elseif($subscription->isExpired())
                                                    Expired
                                                @else
                                                    {{ ucfirst($subscription->status) }}
                                                @endif
                                            </span>
                                        </div>
                                        
                                        <div class="grid grid-cols-2 gap-4 text-sm text-gray-600 dark:text-gray-400">
                                            <div>
                                                <span class="font-medium">Order:</span> {{ $subscription->order_number }}
                                            </div>
                                            <div>
                                                <span class="font-medium">Status:</span> {{ ucfirst($subscription->status) }}
                                            </div>
                                            @if($subscription->expires_at)
                                                <div>
                                                    <span class="font-medium">Expires:</span> 
                                                    {{ $subscription->expires_at->format('M d, Y') }}
                                                </div>
                                                <div>
                                                    <span class="font-medium">Days Left:</span>
                                                    @if($subscription->daysUntilExpiry() !== null)
                                                        @if($subscription->daysUntilExpiry() > 0)
                                                            <span class="text-green-600 dark:text-green-400">{{ ceil($subscription->daysUntilExpiry()) }} days</span>
                                                        @else
                                                            <span class="text-red-600 dark:text-red-400">Expired {{ ceil(abs($subscription->daysUntilExpiry())) }} days ago</span>
                                                        @endif
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="flex flex-col gap-2">
                                        @if($subscription->pricingPlan && $subscription->pricingPlan->is_active && $subscription->isActive())
                                            <a href="{{ route('renewal.quick', ['orderNumber' => $subscription->order_number, 'email' => $subscription->user->email, 'source' => $source ?? request('source')]) }}" 
                                                class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg text-center transition-colors">
                                                Quick Renew (Same Plan)
                                            </a>
                                        @endif
                                        
                                        @if($subscription->isExpired() || $subscription->daysUntilExpiry() <= 7 || $subscription->isActive())
                                            <a href="{{ route('renewal.show', ['orderNumber' => $subscription->order_number, 'email' => $subscription->user->email, 'source' => $source ?? request('source')]) }}" 
                                                class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg text-center transition-colors">
                                                Renew (Edit Order)
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @elseif(request()->has('email') || request()->has('order_number'))
                <div class="mt-10 border-t border-gray-200 dark:border-gray-700 pt-8">
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No subscriptions found</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            We couldn't find any subscriptions matching your search. Please check your email or order number and try again.
                        </p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Help Section -->
        <div class="mt-8 text-center">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Need help? <a href="mailto:contact@smarters-proiptv.com" class="text-indigo-600 hover:text-indigo-700 dark:text-indigo-400">Contact Support</a>
            </p>
        </div>
    </div>
</div>
@endsection

