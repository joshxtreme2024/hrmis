@extends('layouts.app')

@section('title', 'System Settings')
@section('breadcrumbs')
    <li class="flex items-center">
        <i class="bi bi-chevron-right text-gray-300 dark:text-gray-600 mx-2 text-xs"></i>
        <span class="text-gray-800 dark:text-gray-200 font-medium">Settings</span>
    </li>
    <li class="flex items-center">
        <i class="bi bi-chevron-right text-gray-300 dark:text-gray-600 mx-2 text-xs"></i>
        <span class="text-gray-800 dark:text-gray-200 font-medium">System</span>
    </li>
@endsection
@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-6 flex items-center space-x-2 text-sm text-gray-600">
            <a href="{{ route('dashboard') }}" class="hover:text-indigo-600 transition-colors duration-200">Dashboard</a>
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
            <span class="text-gray-800 font-medium">System Settings</span>
        </nav>

        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-8 border px-6 py-6 bg-gradient-to-r from-[#2dd4bf] to-[#1f2937] rounded-lg">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center">
                        <i class="bi bi-gear-fill text-3xl text-indigo-600"></i>
                    </div>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-white">System Settings</h1>
                    <p class="text-sm text-gray-100 mt-1">
                        <span class="inline-flex items-center gap-1">
                            <i class="bi bi-sliders2"></i>
                            Manage your application settings and settingurations
                        </span>
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" form="settingsForm" class="inline-flex items-center px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-lg transition-all duration-200 border border-white/20">
                    <i class="bi bi-check2-circle mr-2"></i>
                    Save Changes
                </button>
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-lg transition-all duration-200 border border-white/20">
                    <i class="bi bi-arrow-left mr-2"></i>
                    Back
                </a>
            </div>
        </div>

        <!-- Settings Tabs -->
        <div class="border-b border-gray-200 dark:border-gray-700 mb-6">
            <nav class="flex space-x-8 overflow-x-auto" aria-label="Settings Tabs">
                <button type="button" class="tab-btn tab-active py-2 px-1 text-sm font-medium border-b-2 transition-colors" data-tab="general">
                    <i class="bi bi-sliders2 mr-2"></i>
                    General
                </button>
                <button type="button" class="tab-btn tab-inactive py-2 px-1 text-sm font-medium border-b-2 transition-colors" data-tab="company">
                    <i class="bi bi-building mr-2"></i>
                    Company
                </button>
                <button type="button" class="tab-btn tab-inactive py-2 px-1 text-sm font-medium border-b-2 transition-colors" data-tab="hr">
                    <i class="bi bi-people mr-2"></i>
                    HR Settings
                </button>
                <button type="button" class="tab-btn tab-inactive py-2 px-1 text-sm font-medium border-b-2 transition-colors" data-tab="payroll">
                    <i class="bi bi-cash-stack mr-2"></i>
                    Payroll
                </button>
                <button type="button" class="tab-btn tab-inactive py-2 px-1 text-sm font-medium border-b-2 transition-colors" data-tab="security">
                    <i class="bi bi-shield-lock mr-2"></i>
                    Security
                </button>
                <button type="button" class="tab-btn tab-inactive py-2 px-1 text-sm font-medium border-b-2 transition-colors" data-tab="email">
                    <i class="bi bi-envelope mr-2"></i>
                    Email
                </button>
            </nav>
        </div>

        <!-- Settings Form -->
        <form id="settingsForm" action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Tab: General -->
            <div id="tab-general" class="tab-content">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Left Column -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Application Settings -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    <i class="bi bi-globe mr-2 text-indigo-600"></i>
                                    Application Settings
                                </h3>
                            </div>
                            <div class="p-6 space-y-4">
                                <div>
                                    <label for="app_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Application Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="app_name" id="app_name" 
                                        value="{{ old('app_name', setting('app.name', 'HRMIS')) }}"
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors">
                                    @error('app_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="app_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Application URL
                                    </label>
                                    <input type="url" name="app_url" id="app_url" 
                                        value="{{ old('app_url', setting('app.url', 'http://localhost')) }}"
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors">
                                    @error('app_url')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="app_locale" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Default Language
                                    </label>
                                    <select name="app_locale" id="app_locale" 
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors">
                                        <option value="en" {{ setting('app_locale', config('app.locale')) == 'en' ? 'selected' : '' }}>English</option>
                                        <option value="fil" {{ setting('app_locale', config('app.locale')) == 'fil' ? 'selected' : '' }}>Filipino</option>
                                        <option value="es" {{ setting('app_locale', config('app.locale')) == 'es' ? 'selected' : '' }}>Spanish</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="timezone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Timezone
                                    </label>
                                    <select name="timezone" id="timezone" 
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors">
                                        <option value="Asia/Manila" {{ setting('timezone', config('app.timezone')) == 'Asia/Manila' ? 'selected' : '' }}>Asia/Manila (UTC+8)</option>
                                        <option value="UTC" {{ setting('timezone', config('app.timezone')) == 'UTC' ? 'selected' : '' }}>UTC</option>
                                        <option value="America/New_York" {{ setting('timezone', config('app.timezone')) == 'America/New_York' ? 'selected' : '' }}>America/New_York</option>
                                        <option value="Europe/London" {{ setting('timezone', config('app.timezone')) == 'Europe/London' ? 'selected' : '' }}>Europe/London</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Logo & Branding -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    <i class="bi bi-image mr-2 text-indigo-600"></i>
                                    Logo & Branding
                                </h3>
                            </div>
                            <div class="p-6 space-y-4">
                                <div class="flex items-center space-x-6">
                                    <div class="flex-shrink-0">
                                        <div class="w-24 h-24 rounded-lg border-2 border-gray-200 dark:border-gray-600 overflow-hidden flex items-center justify-center bg-gray-50 dark:bg-gray-700">
                                            @if(setting('logo'))
                                                <img src="{{ asset('storage/' . setting('logo')) }}" alt="Logo" class="h-12 w-auto">
                                            @else
                                                <img src="{{ asset('images/default-logo.png') }}" alt="Default Logo" class="h-12 w-auto">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <label for="logo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Upload Logo
                                        </label>
                                        <input type="file" name="logo" id="logo" accept="image/*"
                                            class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900/30 dark:file:text-indigo-400">
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Recommended: 200x200px, PNG or SVG</p>
                                    </div>
                                </div>

                                <div>
                                    <div>
                                        @if(setting('favicon'))
                                            <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . setting('favicon')) }}">
                                        @else
                                            <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
                                        @endif
                                    </div>
                                    <label for="favicon" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Favicon
                                    </label>
                                    <input type="file" name="favicon" id="favicon" accept="image/*"
                                        class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900/30 dark:file:text-indigo-400">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Maintenance Mode -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    <i class="bi bi-tools mr-2 text-indigo-600"></i>
                                    Maintenance
                                </h3>
                            </div>
                            <div class="p-6 space-y-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Maintenance Mode</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Make the site temporarily unavailable</p>
                                    </div>
                                    <div class="toggle-switch {{ setting('maintenance_mode', false) ? 'active' : '' }}" 
                                         onclick="toggleSwitch(this)">
                                        <div class="toggle-knob"></div>
                                    </div>
                                    <input type="hidden" name="maintenance_mode" value="{{ setting('maintenance_mode', false) ? '1' : '0' }}">
                                </div>

                                <div>
                                    <label for="maintenance_message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Maintenance Message
                                    </label>
                                    <textarea name="maintenance_message" id="maintenance_message" rows="3"
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors"
                                        placeholder="We're currently undergoing maintenance. Please check back soon.">{{ setting('maintenance_message', '') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- System Status -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    <i class="bi bi-info-circle mr-2 text-indigo-600"></i>
                                    System Status
                                </h3>
                            </div>
                            <div class="p-6 space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">PHP Version</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ phpversion() }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Laravel Version</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ app()->version() }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Environment</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ app()->environment() }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Debug Mode</span>
                                    <span class="text-sm font-medium">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ setting('debug') ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' : 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' }}">
                                            {{ setting('debug') ? 'Enabled' : 'Disabled' }}
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab: Company -->
            <div id="tab-company" class="tab-content hidden">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            <i class="bi bi-building mr-2 text-indigo-600"></i>
                            Company Information
                        </h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="company_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Company Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="company_name" id="company_name" 
                                value="{{ old('company_name', setting('company_name', 'Municipality of Aguinaldo')) }}"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors">
                            @error('company_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="company_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Company Address
                            </label>
                            <input type="text" name="company_address" id="company_address" 
                                value="{{ old('company_address', setting('company_address', 'Aguinaldo, Ifugao, Philippines')) }}"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors">
                        </div>

                        <div>
                            <label for="company_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Phone Number
                            </label>
                            <input type="text" name="company_phone" id="company_phone" 
                                value="{{ old('company_phone', setting('company_phone')) }}"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors">
                        </div>

                        <div>
                            <label for="company_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Company Email
                            </label>
                            <input type="email" name="company_email" id="company_email" 
                                value="{{ old('company_email', setting('company_email')) }}"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors">
                        </div>

                        <div>
                            <label for="company_website" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Website
                            </label>
                            <input type="url" name="company_website" id="company_website" 
                                value="{{ old('company_website', setting('company_website')) }}"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors">
                        </div>

                        <div>
                            <label for="company_tin" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                TIN Number
                            </label>
                            <input type="text" name="company_tin" id="company_tin" 
                                value="{{ old('company_tin', setting('company_tin')) }}"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors">
                        </div>

                        <div class="md:col-span-2">
                            <label for="company_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Company Description
                            </label>
                            <textarea name="company_description" id="company_description" rows="3"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors">{{ old('company_description', setting('company_description')) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab: HR Settings -->
            <div id="tab-hr" class="tab-content hidden">
                <div class="space-y-6">
                    <!-- Employee Settings -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                <i class="bi bi-person-badge mr-2 text-indigo-600"></i>
                                Employee Settings
                            </h3>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="employee_id_prefix" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Employee ID Prefix
                                </label>
                                <input type="text" name="employee_id_prefix" id="employee_id_prefix" 
                                    value="{{ old('employee_id_prefix', setting('employee_id_prefix', 'EMP-')) }}"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors">
                            </div>

                            <div>
                                <label for="employee_id_format" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    ID Format
                                </label>
                                <select name="employee_id_format" id="employee_id_format" 
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors">
                                    <option value="YYYY-XXXX" {{ setting('employee_id_format') == 'YYYY-XXXX' ? 'selected' : '' }}>YYYY-XXXX (e.g., 2024-0001)</option>
                                    <option value="EMP-XXXX" {{ setting('employee_id_format') == 'EMP-XXXX' ? 'selected' : '' }}>EMP-XXXX (e.g., EMP-0001)</option>
                                    <option value="XX-XXXX" {{ setting('employee_id_format') == 'XX-XXXX' ? 'selected' : '' }}>XX-XXXX (e.g., HR-0001)</option>
                                </select>
                            </div>

                            <div>
                                <label for="default_employee_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Default Employee Status
                                </label>
                                <select name="default_employee_status" id="default_employee_status" 
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors">
                                    <option value="active" {{ setting('default_employee_status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="probationary" {{ setting('default_employee_status') == 'probationary' ? 'selected' : '' }}>Probationary</option>
                                    <option value="contractual" {{ setting('default_employee_status') == 'contractual' ? 'selected' : '' }}>Contractual</option>
                                </select>
                            </div>

                            <div>
                                <label for="probationary_period" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Probationary Period (months)
                                </label>
                                <input type="number" name="probationary_period" id="probationary_period" 
                                    value="{{ old('probationary_period', setting('probationary_period', 6)) }}"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors">
                            </div>
                        </div>
                    </div>

                    <!-- Leave Settings -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                <i class="bi bi-calendar-check mr-2 text-indigo-600"></i>
                                Leave Settings
                            </h3>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="annual_leave_days" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Annual Leave Days
                                </label>
                                <input type="number" name="annual_leave_days" id="annual_leave_days" 
                                    value="{{ old('annual_leave_days', setting('annual_leave_days', 15)) }}"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors">
                            </div>

                            <div>
                                <label for="sick_leave_days" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Sick Leave Days
                                </label>
                                <input type="number" name="sick_leave_days" id="sick_leave_days" 
                                    value="{{ old('sick_leave_days', setting('sick_leave_days', 10)) }}"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors">
                            </div>

                            <div>
                                <label for="max_leave_days" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Maximum Leave Days Per Request
                                </label>
                                <input type="number" name="max_leave_days" id="max_leave_days" 
                                    value="{{ old('max_leave_days', setting('max_leave_days', 30)) }}"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors">
                            </div>

                            <div>
                                <label for="leave_approval_required" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Leave Approval Required
                                </label>
                                <select name="leave_approval_required" id="leave_approval_required" 
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors">
                                    <option value="1" {{ setting('leave_approval_required', true) ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ !setting('leave_approval_required', true) ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab: Payroll -->
            <div id="tab-payroll" class="tab-content hidden">
                <div class="space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                <i class="bi bi-cash-coin mr-2 text-indigo-600"></i>
                                Payroll Settings
                            </h3>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="payroll_frequency" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Payroll Frequency
                                </label>
                                <select name="payroll_frequency" id="payroll_frequency" 
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors">
                                    <option value="monthly" {{ setting('payroll_frequency') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                    <option value="semi-monthly" {{ setting('payroll_frequency') == 'semi-monthly' ? 'selected' : '' }}>Semi-Monthly</option>
                                    <option value="weekly" {{ setting('payroll_frequency') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                    <option value="bi-weekly" {{ setting('payroll_frequency') == 'bi-weekly' ? 'selected' : '' }}>Bi-Weekly</option>
                                </select>
                            </div>

                            <div>
                                <label for="payroll_currency" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Currency
                                </label>
                                <select name="payroll_currency" id="payroll_currency" 
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors">
                                    <option value="PHP" {{ setting('payroll_currency') == 'PHP' ? 'selected' : '' }}>PHP (₱)</option>
                                    <option value="USD" {{ setting('payroll_currency') == 'USD' ? 'selected' : '' }}>USD ($)</option>
                                    <option value="EUR" {{ setting('payroll_currency') == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                                </select>
                            </div>

                            <div>
                                <label for="tax_rate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Default Tax Rate (%)
                                </label>
                                <input type="number" step="0.01" name="tax_rate" id="tax_rate" 
                                    value="{{ old('tax_rate', setting('tax_rate', 10)) }}"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors">
                            </div>

                            <div>
                                <label for="sss_contribution" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    SSS Contribution Rate (%)
                                </label>
                                <input type="number" step="0.01" name="sss_contribution" id="sss_contribution" 
                                    value="{{ old('sss_contribution', setting('sss_contribution', 4.5)) }}"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors">
                            </div>

                            <div>
                                <label for="philhealth_contribution" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    PhilHealth Contribution Rate (%)
                                </label>
                                <input type="number" step="0.01" name="philhealth_contribution" id="philhealth_contribution" 
                                    value="{{ old('philhealth_contribution', setting('philhealth_contribution', 3.5)) }}"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors">
                            </div>

                            <div>
                                <label for="pagibig_contribution" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Pag-IBIG Contribution Rate (%)
                                </label>
                                <input type="number" step="0.01" name="pagibig_contribution" id="pagibig_contribution" 
                                    value="{{ old('pagibig_contribution', setting('pagibig_contribution', 2)) }}"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab: Security -->
            <div id="tab-security" class="tab-content hidden">
                <div class="space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                <i class="bi bi-shield-lock mr-2 text-indigo-600"></i>
                                Security Settings
                            </h3>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="password_min_length" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Minimum Password Length
                                </label>
                                <input type="number" name="password_min_length" id="password_min_length" 
                                    value="{{ old('password_min_length', setting('password_min_length', 8)) }}"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors">
                            </div>

                            <div>
                                <label for="password_expiry_days" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Password Expiry (days)
                                </label>
                                <input type="number" name="password_expiry_days" id="password_expiry_days" 
                                    value="{{ old('password_expiry_days', setting('password_expiry_days', 90)) }}"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors">
                            </div>

                            <div>
                                <label for="max_login_attempts" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Max Login Attempts
                                </label>
                                <input type="number" name="max_login_attempts" id="max_login_attempts" 
                                    value="{{ old('max_login_attempts', setting('max_login_attempts', 5)) }}"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors">
                            </div>

                            <div>
                                <label for="session_timeout" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Session Timeout (minutes)
                                </label>
                                <input type="number" name="session_timeout" id="session_timeout" 
                                    value="{{ old('session_timeout', setting('session_timeout', 60)) }}"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors">
                            </div>

                            <div class="md:col-span-2">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Two-Factor Authentication</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Require 2FA for all users</p>
                                    </div>
                                    <div class="toggle-switch {{ setting('two_factor_auth', false) ? 'active' : '' }}" 
                                         onclick="toggleSwitch(this)">
                                        <div class="toggle-knob"></div>
                                    </div>
                                    <input type="hidden" name="two_factor_auth" value="{{ setting('two_factor_auth', false) ? '1' : '0' }}">
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Account Lockout</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Lock account after failed login attempts</p>
                                    </div>
                                    <div class="toggle-switch {{ setting('account_lockout', true) ? 'active' : '' }}" 
                                         onclick="toggleSwitch(this)">
                                        <div class="toggle-knob"></div>
                                    </div>
                                    <input type="hidden" name="account_lockout" value="{{ setting('account_lockout', true) ? '1' : '0' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab: Email -->
            <div id="tab-email" class="tab-content hidden">
                <div class="space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                <i class="bi bi-envelope-paper mr-2 text-indigo-600"></i>
                                Email Settings
                            </h3>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="mail_driver" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Mail Driver
                                </label>
                                <select name="mail_driver" id="mail_driver" 
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors">
                                    <option value="smtp" {{ setting('mail.default') == 'smtp' ? 'selected' : '' }}>SMTP</option>
                                    <option value="sendmail" {{ setting('mail.default') == 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                                    <option value="mailgun" {{ setting('mail.default') == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                                    <option value="ses" {{ setting('mail.default') == 'ses' ? 'selected' : '' }}>Amazon SES</option>
                                    <option value="log" {{ setting('mail.default') == 'log' ? 'selected' : '' }}>Log (Development)</option>
                                </select>
                            </div>

                            <div>
                                <label for="mail_host" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    SMTP Host
                                </label>
                                <input type="text" name="mail_host" id="mail_host" 
                                    value="{{ old('mail_host', setting('mail.mailers.smtp.host', 'smtp.gmail.com')) }}"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors">
                            </div>

                            <div>
                                <label for="mail_port" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    SMTP Port
                                </label>
                                <input type="number" name="mail_port" id="mail_port" 
                                    value="{{ old('mail_port', setting('mail.mailers.smtp.port', 587)) }}"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors">
                            </div>

                            <div>
                                <label for="mail_username" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    SMTP Username
                                </label>
                                <input type="text" name="mail_username" id="mail_username" 
                                    value="{{ old('mail_username', setting('mail.mailers.smtp.username')) }}"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors">
                            </div>

                            <div>
                                <label for="mail_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    SMTP Password
                                </label>
                                <input type="password" name="mail_password" id="mail_password" 
                                    value="{{ old('mail_password', setting('mail.mailers.smtp.password')) }}"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors">
                            </div>

                            <div>
                                <label for="mail_encryption" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Encryption
                                </label>
                                <select name="mail_encryption" id="mail_encryption" 
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors">
                                    <option value="tls" {{ setting('mail.mailers.smtp.encryption') == 'tls' ? 'selected' : '' }}>TLS</option>
                                    <option value="ssl" {{ setting('mail.mailers.smtp.encryption') == 'ssl' ? 'selected' : '' }}>SSL</option>
                                    <option value="null" {{ setting('mail.mailers.smtp.encryption') == 'null' ? 'selected' : '' }}>None</option>
                                </select>
                            </div>

                            <div class="md:col-span-2">
                                <label for="mail_from_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    From Email Address
                                </label>
                                <input type="email" name="mail_from_address" id="mail_from_address" 
                                    value="{{ old('mail_from_address', setting('mail.from.address')) }}"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors">
                            </div>

                            <div class="md:col-span-2">
                                <label for="mail_from_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    From Name
                                </label>
                                <input type="text" name="mail_from_name" id="mail_from_name" 
                                    value="{{ old('mail_from_name', setting('mail.from.name', setting('app.name'))) }}"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white transition-colors">
                            </div>

                            <div class="md:col-span-2">
                                <button type="button" onclick="sendTestEmail()" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-all duration-200">
                                    <i class="bi bi-envelope-check mr-2"></i>
                                    Send Test Email
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Tab Styles */
    .tab-active {
        color: #4f46e5;
        border-bottom-color: #4f46e5;
        border-bottom-width: 2px;
    }
    .dark .tab-active {
        color: #818cf8;
        border-bottom-color: #818cf8;
    }
    .tab-inactive {
        color: #6b7280;
        border-bottom-color: transparent;
    }
    .dark .tab-inactive {
        color: #9ca3af;
    }
    .tab-inactive:hover {
        color: #374151;
        border-bottom-color: #d1d5db;
    }
    .dark .tab-inactive:hover {
        color: #e5e7eb;
        border-bottom-color: #4b5563;
    }

    /* Toggle Switch */
    .toggle-switch {
        position: relative;
        width: 48px;
        height: 28px;
        background: #d1d5db;
        border-radius: 14px;
        transition: all 0.3s ease;
        cursor: pointer;
        flex-shrink: 0;
    }
    .toggle-switch.active {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
    }
    .toggle-switch .toggle-knob {
        position: absolute;
        top: 3px;
        left: 3px;
        width: 22px;
        height: 22px;
        background: white;
        border-radius: 50%;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }
    .toggle-switch.active .toggle-knob {
        left: 23px;
    }
    .dark .toggle-switch {
        background: #374151;
    }
    .dark .toggle-switch.active {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
    }

    /* Input focus styles */
    input:focus, select:focus, textarea:focus {
        outline: none;
    }
</style>
@endpush

@push('scripts')
<script>
    // Tab switching
    document.addEventListener('DOMContentLoaded', function() {
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');

        tabBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active class from all tabs
                tabBtns.forEach(b => {
                    b.classList.remove('tab-active');
                    b.classList.add('tab-inactive');
                });

                // Add active class to clicked tab
                this.classList.remove('tab-inactive');
                this.classList.add('tab-active');

                // Hide all tab contents
                tabContents.forEach(content => {
                    content.classList.add('hidden');
                });

                // Show the corresponding tab content
                const tabId = this.getAttribute('data-tab');
                const targetContent = document.getElementById(`tab-${tabId}`);
                if (targetContent) {
                    targetContent.classList.remove('hidden');
                }
            });
        });

        // Toggle switch functionality
        window.toggleSwitch = function(element) {
            element.classList.toggle('active');
            const hiddenInput = element.nextElementSibling;
            if (hiddenInput && hiddenInput.type === 'hidden') {
                hiddenInput.value = element.classList.contains('active') ? '1' : '0';
            }
        };
    });

    // Send test email
    function sendTestEmail() {
        const form = document.getElementById('settingsForm');
        const formData = new FormData(form);
        
        fetch('{{ route("settings.test-email") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Test email sent successfully!');
            } else {
                alert('Failed to send test email: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error sending test email: ' + error.message);
        });
    }
</script>
@endpush