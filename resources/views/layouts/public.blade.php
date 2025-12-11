<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="color-scheme" content="light dark">
    <meta name="theme-color" content="#ffffff" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#111827" media="(prefers-color-scheme: dark)">

    <title>@yield('title', 'CONTROL WEB AGENCY - Digital Services')</title>


    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('meta_description', 'Full-service web agency for websites, design, digital marketing, hosting, and support. Build and grow your digital presence with CONTROL WEB AGENCY.')">
    <meta name="keywords" content="@yield('meta_keywords', 'web agency, website development, graphic design, digital marketing, SEO, hosting, maintenance')">
    <meta name="author" content="CONTROL WEB AGENCY">
    <meta name="robots" content="noindex, nofollow, noarchive, nosnippet">

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="@yield('title', 'CONTROL WEB AGENCY - Digital Services')">
    <meta property="og:description" content="@yield('meta_description', 'Full-service web agency for websites, design, digital marketing, hosting, and support.')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="CONTROL WEB AGENCY">
    <meta property="og:image" content="{{ asset('images/og-image.jpg') }}">

    
    

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Structured Data -->
    @php
        $organizationSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'CONTROL WEB AGENCY',
            'url' => url('/'),
            'logo' => asset('images/logo.webp'),
            'description' => 'Full-service web agency for website development, design, marketing, and hosting',
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'telephone' => '+1-555-123-4567',
                'contactType' => 'customer service',
                'availableLanguage' => 'English',
            ],
            'sameAs' => [
                // Social links can be updated later
            ],
        ];
    @endphp

    <script type="application/ld+json">
{!! json_encode($organizationSchema, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}
</script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Custom Styles for Animations and Mobile Optimization -->
    <style>
        @keyframes blob {
            0% {
                transform: translate(0px, 0px) scale(1);
            }

            33% {
                transform: translate(30px, -50px) scale(1.1);
            }

            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }

            100% {
                transform: translate(0px, 0px) scale(1);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-blob {
            animation: blob 7s infinite;
        }

        .animation-delay-2000 {
            animation-delay: 2s;
        }

        .animation-delay-4000 {
            animation-delay: 4s;
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out;
        }

        .animate-fade-in-right {
            animation: fadeInRight 0.8s ease-out;
        }

        .animation-delay-200 {
            animation-delay: 0.2s;
            animation-fill-mode: both;
        }

        .animation-delay-400 {
            animation-delay: 0.4s;
            animation-fill-mode: both;
        }

        .animation-delay-600 {
            animation-delay: 0.6s;
            animation-fill-mode: both;
        }

        .animation-delay-800 {
            animation-delay: 0.8s;
            animation-fill-mode: both;
        }

        .animation-delay-1000 {
            animation-delay: 1s;
            animation-fill-mode: both;
        }

        .animation-delay-1200 {
            animation-delay: 1.2s;
            animation-fill-mode: both;
        }

        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Loading optimization */
        img {
            loading: lazy;
        }

        /* Mobile optimizations */
        @media (max-width: 768px) {

            /* Mobile navigation improvements */
            .mobile-nav {
                position: fixed;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100vh;
                background: white;
                z-index: 1000;
                transition: left 0.3s ease-in-out;
                overflow-y: auto;
            }

            .mobile-nav.open {
                left: 0;
            }

            /* Mobile overlay */
            .mobile-nav-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 999;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease-in-out;
            }

            .mobile-nav-overlay.show {
                opacity: 1;
                visibility: visible;
            }

            /* Mobile-specific table styles */
            .mobile-table {
                display: block;
                width: 100%;
            }

            .mobile-table thead {
                display: none;
            }

            .mobile-table tbody,
            .mobile-table tr,
            .mobile-table td {
                display: block;
                width: 100%;
            }

            .mobile-table tr {
                border: 1px solid #e5e7eb;
                border-radius: 0.5rem;
                margin-bottom: 1rem;
                padding: 1rem;
                background: white;
            }

            .mobile-table td {
                border: none;
                padding: 0.5rem 0;
                position: relative;
                padding-left: 0;
            }

            .mobile-table td:before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
                font-weight: 600;
                color: #374151;
            }

            /* Mobile form improvements */
            .form-input {
                font-size: 16px;
                /* Prevents zoom on iOS */
            }

            .btn-mobile {
                width: 100%;
                padding: 0.75rem;
                font-size: 1rem;
            }

            /* Mobile grid improvements */
            .grid-cols-1.md\\:grid-cols-2 {
                grid-template-columns: 1fr;
            }

            .grid-cols-1.md\\:grid-cols-3 {
                grid-template-columns: 1fr;
            }

            .grid-cols-1.md\\:grid-cols-4 {
                grid-template-columns: 1fr;
            }

            /* Mobile text sizing */
            .text-responsive {
                font-size: 0.875rem;
            }

            /* Mobile spacing adjustments */
            .mobile-padding {
                padding: 1rem;
            }

            .mobile-margin {
                margin: 0.5rem;
            }
        }

        /* Tablet optimizations */
        @media (min-width: 769px) and (max-width: 1024px) {
            .tablet-grid-cols-2 {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* Touch-friendly buttons */
        @media (hover: none) and (pointer: coarse) {

            button,
            .btn,
            a {
                min-height: 44px;
                min-width: 44px;
            }
        }

        /* Mobile hamburger menu improvements */
        .mobile-menu-btn {
            -webkit-tap-highlight-color: transparent;
            touch-action: manipulation;
            user-select: none;
        }

        /* Fix z-index for mobile menu */
        @media (max-width: 640px) {
            nav[x-data] {
                position: relative;
                z-index: 100;
            }

            nav[x-data]>div>div>div:last-child {
                z-index: 50;
                position: relative;
            }

            /* Ensure hamburger button is always on top */
            button[aria-label="Toggle menu"] {
                position: relative;
                z-index: 1000 !important;
                pointer-events: auto !important;
                cursor: pointer !important;
                -webkit-tap-highlight-color: rgba(0, 0, 0, 0.1);
            }
            
            /* Ensure no overlay blocks the button */
            nav[x-data] > div > div > div:last-child {
                position: relative;
                z-index: 1001 !important;
            }
        }
    </style>

    <!-- Dark Mode Script -->
    <script>
        // Prevent flash of unstyled content in dark mode
        (function() {
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const storedTheme = localStorage.getItem('theme');

            if (storedTheme === 'dark' || (!storedTheme && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
</head>

<body
    class="font-sans antialiased bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-200">
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <!-- Navigation -->
        <nav class="bg-black shadow-lg dark:shadow-[#8ACE00]/20 transition-colors duration-200 border-b border-gray-700 dark:border-gray-600">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <div class="flex items-center gap-40">
                        <!-- Logo -->
                        <div class="z-40">
                            <a href="{{ route('home') }}"
                                class="">
                                <img class="w-32 " src="{{ asset('images/logo.webp') }}" alt="CONTROL WEB AGENCY">

                            </a>
                        </div>

                        <!-- Navigation Links (simplified) -->
                        <div class="hidden space-x-8 sm:ml-10 sm:flex sm:items-center">
                            <a href="{{ route('website-development') }}"
                                class="border-transparent text-white hover:text-[#8ACE00]  hover:border-gray-300  whitespace-nowrap py-3 px-3 border-b-2 font-medium  transition-colors flex items-center {{ request()->routeIs('website-development') ? 'border-[#8ACE00] text-[#8ACE00] ' : '' }}">
                                Website development
                            </a>
                            <a href="{{ route('graphic-design') }}"
                                class="border-transparent text-white hover:text-[#8ACE00]  hover:border-gray-300  whitespace-nowrap py-3 px-3 border-b-2 font-medium  transition-colors flex items-center {{ request()->routeIs('graphic-design') ? 'border-[#8ACE00] text-[#8ACE00] ' : '' }}">
                                Graphic design
                            </a>
                            <a href="{{ route('digital-marketing') }}"
                                class="border-transparent text-white hover:text-[#8ACE00]  hover:border-gray-300  whitespace-nowrap py-3 px-3 border-b-2 font-medium  transition-colors flex items-center {{ request()->routeIs('digital-marketing') ? 'border-[#8ACE00] text-[#8ACE00] ' : '' }}">
                                Digital Marketing
                            </a>
                            <a href="{{ route('other-services') }}"
                            class="border-transparent text-white hover:text-[#8ACE00]  hover:border-gray-300  whitespace-nowrap py-3 px-3 border-b-2 font-medium  transition-colors flex items-center {{ request()->routeIs('other-services') ? 'border-[#8ACE00] text-[#8ACE00] ' : '' }}">
                            Other Services
                        </a>
                        </div>
                    </div>

                    <!-- Right side -->
                    <div class="hidden sm:flex sm:items-center sm:ml-6 space-x-4">
                        <!-- Dark Mode Toggle -->
                        <button id="theme-toggle"
                            class="p-2 dark:text-[#8ACE00] text-white bg-[#8ACE00]  dark:bg-white text-xl hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
                            title="Toggle dark mode">
                            <!-- Sun icon (visible in dark mode) -->
                            <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor"
                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <!-- Moon icon (visible in light mode) -->
                            <svg id="theme-toggle-dark-icon" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                            </svg>
                        </button>

                        @auth
                            <div class="ml-3 relative">
                                <x-dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <button
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-300 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-100 focus:outline-none transition ease-in-out duration-150">
                                            <div>{{ Auth::user()->name }}</div>
                                            <div class="ml-1">
                                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </button>
                                    </x-slot>

                                    <x-slot name="content">
                                        <x-dropdown-link :href="route('dashboard')">
                                            {{ __('Dashboard') }}
                                        </x-dropdown-link>
                                        <x-dropdown-link :href="route('profile.edit')">
                                            {{ __('Profile') }}
                                        </x-dropdown-link>

                                        <!-- Authentication -->
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <x-dropdown-link :href="route('logout')"
                                                onclick="event.preventDefault();
                                                                this.closest('form').submit();">
                                                {{ __('Log Out') }}
                                            </x-dropdown-link>
                                        </form>
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        @endauth

                        <!-- @guest
                                <div class="flex items-center space-x-4">
                                    <a href="{{ route('login') }}"
                                        class="text-gray-500 dark:text-gray-300 hover:text-orange-500 dark:hover:text-orange-400 font-medium transition-colors">Admin Login</a>
                                </div>
                        @endguest -->
                    </div>

                    <!-- Hamburger -->
                    <div class="-mr-2 flex items-center sm:hidden relative z-50">
                        <button id="theme-toggle"
                            class="p-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
                            title="Toggle dark mode">
                            <!-- Sun icon (visible in dark mode) -->
                            <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor"
                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <!-- Moon icon (visible in light mode) -->
                            <svg id="theme-toggle-dark-icon" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                            </svg>
                        </button>
                        <button id="mobile-hamburger-btn"
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out"
                            style="min-width: 44px; min-height: 44px; touch-action: manipulation; -webkit-tap-highlight-color: rgba(0,0,0,0.1);"
                            type="button" aria-label="Toggle menu">
                            <svg id="hamburger-icon" class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path id="hamburger-bars" class="inline-flex"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                                <path id="hamburger-x" class="hidden"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Responsive Navigation Menu (simplified) -->
            <div id="mobile-nav-menu" class="hidden sm:hidden fixed bg-gray-800 w-full z-40">
                <div class="pt-2 pb-3 space-y-1">
                    <a href="{{ route('website-development') }}"
                        class="border-transparent text-gray-200 hover:text-white hover:bg-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition duration-150 ease-in-out">
                        Website development
                    </a>
                    <a href="{{ route('graphic-design') }}"
                        class="border-transparent text-gray-200 hover:text-white hover:bg-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition duration-150 ease-in-out">
                        Graphic design
                    </a>
                    <a href="{{ route('digital-marketing') }}"
                        class="border-transparent text-gray-200 hover:text-white hover:bg-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition duration-150 ease-in-out">
                        Digital Marketing
                    </a>
                    <a href="{{ route('other-services') }}"
                        class="border-transparent text-gray-200 hover:text-white hover:bg-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition duration-150 ease-in-out">
                        Other Services
                    </a>
                </div>

                <!-- Responsive Settings Options -->
                @auth
                    <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                        <div class="px-4">
                            <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}
                            </div>
                            <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <a href="{{ route('dashboard') }}"
                                class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600 transition duration-150 ease-in-out">
                                Dashboard
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600 transition duration-150 ease-in-out">
                                    Log Out
                                </a>
                            </form>
                        </div>
                    </div>
                @endauth

                <!-- @guest
                        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                            <div class="space-y-1">
                                <a href="{{ route('login') }}"
                                    class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600 transition duration-150 ease-in-out">
                                    Admin Login
                                </a>
                            </div>
                        </div>
                @endguest -->
            </div>
        </nav>

        <!-- Page Content -->
        <main class="">
            @yield('content')
        </main>

        <footer class="bg-black text-white transition-colors duration-200 border-t border-gray-700 dark:border-gray-600">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="col-span-1 flex flex-col  items-center gap-4">
                        <div class="z-40">
                            <a href="{{ route('home') }}"
                                class="flex items-center space-x-2 text-xl font-bold text-[#8ACE00] transition-colors">
                                <img class="w-48" src="{{ asset('images/logo.webp') }}" alt="CONTROL WEB AGENCY">

                            </a>
                        </div>
                        <p class="text-gray-300 dark:text-gray-400 text-center">Full-service web agency delivering websites, design, marketing,
                            and reliable ongoing support.</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold mb-4">Quick Links</h4>
                        <ul class="space-y-2 text-sm text-gray-300 dark:text-gray-400">
                            <li><a href="{{ route('website-development') }}" class="hover:text-white dark:hover:text-gray-200 transition-colors">Website development</a></li>
                            <li><a href="{{ route('graphic-design') }}" class="hover:text-white dark:hover:text-gray-200 transition-colors">Graphic design</a></li>
                            <li><a href="{{ route('digital-marketing') }}" class="hover:text-white dark:hover:text-gray-200 transition-colors">Digital Marketing</a></li>
                            <li><a href="{{ route('other-services') }}" class="hover:text-white dark:hover:text-gray-200 transition-colors">Other Services</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold mb-4">Contact</h4>
                        <ul class="space-y-2 text-sm text-gray-300 dark:text-gray-400">
                            <li>Email: info@controlweb.dev</li>
                            <li>24/7 Support Available</li>
                        </ul>
                    </div>
                </div>
                <div
                    class="mt-8 pt-8 border-t border-gray-700 dark:border-gray-600 text-center text-sm text-gray-300 dark:text-gray-400">
                    <p>&copy; {{ date('Y') }} CONTROL WEB AGENCY. All rights reserved.</p>
                </div>
            </div>
        </footer>

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
                {{ session('error') }}
            </div>
        @endif
    </div>

    <!-- Alpine.js for other components -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Mobile menu - Pure JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('mobile-hamburger-btn');
            const menu = document.getElementById('mobile-nav-menu');
            const bars = document.getElementById('hamburger-bars');
            const xIcon = document.getElementById('hamburger-x');

            if (!btn || !menu) return;

            let isOpen = false;
            let touchHandled = false;

            function toggleMenu() {
                isOpen = !isOpen;
                if (isOpen) {
                    menu.classList.remove('hidden');
                    menu.classList.add('block');
                    if (bars) bars.classList.add('hidden');
                    if (xIcon) xIcon.classList.remove('hidden');
                } else {
                    menu.classList.add('hidden');
                    menu.classList.remove('block');
                    if (bars) bars.classList.remove('hidden');
                    if (xIcon) xIcon.classList.add('hidden');
                }
            }

            // Click handler (for desktop)
            btn.addEventListener('click', function(e) {
                if (touchHandled) {
                    touchHandled = false;
                    return;
                }
                e.preventDefault();
                e.stopPropagation();
                toggleMenu();
            }, true);

            // Touch handlers - trigger action on touchend
            btn.addEventListener('touchstart', function(e) {
                this.style.opacity = '0.8';
                this.style.transform = 'scale(0.95)';
            }, { passive: true });

            btn.addEventListener('touchend', function(e) {
                this.style.opacity = '1';
                this.style.transform = 'scale(1)';
                touchHandled = true;
                setTimeout(function() { touchHandled = false; }, 300);
                toggleMenu();
            }, { passive: true });

            // Close menu when clicking outside
            document.addEventListener('click', function(e) {
                if (isOpen && !menu.contains(e.target) && !btn.contains(e.target)) {
                    toggleMenu();
                }
            });
        });
    </script>
    <!-- Dark Mode Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Dark mode toggle functionality
            const themeToggle = document.getElementById('theme-toggle');
            const lightIcon = document.getElementById('theme-toggle-light-icon');
            const darkIcon = document.getElementById('theme-toggle-dark-icon');

            // Check for saved theme preference or default to system preference
            const currentTheme = localStorage.getItem('theme');
            const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            if (currentTheme === 'dark' || (!currentTheme && systemPrefersDark)) {
                document.documentElement.classList.add('dark');
                if (lightIcon && darkIcon) {
                    lightIcon.classList.remove('hidden');
                    darkIcon.classList.add('hidden');
                }
            } else {
                document.documentElement.classList.remove('dark');
                if (lightIcon && darkIcon) {
                    lightIcon.classList.add('hidden');
                    darkIcon.classList.remove('hidden');
                }
            }

            // Toggle dark mode
            if (themeToggle) {
                themeToggle.addEventListener('click', function() {
                    if (document.documentElement.classList.contains('dark')) {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('theme', 'light');
                        if (lightIcon && darkIcon) {
                            lightIcon.classList.add('hidden');
                            darkIcon.classList.remove('hidden');
                        }
                    } else {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('theme', 'dark');
                        if (lightIcon && darkIcon) {
                            lightIcon.classList.remove('hidden');
                            darkIcon.classList.add('hidden');
                        }
                    }
                });
            }

            // Listen for system theme changes
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
                if (!localStorage.getItem('theme')) {
                    if (e.matches) {
                        document.documentElement.classList.add('dark');
                        if (lightIcon && darkIcon) {
                            lightIcon.classList.remove('hidden');
                            darkIcon.classList.add('hidden');
                        }
                    } else {
                        document.documentElement.classList.remove('dark');
                        if (lightIcon && darkIcon) {
                            lightIcon.classList.add('hidden');
                            darkIcon.classList.remove('hidden');
                        }
                    }
                }
            });
        });
    </script>
</body>

</html>
