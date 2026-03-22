<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'HRMIS') }} - Register</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased bg-gradient-to-br from-blue-50 via-white to-indigo-50 dark:from-gray-900 dark:via-gray-900 dark:to-gray-800 min-h-screen">
    <div class="min-h-screen flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <!-- Brand Header -->
        <div class="text-center mb-8">
            <div class="flex justify-center">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl shadow-lg flex items-center justify-center mb-4 transform hover:scale-105 transition-transform">
                    <i class="bi bi-building text-4xl text-white"></i>
                </div>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                HRMIS Aguinaldo, Ifugao
            </h1>
            <p class="text-gray-600 text-sm dark:text-gray-400 bg-yellow-100 dark:bg-yellow-900/20 border border-yellow-300 dark:border-yellow-700 rounded-lg p-4 inline-block">
                <span class="text-red-500">NOTE:</span> Each employee should create only <strong>one account</strong> to access the Human Resource Management Information System for Aguinaldo, Ifugao.
            </p>
        </div>

        <!-- Main Content -->
        <div class="w-full max-w-2xl">
            <!-- Progress Indicator -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-blue-600 dark:text-blue-400">Step 1 of 2</span>
                    <span class="text-sm text-gray-500 dark:text-gray-400">Basic Information</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 h-2 rounded-full" style="width: 50%"></div>
                </div>
            </div>

            <!-- Error Summary - More user-friendly -->
            @if ($errors->any())
                <div class="mb-6 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-2xl p-4">
                    <div class="flex gap-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-amber-100 dark:bg-amber-800 rounded-full flex items-center justify-center">
                                <i class="bi bi-exclamation-triangle-fill text-amber-600 dark:text-amber-400"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-semibold text-amber-800 dark:text-amber-300 mb-1">
                                {{ __('A few details need your attention:') }}
                            </h4>
                            <ul class="text-sm text-amber-700 dark:text-amber-400 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-8">
                @csrf

                <!-- Name Section - Grouped logically -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <span class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mr-3">
                            <i class="bi bi-person-badge text-blue-600 dark:text-blue-400"></i>
                        </span>
                        Personal Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- First Name -->
                        <div class="md:col-span-2 md:grid md:grid-cols-2 md:gap-4">
                            <div class="mb-5 md:mb-0">
                                <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    First Name <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input 
                                        id="first_name" 
                                        type="text" 
                                        name="first_name" 
                                        value="{{ old('first_name') }}" 
                                        required 
                                        autofocus
                                        placeholder="e.g., Juan"
                                        class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
                                    />
                                </div>
                            </div>

                            <!-- Last Name -->
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Last Name <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input 
                                        id="last_name" 
                                        type="text" 
                                        name="last_name" 
                                        value="{{ old('last_name') }}" 
                                        required
                                        placeholder="e.g., Dela Cruz"
                                        class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Middle Name and Extension - Optional fields with helper text -->
                        <div>
                            <label for="middle_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Middle Name <span class="text-gray-400 text-xs">(optional)</span>
                            </label>
                            <input 
                                id="middle_name" 
                                type="text" 
                                name="middle_name" 
                                value="{{ old('middle_name') }}"
                                placeholder="e.g., Santos"
                                class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
                            />
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 flex items-center">
                                <i class="bi bi-info-circle mr-1"></i>
                                Enter your mother's maiden name if applicable
                            </p>
                        </div>

                        <!-- Name Extension -->
                        <div>
                            <label for="name_extension" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Suffix <span class="text-gray-400 text-xs">(optional)</span>
                            </label>
                            <select 
                                id="name_extension" 
                                name="name_extension"
                                class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
                            >
                                <option value="">Select suffix (if applicable)</option>
                                <option value="Jr." {{ old('name_extension') == 'Jr.' ? 'selected' : '' }}>Jr.</option>
                                <option value="Sr." {{ old('name_extension') == 'Sr.' ? 'selected' : '' }}>Sr.</option>
                                <option value="II" {{ old('name_extension') == 'II' ? 'selected' : '' }}>II</option>
                                <option value="III" {{ old('name_extension') == 'III' ? 'selected' : '' }}>III</option>
                                <option value="IV" {{ old('name_extension') == 'IV' ? 'selected' : '' }}>IV</option>
                                <option value="V" {{ old('name_extension') == 'V' ? 'selected' : '' }}>V</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Account Section -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <span class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900/30 rounded-full flex items-center justify-center mr-3">
                            <i class="bi bi-shield-check text-indigo-600 dark:text-indigo-400"></i>
                        </span>
                        Account Security
                    </h3>

                    <div class="space-y-5">
                        <!-- Email with inline validation hint -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="bi bi-envelope text-gray-400"></i>
                                </div>
                                <input 
                                    id="email" 
                                    type="email" 
                                    name="email" 
                                    value="{{ old('email') }}" 
                                    required
                                    placeholder="you@company.com"
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
                                />
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                We'll send your verification link to this email
                            </p>
                        </div>

                        <!-- Password with strength meter -->
                        <div x-data="{ 
                            showPassword: false,
                            password: '',
                            strength: 0,
                            getStrengthColor() {
                                if (this.strength <= 25) return 'bg-red-500';
                                if (this.strength <= 50) return 'bg-orange-500';
                                if (this.strength <= 75) return 'bg-yellow-500';
                                return 'bg-green-500';
                            },
                            getStrengthText() {
                                if (this.strength <= 25) return 'Weak';
                                if (this.strength <= 50) return 'Fair';
                                if (this.strength <= 75) return 'Good';
                                return 'Strong';
                            },
                            checkStrength() {
                                let strength = 0;
                                if (this.password.length >= 8) strength += 25;
                                if (this.password.match(/[a-z]+/)) strength += 25;
                                if (this.password.match(/[A-Z]+/)) strength += 25;
                                if (this.password.match(/[0-9]+/)) strength += 25;
                                this.strength = strength;
                            }
                        }">
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="bi bi-lock text-gray-400"></i>
                                </div>
                                <input 
                                    id="password" 
                                    :type="showPassword ? 'text' : 'password'"
                                    name="password" 
                                    x-model="password"
                                    @input="checkStrength()"
                                    required
                                    placeholder="Create a strong password"
                                    class="block w-full pl-10 pr-10 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
                                />
                                <button type="button" 
                                        @click="showPassword = !showPassword" 
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i class="bi text-lg" :class="showPassword ? 'bi-eye-slash' : 'bi-eye'"></i>
                                </button>
                            </div>
                            
                            <!-- Password Strength Indicator -->
                            <div class="mt-3 space-y-2">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                        <div class="h-full transition-all duration-300" 
                                             :class="getStrengthColor()"
                                             :style="'width: ' + strength + '%'"></div>
                                    </div>
                                    <span class="text-xs font-medium" 
                                          :class="{
                                              'text-red-500': strength <= 25,
                                              'text-orange-500': strength > 25 && strength <= 50,
                                              'text-yellow-500': strength > 50 && strength <= 75,
                                              'text-green-500': strength > 75
                                          }">
                                        <span x-text="getStrengthText()"></span>
                                    </span>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-2 text-xs">
                                    <div class="flex items-center text-gray-500 dark:text-gray-400">
                                        <i class="bi bi-check-circle-fill mr-1" :class="password.length >= 8 ? 'text-green-500' : 'text-gray-300'"></i>
                                        Min. 8 characters
                                    </div>
                                    <div class="flex items-center text-gray-500 dark:text-gray-400">
                                        <i class="bi bi-check-circle-fill mr-1" :class="password.match(/[a-z]/) ? 'text-green-500' : 'text-gray-300'"></i>
                                        Lowercase letter
                                    </div>
                                    <div class="flex items-center text-gray-500 dark:text-gray-400">
                                        <i class="bi bi-check-circle-fill mr-1" :class="password.match(/[A-Z]/) ? 'text-green-500' : 'text-gray-300'"></i>
                                        Uppercase letter
                                    </div>
                                    <div class="flex items-center text-gray-500 dark:text-gray-400">
                                        <i class="bi bi-check-circle-fill mr-1" :class="password.match(/[0-9]/) ? 'text-green-500' : 'text-gray-300'"></i>
                                        Number
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div x-data="{ showConfirmPassword: false }">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Confirm Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="bi bi-shield-lock text-gray-400"></i>
                                </div>
                                <input 
                                    id="password_confirmation" 
                                    :type="showConfirmPassword ? 'text' : 'password'"
                                    name="password_confirmation" 
                                    required
                                    placeholder="Re-enter your password"
                                    class="block w-full pl-10 pr-10 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
                                />
                                <button type="button" 
                                        @click="showConfirmPassword = !showConfirmPassword" 
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i class="bi text-lg" :class="showConfirmPassword ? 'bi-eye-slash' : 'bi-eye'"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Terms and Submit Section -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full flex justify-center items-center py-4 px-4 border border-transparent rounded-xl shadow-lg text-base font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform hover:scale-[1.01] transition-all duration-200">
                        <i class="bi bi-person-plus-fill mr-2"></i>
                        Create My Account
                    </button>

                    <!-- Login Link -->
                    <p class="mt-4 text-center text-sm text-gray-600 dark:text-gray-400">
                        Already have an account?
                        <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 ml-1">
                            Sign in here
                        </a>
                    </p>
                </div>
            </form>

            <!-- Help Section -->
            <div class="mt-6 text-center">
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Need help? 
                    <a href="#" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 font-medium">Contact Support</a>
                    <span class="mx-2">•</span>
                    <a href="#" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 font-medium">FAQ</a>
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center">
            <p class="text-xs text-gray-500 dark:text-gray-400">
                &copy; {{ date('Y') }} HRMIS Aguinaldo, Ifugao. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>