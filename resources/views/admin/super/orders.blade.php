@extends('layouts.admin')

@section('title', 'All Orders - Super Admin')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-[#201E1F] mb-2">All Orders</h1>
                <p class="text-[#201E1F]/60">View all orders from all admins</p>
            </div>
            <a href="{{ route('admin.super.dashboard') }}" 
               class="border border-gray-200 px-6 py-3 rounded-lg text-[#201E1F] hover:bg-gray-50 text-sm font-semibold transition-all duration-300">
                Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6">
        <form method="GET" action="{{ route('admin.super.orders') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-[#201E1F]/60 mb-2">Filter by Admin</label>
                <select name="admin_id" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613]">
                    <option value="">All Admins</option>
                    @foreach($admins as $admin)
                        <option value="{{ $admin->id }}" {{ request('admin_id') == $admin->id ? 'selected' : '' }}>
                            {{ $admin->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-6 py-3 bg-[#D63613] text-white rounded-lg font-semibold hover:bg-[#D63613]/90 transition-all duration-300">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Admin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($orders as $order)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-[#201E1F]">{{ $order->order_number }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-[#201E1F]">
                            {{ $order->admin ? $order->admin->name : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-[#201E1F]">{{ $order->user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-[#201E1F]">${{ number_format($order->amount, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full bg-{{ $order->status === 'active' ? 'green' : ($order->status === 'pending' ? 'yellow' : 'gray') }}-100 text-{{ $order->status === 'active' ? 'green' : ($order->status === 'pending' ? 'yellow' : 'gray') }}-800">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-[#201E1F]/60">{{ $order->created_at->format('M d, Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-[#201E1F]/60">No orders found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection

