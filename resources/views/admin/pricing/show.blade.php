@extends('layouts.admin')

@section('title', 'View Pricing Plan - ' . $pricingPlan->display_name)

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up">
        <div class="lg:flex space-y-4 justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-[#201E1F] mb-2">Pricing Plan Details</h1>
                <p class="text-[#201E1F]/60">View and manage plan configuration and settings</p>
            </div>
            <div class="lg:flex  items-center lg:space-x-3 space-y-2">
                <a href="{{ route('admin.pricing.edit', $pricingPlan) }}" 
                   class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-3 rounded-lg text-sm font-semibold flex items-center space-x-2 transition-all duration-300 shadow-md hover:shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <span>Edit Plan</span>
                </a>
                <a href="{{ route('admin.pricing.index') }}" 
                   class="bg-white hover:bg-gray-50 text-[#201E1F]/80 hover:text-[#201E1F] border border-gray-200 px-6 py-3 rounded-lg text-sm font-semibold flex items-center space-x-2 transition-all duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Back to Plans</span>
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Plan Details -->
        <div class="lg:col-span-2">
            <!-- Basic Information -->
            <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md animate-fade-in-up" style="animation-delay: 0.2s;">
                <div class="px-6 py-5 border-b border-[#D63613]/10">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-[#201E1F]">Plan Information</h2>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-[#201E1F] mb-2">Plan Name</label>
                            <p class="text-[#201E1F] text-lg">{{ $pricingPlan->name }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-[#201E1F] mb-2">Display Name</label>
                            <p class="text-[#201E1F] text-lg">{{ $pricingPlan->display_name }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-[#201E1F] mb-2">Server Type</label>
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $pricingPlan->server_type === 'premium' ? 'bg-gradient-to-r from-purple-100 to-purple-200 text-purple-700 border border-purple-300' : 'bg-gradient-to-r from-emerald-100 to-emerald-200 text-emerald-700 border border-emerald-300' }}">
                                {{ ucfirst($pricingPlan->server_type) }}
                            </span>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-[#201E1F] mb-2">Device Count</label>
                            <div class="flex items-center space-x-2">
                                <div class="w-6 h-6 bg-gradient-to-br from-cyan-400 to-cyan-600 rounded-md flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <p class="text-[#201E1F] text-lg">{{ $pricingPlan->device_count }} device{{ $pricingPlan->device_count > 1 ? 's' : '' }}</p>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-[#201E1F] mb-2">Duration</label>
                            <div class="flex items-center space-x-2">
                                <div class="w-6 h-6 bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-md flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-[#201E1F] text-lg">{{ $pricingPlan->duration_months }} month{{ $pricingPlan->duration_months > 1 ? 's' : '' }}</p>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-[#201E1F] mb-2">Price</label>
                            <div class="flex items-baseline space-x-2">
                                <p class="text-3xl font-bold text-[#D63613]">${{ number_format($pricingPlan->price, 2) }}</p>
                                <span class="text-[#201E1F]/60">total</span>
                            </div>
                            <p class="text-sm text-[#201E1F]/60 mt-1">${{ number_format($pricingPlan->price / $pricingPlan->duration_months, 2) }}/month</p>
                        </div>
                    </div>

                    @if($pricingPlan->features && count($pricingPlan->features) > 0)
                    <div class="mt-8 pt-6 border-t border-[#D63613]/10">
                        <label class="block text-sm font-semibold text-[#201E1F] mb-4">Features</label>
                        <div class="bg-white p-4 rounded-lg border border-gray-200">
                            <ul class="space-y-3">
                                @foreach($pricingPlan->features as $feature)
                                <li class="flex items-start space-x-3">
                                    <svg class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-[#201E1F] font-medium">{{ $feature }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endif

                    @if($pricingPlan->payment_link)
                    <div class="mt-6 pt-6 border-t border-[#D63613]/10">
                        <label class="block text-sm font-semibold text-[#201E1F] mb-2">Payment Link</label>
                        <div class="bg-white p-3 rounded-lg border border-gray-200">
                            <a href="{{ $pricingPlan->payment_link }}" target="_blank" class="text-[#D63613] hover:text-[#D63613]/80 break-all font-medium flex items-center space-x-2">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                <span>{{ $pricingPlan->payment_link }}</span>
                            </a>
                        </div>
                    </div>
                    @endif

                    <div class="mt-6 pt-6 border-t border-[#D63613]/10">
                        <label class="block text-sm font-semibold text-[#201E1F] mb-2">Status</label>
                        <span class="inline-flex px-3 py-2 text-sm font-semibold rounded-full {{ $pricingPlan->is_active ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-red-100 text-red-700 border border-red-200' }}">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                @if($pricingPlan->is_active)
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                @else
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                @endif
                            </svg>
                            {{ $pricingPlan->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-8">
            <!-- Plan Preview -->
            <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md animate-fade-in-up" style="animation-delay: 0.3s;">
                <div class="px-6 py-5 border-b border-[#D63613]/10">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-[#201E1F]">Plan Preview</h2>
                    </div>
                </div>
                
                <div class="p-6">
                    <!-- Plan Card Preview -->
                    <div class="bg-white border-2 rounded-xl p-6 {{ $pricingPlan->duration_months == 12 ? 'border-[#D63613] relative' : 'border-gray-200' }} hover:shadow-lg transition-all duration-300">
                        @if($pricingPlan->duration_months == 12)
                            <div class="absolute -top-3 left-1/2 transform -translate-x-1/2">
                                <span class="bg-gradient-to-r from-[#D63613] to-[#D63613]/80 text-white px-4 py-1 rounded-full text-xs font-semibold shadow-md">Best Value</span>
                            </div>
                        @endif
                        
                        <div class="text-center mb-6">
                            <h3 class="text-xl font-bold text-[#201E1F] mb-3">{{ $pricingPlan->display_name }}</h3>
                            <div class="flex items-baseline justify-center space-x-1">
                                <span class="text-4xl font-bold text-[#D63613]">${{ number_format($pricingPlan->price, 2) }}</span>
                                <span class="text-[#201E1F]/60">/ {{ $pricingPlan->duration_months }} month{{ $pricingPlan->duration_months > 1 ? 's' : '' }}</span>
                            </div>
                            <p class="text-sm text-[#201E1F]/60 mt-2">${{ number_format($pricingPlan->price / $pricingPlan->duration_months, 2) }}/month</p>
                        </div>
                        
                        <div class="space-y-3 text-sm mb-6">
                            <div class="flex justify-between items-center p-2 bg-gray-50 rounded-lg">
                                <span class="text-[#201E1F]/70">Server:</span>
                                <span class="font-semibold text-[#201E1F]">{{ ucfirst($pricingPlan->server_type) }}</span>
                            </div>
                            <div class="flex justify-between items-center p-2 bg-gray-50 rounded-lg">
                                <span class="text-[#201E1F]/70">Devices:</span>
                                <span class="font-semibold text-[#201E1F]">{{ $pricingPlan->device_count }}</span>
                            </div>
                            <div class="flex justify-between items-center p-2 bg-gray-50 rounded-lg">
                                <span class="text-[#201E1F]/70">Duration:</span>
                                <span class="font-semibold text-[#201E1F]">{{ $pricingPlan->duration_months }} month{{ $pricingPlan->duration_months > 1 ? 's' : '' }}</span>
                            </div>
                        </div>

                        @if($pricingPlan->features && count($pricingPlan->features) > 0)
                        <div class="mb-6 pt-4 border-t border-gray-200">
                            <ul class="space-y-2">
                                @foreach($pricingPlan->features as $feature)
                                <li class="flex items-center text-sm text-[#201E1F]">
                                    <svg class="w-4 h-4 text-green-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $feature }}
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        
                        <button class="w-full bg-gray-100 text-[#201E1F]/60 py-3 px-4 rounded-lg text-sm font-semibold cursor-not-allowed flex items-center justify-center space-x-2" disabled>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <span>Preview Only</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md animate-fade-in-up" style="animation-delay: 0.4s;">
                <div class="px-6 py-5 border-b border-[#D63613]/10">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-amber-400 to-amber-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-[#201E1F]">Plan Statistics</h2>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center p-3 bg-white rounded-lg border border-gray-200">
                            <span class="text-[#201E1F]/70 font-medium">Created:</span>
                            <span class="font-semibold text-[#201E1F]">{{ $pricingPlan->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-white rounded-lg border border-gray-200">
                            <span class="text-[#201E1F]/70 font-medium">Last Updated:</span>
                            <span class="font-semibold text-[#201E1F]">{{ $pricingPlan->updated_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-white rounded-lg border border-gray-200">
                            <span class="text-[#201E1F]/70 font-medium">Total Orders:</span>
                            <span class="font-bold text-[#D63613] text-lg">{{ $pricingPlan->orders_count ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
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
</style>
@endsection