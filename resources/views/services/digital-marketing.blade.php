@extends('layouts.public')

@section('title', 'Digital Marketing - CONTROL WEB AGENCY')

@section('content')
<section class="bg-white dark:bg-black">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="mb-10">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-3">Digital Marketing</h1>
            <p class="text-gray-600 dark:text-gray-400 max-w-3xl">Acquire, convert, and retain customers with data‑driven growth services.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Advertising Management</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Strategy, creatives, budgets, and reporting.</p>
            </div>
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Social ads campaign management</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Meta, Instagram, TikTok, and more.</p>
            </div>
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Google Ads campaign management</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Search, Display, Shopping, and YouTube.</p>
            </div>
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Social network management</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Content calendars, publishing, and community.</p>
            </div>
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">SEO and Content Writing</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">On‑page SEO, articles, and optimization.</p>
            </div>
        </div>

        <div class="mt-16">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-6">Plans & Pricing</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <!-- Advertising Management -->
                <div class="relative bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-6 flex flex-col">
                    <div class="mb-4">
                        <span class="inline-block text-xs font-semibold uppercase tracking-wider text-gray-500">Management</span>
                        <h3 class="mt-1 text-xl font-semibold text-gray-900 dark:text-white">Advertising Management</h3>
                        <div class="mt-3 flex items-end gap-1">
                            <span class="text-3xl font-extrabold text-gray-900 dark:text-white">$299</span>
                            <span class="text-gray-500 text-sm">/mo from</span>
                        </div>
                    </div>
                    <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 flex-1">
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Strategy & media planning</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Creative briefs & assets</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Weekly optimization</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Monthly performance report</li>
                    </ul>
                    <a href="https://checkout.controlweb.ma/products/advertising-management/checkout" class="mt-6 inline-flex items-center justify-center rounded-lg border border-[#8ACE00] text-[#8ACE00] hover:bg-[#8ACE00] hover:text-black px-4 py-2 font-semibold transition-colors">Get Started</a>
                </div>

                <!-- Social ads -->
                <div class="relative bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-6 flex flex-col">
                    <div class="mb-4">
                        <span class="inline-block text-xs font-semibold uppercase tracking-wider text-gray-500">Social</span>
                        <h3 class="mt-1 text-xl font-semibold text-gray-900 dark:text-white">Social ads campaign management</h3>
                        <div class="mt-3 flex items-end gap-1">
                            <span class="text-3xl font-extrabold text-gray-900 dark:text-white">$399</span>
                            <span class="text-gray-500 text-sm">/mo from</span>
                        </div>
                    </div>
                    <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 flex-1">
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Meta/Instagram/TikTok ads</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Audience & pixel setup</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>A/B creative testing</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>CPA/CAC optimization</li>
                    </ul>
                    <a href="https://checkout.controlweb.ma/products/social-ads-campaign-management/checkout" class="mt-6 inline-flex items-center justify-center rounded-lg border border-[#8ACE00] text-[#8ACE00] hover:bg-[#8ACE00] hover:text-black px-4 py-2 font-semibold transition-colors">Get Started</a>
                </div>

                <!-- Google Ads (featured) -->
                <div class="relative bg-white dark:bg-gray-800 border-2 border-[#8ACE00] rounded-2xl p-6 flex flex-col shadow-lg">
                    <span class="absolute -top-3 left-1/2 -translate-x-1/2 px-3 py-1 text-xs font-bold bg-[#8ACE00] text-black rounded-full">Popular</span>
                    <div class="mb-4">
                        <span class="inline-block text-xs font-semibold uppercase tracking-wider text-gray-500">Search</span>
                        <h3 class="mt-1 text-xl font-semibold text-gray-900 dark:text-white">Google Ads campaign management</h3>
                        <div class="mt-3 flex items-end gap-1">
                            <span class="text-3xl font-extrabold text-gray-900 dark:text-white">$399</span>
                            <span class="text-gray-500 text-sm">/mo from</span>
                        </div>
                    </div>
                    <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 flex-1">
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Search/Display/Shopping/YouTube</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Conversion tracking setup</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Keyword & bid optimization</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Search term pruning</li>
                    </ul>
                    <a href="https://checkout.controlweb.ma/products/google-ads-campaign-management/checkout" class="mt-6 inline-flex items-center justify-center rounded-lg bg-[#8ACE00] text-black hover:brightness-95 px-4 py-2 font-semibold transition">Get started</a>
                </div>

                <!-- Social network management -->
                <div class="relative bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-6 flex flex-col">
                    <div class="mb-4">
                        <span class="inline-block text-xs font-semibold uppercase tracking-wider text-gray-500">Community</span>
                        <h3 class="mt-1 text-xl font-semibold text-gray-900 dark:text-white">Social network management</h3>
                        <div class="mt-3 flex items-end gap-1">
                            <span class="text-3xl font-extrabold text-gray-900 dark:text-white">$499</span>
                            <span class="text-gray-500 text-sm">/mo from</span>
                        </div>
                    </div>
                    <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 flex-1">
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Monthly calendar & scheduling</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Copy & creative production</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Community responses</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Insights & growth plan</li>
                    </ul>
                    <a href="https://checkout.controlweb.ma/products/social-network-management/checkout" class="mt-6 inline-flex items-center justify-center rounded-lg border border-[#8ACE00] text-[#8ACE00] hover:bg-[#8ACE00] hover:text-black px-4 py-2 font-semibold transition-colors">Get Started</a>
                </div>

                <!-- SEO & Content -->
                <div class="relative bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-6 flex flex-col">
                    <div class="mb-4">
                        <span class="inline-block text-xs font-semibold uppercase tracking-wider text-gray-500">Organic</span>
                        <h3 class="mt-1 text-xl font-semibold text-gray-900 dark:text-white">SEO and Content Writing</h3>
                        <div class="mt-3 flex items-end gap-1">
                            <span class="text-3xl font-extrabold text-gray-900 dark:text-white">$449</span>
                            <span class="text-gray-500 text-sm">/mo from</span>
                        </div>
                    </div>
                    <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 flex-1">
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Technical & on‑page SEO</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Content brief & articles</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Internal linking & schema</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Monthly progress report</li>
                    </ul>
                    <a href="https://checkout.controlweb.ma/products/seo-and-content-writing/checkout" class="mt-6 inline-flex items-center justify-center rounded-lg border border-[#8ACE00] text-[#8ACE00] hover:bg-[#8ACE00] hover:text-black px-4 py-2 font-semibold transition-colors">Get Started</a>
                </div>
            </div>
        </div>

        <div class="mt-16">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-6">What’s included with every engagement</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5">
                    <div class="text-[#8ACE00] font-semibold mb-2">Tracking</div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Analytics, pixels, and conversion tracking configured.</p>
                </div>
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5">
                    <div class="text-[#8ACE00] font-semibold mb-2">Reporting</div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Clear monthly dashboards and insights.</p>
                </div>
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5">
                    <div class="text-[#8ACE00] font-semibold mb-2">Experimentation</div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">A/B tests to continually improve performance.</p>
                </div>
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5">
                    <div class="text-[#8ACE00] font-semibold mb-2">Ownership</div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">You own the ad accounts, creatives, and data.</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection


