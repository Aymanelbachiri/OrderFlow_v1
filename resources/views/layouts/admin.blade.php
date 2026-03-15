<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow, noarchive, nosnippet">

    <title>@yield('title', 'Dashboard - IPTV Admin')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">

    <!-- PWA Meta Tags -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#DC4822">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="OrderFlow">
    <link rel="apple-touch-icon" href="{{ asset('images/icons/icon-192x192.svg') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')

    <style>
        /* Mobile-first responsive design */
        @media (max-width: 768px) {

            /* Mobile sidebar overlay - completely hidden by default */
            #sidebar {
                position: fixed;
                top: 0;
                left: -100%;
                height: 100vh;
                width: 100vw !important;
                z-index: 1002;
                transition: left 0.3s ease-in-out;
                transform: translateX(0);
                background: #000000;
                overflow-y: auto;
            }

            #sidebar.mobile-open {
                left: 0;
                transform: translateX(0);
            }

            /* Center content when sidebar is full-screen on mobile */
            #sidebar.mobile-open {
                display: flex;
                flex-direction: column;
                justify-content: flex-start;
                align-items: flex-start;
                padding: 2rem;
            }

            /* Mobile close button */
            .mobile-close-btn-container {
                position: absolute;
                top: 1rem;
                right: 1rem;
                z-index: 1003;
            }

            .mobile-close-btn {
                background: rgba(255, 255, 255, 0.1);
                border: none;
                border-radius: 50%;
                width: 3rem;
                height: 3rem;
                min-width: 44px;
                min-height: 44px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                cursor: pointer;
                transition: all 0.3s ease;
                backdrop-filter: blur(10px);
                touch-action: manipulation;
                -webkit-tap-highlight-color: transparent;
                user-select: none;
            }

            .mobile-close-btn:hover {
                background: rgba(255, 255, 255, 0.2);
                transform: scale(1.1);
            }

            .mobile-close-btn:active {
                transform: scale(0.9);
            }

            /* Show close button only on mobile when sidebar is open */
            #sidebar.mobile-open .mobile-close-btn-container {
                display: block !important;
            }

            /* Mobile overlay */
            #mobile-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 999;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease-in-out;
            }

            #mobile-overlay.show {
                opacity: 1;
                visibility: visible;
            }

            /* Main content takes full width on mobile */
            .main-content {
                margin-left: 0 !important;
                width: 100% !important;
                margin: 0 !important;
            }

            /* Hide sidebar completely when not open */
            #sidebar:not(.mobile-open) {
                left: -100%;
                transform: translateX(-100%);
            }

            /* Mobile header */
            .mobile-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 1rem;
                background: #000000;
                border-bottom: 1px solid #DC4822/20;
                position: relative;
                z-index: 1000;
                height: 60px;
            }

            .mobile-menu-btn {
                display: flex;
                align-items: center;
                justify-content: center;
                background: rgba(255, 255, 255, 0.1);
                border: none;
                color: white;
                font-size: 1.5rem;
                cursor: pointer;
                width: 44px;
                height: 44px;
                border-radius: 8px;
                transition: all 0.3s ease;
                touch-action: manipulation;
                -webkit-tap-highlight-color: transparent;
                user-select: none;
                z-index: 1001;
                position: relative;
            }

            .mobile-menu-btn:hover {
                background: rgba(255, 255, 255, 0.2);
                transform: scale(1.05);
            }

            .mobile-menu-btn:active {
                transform: scale(0.95);
            }

            /* Hide desktop toggle button on mobile */
            #sidebarToggle {
                display: none;
            }

            /* Desktop sidebar toggle button */
            @media (min-width: 769px) {
                #sidebarToggle {
                    display: flex !important;
                }
            }

            /* Collapsed sidebar styles */
            #sidebar.collapsed {
                width: 4rem !important;
            }

            #sidebar.collapsed .sidebar-text {
                display: none !important;
            }

            #sidebar.collapsed .nav-link {
                justify-content: center;
            }

            #sidebar.collapsed .nav-link i {
                margin-right: 0 !important;
            }

            /* Mobile-specific table styles */
            .mobile-table {
                display: block;
                width: 100%;
            }

            .mobile-table thead {
                display: none;
            }

            .mobile-table tbody,
            .mobile-table tr,
            .mobile-table td {
                display: block;
                width: 100%;
            }

            .mobile-table tr {
                border: 1px solid #e5e7eb;
                border-radius: 0.5rem;
                margin-bottom: 1rem;
                padding: 1rem;
                background: white;
            }

            .mobile-table td {
                border: none;
                padding: 0.5rem 0;
                position: relative;
                padding-left: 0;
            }

            .mobile-table td:before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
                font-weight: 600;
                color: #374151;
            }
        }

        /* Desktop collapsed sidebar styles */
        @media (min-width: 769px) {
            #sidebar.collapsed {
                width: 4rem !important;
            }

            #sidebar.collapsed .nav-link {
                justify-content: center;
                padding-left: 1rem;
                padding-right: 1rem;
            }

            #sidebar.collapsed .nav-link i {
                margin-right: 0 !important;
            }

            #sidebar.collapsed .sidebar-text {
                display: none !important;
            }

            /* Tooltip styles for collapsed state */
            #sidebar.collapsed .nav-link {
                position: relative;
            }

            #sidebar.collapsed .nav-link:hover::after {
                content: attr(title);
                position: absolute;
                left: 100%;
                top: 50%;
                transform: translateY(-50%);
                background: rgba(0, 0, 0, 0.9);
                color: white;
                padding: 0.5rem 0.75rem;
                border-radius: 0.375rem;
                font-size: 0.875rem;
                white-space: nowrap;
                z-index: 1000;
                margin-left: 0.5rem;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            }

            .mobile-header {
                display: none;
            }

            .mobile-menu-btn {
                display: none;
            }
        }

        /* Smooth transitions */
        #sidebar {
            transition: width 0.3s ease-in-out, left 0.3s ease-in-out;
        }

        .sidebar-text {
            transition: opacity 0.3s ease-in-out;
        }

        #userInfo {
            transition: opacity 0.3s ease-in-out;
        }

        /* Responsive grid improvements */
        @media (max-width: 640px) {
            .grid-cols-1.md\\:grid-cols-2 {
                grid-template-columns: 1fr;
            }

            .grid-cols-1.md\\:grid-cols-3 {
                grid-template-columns: 1fr;
            }

            .grid-cols-1.md\\:grid-cols-4 {
                grid-template-columns: 1fr;
            }

            .grid-cols-1.md\\:grid-cols-5 {
                grid-template-columns: 1fr;
            }
        }

        /* Mobile form improvements */
        @media (max-width: 640px) {
            .form-input {
                font-size: 16px;
                /* Prevents zoom on iOS */
            }

            .btn-mobile {
                width: 100%;
                padding: 0.75rem;
                font-size: 1rem;
            }
        }

        /* Additional mobile layout fixes */
        @media (max-width: 768px) {

            /* Ensure main content area takes full width on mobile */
            .main-content {
                margin: 0 !important;
                width: 100vw !important;
                max-width: 100vw !important;
                border-radius: 0 !important;
                border: none !important;
                box-shadow: none !important;
            }

            /* Ensure desktop table is completely hidden on mobile */
            .desktop-table-container {
                display: none !important;
                visibility: hidden !important;
                height: 0 !important;
                overflow: hidden !important;
            }

            /* Mobile header positioning */
            .mobile-header {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                z-index: 1001;
                background: #080808;
                border-bottom: 1px solid #DC4822/20;
            }

            /* Adjust main content to account for mobile header */
            .main-content {
                margin-top: 4rem !important;
            }
        }
    </style>
