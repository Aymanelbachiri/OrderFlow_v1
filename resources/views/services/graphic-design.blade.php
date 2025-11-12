@extends('layouts.public')

@section('title', 'Graphic Design - CONTROL WEB AGENCY')

@section('content')
<section class="bg-white dark:bg-black">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="mb-10">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-3">Graphic design</h1>
            <p class="text-gray-600 dark:text-gray-400 max-w-3xl">Brand‑first creative for memorable experiences across web and mobile.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Creating a visual identity</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Logos, color systems, and brand guidelines.</p>
            </div>
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Mockup design</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Product and marketing mockups for presentations.</p>
            </div>
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">UI/UX design</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Wireframes and interfaces focused on usability.</p>
            </div>
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Mobile application design</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">iOS and Android design systems and screens.</p>
            </div>
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Landing page design</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Conversion‑focused layouts for campaigns.</p>
            </div>
        </div>

        <div class="mt-16">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-6">Plans & Pricing</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <!-- Visual identity -->
                <div class="relative bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-6 flex flex-col">
                    <div class="mb-4">
                        <span class="inline-block text-xs font-semibold uppercase tracking-wider text-gray-500">Branding</span>
                        <h3 class="mt-1 text-xl font-semibold text-gray-900 dark:text-white">Creating a visual identity</h3>
                        <div class="mt-3 flex items-end gap-1">
                            <span class="text-3xl font-extrabold text-gray-900 dark:text-white">$349</span>
                            <span class="text-gray-500 text-sm">starting</span>
                        </div>
                    </div>
                    <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 flex-1">
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Logo concepts & iterations</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Color palette & typography</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Brand guidelines PDF</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Social media kit</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>2 rounds of revisions</li>
                    </ul>
                    <a href="https://checkout.controlweb.ma/products/creating-a-visual-identity/checkout" class="mt-6 inline-flex items-center justify-center rounded-lg border border-[#8ACE00] text-[#8ACE00] hover:bg-[#8ACE00] hover:text-black px-4 py-2 font-semibold transition-colors">Get Started</a>
                </div>

                <!-- Mockup design -->
                <div class="relative bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-6 flex flex-col">
                    <div class="mb-4">
                        <span class="inline-block text-xs font-semibold uppercase tracking-wider text-gray-500">Presentation</span>
                        <h3 class="mt-1 text-xl font-semibold text-gray-900 dark:text-white">Mockup design</h3>
                        <div class="mt-3 flex items-end gap-1">
                            <span class="text-3xl font-extrabold text-gray-900 dark:text-white">$199</span>
                            <span class="text-gray-500 text-sm">starting</span>
                        </div>
                    </div>
                    <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 flex-1">
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Product/packaging scenes</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>3 angles or environments</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>High‑resolution exports</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Source files included</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>1 round of revisions</li>
                    </ul>
                    <a href="https://checkout.controlweb.ma/products/mockup-design/checkout" class="mt-6 inline-flex items-center justify-center rounded-lg border border-[#8ACE00] text-[#8ACE00] hover:bg-[#8ACE00] hover:text-black px-4 py-2 font-semibold transition-colors">Get Started</a>
                </div>

                <!-- UI/UX design (featured) -->
                <div class="relative bg-white dark:bg-gray-800 border-2 border-[#8ACE00] rounded-2xl p-6 flex flex-col shadow-lg">
                    <span class="absolute -top-3 left-1/2 -translate-x-1/2 px-3 py-1 text-xs font-bold bg-[#8ACE00] text-black rounded-full">Popular</span>
                    <div class="mb-4">
                        <span class="inline-block text-xs font-semibold uppercase tracking-wider text-gray-500">Product</span>
                        <h3 class="mt-1 text-xl font-semibold text-gray-900 dark:text-white">UI/UX design</h3>
                        <div class="mt-3 flex items-end gap-1">
                            <span class="text-3xl font-extrabold text-gray-900 dark:text-white">$1,099</span>
                            <span class="text-gray-500 text-sm">starting</span>
                        </div>
                    </div>
                    <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 flex-1">
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Wireframes & user flows</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>High‑fidelity screens</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Design system & components</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Interactive prototype</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Handover to development</li>
                    </ul>
                    <a href="https://checkout.controlweb.ma/products/uiux-design/checkout" class="mt-6 inline-flex items-center justify-center rounded-lg bg-[#8ACE00] text-black hover:brightness-95 px-4 py-2 font-semibold transition">Get started</a>
                </div>

                <!-- Mobile application design -->
                <div class="relative bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-6 flex flex-col">
                    <div class="mb-4">
                        <span class="inline-block text-xs font-semibold uppercase tracking-wider text-gray-500">Mobile</span>
                        <h3 class="mt-1 text-xl font-semibold text-gray-900 dark:text-white">Mobile application design</h3>
                        <div class="mt-3 flex items-end gap-1">
                            <span class="text-3xl font-extrabold text-gray-900 dark:text-white">$1,499</span>
                            <span class="text-gray-500 text-sm">starting</span>
                        </div>
                    </div>
                    <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 flex-1">
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>iOS & Android screens</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Design system & icons</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Prototype for flows</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Developer handover</li>
                    </ul>
                    <a href="https://checkout.controlweb.ma/products/mobile-application-design/checkout" class="mt-6 inline-flex items-center justify-center rounded-lg border border-[#8ACE00] text-[#8ACE00] hover:bg-[#8ACE00] hover:text-black px-4 py-2 font-semibold transition-colors">Get Started</a>
                </div>

                <!-- Landing page design -->
                <div class="relative bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-6 flex flex-col">
                    <div class="mb-4">
                        <span class="inline-block text-xs font-semibold uppercase tracking-wider text-gray-500">Conversion</span>
                        <h3 class="mt-1 text-xl font-semibold text-gray-900 dark:text-white">Landing page design</h3>
                        <div class="mt-3 flex items-end gap-1">
                            <span class="text-3xl font-extrabold text-gray-900 dark:text-white">$399</span>
                            <span class="text-gray-500 text-sm">starting</span>
                        </div>
                    </div>
                    <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 flex-1">
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Above‑the‑fold concept</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Sections & layout</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Conversion‑focused CTA</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Copy guidance</li>
                    </ul>
                    <a href="https://checkout.controlweb.ma/products/landing-page-design/checkout" class="mt-6 inline-flex items-center justify-center rounded-lg border border-[#8ACE00] text-[#8ACE00] hover:bg-[#8ACE00] hover:text-black px-4 py-2 font-semibold transition-colors">Get Started</a>
                </div>
            </div>
        </div>

        <div class="mt-16">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-6">What’s included with every design</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5">
                    <div class="text-[#8ACE00] font-semibold mb-2">Editable sources</div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Figma/Adobe files with organized layers and styles.</p>
                </div>
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5">
                    <div class="text-[#8ACE00] font-semibold mb-2">Licensing help</div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Guidance on fonts, stock assets, and usage rights.</p>
                </div>
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5">
                    <div class="text-[#8ACE00] font-semibold mb-2">Responsive variants</div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Key breakpoints for a consistent cross‑device look.</p>
                </div>
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5">
                    <div class="text-[#8ACE00] font-semibold mb-2">Brand review</div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Consistency check and handover for implementation.</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection


