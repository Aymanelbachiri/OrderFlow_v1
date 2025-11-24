@extends('layouts.admin')

@section('title', 'WordPress Integration')

@section('content')
<div class="space-y-6">
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- Header -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6">
        <div class="lg:flex space-y-4 justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-[#201E1F] mb-2">WordPress Integration</h1>
                <p class="text-[#201E1F]/60">Manage API tokens and integrate with WordPress</p>
            </div>
            <div>
                <a href="{{ route('admin.wordpress-integration.download-plugin') }}" 
                   class="inline-flex items-center justify-center bg-gradient-to-r from-[#D63613] to-[#D63613]/80 hover:from-[#D63613]/90 hover:to-[#D63613] text-white px-6 py-3 rounded-lg text-sm font-semibold shadow-md hover:shadow-lg transition-all duration-300">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download Plugin
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Start Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- API Information Card -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-md p-6">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-[#201E1F]">API Information</h2>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">API Base URL</label>
                    <div class="flex items-center space-x-2">
                        <input type="text" 
                               value="{{ url('/api') }}" 
                               readonly 
                               class="flex-1 bg-gray-50 border border-gray-300 rounded-lg px-4 py-2 text-sm font-mono"
                               id="api-url">
                        <button onclick="copyToClipboard('api-url')" 
                                class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                            Copy
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Products Endpoint</label>
                    <div class="flex items-center space-x-2">
                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded">GET</span>
                        <code class="flex-1 bg-gray-50 border border-gray-300 rounded-lg px-4 py-2 text-sm font-mono">{{ url('/api/wordpress/products') }}</code>
                        <button onclick="copyToClipboard('api-endpoint')" 
                                class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                            Copy
                        </button>
                    </div>
                    <input type="text" 
                           value="{{ url('/api/wordpress/products') }}" 
                           readonly 
                           class="hidden"
                           id="api-endpoint">
                </div>
            </div>
        </div>

        <!-- Quick Setup Instructions -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-4 flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Quick Setup</span>
            </h3>
            <ol class="list-decimal list-inside space-y-2 text-sm text-blue-800">
                <li>Download and install the WordPress plugin</li>
                <li>Generate an API token below</li>
                <li>Enter the API URL and token in WordPress</li>
                <li>Click "Sync Products" to create pages</li>
            </ol>
        </div>
    </div>

    <!-- Token Management Section -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-[#201E1F]">API Token Management</h2>
            </div>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Generate Token Form -->
                <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
                    <h3 class="text-lg font-semibold text-[#201E1F] mb-4">Generate New Token</h3>
                    <form method="POST" action="{{ route('admin.wordpress-integration.generate-token') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label for="token_name" class="block text-sm font-semibold text-gray-700 mb-2">Token Name (Optional)</label>
                            <input type="text" 
                                   id="token_name" 
                                   name="token_name" 
                                   value="wordpress-integration-{{ now()->format('Y-m-d') }}"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm"
                                   placeholder="wordpress-integration-2024-01-01">
                            <p class="text-xs text-gray-500 mt-1">A descriptive name to help identify this token</p>
                        </div>
                        
                        <div>
                            <label for="source_id" class="block text-sm font-semibold text-gray-700 mb-2">Source (Required for iframe access)</label>
                            <select id="source_id" 
                                    name="source_id" 
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm"
                                    required>
                                <option value="">-- Select a Source --</option>
                                @foreach($sources as $source)
                                    <option value="{{ $source->id }}">{{ $source->name }}</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">The source will be automatically added to all checkout URLs as <code>?source=</code> parameter</p>
                        </div>
                        
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-[#D63613] to-[#D63613]/80 hover:from-[#D63613]/90 hover:to-[#D63613] text-white px-6 py-3 rounded-lg text-sm font-semibold transition-all duration-300 shadow-md hover:shadow-lg flex items-center justify-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span>Generate New Token</span>
                        </button>
                    </form>

                    @if(session('new_token'))
                        <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <p class="text-sm font-semibold text-yellow-800 mb-2">⚠️ Important: Save this token now. You won't be able to see it again!</p>
                            <div class="flex items-center space-x-2">
                                <input type="text" 
                                       value="{{ session('new_token') }}" 
                                       readonly 
                                       class="flex-1 bg-white border border-yellow-300 rounded-lg px-4 py-2 text-sm font-mono"
                                       id="new-token">
                                <button onclick="copyToClipboard('new-token')" 
                                        class="bg-yellow-100 hover:bg-yellow-200 text-yellow-800 px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                                    Copy
                                </button>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Existing Tokens -->
                <div>
                    <h3 class="text-lg font-semibold text-[#201E1F] mb-4">Existing Tokens</h3>
                    @if($tokens->isEmpty())
                        <div class="text-center py-8 text-gray-500 bg-gray-50 rounded-lg border border-gray-200">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                            </svg>
                            <p class="text-sm">No API tokens generated yet.</p>
                            <p class="text-xs mt-1">Generate one to get started.</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($tokens as $token)
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <div class="flex items-start justify-between mb-2">
                                        <div class="flex-1">
                                            <h4 class="text-sm font-semibold text-gray-900">{{ $token['name'] }}</h4>
                                            <div class="flex items-center space-x-2 mt-1">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ implode(', ', $token['abilities']) }}
                                                </span>
                                                @if($token['source'])
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                        Source: {{ $token['source']['name'] }}
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        No Source
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <form method="POST" 
                                              action="{{ route('admin.wordpress-integration.revoke-token', $token['id']) }}" 
                                              class="inline"
                                              onsubmit="return confirm('Are you sure you want to revoke this token?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900 text-sm font-medium">
                                                Revoke
                                            </button>
                                        </form>
                                    </div>
                                    <div class="text-xs text-gray-500 space-y-1">
                                        <p>Created: {{ $token['created_at']->format('Y-m-d H:i') }}</p>
                                        <p>Last Used: {{ $token['last_used_at'] ? $token['last_used_at']->format('Y-m-d H:i') : 'Never' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    element.select();
    element.setSelectionRange(0, 99999);
    document.execCommand('copy');
    
    const button = event.target;
    const originalText = button.textContent;
    button.textContent = 'Copied!';
    button.classList.add('bg-green-100', 'text-green-800');
    
    setTimeout(() => {
        button.textContent = originalText;
        button.classList.remove('bg-green-100', 'text-green-800');
    }, 2000);
}
</script>
@endsection

