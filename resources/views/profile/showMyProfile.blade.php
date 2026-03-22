@extends('layouts.app')

@section('title', 'Employee Profile')
@section('header', 'My Profile')
@section('subheader', 'View and manage your personal information')
@php
$full_name = $employee->first_name . ' ' . $employee->middle_name . ' ' . $employee->last_name . ' ' . $employee->name_extension;
@endphp
@section('header-actions')
    @if($profileComplete)
        <a href="{{ route('myprofile.edit', $employee->id) }}" 
           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-medium rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
            <i class="bi bi-pencil-square mr-2"></i>
            Edit Profile
        </a>
    @endif
    <button onclick="window.history.back()" 
            class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 hover:border-gray-400 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-xl transition-all duration-200">
        <i class="bi bi-arrow-left mr-2"></i>
        Back
    </button>
@endsection

@section('breadcrumbs')
    <li class="flex items-center">
        <i class="bi bi-chevron-right text-gray-300 dark:text-gray-600 mx-2 text-xs"></i>
        <span class="text-gray-800 dark:text-gray-200 font-medium">My Profile</span>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    @if(!$profileComplete)
        <!-- Incomplete Profile Alert -->
        <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-2xl p-6 shadow-lg">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900/30 rounded-xl flex items-center justify-center">
                        <i class="bi bi-exclamation-triangle-fill text-amber-600 dark:text-amber-400 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-lg font-semibold text-amber-800 dark:text-amber-300">Complete Your Profile</h3>
                    <p class="mt-1 text-sm text-amber-700 dark:text-amber-400">
                        Your profile is incomplete. Please provide the missing information to help us serve you better.
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('myprofile.edit', $user->id) }}" 
                           class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white text-sm font-semibold rounded-xl shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                            <i class="bi bi-pencil-square mr-2"></i>
                            Complete Profile Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Profile Header Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-700/50 overflow-hidden">
        <!-- Cover Photo -->
        <div class="h-32 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 relative">
            <div class="absolute inset-0 bg-black/20"></div>
            @if(!$profileComplete)
                <div class="absolute top-4 right-4">
                    <span class="px-3 py-1 bg-amber-500 text-white text-xs font-medium rounded-full shadow-lg">
                        <i class="bi bi-exclamation-circle mr-1"></i>
                        Profile Incomplete
                    </span>
                </div>
            @endif
        </div>
        
        <!-- Profile Info -->
        <div class="px-6 pb-6">
            <div class="flex flex-col md:flex-row md:items-end -mt-12">
                <!-- Avatar -->
                <div class="relative group">
                    <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 p-1 shadow-xl">
                        <div class="w-full h-full rounded-2xl bg-white dark:bg-gray-800 flex items-center justify-center overflow-hidden">
                            @if($employee)
                                @if($employee->photo_path)
                                    <img src="{{ asset('storage/'.$employee->photo_path) }}" 
                                        alt="{{ $full_name }}" 
                                        class="w-full h-full object-cover">
                                @else
                                    <span class="text-3xl font-bold text-gray-400 dark:text-gray-500">
                                        {{ $employee->first_name ? substr($employee->first_name, 0, 1) : '?' }}{{ $employee->last_name ? substr($employee->last_name, 0, 1) : '?' }}
                                    </span>
                                @endif
                            @else
                                <span class="text-3xl font-bold text-gray-400 dark:text-gray-500">??</span>
                            @endif
                        </div>
                    </div>
                    @if($employee?->status === 'active')
                        <span class="absolute bottom-0 right-0 block h-4 w-4 rounded-full bg-green-500 ring-2 ring-white dark:ring-gray-800"></span>
                    @endif
                    
                    <!-- Upload Avatar Button (visible on hover) -->
                    <button class="absolute inset-0 bg-black/50 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center">
                        <i class="bi bi-camera-fill text-white text-xl"></i>
                    </button>
                </div>
                
                <!-- Name and Position -->
                <div class="mt-4 md:mt-0 md:ml-6 flex-1">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div>
                            @if($employee)
                                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                                    {{ $full_name ?: 'Complete Your Profile' }}
                                </h1>

                                <div class="flex items-center mt-1 space-x-2 flex-wrap gap-2">
                                    @if($employee->position && $employee->position->name)
                                        <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-xs font-medium rounded-full">
                                            {{ $employee->position->name }}
                                        </span>
                                    @endif

                                    @if($employee->department && $employee->department->name)
                                        <span class="px-3 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 text-xs font-medium rounded-full">
                                            {{ $employee->department->name }}
                                        </span>
                                    @endif

                                    @if($employee->status)
                                        <span class="px-3 py-1 {{ $employee->status === 'active' ? 'bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400' }} text-xs font-medium rounded-full">
                                            <i class="bi bi-circle-fill text-[8px] mr-1 {{ $employee->status === 'active' ? 'text-green-500' : 'text-gray-500' }}"></i>
                                            {{ ucfirst($employee->status) }}
                                        </span>
                                    @endif
                                </div>
                            @else
                                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Complete Your Profile</h1>
                            @endif
                        </div>
                        
                        <!-- Quick Actions -->
                        <div class="flex items-center space-x-2 mt-3 md:mt-0">
                            <button class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-all" title="Send Message">
                                <i class="bi bi-chat-dots"></i>
                            </button>
                            <button class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-all" title="Send Email">
                                <i class="bi bi-envelope"></i>
                            </button>
                            <button class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-all" title="Download Info">
                                <i class="bi bi-download"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                            <i class="bi bi-building text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Department</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $employee->department->name ?? '—' }}
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                            <i class="bi bi-calendar-check text-green-600 dark:text-green-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Joined Date</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $employee?->hired_at?->format('M d, Y') ?? '—' }}
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                            <i class="bi bi-briefcase text-purple-600 dark:text-purple-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Employee ID</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $employee->employee_id ?? '—' }}
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center">
                            <i class="bi bi-clock-history text-amber-600 dark:text-amber-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Years Active</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $employee?->hired_at ? $employee->hired_at->diffInYears(now()) . ' years' : '—' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Profile Completion Progress (if incomplete) -->
            @if(!$profileComplete)
                <div class="mt-6 bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Profile Completion</span>
                        <span class="text-sm font-semibold text-amber-600 dark:text-amber-400">{{ $completionPercentage }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2.5">
                        <div class="bg-gradient-to-r from-amber-500 to-amber-600 h-2.5 rounded-full" style="width: {{ $completionPercentage }}%"></div>
                    </div>
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                        <i class="bi bi-info-circle mr-1"></i>
                        Complete your profile to access all features
                    </p>
                </div>
            @endif
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Personal Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Information Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-700/50 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-800 flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                            <i class="bi bi-person-badge text-white"></i>
                        </div>
                        <h3 class="ml-3 text-lg font-semibold text-gray-900 dark:text-white">Personal Information</h3>
                    </div>
                    @if(!$profileComplete)
                        <a href="{{ route('myprofile.edit', $user->id) }}#personal" class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                            <i class="bi bi-plus-circle mr-1"></i>
                            Add Information
                        </a>
                    @endif
                </div>
                
                <div class="p-6">
                    @php
                        $hasPersonalInfo = $employee?->full_name || $employee?->gender || $employee?->birth_date || $employee?->marital_status || $employee?->nationality || $employee?->religion;
                    @endphp
                    
                    @if($hasPersonalInfo)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Full Name</label>
                                <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white flex items-center">
                                    <i class="bi bi-person text-gray-400 mr-2"></i>
                                    {{ $employee->full_name ?: '—' }}
                                </p>
                            </div>
                            
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Gender</label>
                                <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white flex items-center">
                                    <i class="bi bi-gender-{{ strtolower($employee->gender) ?? 'ambiguous' }} text-gray-400 mr-2"></i>
                                    {{ $employee->gender ? ucfirst($employee->gender) : '—' }}
                                </p>
                            </div>
                            
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Birth Date</label>
                                <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white flex items-center">
                                    <i class="bi bi-calendar-heart text-gray-400 mr-2"></i>
                                    {{ $employee->birth_date ? $employee->birth_date->format('F d, Y') : '—' }}
                                    @if($employee->birth_date)
                                        <span class="ml-2 text-xs text-gray-500">({{ $employee->birth_date->age }} years old)</span>
                                    @endif
                                </p>
                            </div>
                            
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Marital Status</label>
                                <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white flex items-center">
                                    <i class="bi bi-heart text-gray-400 mr-2"></i>
                                    {{ $employee->marital_status ? ucfirst($employee->marital_status) : '—' }}
                                </p>
                            </div>
                            
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nationality</label>
                                <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white flex items-center">
                                    <i class="bi bi-flag text-gray-400 mr-2"></i>
                                    {{ $employee->nationality ?: '—' }}
                                </p>
                            </div>
                            
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Religion</label>
                                <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white flex items-center">
                                    <i class="bi bi-building text-gray-400 mr-2"></i>
                                    {{ $employee->religion ?: '—' }}
                                </p>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="bi bi-person-x text-gray-400 text-3xl"></i>
                            </div>
                            <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Personal Information Yet</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Add your personal details to complete your profile</p>
                            <a href="{{ route('myprofile.edit', $employee->id) }}#personal" 
                               class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-medium rounded-xl transition-all duration-200">
                                <i class="bi bi-plus-circle mr-2"></i>
                                Add Personal Information
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Contact Information Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-700/50 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-800 flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                            <i class="bi bi-envelope text-white"></i>
                        </div>
                        <h3 class="ml-3 text-lg font-semibold text-gray-900 dark:text-white">Contact Information</h3>
                    </div>
                    @if(!$employee->email || !$employee->mobile || !$employee->address)
                        <a href="{{ route('myprofile.edit', $employee->id) }}#contact" class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                            <i class="bi bi-plus-circle mr-1"></i>
                            Add Contact Info
                        </a>
                    @endif
                </div>
                
                <div class="p-6">
                    @php
                        $hasContactInfo = $employee->email || $employee->personal_email || $employee->mobile || $employee->telephone || $employee->address;
                    @endphp
                    
                    @if($hasContactInfo)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email Address</label>
                                <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white flex items-center">
                                    <i class="bi bi-envelope text-gray-400 mr-2"></i>
                                    @if($employee->email)
                                        <a href="mailto:{{ $employee->email }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                            {{ $employee->email }}
                                        </a>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </p>
                            </div>
                            
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Personal Email</label>
                                <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white flex items-center">
                                    <i class="bi bi-envelope-open text-gray-400 mr-2"></i>
                                    {{ $employee->personal_email ?: '—' }}
                                </p>
                            </div>
                            
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Mobile Number</label>
                                <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white flex items-center">
                                    <i class="bi bi-phone text-gray-400 mr-2"></i>
                                    @if($employee->mobile)
                                        <a href="tel:{{ $employee->mobile }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                            {{ $employee->mobile }}
                                        </a>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </p>
                            </div>
                            
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Telephone</label>
                                <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white flex items-center">
                                    <i class="bi bi-telephone text-gray-400 mr-2"></i>
                                    {{ $employee->telephone ?: '—' }}
                                </p>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Address</label>
                                <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white flex items-start">
                                    <i class="bi bi-geo-alt text-gray-400 mr-2 mt-0.5"></i>
                                    <span>
                                        {{ $employee->address ?: '—' }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="bi bi-envelope-x text-gray-400 text-3xl"></i>
                            </div>
                            <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Contact Information</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Add your contact details to stay connected</p>
                            <a href="{{ route('myprofile.edit', $employee->id) }}#contact" 
                               class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-medium rounded-xl transition-all duration-200">
                                <i class="bi bi-plus-circle mr-2"></i>
                                Add Contact Information
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Emergency Contact Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-700/50 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-800 flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-r from-red-500 to-rose-600 rounded-lg flex items-center justify-center">
                            <i class="bi bi-shield-exclamation text-white"></i>
                        </div>
                        <h3 class="ml-3 text-lg font-semibold text-gray-900 dark:text-white">Emergency Contact</h3>
                    </div>
                    @if(!$employee->emergency_contact_name || !$employee->emergency_contact_phone)
                        <a href="{{ route('myprofile.edit', $employee->id) }}#emergency" class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                            <i class="bi bi-plus-circle mr-1"></i>
                            Add Emergency Contact
                        </a>
                    @endif
                </div>
                
                <div class="p-6">
                    @php
                        $hasEmergencyInfo = $employee->emergency_contact_name || $employee->emergency_contact_relationship || $employee->emergency_contact_phone || $employee->emergency_contact_alt_phone || $employee->emergency_contact_address;
                    @endphp
                    
                    @if($hasEmergencyInfo)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Contact Person</label>
                                <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white flex items-center">
                                    <i class="bi bi-person-circle text-gray-400 mr-2"></i>
                                    {{ $employee->emergency_contact_name ?: '—' }}
                                </p>
                            </div>
                            
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Relationship</label>
                                <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white flex items-center">
                                    <i class="bi bi-heart text-gray-400 mr-2"></i>
                                    {{ $employee->emergency_contact_relationship ?: '—' }}
                                </p>
                            </div>
                            
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Contact Number</label>
                                <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white flex items-center">
                                    <i class="bi bi-phone text-gray-400 mr-2"></i>
                                    {{ $employee->emergency_contact_phone ?: '—' }}
                                </p>
                            </div>
                            
                            <div>
                                <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Alternative Number</label>
                                <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white flex items-center">
                                    <i class="bi bi-telephone text-gray-400 mr-2"></i>
                                    {{ $employee->emergency_contact_alt_phone ?: '—' }}
                                </p>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Address</label>
                                <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white flex items-start">
                                    <i class="bi bi-geo-alt text-gray-400 mr-2 mt-0.5"></i>
                                    <span>
                                        {{ $employee->emergency_contact_address ?: '—' }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="bi bi-shield-x text-gray-400 text-3xl"></i>
                            </div>
                            <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Emergency Contact</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Add an emergency contact for safety purposes</p>
                            <a href="{{ route('myprofile.edit', $employee->id) }}#emergency" 
                               class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-medium rounded-xl transition-all duration-200">
                                <i class="bi bi-plus-circle mr-2"></i>
                                Add Emergency Contact
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column - Employment Details & Stats -->
        <div class="space-y-6">
            <!-- Employment Details Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-700/50 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-800">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg flex items-center justify-center">
                            <i class="bi bi-briefcase text-white"></i>
                        </div>
                        <h3 class="ml-3 text-lg font-semibold text-gray-900 dark:text-white">Employment Details</h3>
                    </div>
                </div>
                
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Employee ID</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $employee->employee_id ?? '—' }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Department</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $employee->department->name ?? '—' }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Position</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $employee->position->name ?? '—' }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Employment Type</span>
                        @if($employee->employment_type)
                            <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-xs font-medium rounded-full">
                                {{ ucfirst($employee->employment_type) }}
                            </span>
                        @else
                            <span class="text-sm text-gray-400">—</span>
                        @endif
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Date Hired</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">
                            {{ $employee->hired_at ? $employee->hired_at->format('M d, Y') : '—' }}
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Regularization Date</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">
                            {{ $employee->regularization_date ? $employee->regularization_date->format('M d, Y') : '—' }}
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Supervisor</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">
                            {{ $employee->supervisor->full_name ?? '—' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Quick Stats Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-700/50 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-800">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-r from-amber-500 to-amber-600 rounded-lg flex items-center justify-center">
                            <i class="bi bi-graph-up text-white"></i>
                        </div>
                        <h3 class="ml-3 text-lg font-semibold text-gray-900 dark:text-white">Leave Statistics</h3>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">12</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Leave Credits</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600 dark:text-green-400">5</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Used Leaves</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">98%</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Attendance</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-amber-600 dark:text-amber-400">0</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Tardiness</div>
                        </div>
                    </div>
                    
                    <hr class="my-4 border-gray-200 dark:border-gray-700">
                    
                    <div class="space-y-3">
                        <div>
                            <div class="flex justify-between text-xs mb-1">
                                <span class="text-gray-600 dark:text-gray-400">Leave Balance</span>
                                <span class="font-medium text-gray-900 dark:text-white">12/15 days</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full" style="width: 80%"></div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between text-xs mb-1">
                                <span class="text-gray-600 dark:text-gray-400">Sick Leave</span>
                                <span class="font-medium text-gray-900 dark:text-white">5/5 days</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-gradient-to-r from-green-500 to-emerald-600 h-2 rounded-full" style="width: 100%"></div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between text-xs mb-1">
                                <span class="text-gray-600 dark:text-gray-400">Vacation Leave</span>
                                <span class="font-medium text-gray-900 dark:text-white">7/10 days</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-gradient-to-r from-purple-500 to-purple-600 h-2 rounded-full" style="width: 70%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-700/50 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-800 flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-r from-cyan-500 to-sky-600 rounded-lg flex items-center justify-center">
                            <i class="bi bi-folder2 text-white"></i>
                        </div>
                        <h3 class="ml-3 text-lg font-semibold text-gray-900 dark:text-white">Documents</h3>
                    </div>
                </div>
                
                <div class="p-6">
                    @php
                        $hasDocuments = $employee->documents && $employee->documents->count() > 0;
                    @endphp
                    
                    @if($hasDocuments)
                        <div class="space-y-3">
                            @foreach($employee->documents as $document)
                                <a href="{{ asset('storage/'.$document->path) }}" target="_blank" 
                                   class="flex items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors group">
                                    <div class="w-8 h-8 {{ $document->type === 'pdf' ? 'bg-red-100 dark:bg-red-900/30' : ($document->type === 'image' ? 'bg-green-100 dark:bg-green-900/30' : 'bg-blue-100 dark:bg-blue-900/30') }} rounded-lg flex items-center justify-center">
                                        <i class="bi {{ $document->type === 'pdf' ? 'bi-file-pdf text-red-600 dark:text-red-400' : ($document->type === 'image' ? 'bi-image text-green-600 dark:text-green-400' : 'bi-file-text text-blue-600 dark:text-blue-400') }}"></i>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400">{{ $document->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $document->size }} • {{ $document->uploaded_at->format('M d, Y') }}</p>
                                    </div>
                                    <i class="bi bi-download text-gray-400 group-hover:text-blue-500"></i>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6">
                            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="bi bi-folder-x text-gray-400 text-2xl"></i>
                            </div>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-1">No Documents Yet</h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Upload your important documents</p>
                        </div>
                    @endif
                    
                    <button class="w-full mt-4 text-center text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                        <i class="bi bi-cloud-upload mr-1"></i>
                        Upload Document
                    </button>
                </div>
            </div>
            
            <!-- Complete Profile CTA (if incomplete) -->
            @if(!$profileComplete)
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl p-6 shadow-xl">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="bi bi-check2-circle text-white text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-white mb-2">Complete Your Profile</h3>
                        <p class="text-sm text-white/80 mb-4">
                            Fill in your information to unlock all features
                        </p>
                        <a href="{{ route('myprofile.edit', $employee->id) }}" 
                           class="inline-flex items-center px-5 py-2.5 bg-white text-blue-600 text-sm font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                            <i class="bi bi-pencil-square mr-2"></i>
                            Complete Profile
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Any additional JavaScript for the profile page
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips if needed
    });
</script>
@endpush