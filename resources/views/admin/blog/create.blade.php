@extends('layouts.admin')

@section('title', 'Create Blog Post')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up">
        <div class="lg:flex space-y-4 justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-gradient-to-br from-[#D63613] to-[#D63613]/80 rounded-xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-[#201E1F] mb-1">Create New Blog Post</h1>
                    <p class="text-[#201E1F]/60">Write and publish engaging content for your audience</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
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

    <!-- Main Form -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md animate-fade-in-up" style="animation-delay: 0.1s;">
        <div class="px-6 py-5 border-b border-[#D63613]/10">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-[#201E1F]">Post Content</h3>
            </div>
        </div>
        
        <form method="POST" action="{{ route('admin.blog.store') }}" enctype="multipart/form-data" class="p-6 space-y-8">
            @csrf
            
            <!-- Basic Information -->
            <div class="space-y-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-semibold text-[#201E1F] mb-2">Title</label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required 
                           class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] placeholder-[#201E1F]/40 transition-all duration-200 @error('title') border-red-500 @enderror"
                           placeholder="Enter your blog post title...">
                    @error('title')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Excerpt -->
                <div>
                    <label for="excerpt" class="block text-sm font-semibold text-[#201E1F] mb-2">Excerpt (Optional)</label>
                    <textarea id="excerpt" name="excerpt" rows="3" 
                              class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] placeholder-[#201E1F]/40 transition-all duration-200 resize-none @error('excerpt') border-red-500 @enderror"
                              placeholder="Brief description of the post...">{{ old('excerpt') }}</textarea>
                    <p class="mt-2 text-sm text-[#201E1F]/50">Brief description of the post. If left empty, it will be auto-generated from content.</p>
                    @error('excerpt')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Content -->
                <div>
                    <label for="content" class="block text-sm font-semibold text-[#201E1F] mb-2">Content</label>
                    <textarea id="content" name="content" rows="15" required 
                              class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] placeholder-[#201E1F]/40 transition-all duration-200 resize-none @error('content') border-red-500 @enderror"
                              placeholder="Write your blog post content here...">{{ old('content') }}</textarea>
                    @error('content')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Featured Image -->
                <div>
                    <label for="featured_image" class="block text-sm font-semibold text-[#201E1F] mb-2">Featured Image</label>
                    <div class="relative">
                        <input type="file" id="featured_image" name="featured_image" accept="image/*" 
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] transition-all duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#D63613] file:text-white file:cursor-pointer hover:file:bg-[#D63613]/90 @error('featured_image') border-red-500 @enderror">
                    </div>
                    <p class="mt-2 text-sm text-[#201E1F]/50">Upload an image that represents your blog post content.</p>
                    @error('featured_image')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- SEO Section -->
            <div class="border-t border-gray-200 pt-8">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-orange-400 to-orange-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-[#201E1F]">SEO Settings</h3>
                </div>
                
                <div class="space-y-6 bg-white p-6 rounded-lg border border-gray-200">
                    <!-- Meta Title -->
                    <div>
                        <label for="meta_title" class="block text-sm font-semibold text-[#201E1F] mb-2">Meta Title</label>
                        <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title') }}" 
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] placeholder-[#201E1F]/40 transition-all duration-200 @error('meta_title') border-red-500 @enderror"
                               placeholder="Enter meta title for search engines...">
                        <p class="mt-2 text-sm text-[#201E1F]/50">If left empty, the post title will be used.</p>
                        @error('meta_title')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Meta Description -->
                    <div>
                        <label for="meta_description" class="block text-sm font-semibold text-[#201E1F] mb-2">Meta Description</label>
                        <textarea id="meta_description" name="meta_description" rows="3" 
                                  class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] placeholder-[#201E1F]/40 transition-all duration-200 resize-none @error('meta_description') border-red-500 @enderror"
                                  placeholder="Enter meta description for search engines...">{{ old('meta_description') }}</textarea>
                        <p class="mt-2 text-sm text-[#201E1F]/50">Brief description for search engines (150-160 characters recommended).</p>
                        @error('meta_description')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Meta Keywords -->
                    <div>
                        <label for="meta_keywords" class="block text-sm font-semibold text-[#201E1F] mb-2">Meta Keywords</label>
                        <input type="text" id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords') }}" 
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] placeholder-[#201E1F]/40 transition-all duration-200 @error('meta_keywords') border-red-500 @enderror"
                               placeholder="keyword1, keyword2, keyword3...">
                        <p class="mt-2 text-sm text-[#201E1F]/50">Comma-separated keywords related to this post.</p>
                        @error('meta_keywords')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Publishing Options -->
            <div class="border-t border-gray-200 pt-8">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19V5a2 2 0 012-2h12a2 2 0 012 2v8M4 19l8-8 4 4"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-[#201E1F]">Publishing Options</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white p-6 rounded-lg border border-gray-200">
                    <!-- Publish Status -->
                    <div class="flex items-center">
                        <input type="checkbox" id="is_published" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }}
                               class="h-4 w-4 text-[#D63613] focus:ring-[#D63613] border-gray-300 rounded bg-white">
                        <label for="is_published" class="ml-3 block text-sm font-medium text-[#201E1F]">
                            Publish immediately
                        </label>
                    </div>

                    <!-- Published Date -->
                    <div>
                        <label for="published_at" class="block text-sm font-semibold text-[#201E1F] mb-2">Publish Date (Optional)</label>
                        <input type="datetime-local" id="published_at" name="published_at" value="{{ old('published_at') }}" 
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] transition-all duration-200 @error('published_at') border-red-500 @enderror">
                        <p class="mt-2 text-sm text-[#201E1F]/50">Leave empty to use current date/time when publishing.</p>
                        @error('published_at')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between items-center pt-8 border-t border-gray-200">
                <a href="{{ route('admin.blog.index') }}" 
                   class="bg-white border border-gray-200 text-[#201E1F] font-semibold py-3 px-6 rounded-lg hover:bg-gray-50 transition-all duration-300 flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <span>Cancel</span>
                </a>
                
                <div class="flex items-center space-x-4">
                    <button type="submit" name="action" value="draft" 
                            class="bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-300 shadow-md hover:shadow-lg flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <span>Save as Draft</span>
                    </button>
                    
                    <button type="submit" name="action" value="publish" 
                            class="bg-gradient-to-r from-[#D63613] to-[#D63613]/80 text-white font-semibold py-3 px-6 rounded-lg hover:from-[#D63613]/90 hover:to-[#D63613]/70 transition-all duration-300 shadow-md hover:shadow-lg flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        <span>Publish Post</span>
                    </button>
                </div>
            </div>
        </form>
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

<script>
// Auto-set publish checkbox based on button clicked
document.addEventListener('DOMContentLoaded', function() {
    const publishBtn = document.querySelector('button[value="publish"]');
    const draftBtn = document.querySelector('button[value="draft"]');
    const publishCheckbox = document.getElementById('is_published');
    
    publishBtn.addEventListener('click', function() {
        publishCheckbox.checked = true;
    });
    
    draftBtn.addEventListener('click', function() {
        publishCheckbox.checked = false;
    });
});
</script>
@endsection