@extends('layouts.admin')

@section('title', 'Shield Domains')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up">
        <div class="lg:flex space-y-4 justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-gradient-to-br from-[#D63613] to-[#D63613]/80 rounded-xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-[#201E1F] mb-1">Shield Domains</h1>
                    <p class="text-[#201E1F]/60">Manage static frontend domains for white-label checkout</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.shield-domains.create') }}" 
                   class="bg-[#D63613] hover:bg-[#b42f11] text-white px-6 py-3 rounded-lg text-sm font-semibold flex items-center space-x-2 transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span>Add Shield Domain</span>
                </a>
            </div>
        </div>

        <!-- Search and Filters -->
        <form method="GET" class="mt-6 flex flex-wrap gap-4">
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}" 
                   placeholder="Search domains..." 
                   class="flex-1 min-w-[200px] px-4 py-2 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent">
            <select name="status" 
                    class="px-4 py-2 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613]">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
            </select>
            <button type="submit" class="px-6 py-2 bg-[#D63613] text-white rounded-lg hover:bg-[#b42f11]">Filter</button>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.shield-domains.index') }}" class="px-6 py-2 border border-gray-200 rounded-lg hover:bg-gray-50">Clear</a>
            @endif
        </form>
    </div>

    <!-- Shield Domains Table -->
    <div class="bg-white rounded-xl border border-[#D63613]/10 shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#F5F5F5]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#201E1F] uppercase tracking-wider">Domain</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#201E1F] uppercase tracking-wider">Template</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#201E1F] uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#201E1F] uppercase tracking-wider">DNS</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#201E1F] uppercase tracking-wider">Sources</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-[#201E1F] uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($shieldDomains as $shieldDomain)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-[#201E1F]">{{ $shieldDomain->domain }}</div>
                            @if($shieldDomain->cloudflare_nameservers)
                                <div class="text-xs text-gray-500 mt-1">
                                    NS: {{ implode(', ', array_slice($shieldDomain->cloudflare_nameservers, 0, 2)) }}...
                                </div>
                                <button type="button" 
                                        onclick="showNameserverInstructions({{ $shieldDomain->id }}, {{ json_encode($shieldDomain->cloudflare_nameservers) }}, {{ $shieldDomain->dns_configured ? 'true' : 'false' }})"
                                        class="mt-1 text-xs text-blue-600 hover:text-blue-800 underline">
                                    View Instructions
                                </button>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                {{ $shieldDomain->template_name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'active' => 'bg-green-100 text-green-800',
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'inactive' => 'bg-gray-100 text-gray-800',
                                    'failed' => 'bg-red-100 text-red-800',
                                ];
                                $color = $statusColors[$shieldDomain->status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $color }}">
                                {{ ucfirst($shieldDomain->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($shieldDomain->dns_configured)
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Configured</span>
                            @else
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-[#201E1F]">
                            {{ $shieldDomain->sources_count }} source(s)
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('admin.shield-domains.show', $shieldDomain) }}" 
                                   class="text-blue-600 hover:text-blue-800 font-medium">
                                    View
                                </a>
                                <form method="POST" action="{{ route('admin.shield-domains.verify-dns', $shieldDomain) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-blue-600 hover:text-blue-900 text-xs">Verify DNS</button>
                                </form>
                                <form method="POST" action="{{ route('admin.shield-domains.sync-cloudflare', $shieldDomain) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-indigo-600 hover:text-indigo-900 text-xs">Sync</button>
                                </form>
                                <a href="{{ route('admin.shield-domains.edit', $shieldDomain) }}" class="text-[#D63613] hover:text-[#b42f11]">Edit</a>
                                <form method="POST" action="{{ route('admin.shield-domains.destroy', $shieldDomain) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this shield domain? This will also delete it from Cloudflare.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <p class="mt-4 text-sm">No shield domains found.</p>
                            <a href="{{ route('admin.shield-domains.create') }}" class="mt-4 inline-block text-[#D63613] hover:text-[#b42f11]">Create your first shield domain</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($shieldDomains->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $shieldDomains->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Nameserver Instructions Modal -->
<div id="nameserverModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-[#201E1F]">Configure Nameservers</h3>
                <button onclick="closeNameserverModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                <div class="mb-4">
                    <p class="font-semibold mb-3 text-base text-yellow-900">Your Cloudflare Nameservers:</p>
                    <div class="space-y-2" id="nameserver-list">
                        <!-- Nameservers will be inserted here -->
                    </div>
                </div>

                <div class="bg-white rounded-lg p-4 border border-yellow-300 mb-4">
                    <p class="font-semibold mb-3 text-yellow-900">Step-by-Step Instructions:</p>
                    <ol class="list-decimal list-inside space-y-2 text-sm text-yellow-800">
                        <li class="mb-2">
                            <strong>Find the nameservers section</strong> in your domain registrar's control panel
                            <span class="text-xs text-gray-600 block mt-1">(Look for "DNS Settings", "Nameservers", or "DNS Management")</span>
                        </li>
                        <li class="mb-2">
                            <strong>Add both of your assigned Cloudflare nameservers:</strong>
                            <ul class="list-disc list-inside ml-4 mt-1 space-y-1" id="nameserver-instructions-list">
                                <!-- Nameservers will be inserted here -->
                            </ul>
                            <p class="text-xs text-gray-600 mt-2 ml-4">Your nameservers will be in this format: <code class="bg-gray-100 px-1 rounded">crystal.ns.cloudflare.com</code> and <code class="bg-gray-100 px-1 rounded">mark.ns.cloudflare.com</code></p>
                        </li>
                        <li class="mb-2">
                            <strong>Delete your other nameservers</strong>
                            <span class="text-xs text-gray-600 block mt-1">(Remove any existing nameservers that are not from Cloudflare)</span>
                        </li>
                        <li class="mb-2">
                            <strong>Save the changes</strong> and wait 24-48 hours for DNS propagation
                        </li>
                    </ol>
                </div>

                <div id="dns-status-message">
                    <!-- DNS status will be inserted here -->
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button onclick="closeNameserverModal()" class="px-6 py-2 bg-[#D63613] text-white rounded-lg hover:bg-[#b42f11]">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function showNameserverInstructions(domainId, nameservers, dnsConfigured) {
    const modal = document.getElementById('nameserverModal');
    const nameserverList = document.getElementById('nameserver-list');
    const instructionsList = document.getElementById('nameserver-instructions-list');
    const statusMessage = document.getElementById('dns-status-message');
    
    // Clear previous content
    nameserverList.innerHTML = '';
    instructionsList.innerHTML = '';
    
    // Add nameservers with copy buttons
    nameservers.forEach(function(ns) {
        const nsDiv = document.createElement('div');
        nsDiv.className = 'flex items-center justify-between bg-gray-50 p-2 rounded border border-gray-200';
        nsDiv.innerHTML = `
            <code class="text-sm font-mono text-gray-800">${ns}</code>
            <button type="button" 
                    onclick="copyToClipboard('${ns}', this)"
                    class="ml-2 px-3 py-1 text-xs bg-yellow-600 text-white rounded hover:bg-yellow-700 transition-colors">
                Click to copy
            </button>
        `;
        nameserverList.appendChild(nsDiv);
        
        // Add to instructions list
        const li = document.createElement('li');
        li.innerHTML = `<code class="bg-gray-100 px-1 rounded">${ns}</code>`;
        instructionsList.appendChild(li);
    });
    
    // Add DNS status
    if (dnsConfigured) {
        statusMessage.innerHTML = `
            <div class="p-3 bg-green-100 border border-green-300 rounded">
                <p class="text-xs font-semibold text-green-900">✓ DNS Status: Configured</p>
                <p class="text-xs text-green-800 mt-1">Your nameservers are properly configured!</p>
            </div>
        `;
    } else {
        statusMessage.innerHTML = `
            <div class="p-3 bg-yellow-100 border border-yellow-300 rounded">
                <p class="text-xs font-semibold text-yellow-900">⚠️ DNS Status: Not Configured</p>
                <p class="text-xs text-yellow-800 mt-1">Once you've updated the nameservers, click "Verify DNS" to check if they're active.</p>
            </div>
        `;
    }
    
    modal.classList.remove('hidden');
}

function closeNameserverModal() {
    document.getElementById('nameserverModal').classList.add('hidden');
}

function copyToClipboard(text, button) {
    navigator.clipboard.writeText(text).then(function() {
        const originalText = button.textContent;
        button.textContent = 'Copied!';
        button.classList.add('bg-green-600');
        button.classList.remove('bg-yellow-600', 'hover:bg-yellow-700');
        setTimeout(function() {
            button.textContent = originalText;
            button.classList.remove('bg-green-600');
            button.classList.add('bg-yellow-600', 'hover:bg-yellow-700');
        }, 2000);
    });
}

// Close modal when clicking outside
document.getElementById('nameserverModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeNameserverModal();
    }
});
</script>
@endsection

