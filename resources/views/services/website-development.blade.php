@extends('layouts.public')

@section('title', 'Website Development - CONTROL WEB AGENCY')

@section('content')
<section class="bg-white dark:bg-black">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="mb-10">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-3">Website development</h1>
            <p class="text-gray-600 dark:text-gray-400 max-w-3xl">We design and build modern, fast, and scalable websites tailored to your business goals.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Landing Page</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">High-conversion single-page site for campaigns and launches.</p>
            </div>
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Showcase site</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Elegant multi-page presence to present your brand and services.</p>
            </div>
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">E-commerce website</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Sell online with secure checkout and product management.</p>
            </div>
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Custom Web solutions</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Custom-built apps and integrations for unique requirements.</p>
            </div>
        </div>

        <div class="mt-16">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-6">Plans & Pricing</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Landing Page -->
                <div class="relative bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-6 flex flex-col">
                    <div class="mb-4">
                        <span class="inline-block text-xs font-semibold uppercase tracking-wider text-gray-500">Starter</span>
                        <h3 class="mt-1 text-xl font-semibold text-gray-900 dark:text-white">Landing Page</h3>
                        <div class="mt-3 flex items-end gap-1">
                            <span class="text-3xl font-extrabold text-gray-900 dark:text-white">$299</span>
                            <span class="text-gray-500 text-sm">starting</span>
                        </div>
                    </div>
                    <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 flex-1">
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Single responsive page</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Basic SEO setup & analytics</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Contact form & call‑to‑action</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Performance optimization</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>1 round of revisions</li>
                    </ul>
                    <a href="https://checkout.controlweb.dev/products/landing-page/checkout" class="mt-6 inline-flex items-center justify-center rounded-lg border border-[#8ACE00] text-[#8ACE00] hover:bg-[#8ACE00] hover:text-black px-4 py-2 font-semibold transition-colors">Get Started</a>
                </div>

                <!-- Showcase site -->
                <div class="relative bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-6 flex flex-col">
                    <div class="mb-4">
                        <span class="inline-block text-xs font-semibold uppercase tracking-wider text-gray-500">Business</span>
                        <h3 class="mt-1 text-xl font-semibold text-gray-900 dark:text-white">Showcase site</h3>
                        <div class="mt-3 flex items-end gap-1">
                            <span class="text-3xl font-extrabold text-gray-900 dark:text-white">$799</span>
                            <span class="text-gray-500 text-sm">starting</span>
                        </div>
                    </div>
                    <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 flex-1">
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>5–10 pages (Home, About, Services, Contact…)</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>CMS for easy content editing</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Blog/News module (optional)</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>On‑page SEO & performance</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>2 rounds of revisions</li>
                    </ul>
                    <a href="https://checkout.controlweb.dev/products/showcase-site/checkout" class="mt-6 inline-flex items-center justify-center rounded-lg border border-[#8ACE00] text-[#8ACE00] hover:bg-[#8ACE00] hover:text-black px-4 py-2 font-semibold transition-colors">Get Started</a>
                </div>

                <!-- E‑commerce website -->
                <div class="relative bg-white dark:bg-gray-800 border-2 border-[#8ACE00] rounded-2xl p-6 flex flex-col shadow-lg">
                    <span class="absolute -top-3 left-1/2 -translate-x-1/2 px-3 py-1 text-xs font-bold bg-[#8ACE00] text-black rounded-full">Popular</span>
                    <div class="mb-4">
                        <span class="inline-block text-xs font-semibold uppercase tracking-wider text-gray-500">Growth</span>
                        <h3 class="mt-1 text-xl font-semibold text-gray-900 dark:text-white">E‑commerce website</h3>
                        <div class="mt-3 flex items-end gap-1">
                            <span class="text-3xl font-extrabold text-gray-900 dark:text-white">$1,499</span>
                            <span class="text-gray-500 text-sm">starting</span>
                        </div>
                    </div>
                    <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 flex-1">
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Product catalog & search</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Cart & secure checkout</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Payments (Stripe/PayPal) integration</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Orders, coupons & shipping rules</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Training & launch support</li>
                    </ul>
                    <a href="https://checkout.controlweb.dev/products/ecommerce-website/checkout" class="mt-6 inline-flex items-center justify-center rounded-lg bg-[#8ACE00] text-black hover:brightness-95 px-4 py-2 font-semibold transition">Get started</a>
                </div>

                <!-- Custom Web solutions -->
                <div class="relative bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-6 flex flex-col">
                    <div class="mb-4">
                        <span class="inline-block text-xs font-semibold uppercase tracking-wider text-gray-500">Tailored</span>
                        <h3 class="mt-1 text-xl font-semibold text-gray-900 dark:text-white">Custom Web solutions</h3>
                        <div class="mt-3 flex items-end gap-1">
                            <span class="text-3xl font-extrabold text-gray-900 dark:text-white">$2,999+</span>
                            <span class="text-gray-500 text-sm">scoped</span>
                        </div>
                    </div>
                    <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 flex-1">
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Custom features & workflows</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>3rd‑party integrations & APIs</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Authentication & roles</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Scalable architecture & performance</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Discovery workshop & roadmap</li>
                    </ul>
                    <a href="https://checkout.controlweb.dev/products/custom-web-solutions/checkout" class="mt-6 inline-flex items-center justify-center rounded-lg border border-[#8ACE00] text-[#8ACE00] hover:bg-[#8ACE00] hover:text-black px-4 py-2 font-semibold transition-colors">Get Started</a>
                </div>
            </div>
        </div>

        <div class="mt-16">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-6">What’s included with every project</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5">
                    <div class="text-[#8ACE00] font-semibold mb-2">Quality</div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Responsive design, accessibility, code reviews and best practices.</p>
                </div>
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5">
                    <div class="text-[#8ACE00] font-semibold mb-2">Performance</div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Optimized assets, caching and core web vitals focus.</p>
                </div>
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5">
                    <div class="text-[#8ACE00] font-semibold mb-2">SEO-ready</div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Meta tags, sitemap, robots and analytics integration.</p>
                </div>
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5">
                    <div class="text-[#8ACE00] font-semibold mb-2">Support</div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Guides, handover, and post‑launch assistance.</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection


