@extends('layouts.admin')

@section('title', 'Application Logs')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up">
        <div class="lg:flex space-y-4 justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-[#201E1F] mb-2">Application Logs</h1>
                <p class="text-[#201E1F]/60">View and manage Laravel application logs</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.settings.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg text-sm font-semibold flex items-center space-x-2 transition-all duration-300 shadow-md hover:shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Back to Settings</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Filters and Actions -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6">
        <form method="GET" action="{{ route('admin.settings.logs') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Lines to show -->
                <div>
                    <label for="lines" class="block text-sm font-semibold text-[#201E1F] mb-2">Lines to Show</label>
                    <select name="lines" id="lines" 
                            class="w-full bg-white border border-gray-200 rounded-lg px-4 py-3 text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20">
                        <option value="100" {{ $lines == 100 ? 'selected' : '' }}>Last 100 lines</option>
                        <option value="250" {{ $lines == 250 ? 'selected' : '' }}>Last 250 lines</option>
                        <option value="500" {{ $lines == 500 ? 'selected' : '' }}>Last 500 lines</option>
                        <option value="1000" {{ $lines == 1000 ? 'selected' : '' }}>Last 1,000 lines</option>
                        <option value="2000" {{ $lines == 2000 ? 'selected' : '' }}>Last 2,000 lines</option>
                    </select>
                </div>

                <!-- Log Level Filter -->
                <div>
                    <label for="level" class="block text-sm font-semibold text-[#201E1F] mb-2">Log Level</label>
                    <select name="level" id="level" 
                            class="w-full bg-white border border-gray-200 rounded-lg px-4 py-3 text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20">
                        @foreach($logLevels as $logLevel)
                            <option value="{{ $logLevel }}" {{ $level == $logLevel ? 'selected' : '' }}>
                                {{ ucfirst($logLevel) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-semibold text-[#201E1F] mb-2">Search</label>
                    <input type="text" name="search" id="search" value="{{ $search }}" 
                           placeholder="Search logs..."
                           class="w-full bg-white border border-gray-200 rounded-lg px-4 py-3 text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20">
                </div>

                <!-- Actions -->
                <div class="flex items-end space-x-2">
                    <button type="submit" 
                            class="flex-1 bg-gradient-to-r from-[#D63613] to-[#D63613]/80 hover:from-[#D63613]/90 hover:to-[#D63613] text-white px-6 py-3 rounded-lg text-sm font-semibold flex items-center justify-center space-x-2 transition-all duration-300 shadow-md hover:shadow-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <span>Filter</span>
                    </button>
                    
                    @if($fileExists)
                    <form method="POST" action="{{ route('admin.settings.clear-logs') }}" class="flex-1" 
                          onsubmit="return confirm('Are you sure you want to clear all logs? This action cannot be undone.');">
                        @csrf
                        <button type="submit" 
                                class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-3 rounded-lg text-sm font-semibold flex items-center justify-center space-x-2 transition-all duration-300 shadow-md hover:shadow-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            <span>Clear</span>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </form>

        <!-- Log File Info -->
        <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white p-4 rounded-lg border border-gray-200">
                <div class="text-sm text-[#201E1F]/60 mb-1">File Status</div>
                <div class="flex items-center space-x-2">
                    @if($fileExists)
                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                        <span class="text-sm font-semibold text-green-700">Exists</span>
                    @else
                        <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                        <span class="text-sm font-semibold text-red-700">Not Found</span>
                    @endif
                </div>
            </div>
            <div class="bg-white p-4 rounded-lg border border-gray-200">
                <div class="text-sm text-[#201E1F]/60 mb-1">File Size</div>
                <div class="text-sm font-semibold text-[#201E1F]">{{ $fileSizeFormatted }}</div>
            </div>
            <div class="bg-white p-4 rounded-lg border border-gray-200">
                <div class="text-sm text-[#201E1F]/60 mb-1">Total Lines</div>
                <div class="text-sm font-semibold text-[#201E1F]">{{ number_format($totalLines) }}</div>
            </div>
        </div>
    </div>

    <!-- Log Content -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-[#201E1F]">Log Entries</h2>
            <div class="text-sm text-[#201E1F]/60">
                Showing {{ count($logContent) }} of {{ number_format($totalLines) }} lines
            </div>
        </div>

        @if($fileExists && count($logContent) > 0)
            <div class="bg-[#1E1E1E] rounded-lg p-4 overflow-x-auto max-h-[600px] overflow-y-auto font-mono text-sm">
                <div class="space-y-1">
                    @foreach($logContent as $index => $line)
                        @php
                            $lineClass = 'text-gray-300';
                            if (stripos($line, '.ERROR:') !== false || stripos($line, '[error]') !== false) {
                                $lineClass = 'text-red-400';
                            } elseif (stripos($line, '.WARNING:') !== false || stripos($line, '[warning]') !== false) {
                                $lineClass = 'text-yellow-400';
                            } elseif (stripos($line, '.INFO:') !== false || stripos($line, '[info]') !== false) {
                                $lineClass = 'text-blue-400';
                            } elseif (stripos($line, '.DEBUG:') !== false || stripos($line, '[debug]') !== false) {
                                $lineClass = 'text-gray-400';
                            }
                        @endphp
                        <div class="{{ $lineClass }} whitespace-pre-wrap break-words hover:bg-gray-800 px-2 py-1 rounded">
                            {{ htmlspecialchars($line) }}
                        </div>
                    @endforeach
                </div>
            </div>
        @elseif($fileExists && count($logContent) == 0)
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
                <svg class="w-12 h-12 text-yellow-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <p class="text-yellow-700 font-semibold">No log entries found</p>
                <p class="text-yellow-600 text-sm mt-2">Try adjusting your filters or search terms.</p>
            </div>
        @else
            <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                <svg class="w-12 h-12 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-red-700 font-semibold">Log file not found</p>
                <p class="text-red-600 text-sm mt-2">The log file does not exist at: storage/logs/laravel.log</p>
            </div>
        @endif
    </div>
</div>

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

.animate-fade-in-up {
    animation: fade-in-up 0.6s ease-out forwards;
}

/* Custom scrollbar for log viewer */
.bg-\[#1E1E1E\]::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

.bg-\[#1E1E1E\]::-webkit-scrollbar-track {
    background: #2a2a2a;
    border-radius: 4px;
}

.bg-\[#1E1E1E\]::-webkit-scrollbar-thumb {
    background: #555;
    border-radius: 4px;
}

.bg-\[#1E1E1E\]::-webkit-scrollbar-thumb:hover {
    background: #777;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-refresh logs every 30 seconds if no filters are applied
    const urlParams = new URLSearchParams(window.location.search);
    const hasFilters = urlParams.get('level') !== 'all' || urlParams.get('search') !== '';
    
    if (!hasFilters) {
        setInterval(function() {
            // Only refresh if user is at the bottom of the log viewer
            const logContainer = document.querySelector('.bg-\\[\\#1E1E1E\\]');
            if (logContainer) {
                const isAtBottom = logContainer.scrollHeight - logContainer.scrollTop <= logContainer.clientHeight + 100;
                if (isAtBottom) {
                    window.location.reload();
                }
            }
        }, 30000); // 30 seconds
    }
    
    // Highlight search terms
    const searchTerm = '{{ $search }}';
    if (searchTerm) {
        const logLines = document.querySelectorAll('.bg-\\[\\#1E1E1E\\] > div > div');
        logLines.forEach(line => {
            const text = line.textContent;
            if (text.toLowerCase().includes(searchTerm.toLowerCase())) {
                line.classList.add('bg-yellow-900', 'bg-opacity-50');
            }
        });
    }
});
</script>
@endsection

