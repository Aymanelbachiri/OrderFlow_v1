@extends('layouts.admin')

@section('title', 'View Admin')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-[#201E1F] mb-2">{{ $admin->name }}</h1>
                <p class="text-[#201E1F]/60">{{ $admin->email }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.super.admins.edit', $admin) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-semibold transition-all duration-300">
                    Edit Admin
                </a>
                <a href="{{ route('admin.super.admins.index') }}" 
                   class="border border-gray-200 px-6 py-3 rounded-lg text-[#201E1F] hover:bg-gray-50 text-sm font-semibold transition-all duration-300">
                    Back to Admins
                </a>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-[#201E1F]/60 mb-1">Total Orders</p>
                    <p class="text-3xl font-bold text-[#201E1F]">{{ number_format($stats['total_orders']) }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-[#201E1F]/60 mb-1">Active Orders</p>
                    <p class="text-3xl font-bold text-[#201E1F]">{{ number_format($stats['active_orders']) }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-[#201E1F]/60 mb-1">Total Revenue</p>
                    <p class="text-3xl font-bold text-[#201E1F]">${{ number_format($stats['total_revenue'], 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-[#201E1F]/60 mb-1">Sources</p>
                    <p class="text-3xl font-bold text-[#201E1F]">{{ number_format($stats['sources_count']) }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Details -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Permissions -->
        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6">
            <h2 class="text-xl font-semibold text-[#201E1F] mb-4">Permissions</h2>
            @php
                $permissions = $admin->adminPermissions;
            @endphp
            <div class="space-y-2">
                <div class="flex items-center justify-between py-2 border-b border-gray-200">
                    <span class="text-sm text-[#201E1F]">Manage Sources</span>
                    <span class="px-2 py-1 text-xs rounded-full {{ $permissions->can_manage_sources ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $permissions->can_manage_sources ? 'Yes' : 'No' }}
                    </span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-gray-200">
                    <span class="text-sm text-[#201E1F]">Create Custom Products</span>
                    <span class="px-2 py-1 text-xs rounded-full {{ $permissions->can_create_custom_products ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $permissions->can_create_custom_products ? 'Yes' : 'No' }}
                    </span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-gray-200">
                    <span class="text-sm text-[#201E1F]">Send Renewal Emails</span>
                    <span class="px-2 py-1 text-xs rounded-full {{ $permissions->can_send_renewal_emails ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $permissions->can_send_renewal_emails ? 'Yes' : 'No' }}
                    </span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-gray-200">
                    <span class="text-sm text-[#201E1F]">Manage Pricing Plans</span>
                    <span class="px-2 py-1 text-xs rounded-full {{ $permissions->can_manage_pricing_plans ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $permissions->can_manage_pricing_plans ? 'Yes' : 'No' }}
                    </span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-gray-200">
                    <span class="text-sm text-[#201E1F]">Manage Reseller Credit Packs</span>
                    <span class="px-2 py-1 text-xs rounded-full {{ $permissions->can_manage_reseller_credit_packs ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $permissions->can_manage_reseller_credit_packs ? 'Yes' : 'No' }}
                    </span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-gray-200">
                    <span class="text-sm text-[#201E1F]">Manage Payment Config</span>
                    <span class="px-2 py-1 text-xs rounded-full {{ $permissions->can_manage_payment_config ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $permissions->can_manage_payment_config ? 'Yes' : 'No' }}
                    </span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-gray-200">
                    <span class="text-sm text-[#201E1F]">View Orders</span>
                    <span class="px-2 py-1 text-xs rounded-full {{ $permissions->can_view_orders ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $permissions->can_view_orders ? 'Yes' : 'No' }}
                    </span>
                </div>
                <div class="flex items-center justify-between py-2">
                    <span class="text-sm text-[#201E1F]">Manage Orders</span>
                    <span class="px-2 py-1 text-xs rounded-full {{ $permissions->can_manage_orders ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $permissions->can_manage_orders ? 'Yes' : 'No' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Limits -->
        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6">
            <h2 class="text-xl font-semibold text-[#201E1F] mb-4">Resource Limits</h2>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-[#201E1F]/60 mb-1">Max Sources</p>
                    <p class="text-lg font-semibold text-[#201E1F]">{{ $permissions->max_sources ?? 'Unlimited' }}</p>
                </div>
                <div>
                    <p class="text-sm text-[#201E1F]/60 mb-1">Max Custom Products</p>
                    <p class="text-lg font-semibold text-[#201E1F]">{{ $permissions->max_custom_products ?? 'Unlimited' }}</p>
                </div>
                <div>
                    <p class="text-sm text-[#201E1F]/60 mb-1">Max Reseller Credit Packs</p>
                    <p class="text-lg font-semibold text-[#201E1F]">{{ $permissions->max_reseller_credit_packs ?? 'Unlimited' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6">
        <h2 class="text-xl font-semibold text-[#201E1F] mb-4">Recent Orders</h2>
        @if($admin->adminOrders->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order #</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($admin->adminOrders as $order)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#201E1F]">{{ $order->order_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#201E1F]">{{ $order->user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#201E1F]">${{ number_format($order->amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full bg-{{ $order->status === 'active' ? 'green' : ($order->status === 'pending' ? 'yellow' : 'gray') }}-100 text-{{ $order->status === 'active' ? 'green' : ($order->status === 'pending' ? 'yellow' : 'gray') }}-800">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#201E1F]/60">{{ $order->created_at->format('M d, Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-[#201E1F]/60">No orders found for this admin.</p>
        @endif
    </div>
</div>
@endsection

