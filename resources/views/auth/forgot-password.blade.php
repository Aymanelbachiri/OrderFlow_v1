<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    
        <div class="min-h-screen flex items-center justify-center bg-gradient-to-br bg-black p-4">
            <!-- Forgot Password Card -->
            <div class="w-full max-w-md">
                <!-- Card Container -->
                <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-gray-200 dark:border-gray-700">
                    
                    <!-- Icon -->
                    <div class="flex justify-center mb-6">
                        <div class="w-16 h-16 bg-gradient-to-br from-[#8ACD00] to-[#6FAD00] rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                            </svg>
                        </div>
                    </div>
    
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Forgot Password?</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            No problem. Just let us know your email address and we will email you a password reset link.
                        </p>
                    </div>
    
                    <!-- Session Status -->
                    <x-auth-session-status class="mb-6" :status="session('status')" />
    
                    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                        @csrf
    
                        <!-- Email Input -->
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
                                       value="{{ old('email') }}"
                                       required
                                       autofocus
                                       placeholder="Enter your email address"
                                       class="w-full pl-12 pr-4 py-3.5 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-[#8ACD00] focus:border-[#8ACD00] transition-all duration-200 text-gray-900 dark:text-white placeholder-gray-400" />
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
    
                        <!-- Send Reset Link Button -->
                        <button type="submit" id="forgot-password-btn" data-custom-touch="true"
                                class="w-full bg-gradient-to-r from-gray-900 to-black dark:from-gray-800 dark:to-gray-900 text-white py-3.5 px-4 rounded-xl font-semibold hover:from-black hover:to-gray-900 dark:hover:from-gray-700 dark:hover:to-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#8ACD00] transition-all duration-200 shadow-lg hover:shadow-xl touch-manipulation"
                                style="-webkit-tap-highlight-color: transparent; min-height: 44px;">
                            Email Password Reset Link
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
        // MOBILE FIX: Trigger form submission on touchend
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const submitBtn = document.getElementById('forgot-password-btn');

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