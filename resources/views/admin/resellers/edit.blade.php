@extends('layouts.admin')

@section('title', 'Edit Reseller - ' . $reseller->name)

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up">
        <div class="lg:flex space-y-4 justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-[#201E1F] mb-2">Edit Reseller</h1>
                <p class="text-[#201E1F]/60">Update reseller information and permissions</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.resellers.show', $reseller) }}" 
                   class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-3 rounded-lg text-sm font-semibold flex items-center space-x-2 transition-all duration-300 shadow-md hover:shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    <span>View Reseller</span>
                </a>
                <a href="{{ route('admin.resellers.index') }}" 
                   class="bg-white hover:bg-gray-50 text-[#201E1F]/80 hover:text-[#201E1F] border border-gray-200 px-6 py-3 rounded-lg text-sm font-semibold flex items-center space-x-2 transition-all duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Back to Resellers</span>
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.resellers.update', $reseller) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')
        
        <!-- Basic Information -->
        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md animate-fade-in-up" style="animation-delay: 0.1s;">
            <div class="px-6 py-5 border-b border-[#D63613]/10">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-[#201E1F]">Basic Information</h3>
                </div>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-[#201E1F] mb-2">Full Name</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $reseller->name) }}"
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] placeholder-[#201E1F]/40 transition-all duration-200 @error('name') border-red-500 @enderror"
                               required>
                        @error('name')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-[#201E1F] mb-2">Email Address</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $reseller->email) }}"
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] placeholder-[#201E1F]/40 transition-all duration-200 @error('email') border-red-500 @enderror"
                               required>
                        @error('email')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-[#201E1F] mb-2">Phone Number</label>
                        <input type="text" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone', $reseller->phone) }}"
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] placeholder-[#201E1F]/40 transition-all duration-200 @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="is_active" class="block text-sm font-semibold text-[#201E1F] mb-2">Account Status</label>
                        <select id="is_active" 
                                name="is_active" 
                                class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] transition-all duration-200">
                            <option value="1" {{ old('is_active', $reseller->is_active) == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active', $reseller->is_active) == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('is_active')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Password Change Section -->
        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md animate-fade-in-up" style="animation-delay: 0.2s;">
            <div class="px-6 py-5 border-b border-[#D63613]/10">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m0 0a2 2 0 012 2 2 2 0 00-2-2m-2-2H9m10 0a2 2 0 00-2-2M7 7a2 2 0 012-2M7 7a2 2 0 00-2 2m0 0a2 2 0 002 2m0 0a2 2 0 002-2M7 7v3a2 2 0 002 2"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-[#201E1F]">Change Password</h3>
                        <p class="text-sm text-[#201E1F]/60">Leave blank to keep current password</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- New Password -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-[#201E1F] mb-2">New Password</label>
                        <input type="password" 
                               id="password" 
                               name="password"
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] placeholder-[#201E1F]/40 transition-all duration-200 @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-[#201E1F] mb-2">Confirm New Password</label>
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation"
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] placeholder-[#201E1F]/40 transition-all duration-200">
                    </div>
                </div>
            </div>
        </div>

        <!-- Suspension Section -->
        @if(!$reseller->is_active || $reseller->suspended_at)
        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md animate-fade-in-up" style="animation-delay: 0.3s;">
            <div class="px-6 py-5 border-b border-[#D63613]/10">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-red-400 to-red-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.868-.833-2.598 0L4.268 18.5C3.498 20.333 4.46 22 6 22z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-[#201E1F]">Suspension Details</h3>
                </div>
            </div>
            
            <div class="p-6">
                <div>
                    <label for="suspension_reason" class="block text-sm font-semibold text-[#201E1F] mb-2">Suspension Reason</label>
                    <textarea id="suspension_reason" 
                              name="suspension_reason" 
                              rows="3"
                              class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] placeholder-[#201E1F]/40 transition-all duration-200 resize-none"
                              placeholder="Reason for suspension...">{{ old('suspension_reason', $reseller->suspension_reason) }}</textarea>
                    @error('suspension_reason')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                @if($reseller->suspended_at)
                <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <p class="text-sm text-yellow-800">
                        <strong>Suspended on:</strong> {{ $reseller->suspended_at->format('M d, Y H:i') }}
                    </p>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Panel Credentials -->
        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md animate-fade-in-up" style="animation-delay: 0.4s;">
            <div class="px-6 py-5 border-b border-[#D63613]/10">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-[#201E1F]">Reseller Panel Credentials</h3>
                        <p class="text-sm text-[#201E1F]/60">Configure the external IPTV panel access for this reseller</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6 space-y-6">
                <!-- Panel URL -->
                <div>
                    <label for="reseller_panel_url" class="block text-sm font-semibold text-[#201E1F] mb-2">Panel URL</label>
                    <input type="url"
                           id="reseller_panel_url"
                           name="reseller_panel_url"
                           value="{{ old('reseller_panel_url', $reseller->reseller_panel_url) }}"
                           class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] placeholder-[#201E1F]/40 transition-all duration-200 @error('reseller_panel_url') border-red-500 @enderror"
                           placeholder="https://panel.example.com">
                    @error('reseller_panel_url')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Panel Username -->
                    <div>
                        <label for="reseller_panel_username" class="block text-sm font-semibold text-[#201E1F] mb-2">Panel Username</label>
                        <input type="text"
                               id="reseller_panel_username"
                               name="reseller_panel_username"
                               value="{{ old('reseller_panel_username', $reseller->reseller_panel_username) }}"
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] placeholder-[#201E1F]/40 transition-all duration-200 @error('reseller_panel_username') border-red-500 @enderror"
                               placeholder="reseller_username">
                        @error('reseller_panel_username')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Panel Password -->
                    <div>
                        <label for="reseller_panel_password" class="block text-sm font-semibold text-[#201E1F] mb-2">Panel Password</label>
                        <input type="password"
                               id="reseller_panel_password"
                               name="reseller_panel_password"
                               value="{{ old('reseller_panel_password', $reseller->reseller_panel_password) }}"
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] placeholder-[#201E1F]/40 transition-all duration-200 @error('reseller_panel_password') border-red-500 @enderror"
                               placeholder="Enter panel password">
                        @error('reseller_panel_password')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Panel Status -->
                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <h4 class="text-sm font-semibold text-[#201E1F] mb-3">Current Panel Status</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div class="flex items-center">
                            @if($reseller->reseller_panel_url)
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                <span class="text-green-700 font-medium">URL Configured</span>
                            @else
                                <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                                <span class="text-red-700 font-medium">URL Not Set</span>
                            @endif
                        </div>
                        <div class="flex items-center">
                            @if($reseller->reseller_panel_username)
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                <span class="text-green-700 font-medium">Username Set</span>
                            @else
                                <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                                <span class="text-red-700 font-medium">Username Not Set</span>
                            @endif
                        </div>
                        <div class="flex items-center">
                            @if($reseller->reseller_panel_password)
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                <span class="text-green-700 font-medium">Password Set</span>
                            @else
                                <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                                <span class="text-red-700 font-medium">Password Not Set</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Credit Management -->
        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md animate-fade-in-up" style="animation-delay: 0.5s;">
            <div class="px-6 py-5 border-b border-[#D63613]/10">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-[#201E1F]">Credit Management</h3>
                        <p class="text-sm text-[#201E1F]/60">Manage the reseller's available credits for creating client accounts</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Available Credits -->
                    <div>
                        <label for="available_credits" class="block text-sm font-semibold text-[#201E1F] mb-2">Available Credits</label>
                        <input type="number"
                               id="available_credits"
                               name="available_credits"
                               value="{{ old('available_credits', $reseller->available_credits ?? 0) }}"
                               min="0"
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] placeholder-[#201E1F]/40 transition-all duration-200 @error('available_credits') border-red-500 @enderror"
                               placeholder="0">
                        @error('available_credits')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-sm text-[#201E1F]/50">Credits available for creating client accounts</p>
                    </div>

                    <!-- Credit Pack Orders Summary -->
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-lg border border-blue-200">
                        <h4 class="text-sm font-semibold text-blue-900 mb-3">Credit Pack Orders</h4>
                        @php
                            $completedOrders = $reseller->orders()->where('order_type', 'credit_pack')->where('status', 'active')->with('resellerCreditPack')->get();
                            $totalPurchased = $completedOrders->sum(function($order) { return $order->resellerCreditPack->credits_amount ?? 0; });
                        @endphp
                        <div class="text-sm text-blue-800 space-y-2">
                            <div class="flex justify-between">
                                <span>Completed Orders:</span>
                                <span class="font-semibold">{{ $completedOrders->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Total Credits Purchased:</span>
                                <span class="font-semibold">{{ number_format($totalPurchased) }}</span>
                            </div>
                            <div class="flex justify-between border-t border-blue-200 pt-2">
                                <span>Currently Available:</span>
                                <span class="font-semibold text-[#D63613]">{{ number_format($reseller->available_credits ?? 0) }}</span>
                            </div>
                        </div>
                        @if($totalPurchased > 0 && ($reseller->available_credits ?? 0) != $totalPurchased)
                            <div class="mt-3 p-2 bg-yellow-100 border border-yellow-300 rounded text-xs text-yellow-800">
                                <strong>⚠️ Note:</strong> Available credits don't match purchased credits. Consider syncing.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Reseller Permissions -->
        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md animate-fade-in-up" style="animation-delay: 0.6s;">
            <div class="px-6 py-5 border-b border-[#D63613]/10">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-[#201E1F]">Reseller Permissions</h3>
                </div>
            </div>
            
            <div class="p-6">
                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="can_create_clients" 
                                   name="can_create_clients" 
                                   value="1"
                                   {{ old('can_create_clients', $reseller->can_create_clients ?? '1') ? 'checked' : '' }}
                                   class="h-4 w-4 text-[#D63613] focus:ring-[#D63613] border-gray-300 rounded bg-white">
                            <label for="can_create_clients" class="ml-3 block text-sm font-medium text-[#201E1F]">
                                Can create client accounts
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="can_manage_orders" 
                                   name="can_manage_orders" 
                                   value="1"
                                   {{ old('can_manage_orders', $reseller->can_manage_orders ?? '1') ? 'checked' : '' }}
                                   class="h-4 w-4 text-[#D63613] focus:ring-[#D63613] border-gray-300 rounded bg-white">
                            <label for="can_manage_orders" class="ml-3 block text-sm font-medium text-[#201E1F]">
                                Can manage client orders
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="can_view_reports" 
                                   name="can_view_reports" 
                                   value="1"
                                   {{ old('can_view_reports', $reseller->can_view_reports ?? '1') ? 'checked' : '' }}
                                   class="h-4 w-4 text-[#D63613] focus:ring-[#D63613] border-gray-300 rounded bg-white">
                            <label for="can_view_reports" class="ml-3 block text-sm font-medium text-[#201E1F]">
                                Can view sales reports
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="can_process_payments" 
                                   name="can_process_payments" 
                                   value="1"
                                   {{ old('can_process_payments', $reseller->can_process_payments ?? '0') ? 'checked' : '' }}
                                   class="h-4 w-4 text-[#D63613] focus:ring-[#D63613] border-gray-300 rounded bg-white">
                            <label for="can_process_payments" class="ml-3 block text-sm font-medium text-[#201E1F]">
                                Can process manual payments
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin Notes -->
        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md animate-fade-in-up" style="animation-delay: 0.7s;">
            <div class="px-6 py-5 border-b border-[#D63613]/10">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-gray-400 to-gray-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-[#201E1F]">Admin Notes</h3>
                </div>
            </div>
            
            <div class="p-6">
                <label for="notes" class="block text-sm font-semibold text-[#201E1F] mb-2">Internal Notes</label>
                <textarea id="notes"
                          name="notes"
                          rows="3"
                          class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] placeholder-[#201E1F]/40 transition-all duration-200 resize-none"
                          placeholder="Internal notes about this reseller...">{{ old('notes', $reseller->notes) }}</textarea>
                @error('notes')
                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Account Information -->
        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md animate-fade-in-up" style="animation-delay: 0.8s;">
            <div class="px-6 py-5 border-b border-[#D63613]/10">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-teal-400 to-teal-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-[#201E1F]">Account Information</h3>
                </div>
            </div>
            
            <div class="p-6">
                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div class="flex justify-between items-center">
                            <span class="text-[#201E1F]/60 font-medium">Member Since:</span>
                            <span class="text-[#201E1F] font-semibold">{{ $reseller->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-[#201E1F]/60 font-medium">Last Login:</span>
                            <span class="text-[#201E1F] font-semibold">{{ $reseller->last_login_at ? $reseller->last_login_at->format('M d, Y H:i') : 'Never' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-[#201E1F]/60 font-medium">Total Orders:</span>
                            <span class="text-[#D63613] font-semibold">{{ $reseller->orders->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end space-x-4 animate-fade-in-up" style="animation-delay: 0.9s;">
            <a href="{{ route('admin.resellers.show', $reseller) }}" 
               class="px-6 py-3 bg-white border border-gray-200 text-[#201E1F] font-semibold rounded-lg hover:bg-gray-50 transition-all duration-300">
                Cancel
            </a>
            <button type="submit" 
                    class="px-6 py-3 bg-gradient-to-r from-[#D63613] to-[#D63613]/80 text-white font-semibold rounded-lg hover:from-[#D63613]/90 hover:to-[#D63613]/70 transition-all duration-300 shadow-md hover:shadow-lg">
                Update Reseller
            </button>
        </div>
    </form>

    <!-- Danger Zone -->
    <div class="bg-gradient-to-r from-red-50 to-red-100 border border-red-200 rounded-xl shadow-md p-6 animate-fade-in-up" style="animation-delay: 1s;">
        <div class="flex items-center space-x-3 mb-4">
            <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.868-.833-2.598 0L4.268 18.5C3.498 20.333 4.46 22 6 22z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-xl font-semibold text-red-900">Danger Zone</h3>
                <p class="text-sm text-red-700">These actions are irreversible. Please be certain before proceeding.</p>
            </div>
        </div>
        
        <div class="bg-white p-4 rounded-lg border border-red-200">
            <div class="flex flex-wrap gap-4">
                @if($reseller->is_active)
                <form action="{{ route('admin.resellers.suspend', $reseller) }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white font-semibold px-6 py-3 rounded-lg transition-all duration-300 shadow-md hover:shadow-lg">
                        Suspend Reseller
                    </button>
                </form>
                @else
                <form action="{{ route('admin.resellers.reactivate', $reseller) }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold px-6 py-3 rounded-lg transition-all duration-300 shadow-md hover:shadow-lg">
                        Reactivate Reseller
                    </button>
                </form>
                @endif
                
                <form action="{{ route('admin.resellers.destroy', $reseller) }}" method="POST" 
                      onsubmit="return confirm('Are you sure you want to delete this reseller? This action cannot be undone and will also delete all associated data.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold px-6 py-3 rounded-lg transition-all duration-300 shadow-md hover:shadow-lg">
                        Delete Reseller
                    </button>
                </form>
            </div>
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

.animate-fade-in-up {
    animation: fade-in-up 0.6s ease-out forwards;
}
</style>
@endsection