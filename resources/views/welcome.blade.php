<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="HRMIS - Human Resource Management Information System for Aguinaldo, Ifugao">

    <title>{{ config('app.name', 'HRMIS') }} | Human Resource Management System</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <style>
        /* Custom Styles */
        .gradient-agui {
            background: linear-gradient(135deg, #1a3a8a 0%, #2563eb 50%, #3b82f6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .gradient-text {
            background: linear-gradient(135deg, #2563eb 0%, #3a3ded 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .btn-primary {
            background: linear-gradient(135deg, #2563eb, #3a3ded);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #1d4ed8, #3a3ded);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .btn-primary:hover::before {
            opacity: 1;
        }

        .btn-primary:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 12px 30px -8px rgba(37, 99, 235, 0.5);
        }

        .btn-outline {
            transition: all 0.3s ease;
        }

        .btn-outline:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px -8px rgba(0, 0, 0, 0.15);
        }

        .image-overlay {
            background: linear-gradient(135deg, 
                rgba(37, 99, 235, 0.20) 0%,
                rgba(124, 58, 237, 0.10) 50%,
                rgba(0, 0, 0, 0.30) 100%
            );
        }

        .photo-credit {
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .badge-pulse {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #22c55e;
            animation: pulse-dot 2s ease-in-out infinite;
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.8); }
        }

        /* Ensure full height on both sides */
        .split-container {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        .image-side {
            flex: 0 0 50%;
            position: relative;
            overflow: hidden;
        }

        .image-side img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center; /* ✅ This centers the image */
        }

        .content-side {
            flex: 0 0 50%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem 4rem;
            background: linear-gradient(135deg, #f0f4ff 0%, #faf5ff 100%);
        }

        .dark .content-side {
            background: linear-gradient(135deg, #111827 0%, #1a1a2e 100%);
        }

        @media (max-width: 768px) {
            .split-container {
                flex-direction: column;
                height: auto;
                min-height: 100vh;
            }

            .image-side {
                flex: 0 0 300px;
                width: 100%;
            }

            .image-side img {
                object-position: center; /* ✅ Keeps centering on mobile */
            }

            .content-side {
                flex: 1;
                padding: 2rem 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .image-side {
                flex: 0 0 250px;
            }
            
            .content-side {
                padding: 1.5rem 1rem;
            }
        }
    </style>
</head>
<body class="antialiased">

    <!-- Split Layout - Full Screen -->
    <div class="split-container">
        
        <!-- Left Side: LGU Photo (50%) -->
        <div class="image-side">
            <!-- LGU Photo -->
            <img 
                src="{{ asset('images/lgu.JPG') }}"
                alt="Aguinaldo, Ifugao - Municipal Hall"
                loading="lazy"
            >
            
            <!-- Overlay -->
            <div class="image-overlay absolute inset-0"></div>
            
            <!-- Photo Credit / Badge at bottom -->
            <div class="absolute bottom-6 left-6 right-6 flex items-center justify-between">
                <div class="photo-credit px-4 py-2 rounded-lg text-xs text-white/90">
                    <span class="badge-pulse inline-block mr-2 align-middle"></span>
                    <span class="align-middle font-medium">Municipality of Aguinaldo, Ifugao</span>
                </div>
                <div class="photo-credit px-4 py-2 rounded-lg text-xs text-white/70">
                    <i class="bi bi-geo-alt mr-1"></i>
                    Cordillera Administrative Region
                </div>
            </div>
        </div>

        <!-- Right Side: Content (50%) -->
        <div class="content-side">
            
            <!-- Title -->
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-tight mb-4">
                <span class="gradient-agui">Aguinaldo</span>
                <br>
                <span class="gradient-text">Human Resource</span>
                <br>
                <span class="text-gray-800 dark:text-gray-200">Management System</span>
            </h1>

            <!-- Description -->
            <p class="text-base sm:text-lg text-gray-600 dark:text-gray-400 leading-relaxed mb-8 max-w-lg">
                Streamlining HR operations for the Municipality of Aguinaldo, Ifugao.
                A comprehensive platform for employee management, attendance tracking, and payroll processing.
            </p>

            <!-- Features -->
            <div class="grid grid-cols-2 gap-2 mb-8 max-w-md">
                <div class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                    <i class="bi bi-check-circle-fill text-blue-600 dark:text-blue-400"></i>
                    <span>Employee Records</span>
                </div>
                <div class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                    <i class="bi bi-check-circle-fill text-blue-600 dark:text-blue-400"></i>
                    <span>Attendance Tracking</span>
                </div>
                <div class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                    <i class="bi bi-check-circle-fill text-blue-600 dark:text-blue-400"></i>
                    <span>Leave Management</span>
                </div>
                <div class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                    <i class="bi bi-check-circle-fill text-blue-600 dark:text-blue-400"></i>
                    <span>Payroll Processing</span>
                </div>
            </div>

            <!-- Action Buttons -->
            @if (Route::has('login'))
                <div class="flex flex-col sm:flex-row gap-3">
                    @auth
                        <a href="{{ url('/dashboard') }}" 
                           class="btn-primary inline-flex items-center justify-center px-8 py-3 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/20 relative z-10 text-base">
                            <i class="bi bi-speedometer2 mr-2"></i>
                            <span class="relative z-10">Go to Dashboard</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                           class="btn-primary inline-flex items-center justify-center px-8 py-3 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/20 relative z-10 text-base">
                            <i class="bi bi-box-arrow-in-right mr-2"></i>
                            <span class="relative z-10">Sign In</span>
                        </a>
                        <a href="{{ route('register') }}" 
                           class="btn-outline inline-flex items-center justify-center px-8 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 hover:border-blue-500 dark:hover:border-blue-500 text-gray-700 dark:text-gray-300 font-semibold rounded-xl shadow-md text-base">
                            <i class="bi bi-person-plus mr-2"></i>
                            Create Account
                        </a>
                    @endauth
                </div>
            @endif

            <!-- Version / Security -->
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-8 flex items-center gap-2">
                <i class="bi bi-shield-check"></i>
                <span>Secured by LGU Aguinaldo</span>
                <span class="w-px h-3 bg-gray-300 dark:bg-gray-600"></span>
                <span>v1.0</span>
            </p>
        </div>
    </div>

</body>
</html>