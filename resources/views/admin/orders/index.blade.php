@extends('layouts.admin')

@section('title', 'Orders Management')

@section('content')
    <div class="space-y-8">
        <!-- Header -->
        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-4 md:p-6 animate-fade-in-up">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-[#201E1F]">Orders Management</h1>
                    <p class="text-[#201E1F]/60 text-sm md:text-base">Monitor and manage all customer orders</p>
                </div>
                <a href="{{ route('admin.orders.create') }}"
                    class="btn-mobile bg-gradient-to-r from-[#D63613] to-[#D63613]/80 hover:from-[#D63613]/90 hover:to-[#D63613] text-white px-6 py-3 rounded-lg text-sm font-semibold flex items-center justify-center space-x-2 transition-all duration-300 shadow-md hover:shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span>Create New Order</span>
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 md:gap-6">
            <div
                class="bg-[#F5F5F5] overflow-hidden rounded-xl border border-[#D63613]/10 shadow-md hover:shadow-medium transition-all duration-300 animate-fade-in-up">
                <div class="p-4 md:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 md:ml-4 flex-1">
                            <dl>
                                <dt class="text-xs md:text-sm font-medium text-[#201E1F]/60 mb-1">Total Orders</dt>
                                <dd class="text-xl md:text-2xl font-bold text-[#201E1F]">{{ $orders->total() }}</dd>
                                <dd class="text-xs text-blue-600 font-medium">All time</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-[#F5F5F5] overflow-hidden rounded-xl border border-[#D63613]/10 shadow-md hover:shadow-medium transition-all duration-300 animate-fade-in-up"
                style="animation-delay: 0.1s;">
                <div class="p-4 md:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 md:ml-4 flex-1">
                            <dl>
                                <dt class="text-xs md:text-sm font-medium text-[#201E1F]/60 mb-1">Active Orders</dt>
                                <dd class="text-xl md:text-2xl font-bold text-[#201E1F]">{{ $activeOrdersCount ?? 0 }}</dd>
                                <dd class="text-xs text-green-600 font-medium">Currently running</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-[#F5F5F5] overflow-hidden rounded-xl border border-[#D63613]/10 shadow-md hover:shadow-medium transition-all duration-300 animate-fade-in-up"
                style="animation-delay: 0.2s;">
                <div class="p-4 md:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 md:ml-4 flex-1">
                            <dl>
                                <dt class="text-xs md:text-sm font-medium text-[#201E1F]/60 mb-1">Pending Orders</dt>
                                <dd class="text-xl md:text-2xl font-bold text-[#201E1F]">{{ $pendingOrdersCount ?? 0 }}</dd>
                                <dd class="text-xs text-yellow-600 font-medium">Awaiting activation</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-[#F5F5F5] overflow-hidden rounded-xl border border-[#D63613]/10 shadow-md hover:shadow-medium transition-all duration-300 animate-fade-in-up"
                style="animation-delay: 0.3s;">
                <div class="p-4 md:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 md:ml-4 flex-1">
                            <dl>
                                <dt class="text-xs md:text-sm font-medium text-[#201E1F]/60 mb-1">Total Revenue</dt>
                                <dd class="text-xl md:text-2xl font-bold text-[#201E1F]">
                                    ${{ number_format($totalRevenue ?? 0, 2) }}</dd>
                                <dd class="text-xs text-purple-600 font-medium">All time</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-[#F5F5F5] overflow-hidden rounded-xl border border-[#D63613]/10 shadow-md hover:shadow-medium transition-all duration-300 animate-fade-in-up"
                style="animation-delay: 0.4s;">
                <div class="p-4 md:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-red-400 to-red-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 md:ml-4 flex-1">
                            <dl>
                                <dt class="text-xs md:text-sm font-medium text-[#201E1F]/60 mb-1">Expired Orders</dt>
                                <dd class="text-xl md:text-2xl font-bold text-[#201E1F]">{{ $expiredOrdersCount ?? 0 }}</dd>
                                <dd class="text-xs text-red-600 font-medium">Need renewal</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="px-4 md:px-6 py-4 border-b border-[#D63613]/10 bg-white/30">
            <form method="GET" action="{{ route('admin.orders.index') }}"
                class="flex flex-col md:flex-row md:items-end md:space-x-4 space-y-3 md:space-y-0">

                <!-- Search -->
                <div class="flex-1">
                    <label for="search" class="block text-sm font-medium text-[#201E1F]/70 mb-1">Search (Order, Name or
                        Email)</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        placeholder="Search orders..."
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#D63613] focus:border-[#D63613]">
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-[#201E1F]/70 mb-1">Status</label>
                    <select name="status" id="status"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#D63613] focus:border-[#D63613]">
                        <option value="">All</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled
                        </option>
                    </select>
                </div>

                <!-- Filter type -->
                <div>
                    <label for="filter" class="block text-sm font-medium text-[#201E1F]/70 mb-1">Filter</label>
                    <select name="filter" id="filter"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#D63613] focus:border-[#D63613]">
                        <option value="">All Orders</option>
                        <option value="expiring" {{ request('filter') === 'expiring' ? 'selected' : '' }}>Expiring Soon
                            (≤7 days)</option>
                        <option value="expired" {{ request('filter') === 'expired' ? 'selected' : '' }}>Expired</option>
                    </select>
                </div>

                <!-- Payment Method -->
                <div>
                    <label for="payment_method" class="block text-sm font-medium text-[#201E1F]/70 mb-1">Payment</label>
                    <select name="payment_method" id="payment_method"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#D63613] focus:border-[#D63613]">
                        <option value="">All</option>
                        <option value="paypal" {{ request('payment_method') === 'paypal' ? 'selected' : '' }}>PayPal
                        </option>
                        <option value="stripe" {{ request('payment_method') === 'stripe' ? 'selected' : '' }}>Stripe
                        </option>
                        <option value="manual" {{ request('payment_method') === 'manual' ? 'selected' : '' }}>Manual
                        </option>
                    </select>
                </div>

                <!-- Plan Type -->
                <div>
                    <label for="plan_type" class="block text-sm font-medium text-[#201E1F]/70 mb-1">Plan Type</label>
                    <select name="plan_type" id="plan_type"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#D63613] focus:border-[#D63613]">
                        <option value="">All</option>
                        <option value="regular" {{ request('plan_type') === 'regular' ? 'selected' : '' }}>Regular
                        </option>
                        <option value="reseller" {{ request('plan_type') === 'reseller' ? 'selected' : '' }}>Reseller
                        </option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="flex space-x-2">
                    <button type="submit"
                        class="bg-[#D63613] hover:bg-[#B72D10] text-white px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200">
                        Apply
                    </button>
                    <a href="{{ route('admin.orders.index') }}"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200">
                        Reset
                    </a>
                </div>
            </form>
        </div>


        <!-- Orders Table/Cards -->
        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md animate-fade-in-up"
            style="animation-delay: 0.5s;">
            <div class="px-4 md:px-6 py-4 border-b border-[#D63613]/10">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-[#201E1F]">All Orders</h2>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-[#201E1F]/60">{{ $orders->total() }} total orders</span>
                        <div class="w-3 h-3 bg-gradient-to-r from-green-400 to-green-600 rounded-full"></div>
                    </div>
                </div>
            </div>

            <!-- Desktop Table View -->
            <div class="hidden md:block overflow-x-auto desktop-table-container">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-[#D63613]">
                        <tr>
                            <th class="px-3 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">
                                Order #</th>
                            <th class="px-3 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">
                                Customer</th>
                            <th class="px-3 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">
                                Plan</th>
                            <th class="px-3 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">
                                Amount</th>
                            <th class="px-3 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">
                                Payment</th>
                            <th class="px-3 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">
                                Source</th>
                            <th class="px-3 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">
                                Status</th>
                            <th class="px-3 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">
                                Created</th>
                            <th class="px-3 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">
                                Expires</th>
                            <th class="px-3 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-[#F5F5F5] divide-y divide-gray-200">
                        @forelse($orders as $order)
                            <tr class="hover:bg-white/50 transition-colors duration-200">
                                <td class="px-3 py-4 whitespace-nowrap text-sm font-medium text-[#201E1F]">
                                    <a href="{{ route('admin.orders.show', $order) }}"
                                        class="text-[#D63613] hover:text-[#D63613]/80 transition-colors duration-200">
                                        {{ $order->order_number }}
                                    </a>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="w-8 h-8 bg-gradient-to-br from-[#D63613] to-[#D63613]/80 rounded-full flex items-center justify-center mr-3">
                                            <span
                                                class="text-xs font-semibold text-white">{{ substr($order->user->name, 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-[#201E1F]">{{ $order->user->name }}</div>
                                            <div class="text-xs text-[#201E1F]/60">{{ $order->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap text-sm text-[#201E1F]">
                                    <div class="flex items-center space-x-2">
                                        @if ($order->order_type === 'credit_pack')
                                            <div class="flex flex-col">
                                                <span
                                                    class="font-medium text-[#201E1F]">{{ $order->resellerCreditPack->name ?? 'Credit Pack' }}</span>
                                                <span
                                                    class="text-xs text-[#201E1F]/60">{{ number_format($order->resellerCreditPack->credits_amount ?? 0) }}
                                                    Credits</span>
                                            </div>
                                            <span
                                                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                                                Credit Pack
                                            </span>
                                        @elseif ($order->order_type === 'custom_product')
                                            <div class="flex items-center space-x-2">
                                                <span
                                                    class="font-medium text-[#201E1F]">{{ $order->customProduct->name ?? 'Custom Product' }}</span>
                                                <span
                                                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-purple-50 text-purple-700 border border-purple-200">
                                                    Custom Product
                                                </span>
                                            </div>
                                        @else
                                            <span
                                                class="text-[#201E1F]">{{ $order->pricingPlan->display_name ?? 'N/A' }}</span>
                                            @if ($order->pricingPlan && $order->pricingPlan->plan_type === 'reseller')
                                                <span
                                                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-orange-50 text-orange-700 border border-orange-200">
                                                    Reseller Plan
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap text-sm font-semibold text-[#201E1F]">
                                    ${{ number_format($order->amount, 2) }}
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap text-sm text-[#201E1F]">
                                    <span class="capitalize">{{ str_replace('_', ' ', $order->payment_method) }}</span>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap text-sm text-[#201E1F]">
                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full border">
                                        {{ $order->source ?? '—' }}
                                    </span>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex px-3 py-1 text-xs font-semibold rounded-full border
                                    @if ($order->status === 'active') bg-green-50 text-green-700 border-green-200
                                    @elseif($order->status === 'pending') bg-yellow-50 text-yellow-700 border-yellow-200
                                    @elseif($order->status === 'expired') bg-red-50 text-red-700 border-red-200
                                    @elseif($order->status === 'cancelled') bg-gray-50 text-gray-700 border-gray-200
                                    @else bg-blue-50 text-blue-700 border-blue-200 @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap">
                                    <div class="text-sm text-[#201E1F]">
                                        {{ $order->created_at ? $order->created_at->format('M d, Y') : 'N/A' }}</div>
                                    <div class="text-xs text-[#201E1F]/60">
                                        {{ $order->created_at ? $order->created_at->format('g:i A') : 'N/A' }}</div>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap text-sm">
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full border
                                    @php
                                        $daysLeft = $order->expires_at ? intval(now()->diffInDays($order->expires_at, false)) : null; @endphp
                                    @if ($daysLeft !== null) @if ($daysLeft > 7) bg-green-50 text-green-700 border-green-200
                                        @elseif($daysLeft > 0 && $daysLeft <= 7) bg-yellow-50 text-yellow-700 border-yellow-200
                                        @elseif($daysLeft <= 0) bg-red-50 text-red-700 border-red-200 @endif
                                    @endif">
                                        @if ($daysLeft !== null)
                                            @if ($daysLeft > 0)
                                                {{ $daysLeft }} days left
                                            @else
                                                Expired
                                            @endif
                                        @else
                                            —
                                        @endif
                                    </span>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.orders.show', $order) }}"
                                            class="text-[#D63613] hover:text-[#D63613]/80 transition-colors duration-200"
                                            title="View Order">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </a>
                                        <a href="{{ route('admin.orders.edit', $order) }}"
                                            class="text-blue-600 hover:text-blue-800 transition-colors duration-200"
                                            title="Edit Order">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </a>
                                        <button type="button"
                                            onclick="openDeleteModal({{ $order->id }}, '{{ $order->order_number }}', '{{ route('admin.orders.destroy', $order) }}')"
                                            class="text-red-600 hover:text-red-800 transition-colors duration-200"
                                            title="Delete Order">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                        
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-6 py-12 text-center text-sm text-[#201E1F]/60">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-[#201E1F]/20 mb-4" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        <p class="text-lg font-medium text-[#201E1F]/40 mb-2">No orders found</p>
                                        <p class="text-sm text-[#201E1F]/30">Get started by creating your first order</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="md:hidden space-y-4 px-4 py-4">
                @forelse($orders as $order)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <!-- Order Header -->
                        <div class="px-4 py-3 bg-gradient-to-r from-[#D63613] to-[#D63613]/90">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                                        <span
                                            class="text-sm font-semibold text-white">{{ substr($order->user->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-semibold text-white">{{ $order->user->name }}</h3>
                                        <p class="text-xs text-white/80">{{ $order->order_number }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-lg font-bold text-white">${{ number_format($order->amount, 2) }}
                                    </div>
                                    <div class="text-xs text-white/80">{{ ucfirst($order->payment_method) }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Order Content -->
                        <div class="px-4 py-4">
                            <!-- Service Details -->
                            <div class="mb-3">
                                @if ($order->order_type === 'credit_pack')
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $order->resellerCreditPack->name ?? 'Credit Pack' }}</div>
                                    <div class="text-xs text-gray-500">
                                        {{ number_format($order->resellerCreditPack->credits_amount ?? 0) }} Credits</div>
                                @elseif ($order->order_type === 'custom_product')
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $order->customProduct->name ?? 'Custom Product' }}</div>
                                @else
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $order->pricingPlan->display_name ?? 'N/A' }}</div>
                                @endif
                            </div>

                            <!-- Status and Expiry -->
                            <div class="flex items-center justify-between mb-3">
                                <span
                                    class="inline-flex px-3 py-1 text-xs font-semibold rounded-full
                                @if ($order->status === 'active') bg-green-50 text-green-700 border border-green-200
                                @elseif($order->status === 'pending') bg-yellow-50 text-yellow-700 border border-yellow-200
                                @elseif($order->status === 'expired') bg-red-50 text-red-700 border border-red-200
                                @elseif($order->status === 'cancelled') bg-gray-50 text-gray-700 border border-gray-200
                                @else bg-blue-50 text-blue-700 border border-blue-200 @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                                @php
                                    $daysLeft = $order->expires_at
                                        ? intval(now()->diffInDays($order->expires_at, false))
                                        : null;
                                @endphp
                                @if ($daysLeft !== null)
                                    <span class="text-xs text-gray-500">
                                        @if ($daysLeft > 0)
                                            {{ $daysLeft }} days left
                                        @else
                                            Expired
                                        @endif
                                    </span>
                                @endif
                            </div>

                            <!-- Date -->
                            <div class="text-xs text-gray-500 mb-3">
                                {{ $order->created_at ? $order->created_at->format('M d, Y g:i A') : 'N/A' }}
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                                <div class="flex items-center space-x-4">
                                    <a href="{{ route('admin.orders.show', $order) }}"
                                        class="flex items-center space-x-1 text-[#D63613] hover:text-[#D63613]/80 transition-colors duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                        <span class="text-xs font-medium">View</span>
                                    </a>
                                    <a href="{{ route('admin.orders.edit', $order) }}"
                                        class="flex items-center space-x-1 text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                        <span class="text-xs font-medium">Edit</span>
                                    </a>
                                </div>
                                <button type="button"
                                    onclick="openDeleteModal({{ $order->id }}, '{{ $order->order_number }}', '{{ route('admin.orders.destroy', $order) }}')"
                                    class="flex items-center space-x-1 text-red-600 hover:text-red-800 transition-colors duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                    <span class="text-xs font-medium">Delete</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <p class="text-lg font-medium text-gray-500 mb-2">No orders found</p>
                        <p class="text-sm text-gray-400">Get started by creating your first order</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        @if ($orders->hasPages())
            <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-4 animate-fade-in-up"
                style="animation-delay: 0.6s;">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-75 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-xl bg-white dark:bg-gray-800">
            <div class="mt-3">
                <!-- Modal Header -->
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-red-100 dark:bg-red-900/20 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-[#201E1F] dark:text-white">Delete Order</h3>
                    </div>
                    <button type="button" onclick="closeDeleteModal()" class="text-[#201E1F]/60 hover:text-[#201E1F] dark:text-gray-400 dark:hover:text-white transition-colors duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Modal Body -->
                <div class="mb-4">
                    <p class="text-sm text-[#201E1F]/80 dark:text-gray-300 mb-2">
                        Are you sure you want to delete this order? This action cannot be undone.
                    </p>
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3 mt-3">
                        <p class="text-xs text-[#201E1F]/60 dark:text-gray-400 mb-1">Order Number:</p>
                        <p class="text-sm font-mono font-semibold text-[#201E1F] dark:text-white" id="modal-order-number"></p>
                    </div>
                </div>
                
                <!-- Modal Footer -->
                <div class="flex items-center justify-end space-x-3 mt-6">
                    <button type="button" 
                            onclick="closeDeleteModal()"
                            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-[#201E1F] dark:text-white rounded-lg text-sm font-medium transition-colors duration-200">
                        Cancel
                    </button>
                    <form id="deleteOrderForm" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition-colors duration-200">
                            Delete Order
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openDeleteModal(orderId, orderNumber, deleteUrl) {
            document.getElementById('modal-order-number').textContent = orderNumber;
            document.getElementById('deleteOrderForm').action = deleteUrl;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('deleteModal').classList.contains('hidden')) {
                closeDeleteModal();
            }
        });
    </script>
@endsection
