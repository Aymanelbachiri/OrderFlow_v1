@php abort(404); @endphp
    <!-- Credit Pack Context (if provided) -->
    @if($creditPack)
        <div class="mb-6 p-4 bg-gradient-to-r from-orange-50 to-red-50 border border-orange-200 rounded-xl">
            <h3 class="text-lg font-medium text-orange-900 mb-3">Selected Credit Pack</h3>
            <div class="flex justify-between items-center">
                <div>
                    <p class="font-medium text-orange-800">{{ $creditPack->name }}</p>
                    <p class="text-sm text-orange-600">{{ number_format($creditPack->credits_amount) }} Credits</p>
                </div>
                <div class="text-right">
                    <p class="text-xl font-bold text-orange-900">{{ $creditPack->formatted_price }}</p>
                    <p class="text-sm text-orange-600">{{ $creditPack->formatted_price_per_credit }} per credit</p>
                </div>
            </div>
            <input type="hidden" name="credit_pack_id" value="{{ $creditPack->id }}">
        </div>
    @endif

    <!-- Registration Header -->
    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-2">Create Reseller Account</h2>
        <p class="text-gray-600">
            @if($creditPack)
                Complete your registration to purchase the selected credit pack
            @else
                Join our reseller program and start earning today
            @endif
        </p>
    </div>

    <form method="POST" action="{{ route('register.reseller.store') }}" class="space-y-6">
        @csrf
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
            <input id="name"
                   type="text"
                   name="name"
                   value="{{ old('name') }}"
                   required
                   autofocus
                   autocomplete="name"
                   placeholder="Enter your full name"
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200 bg-gray-50 focus:bg-white" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
            <input id="email"
                   type="email"
                   name="email"
                   value="{{ old('email') }}"
                   required
                   autocomplete="username"
                   placeholder="Enter your email address"
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200 bg-gray-50 focus:bg-white" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Phone -->
        <div>
            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number (Optional)</label>
            <input id="phone"
                   type="tel"
                   name="phone"
                   value="{{ old('phone') }}"
                   autocomplete="tel"
                   placeholder="Enter your phone number"
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200 bg-gray-50 focus:bg-white" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
            <div class="relative">
                <input id="password"
                       type="password"
                       name="password"
                       required
                       autocomplete="new-password"
                       placeholder="Create a password"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200 bg-gray-50 focus:bg-white pr-12" />
                <button type="button"
                        onclick="togglePassword('password')"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                    <svg id="eye-icon-password" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
            <div class="relative">
                <input id="password_confirmation"
                       type="password"
                       name="password_confirmation"
                       required
                       autocomplete="new-password"
                       placeholder="Confirm your password"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200 bg-gray-50 focus:bg-white pr-12" />
                <button type="button"
                        onclick="togglePassword('password_confirmation')"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                    <svg id="eye-icon-confirmation" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Reseller Benefits -->
        <div class="p-6 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl">
            <h4 class="font-semibold text-green-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                Reseller Benefits
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div class="flex items-center text-sm text-green-800">
                    <svg class="w-4 h-4 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    Purchase credits at wholesale prices
                </div>
                <div class="flex items-center text-sm text-green-800">
                    <svg class="w-4 h-4 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    Manage customer subscriptions
                </div>
                <div class="flex items-center text-sm text-green-800">
                    <svg class="w-4 h-4 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    Access to reseller dashboard
                </div>
                <div class="flex items-center text-sm text-green-800">
                    <svg class="w-4 h-4 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    Dedicated reseller support
                </div>
                <div class="flex items-center text-sm text-green-800">
                    <svg class="w-4 h-4 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    Flexible credit pack options
                </div>
            </div>
        </div>

        <!-- Terms and Conditions -->
        <div class="flex items-start">
            <input id="terms"
                   type="checkbox"
                   required
                   class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded mt-1">
            <label for="terms" class="ml-2 text-sm text-gray-600">
                I agree to the
                <a href="#" class="text-orange-600 hover:text-orange-500 font-medium">Terms of Service</a>
                and
                <a href="#" class="text-orange-600 hover:text-orange-500 font-medium">Reseller Agreement</a>
            </label>
        </div>

        <!-- Create Account Button -->
        @if($creditPack)
            <input type="hidden" name="credit_pack_id" value="{{ $creditPack->id }}" />
        @endif

        <button type="submit" id="reseller-register-btn" data-custom-touch="true"
                class="w-full bg-black text-white py-3 px-4 rounded-lg font-medium hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200 touch-manipulation"
                style="-webkit-tap-highlight-color: transparent; min-height: 44px;">
            @if($creditPack)
                Register & Continue to Payment
            @else
                Create Reseller Account
            @endif
        </button>

        <!-- Alternative Links -->
        <div class="space-y-4 text-center">
            <div>
                <span class="text-gray-600">Already have an account? </span>
                <a href="{{ route('login') }}" class="font-medium text-orange-600 hover:text-orange-500">
                    Sign In
                </a>
            </div>

            <div>
                <span class="text-gray-600">Looking for a regular subscription? </span>
                <a href="{{ route('checkout.show') }}" class="font-medium text-orange-600 hover:text-orange-500">
                    Subscribe via Checkout
                </a>
            </div>
        </div>
    </form>

    <script>
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const eyeIcon = document.getElementById(`eye-icon-${fieldId === 'password_confirmation' ? 'confirmation' : 'password'}`);

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                `;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                `;
            }
        }

        // MOBILE FIX: Trigger form submission on touchend
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const submitBtn = document.getElementById('reseller-register-btn');

            if (submitBtn && form) {
                let isSubmitting = false;
                let touchMoved = false;

                submitBtn.addEventListener('touchstart', function(e) {
                    touchMoved = false;
                    this.style.opacity = '0.9';
                    this.style.transform = 'scale(0.98)';
                }, { passive: true });

                submitBtn.addEventListener('touchmove', function(e) {
                    touchMoved = true;
                }, { passive: true });

                submitBtn.addEventListener('touchend', function(e) {
                    this.style.opacity = '1';
                    this.style.transform = 'scale(1)';

                    if (!touchMoved && !isSubmitting) {
                        e.preventDefault();
                        isSubmitting = true;

                        if (typeof form.requestSubmit === 'function') {
                            form.requestSubmit(submitBtn);
                        } else {
                            form.submit();
                        }
                        setTimeout(() => { isSubmitting = false; }, 3000);
                    }
                });
            }
        });
    </script>
</x-guest-layout>
