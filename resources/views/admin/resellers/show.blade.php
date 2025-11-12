@extends('layouts.admin')

@section('title', 'Reseller Details - ' . $reseller->name)

@section('content')
    <div class="space-y-8">
        <!-- Header -->
        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up">
            <div class="lg:flex space-y-4 justify-between items-center">
                <div class="flex items-center space-x-4">
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-[#D63613] to-[#D63613]/80 rounded-xl flex items-center justify-center">
                        <span class="text-2xl font-bold text-white">{{ substr($reseller->name, 0, 2) }}</span>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-[#201E1F] mb-1">{{ $reseller->name }}</h1>
                        <p class="text-[#201E1F]/60">Reseller Details & Performance Management</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.resellers.edit', $reseller) }}"
                        class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-3 rounded-lg text-sm font-semibold flex items-center space-x-2 transition-all duration-300 shadow-md hover:shadow-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                        <span>Edit Reseller</span>
                    </a>
                    <a href="{{ route('admin.resellers.index') }}"
                        class="bg-white hover:bg-gray-50 text-[#201E1F]/80 hover:text-[#201E1F] border border-gray-200 px-6 py-3 rounded-lg text-sm font-semibold flex items-center space-x-2 transition-all duration-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <span>Back to Resellers</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Reseller Information -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Personal Information -->
                <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up"
                    style="animation-delay: 0.1s;">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-semibold text-[#201E1F]">Reseller Information</h2>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-[#201E1F]/60">Full Name</label>
                            <p class="text-sm text-[#201E1F] bg-white rounded-lg px-4 py-3 border border-gray-200">
                                {{ $reseller->name }}</p>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-[#201E1F]/60">Email Address</label>
                            <p class="text-sm text-[#201E1F] bg-white rounded-lg px-4 py-3 border border-gray-200">
                                {{ $reseller->email }}</p>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-[#201E1F]/60">Phone Number</label>
                            <p class="text-sm text-[#201E1F] bg-white rounded-lg px-4 py-3 border border-gray-200">
                                {{ $reseller->phone ?: 'Not provided' }}</p>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-[#201E1F]/60">Account Status</label>
                            <div class="flex items-center">
                                @if ($reseller->is_active)
                                    <span
                                        class="inline-flex px-3 py-2 text-xs font-semibold rounded-lg bg-green-50 text-green-700 border border-green-200">
                                        <div class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                                        Active
                                    </span>
                                @else
                                    <span
                                        class="inline-flex px-3 py-2 text-xs font-semibold rounded-lg bg-red-50 text-red-700 border border-red-200">
                                        <div class="w-2 h-2 bg-red-400 rounded-full mr-2"></div>
                                        Inactive
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-[#201E1F]/60">Panel Access</label>
                            <div class="flex items-center">
                                @if ($reseller->reseller_panel_url)
                                    <span
                                        class="inline-flex px-3 py-2 text-xs font-semibold rounded-lg bg-green-50 text-green-700 border border-green-200">
                                        <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Configured
                                    </span>
                                @else
                                    <span
                                        class="inline-flex px-3 py-2 text-xs font-semibold rounded-lg bg-red-50 text-red-700 border border-red-200">
                                        <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Not configured
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-[#201E1F]/60">Member Since</label>
                            <p class="text-sm text-[#201E1F] bg-white rounded-lg px-4 py-3 border border-gray-200">
                                {{ $reseller->created_at->format('M d, Y') }}</p>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-[#201E1F]/60">Last Login</label>
                            <p class="text-sm text-[#201E1F] bg-white rounded-lg px-4 py-3 border border-gray-200">
                                {{ $reseller->last_login_at ? $reseller->last_login_at->format('M d, Y H:i') : 'Never' }}
                            </p>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-[#201E1F]/60">Credit Pack Orders</label>
                            <p class="text-sm text-[#201E1F] bg-white rounded-lg px-4 py-3 border border-gray-200">
                                {{ $creditPackOrders ?? 0 }} purchased</p>
                        </div>
                    </div>
                </div>

                <!-- Performance Statistics -->
                <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up"
                    style="animation-delay: 0.2s;">
                    <div class="flex items-center space-x-3 mb-6">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-[#201E1F]">Performance Statistics</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-xl border border-blue-200">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold text-blue-700">{{ $totalOrders }}</div>
                                    <div class="text-sm text-blue-600">Total Orders</div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-xl border border-green-200">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold text-green-700">{{ $activeOrders }}</div>
                                    <div class="text-sm text-green-600">Active Orders</div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>


            </div>

            <!-- Statistics Sidebar -->
            <div class="lg:col-span-1 space-y-8">
                <!-- Performance Metrics -->
                <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up"
                    style="animation-delay: 0.4s;">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-[#201E1F]">Performance Metrics</h3>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center p-3 bg-white rounded-lg border border-gray-200">
                            <span class="text-sm text-[#201E1F]/60">Total Orders</span>
                            <div class="flex items-center space-x-2">
                                <div
                                    class="w-8 h-8 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg flex items-center justify-center">
                                    <span class="text-sm font-semibold text-blue-600">{{ $totalOrders }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between items-center p-3 bg-white rounded-lg border border-gray-200">
                            <span class="text-sm text-[#201E1F]/60">Active Orders</span>
                            <div class="flex items-center space-x-2">
                                <div
                                    class="w-8 h-8 bg-gradient-to-br from-green-100 to-green-200 rounded-lg flex items-center justify-center">
                                    <span class="text-sm font-semibold text-green-600">{{ $activeOrders }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between items-center p-3 bg-white rounded-lg border border-gray-200">
                            <span class="text-sm text-[#201E1F]/60">Credit Pack Orders</span>
                            <div class="flex items-center space-x-2">
                                <div
                                    class="w-8 h-8 bg-gradient-to-br from-purple-100 to-purple-200 rounded-lg flex items-center justify-center">
                                    <span
                                        class="text-sm font-semibold text-purple-600">{{ $creditPackOrders ?? 0 }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between items-center p-3 bg-white rounded-lg border border-gray-200">
                            <span class="text-sm text-[#201E1F]/60">Pending Orders</span>
                            <div class="flex items-center space-x-2">
                                <div
                                    class="w-8 h-8 bg-gradient-to-br from-yellow-100 to-yellow-200 rounded-lg flex items-center justify-center">
                                    <span class="text-sm font-semibold text-yellow-600">{{ $pendingOrders ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up"
                    style="animation-delay: 0.5s;">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-orange-400 to-orange-600 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-[#201E1F]">Quick Actions</h3>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <a href="{{ route('admin.resellers.edit', $reseller) }}"
                            class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center block">
                            Edit Reseller
                        </a>

                        <form action="{{ route('admin.resellers.toggle-status', $reseller) }}" method="POST"
                            class="w-full">
                            @csrf
                            <button type="submit"
                                class="w-full {{ $reseller->is_active ? 'bg-red-500 hover:bg-red-700' : 'bg-green-500 hover:bg-green-700' }} text-white font-bold py-2 px-4 rounded">
                                {{ $reseller->is_active ? 'Suspend' : 'Activate' }} Account
                            </button>
                        </form>

                        <a href="{{ route('admin.orders.index', ['user_id' => $reseller->id]) }}"
                            class="w-full bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded text-center block">
                            View All Orders
                        </a>

                        <form action="{{ route('admin.resellers.send-password-reset', $reseller) }}" method="POST"
                            class="w-full">
                            @csrf
                            <button type="submit"
                                class="w-full bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                                Send Password Reset
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Recent Orders -->
        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md animate-fade-in-up overflow-hidden"
            style="animation-delay: 0.3s;">
            <div class="px-6 py-5 border-b border-[#D63613]/10">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-orange-400 to-orange-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-[#201E1F]">Recent Orders</h2>
                    </div>
                    <a href="{{ route('admin.orders.index', ['user_id' => $reseller->id]) }}"
                        class="text-[#D63613] hover:text-[#D63613]/80 font-medium text-sm transition-colors duration-200">
                        View All Orders →
                    </a>
                </div>
            </div>

            @if ($reseller->orders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-[#D63613]">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">
                                    Order #</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">
                                    Type</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">
                                    Amount</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">
                                    Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-[#F5F5F5] divide-y divide-gray-200">
                            @foreach ($reseller->orders->take(10) as $order)
                                <tr class="hover:bg-white/50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-[#201E1F]">
                                        <a href="{{ route('admin.orders.show', $order) }}"
                                            class="text-[#D63613] hover:text-[#D63613]/80 transition-colors duration-200">
                                            {{ $order->order_number }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-[#201E1F]">
                                        @if ($order->order_type === 'credit_pack')
                                            <span
                                                class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                                                Credit Pack
                                            </span>
                                        @else
                                            {{ $order->pricingPlan->display_name ?? 'Subscription' }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-[#201E1F]">
                                        ${{ number_format($order->amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($order->status === 'active')
                                            <span
                                                class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-50 text-green-700 border border-green-200">
                                                <div class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                                                Active
                                            </span>
                                        @elseif($order->status === 'pending')
                                            <span
                                                class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-yellow-50 text-yellow-700 border border-yellow-200">
                                                <div class="w-2 h-2 bg-yellow-400 rounded-full mr-2"></div>
                                                Pending
                                            </span>
                                        @elseif($order->status === 'expired')
                                            <span
                                                class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-red-50 text-red-700 border border-red-200">
                                                <div class="w-2 h-2 bg-red-400 rounded-full mr-2"></div>
                                                Expired
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-gray-50 text-gray-700 border border-gray-200">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-[#201E1F]">{{ $order->created_at->format('M d, Y') }}
                                        </div>
                                        <div class="text-xs text-[#201E1F]/60">{{ $order->created_at->format('g:i A') }}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center">
                        <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                        <p class="text-gray-500 text-sm">No orders found for this reseller</p>
                        <p class="text-gray-400 text-xs mt-1">Orders will appear here once created</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
