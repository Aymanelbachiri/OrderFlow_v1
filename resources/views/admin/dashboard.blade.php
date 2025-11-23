@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-8">


    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        <!-- Total Orders -->
        <div class="bg-[#F5F5F5] overflow-hidden rounded-xl border border-[#D63613]/10 shadow-md hover:shadow-medium transition-all duration-300 animate-fade-in-up">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-[#201E1F]/60 mb-1">Total Orders</dt>
                            <dd class="text-2xl font-bold text-[#201E1F]">{{ number_format(\App\Models\Order::count()) }}</dd>
                            <dd class="text-xs text-blue-600 font-medium">All time</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Orders -->
        <div class="bg-[#F5F5F5] overflow-hidden rounded-xl border border-[#D63613]/10 shadow-md hover:shadow-medium transition-all duration-300 animate-fade-in-up" style="animation-delay: 0.1s;">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-[#201E1F]/60 mb-1">Active Orders</dt>
                            <dd class="text-2xl font-bold text-[#201E1F]">{{ number_format(\App\Models\Order::where('status', 'active')->count()) }}</dd>
                            <dd class="text-xs text-green-600 font-medium">Currently running</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="bg-[#F5F5F5] overflow-hidden rounded-xl border border-[#D63613]/10 shadow-md hover:shadow-medium transition-all duration-300 animate-fade-in-up" style="animation-delay: 0.2s;">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-[#201E1F]/60 mb-1">Pending Orders</dt>
                            <dd class="text-2xl font-bold text-[#201E1F]">{{ number_format(\App\Models\Order::where('status', 'pending')->count()) }}</dd>
                            <dd class="text-xs text-yellow-600 font-medium">Awaiting activation</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-[#F5F5F5] overflow-hidden rounded-xl border border-[#D63613]/10 shadow-md hover:shadow-medium transition-all duration-300 animate-fade-in-up" style="animation-delay: 0.3s;">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-[#201E1F]/60 mb-1">Total Revenue</dt>
                            <dd class="text-2xl font-bold text-[#201E1F]">${{ number_format(\App\Models\Order::whereIn('status', ['active', 'completed'])->sum('amount'), 2) }}</dd>
                            <dd class="text-xs text-purple-600 font-medium">Generated</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reseller Orders -->
        <div class="bg-[#F5F5F5] overflow-hidden rounded-xl border border-[#D63613]/10 shadow-md hover:shadow-medium transition-all duration-300 animate-fade-in-up" style="animation-delay: 0.4s;">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-orange-400 to-orange-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-[#201E1F]/60 mb-1">Reseller Orders</dt>
                            <dd class="text-2xl font-bold text-[#201E1F]">{{ number_format(\App\Models\Order::where('order_type', 'credit_pack')->count()) }}</dd>
                            <dd class="text-xs text-orange-600 font-medium">Business plans</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Quick Actions & Alerts -->
    <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">
        <!-- Expiring Subscriptions -->
        <div class="bg-[#F5F5F5] overflow-hidden rounded-xl border border-[#D63613]/10 shadow-md hover:shadow-medium transition-all duration-300 animate-fade-in-up" style="animation-delay: 0.4s;">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-[#201E1F]">Expiring Soon</h3>
                    <div class="w-10 h-10 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-2xl font-bold text-[#201E1F]">{{ $expiringSubscriptions }}</span>
                        <span class="text-sm text-gray-500">subscriptions</span>
                    </div>
                    <p class="text-sm text-[#201E1F]/60">Expire within 7 days</p>
                    <a href="{{ route('admin.orders.index', ['filter' => 'expiring']) }}" class="inline-flex items-center text-sm font-medium text-primary-600 hover:text-primary-700 transition-colors duration-200">
                        View Details
                        <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="bg-[#F5F5F5] overflow-hidden rounded-xl border border-[#D63613]/10 shadow-md hover:shadow-medium transition-all duration-300 animate-fade-in-up" style="animation-delay: 0.5s;">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-[#201E1F]">Pending Orders</h3>
                    <div class="w-10 h-10 bg-gradient-to-br from-secondary-400 to-secondary-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-2xl font-bold text-[#201E1F]">{{ $pendingOrders }}</span>
                        <span class="text-sm text-gray-500">orders</span>
                    </div>
                    <p class="text-sm text-[#201E1F]/60">Awaiting processing</p>
                    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="inline-flex items-center text-sm font-medium text-primary-600 hover:text-primary-700 transition-colors duration-200">
                        Process Orders
                        <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Chart -->
    <div class="bg-[#F5F5F5] rounded-xl border shadow-md border-[#D63613]/10 animate-fade-in-up" style="animation-delay: 0.7s;">
        <div class="px-6 py-5 border-b border-[#D63613]/10">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-semibold text-[#201E1F]">Revenue Trends</h3>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-[#7F7B82]">Last 12 months</span>
                    <div class="w-3 h-3 bg-gradient-to-r from-primary-400 to-primary-600 rounded-full"></div>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div class="h-72 flex items-end justify-between space-x-1">
                @foreach(array_slice($monthlyRevenueData, -3) as $index => $data)
                    <div class="flex-1 flex flex-col items-center group">
                        <div class="relative w-full">
                            <div class="w-full sm:w-[80%] bg-gradient-to-t from-primary-500 to-primary-400 rounded-t-lg shadow-sm hover:shadow-md transition-all duration-300 group-hover:from-primary-600 group-hover:to-primary-500"
                                 style="height: {{ $data['revenue'] > 0 ? max(($data['revenue'] / max(array_column($monthlyRevenueData, 'revenue'))) * 240, 12) : 12 }}px;"
                                 title="${{ number_format($data['revenue'], 2) }}">
                            </div>
            
                            <!-- Tooltip -->
                            <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-gray-900 text-[#7F7B82] text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-10">
                                <div class="font-semibold">${{ number_format($data['revenue'], 2) }}</div>
                                <div class="text-[#7F7B82]">{{ $data['month'] }}</div>
                                <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-900"></div>
                            </div>
                        </div>
                        <div class="text-xs text-[#7F7B82] mt-3 transform -rotate-45 origin-top-left font-medium">
                            {{ $data['month'] }}
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-6 pt-4 border-t border-[#D63613]/10">
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-gradient-to-r from-primary-400 to-primary-600 rounded-full mr-2"></div>
                            <span class="text-[#201E1F]/60">Monthly Revenue</span>
                        </div>
                    </div>
                    <div class="text-gray-500">
                        Hover over bars for details
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>

    

    <!-- Recent Orders -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm animate-fade-in-up" style="animation-delay: 0.6s;">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Recent Orders</h3>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500">Latest activity</span>
                    <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                </div>
            </div>
        </div>
        
        <div class="divide-y divide-gray-100">
            @forelse($recentOrders as $order)
                <!-- Desktop Layout -->
                <div class="hidden md:block px-6 py-4 rounded-xl border border-gray-100 hover:border-gray-200 hover:shadow-sm bg-white dark:bg-gray-900 dark:border-gray-800 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <!-- Left: Order Info -->
                        <div class="flex items-center gap-6 flex-1">
                            <!-- Order ID -->
                            <div class="flex-shrink-0">
                                <a href="{{ route('admin.orders.show', $order) }}" 
                                   class="text-sm font-semibold text-[#D63613] hover:text-[#b52c0e] transition-colors duration-200">
                                    #{{ $order->order_number }}
                                </a>
                            </div>
                
                            <!-- Customer -->
                            <div class="flex items-center gap-3 flex-1">
                                <div class="w-10 h-10 bg-gradient-to-br from-[#D63613] to-[#b52c0e] rounded-full flex items-center justify-center shadow-sm">
                                    <span class="text-sm font-semibold text-white">{{ strtoupper(substr($order->user->name, 0, 1)) }}</span>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $order->user->name }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 capitalize">{{ $order->user->role }}</div>
                                </div>
                            </div>
                
                            <!-- Plan -->
                            <div class="flex-1">
                                @if($order->order_type === 'credit_pack')
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $order->resellerCreditPack->name ?? 'Credit Pack' }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ number_format($order->resellerCreditPack->credits_amount ?? 0) }} Credits</div>
                                @else
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $order->pricingPlan->display_name ?? 'N/A' }}</div>
                                    @if($order->pricingPlan)
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $order->pricingPlan->devices_count ?? 1 }} Device(s) · {{ $order->pricingPlan->duration_months ?? 1 }} Month(s)
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                
                        <!-- Right: Amount, Status, Date -->
                        <div class="flex items-center gap-8">
                            <!-- Amount -->
                            <div class="text-right">
                                <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">${{ number_format($order->amount, 2) }}</div>
                            </div>
                
                            <!-- Status -->
                            <div>
                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full
                                    @if($order->status === 'active') bg-green-100 text-green-800 dark:bg-green-800/20 dark:text-green-400
                                    @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-800/20 dark:text-yellow-400
                                    @elseif($order->status === 'expired') bg-red-100 text-red-800 dark:bg-red-800/20 dark:text-red-400
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-800/30 dark:text-gray-400 @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                
                            <!-- Date -->
                            <div class="text-right">
                                <div class="text-sm text-gray-900 dark:text-gray-100">{{ $order->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $order->created_at->format('g:i A') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                

                <!-- Mobile Layout -->
                <div class="md:hidden px-4 py-3 hover:bg-gray-50 transition-colors duration-200">
                    <div class="flex items-center justify-between">
                        <!-- Left side: Order ID and Client -->
                        <div class="flex items-center space-x-3 flex-1">
                            <!-- Order ID -->
                            <div class="flex-shrink-0">
                                <a href="{{ route('admin.orders.show', $order) }}" 
                                   class="text-sm font-semibold text-[#D63613] hover:text-[#D63613]/80 transition-colors duration-200">
                                    #{{ $order->order_number }}
                                </a>
                            </div>
                            
                            <!-- Customer Info -->
                            <div class="flex items-center space-x-2 flex-1">
                                <div class="w-8 h-8 bg-gradient-to-br from-[#D63613] to-[#D63613]/80 rounded-full flex items-center justify-center">
                                    <span class="text-xs font-semibold text-white">{{ substr($order->user->name, 0, 1) }}</span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="text-sm font-medium text-gray-900 truncate">{{ $order->user->name }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right side: Amount only -->
                        <div class="flex-shrink-0">
                            <div class="text-sm font-semibold text-gray-900">${{ number_format($order->amount, 2) }}</div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center">
                        <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-gray-500 text-sm">No orders found</p>
                    </div>
                </div>
            @endforelse
        </div>
        
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-xl">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-600">Showing latest {{ $recentOrders->count() }} orders</p>
                <a href="{{ route('admin.orders.index') }}" 
                   class="inline-flex items-center text-sm font-medium text-[#D63613] hover:text-[#D63613]/80 transition-colors duration-200">
                    View All Orders
                    <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
