<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0a2463">
    <meta name="description" content="@yield('meta_description', config('app.name') . ' - Human Resources Management System')">

    <title>@yield('title', config('app.name'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Bootstrap JS (kept for any page that still uses data-bs-* attributes) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
        :is(a, button, input, select, textarea, summary):focus-visible { outline: 2px solid #2563eb; outline-offset: 2px; }
        @media (prefers-reduced-motion: reduce) { *, ::before, ::after { transition-duration: .01ms !important; animation-duration: .01ms !important; } }

        /* Printing: only the page content, full width, no app chrome */
        @media print {
            #mobile-sidebar, #app-topbar, #app-footer, #mobile-menu-overlay, #app-alerts, #toast-container { display: none !important; }
            #app-main { padding-left: 0 !important; }
            body { background: #fff !important; }
        }
    </style>

    @stack('styles')

    @if(setting('favicon'))
        <link rel="icon" href="{{ asset('storage/' . setting('favicon')) }}">
    @else
        <link rel="icon" href="{{ asset('favicon.ico') }}">
    @endif
</head>
<body class="min-h-full bg-[#f3f6fb] font-sans text-gray-900 antialiased dark:bg-gray-900 dark:text-gray-100">

    <a href="#main-content" class="sr-only z-50 rounded-lg bg-white px-4 py-2 text-sm font-medium text-blue-800 shadow focus:not-sr-only focus:fixed focus:left-4 focus:top-4">Skip to main content</a>

    {{-- Left navigation (fixed; defines toggleMobileMenu()) --}}
    @include('layouts.left-navigation')

    <div id="app-main" class="flex min-h-screen flex-col lg:pl-72">

        {{-- ===== Top bar ===== --}}
        <div id="app-topbar" class="sticky top-0 z-20 border-b border-gray-200 bg-white/90 backdrop-blur dark:border-gray-700 dark:bg-gray-800/90">
            <div class="h-1 bg-gradient-to-r from-[#0a2463] via-blue-600 to-sky-400" aria-hidden="true"></div>
            <div class="flex items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-8">
                <div class="flex min-w-0 flex-1 items-center">
                    <button type="button" onclick="toggleMobileMenu()" aria-controls="mobile-sidebar" aria-label="Open menu"
                            class="-ml-2 mr-3 rounded-lg p-2 text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 lg:hidden">
                        <i class="bi bi-list text-2xl" aria-hidden="true"></i>
                    </button>

                    <nav aria-label="Breadcrumb" class="min-w-0 text-sm">
                        <ol class="flex items-center">
                            <li>
                                <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300" aria-label="Dashboard">
                                    <i class="bi bi-house-door text-lg" aria-hidden="true"></i>
                                </a>
                            </li>
                            @yield('breadcrumbs')
                        </ol>
                    </nav>
                </div>

                <div class="flex items-center gap-2">
                    @yield('quick-actions')
                    <x-notification-dropdown />
                    @auth
                        <x-user-menu-dropdown :user="auth()->user()" />
                    @endauth
                </div>
            </div>
        </div>

        {{-- ===== Page header ===== --}}
        @hasSection('header')
            <header class="border-b border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">
                <div class="flex flex-col gap-4 px-4 py-6 sm:px-6 sm:flex-row sm:items-center sm:justify-between lg:px-8">
                    <div class="min-w-0">
                        <h1 class="text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">@yield('header')</h1>
                        @hasSection('subheader')
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">@yield('subheader')</p>
                        @endif
                    </div>
                    @hasSection('header-actions')
                        <div class="flex flex-wrap items-center gap-3">@yield('header-actions')</div>
                    @endif
                </div>
            </header>
        @endif

        {{-- ===== Flash messages and validation errors ===== --}}
        @php
            $flashStyles = [
                'success' => ['bg-emerald-50 border-emerald-200 text-emerald-800 dark:bg-emerald-900/20 dark:border-emerald-800 dark:text-emerald-300', 'bi-check-circle-fill text-emerald-500'],
                'status'  => ['bg-emerald-50 border-emerald-200 text-emerald-800 dark:bg-emerald-900/20 dark:border-emerald-800 dark:text-emerald-300', 'bi-check-circle-fill text-emerald-500'],
                'error'   => ['bg-red-50 border-red-200 text-red-800 dark:bg-red-900/20 dark:border-red-800 dark:text-red-300', 'bi-exclamation-circle-fill text-red-500'],
                'warning' => ['bg-amber-50 border-amber-200 text-amber-900 dark:bg-amber-900/20 dark:border-amber-800 dark:text-amber-200', 'bi-exclamation-triangle-fill text-amber-500'],
                'info'    => ['bg-blue-50 border-blue-200 text-blue-800 dark:bg-blue-900/20 dark:border-blue-800 dark:text-blue-300', 'bi-info-circle-fill text-blue-500'],
            ];
        @endphp
        <div id="app-alerts" class="space-y-3 px-4 pt-4 sm:px-6 lg:px-8 empty:hidden">
            @foreach($flashStyles as $key => [$box, $icon])
                @if(session($key))
                    <div class="flex items-start gap-3 rounded-lg border p-4 {{ $box }}" role="{{ $key === 'error' ? 'alert' : 'status' }}">
                        <i class="bi {{ $icon }} mt-0.5 text-lg" aria-hidden="true"></i>
                        <p class="flex-1 text-sm font-medium">{{ session($key) }}</p>
                        <button type="button" onclick="this.closest('[role]').remove()" class="rounded p-1 opacity-70 hover:opacity-100" aria-label="Dismiss message"><i class="bi bi-x-lg" aria-hidden="true"></i></button>
                    </div>
                @endif
            @endforeach

            @if($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-red-800 dark:border-red-800 dark:bg-red-900/20 dark:text-red-300" role="alert">
                    <p class="flex items-center gap-2 text-sm font-semibold"><i class="bi bi-exclamation-circle-fill text-red-500" aria-hidden="true"></i> Please fix the following {{ \Illuminate\Support\Str::plural('problem', $errors->count()) }}:</p>
                    <ul class="mt-2 list-inside list-disc text-sm">
                        @foreach($errors->all() as $message)@if($loop->index < 5)<li>{{ $message }}</li>@endif @endforeach
                        @if($errors->count() > 5)<li>and {{ $errors->count() - 5 }} more</li>@endif
                    </ul>
                </div>
            @endif
        </div>

        {{-- ===== Main content ===== --}}
        <main id="main-content" class="flex-1 px-4 py-6 sm:px-6 lg:px-8" tabindex="-1">
            @yield('content')
        </main>

        {{-- ===== Footer ===== --}}
        <footer id="app-footer" class="border-t border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">
            <div class="flex flex-col items-center justify-between gap-2 px-4 py-4 text-sm text-gray-600 dark:text-gray-400 sm:flex-row sm:px-6 lg:px-8">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                <p>HRMIS · Aguinaldo, Ifugao</p>
            </div>
        </footer>
    </div>

    {{-- Mobile menu backdrop --}}
    <div id="mobile-menu-overlay" class="fixed inset-0 z-30 hidden bg-[#06173f]/60 backdrop-blur-sm lg:hidden" onclick="toggleMobileMenu()" aria-hidden="true"></div>

    {{-- Toasts: call showToast('Saved') or showToast('Something failed', 'error') from any page --}}
    <div id="toast-container" class="fixed bottom-4 right-4 z-50 flex max-w-sm flex-col gap-2" aria-live="polite"></div>

    <script>
        window.showToast = function (message, type = 'success') {
            const colors = { success: 'bg-emerald-600', error: 'bg-red-600', info: 'bg-blue-600', warning: 'bg-amber-600' };
            const el = document.createElement('div');
            el.className = 'rounded-lg px-4 py-3 text-sm font-medium text-white shadow-lg ' + (colors[type] || colors.info);
            el.textContent = message;
            document.getElementById('toast-container').appendChild(el);
            setTimeout(() => el.remove(), 4000);
        };
    </script>

    @stack('scripts')
</body>
</html>