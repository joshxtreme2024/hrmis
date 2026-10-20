<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="HRMIS - Human Resource Management Information System for Aguinaldo, Ifugao">
    <title>{{ config('app.name', 'HRMIS') }} | Human Resource Management Information System</title>

    @if(setting('favicon'))
        <link rel="icon" href="{{ asset('storage/' . setting('favicon')) }}">
    @else
        <link rel="icon" href="{{ asset('favicon.ico') }}">
    @endif

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=fraunces:600,700|figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: { extend: { fontFamily: {
                sans: ['Figtree', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                display: ['Fraunces', 'Georgia', 'serif'],
            }}}
        }
    </script>

    <style>
        :root {
            --terrace: #0f2a6b;
            --paddy: #2563eb;
            --rice: #f1f5fd;
            --ink: #14203a;
            --weave: #1d4ed8;
            --ochre: #7cc4ff;
        }

        /* Ifugao-textile inspired band, in blue: the one decorative moment on the page */
        .weave-band {
            height: 10px;
            background:
                repeating-linear-gradient(90deg,
                    var(--weave) 0 24px, var(--ochre) 24px 32px,
                    var(--terrace) 32px 56px, var(--ochre) 56px 64px);
        }

        .hero-photo::after {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(180deg, rgba(15,42,107,.55) 0%, rgba(15,42,107,.82) 60%, rgba(8,25,70,.95) 100%);
        }

        .field:focus-visible, .btn:focus-visible, a:focus-visible {
            outline: 3px solid #3b82f6;
            outline-offset: 2px;
        }

        .btn { transition: background-color .2s ease, transform .2s ease; }
        .btn:active { transform: translateY(1px); }

        @keyframes rise { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: none; } }
        .rise { animation: rise .6s ease-out both; }

        @media (prefers-reduced-motion: reduce) {
            .rise { animation: none; }
            .btn { transition: none; }
        }
    </style>
</head>
<body class="font-sans antialiased bg-[color:var(--rice)] text-[color:var(--ink)] dark:bg-gray-950 dark:text-gray-100">

