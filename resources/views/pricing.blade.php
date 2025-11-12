@extends('layouts.public')



@section('content')
    

    <!-- Pricing Plans Section -->
    <div class="py-20 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Package Type Toggle Switch -->
            <div class="flex justify-center mb-16 animate-fade-in-up">
                <div class="relative bg-gray-100 dark:bg-gray-800 p-1 rounded-2xl shadow-lg">
                    <div class="flex items-center space-x-1">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 px-3">Basic</span>
                        <div class="relative">
                            <input type="checkbox" id="package-toggle" class="sr-only" onchange="togglePackageType()">
                            <label for="package-toggle" class="flex items-center cursor-pointer">
                                <div class="relative">
                                    <div
                                        class="w-16 h-8 bg-gray-300 dark:bg-gray-600 rounded-full shadow-inner transition-colors duration-300">
                                    </div>
                                    <div
                                        class="toggle-dot absolute w-6 h-6 bg-white rounded-full shadow-md top-1 left-1 transition-transform duration-300 flex items-center justify-center">
                                        <div
                                            class="w-3 h-3 bg-blue-500 rounded-full transition-colors duration-300 dot-inner">
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 px-3">Premium</span>
                    </div>
                    <div class="text-center mt-2">
                        <span id="package-label" class="text-lg font-bold text-blue-500">Basic Package</span>
                    </div>
                </div>
            </div>

            <!-- Basic Server Plans -->
            <div id="basic-plans" class="server-plans">
                <div class="text-center mb-12 animate-fade-in-up">
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mb-4">Basic Server Plans</h2>
                    <p class="text-xl text-gray-600 dark:text-gray-300">Perfect for casual viewing with HD quality and
                        essential features</p>
                </div>

                <!-- Device Tabs for Basic -->
                <div class="flex justify-center mb-12">
                    <div class="bg-gray-100 dark:bg-gray-800 p-2 rounded-2xl shadow-lg">
                        <div class="flex flex-wrap justify-center gap-1">
                            @if (isset($pricingPlans['basic']) && count($pricingPlans['basic']) > 0)
                                @foreach ($pricingPlans['basic'] as $deviceCount => $plans)
                                    <button onclick="showDevicePlans('basic', {{ $deviceCount }})"
                                        class="basic-device-tab {{ $loop->first ? 'active' : '' }} px-4 py-3 rounded-xl font-medium text-sm transition-all duration-300 
                                               {{ $loop->first ? 'bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-lg' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                                        {{ $deviceCount }} Device{{ $deviceCount > 1 ? 's' : '' }}
                                    </button>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Device Plans Content for Basic -->
                @if (isset($pricingPlans['basic']))
                    @foreach ($pricingPlans['basic'] as $deviceCount => $plans)
                        <div id="basic-device-{{ $deviceCount }}"
                            class="basic-device-content {{ $loop->first ? 'active' : '' }}"
                            style="{{ $loop->first ? '' : 'display: none;' }}">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-8 text-center">
                                {{ $deviceCount }} Device{{ $deviceCount > 1 ? 's' : '' }} Plan</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mt-6">
                                @foreach ($plans as $index => $plan)
                                    <div class="group relative bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 p-8 hover:transform hover:scale-105 animate-fade-in-up {{ $plan->duration_months == 12 ? 'border-2 border-blue-500 ring-4 ring-blue-100 dark:ring-blue-900/50' : 'border border-gray-200 dark:border-gray-700' }}"
                                        style="animation-delay: {{ $index * 0.1 }}s">
                                        @if ($plan->duration_months == 12)
                                            <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 z-10">
                                                <span
                                                    class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-4 py-2 rounded-full text-sm font-bold shadow-lg whitespace-nowrap">
                                                    🔥 BEST VALUE
                                                </span>
                                            </div>
                                        @endif

                                        <div class="text-center">
                                            <!-- Duration Badge -->
                                            <div
                                                class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-orange-100 to-orange-200 dark:from-orange-900 dark:to-orange-800 rounded-2xl mb-6">
                                                <span
                                                    class="text-2xl font-bold text-orange-500">{{ $plan->duration_months }}M</span>
                                            </div>

                                            <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                                                {{ $plan->duration_months }}
                                                Month{{ $plan->duration_months > 1 ? 's' : '' }}
                                            </h4>

                                            <!-- Price -->
                                            <div class="mb-6">
                                                <div class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
                                                    ${{ number_format($plan->price, 0) }}</div>
                                                <div class="text-lg text-gray-500 dark:text-gray-400">
                                                    ${{ number_format($plan->price / $plan->duration_months, 2) }}/month
                                                </div>
                                                @if ($plan->duration_months > 1)
                                                    <div class="text-sm text-orange-500 font-medium">Save
                                                        {{ round((1 - $plan->price / $plan->duration_months / ($plan->price / 1)) * 100) }}%
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Features -->
                                            @if ($plan->features)
                                                <ul class="text-left space-y-3 mb-8">
                                                    @foreach ($plan->features as $feature)
                                                        <li
                                                            class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                                                            <div
                                                                class="w-5 h-5 bg-orange-100 dark:bg-orange-900 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                                                <svg class="w-3 h-3 text-orange-500" fill="currentColor"
                                                                    viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd"
                                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                        clip-rule="evenodd"></path>
                                                                </svg>
                                                            </div>
                                                            {{ $feature }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif

                                            <!-- CTA Button -->
                                            <a href="{{ route('checkout.show', ['plan_id' => $plan->id, 'source' => 'main']) }}"
                                                class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white py-4 px-6 rounded-xl font-bold text-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl block text-center group-hover:shadow-2xl">
                                                Subscribe
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @else
                    <!-- Fallback if no basic plans configured -->
                    <div class="text-center py-12">
                        <div class="text-gray-500 dark:text-gray-400 mb-4">
                            <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No Basic Plans Available</h3>
                        <p class="text-gray-600 dark:text-gray-300">Please check back later or contact support for
                            assistance.</p>
                    </div>
                @endif
            </div>

            <!-- Premium Server Plans -->
            <div id="premium-plans" class="server-plans hidden">
                <div class="text-center mb-12 animate-fade-in-up">
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mb-4">Premium Server Plans</h2>
                    <p class="text-xl text-gray-600 dark:text-gray-300">Enhanced experience with premium channels, 4K
                        quality, and exclusive features</p>
                </div>

                <!-- Device Tabs for Premium -->
                <div class="flex justify-center mb-12">
                    <div class="bg-gray-100 dark:bg-gray-800 p-2 rounded-2xl shadow-lg">
                        <div class="flex flex-wrap justify-center gap-1">
                            @if (isset($pricingPlans['premium']) && count($pricingPlans['premium']) > 0)
                                @foreach ($pricingPlans['premium'] as $deviceCount => $plans)
                                    <button onclick="showDevicePlans('premium', {{ $deviceCount }})"
                                        class="premium-device-tab {{ $loop->first ? 'active' : '' }} px-4 py-3 rounded-xl font-medium text-sm transition-all duration-300 
                                               {{ $loop->first ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-lg' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                                        {{ $deviceCount }} Device{{ $deviceCount > 1 ? 's' : '' }}
                                    </button>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Device Plans Content for Premium -->
                @if (isset($pricingPlans['premium']))
                    @foreach ($pricingPlans['premium'] as $deviceCount => $plans)
                        <div id="premium-device-{{ $deviceCount }}"
                            class="premium-device-content {{ $loop->first ? 'active' : '' }}"
                            style="{{ $loop->first ? '' : 'display: none;' }}">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-8 text-center">
                                {{ $deviceCount }} Device{{ $deviceCount > 1 ? 's' : '' }} Plan</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mt-6">
                                @foreach ($plans as $index => $plan)
                                    <div class="group relative bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 p-8 hover:transform hover:scale-105 animate-fade-in-up {{ $plan->duration_months == 12 ? 'border-2 border-blue-500 ring-4 ring-blue-100 dark:ring-blue-900/50' : 'border border-gray-200 dark:border-gray-700' }}"
                                        style="animation-delay: {{ $index * 0.1 }}s">
                                        @if ($plan->duration_months == 12)
                                            <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 z-10">
                                                <span
                                                    class="bg-gradient-to-r from-purple-500 to-purple-600 text-white px-4 py-2 rounded-full text-sm font-bold shadow-lg whitespace-nowrap">
                                                    ⭐ PREMIUM VALUE
                                                </span>
                                            </div>
                                        @endif

                                        <div class="text-center">
                                            <!-- Duration Badge -->
                                            <div
                                                class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-purple-100 to-purple-200 dark:from-purple-900 dark:to-purple-800 rounded-2xl mb-6">
                                                <span
                                                    class="text-2xl font-bold text-purple-500">{{ $plan->duration_months }}M</span>
                                            </div>

                                            <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                                                {{ $plan->duration_months }}
                                                Month{{ $plan->duration_months > 1 ? 's' : '' }}
                                            </h4>

                                            <!-- Price -->
                                            <div class="mb-6">
                                                <div class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
                                                    ${{ number_format($plan->price, 0) }}</div>
                                                <div class="text-lg text-gray-500 dark:text-gray-400">
                                                    ${{ number_format($plan->price / $plan->duration_months, 2) }}/month
                                                </div>
                                                @if ($plan->duration_months > 1)
                                                    <div class="text-sm text-purple-500 font-medium">Save
                                                        {{ round((1 - $plan->price / $plan->duration_months / ($plan->price / 1)) * 100) }}%
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Features -->
                                            @if ($plan->features)
                                                <ul class="text-left space-y-3 mb-8">
                                                    @foreach ($plan->features as $feature)
                                                        <li
                                                            class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                                                            <div
                                                                class="w-5 h-5 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                                                <svg class="w-3 h-3 text-purple-500" fill="currentColor"
                                                                    viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd"
                                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                        clip-rule="evenodd"></path>
                                                                </svg>
                                                            </div>
                                                            {{ $feature }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif

                                            <!-- CTA Button -->
                                            <a href="{{ route('checkout.show', ['plan_id' => $plan->id, 'source' => 'main']) }}"
                                                class="w-full bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white py-4 px-6 rounded-xl font-bold text-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl block text-center group-hover:shadow-2xl">
                                                Subscribe
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @else
                    <!-- Fallback if no premium plans configured -->
                    <div class="text-center py-12">
                        <div class="text-gray-500 dark:text-gray-400 mb-4">
                            <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No Premium Plans Available
                        </h3>
                        <p class="text-gray-600 dark:text-gray-300">Please check back later or contact support for
                            assistance.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        /* Toggle Switch Styles */
        .toggle-dot {
            transition: transform 0.3s ease;
        }

        #package-toggle:checked~label .toggle-dot {
            transform: translateX(32px);
        }

        #package-toggle:checked~label .toggle-dot .dot-inner {
            background-color: #8b5cf6 !important;
        }

        #package-toggle:checked~label>div>div {
            background-color: #8b5cf6;
        }

        /* Device Tab Styles */
        .basic-device-tab.active,
        .premium-device-tab.active {
            position: relative;
            overflow: hidden;
        }

        .basic-device-tab.active::before,
        .premium-device-tab.active::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .basic-device-tab:hover::before,
        .premium-device-tab:hover::before {
            left: 100%;
        }

        /* Animation Styles */
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

        /* Device Content Display */
        .basic-device-content,
        .premium-device-content {
            display: none;
        }

        .basic-device-content.active,
        .premium-device-content.active {
            display: block;
            animation: fade-in-up 0.5s ease-out;
        }

        /* Plan Card Hover Effects */
        .group:hover {
            transform: translateY(-8px);
        }

        /* Responsive Design */
        @media (max-width: 768px) {

            .basic-device-tab,
            .premium-device-tab {
                padding: 0.5rem 0.75rem;
                font-size: 0.75rem;
            }
        }
    </style>

    <script>
        function togglePackageType() {
            const toggle = document.getElementById('package-toggle');
            const label = document.getElementById('package-label');
            const basicPlans = document.getElementById('basic-plans');
            const premiumPlans = document.getElementById('premium-plans');

            if (toggle.checked) {
                // Switch to Premium
                label.textContent = 'Premium Package';
                label.classList.remove('text-blue-500');
                label.classList.add('text-purple-500');
                basicPlans.classList.add('hidden');
                premiumPlans.classList.remove('hidden');

                // Show first premium device tab
                const firstPremiumTab = document.querySelector('.premium-device-tab');
                if (firstPremiumTab) {
                    const deviceCount = firstPremiumTab.textContent.match(/\d+/)[0];
                    showDevicePlans('premium', parseInt(deviceCount));
                }
            } else {
                // Switch to Basic
                label.textContent = 'Basic Package';
                label.classList.remove('text-purple-500');
                label.classList.add('text-blue-500');
                premiumPlans.classList.add('hidden');
                basicPlans.classList.remove('hidden');

                // Show first basic device tab
                const firstBasicTab = document.querySelector('.basic-device-tab');
                if (firstBasicTab) {
                    const deviceCount = firstBasicTab.textContent.match(/\d+/)[0];
                    showDevicePlans('basic', parseInt(deviceCount));
                }
            }
        }

        function showDevicePlans(packageType, deviceCount) {
            // Hide all device contents for the current package type
            document.querySelectorAll(`.${packageType}-device-content`).forEach(el => {
                el.classList.remove('active');
                el.style.display = 'none';
            });

            // Show selected device content
            const targetContent = document.getElementById(`${packageType}-device-${deviceCount}`);
            if (targetContent) {
                targetContent.style.display = 'block';
                targetContent.classList.add('active');
            }

            // Update tab styles - Reset all tabs for current package type
            document.querySelectorAll(`.${packageType}-device-tab`).forEach(tab => {
                tab.classList.remove('active');
                if (packageType === 'basic') {
                    tab.classList.remove('bg-gradient-to-r', 'from-orange-500', 'to-red-600', 'text-white',
                        'shadow-lg');
                } else {
                    tab.classList.remove('bg-gradient-to-r', 'from-purple-500', 'to-purple-600', 'text-white',
                        'shadow-lg');
                }
                tab.classList.add('text-gray-700', 'dark:text-gray-300', 'hover:bg-gray-200',
                    'dark:hover:bg-gray-700');
            });

            // Style active tab
            const activeTabs = document.querySelectorAll(`.${packageType}-device-tab`);
            activeTabs.forEach(tab => {
                const tabDeviceCount = parseInt(tab.textContent.match(/\d+/)[0]);
                if (tabDeviceCount === deviceCount) {
                    tab.classList.add('active');
                    tab.classList.remove('text-gray-700', 'dark:text-gray-300', 'hover:bg-gray-200',
                        'dark:hover:bg-gray-700');
                    if (packageType === 'basic') {
                        tab.classList.add('bg-gradient-to-r', 'from-orange-500', 'to-red-600', 'text-white',
                            'shadow-lg');
                    } else {
                        tab.classList.add('bg-gradient-to-r', 'from-purple-500', 'to-purple-600', 'text-white',
                            'shadow-lg');
                    }
                }
            });
        }

        // Legacy function for backward compatibility
        function showServerType(type) {
            const toggle = document.getElementById('package-toggle');
            if (type === 'premium') {
                toggle.checked = true;
            } else {
                toggle.checked = false;
            }
            togglePackageType();
        }

        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize with first basic device tab active
            const firstBasicTab = document.querySelector('.basic-device-tab');
            if (firstBasicTab) {
                const deviceCount = parseInt(firstBasicTab.textContent.match(/\d+/)[0]);
                showDevicePlans('basic', deviceCount);
            }

            // Initialize animation observer
            const animatedElements = document.querySelectorAll('.animate-fade-in-up');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, {
                threshold: 0.1
            });

            animatedElements.forEach(el => {
                if (!el.style.opacity) {
                    el.style.opacity = '0';
                    el.style.transform = 'translateY(30px)';
                    el.style.transition = 'opacity 0.8s ease-out, transform 0.8s ease-out';
                }
                observer.observe(el);
            });
        });
    </script>



  

    

    
@endsection
