@extends('layouts.admin')

@section('title', 'Client Details - ' . $client->name)

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up">
        <div class="lg:flex space-y-4 justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-gradient-to-br from-[#D63613] to-[#D63613]/80 rounded-xl flex items-center justify-center">
                    <span class="text-2xl font-bold text-white">{{ substr($client->name, 0, 2) }}</span>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-[#201E1F] mb-1">{{ $client->name }}</h1>
                    <p class="text-[#201E1F]/60">Client Details & Account Management</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.clients.edit', $client) }}" 
                   class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-3 rounded-lg text-sm font-semibold flex items-center space-x-2 transition-all duration-300 shadow-md hover:shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <span>Edit Client</span>
                </a>
                <a href="{{ route('admin.clients.index') }}" 
                   class=" text-[#201E1F]/80 hover:text-[#201E1F] border border-gray-200 px-6 py-3 rounded-lg text-sm font-semibold flex items-center space-x-2 transition-all duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Back to Clients</span>
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Client Information -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Personal Information -->
            <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up" style="animation-delay: 0.1s;">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-[#201E1F]">Client Information</h2>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Full Name</label>
                        <p class="text-sm text-[#201E1F]  rounded-lg px-4 py-3 border border-gray-200">{{ $client->name }}</p>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Email Address</label>
                        <p class="text-sm text-[#201E1F]  rounded-lg px-4 py-3 border border-gray-200">{{ $client->email }}</p>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Phone Number</label>
                        <p class="text-sm text-[#201E1F]  rounded-lg px-4 py-3 border border-gray-200">{{ $client->phone ?: 'Not provided' }}</p>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Account Status</label>
                        <div class="flex items-center">
                            @if($client->is_active && !$client->suspended_at)
                                <span class="inline-flex px-3 py-2 text-xs font-semibold rounded-lg bg-green-50 text-green-700 border border-green-200">
                                    <div class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                                    Active
                                </span>
                            @else
                                <span class="inline-flex px-3 py-2 text-xs font-semibold rounded-lg bg-red-50 text-red-700 border border-red-200">
                                    <div class="w-2 h-2 bg-red-400 rounded-full mr-2"></div>
                                    Inactive
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    @if(!$client->isClient())
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Email Verified</label>
                        <div class="flex items-center">
                            @if($client->email_verified_at)
                                <span class="inline-flex px-3 py-2 text-xs font-semibold rounded-lg bg-green-50 text-green-700 border border-green-200">
                                    <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Verified
                                </span>
                            @else
                                <span class="inline-flex px-3 py-2 text-xs font-semibold rounded-lg bg-yellow-50 text-yellow-700 border border-yellow-200">
                                    <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    Unverified
                                </span>
                            @endif
                        </div>
                    </div>
                    @endif
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Member Since</label>
                        <p class="text-sm text-[#201E1F]  rounded-lg px-4 py-3 border border-gray-200">{{ $client->created_at->format('M d, Y') }}</p>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Last Login</label>
                        <p class="text-sm text-[#201E1F]  rounded-lg px-4 py-3 border border-gray-200">
                            {{ $client->last_login_at ? $client->last_login_at->format('M d, Y H:i') : 'Never' }}
                        </p>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Last Login IP</label>
                        <p class="text-sm text-[#201E1F] rounded-lg px-4 py-3 border border-gray-200">{{ $client->last_login_ip ?: 'N/A' }}</p>
                    </div>
                </div>

                @if($client->notes)
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <label class="block text-sm font-medium text-[#201E1F]/60 mb-3">Admin Notes</label>
                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                        <p class="text-sm text-[#201E1F]">{{ $client->notes }}</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Orders History -->
            <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md animate-fade-in-up overflow-hidden" style="animation-delay: 0.2s;">
                <div class="px-6 py-5 border-b border-[#D63613]/10">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-semibold text-[#201E1F]">Orders History</h2>
                        </div>
                        <a href="{{ route('admin.orders.create', ['user_id' => $client->id]) }}" 
                           class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-4 py-2 rounded-lg text-sm font-semibold flex items-center space-x-2 transition-all duration-300 shadow-md hover:shadow-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span>Create Order</span>
                        </a>
                    </div>
                </div>
                
                @if($client->orders->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-[#D63613]">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-[#f5f5f5] uppercase tracking-wider">Order #</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-[#f5f5f5] uppercase tracking-wider">Plan</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-[#f5f5f5] uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-[#f5f5f5] uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-[#f5f5f5] uppercase tracking-wider">Created</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-[#f5f5f5] uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-[#F5F5F5] divide-y divide-gray-200">
                                @foreach($client->orders->take(10) as $order)
                                <tr class="hover:bg-white/50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-[#201E1F]">
                                        {{ $order->order_number }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-[#201E1F]/80">
                                        {{ $order->pricingPlan->display_name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-[#201E1F]">
                                        ${{ number_format($order->amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($order->status === 'active')
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-50 text-green-700 border border-green-200">
                                                <div class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                                                Active
                                            </span>
                                        @elseif($order->status === 'pending')
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-yellow-50 text-yellow-700 border border-yellow-200">
                                                <div class="w-2 h-2 bg-yellow-400 rounded-full mr-2"></div>
                                                Pending
                                            </span>
                                        @elseif($order->status === 'expired')
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-red-50 text-red-700 border border-red-200">
                                                <div class="w-2 h-2 bg-red-400 rounded-full mr-2"></div>
                                                Expired
                                            </span>
                                        @else
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-gray-50 text-gray-700 border border-gray-200">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-[#201E1F]">{{ $order->created_at->format('M d, Y') }}</div>
                                        <div class="text-xs text-[#201E1F]/60">{{ $order->created_at->format('g:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('admin.orders.show', $order) }}" 
                                           class="text-blue-600 hover:text-blue-700 font-medium transition-colors duration-200 flex items-center space-x-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            <span class="text-xs">View</span>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if($client->orders->count() > 10)
                    <div class="px-6 py-4 bg-white border-t border-gray-200 text-center">
                        <a href="{{ route('admin.orders.index', ['user_id' => $client->id]) }}" 
                           class="text-[#D63613] hover:text-[#D63613]/80 font-medium transition-colors duration-200">
                            View all {{ $client->orders->count() }} orders →
                        </a>
                    </div>
                    @endif
                @else
                    <div class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <p class="text-gray-500 text-sm">No orders found for this client</p>
                            <p class="text-gray-400 text-xs mt-1">Orders will appear here once created</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Statistics Sidebar -->
        <div class="lg:col-span-1 space-y-8">
            <!-- Quick Stats -->
            <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up" style="animation-delay: 0.3s;">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-[#201E1F]">Quick Statistics</h3>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-3  rounded-lg border border-gray-200">
                        <span class="text-sm text-[#201E1F]/60">Total Orders</span>
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg flex items-center justify-center">
                                <span class="text-sm font-semibold text-blue-600">{{ $client->orders->count() }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center p-3  rounded-lg border border-gray-200">
                        <span class="text-sm text-[#201E1F]/60">Active Orders</span>
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-gradient-to-br from-green-100 to-green-200 rounded-lg flex items-center justify-center">
                                <span class="text-sm font-semibold text-green-600">{{ $client->orders->where('status', 'active')->count() }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center p-3 rounded-lg border border-gray-200">
                        <span class="text-sm text-[#201E1F]/60">Total Spent</span>
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-gradient-to-br from-purple-100 to-purple-200 rounded-lg flex items-center justify-center">
                                <span class="text-xs font-semibold text-purple-600">${{ number_format($client->orders->where('status', 'active')->sum('amount'), 0) }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center p-3 rounded-lg border border-gray-200">
                        <span class="text-sm text-[#201E1F]/60">Pending Orders</span>
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-gradient-to-br from-yellow-100 to-yellow-200 rounded-lg flex items-center justify-center">
                                <span class="text-sm font-semibold text-yellow-600">{{ $client->orders->where('status', 'pending')->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up" style="animation-delay: 0.4s;">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-orange-400 to-orange-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-[#201E1F]">Quick Actions</h3>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <a href="{{ route('admin.clients.edit', $client) }}" 
                       class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-3 px-4 rounded-lg text-center flex items-center justify-center space-x-2 transition-all duration-300 shadow-md hover:shadow-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        <span>Edit Client</span>
                    </a>
                    
                    <a href="{{ route('admin.orders.create', ['user_id' => $client->id]) }}" 
                       class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold py-3 px-4 rounded-lg text-center flex items-center justify-center space-x-2 transition-all duration-300 shadow-md hover:shadow-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span>Create Order</span>
                    </a>
                    
                    @if(!$client->isClient() && !$client->email_verified_at)
                    <form action="{{ route('admin.clients.verify-email', $client) }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white font-semibold py-3 px-4 rounded-lg text-center flex items-center justify-center space-x-2 transition-all duration-300 shadow-md hover:shadow-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Verify Email</span>
                        </button>
                    </form>
                    @endif
                    
                    <form action="{{ route('admin.clients.toggle-status', $client) }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" 
                                class="w-full {{ $client->is_active ? 'bg-red-500 hover:bg-red-700' : 'bg-green-500 hover:bg-green-700' }} text-white font-bold py-2 px-4 rounded">
                            {{ $client->is_active ? 'Deactivate' : 'Activate' }} Account
                        </button>
                    </form>
                    
                    @if(!$client->isClient())
                    <form action="{{ route('admin.clients.send-password-reset', $client) }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" 
                                class="w-full bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                            Send Password Reset
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
