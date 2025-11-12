@extends('layouts.checkout')

@section('title', 'Thank You')

@section('content')
<div class="min-h-screen  flex items-center justify-center py-12 px-4">
    <div class="max-w-2xl w-full">
        <!-- Success Animation -->
        <div class="text-center mb-8 animate-fade-in-up">
            
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-3">Payment Successful!</h1>
            <p class="text-lg text-gray-600 dark:text-gray-300">Your order has been received and is being processed</p>
        </div>

        <!-- Order Details Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-blue-200 dark:border-gray-700 shadow-lg animate-fade-in-up overflow-hidden" style="animation-delay: 0.1s;">
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-50 to-green-100 border-b border-green-200 px-8 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-semibold text-green-900 mb-1">Order Confirmed</h2>
                        <p class="text-sm text-green-700">We've sent a confirmation email to your inbox</p>
                    </div>
                    <div class="hidden sm:block">
                        <div class="w-16 h-16 bg-white  rounded-full flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Information -->
            <div class="p-8 space-y-6">
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Order Details</h3>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 divide-y divide-gray-100">
                        <div class="flex justify-between items-center px-6 py-4">
                            <span class="text-sm font-medium text-gray-900 dark:text-white/60">Order Number</span>
                            <span class="text-sm font-bold text-gray-900 dark:text-white font-mono dark:bg-gray-800 bg-gray-100 px-3 py-1 rounded-lg">{{ $order->order_number }}</span>
                        </div>
                        <div class="flex justify-between items-center px-6 py-4">
                            <span class="text-sm font-medium text-gray-900 dark:text-white/60">Amount Paid</span>
                            <span class="text-xl font-bold text-green-600">${{ number_format($order->amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center px-6 py-4">
                            <span class="text-sm font-medium text-gray-900 dark:text-white/60">Order Status</span>
                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-yellow-50 text-yellow-700 border border-yellow-200">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center px-6 py-4">
                            <span class="text-sm font-medium text-gray-900 dark:text-white/60">Order Date</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $order->created_at->format('M d, Y g:i A') }}</span>
                        </div>
                    </div>
                </div>

                <!-- What's Next Section -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-semibold text-blue-900 mb-2">What happens next?</h4>
                            <ul class="space-y-2 text-sm text-blue-800">
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-blue-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    We'll activate your order and set up your service
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-blue-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    You'll receive your access credentials via email
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-blue-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    This process may take between 30 minutes and 24 hours.
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6">
                    <a href="{{ $returnUrl ?? config('app.url') }}" target="_top" class="flex-1 flex items-center justify-center px-6 py-3 border-2 border-blue-600 text-blue-600 font-semibold rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Back to Home
                    </a>
                    
                </div>
            </div>
        </div>

        <!-- Additional Info -->
        <div class="mt-6 text-center text-sm text-gray-900 dark:text-white/60 animate-fade-in-up" style="animation-delay: 0.2s;">
            <p class="flex items-center justify-center space-x-2">
                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                <span>Your payment was processed securely</span>
            </p>
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

@keyframes scale-in {
    0% {
        opacity: 0;
        transform: scale(0.5);
    }
    50% {
        transform: scale(1.1);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}

.animate-fade-in-up {
    animation: fade-in-up 0.6s ease-out forwards;
}

.animate-scale-in {
    animation: scale-in 0.8s ease-out forwards;
}
</style>
@endsection