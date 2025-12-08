<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login admin</title>
    <link 
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    
    
        <div class="min-h-screen flex items-center justify-center bg-black p-4">
            <!-- Login Card -->
            <div class="w-full max-w-md">
                <!-- Card Container -->
                <div class="bg-white/80  backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-gray-200 dark:border-gray-700">
                    
                    <!-- Logo Icon -->
                    <div class="flex justify-center mb-6 ">
                        <!-- Logo at Bottom -->
                <div class="flex justify-center mt-8 ">
                    <img class="w-32 opacity-80" src="{{ asset('images/logo.webp') }}" alt="CONTROL WEB AGENCY">
                </div>
                    </div>
    
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Sign in with email</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Access your Control Web Agency dashboard and manage your projects
                        </p>
                    </div>
    
                    <!-- Session Status -->
                    <x-auth-session-status class="mb-6" :status="session('status')" />
    
                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
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
                                       autocomplete="username"
                                       placeholder="Email"
                                       class="w-full pl-12 pr-4 py-3.5 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-[#8ACD00] focus:border-[#8ACD00] transition-all duration-200 text-gray-900 dark:text-white placeholder-gray-400" />
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
    
                        <!-- Password Input -->
                        <div>
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
                                       autocomplete="current-password"
                                       placeholder="Password"
                                       class="w-full pl-12 pr-12 py-3.5 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-[#8ACD00] focus:border-[#8ACD00] transition-all duration-200 text-gray-900 dark:text-white placeholder-gray-400" />
                                <button type="button"
                                        onclick="togglePassword()"
                                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                                    <svg id="eye-icon" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                                    </svg>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
    
                        <!-- Forgot Password Link -->
                        @if (Route::has('password.request'))
                            <div class="text-right">
                                <a href="{{ route('password.request') }}"
                                   class="text-sm text-gray-600 dark:text-gray-400 hover:text-[#8ACD00] dark:hover:text-[#8ACD00] transition-colors">
                                    Forgot password?
                                </a>
                            </div>
                        @endif
    
                        <!-- Submit Button -->
                        <button type="submit"
                                id="login-submit-btn"
                                class="w-full bg-gradient-to-r from-gray-900 to-black dark:from-gray-800 dark:to-gray-900 text-white py-3.5 px-4 rounded-xl font-semibold hover:from-black hover:to-gray-900 dark:hover:from-gray-700 dark:hover:to-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#8ACD00] transition-all duration-200 shadow-lg hover:shadow-xl active:scale-[0.98] relative z-10 touch-manipulation"
                                style="touch-action: manipulation; -webkit-tap-highlight-color: rgba(0, 0, 0, 0.1); min-height: 44px; cursor: pointer; pointer-events: auto;">
                            <span id="btn-text">Get Started</span>
                            <span id="btn-loading" class="hidden">
                                <svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                        </button>
    
                    </form>
    
                   
    
                    
                </div>
    
                
            </div>
        </div>
    
        <style>
            /* Mobile button fix - ensure button is always clickable */
            button[type="submit"] {
                position: relative;
                z-index: 10;
                pointer-events: auto !important;
                touch-action: manipulation;
                -webkit-tap-highlight-color: rgba(0, 0, 0, 0.1);
                min-height: 44px;
                cursor: pointer;
            }
            
            /* Ensure form doesn't block button clicks */
            form {
                position: relative;
                z-index: 1;
            }
            
            /* Prevent any overlays from blocking the button */
            @media (max-width: 768px) {
                button[type="submit"] {
                    z-index: 999;
                }
            }
        </style>
        
        <script>
            function togglePassword() {
                const passwordInput = document.getElementById('password');
                const eyeIcon = document.getElementById('eye-icon');

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

            // Handle form submission with loading state
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.querySelector('form');
                const submitBtn = document.getElementById('login-submit-btn');
                const btnText = document.getElementById('btn-text');
                const btnLoading = document.getElementById('btn-loading');

                form.addEventListener('submit', function(e) {
                    // Show loading state
                    submitBtn.disabled = true;
                    btnText.classList.add('hidden');
                    btnLoading.classList.remove('hidden');
                    submitBtn.style.opacity = '0.7';
                    submitBtn.style.cursor = 'not-allowed';
                });
            });
        </script>
    
</body>
</html>