</head>

<body class="font-sans admin-dark-theme">
    <div class="flex h-screen bg-[#080808]">
        {{-- <div class="absolute inset-0 bg-black/20"></div> --}}

        <!-- Mobile Overlay -->
        <div id="mobile-overlay"></div>

        <!-- Mobile Header -->
        <div class="mobile-header">
            <button id="mobileMenuBtn" class="mobile-menu-btn" onclick="toggleAdminMobileMenu()" type="button"
                data-custom-touch="true" aria-label="Toggle navigation menu">
                <i class="fas fa-bars"></i>
            </button>
            <h1 class="text-white font-semibold text-lg">Admin Panel</h1>
            <div></div> <!-- Spacer for centering -->
        </div>

        <!-- Sidebar -->
        <div id="sidebar"
            class="w-64 z-10 flex-shrink-0 shadow-xl transition-all duration-300 ease-in-out overflow-y-auto"
            style="background: #000000;">
            <!-- Toggle Button -->
            <div class="p-4 border-b border-[#DC4822]/80 flex justify-center w-full" ">
                <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="
                0px" y="0px" viewBox="0 0 370.488 365.116" style="enable-background:new 0 0 370.488 365.116"
                xml:space="preserve" class="w-20 h-20 text-white" fill="currentColor">
                <g>
                    <g>
                        <path d="M92.31,185.056l19.509,75.731l-10.039,2.586l-19.508-75.731L92.31,185.056z"></path>
                        <path
                            d="M150.304,219.203l-20.233,5.212l-2.104-8.166l20.233-5.212c3.918-1.01,6.93-2.451,9.035-4.324   c2.105-1.874,3.437-4.065,3.991-6.575c0.556-2.509,0.468-5.186-0.266-8.029c-0.67-2.601-1.862-4.9-3.58-6.898   c-1.716-1.998-3.97-3.387-6.757-4.166c-2.79-0.778-6.144-0.664-10.062,0.346l-17.893,4.609l17.392,67.513l-10.038,2.586   l-19.509-75.731l27.931-7.195c5.722-1.474,10.813-1.732,15.275-0.773c4.462,0.958,8.164,2.916,11.108,5.874   c2.943,2.958,4.991,6.673,6.144,11.146c1.251,4.855,1.295,9.261,0.134,13.222c-1.162,3.959-3.488,7.342-6.981,10.146   C160.632,215.591,156.024,217.729,150.304,219.203z">
                        </path>
                        <path
                            d="M230.04,149.576l2.117,8.218l-58.515,15.074l-2.117-8.218L230.04,149.576z M205.698,155.847l19.508,75.731l-9.882,2.545   l-19.509-75.73L205.698,155.847z">
                        </path>
                        <path
                            d="M245.696,145.543l38.652,58.541l5.718,10.785l-7.75,1.997l-47.491-68.522L245.696,145.543z M283.1,204.407l5.766-69.984   l10.871-2.8l-8.526,82.952l-7.75,1.997L283.1,204.407z">
                        </path>
                    </g>
                    <g>
                        <path
                            d="M118.814,300.229c-0.203-0.788-0.503-1.456-0.899-2.004c-0.396-0.549-0.956-0.993-1.678-1.334   c-0.722-0.342-1.662-0.602-2.82-0.781c-1.158-0.179-2.592-0.296-4.303-0.349c-1.795-0.064-3.443-0.229-4.944-0.494   c-1.501-0.264-2.832-0.666-3.992-1.206c-1.161-0.542-2.128-1.256-2.899-2.145c-0.772-0.888-1.328-1.988-1.666-3.302   c-0.338-1.312-0.381-2.595-0.126-3.847c0.254-1.251,0.78-2.42,1.579-3.507s1.842-2.044,3.128-2.869s2.794-1.461,4.524-1.907   c2.532-0.652,4.809-0.724,6.825-0.214c2.018,0.51,3.689,1.422,5.017,2.735c1.326,1.313,2.218,2.851,2.671,4.611l-4.45,1.146   c-0.326-1.267-0.885-2.321-1.679-3.163c-0.794-0.841-1.811-1.402-3.053-1.684s-2.705-0.205-4.388,0.229   c-1.592,0.41-2.843,0.988-3.754,1.732c-0.912,0.746-1.514,1.593-1.805,2.541s-0.301,1.947-0.03,2.997   c0.183,0.711,0.499,1.316,0.95,1.818c0.449,0.502,1.06,0.917,1.828,1.246c0.768,0.33,1.704,0.574,2.808,0.734   c1.104,0.16,2.4,0.255,3.89,0.283c2.055,0.047,3.863,0.224,5.425,0.529c1.561,0.307,2.899,0.757,4.017,1.35   c1.115,0.594,2.028,1.354,2.737,2.284c0.709,0.929,1.233,2.051,1.571,3.363c0.354,1.375,0.396,2.69,0.127,3.945   c-0.27,1.256-0.821,2.41-1.653,3.465c-0.832,1.054-1.915,1.979-3.249,2.776c-1.336,0.797-2.892,1.424-4.667,1.882   c-1.561,0.402-3.149,0.58-4.767,0.536c-1.617-0.045-3.152-0.341-4.603-0.891c-1.451-0.549-2.707-1.365-3.77-2.45   c-1.062-1.086-1.81-2.47-2.243-4.154l4.448-1.146c0.299,1.158,0.778,2.094,1.438,2.804c0.662,0.712,1.451,1.237,2.371,1.577   c0.919,0.339,1.91,0.508,2.972,0.506c1.062-0.001,2.134-0.141,3.215-0.42c1.56-0.401,2.825-0.958,3.795-1.669   c0.97-0.712,1.633-1.541,1.99-2.489C119.059,302.349,119.096,301.326,118.814,300.229z">
                        </path>
                        <path
                            d="M122.515,272.395l3.823-0.985l5.925,20.414l3.39,13.16l-4.448,1.146L122.515,272.395z M123.928,272.031l4.333-1.116   l18.137,24.654l3.943-30.343l4.356-1.122l-5.026,37.269l-3.383,0.871L123.928,272.031z M152.264,264.731l3.823-0.985l8.69,33.735   l-4.449,1.146l-3.39-13.16L152.264,264.731z">
                        </path>
                        <path
                            d="M177.616,261.387l-3.247,33.623l-4.564,1.176l4.169-37.048l2.943-0.758L177.616,261.387z M191.101,277.383l0.943,3.66   l-18.954,4.883l-0.942-3.661L191.101,277.383z M194.897,289.722l-19.111-27.863l-0.839-2.971l2.941-0.758l21.596,30.41   L194.897,289.722z">
                        </path>
                        <path
                            d="M195.312,253.642l11.169-2.878c2.532-0.652,4.774-0.817,6.727-0.497c1.953,0.321,3.582,1.115,4.89,2.385   c1.306,1.27,2.242,3.001,2.808,5.194c0.397,1.545,0.448,3.034,0.15,4.47c-0.297,1.436-0.915,2.748-1.852,3.937   c-0.938,1.188-2.161,2.208-3.672,3.058l-1.126,0.81l-10.496,2.704l-0.982-3.626l7.923-2.041c1.606-0.414,2.871-1.04,3.791-1.879   s1.527-1.806,1.821-2.903c0.295-1.097,0.292-2.224-0.007-3.383c-0.334-1.297-0.881-2.367-1.642-3.209   c-0.761-0.842-1.753-1.389-2.979-1.642c-1.227-0.252-2.697-0.157-4.411,0.284l-6.696,1.725l7.747,30.074l-4.473,1.152   L195.312,253.642z M223.719,282.297l-12.141-13.18l4.651-1.223l12.19,12.895l0.072,0.278L223.719,282.297z">
                        </path>
                        <path
                            d="M246.009,240.581l0.943,3.661l-26.066,6.715l-0.942-3.661L246.009,240.581z M235.166,243.375l8.689,33.734l-4.403,1.135   l-8.689-33.735L235.166,243.375z">
                        </path>
                        <path
                            d="M255.483,238.141l8.69,33.735l-4.473,1.151l-8.689-33.734L255.483,238.141z M272.212,233.831l0.943,3.661l-17.632,4.542   l-0.942-3.661L272.212,233.831z M273.816,248.885l0.938,3.638l-15.5,3.992l-0.937-3.638L273.816,248.885z M280.196,263.87   l0.938,3.638l-17.863,4.602l-0.937-3.638L280.196,263.87z">
                        </path>
                        <path
                            d="M277.98,232.346l11.169-2.877c2.532-0.652,4.775-0.818,6.727-0.498c1.953,0.321,3.582,1.115,4.89,2.385   c1.306,1.27,2.242,3.001,2.808,5.194c0.397,1.545,0.448,3.034,0.15,4.47c-0.297,1.436-0.914,2.748-1.852,3.937   s-2.161,2.208-3.672,3.058l-1.126,0.81l-10.496,2.704l-0.982-3.626l7.923-2.041c1.607-0.414,2.871-1.04,3.791-1.879   c0.921-0.838,1.527-1.806,1.822-2.902c0.294-1.098,0.291-2.225-0.008-3.384c-0.334-1.297-0.881-2.367-1.641-3.209   c-0.761-0.842-1.754-1.389-2.98-1.642c-1.226-0.251-2.697-0.157-4.411,0.284l-6.696,1.725l7.747,30.074l-4.472,1.152   L277.98,232.346z M306.388,261.001l-12.142-13.179l4.652-1.224l12.19,12.895l0.071,0.278L306.388,261.001z">
                        </path>
                        <path
                            d="M331.694,245.39c-0.203-0.788-0.502-1.456-0.898-2.004c-0.397-0.549-0.956-0.993-1.678-1.335   c-0.723-0.341-1.662-0.601-2.82-0.78s-2.592-0.296-4.302-0.35c-1.796-0.064-3.444-0.229-4.945-0.493   c-1.501-0.264-2.832-0.666-3.992-1.207c-1.161-0.541-2.128-1.256-2.899-2.144c-0.772-0.889-1.328-1.989-1.666-3.302   c-0.338-1.313-0.381-2.595-0.126-3.847c0.254-1.252,0.78-2.421,1.579-3.508s1.842-2.043,3.128-2.868   c1.286-0.826,2.794-1.462,4.523-1.907c2.534-0.652,4.809-0.724,6.826-0.214c2.017,0.51,3.689,1.421,5.017,2.735   c1.326,1.312,2.217,2.851,2.67,4.611l-4.448,1.146c-0.326-1.267-0.886-2.321-1.68-3.163c-0.794-0.841-1.811-1.402-3.054-1.685   c-1.241-0.28-2.704-0.204-4.388,0.229c-1.591,0.409-2.843,0.987-3.753,1.732c-0.912,0.746-1.514,1.593-1.805,2.54   c-0.292,0.948-0.302,1.947-0.031,2.998c0.184,0.71,0.5,1.316,0.951,1.818c0.449,0.501,1.059,0.917,1.827,1.246   s1.705,0.573,2.809,0.734c1.104,0.16,2.4,0.255,3.89,0.282c2.056,0.048,3.863,0.224,5.425,0.53   c1.561,0.306,2.899,0.756,4.016,1.349c1.116,0.594,2.029,1.355,2.737,2.285c0.71,0.929,1.233,2.051,1.572,3.363   c0.354,1.375,0.396,2.689,0.127,3.945c-0.27,1.255-0.822,2.41-1.653,3.464c-0.831,1.055-1.915,1.98-3.25,2.776   c-1.335,0.797-2.891,1.425-4.666,1.882c-1.561,0.402-3.149,0.58-4.767,0.536s-3.152-0.341-4.603-0.89   c-1.451-0.549-2.707-1.366-3.77-2.45c-1.062-1.086-1.811-2.47-2.244-4.154l4.449-1.146c0.298,1.158,0.778,2.094,1.438,2.804   c0.662,0.711,1.451,1.236,2.371,1.576c0.918,0.34,1.91,0.509,2.972,0.507s2.134-0.142,3.215-0.42   c1.56-0.401,2.825-0.959,3.794-1.67c0.97-0.711,1.634-1.541,1.99-2.488C331.94,247.509,331.977,246.487,331.694,245.39z">
                        </path>
                    </g>
                    <g>
                        <path
                            d="M281.942,302.778l-9.014,2.322l-0.937-3.638l9.013-2.322c1.746-0.449,3.087-1.091,4.025-1.926   c0.938-0.835,1.531-1.811,1.778-2.929s0.208-2.31-0.118-3.577c-0.299-1.158-0.83-2.183-1.595-3.072   c-0.765-0.891-1.769-1.509-3.011-1.856c-1.243-0.347-2.736-0.296-4.481,0.154l-7.971,2.053l7.747,30.074l-4.472,1.152   l-8.69-33.735l12.442-3.205c2.549-0.656,4.816-0.771,6.804-0.344c1.988,0.427,3.637,1.299,4.948,2.616s2.225,2.973,2.737,4.965   c0.558,2.163,0.577,4.126,0.059,5.89c-0.518,1.765-1.553,3.271-3.109,4.521C286.543,301.169,284.491,302.121,281.942,302.778z">
                        </path>
                        <path
                            d="M294.153,277.767l11.168-2.877c2.533-0.652,4.775-0.818,6.728-0.498c1.952,0.321,3.582,1.115,4.889,2.385   s2.243,3.001,2.808,5.194c0.398,1.545,0.449,3.034,0.151,4.47c-0.297,1.436-0.915,2.748-1.852,3.937   c-0.938,1.188-2.161,2.208-3.672,3.059l-1.126,0.809l-10.496,2.704l-0.982-3.626l7.923-2.041c1.606-0.414,2.87-1.04,3.791-1.879   c0.921-0.838,1.527-1.806,1.821-2.902c0.295-1.097,0.291-2.225-0.007-3.384c-0.334-1.297-0.881-2.366-1.642-3.209   c-0.761-0.842-1.754-1.389-2.98-1.641c-1.226-0.252-2.696-0.158-4.41,0.283l-6.696,1.726l7.747,30.074l-4.473,1.151   L294.153,277.767z M322.56,306.422l-12.141-13.179l4.651-1.223l12.191,12.894l0.071,0.278L322.56,306.422z">
                        </path>
                        <path
                            d="M353.435,279.346l0.549,2.131c0.653,2.534,0.921,4.886,0.805,7.057c-0.115,2.171-0.588,4.112-1.414,5.824   c-0.827,1.713-1.976,3.153-3.445,4.322s-3.231,2.019-5.286,2.548c-1.992,0.513-3.915,0.612-5.766,0.299   c-1.852-0.313-3.564-1.017-5.139-2.11c-1.576-1.093-2.954-2.558-4.135-4.395c-1.182-1.837-2.098-4.022-2.751-6.556l-0.549-2.132   c-0.652-2.533-0.909-4.884-0.767-7.054c0.141-2.169,0.632-4.116,1.472-5.839c0.841-1.725,1.996-3.171,3.466-4.34   c1.469-1.169,3.2-2.011,5.192-2.523c2.055-0.529,4.008-0.638,5.859-0.323c1.852,0.313,3.558,1.022,5.119,2.128   c1.561,1.104,2.919,2.574,4.075,4.409C351.879,274.628,352.783,276.813,353.435,279.346z M349.559,282.617l-0.561-2.178   c-0.518-2.008-1.181-3.731-1.988-5.171c-0.807-1.438-1.739-2.582-2.797-3.43c-1.06-0.847-2.226-1.395-3.5-1.643   c-1.274-0.249-2.63-0.188-4.066,0.183c-1.391,0.357-2.576,0.951-3.556,1.78c-0.981,0.83-1.733,1.872-2.259,3.127   c-0.525,1.256-0.805,2.711-0.84,4.367c-0.034,1.656,0.207,3.488,0.725,5.496l0.562,2.178c0.521,2.023,1.198,3.755,2.03,5.196   c0.832,1.44,1.79,2.586,2.873,3.436c1.084,0.849,2.255,1.399,3.515,1.651c1.259,0.253,2.583,0.199,3.974-0.159   c1.451-0.374,2.672-0.976,3.66-1.808c0.988-0.831,1.736-1.876,2.243-3.135c0.508-1.259,0.763-2.717,0.765-4.372   C350.341,286.48,350.081,284.641,349.559,282.617z">
                        </path>
                    </g>
                    <path
                        d="M55.505,159.044l269.159-74.589l-157.196,30.595c-0.067-0.491-0.156-0.983-0.283-1.475   c-0.514-1.997-1.489-3.753-2.772-5.22l51.704-89.553c0.682,0.057,1.384,0.008,2.084-0.172c3.358-0.865,5.379-4.289,4.514-7.647   s-4.289-5.379-7.647-4.514c-3.358,0.865-5.379,4.289-4.514,7.647c0.166,0.643,0.426,1.235,0.761,1.769l-51.442,89.099   c-2.815-1.278-6.113-1.64-9.398-0.794c-2.779,0.716-5.152,2.192-6.935,4.131l-81.335-63.12c0.27-1.009,0.295-2.098,0.016-3.182   c-0.865-3.358-4.289-5.379-7.647-4.514s-5.379,4.289-4.514,7.647c0.865,3.358,4.289,5.379,7.647,4.514   c0.263-0.068,0.515-0.157,0.762-0.255l82.194,63.787c-0.754,2.247-0.91,4.696-0.314,7.13L29.544,141.895   c-16.948,3.299-27.146,20.73-21.717,37.12l59.569,179.831L34.513,193.108C31.517,178.009,40.672,163.155,55.505,159.044z">
                    </path>
                </g></svg>
            </div>

            <!-- Mobile Close Button -->
            <div class="mobile-close-btn-container" style="display: none;">
                <button id="mobileCloseBtn" class="mobile-close-btn" onclick="closeAdminMobileMenu()" type="button"
                    data-custom-touch="true" aria-label="Close navigation menu">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>


            <!-- Navigation -->
            <nav class="flex-1 p-4 w-full">
                <ul class="flex flex-col justify-between ">
                    <div class="space-y-2 ">
                        <!-- Dashboard -->
                        <li>
                            <a href="{{ route('admin.dashboard') }}"
                                class="nav-link flex items-center px-4 py-3 text-white hover:bg-[#DC4822]/10 hover:text-[#DC4822] rounded-lg transition-all duration-200 font-medium group {{ request()->routeIs('admin.dashboard') ? 'bg-[#DC4822]/10 text-[#DC4822] border-r-4 border-[#DC4822]' : '' }}"
                                title="Dashboard">
                                <i
                                    class="fas fa-tachometer-alt mr-3 group-hover:scale-110 transition-transform duration-200"></i>
                                <span
                                    class="group-hover:translate-x-1 transition-transform duration-200 sidebar-text">Dashboard</span>
                            </a>
                        </li>

                        <!-- Clients -->
                        <li>
                            <a href="{{ route('admin.clients.index') }}"
                                class="nav-link flex items-center px-4 py-3 text-white hover:bg-[#DC4822]/10 hover:text-[#DC4822] rounded-lg transition-all duration-200 font-medium group {{ request()->routeIs('admin.clients.*') ? 'bg-[#DC4822]/10 text-[#DC4822] border-r-4 border-[#DC4822]' : '' }}"
                                title="Clients">
                                <i
                                    class="fas fa-users mr-3 group-hover:scale-110 transition-transform duration-200"></i>
                                <span
                                    class="group-hover:translate-x-1 transition-transform duration-200 sidebar-text">Clients</span>
                            </a>
                        </li>

                        <!-- Orders -->
                        <li>
                            <a href="{{ route('admin.orders.index') }}"
                                class="nav-link flex items-center px-4 py-3 text-white hover:bg-[#DC4822]/10 hover:text-[#DC4822] rounded-lg transition-all duration-200 font-medium group {{ request()->routeIs('admin.orders.*') ? 'bg-[#DC4822]/10 text-[#DC4822] border-r-4 border-[#DC4822]' : '' }}"
                                title="Orders">
                                <i
                                    class="fas fa-file-alt mr-3 group-hover:scale-110 transition-transform duration-200"></i>
                                <span
                                    class="group-hover:translate-x-1 transition-transform duration-200 sidebar-text">Orders</span>
                            </a>
                        </li>

                        @if(auth()->user()->isAdmin())
                        <!-- Affiliates -->
                        <li>
                            <a href="{{ route('admin.affiliates.index') }}"
                                class="nav-link flex items-center px-4 py-3 text-white hover:bg-[#DC4822]/10 hover:text-[#DC4822] rounded-lg transition-all duration-200 font-medium group {{ request()->routeIs('admin.affiliates.*') ? 'bg-[#DC4822]/10 text-[#DC4822] border-r-4 border-[#DC4822]' : '' }}"
                                title="Affiliates">
                                <i class="fas fa-handshake mr-3 group-hover:scale-110 transition-transform duration-200"></i>
                                <span class="group-hover:translate-x-1 transition-transform duration-200 sidebar-text">Affiliates</span>
                            </a>
                        </li>
                        @endif

                        <!-- Trial Requests -->
                        <li>
                            <a href="{{ route('admin.trial-requests.index') }}"
                                class="nav-link flex items-center px-4 py-3 text-white hover:bg-[#DC4822]/10 hover:text-[#DC4822] rounded-lg transition-all duration-200 font-medium group {{ request()->routeIs('admin.trial-requests.*') ? 'bg-[#DC4822]/10 text-[#DC4822] border-r-4 border-[#DC4822]' : '' }}"
                                title="Trial Requests">
                                <i class="fas fa-clock mr-3 group-hover:scale-110 transition-transform duration-200"></i>
                                <span class="group-hover:translate-x-1 transition-transform duration-200 sidebar-text">Trials</span>
                            </a>
                        </li>

                        <!-- Resellers -->
                        <li>
                            <a href="{{ route('admin.resellers.index') }}"
                                class="nav-link flex items-center px-4 py-3 text-white hover:bg-[#DC4822]/10 hover:text-[#DC4822] rounded-lg transition-all duration-200 font-medium group {{ request()->routeIs('admin.resellers.*') ? 'bg-[#DC4822]/10 text-[#DC4822] border-r-4 border-[#DC4822]' : '' }}"
                                title="Resellers">
                                <i
                                    class="fas fa-user-tie mr-3 group-hover:scale-110 transition-transform duration-200"></i>
                                <span
                                    class="group-hover:translate-x-1 transition-transform duration-200 sidebar-text">Resellers</span>
                            </a>
                        </li>

                        @if(auth()->user()->isAdmin())
                        <!-- Credit Packs -->
                        <li>
                            <a href="{{ route('admin.reseller-credit-packs.index') }}"
                                class="nav-link flex items-center px-4 py-3 text-white hover:bg-[#DC4822]/10 hover:text-[#DC4822] rounded-lg transition-all duration-200 font-medium group {{ request()->routeIs('admin.reseller-credit-packs.*') ? 'bg-[#DC4822]/10 text-[#DC4822] border-r-4 border-[#DC4822]' : '' }}"
                                title="Credit Packs">
                                <i
                                    class="fas fa-dollar-sign mr-3 group-hover:scale-110 transition-transform duration-200"></i>
                                <span
                                    class="group-hover:translate-x-1 transition-transform duration-200 sidebar-text">Credit
                                    Packs</span>
                            </a>
                        </li>

                        <!-- Pricing -->
                        <li>
                            <a href="{{ route('admin.pricing.index') }}"
                                class="nav-link flex items-center px-4 py-3 text-white hover:bg-[#DC4822]/10 hover:text-[#DC4822] rounded-lg transition-all duration-200 font-medium group {{ request()->routeIs('admin.pricing.*') ? 'bg-[#DC4822]/10 text-[#DC4822] border-r-4 border-[#DC4822]' : '' }}"
                                title="Pricing">
                                <i class="fas fa-tags mr-3 group-hover:scale-110 transition-transform duration-200"></i>
                                <span
                                    class="group-hover:translate-x-1 transition-transform duration-200 sidebar-text">Pricing</span>
                            </a>
                        </li>

                        <!-- Custom Products -->
                        <li>
                            <a href="{{ route('admin.custom-products.index') }}"
                                class="nav-link flex items-center px-4 py-3 text-white hover:bg-[#DC4822]/10 hover:text-[#DC4822] rounded-lg transition-all duration-200 font-medium group {{ request()->routeIs('admin.custom-products.*') ? 'bg-[#DC4822]/10 text-[#DC4822] border-r-4 border-[#DC4822]' : '' }}"
                                title="Custom Products">
                                <i
                                    class="fas fa-shopping-bag mr-3 group-hover:scale-110 transition-transform duration-200"></i>
                                <span
                                    class="group-hover:translate-x-1 transition-transform duration-200 sidebar-text">Custom
                                    Products</span>
                            </a>
                        </li>
                        @endif

                        <!-- Analytics -->
                        <li>
                            <a href="{{ route('admin.analytics.index') }}"
                                class="nav-link flex items-center px-4 py-3 text-white hover:bg-[#DC4822]/10 hover:text-[#DC4822] rounded-lg transition-all duration-200 font-medium group {{ request()->routeIs('admin.analytics.*') ? 'bg-[#DC4822]/10 text-[#DC4822] border-r-4 border-[#DC4822]' : '' }}"
                                title="Analytics">
                                <i
                                    class="fas fa-chart-line mr-3 group-hover:scale-110 transition-transform duration-200"></i>
                                <span
                                    class="group-hover:translate-x-1 transition-transform duration-200 sidebar-text">Analytics</span>
                            </a>
                        </li>

                        @if(auth()->user()->isAdmin())
                        <!-- Agents -->
                        <li>
                            <a href="{{ route('admin.agents.index') }}"
                                class="nav-link flex items-center px-4 py-3 text-white hover:bg-[#DC4822]/10 hover:text-[#DC4822] rounded-lg transition-all duration-200 font-medium group {{ request()->routeIs('admin.agents.*') ? 'bg-[#DC4822]/10 text-[#DC4822] border-r-4 border-[#DC4822]' : '' }}"
                                title="Agents">
                                <i class="fas fa-user-shield mr-3 group-hover:scale-110 transition-transform duration-200"></i>
                                <span class="group-hover:translate-x-1 transition-transform duration-200 sidebar-text">Agents</span>
                            </a>
                        </li>

                        <!-- Sources -->
                        <li>
                            <a href="{{ route('admin.sources.index') }}"
                                class="nav-link flex items-center px-4 py-3 text-white hover:bg-[#DC4822]/10 hover:text-[#DC4822] rounded-lg transition-all duration-200 font-medium group {{ request()->routeIs('admin.sources.*') ? 'bg-[#DC4822]/10 text-[#DC4822] border-r-4 border-[#DC4822]' : '' }}"
                                title="Sources">
                                <i
                                    class="fas fa-link mr-3 group-hover:scale-110 transition-transform duration-200"></i>
                                <span
                                    class="group-hover:translate-x-1 transition-transform duration-200 sidebar-text">Sources</span>
                            </a>
                        </li>

                        <!-- WordPress Integration -->
                        <li>
                            <a href="{{ route('admin.wordpress-integration.index') }}"
                                class="nav-link flex items-center px-4 py-3 text-white hover:bg-[#DC4822]/10 hover:text-[#DC4822] rounded-lg transition-all duration-200 font-medium group {{ request()->routeIs('admin.wordpress-integration.*') ? 'bg-[#DC4822]/10 text-[#DC4822] border-r-4 border-[#DC4822]' : '' }}"
                                title="WordPress Integration">
                                <i
                                    class="fab fa-wordpress mr-3 group-hover:scale-110 transition-transform duration-200"></i>
                                <span
                                    class="group-hover:translate-x-1 transition-transform duration-200 sidebar-text">WordPress</span>
                            </a>
                        </li>

                        <!-- Payment Config -->
                        <li>
                            <a href="{{ route('admin.payment.config') }}"
                                class="nav-link flex items-center px-4 py-3 text-white hover:bg-[#DC4822]/10 hover:text-[#DC4822] rounded-lg transition-all duration-200 font-medium group {{ request()->routeIs('admin.payment.config*') ? 'bg-[#DC4822]/10 text-[#DC4822] border-r-4 border-[#DC4822]' : '' }}"
                                title="Payment Config">
                                <i
                                    class="fa-solid fa-credit-card mr-3 group-hover:scale-110 transition-transform duration-200"></i>
                                <span
                                    class="group-hover:translate-x-1 transition-transform duration-200 sidebar-text">Payment
                                    Config</span>
                            </a>
                        </li>

                        <!-- Settings -->
                        <li>
                            <a href="{{ route('admin.settings.index') }}"
                                class="nav-link flex items-center px-4 py-3 text-white hover:bg-[#DC4822]/10 hover:text-[#DC4822] rounded-lg transition-all duration-200 font-medium group {{ request()->routeIs('admin.settings.*') ? 'bg-[#DC4822]/10 text-[#DC4822] border-r-4 border-[#DC4822]' : '' }}"
                                title="Settings">
                                <i class="fas fa-cog mr-3 group-hover:scale-110 transition-transform duration-200"></i>
                                <span
                                    class="group-hover:translate-x-1 transition-transform duration-200 sidebar-text">Settings</span>
                            </a>
                        </li>
                        @endif
                    </div>

                    <!-- Common Links -->
                    <div class="space-y-2">

                        <li class="pt-4 border-t border-[#DC4822]/20 mt-4">
                            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                @csrf
                                <button type="submit" id="logout-btn" data-custom-touch="true"
                                    class="nav-link flex items-center px-4 py-3 text-white hover:bg-red-500/10 hover:text-red-400 rounded-lg transition-all duration-200 font-medium group w-full text-left"
                                    title="Logout">
                                    <i
                                        class="fas fa-sign-out-alt mr-3 group-hover:scale-110 transition-transform duration-200"></i>
                                    <span
                                        class="group-hover:translate-x-1 transition-transform duration-200 sidebar-text">Logout</span>
                                </button>
                            </form>
                        </li>
                    </div>
                </ul>
            </nav>
        </div>

        <!-- Main Content -->
        <div
            class="main-content flex-1 relative z-10 flex flex-col overflow-hidden bg-[#F5F5F5] shadow-2xl m-[10px] border border-[#D63613]/30 rounded-xl md:ml-0">

            <!-- Flash Messages -->
            @if (session('success'))
                <div class="w-max absolute z-20 right-4 bg-green-50 border-l-4 border-green-400 text-green-800 px-6 py-4 mx-6 mt-4 rounded-r-lg shadow-sm"
                    role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-3 text-green-500"></i>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="w-max absolute z-20 right-4 bg-red-50 border-l-4 border-red-400 text-red-800 px-6 py-4 mx-6 mt-4 rounded-r-lg shadow-sm"
                    role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-3 text-red-500"></i>
                        <span class="font-medium">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @if (session('warning'))
                <div class="w-max absolute z-20 right-4 bg-yellow-50 border-l-4 border-yellow-400 text-yellow-800 px-6 py-4 mx-6 mt-4 rounded-r-lg shadow-sm"
                    role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle mr-3 text-yellow-500"></i>
                        <span class="font-medium">{{ session('warning') }}</span>
                    </div>
                </div>
            @endif

            @if (session('info'))
                <div class="w-max absolute z-20 right-4 bg-blue-50 border-l-4 border-blue-400 text-blue-800 px-6 py-4 mx-6 mt-4 rounded-r-lg shadow-sm"
                    role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle mr-3 text-blue-500"></i>
                        <span class="font-medium">{{ session('info') }}</span>
                    </div>
                </div>
            @endif

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-4 md:p-6">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')

    <!-- Global functions for mobile menu as fallback -->
    <script>
        function toggleAdminMobileMenu() {
            const sidebar = document.getElementById('sidebar');
            const mobileOverlay = document.getElementById('mobile-overlay');

            if (!sidebar || !mobileOverlay) return;

            const isOpen = sidebar.classList.contains('mobile-open');

            if (isOpen) {
                closeAdminMobileMenu();
            } else {
                openAdminMobileMenu();
            }
        }

        function openAdminMobileMenu() {
            const sidebar = document.getElementById('sidebar');
            const mobileOverlay = document.getElementById('mobile-overlay');

            if (sidebar && mobileOverlay) {
                sidebar.classList.add('mobile-open');
                mobileOverlay.classList.add('show');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeAdminMobileMenu() {
            const sidebar = document.getElementById('sidebar');
            const mobileOverlay = document.getElementById('mobile-overlay');

            if (sidebar && mobileOverlay) {
                sidebar.classList.remove('mobile-open');
                mobileOverlay.classList.remove('show');
                document.body.style.overflow = '';
            }
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const mobileOverlay = document.getElementById('mobile-overlay');
            const userInfo = document.getElementById('userInfo');
            const sidebarTexts = document.querySelectorAll('.sidebar-text');

            if (!sidebar || !mobileMenuBtn) {
                return;
            }

            function isMobileView() {
                return window.innerWidth <= 768;
            }

            if (sidebarToggle) {
                const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';

                if (isCollapsed && !isMobileView()) {
                    collapseSidebar();
                }

                sidebarToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    if (isMobileView()) {
                        toggleMobileSidebar();
                    } else {
                        if (sidebar.classList.contains('collapsed')) {
                            expandSidebar();
                        } else {
                            collapseSidebar();
                        }
                    }
                });
            }

            // Mobile menu button - touch and click handlers
            let touchHandled = false;
            let menuTouchMoved = false;

            mobileMenuBtn.addEventListener('click', function(e) {
                if (touchHandled) {
                    touchHandled = false;
                    return;
                }
                e.preventDefault();
                e.stopPropagation();
                toggleMobileSidebar();
            }, true);

            mobileMenuBtn.addEventListener('touchstart', function(e) {
                menuTouchMoved = false;
                this.style.transform = 'scale(0.95)';
                this.style.opacity = '0.8';
            }, {
                passive: true
            });

            mobileMenuBtn.addEventListener('touchmove', function(e) {
                menuTouchMoved = true;
                this.style.transform = 'scale(1)';
                this.style.opacity = '1';
            }, {
                passive: true
            });

            mobileMenuBtn.addEventListener('touchend', function(e) {
                this.style.transform = 'scale(1)';
                this.style.opacity = '1';
                if (!menuTouchMoved) {
                    touchHandled = true;
                    setTimeout(function() {
                        touchHandled = false;
                    }, 300);
                    toggleMobileSidebar();
                }
            }, {
                passive: true
            });

            // Mobile close button - touch and click handlers
            const mobileCloseBtn = document.getElementById('mobileCloseBtn');
            if (mobileCloseBtn) {
                let closeTouchHandled = false;
                let closeTouchMoved = false;

                mobileCloseBtn.addEventListener('click', function(e) {
                    if (closeTouchHandled) {
                        closeTouchHandled = false;
                        return;
                    }
                    e.preventDefault();
                    closeMobileSidebar();
                });

                mobileCloseBtn.addEventListener('touchstart', function(e) {
                    closeTouchMoved = false;
                    this.style.transform = 'scale(0.9)';
                    this.style.opacity = '0.8';
                }, {
                    passive: true
                });

                mobileCloseBtn.addEventListener('touchmove', function(e) {
                    closeTouchMoved = true;
                    this.style.transform = 'scale(1)';
                    this.style.opacity = '1';
                }, {
                    passive: true
                });

                mobileCloseBtn.addEventListener('touchend', function(e) {
                    this.style.transform = 'scale(1)';
                    this.style.opacity = '1';
                    if (!closeTouchMoved) {
                        closeTouchHandled = true;
                        setTimeout(function() {
                            closeTouchHandled = false;
                        }, 300);
                        closeMobileSidebar();
                    }
                }, {
                    passive: true
                });
            }

            // Logout button - mobile touch handler
            const logoutBtn = document.getElementById('logout-btn');
            const logoutForm = document.getElementById('logout-form');
            if (logoutBtn && logoutForm) {
                let logoutTouchMoved = false;
                let logoutSubmitting = false;

                logoutBtn.addEventListener('touchstart', function(e) {
                    logoutTouchMoved = false;
                    this.style.opacity = '0.8';
                    this.style.transform = 'scale(0.98)';
                }, {
                    passive: true
                });

                logoutBtn.addEventListener('touchmove', function(e) {
                    logoutTouchMoved = true;
                    this.style.opacity = '1';
                    this.style.transform = 'scale(1)';
                }, {
                    passive: true
                });

                logoutBtn.addEventListener('touchend', function(e) {
                    this.style.opacity = '1';
                    this.style.transform = 'scale(1)';
                    if (!logoutTouchMoved && !logoutSubmitting) {
                        logoutSubmitting = true;
                        logoutForm.submit();
                    }
                }, {
                    passive: true
                });
            }

            // Mobile overlay click handler
            if (mobileOverlay) {
                mobileOverlay.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    closeMobileSidebar();
                });
            }

            // Close sidebar when clicking on nav links (mobile only)
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (isMobileView()) {
                        closeMobileSidebar();
                    }
                });
            });

            // Close sidebar when clicking outside (mobile only)
            document.addEventListener('click', function(event) {
                if (isMobileView() && sidebar.classList.contains('mobile-open')) {
                    // Only close if clicking outside sidebar and its buttons
                    if (!sidebar.contains(event.target) &&
                        !mobileMenuBtn.contains(event.target) &&
                        (!mobileCloseBtn || !mobileCloseBtn.contains(event.target))) {
                        closeMobileSidebar();
                    }
                }
            }, true); // Use capture phase

            // Prevent event bubbling on sidebar and mobile header
            if (sidebar) {
                sidebar.addEventListener('click', function(e) {
                    e.stopPropagation();
                }, true);
            }

            if (document.querySelector('.mobile-header')) {
                document.querySelector('.mobile-header').addEventListener('click', function(e) {
                    e.stopPropagation();
                }, true);
            }

            function toggleMobileSidebar() {
                if (!sidebar || !mobileOverlay) return;

                const isOpen = sidebar.classList.contains('mobile-open');

                if (isOpen) {
                    closeMobileSidebar();
                } else {
                    openMobileSidebar();
                }
            }

            function openMobileSidebar() {
                if (!sidebar || !mobileOverlay) return;

                sidebar.classList.add('mobile-open');
                mobileOverlay.classList.add('show');
                document.body.style.overflow = 'hidden';
            }

            function closeMobileSidebar() {
                if (!sidebar || !mobileOverlay) return;

                sidebar.classList.remove('mobile-open');
                mobileOverlay.classList.remove('show');
                document.body.style.overflow = '';
            }

            function collapseSidebar() {
                if (!sidebar || !sidebarToggle) return;

                sidebar.classList.add('collapsed');
                sidebar.classList.remove('w-64');
                sidebar.classList.add('w-16');

                // Hide user info if it exists
                if (userInfo) {
                    userInfo.style.display = 'none';
                }

                // Hide all text spans
                sidebarTexts.forEach(text => {
                    text.style.display = 'none';
                });

                // Update toggle icon
                sidebarToggle.innerHTML = '<i class="fas fa-chevron-right text-lg"></i>';

                // Store state
                localStorage.setItem('sidebarCollapsed', 'true');
            }

            function expandSidebar() {
                if (!sidebar || !sidebarToggle) return;

                sidebar.classList.remove('collapsed');
                sidebar.classList.remove('w-16');
                sidebar.classList.add('w-64');

                // Show user info if it exists
                if (userInfo) {
                    userInfo.style.display = 'block';
                }

                // Show all text spans
                sidebarTexts.forEach(text => {
                    text.style.display = 'inline';
                });

                // Update toggle icon
                sidebarToggle.innerHTML = '<i class="fas fa-bars text-lg"></i>';

                // Store state
                localStorage.setItem('sidebarCollapsed', 'false');
            }

            // Handle window resize
            let resizeTimeout;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(function() {
                    const newIsMobile = isMobileView();

                    if (newIsMobile) {
                        // Mobile mode: ensure sidebar is hidden and reset desktop state
                        closeMobileSidebar();
                        sidebar.classList.remove('collapsed');
                        sidebar.classList.remove('w-16');
                        sidebar.classList.add('w-64');

                        if (userInfo) userInfo.style.display = 'block';
                        sidebarTexts.forEach(text => {
                            text.style.display = 'inline';
                        });

                        if (sidebarToggle) {
                            sidebarToggle.innerHTML = '<i class="fas fa-bars text-lg"></i>';
                        }
                    } else {
                        // Desktop mode: restore collapsed state if needed
                        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                        if (isCollapsed) {
                            collapseSidebar();
                        }
                    }
                }, 100);
            });

            // Handle escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    if (isMobileView()) {
                        closeMobileSidebar();
                    }
                }
            });
        });

        // Register Service Worker for PWA
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js')
                    .then(function(registration) {
                        console.log('[PWA] Service Worker registered');
                    })
                    .catch(function(error) {
                        console.log('[PWA] Service Worker registration failed:', error);
                    });
            });
        }
    </script>
</body>

</html>
