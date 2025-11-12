@extends('layouts.admin')

@section('title', 'Pricing Management')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up">
        <div class="lg:flex space-y-4 justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-[#201E1F] mb-2">Pricing Management</h1>
                <p class="text-[#201E1F]/60">Manage subscription plans and payment configuration</p>
            </div>
            <div class="lg:flex  items-center lg:space-x-3 space-y-2">
                <a href="{{ route('admin.payment.config') }}" 
                   class="bg-white hover:bg-gray-50 text-[#201E1F]/80 hover:text-[#201E1F] border border-gray-200 px-6 py-3 rounded-lg text-sm font-semibold flex items-center space-x-2 transition-all duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>Payment Config</span>
                </a>
                <a href="{{ route('admin.pricing.create') }}" 
                   class="bg-gradient-to-r from-[#D63613] to-[#D63613]/80 text-white px-6 py-3 rounded-lg text-sm font-semibold hover:from-[#D63613]/90 hover:to-[#D63613]/70 transition-all duration-300 shadow-md hover:shadow-lg flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Add New Plan</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Regular Plans -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md animate-fade-in-up" style="animation-delay: 0.2s;">
        <div class="px-6 py-5 border-b border-[#D63613]/10">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-[#201E1F]">Regular Plans</h2>
            </div>
        </div>

        @if(isset($pricingPlans['regular']))
            @foreach(['basic', 'premium'] as $serverType)
                @if(isset($pricingPlans['regular'][$serverType]))
                    <div class="px-6 py-6 {{ !$loop->last ? 'border-b border-[#D63613]/10' : '' }}">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="w-8 h-8 bg-gradient-to-br {{ $serverType === 'basic' ? 'from-emerald-400 to-emerald-600' : 'from-purple-400 to-purple-600' }} rounded-lg flex items-center justify-center">
                                @if($serverType === 'basic')
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3l14 9-14 9V3z"></path>
                                    </svg>
                                @endif
                            </div>
                            <h3 class="text-lg font-semibold text-[#201E1F]">{{ ucfirst($serverType) }} Server</h3>
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $serverType === 'basic' ? 'bg-emerald-100 text-emerald-700' : 'bg-purple-100 text-purple-700' }}">
                                {{ $serverType === 'basic' ? 'Standard' : 'High Performance' }}
                            </span>
                        </div>

                        @foreach($pricingPlans['regular'][$serverType] as $deviceCount => $plans)
                            <div class="mb-8 last:mb-0">
                                <div class="flex items-center space-x-2 mb-4">
                                    <div class="w-6 h-6 bg-gradient-to-br from-cyan-400 to-cyan-600 rounded-md flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <h4 class="text-md font-semibold text-[#201E1F]">{{ $deviceCount }} Device{{ $deviceCount > 1 ? 's' : '' }}</h4>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                    @foreach($plans as $plan)
                                        <div class="bg-white border border-gray-200 rounded-xl p-5 hover:shadow-lg transition-all duration-300 hover:border-[#D63613]/30">
                                            <div class="flex justify-between items-start mb-4">
                                                <div>
                                                    <h5 class="font-semibold text-[#201E1F] mb-1">{{ $plan->duration_months }} Month{{ $plan->duration_months > 1 ? 's' : '' }}</h5>
                                                    <div class="flex items-baseline space-x-1">
                                                        <span class="text-3xl font-bold text-[#D63613]">${{ number_format($plan->price, 2) }}</span>
                                                    </div>
                                                    <p class="text-sm text-[#201E1F]/60 mt-1">${{ number_format($plan->price / $plan->duration_months, 2) }}/month</p>
                                                </div>
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $plan->is_active ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-red-100 text-red-700 border border-red-200' }}">
                                                    {{ $plan->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </div>

                                            @if($plan->features)
                                                <ul class="text-sm space-y-2 mb-5">
                                                    @foreach($plan->features as $feature)
                                                        <li class="flex items-start space-x-2">
                                                            <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                            </svg>
                                                            <span class="text-[#201E1F] font-medium">{{ $feature }}</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif

                                            <div class="flex space-x-2">

                                                <a href="{{ route('admin.pricing.show', $plan) }}" 
                                                   class="flex-1 bg-blue-500 hover:from-blue-600/90 hover:to-blue-600/70 text-white text-center py-2 px-3 rounded-lg text-sm font-semibold transition-all duration-300 flex items-center justify-center space-x-1">
                                                   <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
                                                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                                                  </svg>
                                                    <span>View</span>
                                                </a>
                                                
                                                <a href="{{ route('admin.pricing.edit', $plan) }}" 
                                                   class="flex-1 bg-gradient-to-r from-[#D63613] to-[#D63613]/80 hover:from-[#D63613]/90 hover:to-[#D63613]/70 text-white text-center py-2 px-3 rounded-lg text-sm font-semibold transition-all duration-300 flex items-center justify-center space-x-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                    <span>Edit</span>
                                                </a>
                                                
                                                <form method="POST" action="{{ route('admin.pricing.destroy', $plan) }}" class="flex-1">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            onclick="return confirm('Are you sure you want to delete this plan?')" 
                                                            class="w-full bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white py-2 px-3 rounded-lg text-sm font-semibold transition-all duration-300 flex items-center justify-center space-x-1">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                        <span>Delete</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endforeach
        @else
            <div class="p-8 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-[#201E1F] mb-2">No regular plans found</h3>
                <p class="text-[#201E1F]/60 mb-4">Get started by creating your first regular pricing plan</p>
                <a href="{{ route('admin.pricing.create') }}" 
                   class="inline-flex items-center space-x-2 bg-gradient-to-r from-[#D63613] to-[#D63613]/80 text-white px-6 py-3 rounded-lg font-semibold hover:from-[#D63613]/90 hover:to-[#D63613]/70 transition-all duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Create Plan</span>
                </a>
            </div>
        @endif
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
