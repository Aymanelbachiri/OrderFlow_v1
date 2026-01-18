@extends('layouts.admin')

@section('title', 'Affiliates Management')

@section('content')
    <div class="space-y-8">
        <!-- Header -->
        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-4 md:p-6 animate-fade-in-up">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-[#201E1F]">Affiliates Management</h1>
                    <p class="text-[#201E1F]/60 text-sm md:text-base">Manage affiliate accounts and referral rewards</p>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            <div class="bg-[#F5F5F5] overflow-hidden rounded-xl border border-[#D63613]/10 shadow-md">
                <div class="p-4 md:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 md:ml-4 flex-1">
                            <dl>
                                <dt class="text-xs md:text-sm font-medium text-[#201E1F]/60 mb-1">Total Affiliates</dt>
                                <dd class="text-xl md:text-2xl font-bold text-[#201E1F]">{{ $totalAffiliates ?? 0 }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-[#F5F5F5] overflow-hidden rounded-xl border border-[#D63613]/10 shadow-md">
                <div class="p-4 md:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 md:ml-4 flex-1">
                            <dl>
                                <dt class="text-xs md:text-sm font-medium text-[#201E1F]/60 mb-1">Active Affiliates</dt>
                                <dd class="text-xl md:text-2xl font-bold text-[#201E1F]">{{ $activeAffiliates ?? 0 }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-[#F5F5F5] overflow-hidden rounded-xl border border-[#D63613]/10 shadow-md">
                <div class="p-4 md:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 md:ml-4 flex-1">
                            <dl>
                                <dt class="text-xs md:text-sm font-medium text-[#201E1F]/60 mb-1">Total Referrals</dt>
                                <dd class="text-xl md:text-2xl font-bold text-[#201E1F]">{{ $totalReferrals ?? 0 }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-[#F5F5F5] overflow-hidden rounded-xl border border-[#D63613]/10 shadow-md">
                <div class="p-4 md:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 md:ml-4 flex-1">
                            <dl>
                                <dt class="text-xs md:text-sm font-medium text-[#201E1F]/60 mb-1">Pending Rewards</dt>
                                <dd class="text-xl md:text-2xl font-bold text-[#201E1F]">{{ $pendingRewards ?? 0 }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl border border-[#D63613]/10 shadow-md">
            <div class="px-4 md:px-6 py-4 border-b border-[#D63613]/10">
                <form method="GET" action="{{ route('admin.affiliates.index') }}" class="flex flex-col md:flex-row md:items-end md:space-x-4 space-y-3 md:space-y-0">
                    <div class="flex-1">
                        <label for="search" class="block text-sm font-medium text-[#201E1F]/70 mb-1">Search (Email or Referral Code)</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                            placeholder="Search affiliates..."
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#D63613] focus:border-[#D63613]">
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-[#201E1F]/70 mb-1">Status</label>
                        <select name="status" id="status"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#D63613] focus:border-[#D63613]">
                            <option value="">All</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div>
                        <button type="submit"
                            class="bg-[#D63613] hover:bg-[#D63613]/90 text-white px-6 py-2 rounded-lg text-sm font-semibold transition-all duration-300">
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Affiliates Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-[#D63613]">
                        <tr>
                            <th class="px-3 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">Email</th>
                            <th class="px-3 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">Referral Code</th>
                            <th class="px-3 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">Selected Subscription</th>
                            <th class="px-3 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">Linked Device</th>
                            <th class="px-3 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">Total Referrals</th>
                            <th class="px-3 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">Rewards Earned</th>
                            <th class="px-3 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">Status</th>
                            <th class="px-3 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-[#F5F5F5] divide-y divide-gray-200">
                        @forelse($affiliates as $affiliate)
                            <tr>
                                <td class="px-3 py-4 whitespace-nowrap text-sm text-[#201E1F]">
                                    {{ $affiliate->email }}
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap text-sm font-mono text-[#201E1F]">
                                    {{ $affiliate->referral_code }}
                                </td>
                                <td class="px-3 py-4 text-sm text-[#201E1F]">
                                    {{ $affiliate->selectedOrder->pricingPlan->display_name ?? 'N/A' }}<br>
                                    <span class="text-xs text-gray-500">{{ $affiliate->selectedOrder->order_number ?? 'N/A' }}</span>
                                </td>
                                <td class="px-3 py-4 text-sm text-[#201E1F]">
                                    @if($affiliate->selectedOrder && $affiliate->selectedOrder->devices)
                                        @php
                                            $linkedDevice = null;
                                            foreach($affiliate->selectedOrder->devices as $index => $device) {
                                                if($affiliate->selected_device_id == ($device['id'] ?? $index)) {
                                                    $linkedDevice = $device;
                                                    $linkedDevice['number'] = $index + 1;
                                                    break;
                                                }
                                            }
                                            if(!$linkedDevice && count($affiliate->selectedOrder->devices) > 0) {
                                                $linkedDevice = $affiliate->selectedOrder->devices[0];
                                                $linkedDevice['number'] = 1;
                                            }
                                        @endphp
                                        @if($linkedDevice)
                                            <div class="text-sm">
                                                <span class="font-medium">Device {{ $linkedDevice['number'] }}</span><br>
                                                <span class="text-xs text-gray-500">{{ $linkedDevice['username'] ?? 'N/A' }}</span>
                                            </div>
                                        @else
                                            <span class="text-gray-500">No device</span>
                                        @endif
                                    @else
                                        <span class="text-gray-500">No devices</span>
                                    @endif
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap text-sm text-[#201E1F]">
                                    {{ $affiliate->total_referrals }}
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap text-sm text-[#201E1F]">
                                    {{ $affiliate->total_rewards_earned }} month(s)
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap">
                                    @if($affiliate->is_active)
                                        <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded">Active</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-800 rounded">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap text-sm space-x-2">
                                    <a href="{{ route('admin.affiliates.show', $affiliate) }}"
                                        class="text-[#D63613] hover:text-[#D63613]/80 font-medium">View</a>
                                    
                                    @if($affiliate->is_active && $affiliate->selectedOrder && $affiliate->selectedOrder->isActive())
                                        <form method="POST" action="{{ route('admin.affiliates.grant-reward', $affiliate) }}" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                onclick="return confirm('Grant 1 month reward to this affiliate? This will extend their subscription and send a congratulations email.')"
                                                class="text-green-600 hover:text-green-800 font-medium">
                                                Grant Reward
                                            </button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('admin.affiliates.destroy', $affiliate) }}" class="inline" id="delete-form-{{ $affiliate->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" 
                                            onclick="openDeleteModal({{ $affiliate->id }}, '{{ $affiliate->email }}', '{{ $affiliate->referral_code }}', {{ $affiliate->total_referrals }})"
                                            class="text-red-600 hover:text-red-800 font-medium">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-3 py-8 text-center text-gray-500">No affiliates found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($affiliates->hasPages())
                <div class="px-4 md:px-6 py-4 border-t border-[#D63613]/10">
                    {{ $affiliates->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-9-4a9 9 0 1118 0 9 9 0 01-18 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mt-4">Delete Affiliate</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Are you sure you want to delete the affiliate <strong id="affiliateEmail"></strong> 
                        (Code: <strong id="affiliateCode"></strong>)?
                    </p>
                    <p class="text-sm text-red-600 mt-2">
                        This action cannot be undone and will also delete <strong id="referralCount"></strong> associated referral record(s).
                    </p>
                </div>
                <div class="items-center px-4 py-3">
                    <button id="confirmDelete" 
                        class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300">
                        Delete
                    </button>
                    <button id="cancelDelete" 
                        class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md w-24 hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentDeleteForm = null;

        function openDeleteModal(affiliateId, email, code, referralCount) {
            currentDeleteForm = document.getElementById('delete-form-' + affiliateId);
            document.getElementById('affiliateEmail').textContent = email;
            document.getElementById('affiliateCode').textContent = code;
            document.getElementById('referralCount').textContent = referralCount;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            currentDeleteForm = null;
        }

        document.getElementById('confirmDelete').addEventListener('click', function() {
            if (currentDeleteForm) {
                currentDeleteForm.submit();
            }
        });

        document.getElementById('cancelDelete').addEventListener('click', closeDeleteModal);

        // Close modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('deleteModal').classList.contains('hidden')) {
                closeDeleteModal();
            }
        });
    </script>
@endsection
