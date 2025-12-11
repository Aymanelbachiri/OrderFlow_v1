<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow, noarchive, nosnippet">
    <meta name="color-scheme" content="light dark">
    <meta name="theme-color" content="#ffffff" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#111827" media="(prefers-color-scheme: dark)">


    <title>@yield('title', 'Premium IPTV Service - Smarters Pro IPTV')</title>
    

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />


    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])



    <!-- Custom Styles for Animations -->
    <style>
        @keyframes blob {
            0% {
                transform: translate(0px, 0px) scale(1);
            }

            33% {
                transform: translate(30px, -50px) scale(1.1);
            }

            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }

            100% {
                transform: translate(0px, 0px) scale(1);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-blob {
            animation: blob 7s infinite;
        }

        .animation-delay-2000 {
            animation-delay: 2s;
        }

        .animation-delay-4000 {
            animation-delay: 4s;
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out;
        }

        .animate-fade-in-right {
            animation: fadeInRight 0.8s ease-out;
        }

        .animation-delay-200 {
            animation-delay: 0.2s;
            animation-fill-mode: both;
        }

        .animation-delay-400 {
            animation-delay: 0.4s;
            animation-fill-mode: both;
        }

        .animation-delay-600 {
            animation-delay: 0.6s;
            animation-fill-mode: both;
        }

        .animation-delay-800 {
            animation-delay: 0.8s;
            animation-fill-mode: both;
        }

        .animation-delay-1000 {
            animation-delay: 1s;
            animation-fill-mode: both;
        }

        .animation-delay-1200 {
            animation-delay: 1.2s;
            animation-fill-mode: both;
        }

        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Loading optimization */
        img {
            loading: lazy;
        }

        /* Mobile touch improvements */
        button,
        a,
        input[type="submit"],
        .payment-method-card,
        .subscription-type-card {
            -webkit-tap-highlight-color: transparent;
            touch-action: manipulation;
        }

        /* Ensure inputs are properly sized on mobile */
        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="password"] {
            font-size: 16px;
            /* Prevents zoom on iOS */
            min-height: 44px;
        }

        /* Payment method and subscription type cards - mobile friendly */
        .payment-method-card,
        .subscription-type-card {
            min-height: 100px;
            touch-action: manipulation;
        }

        /* Active state for selected cards */
        .payment-method-radio:checked~div,
        .subscription-type-radio:checked~div {
            border-color: #6366f1;
            background-color: #eef2ff;
        }

        .dark .payment-method-radio:checked~div,
        .dark .subscription-type-radio:checked~div {
            background-color: rgba(99, 102, 241, 0.2);
        }

        /* Hover effect for better visual feedback */
        .payment-method-card:active,
        .subscription-type-card:active {
            transform: scale(0.98);
        }
    </style>

    <!-- Dark Mode Script -->
    <script>
        // Prevent flash of unstyled content in dark mode
        (function() {
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const storedTheme = localStorage.getItem('theme');

            if (storedTheme === 'dark' || (!storedTheme && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    <!-- Iframe Cookie Helper Script -->
    <script>
        (function() {
            // Detect if we're in an iframe
            const isInIframe = window.self !== window.top;
            
            if (isInIframe) {
                // Try to use Storage Access API for Safari (requires user interaction)
                // This helps with Safari's ITP (Intelligent Tracking Prevention)
                if (typeof document.hasStorageAccess === 'function' && typeof document.requestStorageAccess === 'function') {
                    // Check if we already have storage access
                    document.hasStorageAccess().then(function(hasAccess) {
                        if (!hasAccess) {
                            // Request storage access on user interaction
                            const requestStorageAccess = function() {
                                document.requestStorageAccess().then(
                                    function() {
                                        // Storage access granted - cookies should work now
                                        // Reload to get fresh session with cookies
                                        if (!sessionStorage.getItem('storageAccessRequested')) {
                                            sessionStorage.setItem('storageAccessRequested', 'true');
                                            window.location.reload();
                                        }
                                    },
                                    function() {
                                        // Storage access denied - cookies may not work
                                        // This is expected in some Safari configurations
                                    }
                                );
                            };
                            
                            // Request on first user interaction (click, touch, or keypress)
                            ['click', 'touchstart', 'keydown'].forEach(event => {
                                document.addEventListener(event, requestStorageAccess, { once: true, passive: true });
                            });
                        }
                    }).catch(function() {
                        // hasStorageAccess not supported or failed
                    });
                }
                
                // Ensure CSRF token is available in forms
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (csrfToken) {
                    // Update all forms to ensure they have the CSRF token
                    document.querySelectorAll('form').forEach(form => {
                        let csrfInput = form.querySelector('input[name="_token"]');
                        if (!csrfInput) {
                            csrfInput = document.createElement('input');
                            csrfInput.type = 'hidden';
                            csrfInput.name = '_token';
                            form.insertBefore(csrfInput, form.firstChild);
                        }
                        csrfInput.value = csrfToken;
                    });
                    
                    // Update Axios/fetch defaults if available
                    if (window.axios) {
                        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
                    }
                }
                
                // Register CSRF token with server on page load
                // This ensures the token is cached even if cookies are blocked
                // Critical for iOS Safari where cookies are blocked
                // Reuse the csrfToken variable declared above
                if (csrfToken) {
                    // Send token to server to cache it (using a lightweight GET request)
                    // The server will cache the token from the X-CSRF-TOKEN header
                    // For iOS, we need to ensure this happens immediately
                    fetch('{{ route("checkout.show") }}', {
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
                        },
                        credentials: 'include',
                        cache: 'no-cache'
                    }).catch(function(error) {
                        // Ignore errors - this is just to cache the token
                        // Log for debugging but don't show to user
                        console.debug('CSRF token cache request failed (non-critical):', error);
                    });
                }
                
                // Handle form submissions to ensure CSRF token is sent
                document.addEventListener('submit', function(e) {
                    const form = e.target;
                    if (form.tagName === 'FORM') {
                        const csrfInput = form.querySelector('input[name="_token"]');
                        if (!csrfInput || !csrfInput.value) {
                            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                            if (token) {
                                if (!csrfInput) {
                                    const input = document.createElement('input');
                                    input.type = 'hidden';
                                    input.name = '_token';
                                    input.value = token;
                                    form.insertBefore(input, form.firstChild);
                                } else {
                                    csrfInput.value = token;
                                }
                            }
                        }
                    }
                }, true);
            }
        })();
    </script>
</head>

<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    <!-- Dark Mode Toggle -->
    <div class="fixed top-2 right-2 sm:top-4 sm:right-4 z-50">
        <button id="theme-toggle"
            class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-full p-2.5 sm:p-3 md:p-4 shadow-lg hover:shadow-xl active:shadow-md transition-all duration-300 group touch-manipulation"
            style="min-width: 44px; min-height: 44px; width: 44px; height: 44px; display: flex; align-items: center; justify-content: center;"
            aria-label="Toggle dark mode">
            <!-- Sun icon (visible in dark mode) -->
            <svg id="sun-icon" class="w-10 h-10  text-yellow-500 hidden dark:block" fill="currentColor"
                viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                    clip-rule="evenodd"></path>
            </svg>
            <!-- Moon icon (visible in light mode) -->
            <svg id="moon-icon" class="w-10 h-10 text-gray-700 dark:hidden" fill="currentColor"
                viewBox="0 0 20 20">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
            </svg>
        </button>
    </div>

    <main>
        @yield('content')
    </main>
    <!-- Flash Messages -->
    @if (session('success'))
        <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            {{ session('error') }}
        </div>
    @endif

    <!-- Dark Mode Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('theme-toggle');
            const html = document.documentElement;

            // Get current theme from localStorage or system preference
            function getCurrentTheme() {
                const stored = localStorage.getItem('theme');
                if (stored) {
                    return stored;
                }
                return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }

            // Apply theme
            function applyTheme(theme) {
                if (theme === 'dark') {
                    html.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                } else {
                    html.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                }
            }

            // Initialize theme
            const currentTheme = getCurrentTheme();
            applyTheme(currentTheme);

            // Toggle theme function
            function toggleTheme() {
                const isDark = html.classList.contains('dark');
                applyTheme(isDark ? 'light' : 'dark');
            }

            if (themeToggle) {
                let isToggling = false;

                // Single unified click handler for both mobile and desktop
                themeToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (isToggling) return;
                    isToggling = true;
                    toggleTheme();
                    setTimeout(() => { isToggling = false; }, 300);
                });

                // Visual feedback only - touch
                themeToggle.addEventListener('touchstart', function(e) {
                    this.style.transform = 'scale(0.95)';
                }, { passive: true });

                themeToggle.addEventListener('touchend', function(e) {
                    this.style.transform = 'scale(1)';
                    // Don't trigger toggleTheme here - let click handle it
                }, { passive: true });

                // Visual feedback only - mouse
                themeToggle.addEventListener('mousedown', function(e) {
                    this.style.transform = 'scale(0.95)';
                });

                themeToggle.addEventListener('mouseup', function(e) {
                    this.style.transform = 'scale(1)';
                });

                themeToggle.addEventListener('mouseleave', function(e) {
                    this.style.transform = 'scale(1)';
                });
            }

            // Listen for system theme changes
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
                if (!localStorage.getItem('theme')) {
                    applyTheme(e.matches ? 'dark' : 'light');
                }
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