<div class="min-h-screen lg:grid lg:grid-cols-[1.15fr_1fr]">

    {{-- ===== Information panel ===== --}}
    <section class="hero-photo relative isolate overflow-hidden text-white flex flex-col">
        <img src="{{ asset('images/lgu.JPG') }}" alt="Municipal Hall of Aguinaldo, Ifugao"
             class="absolute inset-0 -z-10 h-full w-full object-cover object-center">

        <div class="relative z-10 flex flex-1 flex-col justify-between gap-12 px-6 py-8 sm:px-10 lg:px-14 lg:py-12">

            <div class="flex items-center gap-3">
                @if(setting('logo'))
                    <img src="{{ asset('storage/' . setting('logo')) }}" alt="Logo" class="h-14 w-auto">
                @else
                    <img src="{{ asset('images/default-logo.png') }}" alt="Logo" class="h-14 w-auto">
                @endif
                <div class="leading-tight">
                    <p class="font-semibold">Municipality of Aguinaldo</p>
                    <p class="text-sm text-white/70">Province of Ifugao</p>
                </div>
            </div>

            <div class="max-w-xl rise">
                <h1 class="font-display text-4xl sm:text-5xl font-bold leading-[1.1]">
                    Human Resource Management Information System
                </h1>
                <p class="mt-5 text-lg text-white/85 leading-relaxed max-w-[60ch]">
                    One place for the LGU's employee records, daily attendance, leave requests, and payroll.
                    Sign in with your account to see your own records or manage your office's.
                </p>

                <ul class="mt-8 grid gap-x-8 gap-y-5 sm:grid-cols-2">
                    <li class="flex gap-3">
                        <i class="bi bi-person-vcard text-xl text-[color:var(--ochre)]"></i>
                        <div>
                            <p class="font-semibold">Employee records</p>
                            <p class="text-sm text-white/75">Personal data, service records, and 201 files.</p>
                        </div>
                    </li>
                    <li class="flex gap-3">
                        <i class="bi bi-clock-history text-xl text-[color:var(--ochre)]"></i>
                        <div>
                            <p class="font-semibold">Attendance</p>
                            <p class="text-sm text-white/75">Daily time logs, tardiness, and undertime.</p>
                        </div>
                    </li>
                    <li class="flex gap-3">
                        <i class="bi bi-calendar2-check text-xl text-[color:var(--ochre)]"></i>
                        <div>
                            <p class="font-semibold">Leave management</p>
                            <p class="text-sm text-white/75">File requests, track balances, and get approvals.</p>
                        </div>
                    </li>
                    <li class="flex gap-3">
                        <i class="bi bi-cash-stack text-xl text-[color:var(--ochre)]"></i>
                        <div>
                            <p class="font-semibold">Payroll</p>
                            <p class="text-sm text-white/75">Salary computation, deductions, and payslips.</p>
                        </div>
                    </li>
                </ul>
            </div>

            <p class="text-sm text-white/70">
                Latest LGU news is on the
                <a href="{{ setting('company_website') }}" target="_blank" rel="noopener"
                   class="font-medium text-white underline underline-offset-4 hover:text-[color:var(--ochre)]">official municipal website</a>.
            </p>
        </div>
        <div class="weave-band relative z-10"></div>
    </section>

    {{-- ===== Login panel ===== --}}
    <main class="flex items-center justify-center px-6 py-12 sm:px-10">
        <div class="w-full max-w-sm">
            <h2 class="font-display text-3xl font-bold text-[color:var(--terrace)] dark:text-white">Sign in</h2>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Use the email address registered with HR.</p>

            @if (session('status'))
                <div class="mt-6 rounded-lg border border-green-300 bg-green-50 px-4 py-3 text-sm text-green-800" role="status">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mt-6 rounded-lg border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium mb-1.5">Email address</label>
                    <div class="relative">
                        <i class="bi bi-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input id="email" name="email" type="email" value="{{ old('email') }}"
                               required autofocus autocomplete="username"
                               class="field w-full rounded-lg border border-gray-300 bg-white py-2.5 pl-10 pr-3 text-base
                                      focus:border-[color:var(--paddy)] dark:border-gray-700 dark:bg-gray-900
                                      @error('email') border-red-500 @enderror">
                    </div>
                </div>

                <div>
                    <div class="flex items-baseline justify-between mb-1.5">
                        <label for="password" class="block text-sm font-medium">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                               class="text-sm text-[color:var(--paddy)] hover:underline dark:text-blue-400">Forgot password?</a>
                        @endif
                    </div>
                    <div class="relative">
                        <i class="bi bi-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input id="password" name="password" type="password" required autocomplete="current-password"
                               class="field w-full rounded-lg border border-gray-300 bg-white py-2.5 pl-10 pr-11 text-base
                                      focus:border-[color:var(--paddy)] dark:border-gray-700 dark:bg-gray-900
                                      @error('password') border-red-500 @enderror">
                        <button type="button" id="togglePassword" aria-label="Show password"
                                class="absolute right-2 top-1/2 -translate-y-1/2 rounded p-1.5 text-gray-500 hover:text-gray-800 dark:hover:text-gray-200">
                            <i class="bi bi-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>

                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="remember" class="h-4 w-4 rounded border-gray-300 accent-[color:var(--paddy)]"
                           {{ old('remember') ? 'checked' : '' }}>
                    Keep me signed in on this device
                </label>

                <button type="submit"
                        class="btn w-full rounded-lg bg-[color:var(--terrace)] px-4 py-3 font-semibold text-white hover:bg-[color:var(--paddy)]">
                    Sign in
                </button>
            </form>

            @if (Route::has('register'))
                <p class="mt-6 text-sm text-gray-600 dark:text-gray-400">
                    New employee?
                    <a href="{{ route('register') }}" class="font-semibold text-[color:var(--weave)] hover:underline">Create an account</a>
                </p>
            @endif

            <p class="mt-10 flex items-center gap-2 text-xs text-gray-500 dark:text-gray-500">
                <i class="bi bi-shield-check"></i>
                Authorized LGU Aguinaldo personnel only. Activity on this system is logged.
            </p>
        </div>
    </main>
</div>

<script>
    (function () {
        var btn = document.getElementById('togglePassword');
        var input = document.getElementById('password');
        var icon = document.getElementById('toggleIcon');
        btn.addEventListener('click', function () {
            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            icon.className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
            btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
        });
    })();
</script>
</body>
</html>