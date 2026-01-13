@extends('layouts.admin')

@section('title', 'Affiliate Details')

@section('content')
    <div class="space-y-8">
        <!-- Header -->
        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-4 md:p-6">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-[#201E1F]">Affiliate Details</h1>
                    <p class="text-[#201E1F]/60 text-sm md:text-base">{{ $affiliate->email }}</p>
                </div>
                <a href="{{ route('admin.affiliates.index') }}"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg text-sm font-semibold transition-all duration-300">
                    Back to List
                </a>
            </div>
        </div>

        <!-- Affiliate Info -->
        <div class="bg-white rounded-xl border border-[#D63613]/10 shadow-md p-6">
            <h2 class="text-xl font-semibold text-[#201E1F] mb-4">Affiliate Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <p class="text-[#201E1F]">{{ $affiliate->email }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Referral Code</label>
                    <p class="text-[#201E1F] font-mono">{{ $affiliate->referral_code }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Referral Link</label>
                    <p class="text-[#201E1F] text-sm break-all">{{ $affiliate->referral_link }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <p>
                        @if($affiliate->is_active)
                            <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded">Active</span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-800 rounded">Inactive</span>
                        @endif
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Selected Subscription</label>
                    <p class="text-[#201E1F]">
                        {{ $affiliate->selectedOrder->pricingPlan->display_name ?? 'N/A' }}<br>
                        <span class="text-sm text-gray-500">Order: {{ $affiliate->selectedOrder->order_number ?? 'N/A' }}</span><br>
                        @if($affiliate->selectedOrder->expires_at)
                            <span class="text-sm text-gray-500">Expires: {{ $affiliate->selectedOrder->expires_at->format('M d, Y') }}</span>
                        @endif
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Statistics</label>
                    <p class="text-[#201E1F]">
                        Total Referrals: <strong>{{ $affiliate->total_referrals }}</strong><br>
                        Rewards Earned: <strong>{{ $affiliate->total_rewards_earned }} month(s)</strong><br>
                        Pending Rewards: <strong>{{ $affiliate->pending_rewards_count }}</strong>
                    </p>
                </div>
            </div>
        </div>

        <!-- Referrals List -->
        <div class="bg-white rounded-xl border border-[#D63613]/10 shadow-md p-6">
            <h2 class="text-xl font-semibold text-[#201E1F] mb-4">Referral History</h2>
            
            @if($affiliate->referrals->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-[#D63613]">
                            <tr>
                                <th class="px-3 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">Order</th>
                                <th class="px-3 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">Customer</th>
                                <th class="px-3 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">Status</th>
                                <th class="px-3 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">Date</th>
                                <th class="px-3 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-[#F5F5F5] divide-y divide-gray-200">
                            @foreach($affiliate->referrals as $referral)
                                <tr>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-[#201E1F]">
                                        <a href="{{ route('admin.orders.show', $referral->order) }}" class="text-[#D63613] hover:underline">
                                            {{ $referral->order->order_number ?? 'N/A' }}
                                        </a>
                                    </td>
                                    <td class="px-3 py-4 text-sm text-[#201E1F]">
                                        {{ $referral->referredUser->name ?? 'N/A' }}<br>
                                        <span class="text-gray-500">{{ $referral->referredUser->email ?? 'N/A' }}</span>
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap">
                                        @if($referral->status === 'pending')
                                            <span class="px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded">Pending</span>
                                        @elseif($referral->status === 'approved')
                                            <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded">
                                                {{ $referral->reward_granted ? 'Rewarded' : 'Approved' }}
                                            </span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded">Rejected</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $referral->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm">
                                        @if($referral->status === 'pending' && !$referral->reward_granted)
                                            <form method="POST" action="{{ route('admin.affiliates.referrals.approve', [$affiliate, $referral]) }}" class="inline-block mr-2">
                                                @csrf
                                                <button type="submit" 
                                                    onclick="return confirm('Approve and grant +1 month reward to this affiliate?')"
                                                    class="text-green-600 hover:text-green-800 font-medium">Approve & Grant</button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.affiliates.referrals.reject', [$affiliate, $referral]) }}" class="inline-block">
                                                @csrf
                                                <button type="submit" 
                                                    onclick="return confirm('Reject this referral reward?')"
                                                    class="text-red-600 hover:text-red-800 font-medium">Reject</button>
                                            </form>
                                        @elseif($referral->reward_granted)
                                            <span class="text-gray-400">Reward granted</span>
                                            @if($referral->reward_granted_at)
                                                <br><span class="text-xs text-gray-500">{{ $referral->reward_granted_at->format('M d, Y') }}</span>
                                            @endif
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 text-center py-8">No referrals yet.</p>
            @endif
        </div>
    </div>
@endsection
