@extends('layouts.admin')

@section('title', 'Edit Blog Post - ' . $blog->title)

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
                    <h1 class="text-3xl font-bold text-[#201E1F] mb-1">Edit Blog Post</h1>
                    <p class="text-[#201E1F]/60">
                        Created {{ $blog->created_at->format('M d, Y') }} by {{ $blog->author->name }}
                    </p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.blog.show', $blog) }}" 
                   class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-3 rounded-lg text-sm font-semibold flex items-center space-x-2 transition-all duration-300 shadow-md hover:shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    <span>View Post</span>
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
        
        <form method="POST" action="{{ route('admin.blog.update', $blog) }}" enctype="multipart/form-data" class="p-6 space-y-8">
            @csrf
            @method('PUT')
            
            <!-- Basic Information -->
            <div class="space-y-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-semibold text-[#201E1F] mb-2">Title</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $blog->title) }}" required 
                           class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] placeholder-[#201E1F]/40 transition-all duration-200 @error('title') border-red-500 @enderror">
                    @error('title')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-semibold text-[#201E1F] mb-2">Slug</label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug', $blog->slug) }}" required 
                           class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] placeholder-[#201E1F]/40 transition-all duration-200 font-mono @error('slug') border-red-500 @enderror">
                    <p class="mt-2 text-sm text-[#201E1F]/50">URL-friendly version of the title. Leave empty to auto-generate.</p>
                    @error('slug')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Excerpt -->
                <div>
                    <label for="excerpt" class="block text-sm font-semibold text-[#201E1F] mb-2">Excerpt (Optional)</label>
                    <textarea id="excerpt" name="excerpt" rows="3" 
                              class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] placeholder-[#201E1F]/40 transition-all duration-200 resize-none @error('excerpt') border-red-500 @enderror"
                              placeholder="Brief description of the post...">{{ old('excerpt', $blog->excerpt) }}</textarea>
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
                              placeholder="Write your blog post content here...">{{ old('content', $blog->content) }}</textarea>
                    @error('content')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Featured Image -->
                <div>
                    <label for="featured_image" class="block text-sm font-semibold text-[#201E1F] mb-2">Featured Image</label>
                    
                    @if($blog->featured_image)
                    <div class="mb-4 p-4 bg-white rounded-lg border border-gray-200">
                        <p class="text-sm font-medium text-[#201E1F]/60 mb-3">Current image:</p>
                        <div class="relative inline-block">
                            <img src="{{ Storage::url($blog->featured_image) }}" 
                                 alt="Current featured image" 
                                 class="w-32 h-32 object-cover rounded-lg border border-gray-300">
                            <div class="absolute -top-2 -right-2">
                                <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <div class="relative">
                        <input type="file" id="featured_image" name="featured_image" accept="image/*" 
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] transition-all duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#D63613] file:text-white file:cursor-pointer hover:file:bg-[#D63613]/90 @error('featured_image') border-red-500 @enderror">
                    </div>
                    <p class="mt-2 text-sm text-[#201E1F]/50">Upload a new image to replace the current one. Leave empty to keep current image.</p>
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
                        <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title', $blog->meta_title) }}" 
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] placeholder-[#201E1F]/40 transition-all duration-200 @error('meta_title') border-red-500 @enderror"
                               placeholder="Enter meta title for search engines...">
                        <p class="mt-2 text-sm text-[#201E1F]/50">Title tag for search engines. Leave empty to use post title.</p>
                        @error('meta_title')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Meta Description -->
                    <div>
                        <label for="meta_description" class="block text-sm font-semibold text-[#201E1F] mb-2">Meta Description</label>
                        <textarea id="meta_description" name="meta_description" rows="3" 
                                  class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] placeholder-[#201E1F]/40 transition-all duration-200 resize-none @error('meta_description') border-red-500 @enderror"
                                  placeholder="Enter meta description for search engines...">{{ old('meta_description', $blog->meta_description) }}</textarea>
                        <p class="mt-2 text-sm text-[#201E1F]/50">Description for search engines. Leave empty to use excerpt.</p>
                        @error('meta_description')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Meta Keywords -->
                    <div>
                        <label for="meta_keywords" class="block text-sm font-semibold text-[#201E1F] mb-2">Meta Keywords</label>
                        <input type="text" id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords', $blog->meta_keywords) }}" 
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent text-[#201E1F] placeholder-[#201E1F]/40 transition-all duration-200 @error('meta_keywords') border-red-500 @enderror"
                               placeholder="keyword1, keyword2, keyword3...">
                        <p class="mt-2 text-sm text-[#201E1F]/50">Comma-separated keywords for search engines.</p>
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
                
                <div class="bg-white p-6 rounded-lg border border-gray-200">
                    <div class="flex items-center">
                        <input type="checkbox" id="is_published" name="is_published" value="1" 
                               {{ old('is_published', $blog->is_published) ? 'checked' : '' }}
                               class="h-4 w-4 text-[#D63613] focus:ring-[#D63613] border-gray-300 rounded bg-white">
                        <label for="is_published" class="ml-3 block text-sm font-medium text-[#201E1F]">
                            Publish this post
                        </label>
                    </div>
                    
                    @if($blog->is_published && $blog->published_at)
                    <div class="mt-4 p-3 bg-green-50 rounded-lg border border-green-200">
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <p class="text-sm text-green-700">
                                Originally published on {{ $blog->published_at->format('M d, Y \a\t H:i') }}
                            </p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-between items-center pt-8 border-t border-gray-200">
                <div class="flex space-x-4">
                    <button type="submit" 
                            class="bg-gradient-to-r from-[#D63613] to-[#D63613]/80 text-white font-semibold py-3 px-6 rounded-lg hover:from-[#D63613]/90 hover:to-[#D63613]/70 transition-all duration-300 shadow-md hover:shadow-lg flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Update Post</span>
                    </button>
                    <a href="{{ route('admin.blog.show', $blog) }}" 
                       class="bg-white border border-gray-200 text-[#201E1F] font-semibold py-3 px-6 rounded-lg hover:bg-gray-50 transition-all duration-300 flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <span>Cancel</span>
                    </a>
                </div>
                
                @if($blog->is_published)
                <a href="{{ route('blog.post', $blog->slug) }}" target="_blank"
                   class="text-[#D63613] hover:text-[#D63613]/80 font-medium transition-colors duration-200 flex items-center space-x-2">
                    <span>View Public Post</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                </a>
                @endif
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
// Auto-generate slug from title
document.getElementById('title').addEventListener('input', function() {
    const title = this.value;
    const slug = title.toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '') // Remove special characters
        .replace(/\s+/g, '-') // Replace spaces with hyphens
        .replace(/-+/g, '-') // Replace multiple hyphens with single hyphen
        .trim('-'); // Remove leading/trailing hyphens
    
    document.getElementById('slug').value = slug;
});
</script>
@endsection