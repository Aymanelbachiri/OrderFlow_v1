@extends('layouts.admin')

@section('title', 'Agent Management')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up">
        <div class="lg:flex space-y-4 justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-[#201E1F] mb-2">Agent Management</h1>
                <p class="text-[#201E1F]/60">Create and manage agents with source-based access control</p>
            </div>
            <a href="{{ route('admin.agents.create') }}"
               class="bg-gradient-to-r from-[#D63613] to-[#D63613]/80 hover:from-[#D63613]/90 hover:to-[#D63613] text-white px-6 py-3 rounded-lg text-sm font-semibold flex items-center space-x-2 transition-all duration-300 shadow-md hover:shadow-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span>Add New Agent</span>
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up" style="animation-delay: 0.1s;">
        <form method="GET" action="{{ route('admin.agents.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-[#201E1F]/60 mb-2">Search Agents</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search by name or email..."
                       class="w-full rounded-lg bg-white border border-gray-200 text-[#201E1F] placeholder-[#201E1F]/40 focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-[#201E1F]/60 mb-2">Status</label>
                <select name="status" class="w-full rounded-lg bg-white border border-gray-200 text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="w-full bg-gradient-to-r from-[#D63613] to-[#D63613]/80 hover:from-[#D63613]/90 hover:to-[#D63613] text-white px-4 py-3 rounded-lg text-sm font-semibold transition-all duration-300 shadow-md hover:shadow-lg">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Agents Table -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md overflow-hidden animate-fade-in-up" style="animation-delay: 0.2s;">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="text-left py-4 px-6 text-sm font-semibold text-[#201E1F]/60 uppercase tracking-wider">Agent</th>
                        <th class="text-left py-4 px-6 text-sm font-semibold text-[#201E1F]/60 uppercase tracking-wider">Email</th>
                        <th class="text-left py-4 px-6 text-sm font-semibold text-[#201E1F]/60 uppercase tracking-wider">Assigned Sources</th>
                        <th class="text-left py-4 px-6 text-sm font-semibold text-[#201E1F]/60 uppercase tracking-wider">Status</th>
                        <th class="text-left py-4 px-6 text-sm font-semibold text-[#201E1F]/60 uppercase tracking-wider">Created</th>
                        <th class="text-right py-4 px-6 text-sm font-semibold text-[#201E1F]/60 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($agents as $agent)
                        <tr class="hover:bg-gray-50/50 transition-colors duration-150">
                            <td class="py-4 px-6">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#D63613] to-[#D63613]/60 flex items-center justify-center text-white font-bold text-sm">
                                        {{ strtoupper(substr($agent->name, 0, 2)) }}
                                    </div>
                                    <span class="font-semibold text-[#201E1F]">{{ $agent->name }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-sm text-[#201E1F]/70">{{ $agent->email }}</td>
                            <td class="py-4 px-6">
                                <div class="flex flex-wrap gap-1">
                                    @forelse($agent->assignedSources as $source)
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                            {{ $source->name }}
                                        </span>
                                    @empty
                                        <span class="text-xs text-gray-400">No sources assigned</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                @if($agent->is_active && !$agent->suspended_at)
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Active</span>
                                @else
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">Inactive</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-sm text-[#201E1F]/70">{{ $agent->created_at->format('M d, Y') }}</td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.agents.edit', $agent) }}"
                                       class="text-[#D63613] hover:text-[#D63613]/80 transition-colors p-1.5 rounded-lg hover:bg-[#D63613]/10"
                                       title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.agents.destroy', $agent) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this agent?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 transition-colors p-1.5 rounded-lg hover:bg-red-50" title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 px-6 text-center text-[#201E1F]/40">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <p class="text-lg font-medium">No agents found</p>
                                    <p class="text-sm mt-1">Create your first agent to get started.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($agents->hasPages())
            <div class="p-6 border-t border-gray-200">
                {{ $agents->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
