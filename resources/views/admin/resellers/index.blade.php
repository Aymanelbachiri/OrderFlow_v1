@extends('layouts.admin')

@section('title', 'Resellers Management')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up">
        <div class="lg:flex space-y-4 justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-[#201E1F] mb-2">Resellers Management</h1>
                <p class="text-[#201E1F]/60">Manage your reseller network and track performance</p>
            </div>
            <a href="{{ route('admin.resellers.create') }}" 
               class="bg-gradient-to-r from-[#D63613] to-[#D63613]/80 hover:from-[#D63613]/90 hover:to-[#D63613] text-white px-6 py-3 rounded-lg text-sm font-semibold flex items-center space-x-2 transition-all duration-300 shadow-md hover:shadow-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span>Add New Reseller</span>
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up" style="animation-delay: 0.1s;">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-[#201E1F]">Filter Resellers</h3>
            <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
                </svg>
            </div>
        </div>
        <form method="GET" action="{{ route('admin.resellers.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-[#201E1F]/60 mb-2">Search</label>
                <input type="text"
                       id="search"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Name, email..."
                       class="w-full rounded-lg bg-white border border-gray-200 text-[#201E1F] placeholder-[#201E1F]/40 focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300">
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-[#201E1F]/60 mb-2">Status</label>
                <select id="status" name="status" class="w-full rounded-lg bg-white border border-gray-200 text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
            </div>

            <div>
                <label for="sort" class="block text-sm font-medium text-[#201E1F]/60 mb-2">Sort By</label>
                <select id="sort" name="sort" class="w-full rounded-lg bg-white border border-gray-200 text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300">
                    <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Date Created</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                    <option value="email" {{ request('sort') == 'email' ? 'selected' : '' }}>Email</option>
                    <option value="last_login_at" {{ request('sort') == 'last_login_at' ? 'selected' : '' }}>Last Login</option>
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full bg-gradient-to-r from-[#D63613] to-[#D63613]/80 hover:from-[#D63613]/90 hover:to-[#D63613] text-white px-4 py-3 rounded-lg text-sm font-semibold transition-all duration-300 shadow-md hover:shadow-lg">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 animate-fade-in-up" style="animation-delay: 0.2s;">
        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-[#201E1F]/60 truncate">Total Resellers</dt>
                        <dd class="text-2xl font-bold text-[#201E1F]">{{ $resellers->total() }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-[#201E1F]/60 truncate">Active Resellers</dt>
                        <dd class="text-2xl font-bold text-[#201E1F]">{{ $activeResellersCount ?? 0 }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-[#201E1F]/60 truncate">Total Revenue</dt>
                        <dd class="text-2xl font-bold text-[#201E1F]">${{ number_format($totalRevenue ?? 0, 2) }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-[#201E1F]/60 truncate">Total Commissions</dt>
                        <dd class="text-2xl font-bold text-[#201E1F]">${{ number_format($totalCommissions ?? 0, 2) }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Resellers Table -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md animate-fade-in-up overflow-hidden" style="animation-delay: 0.3s;">
        <div class="px-6 py-5 border-b border-[#D63613]/10">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-semibold text-[#201E1F]">All Resellers</h3>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-[#201E1F]/60">{{ $resellers->total() }} total resellers</span>
                    <div class="w-3 h-3 bg-gradient-to-r from-orange-400 to-orange-600 rounded-full"></div>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#D63613]">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">Reseller</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">Credit Packs</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">Panel Access</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-[#F5F5F5] divide-y divide-gray-200">
                    @forelse($resellers as $reseller)
                    <tr class="hover:bg-white/50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-gradient-to-br from-[#D63613] to-[#D63613]/80 rounded-xl flex items-center justify-center">
                                        <span class="text-sm font-semibold text-white">
                                            {{ strtoupper(substr($reseller->name, 0, 2)) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-semibold text-[#201E1F]">
                                        <a href="{{ route('admin.resellers.show', $reseller) }}" class="text-[#D63613] hover:text-[#D63613]/80 transition-colors duration-200">
                                            {{ $reseller->name }}
                                        </a>
                                    </div>
                                    <div class="text-xs text-[#201E1F]/60">ID: {{ $reseller->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-[#201E1F]">{{ $reseller->email }}</div>
                            <div class="text-sm text-[#201E1F]/60">{{ $reseller->phone ?: 'No phone' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg flex items-center justify-center mr-2">
                                    <span class="text-sm font-semibold text-blue-600">{{ $reseller->credit_pack_orders_count ?? 0 }}</span>
                                </div>
                                <div>
                                    <div class="text-sm text-[#201E1F]">purchased</div>
                                    <div class="text-xs text-[#201E1F]/60">{{ $reseller->pending_credit_orders_count ?? 0 }} pending</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($reseller->reseller_panel_url)
                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-50 text-green-700 border border-green-200">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Configured
                                </span>
                                <div class="text-xs text-[#201E1F]/60 mt-1">{{ Str::limit($reseller->reseller_panel_url, 30) }}</div>
                            @else
                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-red-50 text-red-700 border border-red-200">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Not configured
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col space-y-1">
                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full {{ $reseller->is_active ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-700 border border-red-200' }}">
                                    <div class="w-2 h-2 {{ $reseller->is_active ? 'bg-green-400 animate-pulse' : 'bg-red-400' }} rounded-full mr-2"></div>
                                    {{ $reseller->is_active ? 'Active' : 'Inactive' }}
                                </span>
                                @if($reseller->email_verified_at)
                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Verified
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('admin.resellers.show', $reseller) }}" 
                                   class="text-blue-600 hover:text-blue-700 font-medium transition-colors duration-200 flex items-center space-x-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <span class="text-xs">View</span>
                                </a>
                                <a href="{{ route('admin.resellers.edit', $reseller) }}" 
                                   class="text-purple-600 hover:text-purple-700 font-medium transition-colors duration-200 flex items-center space-x-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    <span class="text-xs">Edit</span>
                                </a>
                                @if($reseller->is_active)
                                <form action="{{ route('admin.resellers.suspend', $reseller) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-yellow-600 hover:text-yellow-700 font-medium transition-colors duration-200 flex items-center space-x-1"
                                            onclick="return confirm('Are you sure you want to suspend this reseller?')">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                        </svg>
                                        <span class="text-xs">Suspend</span>
                                    </button>
                                </form>
                                @else
                                <form action="{{ route('admin.resellers.reactivate', $reseller) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-700 font-medium transition-colors duration-200 flex items-center space-x-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-xs">Activate</span>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <p class="text-gray-500 text-sm">No resellers found</p>
                                <p class="text-gray-400 text-xs mt-1">Try adjusting your search criteria</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($resellers->hasPages())
        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md px-6 py-4 animate-fade-in-up" style="animation-delay: 0.4s;">
            <div class="flex items-center justify-between">
                <div class="text-sm text-[#201E1F]/60">
                    Showing {{ $resellers->firstItem() ?? 0 }} to {{ $resellers->lastItem() ?? 0 }} of {{ $resellers->total() }} results
                </div>
                <div class="pagination-wrapper">
                    {{ $resellers->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
        @endif
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

/* Custom pagination styling to match light theme */
.pagination-wrapper nav div {
    @apply bg-white border border-gray-200 rounded-lg;
}

.pagination-wrapper nav span,
.pagination-wrapper nav a {
    @apply text-[#201E1F] bg-transparent border-none px-3 py-2 text-sm;
}

.pagination-wrapper nav a:hover {
    @apply text-[#D63613] bg-[#D63613]/10;
}

.pagination-wrapper nav span[aria-current="page"] {
    @apply text-white bg-[#D63613] rounded;
}
</style>
@endsection