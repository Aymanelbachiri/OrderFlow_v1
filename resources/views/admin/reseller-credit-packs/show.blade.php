@extends('layouts.admin')

@section('title', 'Reseller Credit Pack Details')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6 animate-fade-in-up">
        <div class="lg:flex space-y-4 justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-[#201E1F] mb-2">Credit Pack Details</h1>
                <p class="text-[#201E1F]/60">View comprehensive information about this credit package</p>
            </div>
            <div class="lg:flex  items-center lg:space-x-3 space-y-2">
                <a href="{{ route('admin.reseller-credit-packs.edit', $resellerCreditPack) }}" 
                   class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-3 rounded-lg text-sm font-semibold flex items-center space-x-2 transition-all duration-300 shadow-md hover:shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <span>Edit Pack</span>
                </a>
                <a href="{{ route('admin.reseller-credit-packs.index') }}" 
                   class="bg-white hover:bg-gray-50 text-[#201E1F]/80 hover:text-[#201E1F] border border-gray-200 px-6 py-3 rounded-lg text-sm font-semibold flex items-center space-x-2 transition-all duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Back to Credit Packs</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Credit Pack Overview -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md animate-fade-in-up" style="animation-delay: 0.1s;">
        <div class="px-6 py-5 border-b border-[#D63613]/10">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-[#201E1F]">{{ $resellerCreditPack->name }}</h3>
                        <p class="text-sm text-[#201E1F]/60">Credit package overview and specifications</p>
                    </div>
                </div>
                <div class="flex items-center">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                        @if($resellerCreditPack->is_active) 
                            bg-green-100 text-green-800 border border-green-200
                        @else 
                            bg-red-100 text-red-800 border border-red-200
                        @endif">
                        @if($resellerCreditPack->is_active)
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Active Package
                        @else
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Inactive Package
                        @endif
                    </span>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Credits Amount -->
                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-[#201E1F]/60 uppercase tracking-wider">Credits Amount</label>
                            <p class="text-lg font-bold text-[#201E1F]">{{ number_format($resellerCreditPack->credits_amount) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Price -->
                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-green-400 to-green-600 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-[#201E1F]/60 uppercase tracking-wider">Total Price</label>
                            <p class="text-lg font-bold text-[#201E1F]">{{ $resellerCreditPack->formatted_price }}</p>
                        </div>
                    </div>
                </div>

                <!-- Price per Credit -->
                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-[#201E1F]/60 uppercase tracking-wider">Price per Credit</label>
                            <p class="text-lg font-bold text-[#201E1F]">{{ $resellerCreditPack->formatted_price_per_credit }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Package Details -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md animate-fade-in-up" style="animation-delay: 0.2s;">
        <div class="px-6 py-5 border-b border-[#D63613]/10">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-semibold text-[#201E1F]">Package Information</h3>
                    <p class="text-sm text-[#201E1F]/60">Detailed information and timestamps</p>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Created Date -->
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-gradient-to-br from-orange-400 to-orange-600 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-[#201E1F]/60 uppercase tracking-wider">Created Date</label>
                            <p class="text-sm font-semibold text-[#201E1F]">{{ $resellerCreditPack->created_at->format('M d, Y g:i A') }}</p>
                        </div>
                    </div>

                    <!-- Last Updated -->
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-gradient-to-br from-teal-400 to-teal-600 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-[#201E1F]/60 uppercase tracking-wider">Last Updated</label>
                            <p class="text-sm font-semibold text-[#201E1F]">{{ $resellerCreditPack->updated_at->format('M d, Y g:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    @if($resellerCreditPack->features && count($resellerCreditPack->features) > 0)
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md animate-fade-in-up" style="animation-delay: 0.3s;">
        <div class="px-6 py-5 border-b border-[#D63613]/10">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-semibold text-[#201E1F]">Package Features</h3>
                    <p class="text-sm text-[#201E1F]/60">Included features and benefits</p>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($resellerCreditPack->features as $feature)
                        <div class="flex items-center space-x-3">
                            <div class="w-5 h-5 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-[#201E1F]">{{ $feature }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Payment Methods Section -->
    @if($resellerCreditPack->payment_methods && count($resellerCreditPack->payment_methods) > 0)
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md animate-fade-in-up" style="animation-delay: 0.4s;">
        <div class="px-6 py-5 border-b border-[#D63613]/10">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-cyan-400 to-cyan-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-semibold text-[#201E1F]">Payment Methods</h3>
                    <p class="text-sm text-[#201E1F]/60">Accepted payment options for this package</p>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <div class="flex flex-wrap gap-3">
                    @foreach($resellerCreditPack->payment_methods as $method)
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            {{ ucfirst(str_replace('_', ' ', $method)) }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Danger Zone -->
    <div class="bg-red-50 border border-red-200 rounded-xl shadow-md animate-fade-in-up" style="animation-delay: 0.5s;">
        <div class="px-6 py-5 border-b border-red-200">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-red-400 to-red-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-semibold text-red-800">Danger Zone</h3>
                    <p class="text-sm text-red-600">Irreversible and destructive actions</p>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <div class="bg-white rounded-lg border border-red-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-lg font-semibold text-red-800 mb-1">Delete Credit Pack</h4>
                        <p class="text-sm text-red-600">Permanently remove this credit pack. This action cannot be undone.</p>
                    </div>
                    <form action="{{ route('admin.reseller-credit-packs.destroy', $resellerCreditPack) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this credit pack? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-semibold rounded-lg transition-all duration-300 shadow-md hover:shadow-lg flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            <span>Delete Credit Pack</span>
                        </button>
                    </form>
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