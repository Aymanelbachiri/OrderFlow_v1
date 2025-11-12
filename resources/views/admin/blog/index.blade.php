@extends('layouts.admin')

@section('title', 'Blog Management')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up">
        <div class="lg:flex space-y-4 justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-gradient-to-br from-[#D63613] to-[#D63613]/80 rounded-xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-[#201E1F] mb-1">Blog Management</h1>
                    <p class="text-[#201E1F]/60">Manage and publish your content</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.blog.generate-sitemap') }}" 
                   class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-6 py-3 rounded-lg text-sm font-semibold flex items-center space-x-2 transition-all duration-300 shadow-md hover:shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>Generate Sitemap</span>
                </a>
                <a href="{{ route('admin.blog.create') }}" 
                   class="bg-gradient-to-r from-[#D63613] to-[#D63613]/80 hover:from-[#D63613]/90 hover:to-[#D63613] text-white px-6 py-3 rounded-lg text-sm font-semibold flex items-center space-x-2 transition-all duration-300 shadow-md hover:shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span>New Post</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up" style="animation-delay: 0.1s;">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-[#201E1F]">Filter Posts</h3>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.blog.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-[#201E1F]/60 mb-2">Search Posts</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search posts..."
                       class="w-full rounded-lg bg-white border border-gray-200 text-[#201E1F] placeholder-[#201E1F]/40 focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-[#201E1F]/60 mb-2">Status</label>
                <select name="status" class="w-full rounded-lg bg-white border border-gray-200 text-[#201E1F] focus:border-[#D63613] focus:ring-2 focus:ring-[#D63613]/20 transition-all duration-300">
                    <option value="">All Status</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="bg-gradient-to-r from-[#D63613] to-[#D63613]/80 hover:from-[#D63613]/90 hover:to-[#D63613] text-white px-4 py-3 rounded-lg text-sm font-semibold transition-all duration-300 shadow-md hover:shadow-lg flex-1">
                    Filter
                </button>
                <a href="{{ route('admin.blog.index') }}" class="bg-white hover:bg-gray-50 text-[#201E1F]/80 hover:text-[#201E1F] border border-gray-200 px-4 py-3 rounded-lg text-sm font-medium transition-all duration-300">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Posts Table -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md animate-fade-in-up overflow-hidden" style="animation-delay: 0.2s;">
        <div class="px-6 py-5 border-b border-[#D63613]/10">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-semibold text-[#201E1F]">All Posts</h3>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-[#201E1F]/60">{{ $posts->total() }} total posts</span>
                    <div class="w-3 h-3 bg-gradient-to-r from-green-400 to-green-600 rounded-full"></div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#D63613]">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">Post</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">Author</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">Published</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-[#201E1F] uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-[#F5F5F5] divide-y divide-gray-200">
                    @forelse($posts as $post)
                        <tr class="hover:bg-white/50 transition-colors duration-200">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($post->featured_image)
                                        <div class="flex-shrink-0 h-12 w-12">
                                            <img class="h-12 w-12 rounded-lg object-cover shadow-sm" src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}">
                                        </div>
                                    @else
                                        <div class="flex-shrink-0 h-12 w-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-[#201E1F]">{{ $post->title }}</div>
                                        <div class="text-sm text-[#201E1F]/60">{{ Str::limit($post->excerpt ?: strip_tags($post->content), 60) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gradient-to-br from-[#D63613] to-[#D63613]/80 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-xs font-semibold text-white">{{ substr($post->author->name, 0, 1) }}</span>
                                    </div>
                                    <span class="text-sm text-[#201E1F]">{{ $post->author->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full border
                                    {{ $post->is_published ? 'bg-green-50 text-green-700 border-green-200' : 'bg-yellow-50 text-yellow-700 border-yellow-200' }}">
                                    {{ $post->is_published ? 'Published' : 'Draft' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-[#201E1F]">
                                    {{ $post->published_at ? $post->published_at->format('M d, Y') : '-' }}
                                </div>
                                @if($post->published_at)
                                    <div class="text-xs text-[#201E1F]/60">{{ $post->published_at->format('g:i A') }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('admin.blog.show', $post) }}" class="text-blue-600 hover:text-blue-700 font-medium transition-colors duration-200">View</a>
                                    <a href="{{ route('admin.blog.edit', $post) }}" class="text-purple-600 hover:text-purple-700 font-medium transition-colors duration-200">Edit</a>
                                    @if($post->is_published)
                                        <a href="{{ route('blog.post', $post) }}" target="_blank" class="text-green-600 hover:text-green-700 font-medium transition-colors duration-200">Preview</a>
                                    @endif
                                    <form method="POST" action="{{ route('admin.blog.destroy', $post) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Are you sure?')" class="text-red-600 hover:text-red-700 font-medium transition-colors duration-200">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                    </svg>
                                    <p class="text-gray-500 text-sm">No blog posts found</p>
                                    <p class="text-gray-400 text-xs mt-1">Create your first post to get started</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($posts->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-[#D63613] rounded-b-xl">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-[#201E1F]/60">Showing {{ $posts->firstItem() ?? 0 }} to {{ $posts->lastItem() ?? 0 }} of {{ $posts->total() }} results</p>
                    <div class="pagination-wrapper">
                        {{ $posts->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- SEO Information -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up" style="animation-delay: 0.3s;">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-[#201E1F]">SEO Information</h2>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg border border-gray-200 p-6 text-center">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl font-bold text-blue-600">{{ $posts->total() }}</span>
                </div>
                <div class="text-sm text-[#201E1F]/60">Total Posts</div>
            </div>
            <div class="bg-white rounded-lg border border-gray-200 p-6 text-center">
                <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-green-200 rounded-lg flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl font-bold text-green-600">{{ $posts->where('is_published', true)->count() }}</span>
                </div>
                <div class="text-sm text-[#201E1F]/60">Published Posts</div>
            </div>
            <div class="bg-white rounded-lg border border-gray-200 p-6 text-center">
                <div class="w-12 h-12 bg-gradient-to-br from-yellow-100 to-yellow-200 rounded-lg flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl font-bold text-yellow-600">{{ $posts->where('is_published', false)->count() }}</span>
                </div>
                <div class="text-sm text-[#201E1F]/60">Draft Posts</div>
            </div>
        </div>

        <div class="border-t border-gray-200 pt-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-gradient-to-br from-green-400 to-green-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-[#201E1F]">Sitemap Management</h3>
                </div>
            </div>
            
            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <p class="text-sm text-[#201E1F]/70 mb-3">
                    Your sitemap helps search engines discover and index your content.
                    <a href="{{ asset('storage/sitemap.xml') }}" target="_blank" class="text-[#D63613] hover:text-[#D63613]/80 font-medium underline transition-colors duration-200">View current sitemap</a>
                </p>
                <a href="{{ route('admin.blog.generate-sitemap') }}" 
                   class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-4 py-2 rounded-lg text-sm font-semibold flex items-center space-x-2 transition-all duration-300 shadow-md hover:shadow-lg inline-flex">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    <span>Regenerate Sitemap</span>
                </a>
            </div>
        </div>
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

/* Custom pagination styling to match light theme */
.pagination-wrapper nav div {
    @apply bg-white border border-gray-200 rounded-lg;
}

.pagination-wrapper nav span,
.pagination-wrapper nav a {
    @apply text-[#201E1F] bg-transparent border-none px-3 py-2 text-sm;
}

.pagination-wrapper nav a:hover {
    @apply text-[#D63613] bg-[#D63613]/10;
}

.pagination-wrapper nav span[aria-current="page"] {
    @apply text-white bg-[#D63613] rounded;
}
</style>
@endsection