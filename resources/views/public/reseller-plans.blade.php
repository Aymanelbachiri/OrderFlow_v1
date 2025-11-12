@extends('layouts.public')

@section('title', 'Reseller Plans - Start Your IPTV Business')

@section('content')
   

    <!-- Benefits Section -->
    <div class="py-20 bg-white dark:bg-gray-900">
        

        <!-- Credit Packs Section -->
        <div id="plans" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

            @if ($creditPacks->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach ($creditPacks as $pack)
                        <div
                            class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 relative">
                            <!-- Popular Badge -->
                            @if ($pack->id == 2)
                                <div class="absolute -top-3 left-1/2 transform -translate-x-1/2 z-10">
                                    <span
                                        class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-4 py-1 rounded-full text-sm font-medium">Most
                                        Popular</span>
                                </div>
                            @endif

                            <div class="p-8">
                                <div class="text-center">
                                    <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $pack->name ?? 'Credit Pack' }}
                                    </h3>
                                    <div class="mb-6">
                                        <span
                                            class="text-4xl font-bold text-blue-600">{{ $pack->formatted_price ?? '$0.00' }}</span>
                                    </div>
                                    <div class="mb-6">
                                        <span
                                            class="text-2xl font-semibold text-gray-900">{{ number_format($pack->credits_amount ?? 0) }}</span>
                                        <span class="text-gray-500">Credits</span>
                                    </div>
                                    <div class="text-sm text-gray-600 mb-6">
                                        {{ $pack->formatted_price_per_credit ?? '$0.0000' }} per credit
                                    </div>
                                </div>

                                <!-- Features -->
                                @if ($pack->features && count($pack->features) > 0)
                                    <div class="mb-8">
                                        <ul class="space-y-3">
                                            @foreach ($pack->features as $feature)
                                                <li class="flex items-center text-gray-700">
                                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                    {{ $feature }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <!-- CTA Button -->
                                <div class="text-center">
                                    <a href="{{ route('reseller.checkout.show', ['plan_id' => $pack->id, 'source' => 'main']) }}"
                                        class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-300 inline-block">
                                        Get Started
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m13-8V4a1 1 0 00-1-1H7a1 1 0 00-1 1v1m8 0V4.5">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No Credit Packs Available</h3>
                    <p class="text-gray-600 mb-6">Credit packs are currently being prepared. Please check back soon!</p>
                    <a href="{{ route('home') }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-300">
                        Register for Updates
                    </a>
                </div>
            @endif
        </div>

        
    </div>

    
@endsection
