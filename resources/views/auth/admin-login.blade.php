@extends('layouts.guest')

@section('title', 'Admin Login')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12 bg-gray-50 dark:bg-gray-900">
    <div class="max-w-md w-full bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-indigo-100 dark:bg-indigo-900/30 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i class="bi bi-shield-lock text-3xl text-indigo-600 dark:text-indigo-400"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Admin Access</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                The system is currently in maintenance mode.
                <br>Only administrators can access the system.
            </p>
        </div>

        @if(session('status'))
            <div class="mb-4 p-3 bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-lg text-sm text-green-700 dark:text-green-300">
                {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 p-3 bg-red-100 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg text-sm text-red-700 dark:text-red-300">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Email Address
                </label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors"
                    placeholder="admin@example.com">
            </div>

            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Password
                </label>
                <input type="password" name="password" id="password" required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors"
                    placeholder="••••••••">
            </div>

            <button type="submit" 
                class="w-full flex items-center justify-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-lg shadow-lg shadow-indigo-500/20 transition-all duration-200">
                <i class="bi bi-box-arrow-in-right mr-2"></i>
                Login as Administrator
            </button>
        </form>

        <div class="mt-6 text-center text-sm text-gray-500 dark:text-gray-400">
            <p>
                <i class="bi bi-info-circle mr-1"></i>
                Only administrators can login during maintenance.
            </p>
            <p class="mt-2 text-xs">
                <a href="{{ route('login') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                    Go to regular login
                </a>
            </p>
        </div>
    </div>
</div>
@endsection