@extends('layouts.admin')

@section('title', 'Trial Request Details')

@section('content')
    <div class="space-y-8">
        <!-- Header -->
        <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-4 md:p-6 animate-fade-in-up">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div>
                    <a href="{{ route('admin.trial-requests.index') }}" class="text-[#D63613] hover:text-[#D63613]/80 text-sm mb-2 inline-block">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Trial Requests
                    </a>
                    <h1 class="text-2xl md:text-3xl font-bold text-[#201E1F]">Trial Request Details</h1>
                    <p class="text-[#201E1F]/60 text-sm md:text-base">Request ID: {{ $trialRequest->request_id }}</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    @if($trialRequest->status === 'pending')
                    <button type="button" 
                        onclick="openApproveModal({{ $trialRequest->id }}, '{{ $trialRequest->email }}', '{{ $trialRequest->source }}')"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-300">
                        <i class="fas fa-check mr-2"></i>Approve
                    </button>
                    <form method="POST" action="{{ route('admin.trial-requests.reject', $trialRequest) }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-300">
                            <i class="fas fa-times mr-2"></i>Reject
                        </button>
                    </form>
                    @endif
                    <form method="POST" action="{{ route('admin.trial-requests.destroy', $trialRequest) }}" 
                        onsubmit="return confirm('Are you sure you want to delete this request?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-300">
                            <i class="fas fa-trash mr-2"></i>Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>

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

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Request Info -->
            <div class="bg-white rounded-xl border border-[#D63613]/10 shadow-md p-6">
                <h2 class="text-lg font-semibold text-[#201E1F] mb-4 flex items-center">
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    Request Information
                </h2>
                <dl class="space-y-4">
                    <div class="flex justify-between border-b border-gray-100 pb-3">
                        <dt class="text-[#201E1F]/60">Request ID</dt>
                        <dd class="text-[#201E1F] font-mono text-sm">{{ $trialRequest->request_id }}</dd>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 pb-3">
                        <dt class="text-[#201E1F]/60">Status</dt>
                        <dd>
                            @if($trialRequest->status === 'pending')
                            <span class="px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded">Pending</span>
                            @elseif($trialRequest->status === 'approved')
                            <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded">Approved</span>
                            @else
                            <span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded">Rejected</span>
                            @endif
                        </dd>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 pb-3">
                        <dt class="text-[#201E1F]/60">Source</dt>
                        <dd class="text-[#201E1F]">{{ $trialRequest->source ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 pb-3">
                        <dt class="text-[#201E1F]/60">Submitted</dt>
                        <dd class="text-[#201E1F]">{{ $trialRequest->created_at->format('M d, Y H:i:s') }}</dd>
                    </div>
                    @if($trialRequest->processed_at)
                    <div class="flex justify-between border-b border-gray-100 pb-3">
                        <dt class="text-[#201E1F]/60">Processed</dt>
                        <dd class="text-[#201E1F]">{{ $trialRequest->processed_at->format('M d, Y H:i:s') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-[#201E1F]/60">Processed By</dt>
                        <dd class="text-[#201E1F]">{{ $trialRequest->processed_by }}</dd>
                    </div>
                    @endif
                </dl>
            </div>

            <!-- Contact Info -->
            <div class="bg-white rounded-xl border border-[#D63613]/10 shadow-md p-6">
                <h2 class="text-lg font-semibold text-[#201E1F] mb-4 flex items-center">
                    <div class="w-8 h-8 bg-gradient-to-br from-green-400 to-green-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    Contact Information
                </h2>
                <dl class="space-y-4">
                    <div class="flex justify-between border-b border-gray-100 pb-3">
                        <dt class="text-[#201E1F]/60">Email</dt>
                        <dd class="text-[#201E1F]">
                            <a href="mailto:{{ $trialRequest->email }}" class="text-[#D63613] hover:underline">
                                {{ $trialRequest->email }}
                            </a>
                        </dd>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 pb-3">
                        <dt class="text-[#201E1F]/60">Phone</dt>
                        <dd class="text-[#201E1F]">{{ $trialRequest->phone ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 pb-3">
                        <dt class="text-[#201E1F]/60">Has WhatsApp</dt>
                        <dd>
                            @if($trialRequest->has_whatsapp)
                            <span class="text-green-600"><i class="fab fa-whatsapp mr-1"></i>Yes</span>
                            @else
                            <span class="text-[#201E1F]/60">No</span>
                            @endif
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-[#201E1F]/60">Country</dt>
                        <dd class="text-[#201E1F]">{{ $trialRequest->country ?? '-' }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Trial Details -->
            <div class="bg-white rounded-xl border border-[#D63613]/10 shadow-md p-6">
                <h2 class="text-lg font-semibold text-[#201E1F] mb-4 flex items-center">
                    <div class="w-8 h-8 bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/>
                        </svg>
                    </div>
                    Trial Details
                </h2>
                <dl class="space-y-4">
                    <div class="flex justify-between border-b border-gray-100 pb-3">
                        <dt class="text-[#201E1F]/60">Server</dt>
                        <dd class="text-[#201E1F]">{{ $trialRequest->server ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 pb-3">
                        <dt class="text-[#201E1F]/60">Server Type</dt>
                        <dd class="text-[#201E1F]">{{ $trialRequest->server_type ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 pb-3">
                        <dt class="text-[#201E1F]/60">Trial Duration</dt>
                        <dd class="text-[#201E1F]">{{ $trialRequest->trial_duration ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[#201E1F]/60 mb-2">Requested Countries</dt>
                        <dd class="text-[#201E1F]">{{ $trialRequest->requested_countries ?? '-' }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Credentials (if approved) -->
            @if($trialRequest->status === 'approved' && $trialRequest->trial_username)
            <div class="bg-white rounded-xl border border-[#D63613]/10 shadow-md p-6">
                <h2 class="text-lg font-semibold text-[#201E1F] mb-4 flex items-center">
                    <div class="w-8 h-8 bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                    </div>
                    Sent Credentials
                </h2>
                <dl class="space-y-4">
                    <div class="flex justify-between border-b border-gray-100 pb-3">
                        <dt class="text-[#201E1F]/60">Username</dt>
                        <dd class="text-[#201E1F] font-mono">{{ $trialRequest->trial_username }}</dd>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 pb-3">
                        <dt class="text-[#201E1F]/60">Password</dt>
                        <dd class="text-[#201E1F] font-mono">{{ $trialRequest->trial_password }}</dd>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 pb-3">
                        <dt class="text-[#201E1F]/60">URL</dt>
                        <dd class="text-[#201E1F]">
                            <a href="{{ $trialRequest->trial_url }}" target="_blank" class="text-[#D63613] hover:underline">
                                {{ $trialRequest->trial_url }}
                            </a>
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-[#201E1F]/60">Email Sent</dt>
                        <dd>
                            @if($trialRequest->credentials_sent)
                            <span class="text-green-600"><i class="fas fa-check-circle mr-1"></i>Yes ({{ $trialRequest->credentials_sent_at->format('M d, Y H:i') }})</span>
                            @else
                            <span class="text-red-600"><i class="fas fa-times-circle mr-1"></i>No</span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>
            @else
            <!-- Notes -->
            <div class="bg-white rounded-xl border border-[#D63613]/10 shadow-md p-6">
                <h2 class="text-lg font-semibold text-[#201E1F] mb-4 flex items-center">
                    <div class="w-8 h-8 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    Notes
                </h2>
                @if($trialRequest->notes)
                <p class="text-[#201E1F]">{{ $trialRequest->notes }}</p>
                @else
                <p class="text-[#201E1F]/60 italic">No notes added.</p>
                @endif
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
                        <input type="text" name="trial_username" id="trial_username" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#D63613] focus:border-[#D63613]"
                            placeholder="trial_user123">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#201E1F]/70 mb-1">Password *</label>
                        <input type="text" name="trial_password" id="trial_password" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#D63613] focus:border-[#D63613]"
                            placeholder="secure_password">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#201E1F]/70 mb-1">URL *</label>
                        <input type="url" name="trial_url" id="trial_url" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-[#D63613] focus:border-[#D63613]"
                            placeholder="http://server.example.com:8080">
                    </div>

                    <!-- Generate Trial M3U Button -->
                    <div>
                        <button type="button" id="generateM3uBtn" onclick="generateTrialM3u()"
                            class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-300 flex items-center justify-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            <span>Generate Trial M3U</span>
                        </button>
                        <p class="mt-1 text-xs text-gray-500">Auto-fill credentials from Activation Panel API</p>
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

        // Generate Trial M3U from Activation Panel API
        async function generateTrialM3u() {
            const btn = document.getElementById('generateM3uBtn');
            const originalContent = btn.innerHTML;
            
            // Show loading state
            btn.disabled = true;
            btn.innerHTML = `
                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Generating...</span>
            `;

            try {
                const response = await fetch('{{ route("admin.trial-requests.generate-m3u") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    // Fill the form fields with the response data
                    document.getElementById('trial_username').value = data.data.username || '';
                    document.getElementById('trial_password').value = data.data.password || '';
                    document.getElementById('trial_url').value = data.data.url || '';
                    
                    // Show success message
                    btn.classList.remove('from-blue-500', 'to-blue-600', 'hover:from-blue-600', 'hover:to-blue-700');
                    btn.classList.add('from-green-500', 'to-green-600');
                    btn.innerHTML = `
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Credentials Filled!</span>
                    `;
                    
                    // Reset button after 2 seconds
                    setTimeout(() => {
                        btn.classList.remove('from-green-500', 'to-green-600');
                        btn.classList.add('from-blue-500', 'to-blue-600', 'hover:from-blue-600', 'hover:to-blue-700');
                        btn.innerHTML = originalContent;
                        btn.disabled = false;
                    }, 2000);
                } else {
                    // Show error
                    alert('Error: ' + (data.message || 'Failed to generate trial M3U'));
                    btn.innerHTML = originalContent;
                    btn.disabled = false;
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to connect to the server. Please try again.');
                btn.innerHTML = originalContent;
                btn.disabled = false;
            }
        }
    </script>
@endsection
