@extends('layouts.admin')

@section('title', 'Blog Post - ' . $blog->title)

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up">
        <div class="lg:flex space-y-4 justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-gradient-to-br from-[#D63613] to-[#D63613]/80 rounded-xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-[#201E1F] mb-1">{{ $blog->title }}</h1>
                    <p class="text-[#201E1F]/60">
                        Created {{ $blog->created_at->format('M d, Y') }} by {{ $blog->author->name }}
                        @if($blog->updated_at != $blog->created_at)
                            • Updated {{ $blog->updated_at->format('M d, Y') }}
                        @endif
                    </p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.blog.edit', $blog) }}" 
                   class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-3 rounded-lg text-sm font-semibold flex items-center space-x-2 transition-all duration-300 shadow-md hover:shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <span>Edit Post</span>
                </a>
                <a href="{{ route('admin.blog.index') }}" 
                   class="bg-white hover:bg-gray-50 text-[#201E1F]/80 hover:text-[#201E1F] border border-gray-200 px-6 py-3 rounded-lg text-sm font-semibold flex items-center space-x-2 transition-all duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Back to Posts</span>
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Post Content -->
            <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up" style="animation-delay: 0.1s;">
                <!-- Status Badge -->
                <div class="mb-6">
                    <div class="flex items-center space-x-3">
                        @if($blog->is_published)
                            <span class="inline-flex px-3 py-2 text-xs font-semibold rounded-lg bg-green-50 text-green-700 border border-green-200">
                                <div class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                                Published
                            </span>
                            @if($blog->published_at)
                                <span class="text-sm text-[#201E1F]/60">
                                    on {{ $blog->published_at->format('M d, Y \a\t H:i') }}
                                </span>
                            @endif
                        @else
                            <span class="inline-flex px-3 py-2 text-xs font-semibold rounded-lg bg-yellow-50 text-yellow-700 border border-yellow-200">
                                <div class="w-2 h-2 bg-yellow-400 rounded-full mr-2"></div>
                                Draft
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Featured Image -->
                @if($blog->featured_image)
                <div class="mb-6">
                    <div class="relative overflow-hidden rounded-lg border border-gray-200">
                        <img src="{{ Storage::url($blog->featured_image) }}" 
                             alt="{{ $blog->title }}" 
                             class="w-full h-64 object-cover">
                        <div class="absolute top-3 left-3">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded bg-black/20 text-white backdrop-blur-sm">
                                Featured Image
                            </span>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Excerpt -->
                @if($blog->excerpt)
                <div class="mb-6">
                    <div class="bg-gradient-to-r from-blue-50 to-blue-50/50 p-6 rounded-lg border-l-4 border-blue-500">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="w-8 h-8 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                </svg>
                            </div>
                            <h3 class="text-sm font-semibold text-blue-700">Excerpt</h3>
                        </div>
                        <p class="text-blue-600">{{ $blog->excerpt }}</p>
                    </div>
                </div>
                @endif

                <!-- Content -->
                <div class="prose max-w-none">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-[#201E1F]">Content</h3>
                    </div>
                    <div class="bg-white p-6 rounded-lg border border-gray-200">
                        <div class="text-[#201E1F] leading-relaxed">
                            {!! nl2br(e($blog->content)) !!}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Public View Link -->
            @if($blog->is_published)
            <div class="bg-gradient-to-r from-green-50 to-green-50/50 border border-green-200 rounded-xl p-6 animate-fade-in-up" style="animation-delay: 0.2s;">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-lg font-semibold text-green-700 mb-1">Public View Available</p>
                        <a href="{{ route('blog.post', $blog->slug) }}" target="_blank" 
                           class="text-green-600 hover:text-green-700 font-medium transition-colors duration-200 flex items-center space-x-2">
                            <span>View this post on your website</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-8">
            <!-- Post Details -->
            <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up" style="animation-delay: 0.3s;">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-[#201E1F]">Post Details</h3>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-[#201E1F]/60 mb-2">Slug</label>
                        <div class="bg-white p-3 rounded-lg border border-gray-200">
                            <p class="text-sm text-[#201E1F] font-mono">{{ $blog->slug }}</p>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-[#201E1F]/60 mb-2">Author</label>
                        <div class="bg-white p-3 rounded-lg border border-gray-200">
                            <p class="text-sm text-[#201E1F]">{{ $blog->author->name }}</p>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-[#201E1F]/60 mb-2">Created</label>
                        <div class="bg-white p-3 rounded-lg border border-gray-200">
                            <p class="text-sm text-[#201E1F]">{{ $blog->created_at->format('M d, Y \a\t H:i') }}</p>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-[#201E1F]/60 mb-2">Last Updated</label>
                        <div class="bg-white p-3 rounded-lg border border-gray-200">
                            <p class="text-sm text-[#201E1F]">{{ $blog->updated_at->format('M d, Y \a\t H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SEO Information -->
            <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up" style="animation-delay: 0.4s;">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-orange-400 to-orange-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-[#201E1F]">SEO Information</h3>
                </div>
                
                <div class="space-y-4">
                    @if($blog->meta_title)
                    <div>
                        <label class="block text-sm font-medium text-[#201E1F]/60 mb-2">Meta Title</label>
                        <div class="bg-white p-3 rounded-lg border border-gray-200">
                            <p class="text-sm text-[#201E1F]">{{ $blog->meta_title }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($blog->meta_description)
                    <div>
                        <label class="block text-sm font-medium text-[#201E1F]/60 mb-2">Meta Description</label>
                        <div class="bg-white p-3 rounded-lg border border-gray-200">
                            <p class="text-sm text-[#201E1F]">{{ $blog->meta_description }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($blog->meta_keywords)
                    <div>
                        <label class="block text-sm font-medium text-[#201E1F]/60 mb-2">Meta Keywords</label>
                        <div class="bg-white p-3 rounded-lg border border-gray-200">
                            <p class="text-sm text-[#201E1F]">{{ $blog->meta_keywords }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if(!$blog->meta_title && !$blog->meta_description && !$blog->meta_keywords)
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <p class="text-sm text-gray-500">No SEO information provided</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up" style="animation-delay: 0.5s;">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-red-400 to-red-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-[#201E1F]">Quick Actions</h3>
                </div>
                
                <div class="space-y-3">
                    <a href="{{ route('admin.blog.edit', $blog) }}" 
                       class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-3 px-4 rounded-lg text-center flex items-center justify-center space-x-2 transition-all duration-300 shadow-md hover:shadow-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        <span>Edit Post</span>
                    </a>
                    
                    @if($blog->is_published)
                    <a href="{{ route('blog.post', $blog->slug) }}" target="_blank"
                       class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold py-3 px-4 rounded-lg text-center flex items-center justify-center space-x-2 transition-all duration-300 shadow-md hover:shadow-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                        <span>View Public</span>
                    </a>
                    @endif
                    
                    <form action="{{ route('admin.blog.destroy', $blog) }}" method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this blog post?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold py-3 px-4 rounded-lg text-center flex items-center justify-center space-x-2 transition-all duration-300 shadow-md hover:shadow-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            <span>Delete Post</span>
                        </button>
                    </form>
                </div>
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
</style>
@endsection