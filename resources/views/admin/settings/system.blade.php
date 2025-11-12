@extends('layouts.admin')

@section('title', 'System Information')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-semibold text-gray-900">System Information</h1>
        <a href="{{ route('admin.settings.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
            Back to Settings
        </a>
    </div>

    <!-- System Information -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Server Information</h2>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">PHP Version</h3>
                    <p class="text-lg font-semibold text-gray-900">{{ $systemInfo['php_version'] }}</p>
                </div>
                
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Laravel Version</h3>
                    <p class="text-lg font-semibold text-gray-900">{{ $systemInfo['laravel_version'] }}</p>
                </div>
                
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Server Software</h3>
                    <p class="text-lg font-semibold text-gray-900">{{ $systemInfo['server_software'] }}</p>
                </div>
                
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Database</h3>
                    <p class="text-lg font-semibold text-gray-900">{{ ucfirst($systemInfo['database_type']) }}</p>
                </div>
                
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Storage Disk</h3>
                    <p class="text-lg font-semibold text-gray-900">{{ ucfirst($systemInfo['storage_disk']) }}</p>
                </div>
                
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Cache Driver</h3>
                    <p class="text-lg font-semibold text-gray-900">{{ ucfirst($systemInfo['cache_driver']) }}</p>
                </div>
                
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Queue Driver</h3>
                    <p class="text-lg font-semibold text-gray-900">{{ ucfirst($systemInfo['queue_driver']) }}</p>
                </div>
                
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Mail Driver</h3>
                    <p class="text-lg font-semibold text-gray-900">{{ ucfirst($systemInfo['mail_driver']) }}</p>
                </div>
                
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Current Time</h3>
                    <p class="text-lg font-semibold text-gray-900">{{ now()->format('Y-m-d H:i:s') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Platform Statistics -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Platform Statistics</h2>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Users -->
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600">{{ number_format($statistics['total_users']) }}</div>
                    <div class="text-sm text-gray-500">Total Users</div>
                    <div class="mt-2 text-xs text-gray-400">
                        {{ number_format($statistics['total_clients']) }} Clients, 
                        {{ number_format($statistics['total_resellers']) }} Resellers
                    </div>
                </div>
                
                <!-- Orders -->
                <div class="text-center">
                    <div class="text-3xl font-bold text-green-600">{{ number_format($statistics['total_orders']) }}</div>
                    <div class="text-sm text-gray-500">Total Orders</div>
                    <div class="mt-2 text-xs text-gray-400">
                        {{ number_format($statistics['active_orders']) }} Active
                    </div>
                </div>
                
                <!-- Revenue -->
                <div class="text-center">
                    <div class="text-3xl font-bold text-purple-600">${{ number_format($statistics['total_revenue'], 2) }}</div>
                    <div class="text-sm text-gray-500">Total Revenue</div>
                    <div class="mt-2 text-xs text-gray-400">
                        From active orders
                    </div>
                </div>
                
                <!-- Blog Posts -->
                <div class="text-center">
                    <div class="text-3xl font-bold text-orange-600">{{ number_format($statistics['total_blog_posts']) }}</div>
                    <div class="text-sm text-gray-500">Blog Posts</div>
                    <div class="mt-2 text-xs text-gray-400">
                        {{ number_format($statistics['published_posts']) }} Published
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- System Health -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">System Health</h2>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Database Connection -->
                <div class="flex items-center p-4 bg-green-50 rounded-lg">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">Database</h3>
                        <p class="text-sm text-green-600">Connected</p>
                    </div>
                </div>
                
                <!-- Storage -->
                <div class="flex items-center p-4 bg-green-50 rounded-lg">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">Storage</h3>
                        <p class="text-sm text-green-600">Accessible</p>
                    </div>
                </div>
                
                <!-- Cache -->
                <div class="flex items-center p-4 bg-green-50 rounded-lg">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">Cache</h3>
                        <p class="text-sm text-green-600">Working</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">System Logs</h2>
        </div>
        
        <div class="p-6">
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="text-sm text-gray-600">
                    <p class="mb-2"><strong>Application Logs:</strong> Check <code>storage/logs/laravel.log</code> for detailed application logs.</p>
                    <p class="mb-2"><strong>Web Server Logs:</strong> Check your web server's error logs for server-related issues.</p>
                    <p><strong>Database Logs:</strong> Monitor your database server logs for performance and error tracking.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Maintenance Tools -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Maintenance Tools</h2>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-blue-800 mb-2">Performance Optimization</h3>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li>• Clear application cache regularly</li>
                        <li>• Monitor database query performance</li>
                        <li>• Optimize images and static assets</li>
                        <li>• Use CDN for better global performance</li>
                    </ul>
                </div>
                
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-green-800 mb-2">Security Best Practices</h3>
                    <ul class="text-sm text-green-700 space-y-1">
                        <li>• Keep Laravel and PHP updated</li>
                        <li>• Regular security audits</li>
                        <li>• Monitor failed login attempts</li>
                        <li>• Use HTTPS for all connections</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
