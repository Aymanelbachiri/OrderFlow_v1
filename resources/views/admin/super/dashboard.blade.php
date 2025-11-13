@extends('layouts.admin')

@section('title', 'Super Admin Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-[#201E1F] mb-2">Super Admin Dashboard</h1>
                <p class="text-[#201E1F]/60">Overview of all admins and system-wide statistics</p>
            </div>
            <a href="{{ route('admin.super.admins.create') }}" 
               class="bg-gradient-to-r from-[#D63613] to-[#D63613]/80 hover:from-[#D63613]/90 hover:to-[#D63613] text-white px-6 py-3 rounded-lg text-sm font-semibold flex items-center space-x-2 transition-all duration-300 shadow-md hover:shadow-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span>Add New Admin</span>
            </a>
        </div>
    </div>

    <!-- System Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-[#201E1F]/60 mb-1">Total Orders</p>
                    <p class="text-3xl font-bold text-[#201E1F]">{{ number_format($totalOrders) }}</p>
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
                    <p class="text-sm font-medium text-[#201E1F]/60 mb-1">Total Revenue</p>
                    <p class="text-3xl font-bold text-[#201E1F]">${{ number_format($totalRevenue, 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-[#201E1F]/60 mb-1">Total Admins</p>
                    <p class="text-3xl font-bold text-[#201E1F]">{{ $admins->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Admins List -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6">
        <h2 class="text-2xl font-bold text-[#201E1F] mb-6">All Admins</h2>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Orders</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sources</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Products</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($admins as $admin)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-[#D63613] to-[#D63613]/80 flex items-center justify-center text-white font-semibold">
                                        {{ strtoupper(substr($admin->name, 0, 2)) }}
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-[#201E1F]">{{ $admin->name }}</div>
                                    <div class="text-sm text-[#201E1F]/60">{{ $admin->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($admin->is_super_admin)
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">Super Admin</span>
                            @else
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Admin</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-[#201E1F]">
                            {{ number_format($admin->admin_orders_count) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-[#201E1F]">
                            {{ number_format($admin->admin_sources_count) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-[#201E1F]">
                            {{ number_format($admin->admin_custom_products_count) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($admin->is_active)
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                            @else
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.super.view-admin', $admin) }}" class="text-[#D63613] hover:text-[#D63613]/80 mr-4">View</a>
                            <a href="{{ route('admin.super.admins.edit', $admin) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-[#201E1F]/60">No admins found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

