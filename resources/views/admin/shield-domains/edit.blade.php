@extends('layouts.admin')

@section('title', 'Edit Shield Domain')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up">
        <div class="lg:flex space-y-4 justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-gradient-to-br from-[#D63613] to-[#D63613]/80 rounded-xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-[#201E1F] mb-1">Edit Shield Domain</h1>
                    <p class="text-[#201E1F]/60">Update shield domain settings</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.shield-domains.index') }}" 
                   class="bg-white hover:bg-gray-50 text-[#201E1F]/80 hover:text-[#201E1F] border border-gray-200 px-6 py-3 rounded-lg text-sm font-semibold flex items-center space-x-2 transition-all duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Back</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-sm text-green-800">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <!-- Form -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md animate-fade-in-up" style="animation-delay: 0.1s;">
        <form method="POST" action="{{ route('admin.shield-domains.update', $shieldDomain) }}" class="p-6">
            @csrf
            @method('PUT')

            <!-- Domain -->
            <div class="mb-6">
                <label for="domain" class="block text-sm font-medium text-[#201E1F]/60 mb-2">Domain *</label>
                <input type="text" 
                       id="domain" 
                       name="domain" 
                       value="{{ old('domain', $shieldDomain->domain) }}"
                       class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] placeholder-[#201E1F]/40 transition-all duration-200 @error('domain') border-red-500 @enderror"
                       required>
                @error('domain')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Template -->
            <div class="mb-6">
                <label for="template_name" class="block text-sm font-medium text-[#201E1F]/60 mb-2">Template *</label>
                <select id="template_name" 
                        name="template_name" 
                        class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] @error('template_name') border-red-500 @enderror"
                        required>
                    @foreach($templates as $template)
                        <option value="{{ $template }}" {{ old('template_name', $shieldDomain->template_name) == $template ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('-', ' ', $template)) }}
                        </option>
                    @endforeach
                </select>
                @error('template_name')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status (Read-only) -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-[#201E1F]/60 mb-2">Status</label>
                <div class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-[#201E1F]">
                    @php
                        $statusColors = [
                            'active' => 'text-green-700 bg-green-100',
                            'pending' => 'text-yellow-700 bg-yellow-100',
                            'inactive' => 'text-gray-700 bg-gray-100',
                            'failed' => 'text-red-700 bg-red-100',
                        ];
                        $color = $statusColors[$shieldDomain->status] ?? 'text-gray-700 bg-gray-100';
                    @endphp
                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $color }}">
                        {{ ucfirst($shieldDomain->status) }}
                    </span>
                </div>
                <p class="mt-1 text-xs text-[#201E1F]/60">Status is automatically managed by the system</p>
            </div>

            <!-- Create Zone Button -->
            @if(!$shieldDomain->cloudflare_zone_id)
            <div class="mb-6">
                <form method="POST" action="{{ route('admin.shield-domains.create-zone', $shieldDomain) }}" class="inline">
                    @csrf
                    <button type="submit" 
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold transition-all duration-200 flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span>Create Cloudflare Zone</span>
                    </button>
                </form>
                <p class="mt-2 text-sm text-gray-600">Create Cloudflare zone and get nameservers for this domain</p>
            </div>
            @endif

            <!-- Check Status Button -->
            @if($shieldDomain->cloudflare_zone_id)
            <div class="mb-6">
                <form method="POST" action="{{ route('admin.shield-domains.verify-dns', $shieldDomain) }}" class="inline">
                    @csrf
                    <button type="submit" 
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold transition-all duration-200 flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Check Nameserver Status</span>
                    </button>
                </form>
                <p class="mt-2 text-sm text-gray-600">
                    @if(!$shieldDomain->dns_configured)
                        Verify if DNS nameservers are configured correctly at your registrar
                    @else
                        Re-check DNS nameserver status
                    @endif
                </p>
            </div>
            @endif

            <!-- DNS Info - Always show instructions -->
            @php
                $hasNameservers = !empty($shieldDomain->cloudflare_nameservers) && 
                                  (is_array($shieldDomain->cloudflare_nameservers) ? count($shieldDomain->cloudflare_nameservers) > 0 : true);
            @endphp
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="flex-1 text-sm text-yellow-800">
                        <p class="font-semibold mb-3 text-base">Configure Nameservers at Your Domain Registrar</p>
                        
                        @if($hasNameservers)
                        <div class="bg-white rounded-lg p-4 mb-4 border border-yellow-300">
                            <p class="font-semibold mb-2 text-yellow-900">Your Cloudflare Nameservers:</p>
                            <div class="space-y-2">
                                @foreach($shieldDomain->cloudflare_nameservers as $nameserver)
                                <div class="flex items-center justify-between bg-gray-50 p-2 rounded border border-gray-200">
                                    <code class="text-sm font-mono text-gray-800">{{ $nameserver }}</code>
                                    <button type="button" 
                                            onclick="copyToClipboard('{{ $nameserver }}', this)"
                                            class="ml-2 px-3 py-1 text-xs bg-yellow-600 text-white rounded hover:bg-yellow-700 transition-colors">
                                        Click to copy
                                    </button>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @else
                        <div class="bg-white rounded-lg p-4 mb-4 border border-yellow-300">
                            <p class="font-semibold mb-2 text-yellow-900">Nameservers</p>
                            <p class="text-sm text-yellow-800">Click "Check Status" above to create the Cloudflare zone and retrieve your nameservers.</p>
                        </div>
                        @endif

                        <div class="bg-white rounded-lg p-4 border border-yellow-300">
                            <p class="font-semibold mb-3 text-yellow-900">Step-by-Step Instructions:</p>
                            <ol class="list-decimal list-inside space-y-2 text-sm">
                                <li class="mb-2">
                                    <strong>Find the nameservers section</strong> in your domain registrar's control panel
                                    <span class="text-xs text-gray-600 block mt-1">(Look for "DNS Settings", "Nameservers", or "DNS Management")</span>
                                </li>
                                <li class="mb-2">
                                    <strong>Add both of your assigned Cloudflare nameservers:</strong>
                                    @if($hasNameservers)
                                    <ul class="list-disc list-inside ml-4 mt-1 space-y-1">
                                        @foreach($shieldDomain->cloudflare_nameservers as $nameserver)
                                        <li><code class="bg-gray-100 px-1 rounded">{{ $nameserver }}</code></li>
                                        @endforeach
                                    </ul>
                                    @else
                                    <span class="text-xs text-gray-600 block mt-1">(Nameservers will appear here once synced from Cloudflare)</span>
                                    <p class="text-xs text-gray-600 mt-2">Your nameservers will be in this format: <code class="bg-gray-100 px-1 rounded">crystal.ns.cloudflare.com</code> and <code class="bg-gray-100 px-1 rounded">mark.ns.cloudflare.com</code></p>
                                    @endif
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

                        @if(!$shieldDomain->cloudflare_zone_id)
                            <div class="mt-4 p-3 bg-blue-100 border border-blue-300 rounded">
                                <p class="text-xs font-semibold text-blue-900">ℹ️ Setup Required</p>
                                <p class="text-xs text-blue-800 mt-1">Click "Create Cloudflare Zone" above to create the zone and get your nameservers.</p>
                            </div>
                        @elseif(!$shieldDomain->dns_configured)
                            <div class="mt-4 p-3 bg-yellow-100 border border-yellow-300 rounded">
                                <p class="text-xs font-semibold text-yellow-900">⚠️ DNS Status: Not Configured</p>
                                <p class="text-xs text-yellow-800 mt-1">Once you've updated the nameservers at your registrar, click "Check Nameserver Status" to verify they're active.</p>
                            </div>
                        @else
                            <div class="mt-4 p-3 bg-green-100 border border-green-300 rounded">
                                <p class="text-xs font-semibold text-green-900">✓ DNS Status: Configured</p>
                                <p class="text-xs text-green-800 mt-1">Your nameservers are properly configured!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Configure DNS Records Button -->
            @if($shieldDomain->cloudflare_zone_id)
            <div class="mb-6">
                <form method="POST" action="{{ route('admin.shield-domains.configure-dns', $shieldDomain) }}" class="inline" id="configure-dns-form">
                    @csrf
                    <button type="submit" 
                            class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold transition-all duration-200 flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                        </svg>
                        <span>Configure DNS Records</span>
                    </button>
                </form>
                <p class="mt-2 text-sm text-gray-600">Create DNS records (CNAME) pointing to main SaaS server</p>
            </div>
            @endif

            <!-- Danger Zone -->
            @if($shieldDomain->cloudflare_zone_id)
            <div class="bg-red-50 border border-red-200 rounded-xl p-6 mb-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-red-400 to-red-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-red-900">Danger Zone</h3>
                            <p class="text-sm text-red-700">These actions are irreversible. Please be certain before proceeding.</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <!-- Delete DNS Records Button -->
                    <form method="POST" action="{{ route('admin.shield-domains.delete-dns-records', $shieldDomain) }}" 
                          onsubmit="return confirm('Are you sure you want to delete the DNS records pointing to the main server? This will break the domain connection.');">
                        @csrf
                        <button type="submit" 
                                class="w-full px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 font-semibold transition-all duration-200 flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            <span>Delete DNS Records</span>
                        </button>
                    </form>
                    <p class="text-sm text-red-600 text-center">Delete CNAME records pointing to main SaaS server</p>

                    <!-- Delete Zone Button -->
                    <form method="POST" action="{{ route('admin.shield-domains.delete-zone', $shieldDomain) }}" 
                          onsubmit="return confirm('Are you sure you want to delete the Cloudflare zone? This will remove the domain from Cloudflare and cannot be undone.');">
                        @csrf
                        <button type="submit" 
                                class="w-full px-6 py-3 bg-red-700 text-white rounded-lg hover:bg-red-800 font-semibold transition-all duration-200 flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <span>Delete Cloudflare Zone</span>
                        </button>
                    </form>
                    <p class="text-sm text-red-600 text-center">Permanently delete the Cloudflare zone for this domain</p>
                </div>
            </div>
            @endif

            <script>
            console.log('Shield Domain Edit Page Loaded');
            console.log('Domain:', '{{ $shieldDomain->domain }}');
            console.log('Zone ID:', '{{ $shieldDomain->cloudflare_zone_id ?? "Not set" }}');
            console.log('Status:', '{{ $shieldDomain->status }}');
            console.log('DNS Configured:', {{ $shieldDomain->dns_configured ? 'true' : 'false' }});
            console.log('Nameservers:', {{ json_encode($shieldDomain->cloudflare_nameservers) }});

            function copyToClipboard(text, button) {
                console.log('Copying to clipboard:', text);
                navigator.clipboard.writeText(text).then(function() {
                    console.log('Successfully copied to clipboard');
                    const originalText = button.textContent;
                    button.textContent = 'Copied!';
                    button.classList.add('bg-green-600');
                    button.classList.remove('bg-yellow-600', 'hover:bg-yellow-700');
                    setTimeout(function() {
                        button.textContent = originalText;
                        button.classList.remove('bg-green-600');
                        button.classList.add('bg-yellow-600', 'hover:bg-yellow-700');
                    }, 2000);
                }).catch(function(err) {
                    console.error('Failed to copy to clipboard:', err);
                });
            }

            // Add form submission logging
            document.addEventListener('DOMContentLoaded', function() {
                const configureDnsForm = document.getElementById('configure-dns-form');
                if (configureDnsForm) {
                    configureDnsForm.addEventListener('submit', function(e) {
                        console.log('=== Configure DNS Form Submitted ===');
                        console.log('Domain:', '{{ $shieldDomain->domain }}');
                        console.log('Zone ID:', '{{ $shieldDomain->cloudflare_zone_id }}');
                        console.log('Timestamp:', new Date().toISOString());
                        console.log('Starting DNS records configuration...');
                    });
                }

                // Log Check Status form
                const checkStatusForms = document.querySelectorAll('form[action*="verify-dns"]');
                checkStatusForms.forEach(function(form) {
                    form.addEventListener('submit', function(e) {
                        console.log('=== Check Status Form Submitted ===');
                        console.log('Domain:', '{{ $shieldDomain->domain }}');
                        console.log('Zone ID:', '{{ $shieldDomain->cloudflare_zone_id ?? "Not set" }}');
                        console.log('Timestamp:', new Date().toISOString());
                    });
                });

                // Log all form submissions
                const forms = document.querySelectorAll('form');
                console.log(`Total forms found: ${forms.length}`);
                forms.forEach(function(form, index) {
                    form.addEventListener('submit', function(e) {
                        const action = form.getAttribute('action');
                        const method = form.querySelector('input[name="_method"]')?.value || form.method;
                        console.log(`Form ${index + 1} submitted:`, {
                            action: action,
                            method: method,
                            timestamp: new Date().toISOString()
                        });
                    });
                });
            });
            </script>

            <!-- Submit -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.shield-domains.index') }}" 
                   class="px-6 py-3 border border-gray-200 rounded-lg text-[#201E1F]/80 hover:bg-gray-50 transition-all duration-200">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-[#D63613] text-white rounded-lg hover:bg-[#b42f11] font-semibold transition-all duration-200">
                    Update Shield Domain
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

