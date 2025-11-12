@extends('layouts.public')

@section('title', $post->meta_title ?: $post->title)

@php
    $metaTitle = $post->meta_title ?: $post->title;
    $metaDescription = $post->meta_description ?: $post->excerpt;
    $metaKeywords = $post->meta_keywords;
@endphp

@section('content')
<!-- Header Section -->
<div class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Back to Blog -->
        <div class="mb-8">
            <a href="{{ route('blog') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white font-medium transition-colors group">
                <svg class="mr-2 w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Blog
            </a>
        </div>

        <!-- Article Header -->
        <article>
            <!-- Meta Information -->
            <div class="flex items-center gap-4 mb-6">
                <div class="flex items-center gap-3">
                    
                    <div>
                        <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                            <time datetime="{{ $post->published_at->toDateString() }}">
                                {{ $post->published_at->format('M d, Y') }}
                            </time>
                            <span>•</span>
                            <span>8 mins read</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Title -->
            <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mb-6 leading-tight">
                {{ $post->title }}
            </h1>
            
            <!-- Excerpt -->
            @if($post->excerpt)
                <p class="text-lg text-gray-600 dark:text-gray-400 leading-relaxed mb-8">
                    {{ $post->excerpt }}
                </p>
            @endif
        </article>
    </div>
</div>

<!-- Featured Image -->
@if($post->featured_image)
    <div class="bg-white dark:bg-gray-900">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="relative rounded-xl overflow-hidden">
                <img src="{{ Storage::url($post->featured_image) }}" 
                     alt="{{ $post->title }}" 
                     class="w-full h-64 md:h-96 object-cover" 
                     loading="lazy" 
                     decoding="async">
            </div>
        </div>
    </div>
@endif
<!-- Article Content -->
<div class="bg-gray-50 dark:bg-gray-900 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-8 lg:p-12">
            <article class="prose prose-lg dark:prose-invert max-w-none">
                <div class="text-gray-700 dark:text-gray-300 leading-relaxed article-content">
                    {!! nl2br(e($post->content)) !!}
                </div>
            </article>
        </div>
    </div>
</div>



<!-- Related Posts Section -->




<!-- SEO Meta Tags -->
@if($metaTitle)
    @push('meta')
        <meta name="title" content="{{ $metaTitle }}">
    @endpush
@endif

@if($metaDescription)
    @push('meta')
        <meta name="description" content="{{ $metaDescription }}">
    @endpush
@endif

@if($metaKeywords)
    @push('meta')
        <meta name="keywords" content="{{ $metaKeywords }}">
    @endpush
@endif

<!-- Open Graph Meta Tags -->
@push('meta')
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ request()->url() }}">
    @if($post->featured_image)
        <meta property="og:image" content="{{ Storage::url($post->featured_image) }}">
    @endif
    <meta property="article:published_time" content="{{ $post->published_at->toISOString() }}">
    <meta property="article:author" content="{{ $post->author->name }}">
@endpush

<!-- Twitter Card Meta Tags -->
@push('meta')
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $metaTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    @if($post->featured_image)
        <meta name="twitter:image" content="{{ Storage::url($post->featured_image) }}">
    @endif
@endpush
@endsection