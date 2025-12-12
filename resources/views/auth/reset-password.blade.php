<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>reset password</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    
        <div class="min-h-screen flex items-center justify-center bg-black  p-4">
            <!-- Reset Password Card -->
            <div class="w-full max-w-md">
                <!-- Card Container -->
                <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-gray-200 dark:border-gray-700">
                    
                    <!-- Icon -->
                    <div class="flex justify-center mb-6">
                        <div class="w-16 h-16 bg-gradient-to-br from-[#8ACD00] to-[#6FAD00] rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </div>
                    </div>
    
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Reset Password</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Enter your new password below
                        </p>
                    </div>
    
                    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
                        @csrf
    
                        <!-- Password Reset Token -->
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">
    
                        <!-- Email Address (Read-only) -->
                        <div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <input id="email"
                                       type="email"
                                       name="email"
                                       value="{{ old('email', $request->email) }}"
                                       required
                                       autofocus
                                       autocomplete="username"
                                       readonly
                                       class="w-full pl-12 pr-4 py-3.5 bg-gray-100 dark:bg-gray-700/30 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-600 dark:text-gray-400 cursor-not-allowed" />
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
    
                        <!-- New Password -->
                        <div>
                            <label for="password" class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">New Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <input id="password"
                                       type="password"
                                       name="password"
                                       required
                                       autocomplete="new-password"
                                       placeholder="Enter your new password"
                                       class="w-full pl-12 pr-12 py-3.5 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-[#8ACD00] focus:border-[#8ACD00] transition-all duration-200 text-gray-900 dark:text-white placeholder-gray-400" />
                                <button type="button"
                                        onclick="togglePassword('password')"
                                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                                    <svg id="eye-icon-password" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                                    </svg>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
    
                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">Confirm New Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <input id="password_confirmation"
                                       type="password"
                                       name="password_confirmation"
                                       required
                                       autocomplete="new-password"
                                       placeholder="Confirm your new password"
                                       class="w-full pl-12 pr-12 py-3.5 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-[#8ACD00] focus:border-[#8ACD00] transition-all duration-200 text-gray-900 dark:text-white placeholder-gray-400" />
                                <button type="button"
                                        onclick="togglePassword('password_confirmation')"
                                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                                    <svg id="eye-icon-confirmation" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                                    </svg>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>
    
                        <!-- Password Requirements -->
                        <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4">
                            <p class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">Password must contain:</p>
                            <ul class="space-y-1 text-xs text-gray-600 dark:text-gray-400">
                                <li class="flex items-center">
                                    <svg class="w-3 h-3 text-[#8ACD00] mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    At least 8 characters
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-3 h-3 text-[#8ACD00] mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    One uppercase & lowercase letter
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-3 h-3 text-[#8ACD00] mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    One number or special character
                                </li>
                            </ul>
                        </div>
    
                        <!-- Reset Password Button -->
                        <button type="submit" id="reset-password-btn" data-custom-touch="true"
                                class="w-full bg-gradient-to-r from-gray-900 to-black dark:from-gray-800 dark:to-gray-900 text-white py-3.5 px-4 rounded-xl font-semibold hover:from-black hover:to-gray-900 dark:hover:from-gray-700 dark:hover:to-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#8ACD00] transition-all duration-200 shadow-lg hover:shadow-xl touch-manipulation"
                                style="-webkit-tap-highlight-color: transparent; min-height: 44px;">
                            Reset Password
                        </button>
    
                        <!-- Back to Login Link -->
                        <div class="text-center pt-2">
                            <a href="{{ route('login') }}" 
                               class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-[#8ACD00] dark:hover:text-[#8ACD00] font-medium transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                Back to Sign In
                            </a>
                        </div>
                    </form>
                </div>
    
                <!-- Logo at Bottom -->
                <div class="flex justify-center mt-8">
                    <img class="w-32 opacity-80" src="{{ asset('images/logo.webp') }}" alt="CONTROL WEB AGENCY">
                </div>
            </div>
        </div>
    
        <script>
            function togglePassword(fieldId) {
                const passwordInput = document.getElementById(fieldId);
                const eyeIcon = document.getElementById(`eye-icon-${fieldId === 'password_confirmation' ? 'confirmation' : 'password'}`);
    
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    eyeIcon.innerHTML = `
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    `;
                } else {
                    passwordInput.type = 'password';
                    eyeIcon.innerHTML = `
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                    `;
                }
            }

            // MOBILE FIX: Trigger form submission on touchend
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.querySelector('form');
                const submitBtn = document.getElementById('reset-password-btn');

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

</body>
</html>