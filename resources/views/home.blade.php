@extends('layouts.public')

@section('title', 'CONTROL WEB AGENCY - Build Your Digital Presence')

@section('content')
<section class="bg-white dark:bg-black h-min-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-gray-900 dark:text-white mb-4">Boost Your Digital Presence</h1>
            <p class="text-lg text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">Websites, design, marketing, and reliable support — discover our service plans designed to fit your goals.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <a href="{{ route('website-development') }}" class="group bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6 hover:shadow-xl transition-all">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Website development</h2>
                    <span class="text-[#8ACE00]">→</span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Landing pages, showcase sites, e‑commerce, and custom web apps.</p>
            </a>

            <a href="{{ route('graphic-design') }}" class="group bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6 hover:shadow-xl transition-all">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Graphic design</h2>
                    <span class="text-[#8ACE00]">→</span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Visual identities, mockups, UI/UX, and landing page design.</p>
            </a>

            <a href="{{ route('digital-marketing') }}" class="group bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6 hover:shadow-xl transition-all">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Digital Marketing</h2>
                    <span class="text-[#8ACE00]">→</span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Ads management, Google Ads, social media, SEO and content.</p>
            </a>

            <a href="{{ route('other-services') }}" class="group bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6 hover:shadow-xl transition-all">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Other Services</h2>
                    <span class="text-[#8ACE00]">→</span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Security, hosting, maintenance, and performance analytics.</p>
            </a>
        </div>
    </div>
</section>

<section class="bg-gray-50 dark:bg-gray-950 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white mb-4">Our Process and Strategy</h2>
            <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">Here's how we turn your vision into a digital reality from start to finish.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 md:gap-8 relative">
            <div class="hidden md:block absolute top-1/2 left-0 w-full h-px -translate-y-1/2">
                <svg class="w-full" height="2" xmlns="http://www.w3.org/2000/svg">
                    <line x1="0" y1="1" x2="100%" y2="1" stroke-width="3" stroke-dasharray="8 8" class="stroke-gray-300 dark:stroke-gray-700" />
                </svg>
            </div>

            <div class="relative bg-white dark:bg-black p-8 rounded-xl shadow-lg z-10">
                <div class="absolute -top-6 left-1/2 -translate-x-1/2 bg-[#8ACE00] text-white text-xl font-bold rounded-full h-12 w-12 flex items-center justify-center border-4 border-gray-50 dark:border-gray-950">
                    01
                </div>
                <div class="mt-8 text-center md:text-left">
                    <h3 class="text-2xl font-semibold text-gray-900 dark:text-white mb-3">Analysis and Strategy</h3>
                    <p class="text-gray-600 dark:text-gray-400">We start with an in-depth analysis of your project, defining clear objectives and a tailored strategy to ensure success.</p>
                </div>
            </div>

            <div class="relative bg-white dark:bg-black p-8 rounded-xl shadow-lg z-10">
                <div class="absolute -top-6 left-1/2 -translate-x-1/2 bg-[#8ACE00] text-white text-xl font-bold rounded-full h-12 w-12 flex items-center justify-center border-4 border-gray-50 dark:border-gray-950">
                    02
                </div>
                <div class="mt-8 text-center md:text-left">
                    <h3 class="text-2xl font-semibold text-gray-900 dark:text-white mb-3">Design and Development</h3>
                    <p class="text-gray-600 dark:text-gray-400">Our team creates personalized, user-friendly, and optimized designs, followed by clean and efficient development.</p>
                </div>
            </div>

            <div class="relative bg-white dark:bg-black p-8 rounded-xl shadow-lg z-10">
                <div class="absolute -top-6 left-1/2 -translate-x-1/2 bg-[#8ACE00] text-white text-xl font-bold rounded-full h-12 w-12 flex items-center justify-center border-4 border-gray-50 dark:border-gray-950">
                    03
                </div>
                <div class="mt-8 text-center md:text-left">
                    <h3 class="text-2xl font-semibold text-gray-900 dark:text-white mb-3">Launch and Optimization</h3>
                    <p class="text-gray-600 dark:text-gray-400">After rigorous testing, we launch your project and continuously monitor its performance for ongoing optimization.</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection