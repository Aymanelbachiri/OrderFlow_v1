@extends('layouts.admin')

@section('title', 'User Management')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up">
        <div class="lg:flex space-y-4 justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-[#201E1F] mb-2">User Management</h1>
                <p class="text-[#201E1F]/60">Manage clients and resellers across your IPTV platform</p>
            </div>
            <a href="{{ route('admin.clients.create') }}" 
               class="bg-gradient-to-r from-[#D63613] to-[#D63613]/80 hover:from-[#D63613]/90 hover:to-[#D63613] text-white px-6 py-3 rounded-lg text-sm font-semibold flex items-center space-x-2 transition-all duration-300 shadow-md hover:shadow-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span>Add New Client</span>
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up" style="animation-delay: 0.1s;">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-[#201E1F]">Filter Users</h3>
            <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
                </svg>
            </div>
        </div>
        <form method="GET" action="{{ route('admin.clients.index') }}" class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-5 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-[#201E1F]/60 mb-2">Search Users</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search by name or email..." 
                       class="w-full rounded-lg bg-white border border-gray-200 text-[#201E1F] placeholder-[#201E1F]/40 focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-[#201E1F]/60 mb-2">User Type</label>
                <select name="role" class="w-full rounded-lg bg-white border border-gray-200 text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300">
                    <option value="">All Types</option>
                    <option value="client" {{ request('role') === 'client' ? 'selected' : '' }}>Clients</option>
                    <option value="reseller" {{ request('role') === 'reseller' ? 'selected' : '' }}>Resellers</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-[#201E1F]/60 mb-2">Status</label>
                <select name="status" class="w-full rounded-lg bg-white border border-gray-200 text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="w-full bg-gradient-to-r from-[#D63613] to-[#D63613]/80 hover:from-[#D63613]/90 hover:to-[#D63613] text-white px-4 py-3 rounded-lg text-sm font-semibold transition-all duration-300 shadow-md hover:shadow-lg">
                    Filter
                </button>
                <a href="{{ route('admin.clients.index') }}" class="bg-red-500 hover:bg-gray-50 text-white hover:text-white border border-gray-200 px-4 py-3 rounded-lg text-sm font-medium transition-all duration-300">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md animate-fade-in-up overflow-hidden" style="animation-delay: 0.2s;">
        <div class="px-6 py-5 border-b border-[#D63613]/10">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-semibold text-[#201E1F]">All Users</h3>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-[#201E1F]/60">{{ $clients->total() }} total users</span>
                    <div class="w-3 h-3 bg-gradient-to-r from-green-400 to-green-600 rounded-full"></div>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#D63613]">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">User</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">User Type</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">Source</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">Orders</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">Joined</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-[#F5F5F5] divide-y divide-gray-200">
                    @forelse($clients as $client)
                        <tr class="hover:bg-white/50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-gradient-to-br from-[#D63613] to-[#D63613]/80 rounded-xl flex items-center justify-center">
                                            <span class="text-sm font-semibold text-white">{{ substr($client->name, 0, 2) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-[#201E1F]">{{ $client->name }}</div>
                                        <div class="text-xs text-[#201E1F]/60">ID: {{ $client->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-[#201E1F]">{{ $client->email }}</div>
                                @if($client->phone)
                                    <div class="text-sm text-[#201E1F]/60">{{ $client->phone }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($client->role === 'reseller')
                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-orange-50 text-orange-700 border border-orange-200">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        Reseller
                                    </span>
                                @else
                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        Client
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-[#201E1F]">{{ $client->source ?: ($client->role === 'reseller' ? 'reseller' : '—') }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gradient-to-br from-purple-100 to-purple-200 rounded-lg flex items-center justify-center mr-2">
                                        <span class="text-sm font-semibold text-purple-600">{{ $client->orders->count() }}</span>
                                    </div>
                                    <span class="text-xs text-[#201E1F]/60">orders</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($client->is_active && !$client->suspended_at)
                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-50 text-green-700 border border-green-200">
                                        <div class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-red-50 text-red-700 border border-red-200">
                                        <div class="w-2 h-2 bg-red-400 rounded-full mr-2"></div>
                                        Suspended
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-[#201E1F]">{{ $client->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-[#201E1F]/60">{{ $client->created_at->format('g:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('admin.clients.show', $client) }}" 
                                       class="text-blue-600 hover:text-blue-700 font-medium transition-colors duration-200 flex items-center"
                                       title="View Client">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.clients.edit', $client) }}" 
                                       class="text-purple-600 hover:text-purple-700 font-medium transition-colors duration-200 flex items-center"
                                       title="Edit Client">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <button onclick="confirmDeleteClient({{ $client->id }}, '{{ $client->name }}')" 
                                            class="text-red-600 hover:text-red-700 font-medium transition-colors duration-200 flex items-center"
                                            title="Delete Client">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                    @if($client->is_active)
                                        <button onclick="suspendClient({{ $client->id }})" 
                                                class="text-red-600 hover:text-red-700 font-medium transition-colors duration-200 flex items-center"
                                                title="Suspend Client">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                            </svg>
                                        </button>
                                    @else
                                        <form method="POST" action="{{ route('admin.clients.reactivate', $client) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-700 font-medium transition-colors duration-200 flex items-center"
                                                    title="Reactivate Client">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <p class="text-gray-500 text-sm">No users found</p>
                                    <p class="text-gray-400 text-xs mt-1">Try adjusting your search criteria</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($clients->hasPages())
        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md px-6 py-4 animate-fade-in-up" style="animation-delay: 0.3s;">
            <div class="flex items-center justify-between">
                <div class="text-sm text-[#201E1F]/60">
                    Showing {{ $clients->firstItem() ?? 0 }} to {{ $clients->lastItem() ?? 0 }} of {{ $clients->total() }} results
                </div>
                <div class="pagination-wrapper">
                    {{ $clients->links() }}
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Suspend Modal -->
<div id="suspendModal" class="fixed inset-0 bg-black bg-opacity-75 hidden items-center justify-center z-50 animate-fade-in">
    <div class="bg-white border border-gray-200 rounded-xl p-8 w-full max-w-md mx-4 shadow-2xl animate-scale-in">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-red-400 to-red-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-[#201E1F]">Suspend Client Account</h3>
            </div>
            <button onclick="closeSuspendModal()" class="text-[#201E1F]/60 hover:text-[#201E1F] transition-colors duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <form id="suspendForm" method="POST" class="space-y-6">
            @csrf
            <div>
                <label for="suspension_reason" class="block text-sm font-medium text-[#201E1F]/80 mb-3">Reason for Suspension</label>
                <textarea id="suspension_reason" name="suspension_reason" rows="4" required
                          placeholder="Please provide a detailed reason for suspending this client account..."
                          class="w-full rounded-lg bg-white border border-gray-200 text-[#201E1F] placeholder-[#201E1F]/40 focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300 resize-none"></textarea>
            </div>
            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" onclick="closeSuspendModal()" 
                        class="bg-white hover:bg-gray-50 text-[#201E1F]/80 hover:text-[#201E1F] border border-gray-200 px-6 py-3 rounded-lg text-sm font-medium transition-all duration-300">
                    Cancel
                </button>
                <button type="submit" 
                        class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-6 py-3 rounded-lg text-sm font-semibold transition-all duration-300 shadow-md hover:shadow-lg">
                    Suspend Account
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Client Confirmation Modal -->
<div id="deleteClientModal" class="fixed inset-0 bg-black bg-opacity-75 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <!-- Warning Icon -->
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            
            <!-- Modal Title -->
            <h3 class="text-lg font-medium text-gray-900 mb-2">Delete Client</h3>
            
            <!-- Modal Content -->
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500 mb-4">
                    Are you sure you want to delete client <span id="deleteClientName" class="font-semibold text-gray-900"></span>?
                </p>
                <p class="text-sm text-red-600 font-medium">
                    This action cannot be undone and will also delete all associated orders and payment records.
                </p>
            </div>
            
            <!-- Hidden input for client ID -->
            <input type="hidden" id="deleteClientId" value="">
            
            <!-- Modal Actions -->
            <div class="items-center px-4 py-3">
                <div class="flex space-x-3 justify-center">
                    <button onclick="closeDeleteClientModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-colors duration-200">
                        Cancel
                    </button>
                    <button onclick="proceedWithClientDelete()"
                            class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors duration-200">
                        Delete Client
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function suspendClient(clientId) {
    document.getElementById('suspendForm').action = `/admin/clients/${clientId}/suspend`;
    document.getElementById('suspendModal').classList.remove('hidden');
    document.getElementById('suspendModal').classList.add('flex');
}

function closeSuspendModal() {
    document.getElementById('suspendModal').classList.add('hidden');
    document.getElementById('suspendModal').classList.remove('flex');
}

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeSuspendModal();
    }
});

// Close modal on backdrop click
document.getElementById('suspendModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeSuspendModal();
    }
});
</script>

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

@keyframes fade-in {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes scale-in {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.animate-fade-in-up {
    animation: fade-in-up 0.6s ease-out forwards;
}

.animate-fade-in {
    animation: fade-in 0.3s ease-out forwards;
}

.animate-scale-in {
    animation: scale-in 0.3s ease-out forwards;
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

<script>
    function confirmDeleteClient(clientId, clientName) {
        // Update modal content
        document.getElementById('deleteClientName').textContent = clientName;
        document.getElementById('deleteClientId').value = clientId;
        
        // Show the modal
        document.getElementById('deleteClientModal').classList.remove('hidden');
    }

    function closeDeleteClientModal() {
        document.getElementById('deleteClientModal').classList.add('hidden');
    }

    function proceedWithClientDelete() {
        const clientId = document.getElementById('deleteClientId').value;
        
        // Create a form to submit the DELETE request
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/clients/${clientId}`;
        
        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        // Add method override for DELETE
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);
        
        // Submit the form
        document.body.appendChild(form);
        form.submit();
    }

    // Close modal when clicking outside
    document.getElementById('deleteClientModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteClientModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDeleteClientModal();
        }
    });
</script>
@endsection