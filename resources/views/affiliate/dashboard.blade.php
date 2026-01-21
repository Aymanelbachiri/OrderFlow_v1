@extends('layouts.checkout')

@section('title', 'Affiliate Dashboard')

@section('content')
    <div class="min-h-screen flex items-center py-12 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <!-- Header -->
            <div class="text-center mb-10 animate-fade-in-up">
                <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    Affiliate Dashboard
                </h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2 text-lg">
                    Track your referrals and earnings
                </p>
            </div>

            @if (isset($affiliate))
                <!-- Dashboard Content -->
                <div class="space-y-6">
                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div
                            class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Referrals</p>
                                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                                        {{ $affiliate->total_referrals }}</p>
                                </div>
                                <div
                                    class="w-12 h-12 bg-blue-100 dark:bg-blue-900/40 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Free Months Earned</p>
                                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                                        {{ $affiliate->total_rewards_earned }}</p>
                                </div>
                                <div
                                    class="w-12 h-12 bg-green-100 dark:bg-green-900/40 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pending Rewards</p>
                                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                                        {{ $affiliate->pending_rewards_count }}</p>
                                </div>
                                <div
                                    class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/40 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                     <!-- 5. Statuses -->
                     <div>
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-2">Reward Status Legend</h4>
                        <div class="space-y-2 text-xs flex justify-between items-center">
                            <div class="flex flex-col items-center space-y-2">
                                <span class=" font-semibold px-4 py-1 rounded bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 text-center mr-3">Pending</span>
                                <span class="text-gray-600 dark:text-gray-400">Referral recorded, waiting for activation</span>
                            </div>
                            <div class="flex flex-col items-center space-y-2">
                                <span class=" font-semibold px-4 py-1 rounded bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-900 text-center mr-3">Applied</span>
                                <span class="text-gray-600 dark:text-gray-400">Reward month added to your account</span>
                            </div>
                            <div class="flex flex-col items-center space-y-2">
                                <span class=" font-semibold px-4 py-1 rounded bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-900 text-center mr-3">Revoked</span>
                                <span class="text-gray-600 dark:text-gray-400">Cancelled (fraud, refund, or abuse)</span>
                            </div>
                        </div>
                    </div>

                    <!-- Referral Code -->
                    <div
                        class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm p-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Your Referral Information</h2>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Referral
                                    Code</label>
                                <div class="flex items-center space-x-2">
                                    <input type="text" value="{{ $affiliate->referral_code }}" readonly
                                        class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 px-4 py-3 text-gray-900 dark:text-white font-mono text-lg">
                                    <button onclick="copyToClipboard('{{ $affiliate->referral_code }}', this)"
                                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-3 rounded-lg transition-colors">
                                        Copy
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Device Information -->
                    <div
                        class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm p-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Your Linked Devices</h2>
                        @if ($affiliate->selectedOrder && $affiliate->selectedOrder->devices)
                            <div class="space-y-4">
                                @foreach ($affiliate->selectedOrder->devices as $index => $device)
                                    <div
                                        class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h3 class="font-medium text-gray-900 dark:text-white">
                                                    Device {{ $index + 1 }}
                                                    @if ($affiliate->selected_device_id == ($device['id'] ?? $index))
                                                        <span
                                                            class="ml-2 px-2 py-1 text-xs font-semibold bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200 rounded">
                                                            Affiliate Rewards
                                                        </span>
                                                    @endif
                                                </h3>


                                            </div>
                                            <div class="text-right">
                                                @if ($affiliate->selectedOrder->isActive())
                                                    <span
                                                        class="inline-block px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-900 rounded">
                                                        Active
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-block px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 rounded">
                                                        Inactive
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400 text-center py-8">No device information available.
                            </p>
                        @endif
                    </div>

                    <!-- Referrals List -->
                    <div
                        class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm p-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Your Referrals</h2>
                        @if ($affiliate->referrals->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Customer Name</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Status</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Date</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach ($affiliate->referrals as $referral)
                                            <tr>
                                                <td
                                                    class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                    {{ $referral->referredUser->name ?? 'Customer' }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    @if ($referral->status === 'pending')
                                                        <span
                                                            class="px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-900 rounded">Pending</span>
                                                    @elseif($referral->status === 'approved')
                                                        <span
                                                            class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-900 rounded">
                                                            {{ $referral->reward_granted ? 'Rewarded' : 'Approved' }}
                                                        </span>
                                                    @else
                                                        <span
                                                            class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-900 rounded">Rejected</span>
                                                    @endif
                                                </td>
                                                <td
                                                    class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $referral->created_at->format('M d, Y') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400 text-center py-8">No referrals yet. Start sharing
                                your referral code!</p>
                        @endif
                    </div>
                </div>
            @else
                <!-- Lookup Form -->
                <div
                    class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-8">
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">Access Your Dashboard</h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">Enter your email address to view your affiliate
                        dashboard.</p>

                    <form method="GET" action="{{ route('affiliate.dashboard') }}" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Email Address
                            </label>
                            <input type="email" name="email" value="{{ request('email') }}" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="your@email.com">
                        </div>

                        <button type="submit"
                            class="w-full bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-300 shadow-md hover:shadow-lg">
                            View Dashboard
                        </button>
                    </form>

                    @if (request('email'))
                        <div
                            class="mt-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-xl">
                            <p class="text-sm text-red-800 dark:text-red-300">
                                Affiliate not found. Please check your email address.
                            </p>
                        </div>
                    @endif

                    <div class="mt-6 text-center">
                        <a href="{{ route('affiliate.register') }}"
                            class="text-indigo-600 dark:text-indigo-400 hover:underline">
                            Not an affiliate yet? Register here
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            function copyToClipboard(text, button) {
                // Check if clipboard API is available
                if (navigator.clipboard && window.isSecureContext) {
                    // Use modern clipboard API
                    navigator.clipboard.writeText(text).then(function() {
                        showCopySuccess(button);
                    }).catch(function(err) {
                        console.error('Clipboard API failed:', err);
                        fallbackCopy(text, button);
                    });
                } else {
                    // Use fallback method
                    fallbackCopy(text, button);
                }
            }

            function fallbackCopy(text, button) {
                try {
                    const textArea = document.createElement('textarea');
                    textArea.value = text;
                    textArea.style.position = 'fixed';
                    textArea.style.left = '-999999px';
                    textArea.style.top = '-999999px';
                    document.body.appendChild(textArea);
                    textArea.focus();
                    textArea.select();

                    const successful = document.execCommand('copy');
                    document.body.removeChild(textArea);

                    if (successful) {
                        showCopySuccess(button);
                    } else {
                        showCopyError(text);
                    }
                } catch (err) {
                    console.error('Fallback copy failed:', err);
                    showCopyError(text);
                }
            }

            function showCopySuccess(button) {
                const originalText = button.textContent;
                button.textContent = 'Copied!';
                button.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
                button.classList.add('bg-green-600', 'hover:bg-green-700');
                setTimeout(function() {
                    button.textContent = originalText;
                    button.classList.remove('bg-green-600', 'hover:bg-green-700');
                    button.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
                }, 2000);
            }

            function showCopyError(text) {
                // Create a temporary input field for manual copying
                const modal = document.createElement('div');
                modal.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);display:flex;align-items:center;justify-content:center;z-index:9999;'
                ;

                const content = document.createElement('div');
                content.style.cssText = 'background:white;padding:20px;border-radius:8px;max-width:400px;text-align:center;';
                content.innerHTML = `
                                <h3 style="margin:0 0 15px 0;color:#333;">Copy Referral Code</h3>
                                <p style="margin:0 0 15px 0;color:#666;">Please copy the code manually:</p>
                                <input type="text" value="${text}" readonly style="width:100%;padding:10px;border:1px solid #ddd;border-radius:4px;font-family:monospace;font-size:16px;text-align:center;margin-bottom:15px;" onclick="this.select()">
                                <button onclick="document.body.removeChild(this.closest('div').parentElement)" style="background:#4f46e5;color:white;border:none;padding:10px 20px;border-radius:4px;cursor:pointer;">Close</button>
                            `;

                modal.appendChild(content);
                document.body.appendChild(modal);

                // Auto-select the text
                const input = content.querySelector('input');
                input.focus();
            @endpush
        @endsection
