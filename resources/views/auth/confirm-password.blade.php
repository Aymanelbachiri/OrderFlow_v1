<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Password</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    
        <div class="min-h-screen flex items-center justify-center bg-black p-4">
            <!-- Confirm Password Card -->
            <div class="w-full max-w-md">
                <!-- Card Container -->
                <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-gray-200 dark:border-gray-700">
                    
                    <!-- Icon -->
                    <div class="flex justify-center mb-6">
                        <div class="w-16 h-16 bg-gradient-to-br from-[#8ACD00] to-[#6FAD00] rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                    </div>
    
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Confirm Password</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            This is a secure area of the application. Please confirm your password before continuing.
                        </p>
                    </div>
    
                    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
                        @csrf
    
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
                                       placeholder="Enter your password"
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
    
                        <!-- Confirm Button -->
                        <button type="submit"
                                class="w-full bg-gradient-to-r from-gray-900 to-black dark:from-gray-800 dark:to-gray-900 text-white py-3.5 px-4 rounded-xl font-semibold hover:from-black hover:to-gray-900 dark:hover:from-gray-700 dark:hover:to-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#8ACD00] transition-all duration-200 shadow-lg hover:shadow-xl">
                            Confirm
                        </button>
    
                        <!-- Cancel Link -->
                        <div class="text-center pt-2">
                            <a href="{{ url()->previous() }}" 
                               class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-[#8ACD00] dark:hover:text-[#8ACD00] font-medium transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                Cancel
                            </a>
                        </div>
                    </form>
    
                    <!-- Security Note -->
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-start space-x-2">
                            <svg class="w-5 h-5 text-[#8ACD00] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            <p class="text-xs text-gray-500 dark:text-gray-500">
                                Your password confirmation helps keep your account secure. We'll never ask for your password via email.
                            </p>
                        </div>
                    </div>
                </div>
    
                <!-- Logo at Bottom -->
                <div class="flex justify-center mt-8">
                    <img class="w-32 opacity-80" src="{{ asset('images/logo.webp') }}" alt="CONTROL WEB AGENCY">
                </div>
            </div>
        </div>
    
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
        </script>
    
</body>
</html>