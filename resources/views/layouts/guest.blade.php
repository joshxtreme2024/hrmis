<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="HRMS - Human Resources Management System">

    <title>{{ config('app.name', 'HRMS') }} - @yield('title', 'Authentication')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Additional Styles -->
    @stack('styles')
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
        <!-- Company Logo/Brand -->
        <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
            <div class="flex justify-center">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-3 rounded-2xl shadow-lg transform hover:scale-105 transition-transform duration-300">
                    <x-application-logo class="h-12 w-auto fill-current text-white" />
                </div>
            </div>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900 dark:text-white">
                @yield('header', 'Welcome Back')
            </h2>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                @yield('subheader', 'Sign in to access your HRMS dashboard')
            </p>
        </div>

        <!-- Main Content Card -->
        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white dark:bg-gray-800 py-8 px-4 shadow-2xl sm:rounded-2xl sm:px-10 border border-gray-100 dark:border-gray-700 relative overflow-hidden">
                <!-- Decorative Elements -->
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500"></div>
                <div class="absolute -top-24 -right-24 w-48 h-48 bg-gradient-to-br from-blue-400 to-indigo-600 rounded-full opacity-5 blur-3xl"></div>
                <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-gradient-to-br from-purple-400 to-pink-600 rounded-full opacity-5 blur-3xl"></div>
                
                <!-- Content Section -->
                @yield('content')
            </div>

            <!-- Footer Links -->
            <!-- <div class="mt-6 text-center space-x-4 text-xs text-gray-500 dark:text-gray-500">
                <a href="#" class="hover:text-gray-700 dark:hover:text-gray-300 transition-colors">Privacy Policy</a>
                <span>•</span>
                <a href="#" class="hover:text-gray-700 dark:hover:text-gray-300 transition-colors">Terms of Service</a>
                <span>•</span>
                <a href="#" class="hover:text-gray-700 dark:hover:text-gray-300 transition-colors">Contact Support</a>
            </div> -->
        </div>
    </div>

    @stack('scripts')
</body>
</html>