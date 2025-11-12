<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="robots" content="noindex, nofollow, noarchive, nosnippet">

        <title>{{ config('app.name', 'IPTV Smarters Pro') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .dark-gradient-bg {
                background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 50%, #0a0a0a 100%);
                position: relative;
            }

            .dark-gradient-bg::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: linear-gradient(135deg,
                    rgba(214, 54, 19, 0.1) 0%,
                    rgba(249, 115, 22, 0.05) 50%,
                    rgba(214, 54, 19, 0.1) 100%);
                animation: pulseGlow 8s ease-in-out infinite;
            }

            @keyframes pulseGlow {
                0%, 100% { opacity: 0.3; }
                50% { opacity: 0.6; }
            }

            .glass-card {
                background: rgba(255, 255, 255, 0.98);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(214, 54, 19, 0.1);
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            }

            .dark .glass-card {
                background: rgba(16, 18, 17, 0.95);
                border: 1px solid rgba(214, 54, 19, 0.2);
                color: #EFEEEA;
            }

            .floating-elements {
                position: absolute;
                width: 100%;
                height: 100%;
                overflow: hidden;
                pointer-events: none;
            }

            .element {
                position: absolute;
                border-radius: 50%;
                background: linear-gradient(45deg, rgba(214, 54, 19, 0.1), rgba(249, 115, 22, 0.1));
                animation: float 25s infinite linear;
            }

            .element:nth-child(1) {
                width: 100px;
                height: 100px;
                top: 15%;
                left: 15%;
                animation-delay: 0s;
            }

            .element:nth-child(2) {
                width: 150px;
                height: 150px;
                top: 65%;
                left: 75%;
                animation-delay: 8s;
            }

            .element:nth-child(3) {
                width: 80px;
                height: 80px;
                top: 75%;
                left: 25%;
                animation-delay: 16s;
            }

            @keyframes float {
                0% { transform: translateY(0px) rotate(0deg); opacity: 0.3; }
                25% { transform: translateY(-20px) rotate(90deg); opacity: 0.6; }
                50% { transform: translateY(0px) rotate(180deg); opacity: 0.3; }
                75% { transform: translateY(20px) rotate(270deg); opacity: 0.6; }
                100% { transform: translateY(0px) rotate(360deg); opacity: 0.3; }
            }

            .brand-text {
                background: linear-gradient(135deg, #D63613, #F97316);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
        </style>
    </head>
    <body class="font-sans antialiased" style="font-family: 'Inter', sans-serif;">
        <div class="min-h-screen flex">
            <!-- Left Side - Dark Gradient Background with Content -->
            <div class="hidden lg:flex lg:w-1/2 dark-gradient-bg relative overflow-hidden">
                <div class="floating-elements">
                    <div class="element"></div>
                    <div class="element"></div>
                    <div class="element"></div>
                </div>

                <div class="flex flex-col justify-between p-12 relative z-10 text-white">
                    <!-- Top Section -->
                    <div>
                        <div class="text-sm font-medium tracking-wider uppercase opacity-80 mb-8 brand-text">
                            {{ config('app.name', 'IPTV SMARTERS PRO') }}
                        </div>
                    </div>

                    <!-- Center Content -->
                    <div class="max-w-md">
                        <h1 class="text-5xl font-bold leading-tight mb-6">
                            Premium<br>
                            <span class="brand-text">IPTV</span><br>
                            Experience
                        </h1>
                        <p class="text-lg opacity-90 leading-relaxed mb-8">
                            Access 25,000+ premium channels with 4K quality,<br>
                            99.9% uptime, and 24/7 professional support.
                        </p>

                        <!-- Features List -->
                        <div class="space-y-3">
                            <div class="flex items-center text-sm opacity-80">
                                <svg class="w-4 h-4 mr-3 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                4K Ultra HD Quality
                            </div>
                            <div class="flex items-center text-sm opacity-80">
                                <svg class="w-4 h-4 mr-3 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                99.9% Server Uptime
                            </div>
                            <div class="flex items-center text-sm opacity-80">
                                <svg class="w-4 h-4 mr-3 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                24/7 Premium Support
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Section -->
                    <div class="opacity-60">
                        <p class="text-sm">© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                    </div>
                </div>
            </div>

            <!-- Right Side - Form -->
            <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-gray-50 dark:bg-gray-900">
                <div class="w-full max-w-md">
                    <!-- Logo for mobile -->
                    <div class="lg:hidden text-center mb-8">
                        <a href="/" class="inline-block">
                            <div class="w-16 h-16 bg-gradient-to-r from-orange-500 to-red-600 rounded-xl flex items-center justify-center">
                                <span class="text-white font-bold text-xl">IP</span>
                            </div>
                        </a>
                    </div>

                    <!-- Logo for desktop -->
                    <div class="hidden lg:block text-right mb-8">
                        <a href="/" class="inline-flex items-center text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white transition-colors">
                            <div class="w-8 h-8 bg-gradient-to-r from-orange-500 to-red-600 rounded-lg flex items-center justify-center mr-2">
                                <span class="text-white font-bold text-sm">IP</span>
                            </div>
                            <span class="font-semibold">{{ config('app.name') }}</span>
                        </a>
                    </div>

                    <!-- Form Container -->
                    <div class="glass-card rounded-2xl p-8 shadow-xl">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
