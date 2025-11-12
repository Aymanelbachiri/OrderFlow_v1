@extends('layouts.public')

@section('title', 'Blog - Latest IPTV News & Updates')

@section('content')
<!-- Hero Section - Simplified and Modern -->
<div class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="max-w-3xl">
            <div class="inline-flex items-center px-3 py-1 rounded-full bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-sm font-medium mb-4">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
                News
            </div>
            <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                Our Recent Articles
            </h1>
            <p class="text-lg text-gray-600 dark:text-gray-400">
                Stay informed with Our Latest insights
            </p>
        </div>
    </div>
</div>

<!-- Blog Posts Section -->
<div class="bg-gray-50 dark:bg-gray-900 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($posts->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($posts as $post)
                    <article class="group bg-white dark:bg-gray-800 rounded-xl overflow-hidden hover:shadow-xl transition-all duration-300 border border-gray-200 dark:border-gray-700">
                        <!-- Featured Image -->
                        <a href="{{ route('blog.post', $post) }}" class="block relative overflow-hidden aspect-video">
                            @if($post->featured_image)
                                <img src="{{ Storage::url($post->featured_image) }}" 
                                     alt="{{ $post->title }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                                     loading="lazy" 
                                     decoding="async">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                    </svg>
                                </div>
                            @endif
                        </a>
                        
                        <!-- Content -->
                        <div class="p-6">
                            <!-- Meta Information -->
                            <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400 mb-4">
                                <time datetime="{{ $post->published_at->toDateString() }}">
                                    {{ $post->published_at->format('d M Y') }}
                                </time>
                                <span>•</span>
                                <span>8 mins read</span>
                            </div>
                            
                            <!-- Title -->
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 line-clamp-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                <a href="{{ route('blog.post', $post) }}">
                                    {{ $post->title }}
                                </a>
                            </h2>
                            
                            <!-- Excerpt -->
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-2">
                                {{ $post->excerpt }}
                            </p>
                            
                            <!-- Read More Link -->
                            <a href="{{ route('blog.post', $post) }}" 
                               class="inline-flex items-center text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-semibold group/link">
                                Read More
                                <svg class="ml-1 w-4 h-4 group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-20">
                <div class="w-20 h-20 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No articles yet</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">Check back soon for the latest updates and news.</p>
            </div>
        @endif
    </div>
</div>
@endsection