@extends('layouts.public')

@section('title', 'Other Services - CONTROL WEB AGENCY')

@section('content')
<section class="bg-white dark:bg-black">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="mb-10">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-3">Other Services</h1>
            <p class="text-gray-600 dark:text-gray-400 max-w-3xl">Everything you need to keep your platforms secure, fast, and reliable.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Web security</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Hardening, monitoring, and incident response.</p>
            </div>
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Hosting and Infrastructure</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Managed cloud and scalable deployments.</p>
            </div>
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Maintenance and Support</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Updates, backups, and SLA support.</p>
            </div>
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Performance Analysis and Tracking</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Analytics, audits, and continuous improvement.</p>
            </div>
        </div>
        <div class="mt-16">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-6">Plans & Pricing</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Web security -->
                <div class="relative bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-6 flex flex-col">
                    <div class="mb-4">
                        <span class="inline-block text-xs font-semibold uppercase tracking-wider text-gray-500">Security</span>
                        <h3 class="mt-1 text-xl font-semibold text-gray-900 dark:text-white">Web security</h3>
                        <div class="mt-3 flex items-end gap-1">
                            <span class="text-3xl font-extrabold text-gray-900 dark:text-white">$299</span>
                            <span class="text-gray-500 text-sm">/mo from</span>
                        </div>
                    </div>
                    <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 flex-1">
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Hardening & WAF</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Malware scans & alerts</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Incident response</li>
                    </ul>
                    <a href="https://checkout.controlweb.ma/products/web-security/checkout" class="mt-6 inline-flex items-center justify-center rounded-lg border border-[#8ACE00] text-[#8ACE00] hover:bg-[#8ACE00] hover:text-black px-4 py-2 font-semibold transition-colors">Talk to us</a>
                </div>

                <!-- Hosting & Infra (featured) -->
                <div class="relative bg-white dark:bg-gray-800 border-2 border-[#8ACE00] rounded-2xl p-6 flex flex-col shadow-lg">
                    <span class="absolute -top-3 left-1/2 -translate-x-1/2 px-3 py-1 text-xs font-bold bg-[#8ACE00] text-black rounded-full">Popular</span>
                    <div class="mb-4">
                        <span class="inline-block text-xs font-semibold uppercase tracking-wider text-gray-500">Cloud</span>
                        <h3 class="mt-1 text-xl font-semibold text-gray-900 dark:text-white">Hosting and Infrastructure</h3>
                        <div class="mt-3 flex items-end gap-1">
                            <span class="text-3xl font-extrabold text-gray-900 dark:text-white">$29</span>
                            <span class="text-gray-500 text-sm">/mo from</span>
                        </div>
                    </div>
                    <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 flex-1">
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Managed servers & backups</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>CDN & SSL management</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Scalable deployments</li>
                    </ul>
                    <a href="https://checkout.controlweb.ma/products/hosting-and-infrastructure/checkout" class="mt-6 inline-flex items-center justify-center rounded-lg bg-[#8ACE00] text-black hover:brightness-95 px-4 py-2 font-semibold transition">Get started</a>
                </div>

                <!-- Maintenance -->
                <div class="relative bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-6 flex flex-col">
                    <div class="mb-4">
                        <span class="inline-block text-xs font-semibold uppercase tracking-wider text-gray-500">Care</span>
                        <h3 class="mt-1 text-xl font-semibold text-gray-900 dark:text-white">Maintenance and Support</h3>
                        <div class="mt-3 flex items-end gap-1">
                            <span class="text-3xl font-extrabold text-gray-900 dark:text-white">$99</span>
                            <span class="text-gray-500 text-sm">/mo from</span>
                        </div>
                    </div>
                    <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 flex-1">
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Updates & patching</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Backups & restores</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Helpdesk & SLAs</li>
                    </ul>
                    <a href="https://checkout.controlweb.ma/products/maintenance-and-support/checkout" class="mt-6 inline-flex items-center justify-center rounded-lg border border-[#8ACE00] text-[#8ACE00] hover:bg-[#8ACE00] hover:text-black px-4 py-2 font-semibold transition-colors">Choose plan</a>
                </div>

                <!-- Performance analysis -->
                <div class="relative bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-6 flex flex-col">
                    <div class="mb-4">
                        <span class="inline-block text-xs font-semibold uppercase tracking-wider text-gray-500">Insights</span>
                        <h3 class="mt-1 text-xl font-semibold text-gray-900 dark:text-white">Performance Analysis and Tracking</h3>
                        <div class="mt-3 flex items-end gap-1">
                            <span class="text-3xl font-extrabold text-gray-900 dark:text-white">$149</span>
                            <span class="text-gray-500 text-sm">/mo from</span>
                        </div>
                    </div>
                    <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300 flex-1">
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Dashboards & KPIs</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Regular audits & insights</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-[#8ACE00] mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414l2.293 2.293 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Actionable recommendations</li>
                    </ul>
                    <a href="https://checkout.controlweb.ma/products/performance-analysis-and-tracking/checkout" class="mt-6 inline-flex items-center justify-center rounded-lg border border-[#8ACE00] text-[#8ACE00] hover:bg-[#8ACE00] hover:text-black px-4 py-2 font-semibold transition-colors">Request details</a>
                </div>
            </div>
        </div>

        <div class="mt-16">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-6">What’s included across services</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5">
                    <div class="text-[#8ACE00] font-semibold mb-2">Monitoring</div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Uptime, alerts, and basic health checks.</p>
                </div>
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5">
                    <div class="text-[#8ACE00] font-semibold mb-2">Documentation</div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Runbooks and change logs for transparency.</p>
                </div>
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5">
                    <div class="text-[#8ACE00] font-semibold mb-2">Support</div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Email support with defined response times.</p>
                </div>
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5">
                    <div class="text-[#8ACE00] font-semibold mb-2">Transparency</div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Clear SLAs and monthly activity reports.</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection


