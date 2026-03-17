@extends('layouts.guest')

@section('title', 'Register')
@section('header', 'Create Account')
@section('subheader', 'Join our HRMIS platform to get started')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <!-- <div class="text-center">
            <h2 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                Create Account
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                Join our HRMIS platform to get started
            </p>
        </div> -->

        <!-- Error Summary -->
        @if ($errors->any())
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="bi bi-exclamation-circle-fill text-red-500 text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800 dark:text-red-300">
                            {{ __('Please fix the following errors:') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    <i class="bi bi-person-fill text-blue-500 mr-1"></i>
                    {{ __('Full Name') }}
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="bi bi-person text-gray-400 group-focus-within:text-blue-500 transition-colors"></i>
                    </div>
                    <input 
                        id="name" 
                        type="text" 
                        name="name" 
                        value="{{ old('name') }}" 
                        required 
                        autofocus 
                        autocomplete="name"
                        placeholder="Enter your full name"
                        class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all @error('name') border-red-500 dark:border-red-500 @enderror"
                    />
                </div>
                @error('name')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                        <i class="bi bi-exclamation-circle mr-1"></i>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    <i class="bi bi-envelope-fill text-blue-500 mr-1"></i>
                    {{ __('Email Address') }}
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="bi bi-envelope text-gray-400 group-focus-within:text-blue-500 transition-colors"></i>
                    </div>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autocomplete="username"
                        placeholder="you@example.com"
                        class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all @error('email') border-red-500 dark:border-red-500 @enderror"
                    />
                </div>
                @error('email')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                        <i class="bi bi-exclamation-circle mr-1"></i>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Password -->
            <div x-data="{ showPassword: false }">
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    <i class="bi bi-lock-fill text-blue-500 mr-1"></i>
                    {{ __('Password') }}
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="bi bi-lock text-gray-400 group-focus-within:text-blue-500 transition-colors"></i>
                    </div>
                    <input 
                        id="password" 
                        :type="showPassword ? 'text' : 'password'"
                        name="password" 
                        required 
                        autocomplete="new-password"
                        placeholder="••••••••"
                        class="block w-full pl-10 pr-10 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all @error('password') border-red-500 dark:border-red-500 @enderror"
                    />
                    <button type="button" 
                            @click="showPassword = !showPassword" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors focus:outline-none">
                        <i class="bi text-lg" :class="showPassword ? 'bi-eye-slash-fill' : 'bi-eye-fill'"></i>
                    </button>
                </div>
                @error('password')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                        <i class="bi bi-exclamation-circle mr-1"></i>
                        {{ $message }}
                    </p>
                @enderror

                <!-- Password Strength Indicator -->
                <div class="mt-2 space-y-1">
                    <div class="flex items-center space-x-1">
                        <div class="h-1 flex-1 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-red-500 to-green-500" style="width: 0%"></div>
                        </div>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Strength</span>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        <i class="bi bi-info-circle mr-1"></i>
                        Use at least 8 characters with letters and numbers
                    </p>
                </div>
            </div>

            <!-- Confirm Password -->
            <div x-data="{ showConfirmPassword: false }">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    <i class="bi bi-shield-lock-fill text-blue-500 mr-1"></i>
                    {{ __('Confirm Password') }}
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="bi bi-shield-lock text-gray-400 group-focus-within:text-blue-500 transition-colors"></i>
                    </div>
                    <input 
                        id="password_confirmation" 
                        :type="showConfirmPassword ? 'text' : 'password'"
                        name="password_confirmation" 
                        required 
                        autocomplete="new-password"
                        placeholder="••••••••"
                        class="block w-full pl-10 pr-10 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all @error('password_confirmation') border-red-500 dark:border-red-500 @enderror"
                    />
                    <button type="button" 
                            @click="showConfirmPassword = !showConfirmPassword" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors focus:outline-none">
                        <i class="bi text-lg" :class="showConfirmPassword ? 'bi-eye-slash-fill' : 'bi-eye-fill'"></i>
                    </button>
                </div>
                @error('password_confirmation')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                        <i class="bi bi-exclamation-circle mr-1"></i>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Terms and Conditions -->
            <!-- <div class="flex items-start">
                <div class="flex items-center h-5">
                    <input 
                        id="terms" 
                        name="terms" 
                        type="checkbox" 
                        required
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                    >
                </div>
                <label for="terms" class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                    I agree to the 
                    <a href="#" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-medium">Terms of Service</a> 
                    and 
                    <a href="#" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-medium">Privacy Policy</a>
                </label>
            </div> -->

            <!-- Submit Button -->
            <div>
                <button type="submit" 
                        class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-xl shadow-lg text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform hover:scale-[1.02] transition-all duration-200">
                    <i class="bi bi-person-plus mr-2"></i>
                    {{ __('Create Account') }}
                </button>
            </div>

            <!-- Login Link -->
            <div class="text-center">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Already have an account?') }}
                    <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition-colors">
                        {{ __('Sign in') }}
                    </a>
                </p>
            </div>

            <!-- Social Registration (Optional) -->
            <!-- <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400">
                        <i class="bi bi-three-dots"></i>
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <button type="button" 
                        class="flex justify-center items-center py-2.5 px-4 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                    <i class="bi bi-google text-red-500 mr-2"></i>
                    Google
                </button>
                <button type="button" 
                        class="flex justify-center items-center py-2.5 px-4 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                    <i class="bi bi-github text-gray-900 dark:text-white mr-2"></i>
                    GitHub
                </button>
            </div> -->
        </form>
    </div>

    <!-- Password Strength Script (Optional) -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('passwordStrength', () => ({
                password: '',
                strength: 0,
                checkStrength() {
                    let strength = 0;
                    if (this.password.length >= 8) strength += 25;
                    if (this.password.match(/[a-z]+/)) strength += 25;
                    if (this.password.match(/[A-Z]+/)) strength += 25;
                    if (this.password.match(/[0-9]+/)) strength += 25;
                    this.strength = strength;
                }
            }));
        });
    </script>
@endsection