@extends('layouts.admin')

@section('title', 'Trial Requests')

@section('content')
    <div class="space-y-8">
        <!-- Header -->
        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-4 md:p-6 animate-fade-in-up">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-[#201E1F]">Trial Requests</h1>
                    <p class="text-[#201E1F]/60 text-sm md:text-base">Manage incoming trial requests from your website</p>
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 md:ml-4 flex-1">
                            <dl>
                                <dt class="text-xs md:text-sm font-medium text-[#201E1F]/60 mb-1">Total Requests</dt>
                                <dd class="text-xl md:text-2xl font-bold text-[#201E1F]">{{ $stats['total'] }}</dd>
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
                                <dt class="text-xs md:text-sm font-medium text-[#201E1F]/60 mb-1">Pending</dt>
                                <dd class="text-xl md:text-2xl font-bold text-[#201E1F]">{{ $stats['pending'] }}</dd>
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
                                <dt class="text-xs md:text-sm font-medium text-[#201E1F]/60 mb-1">Approved</dt>
                                <dd class="text-xl md:text-2xl font-bold text-[#201E1F]">{{ $stats['approved'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-[#F5F5F5] overflow-hidden rounded-xl border border-[#D63613]/10 shadow-md">
                <div class="p-4 md:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-red-400 to-red-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 md:ml-4 flex-1">
                            <dl>
                                <dt class="text-xs md:text-sm font-medium text-[#201E1F]/60 mb-1">Rejected</dt>
                                <dd class="text-xl md:text-2xl font-bold text-[#201E1F]">{{ $stats['rejected'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Messages -->
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
        @endif

        <!-- Filters & Table -->
        <div class="bg-white rounded-xl border border-[#D63613]/10 shadow-md">
            <div class="px-4 md:px-6 py-4 border-b border-[#D63613]/10">
                <form method="GET" action="{{ route('admin.trial-requests.index') }}" class="flex flex-col md:flex-row md:items-end md:space-x-4 space-y-3 md:space-y-0">
                    <div class="flex-1">
                        <label for="search" class="block text-sm font-medium text-[#201E1F]/70 mb-1">Search</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                            placeholder="Search by email, phone, request ID..."
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#D63613] focus:border-[#D63613]">
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-[#201E1F]/70 mb-1">Status</label>
                        <select name="status" id="status"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#D63613] focus:border-[#D63613]">
                            <option value="">All</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
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

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-[#D63613]">
                        <tr>
                            <th class="px-3 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">Request ID</th>
                            <th class="px-3 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">Email</th>
                            <th class="px-3 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">Phone</th>
                            <th class="px-3 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">Country</th>
                            <th class="px-3 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">Server</th>
                            <th class="px-3 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">Status</th>
                            <th class="px-3 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">Date</th>
                            <th class="px-3 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-[#F5F5F5] divide-y divide-gray-200">
                        @forelse($trialRequests as $request)
                        <tr>
                            <td class="px-3 py-4 whitespace-nowrap text-sm font-mono text-[#201E1F]">
                                {{ Str::limit($request->request_id, 20) }}
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm text-[#201E1F]">
                                {{ $request->email }}
                                @if($request->has_whatsapp)
                                <span class="ml-1 text-green-600" title="Has WhatsApp"><i class="fab fa-whatsapp"></i></span>
                                @endif
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm text-[#201E1F]">{{ $request->phone ?? '-' }}</td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm text-[#201E1F]">{{ $request->country ?? '-' }}</td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm text-[#201E1F]">
                                {{ $request->server_type ?? $request->server ?? '-' }}
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                @if($request->status === 'pending')
                                <span class="px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded">Pending</span>
                                @elseif($request->status === 'approved')
                                <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded">Approved</span>
                                @if($request->credentials_sent)
                                <span class="ml-1 text-green-600" title="Email sent"><i class="fas fa-envelope-circle-check"></i></span>
                                @endif
                                @else
                                <span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded">Rejected</span>
                                @endif
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm text-[#201E1F]/60">
                                {{ $request->created_at->format('M d, Y H:i') }}
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm space-x-2">
                                <a href="{{ route('admin.trial-requests.show', $request) }}" 
                                    class="text-[#D63613] hover:text-[#D63613]/80 font-medium">View</a>
                                @if($request->status === 'pending')
                                <button type="button" 
                                    onclick="openApproveModal({{ $request->id }}, '{{ $request->email }}', '{{ $request->source }}')"
                                    class="text-green-600 hover:text-green-800 font-medium">Approve</button>
                                <form method="POST" action="{{ route('admin.trial-requests.reject', $request) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Reject</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-3 py-8 text-center text-gray-500">No trial requests found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($trialRequests->hasPages())
            <div class="px-4 md:px-6 py-4 border-t border-[#D63613]/10">
                {{ $trialRequests->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- Approve Modal -->
    <div id="approveModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-10 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-xl bg-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-[#201E1F]">Approve Trial Request</h3>
                <button type="button" onclick="closeApproveModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <p class="text-sm text-gray-600 mb-4">
                Sending credentials to: <strong id="modalEmail"></strong>
            </p>

            <form id="approveForm" method="POST" action="">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-[#201E1F]/70 mb-1">Username *</label>
                        <input type="text" name="trial_username" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#D63613] focus:border-[#D63613]"
                            placeholder="trial_user123">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#201E1F]/70 mb-1">Password *</label>
                        <input type="text" name="trial_password" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#D63613] focus:border-[#D63613]"
                            placeholder="secure_password">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#201E1F]/70 mb-1">URL *</label>
                        <input type="url" name="trial_url" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#D63613] focus:border-[#D63613]"
                            placeholder="http://server.example.com:8080">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#201E1F]/70 mb-1">Send Email Via (Source) *</label>
                        <select name="smtp_source" required id="smtpSourceSelect"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#D63613] focus:border-[#D63613]">
                            <option value="">Select source...</option>
                            @foreach($sources as $source)
                            <option value="{{ $source->name }}" {{ $source->smtp_host ? '' : 'disabled' }}>
                                {{ $source->name }} {{ $source->smtp_host ? '' : '(No SMTP configured)' }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#201E1F]/70 mb-1">Notes (Optional)</label>
                        <textarea name="notes" rows="2"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#D63613] focus:border-[#D63613]"
                            placeholder="Any additional notes..."></textarea>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeApproveModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        Approve & Send Email
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openApproveModal(requestId, email, source) {
            document.getElementById('modalEmail').textContent = email;
            document.getElementById('approveForm').action = '/admin/trial-requests/' + requestId + '/approve';
            
            // Pre-select source if it matches
            const sourceSelect = document.getElementById('smtpSourceSelect');
            if (source) {
                for (let option of sourceSelect.options) {
                    if (option.value.toLowerCase() === source.toLowerCase()) {
                        option.selected = true;
                        break;
                    }
                }
            }
            
            document.getElementById('approveModal').classList.remove('hidden');
        }

        function closeApproveModal() {
            document.getElementById('approveModal').classList.add('hidden');
            document.getElementById('approveForm').reset();
        }

        // Close modal when clicking outside
        document.getElementById('approveModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeApproveModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('approveModal').classList.contains('hidden')) {
                closeApproveModal();
            }
        });
    </script>
@endsection
