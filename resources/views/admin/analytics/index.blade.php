@extends('layouts.admin')

@section('title', 'Revenue Analytics')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="lg:flex space-y-4 items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-[#201E1F]">Revenue Analytics</h1>
            <p class="text-[#201E1F]/60 mt-2">Comprehensive revenue analysis and business insights</p>
        </div>
        
        <!-- Date Range Selector -->
        <div class="flex justify-between items-center space-x-4">
            <label for="dateRange" class="text-sm font-medium text-[#201E1F]/60">Date Range:</label>
            <select id="dateRange" onchange="changeDateRange(this.value)" class="px-4 py-2 border border-[#D63613]/20 rounded-lg focus:ring-2 focus:ring-[#D63613]/20 focus:border-[#D63613]">
                <option value="7" {{ $dateRange === '7' ? 'selected' : '' }}>Last 7 days</option>
                <option value="30" {{ $dateRange === '30' ? 'selected' : '' }}>Last 30 days</option>
                <option value="90" {{ $dateRange === '90' ? 'selected' : '' }}>Last 90 days</option>
                <option value="365" {{ $dateRange === '365' ? 'selected' : '' }}>Last year</option>
                <option value="all" {{ $dateRange === 'all' ? 'selected' : '' }}>All time</option>
            </select>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-[#201E1F]/60">Total Revenue</p>
                    <p class="text-2xl font-bold text-[#201E1F]">${{ number_format($summaryStats['total_revenue'], 2) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-[#201E1F]/60">Total Orders</p>
                    <p class="text-2xl font-bold text-[#201E1F]">{{ number_format($summaryStats['total_orders']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-[#201E1F]/60">Avg Order Value</p>
                    <p class="text-2xl font-bold text-[#201E1F]">${{ number_format($summaryStats['avg_order_value'], 2) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-gradient-to-br from-orange-400 to-orange-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-[#201E1F]/60">New Customers</p>
                    <p class="text-2xl font-bold text-[#201E1F]">{{ number_format($summaryStats['new_customers']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-gradient-to-br from-red-400 to-red-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-[#201E1F]/60">Repeat Rate</p>
                    <p class="text-2xl font-bold text-[#201E1F]">{{ number_format($summaryStats['conversion_rate'], 1) }}%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Revenue by Source -->
        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6">
            <h3 class="text-xl font-semibold text-[#201E1F] mb-6">Revenue by Source</h3>
            <div class="space-y-4">
                @php
                    $totalSourceRevenue = $revenueBySource->sum('revenue');
                @endphp
                @foreach($revenueBySource as $source)
                    @php
                        $percentage = $totalSourceRevenue > 0 ? ($source['revenue'] / $totalSourceRevenue) * 100 : 0;
                    @endphp
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-4 h-4 rounded-full {{ $source['source'] === 'Client' ? 'bg-blue-500' : 'bg-orange-500' }}"></div>
                            <span class="text-sm font-medium text-[#201E1F]">{{ $source['source'] }}</span>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-semibold text-[#201E1F]">${{ number_format($source['revenue'], 2) }}</div>
                            <div class="text-xs text-[#201E1F]/60">{{ number_format($percentage, 1) }}%</div>
                        </div>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="h-2 rounded-full {{ $source['source'] === 'Client' ? 'bg-blue-500' : 'bg-orange-500' }}" style="width: {{ $percentage }}%"></div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Payment Methods -->
        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6">
            <h3 class="text-xl font-semibold text-[#201E1F] mb-6">Payment Methods</h3>
            <div class="space-y-4">
                @php
                    $totalPaymentRevenue = $revenueByPaymentMethod->sum('revenue');
                @endphp
                @foreach($revenueByPaymentMethod as $method)
                    @php
                        $percentage = $totalPaymentRevenue > 0 ? ($method['revenue'] / $totalPaymentRevenue) * 100 : 0;
                        $colors = [
                            'Paypal' => 'bg-blue-500',
                            'Stripe' => 'bg-purple-500',
                            'Crypto' => 'bg-yellow-500',
                            'Unknown' => 'bg-gray-500'
                        ];
                        $color = $colors[$method['method']] ?? 'bg-gray-500';
                    @endphp
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-4 h-4 rounded-full {{ $color }}"></div>
                            <span class="text-sm font-medium text-[#201E1F]">{{ $method['method'] }}</span>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-semibold text-[#201E1F]">${{ number_format($method['revenue'], 2) }}</div>
                            <div class="text-xs text-[#201E1F]/60">{{ number_format($percentage, 1) }}%</div>
                        </div>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="h-2 rounded-full {{ $color }}" style="width: {{ $percentage }}%"></div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Revenue Trends Chart -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6">
        <h3 class="text-xl font-semibold text-[#201E1F] mb-6">Revenue Trends</h3>
        <div class="h-80 flex items-end justify-between space-x-2">
            @php
                $maxRevenue = max(array_column($revenueTrends, 'revenue'));
            @endphp
            @foreach($revenueTrends as $trend)
                <div class="flex-1 flex flex-col items-center group">
                    <div class="relative w-full">
                        <div class="w-full bg-gradient-to-t from-[#D63613] to-[#D63613]/80 rounded-t-lg shadow-sm hover:shadow-md transition-all duration-300 group-hover:from-[#D63613]/90 group-hover:to-[#D63613]/70"
                             style="height: {{ $maxRevenue > 0 ? max(($trend['revenue'] / $maxRevenue) * 280, 8) : 8 }}px;"
                             title="${{ number_format($trend['revenue'], 2) }} - {{ $trend['orders'] }} orders">
                        </div>
                        <!-- Tooltip -->
                        <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-gray-900 text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-10">
                            <div class="font-semibold">${{ number_format($trend['revenue'], 2) }}</div>
                            <div class="text-gray-300">{{ $trend['orders'] }} orders</div>
                            <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-900"></div>
                        </div>
                    </div>
                    <div class="text-xs text-[#201E1F]/60 mt-3 transform -rotate-45 origin-top-left font-medium">
                        {{ $trend['date'] }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Popular Products Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Popular Plans -->
        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6">
            <h3 class="text-xl font-semibold text-[#201E1F] mb-6">Popular Plans</h3>
            <div class="space-y-4">
                @foreach($popularPlans->take(5) as $plan)
                    <div class="flex items-center justify-between p-3 bg-white rounded-lg">
                        <div>
                            <div class="text-sm font-semibold text-[#201E1F]">{{ $plan->name }}</div>
                            <div class="text-xs text-[#201E1F]/60">${{ number_format($plan->price, 2) }}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-semibold text-[#201E1F]">{{ $plan->order_count }}</div>
                            <div class="text-xs text-[#201E1F]/60">orders</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Popular Credit Packs -->
        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6">
            <h3 class="text-xl font-semibold text-[#201E1F] mb-6">Popular Credit Packs</h3>
            <div class="space-y-4">
                @foreach($popularCreditPacks->take(5) as $pack)
                    <div class="flex items-center justify-between p-3 bg-white rounded-lg">
                        <div>
                            <div class="text-sm font-semibold text-[#201E1F]">{{ $pack->name }}</div>
                            <div class="text-xs text-[#201E1F]/60">{{ $pack->credits_amount }} credits - ${{ number_format($pack->price, 2) }}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-semibold text-[#201E1F]">{{ $pack->order_count }}</div>
                            <div class="text-xs text-[#201E1F]/60">orders</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Popular Custom Products -->
        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6">
            <h3 class="text-xl font-semibold text-[#201E1F] mb-6">Popular Custom Products</h3>
            <div class="space-y-4">
                @foreach($popularCustomProducts->take(5) as $product)
                    <div class="flex items-center justify-between p-3 bg-white rounded-lg">
                        <div>
                            <div class="text-sm font-semibold text-[#201E1F]">{{ $product->name }}</div>
                            <div class="text-xs text-[#201E1F]/60">${{ number_format($product->price, 2) }}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-semibold text-[#201E1F]">{{ $product->order_count }}</div>
                            <div class="text-xs text-[#201E1F]/60">orders</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Top Customers Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Clients -->
        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6">
            <h3 class="text-xl font-semibold text-[#201E1F] mb-6">Top Clients by Revenue</h3>
            <div class="space-y-4">
                @foreach($topClients->take(5) as $client)
                    <div class="flex items-center justify-between p-3 bg-white rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                <span class="text-xs font-semibold text-white">{{ substr($client->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-[#201E1F]">{{ $client->name }}</div>
                                <div class="text-xs text-[#201E1F]/60">{{ $client->email }}</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-semibold text-[#201E1F]">${{ number_format($client->total_spent, 2) }}</div>
                            <div class="text-xs text-[#201E1F]/60">{{ $client->total_orders }} orders</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Top Resellers -->
        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6">
            <h3 class="text-xl font-semibold text-[#201E1F] mb-6">Top Resellers by Revenue</h3>
            <div class="space-y-4">
                @foreach($topResellers->take(5) as $reseller)
                    <div class="flex items-center justify-between p-3 bg-white rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center">
                                <span class="text-xs font-semibold text-white">{{ substr($reseller->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-[#201E1F]">{{ $reseller->name }}</div>
                                <div class="text-xs text-[#201E1F]/60">{{ $reseller->email }}</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-semibold text-[#201E1F]">${{ number_format($reseller->total_spent, 2) }}</div>
                            <div class="text-xs text-[#201E1F]/60">{{ $reseller->total_orders }} orders</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Order Status Distribution -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6">
        <h3 class="text-xl font-semibold text-[#201E1F] mb-6">Order Status Distribution</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @php
                $totalOrders = $orderStatusDistribution->sum('count');
            @endphp
            @foreach($orderStatusDistribution as $status)
                @php
                    $percentage = $totalOrders > 0 ? ($status['count'] / $totalOrders) * 100 : 0;
                    $colors = [
                        'Active' => 'bg-green-500',
                        'Pending' => 'bg-yellow-500',
                        'Expired' => 'bg-red-500',
                        'Completed' => 'bg-blue-500'
                    ];
                    $color = $colors[$status['status']] ?? 'bg-gray-500';
                @endphp
                <div class="p-4 bg-white rounded-lg text-center">
                    <div class="w-12 h-12 {{ $color }} rounded-full flex items-center justify-center mx-auto mb-3">
                        <span class="text-white font-semibold">{{ $status['count'] }}</span>
                    </div>
                    <div class="text-sm font-semibold text-[#201E1F]">{{ $status['status'] }}</div>
                    <div class="text-xs text-[#201E1F]/60">{{ number_format($percentage, 1) }}%</div>
                    <div class="text-xs text-[#201E1F]/60">${{ number_format($status['amount'], 2) }}</div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
function changeDateRange(range) {
    const url = new URL(window.location);
    url.searchParams.set('range', range);
    window.location.href = url.toString();
}
</script>
@endsection
