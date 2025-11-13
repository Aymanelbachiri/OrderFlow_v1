@extends('layouts.admin')

@section('title', 'Admin Management')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-[#201E1F] mb-2">Admin Management</h1>
                <p class="text-[#201E1F]/60">Manage admin users and their permissions</p>
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

    <!-- Admins List -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Orders</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sources</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Products</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-[#201E1F]/60">
                            {{ $admin->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.super.admins.edit', $admin) }}" class="text-blue-600 hover:text-blue-800 mr-4">Edit</a>
                            @if(!$admin->is_super_admin)
                                <form action="{{ route('admin.super.admins.destroy', $admin) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this admin?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                                </form>
                            @endif
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

