<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', config('app.name') . ' - Human Resources Management System')">

    <title>@yield('title', config('app.name'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Additional Styles -->
    @stack('styles')

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
</head>
<body class="font-sans antialiased bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 h-full">
    <div class="h-full flex">
        <!-- Left Panel Navigation -->
        @include('layouts.left-navigation')

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden lg:pl-72">
            <!-- Top Bar -->
            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm border-b border-gray-200/80 dark:border-gray-700/80 sticky top-0 z-40">
                <div class="px-4 sm:px-6 lg:px-8 py-3">
                    <div class="flex items-center justify-between">
                        <!-- Mobile Menu Button & Breadcrumbs -->
                        <div class="flex items-center flex-1">
                            <button type="button" class="lg:hidden -ml-2 mr-3 p-2 rounded-xl text-gray-500 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition-all duration-200" onclick="toggleMobileMenu()">
                                <i class="bi bi-list text-2xl"></i>
                            </button>

                            <!-- Breadcrumbs -->
                            <nav class="flex items-center space-x-2 text-sm" aria-label="Breadcrumb">
                                <ol class="flex items-center space-x-2">
                                    <li>
                                        <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 transition-colors">
                                            <i class="bi bi-house-door text-lg"></i>
                                        </a>
                                    </li>
                                    @yield('breadcrumbs')
                                </ol>
                            </nav>
                        </div>

                        <!-- Right Side Actions -->
                        <div class="flex items-center space-x-2">
                            <!-- Quick Actions -->
                            @yield('quick-actions')

                            <!-- Search Button -->
                            <button class="p-2.5 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition-all duration-200">
                                <i class="bi bi-search text-xl"></i>
                            </button>

                            <!-- Notifications -->
                            <x-notification-dropdown />

                            <!-- User Menu -->
                            @auth
                                <x-user-menu-dropdown />
                            @endauth
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Header -->
            @hasSection('header')
                <header class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm border-b border-gray-200/80 dark:border-gray-700/80">
                    <div class="px-4 sm:px-6 lg:px-8 py-6">
                        <div class="flex items-center justify-between">
                            <div class="space-y-1">
                                <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center">
                                    @yield('header')
                                </h1>
                                @hasSection('subheader')
                                    <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center">
                                        <i class="bi bi-info-circle me-1"></i>
                                        @yield('subheader')
                                    </p>
                                @endif
                            </div>
                            @hasSection('header-actions')
                                <div class="flex items-center space-x-3">
                                    @yield('header-actions')
                                </div>
                            @endif
                        </div>
                    </div>
                </header>
            @endif

            <!-- Alert Messages -->
            <div class="px-4 sm:px-6 lg:px-8 mt-4">
                @if(session('success'))
                    <div class="rounded-xl bg-green-50 dark:bg-green-900/20 p-4 mb-4 border border-green-200 dark:border-green-800 shadow-sm">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="bi bi-check-circle-fill text-green-400 dark:text-green-600 text-lg"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800 dark:text-green-300">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="rounded-xl bg-red-50 dark:bg-red-900/20 p-4 mb-4 border border-red-200 dark:border-red-800 shadow-sm">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="bi bi-exclamation-circle-fill text-red-400 dark:text-red-600 text-lg"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800 dark:text-red-300">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto">
                <div class="px-4 sm:px-6 lg:px-8 py-6">
                    {{ $slot }}
                </div>
            </main>

            <!-- Footer -->
            <footer class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm border-t border-gray-200/80 dark:border-gray-700/80 mt-auto">
                <div class="px-4 sm:px-6 lg:px-8 py-4">
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center space-x-4 text-gray-500 dark:text-gray-400">
                            <i class="bi bi-c-circle"></i>
                            <span>{{ date('Y') }} {{ config('app.name') }}. All rights reserved.</span>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="flex items-center text-gray-500 dark:text-gray-400">
                                <i class="bi bi-tag me-1"></i>
                                Version 1.0.0
                            </span>
                            <span class="w-1 h-1 bg-gray-300 dark:bg-gray-600 rounded-full"></span>
                            <span class="flex items-center text-gray-500 dark:text-gray-400">
                                <i class="bi bi-stars me-1"></i>
                                HRMIS
                            </span>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Mobile Menu Overlay -->
    <div id="mobile-menu-overlay" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-30 hidden lg:hidden transition-opacity duration-300" onclick="toggleMobileMenu()"></div>

    <!-- Scripts -->
    <script>
        function toggleMobileMenu() {
            const sidebar = document.getElementById('mobile-sidebar');
            const overlay = document.getElementById('mobile-menu-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    </script>

    @stack('scripts')

    <!-- Toast Notifications Container -->
    <div id="toast-container" class="fixed bottom-4 right-4 z-50 space-y-2"></div>
</body>
</html>