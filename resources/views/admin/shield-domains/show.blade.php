@extends('layouts.admin')

@section('title', 'Shield Domain - ' . $shieldDomain->domain)

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up">
        <div class="lg:flex space-y-4 justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-gradient-to-br from-[#D63613] to-[#D63613]/80 rounded-xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-[#201E1F] mb-1">{{ $shieldDomain->domain }}</h1>
                    <p class="text-[#201E1F]/60">Shield Domain Management & Configuration</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.shield-domains.edit', $shieldDomain) }}" 
                   class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-3 rounded-lg text-sm font-semibold flex items-center space-x-2 transition-all duration-300 shadow-md hover:shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <span>Edit</span>
                </a>
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

    <!-- Success/Error Messages -->
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

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-sm text-red-800">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    @if(session('warning'))
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <p class="text-sm text-yellow-800">{{ session('warning') }}</p>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <p class="text-sm font-semibold text-red-800">Errors:</p>
                <ul class="text-sm text-red-700 mt-1 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Domain Information -->
            <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up" style="animation-delay: 0.1s;">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-[#201E1F]">Domain Information</h2>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Domain</label>
                        <p class="text-sm text-[#201E1F] bg-white rounded-lg px-4 py-3 border border-gray-200 font-mono">
                            {{ $shieldDomain->domain }}
                        </p>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Template</label>
                        <p class="text-sm text-[#201E1F] bg-white rounded-lg px-4 py-3 border border-gray-200">
                            {{ ucfirst(str_replace('-', ' ', $shieldDomain->template_name)) }}
                        </p>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Status</label>
                        @php
                            $statusColors = [
                                'active' => 'bg-green-100 text-green-800',
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'inactive' => 'bg-gray-100 text-gray-800',
                                'failed' => 'bg-red-100 text-red-800',
                            ];
                            $color = $statusColors[$shieldDomain->status] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="inline-block px-3 py-2 text-sm font-medium rounded-lg {{ $color }}">
                            {{ ucfirst($shieldDomain->status) }}
                        </span>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">DNS Configured</label>
                        @if($shieldDomain->dns_configured)
                            <span class="inline-block px-3 py-2 text-sm font-medium rounded-lg bg-green-100 text-green-800">
                                ✓ Configured
                            </span>
                            @if($shieldDomain->dns_configured_at)
                                <p class="text-xs text-gray-500 mt-1">Configured on {{ $shieldDomain->dns_configured_at->format('M d, Y H:i') }}</p>
                            @endif
                        @else
                            <span class="inline-block px-3 py-2 text-sm font-medium rounded-lg bg-yellow-100 text-yellow-800">
                                ⚠️ Pending
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Cloudflare Configuration -->
            <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up" style="animation-delay: 0.2s;">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-orange-400 to-orange-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-[#201E1F]">Cloudflare Configuration</h2>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-[#201E1F]/60">Zone ID</label>
                            <p class="text-sm text-[#201E1F] bg-white rounded-lg px-4 py-3 border border-gray-200 font-mono">
                                {{ $shieldDomain->cloudflare_zone_id ?: 'Not created yet' }}
                            </p>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-[#201E1F]/60">Pages Project ID</label>
                            <p class="text-sm text-[#201E1F] bg-white rounded-lg px-4 py-3 border border-gray-200 font-mono">
                                {{ $shieldDomain->cloudflare_pages_project_id ?: 'Not bound yet' }}
                            </p>
                        </div>
                    </div>

                    @if($shieldDomain->cloudflare_nameservers)
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60">Nameservers</label>
                        <div class="bg-white rounded-lg border border-gray-200 p-4 space-y-2">
                            @foreach($shieldDomain->cloudflare_nameservers as $nameserver)
                            <div class="flex items-center justify-between">
                                <code class="text-sm font-mono text-gray-800">{{ $nameserver }}</code>
                                <button type="button" 
                                        onclick="copyToClipboard('{{ $nameserver }}', this)"
                                        class="px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                                    Copy
                                </button>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Check Status & Actions -->
            <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up" style="animation-delay: 0.3s;">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-[#201E1F]">Actions</h2>
                    </div>
                </div>

                <div class="space-y-4">
                    <!-- Create Zone Button -->
                    @if(!$shieldDomain->cloudflare_zone_id)
                    <form method="POST" action="{{ route('admin.shield-domains.create-zone', $shieldDomain) }}" class="inline">
                        @csrf
                        <button type="submit" 
                                class="w-full px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold transition-all duration-200 flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span>Create Cloudflare Zone</span>
                        </button>
                    </form>
                    <p class="text-sm text-gray-600 text-center">Create Cloudflare zone and get nameservers for this domain</p>
                    @endif

                    <!-- Check Status Button -->
                    @if($shieldDomain->cloudflare_zone_id)
                    <form method="POST" action="{{ route('admin.shield-domains.verify-dns', $shieldDomain) }}" class="inline">
                        @csrf
                        <button type="submit" 
                                class="w-full px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold transition-all duration-200 flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Check Nameserver Status</span>
                        </button>
                    </form>
                    <p class="text-sm text-gray-600 text-center">
                        @if(!$shieldDomain->dns_configured)
                            Verify if DNS nameservers are configured correctly at your registrar
                        @else
                            Re-check DNS nameserver status
                        @endif
                    </p>
                    @endif

                    <!-- Configure DNS Records Button -->
                    @if($shieldDomain->cloudflare_zone_id)
                    <form method="POST" action="{{ route('admin.shield-domains.configure-dns', $shieldDomain) }}" class="inline" id="configure-dns-form">
                        @csrf
                        <button type="submit" 
                                class="w-full px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold transition-all duration-200 flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                            </svg>
                            <span>Configure DNS Records</span>
                        </button>
                    </form>
                    <p class="text-sm text-gray-600 text-center">Manually create DNS records pointing to main SaaS server</p>
                    @endif

                    <!-- Sync Cloudflare Button -->
                    @if($shieldDomain->cloudflare_zone_id)
                    <form method="POST" action="{{ route('admin.shield-domains.sync-cloudflare', $shieldDomain) }}" class="inline">
                        @csrf
                        <button type="submit" 
                                class="w-full px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 font-semibold transition-all duration-200 flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            <span>Sync with Cloudflare</span>
                        </button>
                    </form>
                    <p class="text-sm text-gray-600 text-center">Refresh nameservers from Cloudflare</p>
                    @endif
                </div>
            </div>

            <!-- Danger Zone -->
            @if($shieldDomain->cloudflare_zone_id)
            <div class="bg-red-50 border border-red-200 rounded-xl p-6 animate-fade-in-up" style="animation-delay: 0.5s;">
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

            <!-- Nameserver Instructions -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 animate-fade-in-up" style="animation-delay: 0.4s;">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="flex-1 text-sm text-yellow-800">
                        <p class="font-semibold mb-3 text-base">Configure Nameservers at Your Domain Registrar</p>
                        
                        @if(!$shieldDomain->cloudflare_zone_id)
                        <div class="bg-white rounded-lg p-4 mb-4 border border-yellow-300">
                            <p class="font-semibold mb-2 text-yellow-900">Setup Required</p>
                            <p class="text-sm text-yellow-800">Click "Check Status" above to create the Cloudflare zone and retrieve your nameservers.</p>
                        </div>
                        @elseif($shieldDomain->cloudflare_nameservers && count($shieldDomain->cloudflare_nameservers) > 0)
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
                            <p class="text-sm text-yellow-800">Click "Sync with Cloudflare" to retrieve nameservers.</p>
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
                                    @if($shieldDomain->cloudflare_nameservers && count($shieldDomain->cloudflare_nameservers) > 0)
                                    <ul class="list-disc list-inside ml-4 mt-1 space-y-1">
                                        @foreach($shieldDomain->cloudflare_nameservers as $nameserver)
                                        <li><code class="bg-gray-100 px-1 rounded">{{ $nameserver }}</code></li>
                                        @endforeach
                                    </ul>
                                    @else
                                    <span class="text-xs text-gray-600 block mt-1">(Nameservers will appear here once retrieved from Cloudflare)</span>
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
                                <li class="mb-2">
                                    <strong>Click "Check Status"</strong> to verify DNS is configured correctly
                                </li>
                            </ol>
                        </div>

                        @if(!$shieldDomain->cloudflare_zone_id)
                            <div class="mt-4 p-3 bg-blue-100 border border-blue-300 rounded">
                                <p class="text-xs font-semibold text-blue-900">ℹ️ Setup Required</p>
                                <p class="text-xs text-blue-800 mt-1">Click "Check Status" above to create the Cloudflare zone and get your nameservers.</p>
                            </div>
                        @elseif(!$shieldDomain->dns_configured)
                            <div class="mt-4 p-3 bg-yellow-100 border border-yellow-300 rounded">
                                <p class="text-xs font-semibold text-yellow-900">⚠️ DNS Status: Not Configured</p>
                                <p class="text-xs text-yellow-800 mt-1">Once you've updated the nameservers at your registrar, click "Check Status" to verify they're active.</p>
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
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-8">
            <!-- Quick Stats -->
            <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up" style="animation-delay: 0.5s;">
                <h3 class="text-lg font-semibold text-[#201E1F] mb-4">Quick Info</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-[#201E1F]/60">Created</p>
                        <p class="text-sm font-medium text-[#201E1F]">{{ $shieldDomain->created_at->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-[#201E1F]/60">Last Updated</p>
                        <p class="text-sm font-medium text-[#201E1F]">{{ $shieldDomain->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                    @if($shieldDomain->sources_count > 0)
                    <div>
                        <p class="text-sm text-[#201E1F]/60">Linked Sources</p>
                        <p class="text-sm font-medium text-[#201E1F]">{{ $shieldDomain->sources_count }} source(s)</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Related Sources -->
            @if($shieldDomain->sources_count > 0)
            <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up" style="animation-delay: 0.6s;">
                <h3 class="text-lg font-semibold text-[#201E1F] mb-4">Linked Sources</h3>
                <div class="space-y-2">
                    @foreach($shieldDomain->sources as $source)
                    <a href="{{ route('admin.sources.edit', $source) }}" 
                       class="block p-3 bg-white rounded-lg border border-gray-200 hover:border-blue-300 hover:shadow-sm transition-all">
                        <p class="text-sm font-medium text-[#201E1F]">{{ $source->name }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $source->return_url }}</p>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
console.log('Shield Domain Management Page Loaded');
console.log('Domain:', '{{ $shieldDomain->domain }}');
console.log('Zone ID:', '{{ $shieldDomain->cloudflare_zone_id ?? "Not set" }}');
console.log('Status:', '{{ $shieldDomain->status }}');
console.log('DNS Configured:', {{ $shieldDomain->dns_configured ? 'true' : 'false' }});

function copyToClipboard(text, button) {
    console.log('Copying to clipboard:', text);
    navigator.clipboard.writeText(text).then(function() {
        console.log('Successfully copied to clipboard');
        const originalText = button.textContent;
        button.textContent = 'Copied!';
        button.classList.add('bg-green-600');
        button.classList.remove('bg-yellow-600', 'bg-blue-600', 'hover:bg-yellow-700', 'hover:bg-blue-700');
        setTimeout(function() {
            button.textContent = originalText;
            button.classList.remove('bg-green-600');
            if (button.classList.contains('bg-yellow-600') || button.classList.contains('bg-blue-600')) {
                // Restore original class
            } else {
                button.classList.add('bg-yellow-600', 'hover:bg-yellow-700');
            }
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

    // Log all form submissions
    const forms = document.querySelectorAll('form');
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
@endsection

