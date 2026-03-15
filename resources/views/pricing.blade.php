@extends('layouts.public')



@section('content')
    

    <!-- Pricing Plans Section -->
    <div class="py-20 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Package Type Selector -->
            <div class="flex justify-center mb-16 animate-fade-in-up">
                <div class="relative bg-gray-100 dark:bg-gray-800 p-2 rounded-2xl shadow-lg">
                    <div class="flex flex-wrap justify-center gap-1">
                        @if(isset($pricingPlans['basic']))
                            <button onclick="showPackageType('basic')" 
                                class="package-type-tab active px-6 py-3 rounded-xl font-medium text-sm transition-all duration-300 bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-lg"
                                data-type="basic">
                                Basic
                            </button>
                        @endif
                        @if(isset($pricingPlans['premium']))
                            <button onclick="showPackageType('premium')" 
                                class="package-type-tab px-6 py-3 rounded-xl font-medium text-sm transition-all duration-300 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700"
                                data-type="premium">
                                Premium
                            </button>
                        @endif
                        @if(isset($genericPlans) && $genericPlans->count() > 0)
                            @foreach($genericPlans as $label => $deviceGroups)
                                <button onclick="showPackageType('generic-{{ Str::slug($label) }}')" 
                                    class="package-type-tab px-6 py-3 rounded-xl font-medium text-sm transition-all duration-300 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700"
                                    data-type="generic-{{ Str::slug($label) }}">
                                    {{ $label }}
                                </button>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <!-- Basic Server Plans -->
            <div id="basic-plans" class="server-plans package-section">
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
            <div id="premium-plans" class="server-plans package-section hidden">
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

            <!-- Generic Server Plans -->
            @if(isset($genericPlans) && $genericPlans->count() > 0)
                @foreach($genericPlans as $label => $deviceGroups)
                    @php $slug = Str::slug($label); @endphp
                    <div id="generic-{{ $slug }}-plans" class="server-plans package-section hidden">
                        <div class="text-center mb-12 animate-fade-in-up">
                            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mb-4">{{ $label }} Plans</h2>
                            <p class="text-xl text-gray-600 dark:text-gray-300">Explore our {{ $label }} subscription options</p>
                        </div>

                        <!-- Device Tabs -->
                        <div class="flex justify-center mb-12">
                            <div class="bg-gray-100 dark:bg-gray-800 p-2 rounded-2xl shadow-lg">
                                <div class="flex flex-wrap justify-center gap-1">
                                    @foreach($deviceGroups as $deviceCount => $plans)
                                        <button onclick="showDevicePlans('generic-{{ $slug }}', {{ $deviceCount }})"
                                            class="generic-{{ $slug }}-device-tab {{ $loop->first ? 'active bg-gradient-to-r from-amber-500 to-orange-600 text-white shadow-lg' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }} px-4 py-3 rounded-xl font-medium text-sm transition-all duration-300">
                                            {{ $deviceCount }} Device{{ $deviceCount > 1 ? 's' : '' }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Device Plans Content -->
                        @foreach($deviceGroups as $deviceCount => $plans)
                            <div id="generic-{{ $slug }}-device-{{ $deviceCount }}"
                                class="generic-{{ $slug }}-device-content {{ $loop->first ? 'active' : '' }}"
                                style="{{ $loop->first ? '' : 'display: none;' }}">
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-8 text-center">
                                    {{ $deviceCount }} Device{{ $deviceCount > 1 ? 's' : '' }} Plan</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mt-6">
                                    @foreach($plans as $index => $plan)
                                        <div class="group relative bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 p-8 hover:transform hover:scale-105 animate-fade-in-up {{ $plan->duration_months == 12 ? 'border-2 border-amber-500 ring-4 ring-amber-100 dark:ring-amber-900/50' : 'border border-gray-200 dark:border-gray-700' }}"
                                            style="animation-delay: {{ $index * 0.1 }}s">
                                            @if($plan->duration_months == 12)
                                                <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 z-10">
                                                    <span class="bg-gradient-to-r from-amber-500 to-orange-600 text-white px-4 py-2 rounded-full text-sm font-bold shadow-lg whitespace-nowrap">
                                                        BEST VALUE
                                                    </span>
                                                </div>
                                            @endif

                                            <div class="text-center">
                                                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-amber-100 to-amber-200 dark:from-amber-900 dark:to-amber-800 rounded-2xl mb-6">
                                                    <span class="text-2xl font-bold text-amber-500">{{ $plan->duration_months }}M</span>
                                                </div>

                                                <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                                                    {{ $plan->duration_months }} Month{{ $plan->duration_months > 1 ? 's' : '' }}
                                                </h4>

                                                <div class="mb-6">
                                                    <div class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
                                                        ${{ number_format($plan->price, 0) }}</div>
                                                    <div class="text-lg text-gray-500 dark:text-gray-400">
                                                        ${{ number_format($plan->price / $plan->duration_months, 2) }}/month
                                                    </div>
                                                    @if($plan->duration_months > 1)
                                                        <div class="text-sm text-amber-500 font-medium">Save
                                                            {{ round((1 - $plan->price / $plan->duration_months / ($plan->price / 1)) * 100) }}%
                                                        </div>
                                                    @endif
                                                </div>

                                                @if($plan->features)
                                                    <ul class="text-left space-y-3 mb-8">
                                                        @foreach($plan->features as $feature)
                                                            <li class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                                                                <div class="w-5 h-5 bg-amber-100 dark:bg-amber-900 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                                                    <svg class="w-3 h-3 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                                    </svg>
                                                                </div>
                                                                {{ $feature }}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif

                                                <a href="{{ route('checkout.show', ['plan_id' => $plan->id, 'source' => 'main']) }}"
                                                    class="w-full bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white py-4 px-6 rounded-xl font-bold text-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl block text-center group-hover:shadow-2xl">
                                                    Subscribe
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <style>
        /* Package Type Tab Styles */
        .package-type-tab.active {
            position: relative;
            overflow: hidden;
        }

        .package-type-tab.active::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .package-type-tab:hover::before {
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
        const tabGradients = {
            'basic': ['from-blue-500', 'to-indigo-600'],
            'premium': ['from-indigo-500', 'to-purple-600'],
        };
        const defaultGradient = ['from-amber-500', 'to-orange-600'];

        function getGradient(type) {
            return tabGradients[type] || defaultGradient;
        }

        function getDeviceGradient(packageType) {
            if (packageType === 'basic') return ['from-blue-500', 'to-indigo-600'];
            if (packageType === 'premium') return ['from-indigo-500', 'to-purple-600'];
            return ['from-amber-500', 'to-orange-600'];
        }

        function showPackageType(type) {
            // Hide all package sections
            document.querySelectorAll('.package-section').forEach(el => el.classList.add('hidden'));

            // Show the selected section
            const target = document.getElementById(type + '-plans');
            if (target) {
                target.classList.remove('hidden');
            }

            // Reset all package type tabs
            const gradient = getGradient(type);
            document.querySelectorAll('.package-type-tab').forEach(tab => {
                tab.classList.remove('active', 'bg-gradient-to-r', 'text-white', 'shadow-lg',
                    'from-blue-500', 'to-indigo-600', 'from-indigo-500', 'to-purple-600',
                    'from-amber-500', 'to-orange-600');
                tab.classList.add('text-gray-700', 'dark:text-gray-300', 'hover:bg-gray-200', 'dark:hover:bg-gray-700');
            });

            // Style the active tab
            const activeTab = document.querySelector(`.package-type-tab[data-type="${type}"]`);
            if (activeTab) {
                activeTab.classList.add('active', 'bg-gradient-to-r', 'text-white', 'shadow-lg', ...gradient);
                activeTab.classList.remove('text-gray-700', 'dark:text-gray-300', 'hover:bg-gray-200', 'dark:hover:bg-gray-700');
            }

            // Show first device tab of the selected package
            const firstDeviceTab = document.querySelector(`.${type}-device-tab`);
            if (firstDeviceTab) {
                const deviceCount = parseInt(firstDeviceTab.textContent.match(/\d+/)[0]);
                showDevicePlans(type, deviceCount);
            }
        }

        function showDevicePlans(packageType, deviceCount) {
            document.querySelectorAll(`.${packageType}-device-content`).forEach(el => {
                el.classList.remove('active');
                el.style.display = 'none';
            });

            const targetContent = document.getElementById(`${packageType}-device-${deviceCount}`);
            if (targetContent) {
                targetContent.style.display = 'block';
                targetContent.classList.add('active');
            }

            const deviceGradient = getDeviceGradient(packageType);

            document.querySelectorAll(`.${packageType}-device-tab`).forEach(tab => {
                tab.classList.remove('active', 'bg-gradient-to-r', 'text-white', 'shadow-lg',
                    'from-blue-500', 'to-indigo-600', 'from-indigo-500', 'to-purple-600',
                    'from-amber-500', 'to-orange-600', 'from-orange-500', 'to-red-600');
                tab.classList.add('text-gray-700', 'dark:text-gray-300', 'hover:bg-gray-200', 'dark:hover:bg-gray-700');
            });

            document.querySelectorAll(`.${packageType}-device-tab`).forEach(tab => {
                const tabDeviceCount = parseInt(tab.textContent.match(/\d+/)[0]);
                if (tabDeviceCount === deviceCount) {
                    tab.classList.add('active', 'bg-gradient-to-r', 'text-white', 'shadow-lg', ...deviceGradient);
                    tab.classList.remove('text-gray-700', 'dark:text-gray-300', 'hover:bg-gray-200', 'dark:hover:bg-gray-700');
                }
            });
        }

        function showServerType(type) {
            showPackageType(type);
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Show the first available package type
            const firstTab = document.querySelector('.package-type-tab');
            if (firstTab) {
                showPackageType(firstTab.dataset.type);
            }

            const animatedElements = document.querySelectorAll('.animate-fade-in-up');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, { threshold: 0.1 });

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
