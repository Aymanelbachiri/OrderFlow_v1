@extends('layouts.admin')

@section('title', 'Create New Admin')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-[#201E1F] mb-2">Create New Admin</h1>
                <p class="text-[#201E1F]/60">Add a new admin user with custom permissions</p>
            </div>
            <a href="{{ route('admin.super.admins.index') }}" 
               class="text-[#201E1F]/80 hover:text-[#201E1F] border border-gray-200 px-6 py-3 rounded-lg text-sm font-semibold flex items-center space-x-2 transition-all duration-300">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Back to Admins</span>
            </a>
        </div>
    </div>

    <!-- Main Form -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6">
        <form action="{{ route('admin.super.admins.store') }}" method="POST">
            @csrf
            
            <div class="space-y-6">
                <!-- Basic Information -->
                <div>
                    <h2 class="text-xl font-semibold text-[#201E1F] mb-4">Basic Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-[#201E1F]/60 mb-2">Name *</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" 
                                   class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent @error('name') border-red-500 @enderror"
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-[#201E1F]/60 mb-2">Email *</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" 
                                   class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent @error('email') border-red-500 @enderror"
                                   required>
                            @error('email')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-[#201E1F]/60 mb-2">Password *</label>
                            <input type="password" id="password" name="password" 
                                   class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent @error('password') border-red-500 @enderror"
                                   required>
                            @error('password')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-[#201E1F]/60 mb-2">Confirm Password *</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" 
                                   class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent"
                                   required>
                        </div>
                    </div>
                </div>

                <!-- Admin Type -->
                <div>
                    <h2 class="text-xl font-semibold text-[#201E1F] mb-4">Admin Type</h2>
                    <div class="flex items-center space-x-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_super_admin" value="1" {{ old('is_super_admin') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-[#D63613] focus:ring-[#D63613]">
                            <span class="ml-2 text-sm text-[#201E1F]">Super Admin (Full Access)</span>
                        </label>
                    </div>
                    <p class="mt-2 text-sm text-[#201E1F]/60">Super admins have full access to all features and can manage other admins.</p>
                </div>

                <!-- Permissions -->
                <div>
                    <h2 class="text-xl font-semibold text-[#201E1F] mb-4">Permissions</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="can_manage_sources" value="1" {{ old('can_manage_sources') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-[#D63613] focus:ring-[#D63613]">
                            <span class="ml-2 text-sm text-[#201E1F]">Manage Sources</span>
                        </label>

                        <label class="flex items-center">
                            <input type="checkbox" name="can_create_custom_products" value="1" {{ old('can_create_custom_products') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-[#D63613] focus:ring-[#D63613]">
                            <span class="ml-2 text-sm text-[#201E1F]">Create Custom Products</span>
                        </label>

                        <label class="flex items-center">
                            <input type="checkbox" name="can_send_renewal_emails" value="1" {{ old('can_send_renewal_emails') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-[#D63613] focus:ring-[#D63613]">
                            <span class="ml-2 text-sm text-[#201E1F]">Send Renewal Emails</span>
                        </label>

                        <label class="flex items-center">
                            <input type="checkbox" name="can_manage_pricing_plans" value="1" {{ old('can_manage_pricing_plans', true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-[#D63613] focus:ring-[#D63613]">
                            <span class="ml-2 text-sm text-[#201E1F]">Manage Pricing Plans</span>
                        </label>

                        <label class="flex items-center">
                            <input type="checkbox" name="can_manage_reseller_credit_packs" value="1" {{ old('can_manage_reseller_credit_packs') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-[#D63613] focus:ring-[#D63613]">
                            <span class="ml-2 text-sm text-[#201E1F]">Manage Reseller Credit Packs</span>
                        </label>

                        <label class="flex items-center">
                            <input type="checkbox" name="can_manage_payment_config" value="1" {{ old('can_manage_payment_config', true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-[#D63613] focus:ring-[#D63613]">
                            <span class="ml-2 text-sm text-[#201E1F]">Manage Payment Config</span>
                        </label>

                        <label class="flex items-center">
                            <input type="checkbox" name="can_view_orders" value="1" {{ old('can_view_orders', true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-[#D63613] focus:ring-[#D63613]">
                            <span class="ml-2 text-sm text-[#201E1F]">View Orders</span>
                        </label>

                        <label class="flex items-center">
                            <input type="checkbox" name="can_manage_orders" value="1" {{ old('can_manage_orders', true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-[#D63613] focus:ring-[#D63613]">
                            <span class="ml-2 text-sm text-[#201E1F]">Manage Orders</span>
                        </label>
                    </div>
                </div>

                <!-- Limits -->
                <div>
                    <h2 class="text-xl font-semibold text-[#201E1F] mb-4">Resource Limits (Optional)</h2>
                    <p class="text-sm text-[#201E1F]/60 mb-4">Leave empty for unlimited. Super admins have unlimited resources.</p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="max_sources" class="block text-sm font-medium text-[#201E1F]/60 mb-2">Max Sources</label>
                            <input type="number" id="max_sources" name="max_sources" value="{{ old('max_sources') }}" min="0"
                                   class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent"
                                   placeholder="Unlimited">
                        </div>

                        <div>
                            <label for="max_custom_products" class="block text-sm font-medium text-[#201E1F]/60 mb-2">Max Custom Products</label>
                            <input type="number" id="max_custom_products" name="max_custom_products" value="{{ old('max_custom_products') }}" min="0"
                                   class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent"
                                   placeholder="Unlimited">
                        </div>

                        <div>
                            <label for="max_reseller_credit_packs" class="block text-sm font-medium text-[#201E1F]/60 mb-2">Max Reseller Credit Packs</label>
                            <input type="number" id="max_reseller_credit_packs" name="max_reseller_credit_packs" value="{{ old('max_reseller_credit_packs') }}" min="0"
                                   class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent"
                                   placeholder="Unlimited">
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.super.admins.index') }}" 
                       class="px-6 py-3 border border-gray-200 rounded-lg text-[#201E1F] hover:bg-gray-50 transition-all duration-300">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-gradient-to-r from-[#D63613] to-[#D63613]/80 hover:from-[#D63613]/90 hover:to-[#D63613] text-white rounded-lg font-semibold transition-all duration-300 shadow-md hover:shadow-lg">
                        Create Admin
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

