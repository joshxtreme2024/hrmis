@extends('layouts.app')

@section('title', 'Personal Data Sheet')
@section('header', 'Personal Data Sheet')
@section('subheader', 'Complete personal information profile')

@php
    $fullName = $personalData ? $personalData->last_name . ', ' . $personalData->first_name . ' ' . ($personalData->middle_name ?? '') . ' ' . ($personalData->ext_name ?? '') : 'Not Set';
@endphp

@section('header-actions')
    <button onclick="window.history.back()" 
            class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 hover:border-gray-400 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-xl transition-all duration-200">
        <i class="bi bi-arrow-left mr-2"></i>
        Back
    </button>
@endsection

@section('breadcrumbs')
    <li class="flex items-center">
        <i class="bi bi-chevron-right text-gray-300 dark:text-gray-600 mx-2 text-xs"></i>
        <span class="text-gray-800 dark:text-gray-200 font-medium">PDS</span>
    </li>
@endsection

<style>
    /* Modal animation */
    #editChildModal {
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }

    #editChildModal.hidden {
        opacity: 0;
        visibility: hidden;
    }

    #editChildModal:not(.hidden) {
        opacity: 1;
        visibility: visible;
    }

    #editChildModal .relative {
        transform: scale(0.95);
        transition: transform 0.3s ease;
    }

    #editChildModal:not(.hidden) .relative {
        transform: scale(1);
    }

    /* Form input focus effects */
    input:focus, select:focus {
        outline: none;
    }

    /* Smooth transitions for form elements */
    input, select {
        transition: all 0.2s ease;
    }

    .employment-card {
        transition: all 0.3s ease;
    }

    .employment-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    }

    .stat-card {
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    /* Dark mode hover effects */
    .dark .employment-card:hover {
        box-shadow: 0 10px 40px rgba(255,255,255,0.05);
    }

    .dark .stat-card:hover {
        box-shadow: 0 8px 25px rgba(255,255,255,0.05);
    }
</style>

@section('content')
<div class="space-y-6">
    <!-- Profile Header -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-700/50 overflow-hidden">
        <div class="h-32 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 relative">
            <div class="absolute inset-0 bg-black/20"></div>
        </div>
        
        <div class="px-6 pb-6 mt-2">
            <div class="flex flex-col md:flex-row md:items-end -mt-12">
                <div class="relative">
                    <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 p-1 shadow-xl">
                        <div class="w-full h-full rounded-2xl bg-white dark:bg-gray-800 flex items-center justify-center overflow-hidden">
                            @if($personalData && $personalData->photo_path)
                                <img src="{{ asset('storage/'.$personalData->photo_path) }}" 
                                     alt="{{ $personalData->completeName() }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <span class="text-3xl font-bold text-gray-400 dark:text-gray-500">
                                    {{ $personalData ? substr($personalData->first_name ?? '?', 0, 1) : '?' }}{{ $personalData ? substr($personalData->last_name ?? '?', 0, 1) : '?' }}
                                </span>
                            @endif
                        </div>
                        <div class="absolute bottom-0 right-0 w-6 h-6 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center shadow-md">
                            <i class="bi bi-camera text-white text-sm"></i>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 md:mt-0 md:ml-6 flex-1">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $personalData->completeName() }}
                            </h1>
                            <div class="flex items-center mt-1 space-x-2 flex-wrap gap-2">
                                @if($personalData && $personalData->sex)
                                    <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-xs font-medium rounded-full">
                                        {{ ucfirst($personalData->sex) }}
                                    </span>
                                @endif
                                @if($personalData && $personalData->civil_status)
                                    <span class="px-3 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 text-xs font-medium rounded-full">
                                        {{ ucfirst($personalData->civil_status) }}
                                    </span>
                                @endif
                                @if($personalData && $personalData->nationality)
                                    <span class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 text-xs font-medium rounded-full">
                                        {{ $personalData->nationality }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <div>
                    <a href="{{ route('mydocuments.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-medium rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105"
                        >
                        <i class="fa-regular fa-folder mr-2 text-3xl"></i>
                        My 201 File
                    </a>
                </div>
            </div>
            
            <!-- Profile Completion -->
            <div class="mt-6 bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Profile Completion</span>
                    <span class="text-sm font-semibold {{ $profileComplete ? 'text-green-600 dark:text-green-400' : 'text-amber-600 dark:text-amber-400' }}">
                        {{ $completionPercentage ?? 0 }}%
                    </span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2.5">
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-2.5 rounded-full transition-all duration-500" 
                        style="width: {{ $completionPercentage ?? 0 }}%"></div>
                </div>
                
                <!-- Status Badge -->
                <div class="mt-3 flex items-center justify-between">
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        <i class="bi bi-info-circle mr-1"></i>
                        {{ $profileComplete ? '✅ Your PDS is complete!' : 'Complete all sections to have a full PDS profile' }}
                    </p>
                    <span class="px-2 py-0.5 text-xs font-medium rounded-full 
                        @php
                            $status = $completionStatus ?? 'incomplete';
                        @endphp
                        @if($status === 'excellent')
                            bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400
                        @elseif($status === 'complete')
                            bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400
                        @elseif($status === 'partial')
                            bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400
                        @else
                            bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400
                        @endif
                    ">
                        {{ ucfirst($status) }}
                    </span>
                </div>
                
                <!-- Section-wise Completion Details -->
                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($completionSections ?? [] as $key => $section)
                    @php
                        $score = round(($section['score'] ?? 0) * 100);
                        $isComplete = ($section['score'] ?? 0) >= 0.8;
                        $color = $section['color'] ?? 'gray';
                        $errors = $section['errors'] ?? [];
                        $label = $section['label'] ?? 'Section';
                        $icon = $section['icon'] ?? 'bi-circle';
                        
                        // ✅ Map colors to actual Tailwind classes
                        $colorClasses = [
                            'blue' => [
                                'bg' => 'bg-blue-100 dark:bg-blue-900/30',
                                'icon' => 'text-blue-600 dark:text-blue-400',
                                'bar' => 'bg-blue-500',
                                'border' => 'border-blue-200 dark:border-blue-800',
                            ],
                            'purple' => [
                                'bg' => 'bg-purple-100 dark:bg-purple-900/30',
                                'icon' => 'text-purple-600 dark:text-purple-400',
                                'bar' => 'bg-purple-500',
                                'border' => 'border-purple-200 dark:border-purple-800',
                            ],
                            'amber' => [
                                'bg' => 'bg-amber-100 dark:bg-amber-900/30',
                                'icon' => 'text-amber-600 dark:text-amber-400',
                                'bar' => 'bg-amber-500',
                                'border' => 'border-amber-200 dark:border-amber-800',
                            ],
                            'green' => [
                                'bg' => 'bg-green-100 dark:bg-green-900/30',
                                'icon' => 'text-green-600 dark:text-green-400',
                                'bar' => 'bg-green-500',
                                'border' => 'border-green-200 dark:border-green-800',
                            ],
                            'red' => [
                                'bg' => 'bg-red-100 dark:bg-red-900/30',
                                'icon' => 'text-red-600 dark:text-red-400',
                                'bar' => 'bg-red-500',
                                'border' => 'border-red-200 dark:border-red-800',
                            ],
                            'yellow' => [
                                'bg' => 'bg-yellow-100 dark:bg-yellow-900/30',
                                'icon' => 'text-yellow-600 dark:text-yellow-400',
                                'bar' => 'bg-yellow-500',
                                'border' => 'border-yellow-200 dark:border-yellow-800',
                            ],
                            'teal' => [
                                'bg' => 'bg-teal-100 dark:bg-teal-900/30',
                                'icon' => 'text-teal-600 dark:text-teal-400',
                                'bar' => 'bg-teal-500',
                                'border' => 'border-teal-200 dark:border-teal-800',
                            ],
                            'cyan' => [
                                'bg' => 'bg-cyan-100 dark:bg-cyan-900/30',
                                'icon' => 'text-cyan-600 dark:text-cyan-400',
                                'bar' => 'bg-cyan-500',
                                'border' => 'border-cyan-200 dark:border-cyan-800',
                            ],
                            'indigo' => [
                                'bg' => 'bg-indigo-100 dark:bg-indigo-900/30',
                                'icon' => 'text-indigo-600 dark:text-indigo-400',
                                'bar' => 'bg-indigo-500',
                                'border' => 'border-indigo-200 dark:border-indigo-800',
                            ],
                            'pink' => [
                                'bg' => 'bg-pink-100 dark:bg-pink-900/30',
                                'icon' => 'text-pink-600 dark:text-pink-400',
                                'bar' => 'bg-pink-500',
                                'border' => 'border-pink-200 dark:border-pink-800',
                            ],
                            'rose' => [
                                'bg' => 'bg-rose-100 dark:bg-rose-900/30',
                                'icon' => 'text-rose-600 dark:text-rose-400',
                                'bar' => 'bg-rose-500',
                                'border' => 'border-rose-200 dark:border-rose-800',
                            ],
                            'violet' => [
                                'bg' => 'bg-violet-100 dark:bg-violet-900/30',
                                'icon' => 'text-violet-600 dark:text-violet-400',
                                'bar' => 'bg-violet-500',
                                'border' => 'border-violet-200 dark:border-violet-800',
                            ],
                            'gray' => [
                                'bg' => 'bg-gray-100 dark:bg-gray-700/30',
                                'icon' => 'text-gray-600 dark:text-gray-400',
                                'bar' => 'bg-gray-500',
                                'border' => 'border-gray-200 dark:border-gray-700',
                            ],
                            'gray' => [
                                'bg' => 'bg-gray-100 dark:bg-gray-700/30',
                                'icon' => 'text-gray-600 dark:text-gray-400',
                                'bar' => 'bg-red-500',
                                'border' => 'border-gray-200 dark:border-gray-700',
                            ],
                        ];
                        
                        $classes = $colorClasses[$color] ?? $colorClasses['gray'];
                    @endphp
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-3 border {{ $isComplete ? 'border-green-200 dark:border-green-800' : $classes['border'] }}">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-7 h-7 rounded-lg {{ $classes['bg'] }} flex items-center justify-center mr-2">
                                    <i class="{{ $icon }} {{ $classes['icon'] }} text-xs"></i>
                                </div>
                                <span class="text-xs font-medium text-gray-700 dark:text-gray-300">{{ $label }}</span>
                            </div>
                            <div class="flex items-center space-x-1">
                                <span class="text-xs font-semibold {{ $isComplete ? 'text-green-600 dark:text-green-400' : 'text-gray-500 dark:text-gray-400' }}">
                                    {{ $score }}%
                                </span>
                                @if($isComplete)
                                    <i class="bi bi-check-circle-fill text-green-500 text-xs"></i>
                                @else
                                    <i class="bi bi-exclamation-circle-fill text-amber-500 text-xs"></i>
                                @endif
                            </div>
                        </div>
                        <div class="mt-1.5 w-full bg-gray-200 dark:bg-gray-600 rounded-full h-1">
                            <div class="{{ $classes['bar'] }} h-1 rounded-full transition-all duration-500" 
                                style="width: {{ $score }}%"></div>
                        </div>
                        @if(!$isComplete && count($errors) > 0)
                            <div class="mt-1.5">
                                <p class="text-xs text-red-500 dark:text-red-400">
                                    <i class="bi bi-exclamation-triangle mr-0.5"></i>
                                    {{ implode(', ', array_slice($errors, 0, 2)) }}
                                    @if(count($errors) > 2)
                                        +{{ count($errors) - 2 }} more
                                    @endif
                                </p>
                            </div>
                        @endif
                    </div>
                @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-700/50 overflow-hidden">
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="flex flex-wrap -mb-px" role="tablist">
                <button class="tab-btn active px-4 py-3 text-sm font-medium text-indigo-600 dark:text-indigo-400 border-b-2 border-indigo-600 dark:border-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 transition-colors" 
                        data-tab="personal" role="tab" aria-selected="true">
                    <i class="bi bi-person mr-2"></i>
                    Personal
                </button>
                <button class="tab-btn px-4 py-3 text-sm font-medium text-gray-500 dark:text-gray-400 border-b-2 border-transparent hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 transition-colors" 
                        data-tab="family" role="tab" aria-selected="false">
                    <i class="bi bi-people mr-2"></i>
                    Family
                </button>
                <button class="tab-btn px-4 py-3 text-sm font-medium text-gray-500 dark:text-gray-400 border-b-2 border-transparent hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 transition-colors" 
                        data-tab="education" role="tab" aria-selected="false">
                    <i class="bi bi-mortarboard mr-2"></i>
                    Education
                </button>
                <button class="tab-btn px-4 py-3 text-sm font-medium text-gray-500 dark:text-gray-400 border-b-2 border-transparent hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 transition-colors" 
                        data-tab="eligibilities" role="tab" aria-selected="false">
                    <i class="fa-solid fa-certificate mr-2"></i>
                    CS Eligibilities
                </button>
                <button class="tab-btn px-4 py-3 text-sm font-medium text-gray-500 dark:text-gray-400 border-b-2 border-transparent hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 transition-colors" 
                        data-tab="work" role="tab" aria-selected="false">
                    <i class="bi bi-briefcase mr-2"></i>
                    Work Experiences
                </button>
                <button class="tab-btn px-4 py-3 text-sm font-medium text-gray-500 dark:text-gray-400 border-b-2 border-transparent hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 transition-colors" 
                        data-tab="volwork" role="tab" aria-selected="false">
                    <i class="fa-solid fa-person-digging mr-2"></i>
                    Voluntary Works
                </button>
                <button class="tab-btn px-4 py-3 text-sm font-medium text-gray-500 dark:text-gray-400 border-b-2 border-transparent hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 transition-colors" 
                        data-tab="trainings" role="tab" aria-selected="false">
                    <i class="fa-solid fa-dumbbell mr-2"></i>
                    Trainings
                </button>
                <button class="tab-btn px-4 py-3 text-sm font-medium text-gray-500 dark:text-gray-400 border-b-2 border-transparent hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 transition-colors" 
                        data-tab="skills" role="tab" aria-selected="false">
                    <i class="bi bi-star mr-2"></i>
                    Skills
                </button>
                <button class="tab-btn px-4 py-3 text-sm font-medium text-gray-500 dark:text-gray-400 border-b-2 border-transparent hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 transition-colors" 
                        data-tab="distinctions" role="tab" aria-selected="false">
                    <i class="fa-solid fa-trophy mr-2"></i>
                    Distinctions
                </button>
                <button class="tab-btn px-4 py-3 text-sm font-medium text-gray-500 dark:text-gray-400 border-b-2 border-transparent hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 transition-colors" 
                        data-tab="organizations" role="tab" aria-selected="false">
                    <i class="fa-solid fa-users mr-2"></i>
                    Organizations
                </button>
                <button class="tab-btn px-4 py-3 text-sm font-medium text-gray-500 dark:text-gray-400 border-b-2 border-transparent hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 transition-colors" 
                        data-tab="background" role="tab" aria-selected="false">
                    <i class="bi bi-person-check mr-2"></i>
                    Background Info
                </button>
                <button class="tab-btn px-4 py-3 text-sm font-medium text-gray-500 dark:text-gray-400 border-b-2 border-transparent hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 transition-colors" 
                        data-tab="references" role="tab" aria-selected="false">
                    <i class="bi bi-person-check mr-2"></i>
                    References
                </button>
                <button class="tab-btn px-4 py-3 text-sm font-medium text-gray-500 dark:text-gray-400 border-b-2 border-transparent hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 transition-colors" 
                        data-tab="employment" role="tab" aria-selected="false">
                    <i class="bi bi-building mr-2"></i>
                    Employment
                </button>
                <button class="tab-btn px-4 py-3 text-sm font-medium text-gray-500 dark:text-gray-400 border-b-2 border-transparent hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 transition-colors" 
                        data-tab="government" role="tab" aria-selected="false">
                    <i class="bi bi-person-vcard mr-2"></i>
                    Government IDs
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
            <!-- Personal Tab -->
            <div class="tab-content active" id="tab-personal">
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200/50 dark:border-gray-700/50 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-800 flex justify-between items-center">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                                <i class="bi bi-person text-white"></i>
                            </div>
                            <h3 class="ml-3 text-lg font-semibold text-gray-900 dark:text-white">Personal Information</h3>
                            <span class="ml-2 px-2.5 py-0.5 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs font-medium rounded-full">
                                {{ $personalData ? 'Complete' : 'Incomplete' }}
                            </span>
                        </div>
                        <div class="flex justify-end items-end">
                            <a href="{{ route('myprofile.editPersonalData') }}" 
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-medium rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                                <i class="bi bi-pencil-square mr-2"></i>
                                Edit
                            </a>
                        </div>
                    </div>
                    <div class="p-6">
                        @if($personalData)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Personal Details -->
                                <div class="md:col-span-2">
                                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                                        <span class="w-1 h-4 bg-blue-500 rounded-full mr-2"></span>
                                        Personal Details
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 dark:bg-gray-700/30 rounded-xl p-4">
                                        <div>
                                            <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">First Name</label>
                                            <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">{{ $personalData->first_name ?? '—' }}</p>
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Middle Name</label>
                                            <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">{{ $personalData->middle_name ?? '—' }}</p>
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Last Name</label>
                                            <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">{{ $personalData->last_name ?? '—' }}</p>
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name Extension</label>
                                            <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">{{ $personalData->ext_name ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Birth & Demographics -->
                                <div class="md:col-span-2">
                                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                                        <span class="w-1 h-4 bg-green-500 rounded-full mr-2"></span>
                                        Birth & Demographics
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 dark:bg-gray-700/30 rounded-xl p-4">
                                        <div>
                                            <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Sex</label>
                                            <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white capitalize">{{ $personalData->sex ?? '—' }}</p>
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Birth Date</label>
                                            <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $personalData->birth_date ? \Carbon\Carbon::parse($personalData->birth_date)->format('F d, Y') : '—' }}
                                            </p>
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Place of Birth</label>
                                            <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">{{ $personalData->place_of_birth ?? '—' }}</p>
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Marital Status</label>
                                            <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white capitalize">{{ $personalData->civil_status ?? '—' }}</p>
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nationality</label>
                                            <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">{{ $personalData->nationality ?? '—' }}</p>
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Religion</label>
                                            <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">{{ $personalData->religion ?? '—' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Physical Attributes -->
                                <div class="md:col-span-2">
                                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                                        <span class="w-1 h-4 bg-purple-500 rounded-full mr-2"></span>
                                        Physical Attributes
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-gray-50 dark:bg-gray-700/30 rounded-xl p-4">
                                        <div>
                                            <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Height (cm)</label>
                                            <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">{{ $personalData->height ?? '—' }}</p>
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Weight (kg)</label>
                                            <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">{{ $personalData->weight ?? '—' }}</p>
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Blood Type</label>
                                            <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">{{ $personalData->blood_type ?? '—' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Contact Information -->
                                <div class="md:col-span-2">
                                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                                        <span class="w-1 h-4 bg-amber-500 rounded-full mr-2"></span>
                                        Contact Information
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 dark:bg-gray-700/30 rounded-xl p-4">
                                        <div>
                                            <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Telephone No.</label>
                                            <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">{{ $personalData->telephone_no ?? '—' }}</p>
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Mobile No.</label>
                                            <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">{{ $personalData->mobile_no ?? '—' }}</p>
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</label>
                                            <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">{{ $personalData->user->email ?? '—' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Addresses Section -->
                                <div class="md:col-span-2">
                                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                                        <span class="w-1 h-4 bg-red-500 rounded-full mr-2"></span>
                                        Addresses
                                    </h4>
                                    
                                    @if($addresses && $addresses->count() > 0)
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            @foreach($addresses as $address)
                                                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-4 border border-gray-200 dark:border-gray-600 group relative">
                                                    <div class="flex items-center justify-between mb-2">
                                                        <span class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                                            <i class="bi bi-geo-alt 
                                                                @if($address->address_type == 'residential') text-green-500 
                                                                @elseif($address->address_type == 'permanent') text-blue-500
                                                                @else text-gray-500 @endif 
                                                                mr-1">
                                                            </i>
                                                            {{ ucfirst($address->address_type) }} Address
                                                        </span>
                                                        <span class="px-2 py-0.5 text-xs font-medium rounded-full 
                                                            @if($address->address_type == 'residential') bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400
                                                            @elseif($address->address_type == 'permanent') bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400
                                                            @else bg-gray-100 dark:bg-gray-700/30 text-gray-700 dark:text-gray-400 @endif">
                                                            {{ ucfirst($address->address_type) }}
                                                        </span>
                                                    </div>
                                                    
                                                    <div class="space-y-1 text-sm">
                                                        @if($address->hbl_number)
                                                            <p class="text-gray-600 dark:text-gray-400">
                                                                <span class="text-gray-500 dark:text-gray-500">House/Block/Lot:</span> 
                                                                {{ $address->hbl_number }}
                                                            </p>
                                                        @endif
                                                        
                                                        @if($address->street)
                                                            <p class="text-gray-600 dark:text-gray-400">
                                                                <span class="text-gray-500 dark:text-gray-500">Street:</span> 
                                                                {{ $address->street }}
                                                            </p>
                                                        @endif
                                                        
                                                        @if($address->subdi_village)
                                                            <p class="text-gray-600 dark:text-gray-400">
                                                                <span class="text-gray-500 dark:text-gray-500">Subdivision/Village:</span> 
                                                                {{ $address->subdi_village }}
                                                            </p>
                                                        @endif
                                                        
                                                        <p class="text-gray-600 dark:text-gray-400">
                                                            <span class="text-gray-500 dark:text-gray-500">Barangay:</span> 
                                                            {{ $address->barangay ?? '—' }}
                                                        </p>
                                                        
                                                        <p class="text-gray-600 dark:text-gray-400">
                                                            <span class="text-gray-500 dark:text-gray-500">City/Municipality:</span> 
                                                            {{ $address->city_municipality ?? '—' }}
                                                        </p>
                                                        
                                                        <p class="text-gray-600 dark:text-gray-400">
                                                            <span class="text-gray-500 dark:text-gray-500">Province:</span> 
                                                            {{ $address->province ?? '—' }}
                                                        </p>
                                                        
                                                        @if($address->zip_code)
                                                            <p class="text-gray-600 dark:text-gray-400">
                                                                <span class="text-gray-500 dark:text-gray-500">Zip Code:</span> 
                                                                {{ $address->zip_code }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                    
                                                    <!-- Full Address Display -->
                                                    <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-600">
                                                        <p class="text-xs text-gray-400 dark:text-gray-500">Full Address</p>
                                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                            {{ $address->full_address ?? '—' }}
                                                        </p>
                                                    </div>

                                                    <!-- Action Buttons -->
                                                    <div class="absolute top-3 right-3 flex space-x-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                                        <button onclick="openEditAddressModal({{ $address->id }})" 
                                                                class="p-1.5 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-lg hover:bg-indigo-200 dark:hover:bg-indigo-800/50 transition-colors"
                                                                title="Edit Address">
                                                            <i class="bi bi-pencil-square text-sm"></i>
                                                        </button>
                                                        <button onclick="confirmDeleteAddress({{ $address->id }})" 
                                                                class="p-1.5 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-lg hover:bg-red-200 dark:hover:bg-red-800/50 transition-colors"
                                                                title="Delete Address">
                                                            <i class="bi bi-trash text-sm"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        
                                        <!-- Add Address Button -->
                                        <div class="mt-4">
                                            <button onclick="openAddAddressModal()" 
                                                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white text-sm font-medium rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                                                <i class="bi bi-plus-lg mr-2"></i>
                                                Add Address
                                            </button>
                                        </div>
                                    @else
                                        <div class="text-center py-8 bg-gray-50 dark:bg-gray-700/30 rounded-xl border border-gray-200 dark:border-gray-600">
                                            <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-3">
                                                <i class="bi bi-geo-alt text-gray-400 text-xl"></i>
                                            </div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">No addresses recorded.</p>
                                            <button onclick="openAddAddressModal()" 
                                                    class="mt-3 inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-medium rounded-xl transition-all duration-200 shadow-md hover:shadow-lg">
                                                <i class="bi bi-plus-lg mr-2"></i>
                                                Add Address
                                            </button>
                                        </div>
                                    @endif

                                    <!-- Add/Edit Address Modal -->
                                    <div id="addressModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                        <!-- Backdrop -->
                                        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-80 transition-opacity"></div>
                                        
                                        <!-- Modal Container -->
                                        <div class="flex min-h-screen items-center justify-center p-4">
                                            <div class="relative w-full max-w-2xl transform rounded-2xl bg-white dark:bg-gray-800 shadow-2xl transition-all">
                                                <!-- Modal Header -->
                                                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-600 to-indigo-600">
                                                    <div class="flex items-center">
                                                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                                                            <i class="bi bi-geo-alt text-white text-lg"></i>
                                                        </div>
                                                        <h3 class="ml-3 text-xl font-bold text-white" id="addressModalTitle">
                                                            Add Address
                                                        </h3>
                                                    </div>
                                                    <button onclick="closeAddressModal()" 
                                                            class="text-white/70 hover:text-white transition-colors">
                                                        <i class="bi bi-x-lg text-xl"></i>
                                                    </button>
                                                </div>
                                                
                                                <!-- Modal Body -->
                                                <div class="px-6 py-6 max-h-[70vh] overflow-y-auto">
                                                    <form id="addressForm" method="POST">
                                                        @csrf
                                                        <input type="hidden" id="address_method" name="_method" value="POST">
                                                        <input type="hidden" id="address_id" name="address_id" value="">
                                                        
                                                        <div class="space-y-6">
                                                            <!-- Address Type -->
                                                            <div id="address_type_container" class="mb-4">
                                                                <label for="address_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                                    <i class="bi bi-tag text-blue-500 mr-1"></i>
                                                                    Address Type <span class="text-red-500">*</span>
                                                                </label>
                                                                <select id="address_type" 
                                                                        name="address_type" 
                                                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                                        required>
                                                                    <option value="">Select Address Type</option>
                                                                    <option value="residential">Present Address</option>
                                                                    <option value="permanent">Permanent Address</option>
                                                                </select>
                                                                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                                                                    <i class="bi bi-info-circle mr-1"></i>
                                                                    Select the type of address (Present, Permanent, Provincial, or Other)
                                                                </p>
                                                            </div>

                                                            <!-- Hidden field for address_type when editing -->
                                                            <input type="hidden" id="address_type_hidden" name="address_type_hidden" value="">

                                                            <!-- House/Block/Lot & Street -->
                                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                                <div>
                                                                    <label for="hbl_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                                        <i class="bi bi-hash text-blue-500 mr-1"></i>
                                                                        House/Block/Lot No.
                                                                    </label>
                                                                    <input type="text" 
                                                                        id="hbl_number" 
                                                                        name="hbl_number" 
                                                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                                        placeholder="e.g., 123, Block 5, Lot 8">
                                                                </div>
                                                                <div>
                                                                    <label for="street" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                                        <i class="bi bi-signpost text-blue-500 mr-1"></i>
                                                                        Street
                                                                    </label>
                                                                    <input type="text" 
                                                                        id="street" 
                                                                        name="street" 
                                                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                                        placeholder="e.g., Mabini St.">
                                                                </div>
                                                            </div>

                                                            <!-- Subdivision/Village -->
                                                            <div>
                                                                <label for="subdi_village" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                                    <i class="bi bi-tree text-blue-500 mr-1"></i>
                                                                    Subdivision / Village
                                                                </label>
                                                                <input type="text" 
                                                                    id="subdi_village" 
                                                                    name="subdi_village" 
                                                                    class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                                    placeholder="e.g., Greenfields Subdivision">
                                                            </div>

                                                            <!-- Barangay & City/Municipality -->
                                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                                <div>
                                                                    <label for="barangay" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                                        <i class="bi bi-building text-blue-500 mr-1"></i>
                                                                        Barangay <span class="text-red-500">*</span>
                                                                    </label>
                                                                    <input type="text" 
                                                                        id="barangay" 
                                                                        name="barangay" 
                                                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                                        placeholder="e.g., Barangay San Juan"
                                                                        required>
                                                                </div>
                                                                <div>
                                                                    <label for="city_municipality" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                                        <i class="bi bi-city text-blue-500 mr-1"></i>
                                                                        City / Municipality <span class="text-red-500">*</span>
                                                                    </label>
                                                                    <input type="text" 
                                                                        id="city_municipality" 
                                                                        name="city_municipality" 
                                                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                                        placeholder="e.g., Quezon City"
                                                                        required>
                                                                </div>
                                                            </div>

                                                            <!-- Province & Zip Code -->
                                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                                <div>
                                                                    <label for="province" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                                        <i class="bi bi-map text-blue-500 mr-1"></i>
                                                                        Province <span class="text-red-500">*</span>
                                                                    </label>
                                                                    <input type="text" 
                                                                        id="province" 
                                                                        name="province" 
                                                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                                        placeholder="e.g., Metro Manila"
                                                                        required>
                                                                </div>
                                                                <div>
                                                                    <label for="zip_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                                        <i class="bi bi-mailbox text-blue-500 mr-1"></i>
                                                                        Zip Code
                                                                    </label>
                                                                    <input type="text" 
                                                                        id="zip_code" 
                                                                        name="zip_code" 
                                                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                                        placeholder="e.g., 1100">
                                                                </div>
                                                            </div>

                                                            <!-- Address Preview -->
                                                            <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-4 border border-gray-200 dark:border-gray-600">
                                                                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium mb-2">
                                                                    <i class="bi bi-eye mr-1"></i>
                                                                    Address Preview
                                                                </p>
                                                                <p id="addressPreview" class="text-sm text-gray-700 dark:text-gray-300">
                                                                    Complete the fields above to preview the address
                                                                </p>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Form Actions -->
                                                        <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                                                            <button type="button" 
                                                                    onclick="closeAddressModal()"
                                                                    class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-xl transition-all duration-200">
                                                                Cancel
                                                            </button>
                                                            <button type="submit" 
                                                                    class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                                                                <i class="bi bi-save mr-2"></i>
                                                                <span id="addressSubmitText">Save Address</span>
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="bi bi-person-x text-gray-400 text-2xl"></i>
                                </div>
                                <p class="text-gray-500 dark:text-gray-400">No personal data found.</p>
                                <a href="{{ route('myprofile.editPersonalData') }}" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 text-sm mt-2 inline-block">
                                    Add Personal Data
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Family Tab -->
            <div class="tab-content hidden" id="tab-family">
                <div class="space-y-6">
                    <!-- Family Background -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200/50 dark:border-gray-700/50 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-800">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                                        <i class="bi bi-people text-white"></i>
                                    </div>
                                    <h3 class="ml-3 text-lg font-semibold text-gray-900 dark:text-white">Family Background</h3>
                                </div>
                                @if($familyBackground)
                                    <a href="{{ route('myprofile.editFamilyDetails', $familyBackground->id) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-medium rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                                        <i class="bi bi-pencil-square mr-2"></i>
                                        Edit
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="p-6">
                            @if($familyBackground)
                                <div class="space-y-6">
                                    <!-- Spouse Information -->
                                    <div>
                                        <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                                            <span class="w-1 h-4 bg-indigo-500 rounded-full mr-2"></span>
                                            Spouse Information
                                        </h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 bg-gray-50 dark:bg-gray-700/30 rounded-xl p-4">
                                            <div>
                                                <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Full Name</label>
                                                <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                                                    @php
                                                        $spouseName = trim(
                                                            ($familyBackground->spouse_first_name ?? '') . ' ' .
                                                            ($familyBackground->spouse_middle_name ?? '') . ' ' .
                                                            ($familyBackground->spouse_last_name ?? '') . ' ' .
                                                            ($familyBackground->spouse_name_extension ?? '')
                                                        );
                                                    @endphp
                                                    {{ $spouseName ?: '—' }}
                                                </p>
                                            </div>
                                            <div>
                                                <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Occupation</label>
                                                <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">{{ $familyBackground->spouse_occupation ?? '—' }}</p>
                                            </div>
                                            <div>
                                                <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Employer/Business</label>
                                                <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">{{ $familyBackground->spouse_employer_business ?? '—' }}</p>
                                            </div>
                                            <div>
                                                <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Business Address</label>
                                                <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">{{ $familyBackground->spouse_business_address ?? '—' }}</p>
                                            </div>
                                            <div>
                                                <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Telephone No.</label>
                                                <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">{{ $familyBackground->spouse_telephone_no ?? '—' }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Father's Information -->
                                    <div>
                                        <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                                            <span class="w-1 h-4 bg-blue-500 rounded-full mr-2"></span>
                                            Father's Information
                                        </h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 bg-gray-50 dark:bg-gray-700/30 rounded-xl p-4">
                                            <div>
                                                <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Full Name</label>
                                                <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                                                    @php
                                                        $fatherName = trim(
                                                            ($familyBackground->father_first_name ?? '') . ' ' .
                                                            ($familyBackground->father_middle_name ?? '') . ' ' .
                                                            ($familyBackground->father_last_name ?? '') . ' ' .
                                                            ($familyBackground->father_name_extension ?? '')
                                                        );
                                                    @endphp
                                                    {{ $fatherName ?: '—' }}
                                                </p>
                                            </div>
                                            <div>
                                                <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name Extension</label>
                                                <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">{{ $familyBackground->father_name_extension ?? '—' }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Mother's Information -->
                                    <div>
                                        <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                                            <span class="w-1 h-4 bg-pink-500 rounded-full mr-2"></span>
                                            Mother's Information
                                        </h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 bg-gray-50 dark:bg-gray-700/30 rounded-xl p-4">
                                            <div>
                                                <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Full Name</label>
                                                <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                                                    @php
                                                        $motherName = trim(
                                                            ($familyBackground->mother_first_name ?? '') . ' ' .
                                                            ($familyBackground->mother_middle_name ?? '') . ' ' .
                                                            ($familyBackground->mother_last_name ?? '')
                                                        );
                                                    @endphp
                                                    {{ $motherName ?: '—' }}
                                                </p>
                                            </div>
                                            <div>
                                                <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Maiden Last Name</label>
                                                <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">{{ $familyBackground->mother_maiden_last_name ?? '—' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="bi bi-people text-gray-400 text-2xl"></i>
                                    </div>
                                    <p class="text-gray-500 dark:text-gray-400">No family background data found.</p>
                                    <a href="{{ route('myprofile.createFamilyDetails') }}" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 text-sm mt-2 inline-block">
                                        Add Family Details
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Children Section -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200/50 dark:border-gray-700/50 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-800">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gradient-to-r from-pink-500 to-rose-600 rounded-lg flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="fill-current text-white">
                                            <path d="M256 128C256 92.7 284.7 64 320 64C355.3 64 384 92.7 384 128C384 163.3 355.3 192 320 192C284.7 192 256 163.3 256 128zM304 448L304 544C304 561.7 289.7 576 272 576C254.3 576 240 561.7 240 544L240 351.8L219.1 385C209.7 400 189.9 404.4 175 395C160.1 385.6 155.5 365.9 164.9 351L204.8 287.7C229.7 248 273.2 224 320 224C366.8 224 410.3 248 435.2 287.6L475.1 351C484.5 366 480 385.7 465.1 395.1C450.2 404.5 430.4 400 421 385.1L400 351.8L400 544C400 561.7 385.7 576 368 576C350.3 576 336 561.7 336 544L336 448L304 448z"/>
                                        </svg>
                                    </div>
                                    <h3 class="ml-3 text-lg font-semibold text-gray-900 dark:text-white">Children</h3>
                                    <span class="ml-2 px-2.5 py-0.5 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs font-medium rounded-full">
                                        {{ $children->count() }}
                                    </span>
                                </div>
                                <a href="{{ route('myprofile.createChild') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white text-sm font-medium rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                                    <i class="bi bi-plus-lg mr-2"></i>
                                    Add Child
                                </a>
                            </div>
                        </div>
                        <div class="p-6">
                            @if($children->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead>
                                            <tr class="bg-gray-50 dark:bg-gray-700/50">
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">#</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date of Birth</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Sex</th>
                                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($children as $index => $child)
                                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors duration-150">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-pink-100 to-pink-200 dark:from-pink-900/30 dark:to-pink-800/30 flex items-center justify-center mr-3">
                                                                <span class="text-xs font-semibold text-pink-600 dark:text-pink-400">
                                                                    {{ substr($child->name, 0, 1) }}
                                                                </span>
                                                            </div>
                                                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $child->name }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $child->date_of_birth ? \Carbon\Carbon::parse($child->date_of_birth)->format('F d, Y') : '—' }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $child->sex ? $child->sex : '—' }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                        <div class="flex items-center justify-end space-x-2">
                                                            <button onclick="openEditChildModal({{ $child->id }})" 
                                                                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors"
                                                                    data-child-id="{{ $child->id }}">
                                                                <i class="bi bi-pencil-square"></i>
                                                            </button>
                                                            <button onclick="confirmDelete({{ $child->id }})" 
                                                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="fill-current text-gray-400 text-2xl">
                                            <path d="M256 128C256 92.7 284.7 64 320 64C355.3 64 384 92.7 384 128C384 163.3 355.3 192 320 192C284.7 192 256 163.3 256 128zM304 448L304 544C304 561.7 289.7 576 272 576C254.3 576 240 561.7 240 544L240 351.8L219.1 385C209.7 400 189.9 404.4 175 395C160.1 385.6 155.5 365.9 164.9 351L204.8 287.7C229.7 248 273.2 224 320 224C366.8 224 410.3 248 435.2 287.6L475.1 351C484.5 366 480 385.7 465.1 395.1C450.2 404.5 430.4 400 421 385.1L400 351.8L400 544C400 561.7 385.7 576 368 576C350.3 576 336 561.7 336 544L336 448L304 448z"/>
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 dark:text-gray-400">No children records found.</p>
                                    <a href="{{ route('myprofile.createChild') }}" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 text-sm mt-2 inline-block">
                                        Add Child
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Child Modal -->
            <div id="editChildModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <!-- Backdrop -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-80 transition-opacity"></div>
                
                <!-- Modal Container -->
                <div class="flex min-h-screen items-center justify-center p-4">
                    <div class="relative w-full max-w-2xl transform rounded-2xl bg-white dark:bg-gray-800 shadow-2xl transition-all">
                        <!-- Modal Header -->
                        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-pink-500 to-rose-600 rounded-xl flex items-center justify-center shadow-lg">
                                    <i class="bi bi-pencil-square text-white text-lg"></i>
                                </div>
                                <h3 class="ml-3 text-xl font-bold text-gray-900 dark:text-white" id="modal-title">
                                    Edit Child Information
                                </h3>
                            </div>
                            <button onclick="closeEditChildModal()" 
                                    class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 transition-colors">
                                <i class="bi bi-x-lg text-xl"></i>
                            </button>
                        </div>
                        
                        <!-- Modal Body -->
                        <div class="px-6 py-6">
                            <form id="editChildForm" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="space-y-6">
                                    <!-- Child Name -->
                                    <div>
                                        <label for="child_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            <i class="bi bi-person text-indigo-500 mr-1"></i>
                                            Full Name <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" 
                                            id="child_name" 
                                            name="name" 
                                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                                            placeholder="Enter child's full name"
                                            required>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Date of Birth -->
                                        <div>
                                            <label for="child_dob" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                <i class="bi bi-calendar-event text-pink-500 mr-1"></i>
                                                Date of Birth <span class="text-red-500">*</span>
                                            </label>
                                            <input type="date" 
                                                id="child_dob" 
                                                name="date_of_birth" 
                                                class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                                                required>
                                        </div>
                                        
                                        <!-- Sex -->
                                        <div>
                                            <label for="child_sex" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                <i class="bi bi-gender-ambiguous text-purple-500 mr-1"></i>
                                                Sex <span class="text-red-500">*</span>
                                            </label>
                                            <select id="child_sex" 
                                                    name="sex" 
                                                    class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                                                    required>
                                                <option value="">Select sex</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                                    <button type="button" 
                                            onclick="closeEditChildModal()"
                                            class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-xl transition-all duration-200">
                                        Cancel
                                    </button>
                                    <button type="submit" 
                                            class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                                        <i class="bi bi-save mr-2"></i>
                                        Update Child
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Education Tab -->
            <div class="tab-content hidden" id="tab-education">
                <div class="space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200/50 dark:border-gray-700/50 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-800">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gradient-to-r from-amber-500 to-orange-600 rounded-lg flex items-center justify-center">
                                        <i class="bi bi-mortarboard text-white"></i>
                                    </div>
                                    <h3 class="ml-3 text-lg font-semibold text-gray-900 dark:text-white">Educational Background</h3>
                                    <span class="ml-2 px-2.5 py-0.5 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs font-medium rounded-full">
                                        {{ $education->count() }}
                                    </span>
                                </div>
                                <button onclick="openAddEducationModal()" 
                                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white text-sm font-medium rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                                    <i class="bi bi-plus-lg mr-2"></i>
                                    Add Education
                                </button>
                            </div>
                        </div>
                        <div class="p-6">
                            @if($education && $education->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead>
                                            <tr class="bg-gray-50 dark:bg-gray-700/50">
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">#</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Level</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">School</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Degree/Course</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Year</th>
                                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($education as $index => $edu)
                                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors duration-150" data-education-id="{{ $edu->id }}">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="px-2 py-1 text-xs font-medium">
                                                            <x-education-badge :level="$edu->level" />
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-amber-100 to-amber-200 dark:from-amber-900/30 dark:to-amber-800/30 flex items-center justify-center mr-3">
                                                                <i class="bi bi-building text-amber-600 dark:text-amber-400 text-xs"></i>
                                                            </div>
                                                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $edu->school_name }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="font-semibold text-sm text-gray-500 dark:text-gray-400">{{ $edu->degree_course ?? '—' }}</span> <br>
                                                        <span class=" text-sm text-gray-500 dark:text-gray-400">{{ $edu->highest_level_earned }}</span>
                                                        @if($edu->scholarship_honors)
                                                            <br>
                                                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 border border-green-200 dark:border-green-800">
                                                                <i class="bi bi-award mr-1 text-green-500 dark:text-green-400"></i>
                                                                {{ $edu->scholarship_honors }}
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $edu->period_from }} - {{ $edu->period_to }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                        <div class="flex items-center justify-end space-x-2">
                                                            <button onclick="openEditEducationModal({{ $edu->id }})" 
                                                                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors"
                                                                    data-education-id="{{ $edu->id }}">
                                                                <i class="bi bi-pencil-square"></i>
                                                            </button>
                                                            <button onclick="confirmDeleteEducation({{ $edu->id }})" 
                                                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="bi bi-mortarboard text-gray-400 text-3xl"></i>
                                    </div>
                                    <p class="text-gray-500 dark:text-gray-400 font-medium">No educational background recorded</p>
                                    <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Click the "Add Education" button to get started</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add/Edit Education Modal -->
            <div id="educationModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <!-- Backdrop -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-80 transition-opacity"></div>
                
                <!-- Modal Container -->
                <div class="flex min-h-screen items-center justify-center p-4">
                    <div class="relative w-full max-w-2xl transform rounded-2xl bg-white dark:bg-gray-800 shadow-2xl transition-all">
                        <!-- Modal Header -->
                        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-amber-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg">
                                    <i class="bi bi-mortarboard text-white text-lg"></i>
                                </div>
                                <h3 class="ml-3 text-xl font-bold text-gray-900 dark:text-white" id="educationModalTitle">
                                    Add Education
                                </h3>
                            </div>
                            <button onclick="closeEducationModal()" 
                                    class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 transition-colors">
                                <i class="bi bi-x-lg text-xl"></i>
                            </button>
                        </div>
                        
                        <!-- Modal Body -->
                        <div class="px-6 py-6">
                            <form id="educationForm" method="POST">
                                @csrf
                                <input type="hidden" id="education_method" name="_method" value="POST">
                                <input type="hidden" id="education_id" name="education_id" value="">
                                
                                <div class="space-y-6">
                                    <!-- Education Level -->
                                    <div>
                                        <label for="level" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            <i class="bi bi-layers text-amber-500 mr-1"></i>
                                            Education Level <span class="text-red-500">*</span>
                                        </label>
                                        <select id="level" 
                                                name="level" 
                                                class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200"
                                                required>
                                            <option value="">Select education level</option>
                                            <option value="elementary">Elementary</option>
                                            <option value="high_school">High School</option>
                                            <option value="senior_high_school">Senior High School</option>
                                            <option value="college">College</option>
                                            <option value="post_graduate">Post Graduate</option>
                                            <option value="vocational">Vocational</option>
                                        </select>
                                    </div>

                                    <!-- degree_course -->
                                    <div>
                                        <label for="degree_course" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            <i class="bi bi-book text-amber-500 mr-1"></i>
                                            Course / Degree
                                        </label>
                                        <input type="text" 
                                            id="degree_course" 
                                            name="degree_course" 
                                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200"
                                            placeholder="Enter course or degree">
                                    </div>

                                    <!-- School Name -->
                                    <div>
                                        <label for="school_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            <i class="bi bi-building text-amber-500 mr-1"></i>
                                            School Name <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" 
                                            id="school_name" 
                                            name="school_name" 
                                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200"
                                            placeholder="Enter school name"
                                            required>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        <!-- Year From -->
                                        <div>
                                            <label for="period_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                <i class="bi bi-calendar-start text-amber-500 mr-1"></i>
                                                Year From <span class="text-red-500">*</span>
                                            </label>
                                            <input type="number" 
                                                id="period_from" 
                                                name="period_from" 
                                                min="1950" 
                                                max="{{ date('Y') }}"
                                                class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200"
                                                placeholder="YYYY"
                                                required>
                                        </div>
                                        
                                        <!-- Year To -->
                                        <div>
                                            <label for="period_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                <i class="bi bi-calendar-end text-amber-500 mr-1"></i>
                                                Year To <span class="text-red-500">*</span>
                                            </label>
                                            <input type="number" 
                                                id="period_to" 
                                                name="period_to" 
                                                min="1950" 
                                                max="{{ date('Y') }}"
                                                class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200"
                                                placeholder="YYYY"
                                                required>
                                        </div>

                                        <!-- Year Graduated -->
                                        <div>
                                            <label for="year_graduated" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                <i class="bi bi-calendar-end text-amber-500 mr-1"></i>
                                                Year Graduated 
                                            </label>
                                            <input type="number" 
                                                id="year_graduated" 
                                                name="year_graduated" 
                                                min="1950" 
                                                max="{{ date('Y') }}"
                                                class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200"
                                                placeholder="YYYY"
                                                >
                                        </div>
                                    </div>

                                    <!-- Highest Level Earned -->
                                    <div>
                                        <label for="highest_level_earned" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            <i class="bi bi-award text-amber-500 mr-1"></i>
                                            Highest Level Earned
                                        </label>
                                        <input type="text" 
                                            id="highest_level_earned" 
                                            name="highest_level_earned" 
                                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200"
                                            placeholder="e.g., Graduate, 30 (units)">
                                    </div>

                                    <!-- Honors Received -->
                                    <div>
                                        <label for="scholarship_honors" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            <i class="bi bi-award text-amber-500 mr-1"></i>
                                            Honors Received (Optional)
                                        </label>
                                        <input type="text" 
                                            id="scholarship_honors" 
                                            name="scholarship_honors" 
                                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200"
                                            placeholder="e.g., Cum Laude, With Honors">
                                    </div>
                                </div>
                                
                                <!-- Form Actions -->
                                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                                    <button type="button" 
                                            onclick="closeEducationModal()"
                                            class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-xl transition-all duration-200">
                                        Cancel
                                    </button>
                                    <button type="submit" 
                                            class="px-6 py-2.5 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                                        <i class="bi bi-save mr-2"></i>
                                        <span id="educationSubmitText">Save Education</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Eligibilities Tab -->
            <div class="tab-content hidden" id="tab-eligibilities">
                <div class="space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200/50 dark:border-gray-700/50 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-800">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gradient-to-r from-yellow-500 to-amber-600 rounded-lg flex items-center justify-center">
                                        <i class="bi bi-award text-white"></i>
                                    </div>
                                    <h3 class="ml-3 text-lg font-semibold text-gray-900 dark:text-white">Eligibilities</h3>
                                    <span class="ml-2 px-2.5 py-0.5 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs font-medium rounded-full">
                                        {{ $eligibilities->count() }}
                                    </span>
                                </div>
                                <button onclick="openAddEligibilityModal()" 
                                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white text-sm font-medium rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                                    <i class="bi bi-plus-lg mr-2"></i>
                                    Add Eligibility
                                </button>
                            </div>
                        </div>
                        <div class="p-6">
                            @if($eligibilities && $eligibilities->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead>
                                            <tr class="bg-gray-50 dark:bg-gray-700/50">
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">#</th>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Eligibility</th>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Rating</th>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Examination</th>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">License</th>
                                                <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($eligibilities as $index => $eligibility)
                                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors duration-150" data-eligibility-id="{{ $eligibility->id }}">
                                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                                                    <td class="px-4 py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-yellow-100 to-amber-200 dark:from-yellow-900/30 dark:to-amber-800/30 flex items-center justify-center mr-3">
                                                                <i class="bi bi-award text-yellow-600 dark:text-yellow-400 text-xs"></i>
                                                            </div>
                                                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $eligibility->career_service }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                        @if($eligibility->rating)
                                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400">
                                                                {{ $eligibility->rating }}
                                                            </span>
                                                        @else
                                                            <span class="text-gray-400 dark:text-gray-500">—</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                        @if($eligibility->examination_date)
                                                            <div>
                                                                <span>{{ \Carbon\Carbon::parse($eligibility->examination_date)->format('M d, Y') }}</span>
                                                                @if($eligibility->examination_place)
                                                                    <span class="text-xs text-gray-400 dark:text-gray-500 block">{{ $eligibility->examination_place }}</span>
                                                                @endif
                                                            </div>
                                                        @else
                                                            <span class="text-gray-400 dark:text-gray-500">—</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                        @if($eligibility->license_number)
                                                            <div>
                                                                <span class="font-medium text-gray-900 dark:text-white">{{ $eligibility->license_number }}</span>
                                                                @if($eligibility->license_date_validity)
                                                                    <span class="text-xs text-gray-400 dark:text-gray-500 block">
                                                                        Valid until: {{ \Carbon\Carbon::parse($eligibility->license_date_validity)->format('M d, Y') }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        @else
                                                            <span class="text-gray-400 dark:text-gray-500">—</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                        <div class="flex items-center justify-end space-x-2">
                                                            <button onclick="openEditEligibilityModal({{ $eligibility->id }})" 
                                                                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors"
                                                                    data-eligibility-id="{{ $eligibility->id }}">
                                                                <i class="bi bi-pencil-square"></i>
                                                            </button>
                                                            <button onclick="confirmDeleteEligibility({{ $eligibility->id }})" 
                                                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="bi bi-award text-gray-400 text-3xl"></i>
                                    </div>
                                    <p class="text-gray-500 dark:text-gray-400 font-medium">No eligibilities recorded</p>
                                    <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Click the "Add Eligibility" button to get started</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add/Edit Eligibility Modal -->
            <div id="eligibilityModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <!-- Backdrop -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-80 transition-opacity"></div>
                
                <!-- Modal Container -->
                <div class="flex min-h-screen items-center justify-center p-4">
                    <div class="relative w-full max-w-3xl transform rounded-2xl bg-white dark:bg-gray-800 shadow-2xl transition-all">
                        <!-- Modal Header -->
                        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-yellow-500 to-amber-600 rounded-xl flex items-center justify-center shadow-lg">
                                    <i class="bi bi-award text-white text-lg"></i>
                                </div>
                                <h3 class="ml-3 text-xl font-bold text-gray-900 dark:text-white" id="eligibilityModalTitle">
                                    Add Eligibility
                                </h3>
                            </div>
                            <button onclick="closeEligibilityModal()" 
                                    class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 transition-colors">
                                <i class="bi bi-x-lg text-xl"></i>
                            </button>
                        </div>
                        
                        <!-- Modal Body -->
                        <div class="px-6 py-6 max-h-[70vh] overflow-y-auto">
                            <form id="eligibilityForm" method="POST">
                                @csrf
                                <input type="hidden" id="eligibility_method" name="_method" value="POST">
                                <input type="hidden" id="eligibility_id" name="eligibility_id" value="">
                                
                                <div class="space-y-6">
                                    <!-- Career Service & Order -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="career_service" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                <i class="bi bi-award text-yellow-500 mr-1"></i>
                                                Career Service / Eligibility <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" 
                                                id="career_service" 
                                                name="career_service" 
                                                class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all duration-200"
                                                placeholder="e.g., Civil Service Professional"
                                                required>
                                        </div>
                                        <div>
                                            <label for="eligibility_order" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                <i class="bi bi-sort-numeric-down text-yellow-500 mr-1"></i>
                                                Display Order
                                            </label>
                                            <input type="number" 
                                                id="eligibility_order" 
                                                name="order" 
                                                min="1"
                                                class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all duration-200"
                                                placeholder="1, 2, 3...">
                                        </div>
                                    </div>

                                    <!-- Rating & License -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="rating" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                <i class="bi bi-star text-yellow-500 mr-1"></i>
                                                Rating
                                            </label>
                                            <input type="text" 
                                                id="rating" 
                                                name="rating" 
                                                class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all duration-200"
                                                placeholder="e.g., 85.50">
                                        </div>
                                        <div>
                                            <label for="license_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                <i class="bi bi-card-text text-yellow-500 mr-1"></i>
                                                License Number
                                            </label>
                                            <input type="text" 
                                                id="license_number" 
                                                name="license_number" 
                                                class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all duration-200"
                                                placeholder="e.g., 123456-7890">
                                        </div>
                                    </div>

                                    <!-- Examination Date & Place -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="examination_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                <i class="bi bi-calendar-event text-yellow-500 mr-1"></i>
                                                Examination Date
                                            </label>
                                            <input type="date" 
                                                id="examination_date" 
                                                name="examination_date" 
                                                class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all duration-200">
                                        </div>
                                        <div>
                                            <label for="examination_place" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                <i class="bi bi-geo-alt text-yellow-500 mr-1"></i>
                                                Examination Place
                                            </label>
                                            <input type="text" 
                                                id="examination_place" 
                                                name="examination_place" 
                                                class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all duration-200"
                                                placeholder="e.g., Manila, Philippines">
                                        </div>
                                    </div>

                                    <!-- License Date Validity -->
                                    <div>
                                        <label for="license_date_validity" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            <i class="bi bi-calendar-check text-yellow-500 mr-1"></i>
                                            License Date Validity
                                        </label>
                                        <input type="date" 
                                            id="license_date_validity" 
                                            name="license_date_validity" 
                                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all duration-200">
                                    </div>
                                </div>
                                
                                <!-- Form Actions -->
                                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                                    <button type="button" 
                                            onclick="closeEligibilityModal()"
                                            class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-xl transition-all duration-200">
                                        Cancel
                                    </button>
                                    <button type="submit" 
                                            class="px-6 py-2.5 bg-gradient-to-r from-yellow-500 to-amber-600 hover:from-yellow-600 hover:to-amber-700 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                                        <i class="bi bi-save mr-2"></i>
                                        <span id="eligibilitySubmitText">Save Eligibility</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Work Tab -->
            <div class="tab-content hidden" id="tab-work">
                <div class="space-y-6">
                    <!-- Work Experience -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200/50 dark:border-gray-700/50 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-800">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gradient-to-r from-cyan-500 to-sky-600 rounded-lg flex items-center justify-center">
                                        <i class="bi bi-briefcase text-white"></i>
                                    </div>
                                    <h3 class="ml-3 text-lg font-semibold text-gray-900 dark:text-white">Work Experience</h3>
                                    <span class="ml-2 px-2.5 py-0.5 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs font-medium rounded-full">
                                        {{ $workExperiences->count() }}
                                    </span>
                                </div>
                                <button onclick="openAddWorkModal()" 
                                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white text-sm font-medium rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                                    <i class="bi bi-plus-lg mr-2"></i>
                                    Add Work Experience
                                </button>
                            </div>
                        </div>
                        <div class="p-6">
                            @if($workExperiences && $workExperiences->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead>
                                            <tr class="bg-gray-50 dark:bg-gray-700/50">
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">#</th>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Position</th>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Department/Agency</th>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Period</th>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Salary</th>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                                <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($workExperiences as $index => $work)
                                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors duration-150" data-work-id="{{ $work->id }}">
                                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                                                    <td class="px-4 py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-cyan-100 to-cyan-200 dark:from-cyan-900/30 dark:to-cyan-800/30 flex items-center justify-center mr-3">
                                                                <i class="bi bi-person-badge text-cyan-600 dark:text-cyan-400 text-xs"></i>
                                                            </div>
                                                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $work->position_title }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $work->department_agency_office }}
                                                    </td>
                                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $work->inclusive_from->format('M Y') }} - 
                                                        {{ $work->inclusive_to ? $work->inclusive_to->format('M Y') : 'Present' }}
                                                    </td>
                                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                        @if($work->monthly_salary)
                                                            ₱{{ number_format($work->monthly_salary, 2) }}
                                                            @if($work->salary_grade)
                                                                <span class="text-xs text-gray-400 dark:text-gray-500 block">SG: {{ $work->salary_grade }}</span>
                                                            @endif
                                                        @else
                                                            —
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-4 whitespace-nowrap">
                                                        <div class="flex items-center space-x-1">
                                                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $work->is_gov == 'Yes' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' : 'bg-gray-100 dark:bg-gray-700/30 text-gray-700 dark:text-gray-400' }}">
                                                                {{ $work->is_gov == 'Yes' ? 'Government' : 'Private' }}
                                                            </span>
                                                            @if($work->status_of_appointment)
                                                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400">
                                                                    {{ ucfirst(str_replace('_', ' ', $work->status_of_appointment)) }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                        <div class="flex items-center justify-end space-x-2">
                                                            <button onclick="openEditWorkModal({{ $work->id }})" 
                                                                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors"
                                                                    data-work-id="{{ $work->id }}">
                                                                <i class="bi bi-pencil-square"></i>
                                                            </button>
                                                            <button onclick="confirmDeleteWork({{ $work->id }})" 
                                                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="bi bi-briefcase text-gray-400 text-3xl"></i>
                                    </div>
                                    <p class="text-gray-500 dark:text-gray-400 font-medium">No work experience recorded</p>
                                    <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Click the "Add Work Experience" button to get started</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add/Edit Work Experience Modal -->
            <div id="workModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <!-- Backdrop -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-80 transition-opacity"></div>
                
                <!-- Modal Container -->
                <div class="flex min-h-screen items-center justify-center p-4">
                    <div class="relative w-full max-w-3xl transform rounded-2xl bg-white dark:bg-gray-800 shadow-2xl transition-all">
                        <!-- Modal Header -->
                        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-cyan-500 to-sky-600 rounded-xl flex items-center justify-center shadow-lg">
                                    <i class="bi bi-briefcase text-white text-lg"></i>
                                </div>
                                <h3 class="ml-3 text-xl font-bold text-gray-900 dark:text-white" id="workModalTitle">
                                    Add Work Experience
                                </h3>
                            </div>
                            <button onclick="closeWorkModal()" 
                                    class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 transition-colors">
                                <i class="bi bi-x-lg text-xl"></i>
                            </button>
                        </div>
                        
                        <!-- Modal Body -->
                        <div class="px-6 py-6 max-h-[70vh] overflow-y-auto">
                            <form id="workForm" method="POST">
                                @csrf
                                <input type="hidden" id="work_method" name="_method" value="POST">
                                <input type="hidden" id="work_id" name="work_id" value="">
                                
                                <div class="space-y-6">
                                    <!-- Position Title & Order -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="position_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                <i class="bi bi-person-badge text-cyan-500 mr-1"></i>
                                                Position Title <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" 
                                                id="position_title" 
                                                name="position_title" 
                                                class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all duration-200"
                                                placeholder="e.g., Senior Software Engineer"
                                                required>
                                        </div>
                                        <div>
                                            <label for="order" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                <i class="bi bi-sort-numeric-down text-cyan-500 mr-1"></i>
                                                Display Order
                                            </label>
                                            <input type="number" 
                                                id="order" 
                                                name="order" 
                                                min="1"
                                                class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all duration-200"
                                                placeholder="1, 2, 3...">
                                        </div>
                                    </div>

                                    <!-- Department/Agency -->
                                    <div>
                                        <label for="department_agency_office" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            <i class="bi bi-building text-cyan-500 mr-1"></i>
                                            Department / Agency / Office <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" 
                                            id="department_agency_office" 
                                            name="department_agency_office" 
                                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all duration-200"
                                            placeholder="e.g., Department of Technology"
                                            required>
                                    </div>

                                    <!-- Inclusive Dates -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="inclusive_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                <i class="bi bi-calendar-start text-cyan-500 mr-1"></i>
                                                Date From <span class="text-red-500">*</span>
                                            </label>
                                            <input type="date" 
                                                id="inclusive_from" 
                                                name="inclusive_from" 
                                                class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all duration-200"
                                                required>
                                        </div>
                                        <div>
                                            <label for="inclusive_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                <i class="bi bi-calendar-end text-cyan-500 mr-1"></i>
                                                Date To
                                                <span class="text-xs text-gray-400 dark:text-gray-500">(Leave blank if present)</span>
                                            </label>
                                            <input type="date" 
                                                id="inclusive_to" 
                                                name="inclusive_to" 
                                                class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all duration-200">
                                        </div>
                                    </div>

                                    <!-- Salary Information -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="monthly_salary" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                <i class="bi bi-cash text-cyan-500 mr-1"></i>
                                                Monthly Salary
                                            </label>
                                            <div class="relative">
                                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400">₱</span>
                                                <input type="number" 
                                                    id="monthly_salary" 
                                                    name="monthly_salary" 
                                                    step="0.01"
                                                    min="0"
                                                    class="w-full pl-8 pr-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all duration-200"
                                                    placeholder="0.00">
                                            </div>
                                        </div>
                                        <div>
                                            <label for="salary_grade" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                <i class="bi bi-graph-up text-cyan-500 mr-1"></i>
                                                Salary Grade
                                            </label>
                                            <input type="text" 
                                                id="salary_grade" 
                                                name="salary_grade" 
                                                class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all duration-200"
                                                placeholder="e.g., SG-12">
                                        </div>
                                    </div>

                                    <!-- Status & Type -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="status_of_appointment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                <i class="bi bi-clipboard-check text-cyan-500 mr-1"></i>
                                                Status of Appointment
                                            </label>
                                            <select id="status_of_appointment" 
                                                    name="status_of_appointment" 
                                                    class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all duration-200">
                                                <option value="">Select status</option>
                                                <option value="permanent">Permanent</option>
                                                <option value="coterminus">Coterminus</option>
                                                <option value="temporary">Temporary</option>
                                                <option value="contractual">Contractual</option>
                                                <option value="job_order">Regular</option>
                                                <option value="casual">Casual</option>
                                                <option value="probationary">Probationary</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="is_gov" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                <i class="bi bi-building text-cyan-500 mr-1"></i>
                                                Government Service?
                                            </label>
                                            <select id="is_gov" 
                                                    name="is_gov" 
                                                    class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all duration-200">
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Form Actions -->
                                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                                    <button type="button" 
                                            onclick="closeWorkModal()"
                                            class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-xl transition-all duration-200">
                                        Cancel
                                    </button>
                                    <button type="submit" 
                                            class="px-6 py-2.5 bg-gradient-to-r from-cyan-500 to-sky-600 hover:from-cyan-600 hover:to-sky-700 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                                        <i class="bi bi-save mr-2"></i>
                                        <span id="workSubmitText">Save Work Experience</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Voluntary Works Tab -->
            <div class="tab-content hidden" id="tab-volwork">
                <div class="space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200/50 dark:border-gray-700/50 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-800">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gradient-to-r from-violet-500 to-purple-600 rounded-lg flex items-center justify-center">
                                        <i class="bi bi-heart text-white"></i>
                                    </div>
                                    <h3 class="ml-3 text-lg font-semibold text-gray-900 dark:text-white">Voluntary Works</h3>
                                    <span class="ml-2 px-2.5 py-0.5 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs font-medium rounded-full">
                                        {{ $voluntaryWorks->count() }}
                                    </span>
                                </div>
                                <button onclick="openAddVoluntaryWorkModal()" 
                                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white text-sm font-medium rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                                    <i class="bi bi-plus-lg mr-2"></i>
                                    Add Voluntary Work
                                </button>
                            </div>
                        </div>
                        <div class="p-6">
                            @if($voluntaryWorks && $voluntaryWorks->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead>
                                            <tr class="bg-gray-50 dark:bg-gray-700/50">
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">#</th>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Organization</th>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Position</th>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Period</th>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Hours</th>
                                                <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($voluntaryWorks as $index => $work)
                                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors duration-150" data-voluntary-work-id="{{ $work->id }}">
                                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                                                    <td class="px-4 py-4">
                                                        <div class="flex items-start">
                                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-violet-100 to-purple-200 dark:from-violet-900/30 dark:to-purple-800/30 flex items-center justify-center mr-3 flex-shrink-0 mt-0.5">
                                                                <i class="bi bi-building text-violet-600 dark:text-violet-400 text-xs"></i>
                                                            </div>
                                                            <div>
                                                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $work->organization_name }}</span>
                                                                @if($work->organization_address)
                                                                    <span class="text-xs text-gray-400 dark:text-gray-500 block">{{ $work->organization_address }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $work->position_nature_of_work ?? '—' }}
                                                    </td>
                                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                        @if($work->inclusive_from)
                                                            <div>
                                                                <span>{{ \Carbon\Carbon::parse($work->inclusive_from)->format('M d, Y') }}</span>
                                                                @if($work->inclusive_to)
                                                                    <span class="text-xs text-gray-400 dark:text-gray-500 block">
                                                                        to {{ \Carbon\Carbon::parse($work->inclusive_to)->format('M d, Y') }}
                                                                    </span>
                                                                @else
                                                                    <span class="text-xs text-green-600 dark:text-green-400 block font-medium">
                                                                        (Present)
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        @else
                                                            <span class="text-gray-400 dark:text-gray-500">—</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                        @if($work->number_of_hours)
                                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400">
                                                                {{ $work->number_of_hours }} hrs
                                                            </span>
                                                        @else
                                                            <span class="text-gray-400 dark:text-gray-500">—</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                        <div class="flex items-center justify-end space-x-2">
                                                            <button onclick="openEditVoluntaryWorkModal({{ $work->id }})" 
                                                                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors"
                                                                    data-voluntary-work-id="{{ $work->id }}">
                                                                <i class="bi bi-pencil-square"></i>
                                                            </button>
                                                            <button onclick="confirmDeleteVoluntaryWork({{ $work->id }})" 
                                                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="bi bi-heart text-gray-400 text-3xl"></i>
                                    </div>
                                    <p class="text-gray-500 dark:text-gray-400 font-medium">No voluntary works recorded</p>
                                    <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Click the "Add Voluntary Work" button to get started</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add/Edit Voluntary Work Modal -->
            <div id="voluntaryWorkModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <!-- Backdrop -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-80 transition-opacity"></div>
                
                <!-- Modal Container -->
                <div class="flex min-h-screen items-center justify-center p-4">
                    <div class="relative w-full max-w-3xl transform rounded-2xl bg-white dark:bg-gray-800 shadow-2xl transition-all">
                        <!-- Modal Header -->
                        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-violet-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                                    <i class="bi bi-heart text-white text-lg"></i>
                                </div>
                                <h3 class="ml-3 text-xl font-bold text-gray-900 dark:text-white" id="voluntaryWorkModalTitle">
                                    Add Voluntary Work
                                </h3>
                            </div>
                            <button onclick="closeVoluntaryWorkModal()" 
                                    class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 transition-colors">
                                <i class="bi bi-x-lg text-xl"></i>
                            </button>
                        </div>
                        
                        <!-- Modal Body -->
                        <div class="px-6 py-6 max-h-[70vh] overflow-y-auto">
                            <form id="voluntaryWorkForm" method="POST">
                                @csrf
                                <input type="hidden" id="voluntary_work_method" name="_method" value="POST">
                                <input type="hidden" id="voluntary_work_id" name="voluntary_work_id" value="">
                                
                                <div class="space-y-6">
                                    <!-- Organization Name & Order -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="organization_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                <i class="bi bi-building text-violet-500 mr-1"></i>
                                                Organization Name <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" 
                                                id="organization_name" 
                                                name="organization_name" 
                                                class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all duration-200"
                                                placeholder="e.g., Red Cross Philippines"
                                                required>
                                        </div>
                                        <div>
                                            <label for="voluntary_work_order" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                <i class="bi bi-sort-numeric-down text-violet-500 mr-1"></i>
                                                Display Order
                                            </label>
                                            <input type="number" 
                                                id="voluntary_work_order" 
                                                name="order" 
                                                min="1"
                                                class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all duration-200"
                                                placeholder="1, 2, 3...">
                                        </div>
                                    </div>

                                    <!-- Organization Address -->
                                    <div>
                                        <label for="organization_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            <i class="bi bi-geo-alt text-violet-500 mr-1"></i>
                                            Organization Address
                                        </label>
                                        <input type="text" 
                                            id="organization_address" 
                                            name="organization_address" 
                                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all duration-200"
                                            placeholder="e.g., 123 Main St, City">
                                    </div>

                                    <!-- Position & Nature of Work -->
                                    <div>
                                        <label for="position_nature_of_work" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            <i class="bi bi-person-badge text-violet-500 mr-1"></i>
                                            Position / Nature of Work <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" 
                                            id="position_nature_of_work" 
                                            name="position_nature_of_work" 
                                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all duration-200"
                                            placeholder="e.g., Volunteer Coordinator"
                                            required>
                                    </div>

                                    <!-- Inclusive Dates -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="voluntary_inclusive_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                <i class="bi bi-calendar-start text-violet-500 mr-1"></i>
                                                Date From <span class="text-red-500">*</span>
                                            </label>
                                            <input type="date" 
                                                id="voluntary_inclusive_from" 
                                                name="inclusive_from" 
                                                class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all duration-200"
                                                required>
                                        </div>
                                        <div>
                                            <label for="voluntary_inclusive_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                <i class="bi bi-calendar-end text-violet-500 mr-1"></i>
                                                Date To
                                                <span class="text-xs text-gray-400 dark:text-gray-500">(Leave blank if present)</span>
                                            </label>
                                            <input type="date" 
                                                id="voluntary_inclusive_to" 
                                                name="inclusive_to" 
                                                class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all duration-200">
                                        </div>
                                    </div>

                                    <!-- Number of Hours -->
                                    <div>
                                        <label for="voluntary_number_of_hours" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            <i class="bi bi-clock text-violet-500 mr-1"></i>
                                            Number of Hours
                                        </label>
                                        <input type="number" 
                                            id="voluntary_number_of_hours" 
                                            name="number_of_hours" 
                                            step="0.5"
                                            min="0"
                                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all duration-200"
                                            placeholder="e.g., 40">
                                    </div>
                                </div>
                                
                                <!-- Form Actions -->
                                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                                    <button type="button" 
                                            onclick="closeVoluntaryWorkModal()"
                                            class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-xl transition-all duration-200">
                                        Cancel
                                    </button>
                                    <button type="submit" 
                                            class="px-6 py-2.5 bg-gradient-to-r from-violet-500 to-purple-600 hover:from-violet-600 hover:to-purple-700 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                                        <i class="bi bi-save mr-2"></i>
                                        <span id="voluntaryWorkSubmitText">Save Voluntary Work</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trainings Tab -->
            <div class="tab-content hidden" id="tab-trainings">
                <div class="space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200/50 dark:border-gray-700/50 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-800">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gradient-to-r from-teal-500 to-green-600 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-dumbbell text-white"></i>
                                    </div>
                                    <h3 class="ml-3 text-lg font-semibold text-gray-900 dark:text-white">Trainings</h3>
                                    <span class="ml-2 px-2.5 py-0.5 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs font-medium rounded-full">
                                        {{ $trainings->count() }}
                                    </span>
                                </div>
                                <button onclick="openAddTrainingModal()" 
                                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white text-sm font-medium rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                                    <i class="bi bi-plus-lg mr-2"></i>
                                    Add Training
                                </button>
                            </div>
                        </div>
                        <div class="p-6">
                            @if($trainings && $trainings->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead>
                                            <tr class="bg-gray-50 dark:bg-gray-700/50">
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">#</th>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Training Program</th>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Conducted By</th>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Period</th>
                                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Hours</th>
                                                <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($trainings as $index => $training)
                                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors duration-150" data-training-id="{{ $training->id }}">
                                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                                                    <td class="px-4 py-4">
                                                        <div class="flex items-start">
                                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-teal-100 to-green-200 dark:from-teal-900/30 dark:to-green-800/30 flex items-center justify-center mr-3 flex-shrink-0 mt-0.5">
                                                                <i class="fas fa-dumbbell text-teal-600 dark:text-teal-400 text-xs"></i>
                                                            </div>
                                                            <div>
                                                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $training->title_of_program }}</span>
                                                                <br>
                                                                <div>
                                                                    @if($training->has_type)
                                                                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $training->type_badge_color }}">
                                                                            {{ $training->formatted_type }}
                                                                        </span>
                                                                    @else
                                                                        <span class="text-gray-400 dark:text-gray-500">—</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $training->conducted_by ?? '—' }}
                                                    </td>
                                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                        @if($training->inclusive_from)
                                                            <div>
                                                                <span>{{ $training->inclusive_from->format('M d, Y') }}</span>
                                                                @if($training->inclusive_to)
                                                                    <span class="text-xs text-gray-400 dark:text-gray-500 block">
                                                                        to {{ $training->inclusive_to->format('M d, Y') }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        @else
                                                            <span class="text-gray-400 dark:text-gray-500">—</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                        @if($training->number_of_hours)
                                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-teal-100 dark:bg-teal-900/30 text-teal-700 dark:text-teal-400">
                                                                {{ $training->number_of_hours }} hrs
                                                            </span>
                                                        @else
                                                            <span class="text-gray-400 dark:text-gray-500">—</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                        <div class="flex items-center justify-end space-x-2">
                                                            <button onclick="openEditTrainingModal({{ $training->id }})" 
                                                                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors"
                                                                    data-training-id="{{ $training->id }}">
                                                                <i class="bi bi-pencil-square"></i>
                                                            </button>
                                                            <button onclick="confirmDeleteTraining({{ $training->id }})" 
                                                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-dumbbell text-gray-400 text-3xl"></i>
                                    </div>
                                    <p class="text-gray-500 dark:text-gray-400 font-medium">No trainings recorded</p>
                                    <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Click the "Add Training" button to get started</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add/Edit Training Modal -->
            <div id="trainingModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <!-- Backdrop -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-80 transition-opacity"></div>
                
                <!-- Modal Container -->
                <div class="flex min-h-screen items-center justify-center p-4">
                    <div class="relative w-full max-w-3xl transform rounded-2xl bg-white dark:bg-gray-800 shadow-2xl transition-all">
                        <!-- Modal Header -->
                        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-teal-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                                    <i class="fas fa-dumbbell text-white text-lg"></i>
                                </div>
                                <h3 class="ml-3 text-xl font-bold text-gray-900 dark:text-white" id="trainingModalTitle">
                                    Add Training
                                </h3>
                            </div>
                            <button onclick="closeTrainingModal()" 
                                    class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 transition-colors">
                                <i class="bi bi-x-lg text-xl"></i>
                            </button>
                        </div>
                        
                        <!-- Modal Body -->
                        <div class="px-6 py-6 max-h-[70vh] overflow-y-auto">
                            <form id="trainingForm" method="POST">
                                @csrf
                                <input type="hidden" id="training_method" name="_method" value="POST">
                                <input type="hidden" id="training_id" name="training_id" value="">
                                
                                <div class="space-y-6">
                                    <!-- Title & Order -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="title_of_program" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                <i class="fas fa-dumbbell text-teal-500 mr-1"></i>
                                                Title of Program <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" 
                                                id="title_of_program" 
                                                name="title_of_program" 
                                                class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200"
                                                placeholder="e.g., Leadership Development Program"
                                                required>
                                        </div>
                                        <div>
                                            <label for="training_order" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                <i class="bi bi-sort-numeric-down text-teal-500 mr-1"></i>
                                                Display Order
                                            </label>
                                            <input type="number" 
                                                id="training_order" 
                                                name="order" 
                                                min="1"
                                                class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200"
                                                placeholder="1, 2, 3...">
                                        </div>
                                    </div>

                                    <!-- Conducted By & Type -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="conducted_by" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                <i class="bi bi-building text-teal-500 mr-1"></i>
                                                Conducted By <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" 
                                                id="conducted_by" 
                                                name="conducted_by" 
                                                class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200"
                                                placeholder="e.g., Civil Service Commission"
                                                required>
                                        </div>
                                        <div>
                                            <label for="type_of_ld" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                <i class="bi bi-tags text-teal-500 mr-1"></i>
                                                Type of Learning & Development
                                            </label>
                                            <select id="type_of_ld" 
                                                    name="type_of_ld" 
                                                    class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200">
                                                <option value="">Select type</option>
                                                <option value="managerial">Managerial</option>
                                                <option value="supervisory">Supervisory</option>
                                                <option value="technical">Technical</option>
                                                <option value="behavioral">Behavioral</option>
                                                <option value="leadership">Leadership</option>
                                                <option value="functional">Functional</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Inclusive Dates -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="inclusive_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                <i class="bi bi-calendar-start text-teal-500 mr-1"></i>
                                                Date From <span class="text-red-500">*</span>
                                            </label>
                                            <input type="date" 
                                                id="inclusive_from" 
                                                name="inclusive_from" 
                                                class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200"
                                                required>
                                        </div>
                                        <div>
                                            <label for="inclusive_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                <i class="bi bi-calendar-end text-teal-500 mr-1"></i>
                                                Date To
                                                <span class="text-xs text-gray-400 dark:text-gray-500">(Leave blank if same day)</span>
                                            </label>
                                            <input type="date" 
                                                id="inclusive_to" 
                                                name="inclusive_to" 
                                                class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200">
                                        </div>
                                    </div>

                                    <!-- Number of Hours -->
                                    <div>
                                        <label for="number_of_hours" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            <i class="bi bi-clock text-teal-500 mr-1"></i>
                                            Number of Hours <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" 
                                            id="number_of_hours" 
                                            name="number_of_hours" 
                                            step="0.5"
                                            min="0.5"
                                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200"
                                            placeholder="e.g., 8, 16, 24"
                                            required>
                                    </div>
                                </div>
                                
                                <!-- Form Actions -->
                                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                                    <button type="button" 
                                            onclick="closeTrainingModal()"
                                            class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-xl transition-all duration-200">
                                        Cancel
                                    </button>
                                    <button type="submit" 
                                            class="px-6 py-2.5 bg-gradient-to-r from-teal-500 to-green-600 hover:from-teal-600 hover:to-green-700 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                                        <i class="bi bi-save mr-2"></i>
                                        <span id="trainingSubmitText">Save Training</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Government IDs Tab -->
            <div class="tab-content hidden" id="tab-government">
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200/50 dark:border-gray-700/50 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-purple-600 to-purple-800">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                                    <i class="bi bi-person-vcard text-white"></i>
                                </div>
                                <h3 class="ml-3 text-lg font-semibold text-white">Gov't. IDs</h3>
                                <span class="ml-2 px-2.5 py-0.5 bg-white/20 text-white text-xs font-medium rounded-full">
                                    PDS Requirements
                                </span>
                            </div>
                            @if($governmentIds)
                                <button onclick="openEditGovernmentIdModal({{ $governmentIds->id }})" 
                                        class="inline-flex items-center px-3 py-1.5 bg-white/20 hover:bg-white/30 text-white text-sm font-medium rounded-lg transition-all duration-200">
                                    <i class="bi bi-pencil-square mr-1.5"></i>
                                    Edit
                                </button>
                            @else
                                <button onclick="openAddGovernmentIdModal()" 
                                        class="inline-flex items-center px-3 py-1.5 bg-white/20 hover:bg-white/30 text-white text-sm font-medium rounded-lg transition-all duration-200">
                                    <i class="bi bi-plus-lg mr-1.5"></i>
                                    Add IDs
                                </button>
                            @endif
                        </div>
                    </div>
                    <div class="p-6">
                        @if($governmentIds)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <!-- UMID ID NO. -->
                                <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl p-4 border border-blue-200 dark:border-blue-800">
                                    <div class="flex items-start">
                                        <div class="w-10 h-10 rounded-lg bg-blue-500/10 flex items-center justify-center mr-3 flex-shrink-0">
                                            <i class="fa-regular fa-id-card text-blue-600 dark:text-blue-400 text-lg"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">UMID ID No.</p>
                                            <p class="mt-0.5 text-sm font-semibold text-gray-900 dark:text-white">
                                                {{ $governmentIds->umid_number ?? '—' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- PAG-IBIG ID NO. -->
                                <div class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 rounded-xl p-4 border border-orange-200 dark:border-orange-800">
                                    <div class="flex items-start">
                                        <div class="w-10 h-10 rounded-lg bg-orange-500/10 flex items-center justify-center mr-3 flex-shrink-0">
                                            <i class="bi bi-house-heart text-orange-600 dark:text-orange-400 text-lg"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">PAG-IBIG ID No.</p>
                                            <p class="mt-0.5 text-sm font-semibold text-gray-900 dark:text-white">
                                                {{ $governmentIds->pagibig_number ?? '—' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- PHILHEALTH NO. -->
                                <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl p-4 border border-green-200 dark:border-green-800">
                                    <div class="flex items-start">
                                        <div class="w-10 h-10 rounded-lg bg-green-500/10 flex items-center justify-center mr-3 flex-shrink-0">
                                            <i class="bi bi-heart-pulse text-green-600 dark:text-green-400 text-lg"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">PhilHealth No.</p>
                                            <p class="mt-0.5 text-sm font-semibold text-gray-900 dark:text-white">
                                                {{ $governmentIds->philhealth_number ?? '—' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- PhilSys Number (PSN) -->
                                <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-indigo-900/20 dark:to-indigo-800/20 rounded-xl p-4 border border-indigo-200 dark:border-indigo-800">
                                    <div class="flex items-start">
                                        <div class="w-10 h-10 rounded-lg bg-indigo-500/10 flex items-center justify-center mr-3 flex-shrink-0">
                                            <i class="bi bi-qr-code text-indigo-600 dark:text-indigo-400 text-lg"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">PhilSys Number (PSN)</p>
                                            <p class="mt-0.5 text-sm font-semibold text-gray-900 dark:text-white">
                                                {{ $governmentIds->philsys_number ?? '—' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- TIN NO. -->
                                <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-xl p-4 border border-red-200 dark:border-red-800">
                                    <div class="flex items-start">
                                        <div class="w-10 h-10 rounded-lg bg-red-500/10 flex items-center justify-center mr-3 flex-shrink-0">
                                            <i class="bi bi-receipt text-red-600 dark:text-red-400 text-lg"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">TIN</p>
                                            <p class="mt-0.5 text-sm font-semibold text-gray-900 dark:text-white">
                                                {{ $governmentIds->tin_number ?? '—' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- SSS NO. -->
                                <div class="bg-gradient-to-br from-pink-50 to-pink-100 dark:from-pink-900/20 dark:to-pink-800/20 rounded-xl p-4 border border-pink-200 dark:border-pink-800">
                                    <div class="flex items-start">
                                        <div class="w-10 h-10 rounded-lg bg-pink-500/10 flex items-center justify-center mr-3 flex-shrink-0">
                                            <i class="bi bi-shield-check text-pink-600 dark:text-pink-400 text-lg"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">SSS No.</p>
                                            <p class="mt-0.5 text-sm font-semibold text-gray-900 dark:text-white">
                                                {{ $governmentIds->sss_number ?? '—' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- GSIS NO. -->
                                <div class="bg-gradient-to-br from-cyan-50 to-cyan-100 dark:from-cyan-900/20 dark:to-cyan-800/20 rounded-xl p-4 border border-cyan-200 dark:border-cyan-800">
                                    <div class="flex items-start">
                                        <div class="w-10 h-10 rounded-lg bg-cyan-500/10 flex items-center justify-center mr-3 flex-shrink-0">
                                            <i class="bi bi-building text-cyan-600 dark:text-cyan-400 text-lg"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">GSIS No.</p>
                                            <p class="mt-0.5 text-sm font-semibold text-gray-900 dark:text-white">
                                                {{ $governmentIds->gsis_number ?? '—' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- DRIVER'S LICENSE -->
                                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20 rounded-xl p-4 border border-yellow-200 dark:border-yellow-800">
                                    <div class="flex items-start">
                                        <div class="w-10 h-10 rounded-lg bg-yellow-500/10 flex items-center justify-center mr-3 flex-shrink-0">
                                            <i class="bi bi-car-front text-yellow-600 dark:text-yellow-400 text-lg"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Driver's License</p>
                                            <p class="mt-0.5 text-sm font-semibold text-gray-900 dark:text-white">
                                                {{ $governmentIds->dl_number ?? '—' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- PASSPORT NO. -->
                                <div class="bg-gradient-to-br from-teal-50 to-teal-100 dark:from-teal-900/20 dark:to-teal-800/20 rounded-xl p-4 border border-teal-200 dark:border-teal-800">
                                    <div class="flex items-start">
                                        <div class="w-10 h-10 rounded-lg bg-teal-500/10 flex items-center justify-center mr-3 flex-shrink-0">
                                            <i class="bi bi-passport text-teal-600 dark:text-teal-400 text-lg"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Passport No.</p>
                                            <p class="mt-0.5 text-sm font-semibold text-gray-900 dark:text-white">
                                                {{ $governmentIds->passport_number ?? '—' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- PRC ID NO. -->
                                <div class="bg-gradient-to-br from-violet-50 to-violet-100 dark:from-violet-900/20 dark:to-violet-800/20 rounded-xl p-4 border border-violet-200 dark:border-violet-800">
                                    <div class="flex items-start">
                                        <div class="w-10 h-10 rounded-lg bg-violet-500/10 flex items-center justify-center mr-3 flex-shrink-0">
                                            <i class="bi bi-award text-violet-600 dark:text-violet-400 text-lg"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">PRC ID No.</p>
                                            <p class="mt-0.5 text-sm font-semibold text-gray-900 dark:text-white">
                                                {{ $governmentIds->prc_number ?? '—' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Summary Status -->
                            <div class="mt-6 p-4 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-xl border border-purple-200 dark:border-purple-800">
                                <div class="flex items-center justify-between flex-wrap gap-2">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-purple-500 to-pink-600 flex items-center justify-center">
                                            <i class="bi bi-check-circle text-white"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">Government IDs Status</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                @php
                                                    $fields = [
                                                        'umid_number',
                                                        'pagibig_number', 
                                                        'philhealth_number',
                                                        'philsys_number',
                                                        'tin_number',
                                                        'sss_number',
                                                        'dl_number'
                                                    ];
                                                    $filled = 0;
                                                    foreach ($fields as $field) {
                                                        if (!empty($governmentIds->$field)) {
                                                            $filled++;
                                                        }
                                                    }
                                                    $total = count($fields);
                                                @endphp
                                                {{ $filled }} of {{ $total }} IDs provided
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="px-3 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 text-xs font-medium rounded-full">
                                            <i class="bi bi-circle-fill mr-1 text-[6px]"></i>
                                            {{ round(($filled / $total) * 100) }}% Complete
                                        </span>
                                    </div>
                                </div>
                                <!-- Progress Bar -->
                                <div class="mt-3 w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-purple-500 to-pink-600 h-2 rounded-full transition-all duration-500" 
                                        style="width: {{ round(($filled / $total) * 100) }}%"></div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="bi bi-person-vcard text-gray-400 text-3xl"></i>
                                </div>
                                <p class="text-gray-500 dark:text-gray-400 font-medium">No government IDs recorded</p>
                                <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Click the "Add IDs" button to enter your government identification numbers</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Add/Edit Government IDs Modal -->
                <div id="governmentIdModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <!-- Backdrop -->
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-80 transition-opacity"></div>
                    
                    <!-- Modal Container -->
                    <div class="flex min-h-screen items-center justify-center p-4">
                        <div class="relative w-full max-w-2xl transform rounded-2xl bg-white dark:bg-gray-800 shadow-2xl transition-all">
                            <!-- Modal Header -->
                            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-purple-600 to-purple-800">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                                        <i class="bi bi-person-vcard text-white text-lg"></i>
                                    </div>
                                    <h3 class="ml-3 text-xl font-bold text-white" id="governmentIdModalTitle">
                                        Government IDs
                                    </h3>
                                </div>
                                <button onclick="closeGovernmentIdModal()" 
                                        class="text-white/70 hover:text-white transition-colors">
                                    <i class="bi bi-x-lg text-xl"></i>
                                </button>
                            </div>
                            
                            <!-- Modal Body -->
                            <div class="px-6 py-6 max-h-[70vh] overflow-y-auto">
                                <form id="governmentIdForm" method="POST">
                                    @csrf
                                    <input type="hidden" id="government_id_method" name="_method" value="POST">
                                    <input type="hidden" id="government_id" name="government_id" value="">
                                    
                                    <div class="space-y-6">
                                        <!-- PDS Required IDs -->
                                        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-xl p-4 border border-purple-200 dark:border-purple-800">
                                            <p class="text-sm font-medium text-purple-700 dark:text-purple-400 mb-3">
                                                <i class="bi bi-info-circle mr-1"></i>
                                                Required Government IDs (PDS)
                                            </p>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <!-- UMID ID NO. -->
                                                <div>
                                                    <label for="umid_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                        <i class="bi bi-id-card text-blue-500 mr-1"></i>
                                                        UMID ID No.
                                                    </label>
                                                    <input type="text" 
                                                        id="umid_number" 
                                                        name="umid_number" 
                                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                                        placeholder="e.g., XXXX-XXXXXXX-X">
                                                </div>

                                                <!-- PAG-IBIG ID NO. -->
                                                <div>
                                                    <label for="pagibig_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                        <i class="bi bi-house-heart text-orange-500 mr-1"></i>
                                                        PAG-IBIG ID No.
                                                    </label>
                                                    <input type="text" 
                                                        id="pagibig_number" 
                                                        name="pagibig_number" 
                                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                                        placeholder="e.g., XXXX-XXXX-XXXX">
                                                </div>

                                                <!-- PHILHEALTH NO. -->
                                                <div>
                                                    <label for="philhealth_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                        <i class="bi bi-heart-pulse text-green-500 mr-1"></i>
                                                        PhilHealth No.
                                                    </label>
                                                    <input type="text" 
                                                        id="philhealth_number" 
                                                        name="philhealth_number" 
                                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                                        placeholder="e.g., XX-XXXXXXXXX-X">
                                                </div>

                                                <!-- PhilSys Number (PSN) -->
                                                <div>
                                                    <label for="philsys_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                        <i class="bi bi-qr-code text-indigo-500 mr-1"></i>
                                                        PhilSys Number (PSN)
                                                    </label>
                                                    <input type="text" 
                                                        id="philsys_number" 
                                                        name="philsys_number" 
                                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                                        placeholder="e.g., XXXX-XXXXXXX-X">
                                                </div>

                                                <!-- TIN NO. -->
                                                <div>
                                                    <label for="tin_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                        <i class="bi bi-receipt text-red-500 mr-1"></i>
                                                        TIN
                                                    </label>
                                                    <input type="text" 
                                                        id="tin_number" 
                                                        name="tin_number" 
                                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                                        placeholder="e.g., XXX-XXX-XXX-XXX">
                                                </div>

                                                <!-- SSS NO. -->
                                                <div>
                                                    <label for="sss_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                        <i class="bi bi-shield-check text-pink-500 mr-1"></i>
                                                        SSS No.
                                                    </label>
                                                    <input type="text" 
                                                        id="sss_number" 
                                                        name="sss_number" 
                                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                                        placeholder="e.g., XX-XXXXXXX-X">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Additional Government IDs -->
                                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                                <i class="bi bi-plus-circle text-purple-500 mr-1"></i>
                                                Additional Government IDs (Optional)
                                            </p>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <!-- GSIS Number -->
                                                <div>
                                                    <label for="gsis_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                        <i class="bi bi-building text-cyan-500 mr-1"></i>
                                                        GSIS Number
                                                    </label>
                                                    <input type="text" 
                                                        id="gsis_number" 
                                                        name="gsis_number" 
                                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                                        placeholder="e.g., XX-XXXXXXX-X">
                                                </div>

                                                <!-- Driver's License -->
                                                <div>
                                                    <label for="dl_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                        <i class="bi bi-car-front text-amber-500 mr-1"></i>
                                                        Driver's License No.
                                                    </label>
                                                    <input type="text" 
                                                        id="dl_number" 
                                                        name="dl_number" 
                                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                                        placeholder="e.g., XXXX-XXXX-XXXX">
                                                </div>

                                                <!-- Passport Number -->
                                                <div>
                                                    <label for="passport_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                        <i class="bi bi-passport text-teal-500 mr-1"></i>
                                                        Passport No.
                                                    </label>
                                                    <input type="text" 
                                                        id="passport_number" 
                                                        name="passport_number" 
                                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                                        placeholder="e.g., XX-XXXXXXX">
                                                </div>

                                                <!-- PRC ID -->
                                                <div>
                                                    <label for="prc_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                        <i class="bi bi-award text-purple-500 mr-1"></i>
                                                        PRC ID No.
                                                    </label>
                                                    <input type="text" 
                                                        id="prc_number" 
                                                        name="prc_number" 
                                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                                                        placeholder="e.g., XXXX-XXXX">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Tips -->
                                        <div class="bg-amber-50 dark:bg-amber-900/20 rounded-xl p-4 border border-amber-200 dark:border-amber-800">
                                            <p class="text-xs text-amber-700 dark:text-amber-400 flex items-start">
                                                <i class="bi bi-lightbulb mr-2 text-amber-500 flex-shrink-0 mt-0.5"></i>
                                                <span>
                                                    <span class="font-medium">Tip:</span> 
                                                    Enter your government ID numbers exactly as they appear on your physical IDs. 
                                                    These are required for PDS (Personal Data Sheet) compliance.
                                                    You can use dashes or spaces for formatting.
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <!-- Form Actions -->
                                    <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                                        <button type="button" 
                                                onclick="closeGovernmentIdModal()"
                                                class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-xl transition-all duration-200">
                                            Cancel
                                        </button>
                                        <button type="submit" 
                                                class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                                            <i class="bi bi-save mr-2"></i>
                                            <span id="governmentIdSubmitText">Save Government IDs</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employment tab -->
            <div class="tab-content hidden" id="tab-employment">
                <!-- Employment Details -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200/50 dark:border-gray-700/50 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-indigo-500 to-purple-600">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                                    <i class="bi bi-building text-white"></i>
                                </div>
                                <h3 class="ml-3 text-lg font-semibold text-white">Employment Information</h3>
                                <span class="ml-3 px-2.5 py-0.5 bg-white/20 text-white text-xs font-medium rounded-full">
                                    LGU Employee
                                </span>
                            </div>
                            @if($employment)
                                <button onclick="openEditEmploymentModal({{ $employment->id }})" 
                                        class="inline-flex items-center px-3 py-1.5 bg-white/20 hover:bg-white/30 text-white text-sm font-medium rounded-lg transition-all duration-200">
                                    <i class="bi bi-pencil-square mr-1.5"></i>
                                    Edit
                                </button>
                            @endif
                        </div>
                    </div>
                    
                    <div class="p-6">
                        @if($employment)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Employee ID -->
                                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center mr-3">
                                            <i class="bi bi-person-badge text-indigo-600 dark:text-indigo-400"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Employee ID</p>
                                            <p class="mt-0.5 text-sm font-semibold text-gray-900 dark:text-white">
                                                {{ $employment->employee_id ?? '—' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center mr-3">
                                            <i class="bi bi-circle-fill text-green-600 dark:text-green-400 text-sm"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Status</p>
                                            <p class="mt-0.5 text-sm font-semibold text-gray-900 dark:text-white">
                                                <span class="px-2 py-0.5 rounded-full text-xs {{ $employment->status_badge }}">
                                                    {{ $employment->status_label }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Position -->
                                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center mr-3">
                                            <i class="bi bi-briefcase text-purple-600 dark:text-purple-400"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Position</p>
                                            <p class="mt-0.5 text-sm font-semibold text-gray-900 dark:text-white">
                                                {{ $employment->position->title ?? '—' }}
                                            </p>
                                            @if($employment->salary_grade)
                                                <p class="text-xs text-gray-400 dark:text-gray-500">SG: {{ $employment->salary_grade }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Department -->
                                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mr-3">
                                            <i class="bi bi-diagram-3 text-blue-600 dark:text-blue-400"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Department</p>
                                            <p class="mt-0.5 text-sm font-semibold text-gray-900 dark:text-white">
                                                {{ $employment->department->name ?? '—' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Employment Type -->
                                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center mr-3">
                                            <i class="bi bi-person-check text-amber-600 dark:text-amber-400"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Employment Type</p>
                                            <p class="mt-0.5 text-sm font-semibold text-gray-900 dark:text-white">
                                                {{ $employment->employment_type_label }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Salary -->
                                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center mr-3">
                                            <i class="bi bi-cash text-emerald-600 dark:text-emerald-400"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Monthly Salary</p>
                                            <p class="mt-0.5 text-sm font-semibold text-gray-900 dark:text-white">
                                                {{ $employment->formatted_salary }}
                                            </p>
                                            @if($employment->step_increment)
                                                <p class="text-xs text-gray-400 dark:text-gray-500">Step: {{ $employment->step_increment }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Date Hired -->
                                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-lg bg-teal-100 dark:bg-teal-900/30 flex items-center justify-center mr-3">
                                            <i class="bi bi-calendar-event text-teal-600 dark:text-teal-400"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Date Hired</p>
                                            <p class="mt-0.5 text-sm font-semibold text-gray-900 dark:text-white">
                                                {{ $employment->formatted_hired_date }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Date of Original Appointment -->
                                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-lg bg-cyan-100 dark:bg-cyan-900/30 flex items-center justify-center mr-3">
                                            <i class="bi bi-calendar2-event text-cyan-600 dark:text-cyan-400"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Original Appointment</p>
                                            <p class="mt-0.5 text-sm font-semibold text-gray-900 dark:text-white">
                                                {{ $employment->formatted_original_appointment }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Date of Last Promotion -->
                                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-lg bg-rose-100 dark:bg-rose-900/30 flex items-center justify-center mr-3">
                                            <i class="bi bi-arrow-up-circle text-rose-600 dark:text-rose-400"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Last Promotion</p>
                                            <p class="mt-0.5 text-sm font-semibold text-gray-900 dark:text-white">
                                                {{ $employment->formatted_last_promotion }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Service Statistics -->
                            <div class="mt-6 grid grid-cols-2 sm:grid-cols-4 gap-3">
                                <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-indigo-900/20 dark:to-indigo-800/20 rounded-xl p-4 text-center border border-indigo-200 dark:border-indigo-800">
                                    <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                                        {{ $employment->years_of_service }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Years of Service</p>
                                </div>
                                <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-xl p-4 text-center border border-purple-200 dark:border-purple-800">
                                    <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                                        {{ $employment->months_of_service }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Total Months</p>
                                </div>
                                <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl p-4 text-center border border-blue-200 dark:border-blue-800">
                                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                                        {{ $employment->days_of_service }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Total Days</p>
                                </div>
                                <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl p-4 text-center border border-green-200 dark:border-green-800">
                                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                                        {{ $employment->status_label }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Status</p>
                                </div>
                            </div>

                            <!-- Employment Summary -->
                            <div class="mt-6 p-4 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 rounded-xl border border-indigo-100 dark:border-indigo-800">
                                <div class="flex items-center justify-between flex-wrap gap-2">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center">
                                            <i class="bi bi-person-check text-white"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $employment->position->name ?? 'Employee' }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $employment->service_duration }} of service
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-medium rounded-full">
                                            <i class="bi bi-circle-fill mr-1 text-[6px]"></i>
                                            {{ $employment->status_label }}
                                        </span>
                                        <span class="px-3 py-1 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 text-xs font-medium rounded-full">
                                            {{ $employment->employment_type_label }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- No Employment Record -->
                            <div class="text-center py-12">
                                <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="bi bi-building text-gray-400 text-3xl"></i>
                                </div>
                                <p class="text-gray-500 dark:text-gray-400 font-medium">No employment record found</p>
                                <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Please contact HR to add your employment details</p>
                                <button onclick="openAddEmploymentModal()" 
                                        class="mt-4 inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white text-sm font-medium rounded-xl transition-all duration-200 shadow-md hover:shadow-lg">
                                    <i class="bi bi-plus-lg mr-2"></i>
                                    Add Employment Record
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Add/Edit Employment Modal -->
                <div id="employmentModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <!-- Backdrop -->
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-80 transition-opacity"></div>
                    
                    <!-- Modal Container -->
                    <div class="flex min-h-screen items-center justify-center p-4">
                        <div class="relative w-full max-w-2xl transform rounded-2xl bg-white dark:bg-gray-800 shadow-2xl transition-all">
                            <!-- Modal Header -->
                            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                                        <i class="bi bi-building text-white text-lg"></i>
                                    </div>
                                    <h3 class="ml-3 text-xl font-bold text-gray-900 dark:text-white" id="employmentModalTitle">
                                        Add Employment Record
                                    </h3>
                                </div>
                                <button onclick="closeEmploymentModal()" 
                                        class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 transition-colors">
                                    <i class="bi bi-x-lg text-xl"></i>
                                </button>
                            </div>
                            
                            <!-- Modal Body -->
                            <div class="px-6 py-6 max-h-[70vh] overflow-y-auto">
                                <form id="employmentForm" method="POST">
                                    @csrf
                                    <input type="hidden" id="employment_method" name="_method" value="POST">
                                    <input type="hidden" id="employment_id" name="employment_id" value="">
                                    
                                    <div class="space-y-6">
                                        <!-- Employee ID & Status -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label for="employee_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                    <i class="bi bi-person-badge text-indigo-500 mr-1"></i>
                                                    Employee ID
                                                </label>
                                                <input type="text" 
                                                    id="employee_id" 
                                                    name="employee_id" 
                                                    class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                                                    placeholder="e.g., LGU-2024-001">
                                            </div>
                                            <div>
                                                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                    <i class="bi bi-circle text-indigo-500 mr-1"></i>
                                                    Status <span class="text-red-500">*</span>
                                                </label>
                                                <select id="status" 
                                                        name="status" 
                                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                                                        required>
                                                    <option value="active">Active</option>
                                                    <option value="inactive">Inactive</option>
                                                    <option value="resigned">Resigned</option>
                                                    <option value="retired">Retired</option>
                                                    <option value="on_leave">On Leave</option>
                                                    <option value="suspended">Suspended</option>
                                                    <option value="terminated">Terminated</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Position & Department -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label for="position_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                    <i class="bi bi-briefcase text-indigo-500 mr-1"></i>
                                                    Position <span class="text-red-500">*</span>
                                                </label>
                                                <select id="position_id" 
                                                        name="position_id" 
                                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                                                        required>
                                                    <option value="">Select Position</option>
                                                    @foreach($positions as $position)
                                                        <option value="{{ $position->id }}">{{ $position->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label for="department_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                    <i class="bi bi-diagram-3 text-indigo-500 mr-1"></i>
                                                    Department <span class="text-red-500">*</span>
                                                </label>
                                                <select id="department_id" 
                                                        name="department_id" 
                                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                                                        required>
                                                    <option value="">Select Department</option>
                                                    @foreach($departments ?? [] as $department)
                                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Employment Type & Salary Grade -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label for="employment_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                    <i class="bi bi-person-check text-indigo-500 mr-1"></i>
                                                    Employment Type <span class="text-red-500">*</span>
                                                </label>
                                                <select id="employment_type" 
                                                        name="employment_type" 
                                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                                                        required>
                                                    <option value="permanent">Permanent</option>
                                                    <option value="temporary">Temporary</option>
                                                    <option value="contractual">Contractual</option>
                                                    <option value="casual">Casual</option>
                                                    <option value="job_order">Job Order</option>
                                                    <option value="consultant">Consultant</option>
                                                    <option value="co_term">Co-Terminus</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label for="salary_grade" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                    <i class="bi bi-graph-up text-indigo-500 mr-1"></i>
                                                    Salary Grade
                                                </label>
                                                <select id="salary_grade" 
                                                        name="salary_grade" 
                                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                                                    <option value="">Select SG</option>
                                                    @for($i = 1; $i <= 33; $i++)
                                                        <option value="SG-{{ $i }}">SG-{{ $i }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Salary & Step Increment -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label for="salary" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                    <i class="bi bi-cash text-indigo-500 mr-1"></i>
                                                    Monthly Salary
                                                </label>
                                                <div class="relative">
                                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400">₱</span>
                                                    <input type="number" 
                                                        id="salary" 
                                                        name="salary" 
                                                        step="0.01"
                                                        min="0"
                                                        class="w-full pl-8 pr-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                                                        placeholder="0.00">
                                                </div>
                                            </div>
                                            <div>
                                                <label for="step_increment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                    <i class="bi bi-plus-circle text-indigo-500 mr-1"></i>
                                                    Step Increment
                                                </label>
                                                <select id="step_increment" 
                                                        name="step_increment" 
                                                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                                                    <option value="">Select Step</option>
                                                    @for($i = 1; $i <= 8; $i++)
                                                        <option value="{{ $i }}">Step {{ $i }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Important Dates -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label for="hired_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                    <i class="bi bi-calendar-event text-indigo-500 mr-1"></i>
                                                    Date Hired <span class="text-red-500">*</span>
                                                </label>
                                                <input type="date" 
                                                    id="hired_at" 
                                                    name="hired_at" 
                                                    class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                                                    required>
                                            </div>
                                            <div>
                                                <label for="date_of_original_appointment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                    <i class="bi bi-calendar2-event text-indigo-500 mr-1"></i>
                                                    Original Appointment Date
                                                </label>
                                                <input type="date" 
                                                    id="date_of_original_appointment" 
                                                    name="date_of_original_appointment" 
                                                    class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                                            </div>
                                            <div class="md:col-span-2">
                                                <label for="date_of_last_promotion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                    <i class="bi bi-arrow-up-circle text-indigo-500 mr-1"></i>
                                                    Last Promotion Date
                                                </label>
                                                <input type="date" 
                                                    id="date_of_last_promotion" 
                                                    name="date_of_last_promotion" 
                                                    class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Form Actions -->
                                    <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                                        <button type="button" 
                                                onclick="closeEmploymentModal()"
                                                class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-xl transition-all duration-200">
                                            Cancel
                                        </button>
                                        <button type="submit" 
                                                class="px-6 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                                            <i class="bi bi-save mr-2"></i>
                                            <span id="employmentSubmitText">Save Employment Record</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Skills Tab -->
            <div class="tab-content hidden" id="tab-skills">
                <div class="space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200/50 dark:border-gray-700/50 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-800">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gradient-to-r from-teal-500 to-blue-600 rounded-lg flex items-center justify-center">
                                        <i class="bi bi-star text-white"></i>
                                    </div>
                                    <h3 class="ml-3 text-lg font-semibold text-gray-900 dark:text-white">Skills & Hobbies</h3>
                                    <span class="ml-2 px-2.5 py-0.5 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs font-medium rounded-full">
                                        {{ $skills->count() }}
                                    </span>
                                </div>
                                <button onclick="openAddSkillModal()" 
                                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white text-sm font-medium rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                                    <i class="bi bi-plus-lg mr-2"></i>
                                    Add Skill
                                </button>
                            </div>
                        </div>
                        <div class="p-6">
                            @if($skills && $skills->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead>
                                            <tr class="bg-gray-50 dark:bg-gray-700/50">
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">#</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Skill / Hobby</th>
                                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($skills as $index => $skill)
                                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors duration-150">
                                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                                                    <td class="px-4 py-4">
                                                        <div class="flex items-center">
                                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-red-100 to-rose-200 dark:from-red-900/30 dark:to-rose-800/30 flex items-center justify-center mr-3">
                                                                <i class="bi bi-star text-red-600 dark:text-red-400 text-xs"></i>
                                                            </div>
                                                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $skill->skill_hobby }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                        <div class="flex items-center justify-end space-x-2">
                                                            <button onclick="openEditSkillModal({{ $skill->id }})" 
                                                                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors">
                                                                <i class="bi bi-pencil-square"></i>
                                                            </button>
                                                            <button onclick="confirmDeleteSkill({{ $skill->id }})" 
                                                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="bi bi-star text-gray-400 text-3xl"></i>
                                    </div>
                                    <p class="text-gray-500 dark:text-gray-400 font-medium">No skills or hobbies recorded</p>
                                    <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Click the "Add Skill" button to get started</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add/Edit Skill Modal -->
            <div id="skillModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <!-- Backdrop -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-80 transition-opacity"></div>
                
                <!-- Modal Container -->
                <div class="flex min-h-screen items-center justify-center p-4">
                    <div class="relative w-full max-w-lg transform rounded-2xl bg-white dark:bg-gray-800 shadow-2xl transition-all">
                        <!-- Modal Header -->
                        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-teal-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                                    <i class="bi bi-star text-white text-lg"></i>
                                </div>
                                <h3 class="ml-3 text-xl font-bold text-gray-900 dark:text-white" id="skillModalTitle">
                                    Add Skill
                                </h3>
                            </div>
                            <button onclick="closeSkillModal()" 
                                    class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 transition-colors">
                                <i class="bi bi-x-lg text-xl"></i>
                            </button>
                        </div>
                        
                        <!-- Modal Body -->
                        <div class="px-6 py-6">
                            <form id="skillForm" method="POST">
                                @csrf
                                <input type="hidden" id="skill_method" name="_method" value="POST">
                                <input type="hidden" id="skill_id" name="skill_id" value="">
                                
                                <div class="space-y-6">
                                    <!-- Skill/Hobby -->
                                    <div>
                                        <label for="skill_hobby" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            <i class="bi bi-person-check text-teal-500 mr-1"></i>
                                            Skill / Hobby <span class="text-red-500">*</span>
                                        </label>
                                        <textarea 
                                            id="skill_hobby" 
                                            name="skill_hobby" 
                                            rows="3"
                                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 resize-none"
                                            placeholder="Enter your skill or hobby. You can add multiple skills separated by commas or list them one per line."
                                            required></textarea>
                                        <p class="mt-2 text-xs text-gray-400 dark:text-gray-500">
                                            <i class="bi bi-info-circle mr-1"></i>
                                            Examples: "Web Development, Graphic Design, Photography", or "Playing Guitar, Painting, Reading"
                                        </p>
                                    </div>
                                </div>
                                
                                <!-- Form Actions -->
                                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                                    <button type="button" 
                                            onclick="closeSkillModal()"
                                            class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-xl transition-all duration-200">
                                        Cancel
                                    </button>
                                    <button type="submit" 
                                            class="px-6 py-2.5 bg-gradient-to-r from-teal-500 to-blue-600 hover:from-teal-600 hover:to-blue-700 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                                        <i class="bi bi-save mr-2"></i>
                                        <span id="skillSubmitText">Save Skill</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Distinctions Tab -->
            <div class="tab-content hidden" id="tab-distinctions">
                <div class="space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200/50 dark:border-gray-700/50 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-800">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gradient-to-r from-purple-400 via-pink-500 to-red-500 rounded-lg flex items-center justify-center">
                                        <i class="fa-solid fa-trophy text-white"></i>
                                    </div>
                                    <h3 class="ml-3 text-lg font-semibold text-gray-900 dark:text-white">Academic Distinctions</h3>
                                    <span class="ml-2 px-2.5 py-0.5 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs font-medium rounded-full">
                                        {{ $distinctions->count() }}
                                    </span>
                                </div>
                                <button onclick="openAddDistinctionModal()" 
                                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white text-sm font-medium rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                                    <i class="bi bi-plus-lg mr-2"></i>
                                    Add Distinction
                                </button>
                            </div>
                        </div>
                        <div class="p-6">
                            @if($distinctions && $distinctions->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead>
                                            <tr class="bg-gray-50 dark:bg-gray-700/50">
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">#</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Distinction / Award</th>
                                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($distinctions as $index => $distinction)
                                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors duration-150">
                                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                                                    <td class="px-4 py-4">
                                                        <div class="flex items-center">
                                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-100 to-pink-200 dark:from-purple-900/30 dark:to-pink-800/30 flex items-center justify-center mr-3">
                                                                <i class="fa-solid fa-trophy text-purple-600 dark:text-purple-400 text-xs"></i>
                                                            </div>
                                                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $distinction->distinctions }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                        <div class="flex items-center justify-end space-x-2">
                                                            <button onclick="openEditDistinctionModal({{ $distinction->id }})" 
                                                                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors">
                                                                <i class="bi bi-pencil-square"></i>
                                                            </button>
                                                            <button onclick="confirmDeleteDistinction({{ $distinction->id }})" 
                                                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fa-solid fa-trophy text-gray-400 text-3xl"></i>
                                    </div>
                                    <p class="text-gray-500 dark:text-gray-400 font-medium">No academic distinctions recorded</p>
                                    <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Click the "Add Distinction" button to get started</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add/Edit Distinction Modal -->
            <div id="distinctionModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <!-- Backdrop -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-80 transition-opacity"></div>
                
                <!-- Modal Container -->
                <div class="flex min-h-screen items-center justify-center p-4">
                    <div class="relative w-full max-w-lg transform rounded-2xl bg-white dark:bg-gray-800 shadow-2xl transition-all">
                        <!-- Modal Header -->
                        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-teal-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                                    <i class="fa-solid fa-trophy text-white text-lg"></i>
                                </div>
                                <h3 class="ml-3 text-xl font-bold text-gray-900 dark:text-white" id="distinctionModalTitle">
                                    Add Distinction
                                </h3>
                            </div>
                            <button onclick="closeDistinctionModal()" 
                                    class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 transition-colors">
                                <i class="bi bi-x-lg text-xl"></i>
                            </button>
                        </div>
                        
                        <!-- Modal Body -->
                        <div class="px-6 py-6">
                            <form id="distinctionForm" method="POST">
                                @csrf
                                <input type="hidden" id="distinction_method" name="_method" value="POST">
                                <input type="hidden" id="distinction_id" name="distinction_id" value="">
                                
                                <div class="space-y-6">
                                    <!-- Distinction/Award -->
                                    <div>
                                        <label for="distinctions" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            <i class="fa-solid fa-trophy text-teal-500 mr-1"></i>
                                            Distinction / Award <span class="text-red-500">*</span>
                                        </label>
                                        <textarea 
                                            id="distinctions" 
                                            name="distinctions" 
                                            rows="4"
                                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-200 resize-none"
                                            placeholder="Enter your academic distinction or award. You can add multiple distinctions separated by commas or list them one per line."
                                            required></textarea>
                                        <p class="mt-2 text-xs text-gray-400 dark:text-gray-500">
                                            <i class="bi bi-info-circle mr-1"></i>
                                            Examples: "Summa Cum Laude, Dean's Lister, Best Thesis Award", or "Academic Excellence Award, Leadership Award"
                                        </p>
                                        <div class="mt-2 text-xs text-gray-400 dark:text-gray-500">
                                            <i class="bi bi-lightbulb mr-1"></i>
                                            <span class="font-medium">Tips:</span>
                                            <ul class="list-disc list-inside mt-1 space-y-0.5">
                                                <li>Include the year if applicable (e.g., "Dean's Lister 2023")</li>
                                                <li>Specify the granting institution if relevant</li>
                                                <li>Separate multiple entries with commas</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Form Actions -->
                                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                                    <button type="button" 
                                            onclick="closeDistinctionModal()"
                                            class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-xl transition-all duration-200">
                                        Cancel
                                    </button>
                                    <button type="submit" 
                                            class="px-6 py-2.5 bg-gradient-to-r from-teal-500 to-blue-600 hover:from-teal-600 hover:to-blue-700 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                                        <i class="bi bi-save mr-2"></i>
                                        <span id="distinctionSubmitText">Save Distinction</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Organizations Tab -->
            <div class="tab-content hidden" id="tab-organizations">
                <div class="space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200/50 dark:border-gray-700/50 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-800">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gradient-to-r from-purple-400 via-pink-500 to-red-500 rounded-lg flex items-center justify-center">
                                        <i class="fa-solid fa-users text-white"></i>
                                    </div>
                                    <h3 class="ml-3 text-lg font-semibold text-gray-900 dark:text-white">Organizations</h3>
                                    <span class="ml-2 px-2.5 py-0.5 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs font-medium rounded-full">
                                        {{ $organizations->count() }}
                                    </span>
                                </div>
                                <button onclick="openAddOrganizationModal()" 
                                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white text-sm font-medium rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                                    <i class="bi bi-plus-lg mr-2"></i>
                                    Add Organization
                                </button>
                            </div>
                        </div>
                        <div class="p-6">
                            @if($organizations && $organizations->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead>
                                            <tr class="bg-gray-50 dark:bg-gray-700/50">
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">#</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Organization Name</th>
                                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($organizations as $index => $organization)
                                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors duration-150">
                                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                                                    <td class="px-4 py-4">
                                                        <div class="flex items-center">
                                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-100 to-pink-200 dark:from-purple-900/30 dark:to-pink-800/30 flex items-center justify-center mr-3">
                                                                <i class="fa-solid fa-users text-purple-600 dark:text-purple-400 text-xs"></i>
                                                            </div>
                                                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $organization->organization }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                        <div class="flex items-center justify-end space-x-2">
                                                            <button onclick="openEditOrganizationModal({{ $organization->id }})" 
                                                                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors">
                                                                <i class="bi bi-pencil-square"></i>
                                                            </button>
                                                            <button onclick="confirmDeleteOrganization({{ $organization->id }})" 
                                                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fa-solid fa-users text-gray-400 text-3xl"></i>
                                    </div>
                                    <p class="text-gray-500 dark:text-gray-400 font-medium">No organizations recorded</p>
                                    <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Click the "Add Organization" button to get started</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add/Edit Organization Modal -->
            <div id="organizationModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <!-- Backdrop -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-80 transition-opacity"></div>
                
                <!-- Modal Container -->
                <div class="flex min-h-screen items-center justify-center p-4">
                    <div class="relative w-full max-w-lg transform rounded-2xl bg-white dark:bg-gray-800 shadow-2xl transition-all">
                        <!-- Modal Header -->
                        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-purple-400 via-pink-500 to-red-500 rounded-xl flex items-center justify-center shadow-lg">
                                    <i class="fa-solid fa-users text-white text-lg"></i>
                                </div>
                                <h3 class="ml-3 text-xl font-bold text-gray-900 dark:text-white" id="organizationModalTitle">
                                    Add Organization
                                </h3>
                            </div>
                            <button onclick="closeOrganizationModal()" 
                                    class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 transition-colors">
                                <i class="bi bi-x-lg text-xl"></i>
                            </button>
                        </div>
                        
                        <!-- Modal Body -->
                        <div class="px-6 py-6">
                            <form id="organizationForm" method="POST">
                                @csrf
                                <input type="hidden" id="organization_method" name="_method" value="POST">
                                <input type="hidden" id="organization_id" name="organization_id" value="">
                                
                                <div class="space-y-6">
                                    <!-- Organization Name -->
                                    <div>
                                        <label for="organization" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            <i class="fa-solid fa-building text-purple-500 mr-1"></i>
                                            Organization Name <span class="text-red-500">*</span>
                                        </label>
                                        <textarea 
                                            id="organization" 
                                            name="organization" 
                                            rows="3"
                                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 resize-none"
                                            placeholder="Enter organization name. You can add multiple organizations separated by commas or list them one per line."
                                            required></textarea>
                                        <p class="mt-2 text-xs text-gray-400 dark:text-gray-500">
                                            <i class="bi bi-info-circle mr-1"></i>
                                            Examples: "Rotary Club, Red Cross, Youth Alliance", or "Student Council, Debate Team"
                                        </p>
                                        <div class="mt-2 text-xs text-gray-400 dark:text-gray-500">
                                            <i class="bi bi-lightbulb mr-1"></i>
                                            <span class="font-medium">Tips:</span>
                                            <ul class="list-disc list-inside mt-1 space-y-0.5">
                                                <li>Include your role if relevant (e.g., "Rotary Club - President")</li>
                                                <li>Separate multiple entries with commas</li>
                                                <li>Include years of membership if desired</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Form Actions -->
                                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                                    <button type="button" 
                                            onclick="closeOrganizationModal()"
                                            class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-xl transition-all duration-200">
                                        Cancel
                                    </button>
                                    <button type="submit" 
                                            class="px-6 py-2.5 bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                                        <i class="bi bi-save mr-2"></i>
                                        <span id="organizationSubmitText">Save Organization</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- References Tab -->
            <div class="tab-content hidden" id="tab-references">
                <div class="space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200/50 dark:border-gray-700/50 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-800">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gradient-to-r from-red-500 to-rose-600 rounded-lg flex items-center justify-center">
                                        <i class="bi bi-person-check text-white"></i>
                                    </div>
                                    <h3 class="ml-3 text-lg font-semibold text-gray-900 dark:text-white">References</h3>
                                    <span class="ml-2 px-2.5 py-0.5 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs font-medium rounded-full">
                                        {{ $references->count() }}
                                    </span>
                                </div>
                                <button onclick="openAddReferenceModal()" 
                                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white text-sm font-medium rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                                    <i class="bi bi-plus-lg mr-2"></i>
                                    Add Reference
                                </button>
                            </div>
                        </div>
                        <div class="p-6">
                            @if($references && $references->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead>
                                            <tr class="bg-gray-50 dark:bg-gray-700/50">
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">#</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Occupation</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Contact</th>
                                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($references as $index => $reference)
                                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors duration-150">
                                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                                                    <td class="px-4 py-4">
                                                        <div class="flex items-center">
                                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-red-100 to-rose-200 dark:from-red-900/30 dark:to-rose-800/30 flex items-center justify-center mr-3">
                                                                <i class="bi bi-person text-red-600 dark:text-red-400 text-xs"></i>
                                                            </div>
                                                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $reference->name }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $reference->occupation ?? '—' }}</td>
                                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $reference->contact_number ?? '—' }}</td>
                                                    <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                        <div class="flex items-center justify-end space-x-2">
                                                            <button onclick="openEditReferenceModal({{ $reference->id }})" 
                                                                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors">
                                                                <i class="bi bi-pencil-square"></i>
                                                            </button>
                                                            <button onclick="confirmDeleteReference({{ $reference->id }})" 
                                                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="bi bi-person-check text-gray-400 text-3xl"></i>
                                    </div>
                                    <p class="text-gray-500 dark:text-gray-400 font-medium">No references recorded</p>
                                    <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Click the "Add Reference" button to get started</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add/Edit Reference Modal -->
            <div id="referenceModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <!-- Backdrop -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-80 transition-opacity"></div>
                
                <!-- Modal Container -->
                <div class="flex min-h-screen items-center justify-center p-4">
                    <div class="relative w-full max-w-2xl transform rounded-2xl bg-white dark:bg-gray-800 shadow-2xl transition-all">
                        <!-- Modal Header -->
                        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-red-500 to-rose-600 rounded-xl flex items-center justify-center shadow-lg">
                                    <i class="bi bi-person-check text-white text-lg"></i>
                                </div>
                                <h3 class="ml-3 text-xl font-bold text-gray-900 dark:text-white" id="referenceModalTitle">
                                    Add Reference
                                </h3>
                            </div>
                            <button onclick="closeReferenceModal()" 
                                    class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 transition-colors">
                                <i class="bi bi-x-lg text-xl"></i>
                            </button>
                        </div>
                        
                        <!-- Modal Body -->
                        <div class="px-6 py-6 max-h-[70vh] overflow-y-auto">
                            <form id="referenceForm" method="POST">
                                @csrf
                                <input type="hidden" id="reference_method" name="_method" value="POST">
                                <input type="hidden" id="reference_id" name="reference_id" value="">
                                
                                <div class="space-y-6">
                                    <!-- Name & Order -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="reference_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                <i class="bi bi-person text-red-500 mr-1"></i>
                                                Full Name <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" 
                                                id="reference_name" 
                                                name="name" 
                                                class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200"
                                                placeholder="e.g., Juan Dela Cruz"
                                                required>
                                        </div>
                                        <div>
                                            <label for="reference_order" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                <i class="bi bi-sort-numeric-down text-red-500 mr-1"></i>
                                                Display Order
                                            </label>
                                            <input type="number" 
                                                id="reference_order" 
                                                name="order" 
                                                min="1"
                                                class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200"
                                                placeholder="1, 2, 3...">
                                        </div>
                                    </div>

                                    <!-- Occupation -->
                                    <div>
                                        <label for="reference_occupation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            <i class="bi bi-briefcase text-red-500 mr-1"></i>
                                            Occupation <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" 
                                            id="reference_occupation" 
                                            name="occupation" 
                                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200"
                                            placeholder="e.g., HR Manager"
                                            required>
                                    </div>

                                    <!-- Contact Number & Email -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="reference_contact" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                <i class="bi bi-phone text-red-500 mr-1"></i>
                                                Contact Number <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" 
                                                id="reference_contact" 
                                                name="contact_number" 
                                                class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200"
                                                placeholder="e.g., 09171234567"
                                                required>
                                        </div>
                                        <div>
                                            <label for="reference_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                <i class="bi bi-envelope text-red-500 mr-1"></i>
                                                Email Address
                                            </label>
                                            <input type="email" 
                                                id="reference_email" 
                                                name="email" 
                                                class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200"
                                                placeholder="e.g., juan@example.com">
                                        </div>
                                    </div>

                                    <!-- Address -->
                                    <div>
                                        <label for="reference_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            <i class="bi bi-geo-alt text-red-500 mr-1"></i>
                                            Address
                                        </label>
                                        <textarea 
                                            id="reference_address" 
                                            name="address" 
                                            rows="2"
                                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 resize-none"
                                            placeholder="e.g., 123 Main St, City, Province"></textarea>
                                    </div>
                                </div>
                                
                                <!-- Form Actions -->
                                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                                    <button type="button" 
                                            onclick="closeReferenceModal()"
                                            class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-xl transition-all duration-200">
                                        Cancel
                                    </button>
                                    <button type="submit" 
                                            class="px-6 py-2.5 bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                                        <i class="bi bi-save mr-2"></i>
                                        <span id="referenceSubmitText">Save Reference</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Background Information -->
            <div class="tab-content hidden" id="tab-background">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-700/50 overflow-hidden">
                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-600 to-indigo-600">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                                <i class="bi bi-clipboard-data text-white text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-lg font-semibold text-white">Background Information</h3>
                                <p class="text-sm text-blue-100">Please answer all questions truthfully and completely</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 space-y-8">
                        <form id="backgroundForm" method="POST" action="{{ route('myprofile.background.store') }}">
                            @csrf

                            <!-- Question 34: Relationship to Appointing Authority -->
                            <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-5 border border-gray-200 dark:border-gray-600">
                                <div class="flex items-start">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-bold text-sm flex-shrink-0 mr-3 mt-0.5">
                                        34
                                    </span>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white mb-3">
                                            Are you related by consanguinity or affinity to the appointing or recommending authority, or to the chief of bureau or office or to the person who has immediate supervision over you in the Office, Bureau or Department where you will be appointed?
                                        </p>
                                        
                                        <div class="space-y-3 ml-4">
                                            <!-- 34a: Within third degree -->
                                            <div class="flex items-start">
                                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400 w-6">a.</span>
                                                <div class="flex-1">
                                                    <p class="text-sm text-gray-700 dark:text-gray-300 mb-2">Within the third degree?</p>
                                                    <div class="flex space-x-4">
                                                        <label class="inline-flex items-center">
                                                            <input type="radio" name="q34_a" value="yes" 
                                                                class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700"
                                                                {{ old('q34_a', $backgroundInfo->q34_a ?? '') === 'yes' ? 'checked' : '' }}>
                                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Yes</span>
                                                        </label>
                                                        <label class="inline-flex items-center">
                                                            <input type="radio" name="q34_a" value="no" 
                                                                class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700"
                                                                {{ old('q34_a', $backgroundInfo->q34_a ?? '') === 'no' ? 'checked' : '' }}>
                                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">No</span>
                                                        </label>
                                                    </div>
                                                    @if($backgroundInfo && $backgroundInfo->q34_a_details)
                                                        <div class="mt-2">
                                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                                <i class="bi bi-info-circle mr-1"></i>
                                                                Details: {{ $backgroundInfo->q34_a_details }}
                                                            </p>
                                                        </div>
                                                    @endif
                                                    <div class="mt-2 q34-details {{ old('q34_a', $backgroundInfo->q34_a ?? '') === 'yes' ? '' : 'hidden' }}">
                                                        <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Please specify relationship:</label>
                                                        <input type="text" name="q34_a_details" 
                                                            class="mt-1 w-full px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                            placeholder="e.g., Brother of the appointing authority"
                                                            value="{{ old('q34_a_details', $backgroundInfo->q34_a_details ?? '') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- 34b: Within fourth degree -->
                                            <div class="flex items-start pt-2 border-t border-gray-200 dark:border-gray-600">
                                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400 w-6">b.</span>
                                                <div class="flex-1">
                                                    <p class="text-sm text-gray-700 dark:text-gray-300 mb-2">Within the fourth degree (for Local Government Unit - Career Employees)?</p>
                                                    <div class="flex space-x-4">
                                                        <label class="inline-flex items-center">
                                                            <input type="radio" name="q34_b" value="yes" 
                                                                class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700"
                                                                {{ old('q34_b', $backgroundInfo->q34_b ?? '') === 'yes' ? 'checked' : '' }}>
                                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Yes</span>
                                                        </label>
                                                        <label class="inline-flex items-center">
                                                            <input type="radio" name="q34_b" value="no" 
                                                                class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700"
                                                                {{ old('q34_b', $backgroundInfo->q34_b ?? '') === 'no' ? 'checked' : '' }}>
                                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">No</span>
                                                        </label>
                                                    </div>
                                                    @if($backgroundInfo && $backgroundInfo->q34_b_details)
                                                        <div class="mt-2">
                                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                                <i class="bi bi-info-circle mr-1"></i>
                                                                Details: {{ $backgroundInfo->q34_b_details }}
                                                            </p>
                                                        </div>
                                                    @endif
                                                    <div class="mt-2 q34-details {{ old('q34_b', $backgroundInfo->q34_b ?? '') === 'yes' ? '' : 'hidden' }}">
                                                        <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Please specify relationship:</label>
                                                        <input type="text" name="q34_b_details" 
                                                            class="mt-1 w-full px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                            placeholder="e.g., Cousin of the department head"
                                                            value="{{ old('q34_b_details', $backgroundInfo->q34_b_details ?? '') }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Question 35: Administrative Offense & Criminal Charges -->
                            <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-5 border border-gray-200 dark:border-gray-600">
                                <div class="flex items-start">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-bold text-sm flex-shrink-0 mr-3 mt-0.5">
                                        35
                                    </span>
                                    <div class="flex-1 space-y-4">
                                        <!-- 35a -->
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-white mb-2">a. Have you ever been found guilty of any administrative offense?</p>
                                            <div class="flex space-x-4">
                                                <label class="inline-flex items-center">
                                                    <input type="radio" name="q35_a" value="yes" 
                                                        class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700"
                                                        {{ old('q35_a', $backgroundInfo->q35_a ?? '') === 'yes' ? 'checked' : '' }}>
                                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Yes</span>
                                                </label>
                                                <label class="inline-flex items-center">
                                                    <input type="radio" name="q35_a" value="no" 
                                                        class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700"
                                                        {{ old('q35_a', $backgroundInfo->q35_a ?? '') === 'no' ? 'checked' : '' }}>
                                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">No</span>
                                                </label>
                                            </div>
                                            <div class="mt-2 q35-details {{ old('q35_a', $backgroundInfo->q35_a ?? '') === 'yes' ? '' : 'hidden' }}">
                                                <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Please provide details:</label>
                                                <textarea name="q35_a_details" rows="2" 
                                                        class="mt-1 w-full px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                        placeholder="Please provide details of the administrative offense...">{{ old('q35_a_details', $backgroundInfo->q35_a_details ?? '') }}</textarea>
                                            </div>
                                        </div>

                                        <!-- 35b -->
                                        <div class="pt-3 border-t border-gray-200 dark:border-gray-600">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white mb-2">b. Have you been criminally charged before any court?</p>
                                            <div class="flex space-x-4">
                                                <label class="inline-flex items-center">
                                                    <input type="radio" name="q35_b" value="yes" 
                                                        class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700"
                                                        {{ old('q35_b', $backgroundInfo->q35_b ?? '') === 'yes' ? 'checked' : '' }}>
                                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Yes</span>
                                                </label>
                                                <label class="inline-flex items-center">
                                                    <input type="radio" name="q35_b" value="no" 
                                                        class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700"
                                                        {{ old('q35_b', $backgroundInfo->q35_b ?? '') === 'no' ? 'checked' : '' }}>
                                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">No</span>
                                                </label>
                                            </div>
                                            <div class="mt-2 q35-details {{ old('q35_b', $backgroundInfo->q35_b ?? '') === 'yes' ? '' : 'hidden' }}">
                                                <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Please provide details:</label>
                                                <textarea name="q35_b_details" rows="2" 
                                                        class="mt-1 w-full px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                        placeholder="Please provide details of the criminal charge...">{{ old('q35_b_details', $backgroundInfo->q35_b_details ?? '') }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Question 36: Conviction -->
                            <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-5 border border-gray-200 dark:border-gray-600">
                                <div class="flex items-start">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-bold text-sm flex-shrink-0 mr-3 mt-0.5">
                                        36
                                    </span>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white mb-2">
                                            Have you ever been convicted of any crime or violation of any law, decree, ordinance or regulation by any court or tribunal?
                                        </p>
                                        <div class="flex space-x-4">
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="q36" value="yes" 
                                                    class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700"
                                                    {{ old('q36', $backgroundInfo->q36 ?? '') === 'yes' ? 'checked' : '' }}>
                                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Yes</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="q36" value="no" 
                                                    class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700"
                                                    {{ old('q36', $backgroundInfo->q36 ?? '') === 'no' ? 'checked' : '' }}>
                                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">No</span>
                                            </label>
                                        </div>
                                        <div class="mt-2 q36-details {{ old('q36', $backgroundInfo->q36 ?? '') === 'yes' ? '' : 'hidden' }}">
                                            <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Please provide details:</label>
                                            <textarea name="q36_details" rows="2" 
                                                    class="mt-1 w-full px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                    placeholder="Please provide details of the conviction...">{{ old('q36_details', $backgroundInfo->q36_details ?? '') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Question 37: Separation from Service -->
                            <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-5 border border-gray-200 dark:border-gray-600">
                                <div class="flex items-start">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-bold text-sm flex-shrink-0 mr-3 mt-0.5">
                                        37
                                    </span>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white mb-2">
                                            Have you ever been separated from the service in any of the following modes: resignation, retirement, dropped from the rolls, dismissal, termination, end of term, finished contract or phased out (abolition) in the public or private sector?
                                        </p>
                                        <div class="flex space-x-4">
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="q37" value="yes" 
                                                    class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700"
                                                    {{ old('q37', $backgroundInfo->q37 ?? '') === 'yes' ? 'checked' : '' }}>
                                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Yes</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="q37" value="no" 
                                                    class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700"
                                                    {{ old('q37', $backgroundInfo->q37 ?? '') === 'no' ? 'checked' : '' }}>
                                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">No</span>
                                            </label>
                                        </div>
                                        <div class="mt-2 q37-details {{ old('q37', $backgroundInfo->q37 ?? '') === 'yes' ? '' : 'hidden' }}">
                                            <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Please provide details:</label>
                                            <textarea name="q37_details" rows="2" 
                                                    class="mt-1 w-full px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                    placeholder="Please provide details of the separation...">{{ old('q37_details', $backgroundInfo->q37_details ?? '') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Question 38: Election Candidate -->
                            <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-5 border border-gray-200 dark:border-gray-600">
                                <div class="flex items-start">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-bold text-sm flex-shrink-0 mr-3 mt-0.5">
                                        38
                                    </span>
                                    <div class="flex-1 space-y-4">
                                        <!-- 38a -->
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-white mb-2">a. Have you ever been a candidate in a national or local election held within the last year (except Barangay election)?</p>
                                            <div class="flex space-x-4">
                                                <label class="inline-flex items-center">
                                                    <input type="radio" name="q38_a" value="yes" 
                                                        class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700"
                                                        {{ old('q38_a', $backgroundInfo->q38_a ?? '') === 'yes' ? 'checked' : '' }}>
                                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Yes</span>
                                                </label>
                                                <label class="inline-flex items-center">
                                                    <input type="radio" name="q38_a" value="no" 
                                                        class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700"
                                                        {{ old('q38_a', $backgroundInfo->q38_a ?? '') === 'no' ? 'checked' : '' }}>
                                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">No</span>
                                                </label>
                                            </div>
                                            <div class="mt-2 q38-details {{ old('q38_a', $backgroundInfo->q38_a ?? '') === 'yes' ? '' : 'hidden' }}">
                                                <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Please provide details:</label>
                                                <input type="text" name="q38_a_details" 
                                                    class="mt-1 w-full px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                    placeholder="e.g., Candidate for Mayor, 2024"
                                                    value="{{ old('q38_a_details', $backgroundInfo->q38_a_details ?? '') }}">
                                            </div>
                                        </div>

                                        <!-- 38b -->
                                        <div class="pt-3 border-t border-gray-200 dark:border-gray-600">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white mb-2">b. Have you resigned from the government service during the three (3)-month period before the last election to promote/actively campaign for a national or local candidate?</p>
                                            <div class="flex space-x-4">
                                                <label class="inline-flex items-center">
                                                    <input type="radio" name="q38_b" value="yes" 
                                                        class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700"
                                                        {{ old('q38_b', $backgroundInfo->q38_b ?? '') === 'yes' ? 'checked' : '' }}>
                                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Yes</span>
                                                </label>
                                                <label class="inline-flex items-center">
                                                    <input type="radio" name="q38_b" value="no" 
                                                        class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700"
                                                        {{ old('q38_b', $backgroundInfo->q38_b ?? '') === 'no' ? 'checked' : '' }}>
                                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">No</span>
                                                </label>
                                            </div>
                                            <div class="mt-2 q38-details {{ old('q38_b', $backgroundInfo->q38_b ?? '') === 'yes' ? '' : 'hidden' }}">
                                                <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Please provide details:</label>
                                                <input type="text" name="q38_b_details" 
                                                    class="mt-1 w-full px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                    placeholder="e.g., Resigned in May 2023 to campaign for..."
                                                    value="{{ old('q38_b_details', $backgroundInfo->q38_b_details ?? '') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Question 39: Immigrant Status -->
                            <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-5 border border-gray-200 dark:border-gray-600">
                                <div class="flex items-start">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-bold text-sm flex-shrink-0 mr-3 mt-0.5">
                                        39
                                    </span>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white mb-2">
                                            Have you acquired the status of an immigrant or permanent resident of another country?
                                        </p>
                                        <div class="flex space-x-4">
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="q39" value="yes" 
                                                    class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700"
                                                    {{ old('q39', $backgroundInfo->q39 ?? '') === 'yes' ? 'checked' : '' }}>
                                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Yes</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="q39" value="no" 
                                                    class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700"
                                                    {{ old('q39', $backgroundInfo->q39 ?? '') === 'no' ? 'checked' : '' }}>
                                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">No</span>
                                            </label>
                                        </div>
                                        <div class="mt-2 q39-details {{ old('q39', $backgroundInfo->q39 ?? '') === 'yes' ? '' : 'hidden' }}">
                                            <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Please specify country:</label>
                                            <input type="text" name="q39_details" 
                                                class="mt-1 w-full px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                placeholder="e.g., United States, Canada"
                                                value="{{ old('q39_details', $backgroundInfo->q39_details ?? '') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Question 40: Special Status -->
                            <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-5 border border-gray-200 dark:border-gray-600">
                                <div class="flex items-start">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-bold text-sm flex-shrink-0 mr-3 mt-0.5">
                                        40
                                    </span>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white mb-3">
                                            Pursuant to: (a) Indigenous People's Act (RA 8371); (b) Magna Carta for Disabled Persons (RA 7277, as amended); and (c) Expanded Solo Parents Welfare Act (RA 11861), please answer the following items:
                                        </p>
                                        
                                        <div class="space-y-3 ml-4">
                                            <!-- 40a -->
                                            <div class="flex items-start">
                                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400 w-6">a.</span>
                                                <div class="flex-1">
                                                    <p class="text-sm text-gray-700 dark:text-gray-300 mb-2">Are you a member of any indigenous group?</p>
                                                    <div class="flex space-x-4">
                                                        <label class="inline-flex items-center">
                                                            <input type="radio" name="q40_a" value="yes" 
                                                                class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700"
                                                                {{ old('q40_a', $backgroundInfo->q40_a ?? '') === 'yes' ? 'checked' : '' }}>
                                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Yes</span>
                                                        </label>
                                                        <label class="inline-flex items-center">
                                                            <input type="radio" name="q40_a" value="no" 
                                                                class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700"
                                                                {{ old('q40_a', $backgroundInfo->q40_a ?? '') === 'no' ? 'checked' : '' }}>
                                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">No</span>
                                                        </label>
                                                    </div>
                                                    <div class="mt-2 q40-details {{ old('q40_a', $backgroundInfo->q40_a ?? '') === 'yes' ? '' : 'hidden' }}">
                                                        <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Please specify indigenous group:</label>
                                                        <input type="text" name="q40_a_details" 
                                                            class="mt-1 w-full px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                            placeholder="e.g., Igorot, Lumad, Mangyan"
                                                            value="{{ old('q40_a_details', $backgroundInfo->q40_a_details ?? '') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- 40b -->
                                            <div class="flex items-start pt-2 border-t border-gray-200 dark:border-gray-600">
                                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400 w-6">b.</span>
                                                <div class="flex-1">
                                                    <p class="text-sm text-gray-700 dark:text-gray-300 mb-2">Are you a person with disability?</p>
                                                    <div class="flex space-x-4">
                                                        <label class="inline-flex items-center">
                                                            <input type="radio" name="q40_b" value="yes" 
                                                                class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700"
                                                                {{ old('q40_b', $backgroundInfo->q40_b ?? '') === 'yes' ? 'checked' : '' }}>
                                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Yes</span>
                                                        </label>
                                                        <label class="inline-flex items-center">
                                                            <input type="radio" name="q40_b" value="no" 
                                                                class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700"
                                                                {{ old('q40_b', $backgroundInfo->q40_b ?? '') === 'no' ? 'checked' : '' }}>
                                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">No</span>
                                                        </label>
                                                    </div>
                                                    <div class="mt-2 q40-details {{ old('q40_b', $backgroundInfo->q40_b ?? '') === 'yes' ? '' : 'hidden' }}">
                                                        <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Please specify type of disability:</label>
                                                        <input type="text" name="q40_b_details" 
                                                            class="mt-1 w-full px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                            placeholder="e.g., Visual impairment, Mobility issue"
                                                            value="{{ old('q40_b_details', $backgroundInfo->q40_b_details ?? '') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- 40c -->
                                            <div class="flex items-start pt-2 border-t border-gray-200 dark:border-gray-600">
                                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400 w-6">c.</span>
                                                <div class="flex-1">
                                                    <p class="text-sm text-gray-700 dark:text-gray-300 mb-2">Are you a solo parent?</p>
                                                    <div class="flex space-x-4">
                                                        <label class="inline-flex items-center">
                                                            <input type="radio" name="q40_c" value="yes" 
                                                                class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700"
                                                                {{ old('q40_c', $backgroundInfo->q40_c ?? '') === 'yes' ? 'checked' : '' }}>
                                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Yes</span>
                                                        </label>
                                                        <label class="inline-flex items-center">
                                                            <input type="radio" name="q40_c" value="no" 
                                                                class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700"
                                                                {{ old('q40_c', $backgroundInfo->q40_c ?? '') === 'no' ? 'checked' : '' }}>
                                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">No</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <a href="{{ route('myprofile.show') }}" 
                                class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-xl transition-all duration-200">
                                    Cancel
                                </a>
                                <button type="submit" 
                                        class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                                    <i class="bi bi-save mr-2"></i>
                                    Save Background Information
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    // Tab functionality
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.tab-btn');
        const contents = document.querySelectorAll('.tab-content');

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active class from all tabs
                tabs.forEach(t => {
                    t.classList.remove('active');
                    t.classList.remove('text-indigo-600', 'dark:text-indigo-400', 'border-indigo-600', 'dark:border-indigo-400');
                    t.classList.add('text-gray-500', 'dark:text-gray-400', 'border-transparent');
                    t.setAttribute('aria-selected', 'false');
                });

                // Add active class to clicked tab
                this.classList.add('active');
                this.classList.add('text-indigo-600', 'dark:text-indigo-400', 'border-indigo-600', 'dark:border-indigo-400');
                this.classList.remove('text-gray-500', 'dark:text-gray-400', 'border-transparent');
                this.setAttribute('aria-selected', 'true');

                // Hide all tab contents
                contents.forEach(content => {
                    content.classList.add('hidden');
                    content.classList.remove('active');
                });

                // Show the corresponding tab content
                const tabId = this.getAttribute('data-tab');
                const targetContent = document.getElementById(`tab-${tabId}`);
                if (targetContent) {
                    targetContent.classList.remove('hidden');
                    targetContent.classList.add('active');
                }
            });
        });
    });

    // Delete confirmation for children
    function confirmDelete(childId) {
        if (confirm('Are you sure you want to delete this child record?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/myprofile/children/${childId}`;
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            form.appendChild(methodField);
            
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Store child data for editing
    let currentChildData = null;

    // Open Edit Modal
    function openEditChildModal(childId) {
        // Check if Swal is available
        if (typeof Swal !== 'undefined') {
            // Show loading state
            Swal.fire({
                title: 'Loading...',
                text: 'Fetching child data',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }

        // Fetch child data
        fetch(`/myprofile/children/${childId}/edit-data`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (typeof Swal !== 'undefined') {
                Swal.close();
            }
            currentChildData = data;
            populateChildForm(data);
            document.getElementById('editChildModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            console.log('Modal opened successfully');
        })
        .catch(error => {
            console.error('Error:', error);
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load child data. Please try again.',
                    confirmButtonColor: '#6366f1'
                });
            } else {
                alert('Failed to load child data. Please try again.');
            }
        });
    }

    // Close Edit Modal
    function closeEditChildModal() {
        const modal = document.getElementById('editChildModal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            const form = document.getElementById('editChildForm');
            if (form) {
                form.reset();
            }
            currentChildData = null;
            console.log('Modal closed');
        }
    }

    // Populate form with child data
    function populateChildForm(data) {
        const form = document.getElementById('editChildForm');
        if (form) {
            form.action = `/myprofile/children/${data.id}`;
        }
        
        const nameInput = document.getElementById('child_name');
        const dobInput = document.getElementById('child_dob');
        const sexSelect = document.getElementById('child_sex');
        
        if (nameInput) nameInput.value = data.name || '';
        if (dobInput) dobInput.value = data.date_of_birth || '';
        if (sexSelect) sexSelect.value = data.sex || '';
        
        console.log('Form populated with data:', data);
    }

    // Handle form submission
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('editChildForm');
        
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Show loading
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Updating...',
                        text: 'Please wait while we update the child record',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                }
                
                const formData = new FormData(this);
                const url = this.action;
                
                fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw new Error(err.message || 'Update failed');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Child record updated successfully',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                    
                    closeEditChildModal();
                    
                    // Reload the page to show updated data
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Update Failed',
                            text: error.message || 'There was an error updating the child record. Please try again.',
                            confirmButtonColor: '#6366f1'
                        });
                    } else {
                        alert('Update failed: ' + error.message);
                    }
                });
            });
        }
    });

    // Close modal on backdrop click
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('editChildModal');
        if (modal && e.target === modal) {
            closeEditChildModal();
        }
    });

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeEditChildModal();
        }
    });

    // Open Add Education Modal
    function openAddEducationModal() {
        const modal = document.getElementById('educationModal');
        const form = document.getElementById('educationForm');
        const title = document.getElementById('educationModalTitle');
        const submitText = document.getElementById('educationSubmitText');
        
        // Reset form
        form.reset();
        document.getElementById('education_method').value = 'POST';
        document.getElementById('education_id').value = '';
        form.action = '{{ route("myprofile.education.store") }}';
        
        title.textContent = 'Add Education';
        submitText.textContent = 'Add Education';
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    // Open Edit Education Modal
    function openEditEducationModal(educationId) {
        // Show loading
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Loading...',
                text: 'Fetching education data',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }
        
        fetch(`/myprofile/education/${educationId}/edit-data`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (typeof Swal !== 'undefined') {
                Swal.close();
            }
            
            const modal = document.getElementById('educationModal');
            const form = document.getElementById('educationForm');
            const title = document.getElementById('educationModalTitle');
            const submitText = document.getElementById('educationSubmitText');
            
            // Set form values
            document.getElementById('education_method').value = 'PUT';
            document.getElementById('education_id').value = data.id;
            form.action = `/myprofile/education/${data.id}`;
            
            document.getElementById('level').value = data.level || '';
            document.getElementById('school_name').value = data.school_name || '';
            document.getElementById('degree_course').value = data.degree_course || '';
            document.getElementById('period_from').value = data.period_from || '';
            document.getElementById('period_to').value = data.period_to || '';
            document.getElementById('year_graduated').value = data.year_graduated || '';
            document.getElementById('scholarship_honors').value = data.scholarship_honors || '';
            document.getElementById('highest_level_earned').value = data.highest_level_earned || '';
            
            title.textContent = 'Edit Education';
            submitText.textContent = 'Update Education';
            
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        })
        .catch(error => {
            console.error('Error:', error);
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load education data. Please try again.',
                    confirmButtonColor: '#f59e0b'
                });
            } else {
                alert('Failed to load education data. Please try again.');
            }
        });
    }

    // Close Education Modal
    function closeEducationModal() {
        const modal = document.getElementById('educationModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        document.getElementById('educationForm').reset();
    }

    // Handle Education Form Submission
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('educationForm');
        
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Validate year range
                const yearFrom = parseInt(document.getElementById('period_from').value);
                const yearTo = parseInt(document.getElementById('period_to').value);
                
                if (yearFrom > yearTo) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid Year',
                            text: 'Year From cannot be greater than Year To.',
                            confirmButtonColor: '#f59e0b'
                        });
                    } else {
                        alert('Year From cannot be greater than Year To.');
                    }
                    return;
                }
                
                // Show loading
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Saving...',
                        text: 'Please wait while we save the education record',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                }
                
                const formData = new FormData(this);
                const url = this.action;
                const method = document.getElementById('education_method').value;
                
                fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw new Error(err.message || 'Save failed');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message || 'Education record saved successfully',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                    
                    closeEducationModal();
                    
                    // Reload the page to show updated data
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Save Failed',
                            text: error.message || 'There was an error saving the education record. Please try again.',
                            confirmButtonColor: '#f59e0b'
                        });
                    } else {
                        alert('Save failed: ' + error.message);
                    }
                });
            });
        }
    });

    // Delete Education Confirmation
    function confirmDeleteEducation(educationId) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Delete Education Record',
                text: 'Are you sure you want to delete this education record? This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteEducation(educationId);
                }
            });
        } else {
            if (confirm('Are you sure you want to delete this education record?')) {
                deleteEducation(educationId);
            }
        }
    }

    // Delete Education
    function deleteEducation(educationId) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Deleting...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/myprofile/education/${educationId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]')?.content || '';
        form.appendChild(csrfToken);
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);
        
        document.body.appendChild(form);
        form.submit();
    }

    // Open Add Work Modal
    function openAddWorkModal() {
        const modal = document.getElementById('workModal');
        const form = document.getElementById('workForm');
        const title = document.getElementById('workModalTitle');
        const submitText = document.getElementById('workSubmitText');
        
        // Reset form
        form.reset();
        document.getElementById('work_method').value = 'POST';
        document.getElementById('work_id').value = '';
        form.action = '{{ route("myprofile.work.store") }}';
        
        // Set default date from to today
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('inclusive_from').value = today;
        
        title.textContent = 'Add Work Experience';
        submitText.textContent = 'Save Work Experience';
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    // Open Edit Work Modal
    function openEditWorkModal(workId) {
        // Show loading
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Loading...',
                text: 'Fetching work experience data',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }
        
        fetch(`/myprofile/work/${workId}/edit-data`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (typeof Swal !== 'undefined') {
                Swal.close();
            }
            
            const modal = document.getElementById('workModal');
            const form = document.getElementById('workForm');
            const title = document.getElementById('workModalTitle');
            const submitText = document.getElementById('workSubmitText');
            
            // Set form values
            document.getElementById('work_method').value = 'PUT';
            document.getElementById('work_id').value = data.id;
            form.action = `/myprofile/work/${data.id}`;
            
            document.getElementById('position_title').value = data.position_title || '';
            document.getElementById('order').value = data.order || '';
            document.getElementById('department_agency_office').value = data.department_agency_office || '';
            document.getElementById('inclusive_from').value = data.inclusive_from || '';
            document.getElementById('inclusive_to').value = data.inclusive_to || '';
            document.getElementById('monthly_salary').value = data.monthly_salary || '';
            document.getElementById('salary_grade').value = data.salary_grade || '';
            document.getElementById('status_of_appointment').value = data.status_of_appointment || '';
            document.getElementById('is_gov').value = data.is_gov ? 'Yes' : 'No';
            
            title.textContent = 'Edit Work Experience';
            submitText.textContent = 'Update Work Experience';
            
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        })
        .catch(error => {
            console.error('Error:', error);
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load work experience data. Please try again.',
                    confirmButtonColor: '#06b6d4'
                });
            } else {
                alert('Failed to load work experience data. Please try again.');
            }
        });
    }

    // Close Work Modal
    function closeWorkModal() {
        const modal = document.getElementById('workModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        document.getElementById('workForm').reset();
    }

    // Handle Work Form Submission
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('workForm');
        
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Validate date range
                const fromDate = document.getElementById('inclusive_from').value;
                const toDate = document.getElementById('inclusive_to').value;
                
                if (fromDate && toDate && new Date(toDate) < new Date(fromDate)) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid Date Range',
                            text: 'Date To cannot be earlier than Date From.',
                            confirmButtonColor: '#06b6d4'
                        });
                    } else {
                        alert('Date To cannot be earlier than Date From.');
                    }
                    return;
                }
                
                // Show loading
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Saving...',
                        text: 'Please wait while we save the work experience',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                }
                
                const formData = new FormData(this);
                const url = this.action;
                const method = document.getElementById('work_method').value;
                
                // Ensure is_gov is sent as 0 or 1
                formData.set('is_gov', document.getElementById('is_gov').value);
                
                fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw new Error(err.message || 'Save failed');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message || 'Work experience saved successfully',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                    
                    closeWorkModal();
                    
                    // Reload the page to show updated data
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Save Failed',
                            text: error.message || 'There was an error saving the work experience. Please try again.',
                            confirmButtonColor: '#06b6d4'
                        });
                    } else {
                        alert('Save failed: ' + error.message);
                    }
                });
            });
        }
    });

    // Delete Work Experience Confirmation
    function confirmDeleteWork(workId) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Delete Work Experience',
                text: 'Are you sure you want to delete this work experience? This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteWork(workId);
                }
            });
        } else {
            if (confirm('Are you sure you want to delete this work experience?')) {
                deleteWork(workId);
            }
        }
    }

    // Delete Work Experience
    function deleteWork(workId) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Deleting...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/myprofile/work/${workId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]')?.content || '';
        form.appendChild(csrfToken);
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);
        
        document.body.appendChild(form);
        form.submit();
    }

    // Open Add Eligibility Modal
    function openAddEligibilityModal() {
        const modal = document.getElementById('eligibilityModal');
        const form = document.getElementById('eligibilityForm');
        const title = document.getElementById('eligibilityModalTitle');
        const submitText = document.getElementById('eligibilitySubmitText');
        
        // Reset form
        form.reset();
        document.getElementById('eligibility_method').value = 'POST';
        document.getElementById('eligibility_id').value = '';
        form.action = '{{ route("myprofile.eligibility.store") }}';
        
        title.textContent = 'Add Eligibility';
        submitText.textContent = 'Save Eligibility';
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    // Open Edit Eligibility Modal
    function openEditEligibilityModal(eligibilityId) {
        // Show loading
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Loading...',
                text: 'Fetching eligibility data',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }
        
        fetch(`/myprofile/eligibility/${eligibilityId}/edit-data`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (typeof Swal !== 'undefined') {
                Swal.close();
            }
            
            const modal = document.getElementById('eligibilityModal');
            const form = document.getElementById('eligibilityForm');
            const title = document.getElementById('eligibilityModalTitle');
            const submitText = document.getElementById('eligibilitySubmitText');
            
            // Set form values
            document.getElementById('eligibility_method').value = 'PUT';
            document.getElementById('eligibility_id').value = data.id;
            form.action = `/myprofile/eligibility/${data.id}`;
            
            document.getElementById('career_service').value = data.career_service || '';
            document.getElementById('eligibility_order').value = data.order || '';
            document.getElementById('rating').value = data.rating || '';
            document.getElementById('examination_date').value = data.examination_date || '';
            document.getElementById('examination_place').value = data.examination_place || '';
            document.getElementById('license_number').value = data.license_number || '';
            document.getElementById('license_date_validity').value = data.license_date_validity || '';
            
            title.textContent = 'Edit Eligibility';
            submitText.textContent = 'Update Eligibility';
            
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        })
        .catch(error => {
            console.error('Error:', error);
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load eligibility data. Please try again.',
                    confirmButtonColor: '#f59e0b'
                });
            } else {
                alert('Failed to load eligibility data. Please try again.');
            }
        });
    }

    // Close Eligibility Modal
    function closeEligibilityModal() {
        const modal = document.getElementById('eligibilityModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        document.getElementById('eligibilityForm').reset();
    }

    // Handle Eligibility Form Submission
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('eligibilityForm');
        
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Show loading
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Saving...',
                        text: 'Please wait while we save the eligibility record',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                }
                
                const formData = new FormData(this);
                const url = this.action;
                
                fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw new Error(err.message || 'Save failed');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message || 'Eligibility saved successfully',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                    
                    closeEligibilityModal();
                    
                    // Reload the page to show updated data
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Save Failed',
                            text: error.message || 'There was an error saving the eligibility. Please try again.',
                            confirmButtonColor: '#f59e0b'
                        });
                    } else {
                        alert('Save failed: ' + error.message);
                    }
                });
            });
        }
    });

    // Delete Eligibility Confirmation
    function confirmDeleteEligibility(eligibilityId) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Delete Eligibility',
                text: 'Are you sure you want to delete this eligibility record? This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteEligibility(eligibilityId);
                }
            });
        } else {
            if (confirm('Are you sure you want to delete this eligibility record?')) {
                deleteEligibility(eligibilityId);
            }
        }
    }

    // Delete Eligibility
    function deleteEligibility(eligibilityId) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Deleting...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/myprofile/eligibility/${eligibilityId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]')?.content || '';
        form.appendChild(csrfToken);
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);
        
        document.body.appendChild(form);
        form.submit();
    }

    // Open Add Training Modal
    function openAddTrainingModal() {
        const modal = document.getElementById('trainingModal');
        const form = document.getElementById('trainingForm');
        const title = document.getElementById('trainingModalTitle');
        const submitText = document.getElementById('trainingSubmitText');
        
        // Reset form
        form.reset();
        document.getElementById('training_method').value = 'POST';
        document.getElementById('training_id').value = '';
        form.action = '{{ route("myprofile.training.store") }}';
        
        // Set default date from to today
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('inclusive_from').value = '';
        
        title.textContent = 'Add Training';
        submitText.textContent = 'Save Training';
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    // Open Edit Training Modal
    function openEditTrainingModal(trainingId) {
        // Show loading
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Loading...',
                text: 'Fetching training data',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }
        
        fetch(`/myprofile/training/${trainingId}/edit-data`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (typeof Swal !== 'undefined') {
                Swal.close();
            }
            
            const modal = document.getElementById('trainingModal');
            const form = document.getElementById('trainingForm');
            const title = document.getElementById('trainingModalTitle');
            const submitText = document.getElementById('trainingSubmitText');
            
            // Set form values
            document.getElementById('training_method').value = 'PUT';
            document.getElementById('training_id').value = data.id;
            form.action = `/myprofile/training/${data.id}`;
            
            document.getElementById('title_of_program').value = data.title_of_program || '';
            document.getElementById('training_order').value = data.order || '';
            document.getElementById('inclusive_from').value = data.inclusive_from || '';
            document.getElementById('inclusive_to').value = data.inclusive_to || '';
            document.getElementById('number_of_hours').value = data.number_of_hours || '';
            document.getElementById('type_of_ld').value = data.type_of_ld || '';
            document.getElementById('conducted_by').value = data.conducted_by || '';
            
            title.textContent = 'Edit Training';
            submitText.textContent = 'Update Training';
            
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        })
        .catch(error => {
            console.error('Error:', error);
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load training data. Please try again.',
                    confirmButtonColor: '#14b8a6'
                });
            } else {
                alert('Failed to load training data. Please try again.');
            }
        });
    }

    // Close Training Modal
    function closeTrainingModal() {
        const modal = document.getElementById('trainingModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        document.getElementById('trainingForm').reset();
    }

    // Handle Training Form Submission
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('trainingForm');
        
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Validate date range
                const fromDate = document.getElementById('inclusive_from').value;
                const toDate = document.getElementById('inclusive_to').value;
                
                if (fromDate && toDate && new Date(toDate) < new Date(fromDate)) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid Date Range',
                            text: 'Date To cannot be earlier than Date From.',
                            confirmButtonColor: '#14b8a6'
                        });
                    } else {
                        alert('Date To cannot be earlier than Date From.');
                    }
                    return;
                }
                
                // Validate hours
                const hours = parseFloat(document.getElementById('number_of_hours').value);
                if (hours <= 0) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid Hours',
                            text: 'Number of hours must be greater than 0.',
                            confirmButtonColor: '#14b8a6'
                        });
                    } else {
                        alert('Number of hours must be greater than 0.');
                    }
                    return;
                }
                
                // Show loading
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Saving...',
                        text: 'Please wait while we save the training record',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                }
                
                const formData = new FormData(this);
                const url = this.action;
                
                fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw new Error(err.message || 'Save failed');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message || 'Training saved successfully',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                    
                    closeTrainingModal();
                    
                    // Reload the page to show updated data
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Save Failed',
                            text: error.message || 'There was an error saving the training. Please try again.',
                            confirmButtonColor: '#14b8a6'
                        });
                    } else {
                        alert('Save failed: ' + error.message);
                    }
                });
            });
        }
    });

    // Delete Training Confirmation
    function confirmDeleteTraining(trainingId) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Delete Training',
                text: 'Are you sure you want to delete this training record? This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteTraining(trainingId);
                }
            });
        } else {
            if (confirm('Are you sure you want to delete this training record?')) {
                deleteTraining(trainingId);
            }
        }
    }

    // Delete Training
    function deleteTraining(trainingId) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Deleting...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/myprofile/training/${trainingId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]')?.content || '';
        form.appendChild(csrfToken);
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);
        
        document.body.appendChild(form);
        form.submit();
    }

    // Date Helper Functions
    function getTodayDate() {
        return new Date().toISOString().split('T')[0];
    }

    function formatDateForInput(date) {
        if (!date) return '';
        try {
            const d = new Date(date);
            if (!isNaN(d.getTime())) {
                return d.toISOString().split('T')[0];
            }
        } catch (e) {
            return '';
        }
        return '';
    }

    // Open Add Voluntary Work Modal
    function openAddVoluntaryWorkModal() {
        const modal = document.getElementById('voluntaryWorkModal');
        const form = document.getElementById('voluntaryWorkForm');
        const title = document.getElementById('voluntaryWorkModalTitle');
        const submitText = document.getElementById('voluntaryWorkSubmitText');
        
        // Reset form
        form.reset();
        document.getElementById('voluntary_work_method').value = 'POST';
        document.getElementById('voluntary_work_id').value = '';
        form.action = '{{ route("myprofile.voluntary-work.store") }}';
        
        // Set default dates
        const today = getTodayDate();
        document.getElementById('voluntary_inclusive_from').value = today;
        document.getElementById('voluntary_inclusive_to').value = '';
        
        title.textContent = 'Add Voluntary Work';
        submitText.textContent = 'Save Voluntary Work';
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    // Open Edit Voluntary Work Modal
    function openEditVoluntaryWorkModal(workId) {
        // Show loading
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Loading...',
                text: 'Fetching voluntary work data',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }
        
        fetch(`/myprofile/voluntary-work/${workId}/edit-data`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (typeof Swal !== 'undefined') {
                Swal.close();
            }
            
            const modal = document.getElementById('voluntaryWorkModal');
            const form = document.getElementById('voluntaryWorkForm');
            const title = document.getElementById('voluntaryWorkModalTitle');
            const submitText = document.getElementById('voluntaryWorkSubmitText');
            
            // Set form values
            document.getElementById('voluntary_work_method').value = 'PUT';
            document.getElementById('voluntary_work_id').value = data.id;
            form.action = `/myprofile/voluntary-work/${data.id}`;
            
            document.getElementById('organization_name').value = data.organization_name || '';
            document.getElementById('voluntary_work_order').value = data.order || '';
            document.getElementById('organization_address').value = data.organization_address || '';
            document.getElementById('position_nature_of_work').value = data.position_nature_of_work || '';
            document.getElementById('voluntary_inclusive_from').value = formatDateForInput(data.inclusive_from);
            document.getElementById('voluntary_inclusive_to').value = formatDateForInput(data.inclusive_to);
            document.getElementById('voluntary_number_of_hours').value = data.number_of_hours || '';
            
            title.textContent = 'Edit Voluntary Work';
            submitText.textContent = 'Update Voluntary Work';
            
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        })
        .catch(error => {
            console.error('Error:', error);
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load voluntary work data. Please try again.',
                    confirmButtonColor: '#8b5cf6'
                });
            } else {
                alert('Failed to load voluntary work data. Please try again.');
            }
        });
    }

    // Close Voluntary Work Modal
    function closeVoluntaryWorkModal() {
        const modal = document.getElementById('voluntaryWorkModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        document.getElementById('voluntaryWorkForm').reset();
    }

    // Handle Voluntary Work Form Submission
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('voluntaryWorkForm');
        
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Validate date range
                const fromDate = document.getElementById('voluntary_inclusive_from').value;
                const toDate = document.getElementById('voluntary_inclusive_to').value;
                
                if (fromDate && toDate && new Date(toDate) < new Date(fromDate)) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid Date Range',
                            text: 'Date To cannot be earlier than Date From.',
                            confirmButtonColor: '#8b5cf6'
                        });
                    } else {
                        alert('Date To cannot be earlier than Date From.');
                    }
                    return;
                }
                
                // Show loading
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Saving...',
                        text: 'Please wait while we save the voluntary work record',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                }
                
                const formData = new FormData(this);
                const url = this.action;
                
                fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw new Error(err.message || 'Save failed');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message || 'Voluntary work saved successfully',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                    
                    closeVoluntaryWorkModal();
                    
                    // Reload the page to show updated data
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Save Failed',
                            text: error.message || 'There was an error saving the voluntary work. Please try again.',
                            confirmButtonColor: '#8b5cf6'
                        });
                    } else {
                        alert('Save failed: ' + error.message);
                    }
                });
            });
        }
    });

    // Delete Voluntary Work Confirmation
    function confirmDeleteVoluntaryWork(workId) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Delete Voluntary Work',
                text: 'Are you sure you want to delete this voluntary work record? This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteVoluntaryWork(workId);
                }
            });
        } else {
            if (confirm('Are you sure you want to delete this voluntary work record?')) {
                deleteVoluntaryWork(workId);
            }
        }
    }

    // Delete Voluntary Work
    function deleteVoluntaryWork(workId) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Deleting...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/myprofile/voluntary-work/${workId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]')?.content || '';
        form.appendChild(csrfToken);
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);
        
        document.body.appendChild(form);
        form.submit();
    }

    // Open Add Skill Modal
    function openAddSkillModal() {
        const modal = document.getElementById('skillModal');
        const form = document.getElementById('skillForm');
        const title = document.getElementById('skillModalTitle');
        const submitText = document.getElementById('skillSubmitText');
        
        // Reset form
        form.reset();
        document.getElementById('skill_method').value = 'POST';
        document.getElementById('skill_id').value = '';
        form.action = '{{ route("myprofile.skill.store") }}';
        
        title.textContent = 'Add Skill / Hobby';
        submitText.textContent = 'Save Skill';
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Focus on the textarea
        setTimeout(() => {
            document.getElementById('skill_hobby').focus();
        }, 300);
    }

    // Open Edit Skill Modal
    function openEditSkillModal(skillId) {
        // Show loading
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Loading...',
                text: 'Fetching skill data',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }
        
        fetch(`/myprofile/skill/${skillId}/edit-data`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (typeof Swal !== 'undefined') {
                Swal.close();
            }
            
            const modal = document.getElementById('skillModal');
            const form = document.getElementById('skillForm');
            const title = document.getElementById('skillModalTitle');
            const submitText = document.getElementById('skillSubmitText');
            
            // Set form values
            document.getElementById('skill_method').value = 'PUT';
            document.getElementById('skill_id').value = data.id;
            form.action = `/myprofile/skill/${data.id}`;
            
            document.getElementById('skill_hobby').value = data.skill_hobby || '';
            
            title.textContent = 'Edit Skill / Hobby';
            submitText.textContent = 'Update Skill';
            
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            // Focus on the textarea
            setTimeout(() => {
                document.getElementById('skill_hobby').focus();
            }, 300);
        })
        .catch(error => {
            console.error('Error:', error);
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load skill data. Please try again.',
                    confirmButtonColor: '#ef4444'
                });
            } else {
                alert('Failed to load skill data. Please try again.');
            }
        });
    }

    // Close Skill Modal
    function closeSkillModal() {
        const modal = document.getElementById('skillModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        document.getElementById('skillForm').reset();
    }

    // Handle Skill Form Submission
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('skillForm');
        
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Validate
                const skill = document.getElementById('skill_hobby').value.trim();
                if (!skill) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            text: 'Please enter a skill or hobby.',
                            confirmButtonColor: '#ef4444'
                        });
                    } else {
                        alert('Please enter a skill or hobby.');
                    }
                    return;
                }
                
                // Show loading
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Saving...',
                        text: 'Please wait while we save the skill',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                }
                
                const formData = new FormData(this);
                const url = this.action;
                
                fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw new Error(err.message || 'Save failed');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message || 'Skill saved successfully',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                    
                    closeSkillModal();
                    
                    // Reload the page to show updated data
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Save Failed',
                            text: error.message || 'There was an error saving the skill. Please try again.',
                            confirmButtonColor: '#ef4444'
                        });
                    } else {
                        alert('Save failed: ' + error.message);
                    }
                });
            });
        }
    });

    // Delete Skill Confirmation
    function confirmDeleteSkill(skillId) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Delete Skill',
                text: 'Are you sure you want to delete this skill? This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteSkill(skillId);
                }
            });
        } else {
            if (confirm('Are you sure you want to delete this skill?')) {
                deleteSkill(skillId);
            }
        }
    }

    // Delete Skill
    function deleteSkill(skillId) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Deleting...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/myprofile/skill/${skillId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]')?.content || '';
        form.appendChild(csrfToken);
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);
        
        document.body.appendChild(form);
        form.submit();
    }

    // Open Add Distinction Modal
    function openAddDistinctionModal() {
        const modal = document.getElementById('distinctionModal');
        const form = document.getElementById('distinctionForm');
        const title = document.getElementById('distinctionModalTitle');
        const submitText = document.getElementById('distinctionSubmitText');
        
        // Reset form
        form.reset();
        document.getElementById('distinction_method').value = 'POST';
        document.getElementById('distinction_id').value = '';
        form.action = '{{ route("myprofile.distinction.store") }}';
        
        title.textContent = 'Add Academic Distinction';
        submitText.textContent = 'Save Distinction';
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Focus on the textarea
        setTimeout(() => {
            document.getElementById('distinctions').focus();
        }, 300);
    }

    // Open Edit Distinction Modal
    function openEditDistinctionModal(distinctionId) {
        // Show loading
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Loading...',
                text: 'Fetching distinction data',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }
        
        fetch(`/myprofile/distinction/${distinctionId}/edit-data`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (typeof Swal !== 'undefined') {
                Swal.close();
            }
            
            const modal = document.getElementById('distinctionModal');
            const form = document.getElementById('distinctionForm');
            const title = document.getElementById('distinctionModalTitle');
            const submitText = document.getElementById('distinctionSubmitText');
            
            // Set form values
            document.getElementById('distinction_method').value = 'PUT';
            document.getElementById('distinction_id').value = data.id;
            form.action = `/myprofile/distinction/${data.id}`;
            
            document.getElementById('distinctions').value = data.distinctions || '';
            
            title.textContent = 'Edit Academic Distinction';
            submitText.textContent = 'Update Distinction';
            
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            // Focus on the textarea
            setTimeout(() => {
                document.getElementById('distinctions').focus();
            }, 300);
        })
        .catch(error => {
            console.error('Error:', error);
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load distinction data. Please try again.',
                    confirmButtonColor: '#14b8a6'
                });
            } else {
                alert('Failed to load distinction data. Please try again.');
            }
        });
    }

    // Close Distinction Modal
    function closeDistinctionModal() {
        const modal = document.getElementById('distinctionModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        document.getElementById('distinctionForm').reset();
    }

    // Handle Distinction Form Submission
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('distinctionForm');
        
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Validate
                const distinction = document.getElementById('distinctions').value.trim();
                if (!distinction) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            text: 'Please enter a distinction or award.',
                            confirmButtonColor: '#14b8a6'
                        });
                    } else {
                        alert('Please enter a distinction or award.');
                    }
                    return;
                }
                
                // Show loading
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Saving...',
                        text: 'Please wait while we save the distinction',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                }
                
                const formData = new FormData(this);
                const url = this.action;
                
                fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw new Error(err.message || 'Save failed');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message || 'Distinction saved successfully',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                    
                    closeDistinctionModal();
                    
                    // Reload the page to show updated data
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Save Failed',
                            text: error.message || 'There was an error saving the distinction. Please try again.',
                            confirmButtonColor: '#14b8a6'
                        });
                    } else {
                        alert('Save failed: ' + error.message);
                    }
                });
            });
        }
    });

    // Delete Distinction Confirmation
    function confirmDeleteDistinction(distinctionId) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Delete Distinction',
                text: 'Are you sure you want to delete this distinction? This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteDistinction(distinctionId);
                }
            });
        } else {
            if (confirm('Are you sure you want to delete this distinction?')) {
                deleteDistinction(distinctionId);
            }
        }
    }

    // Delete Distinction
    function deleteDistinction(distinctionId) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Deleting...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/myprofile/distinction/${distinctionId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]')?.content || '';
        form.appendChild(csrfToken);
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);
        
        document.body.appendChild(form);
        form.submit();
    }

    // Open Add Organization Modal
    function openAddOrganizationModal() {
        const modal = document.getElementById('organizationModal');
        const form = document.getElementById('organizationForm');
        const title = document.getElementById('organizationModalTitle');
        const submitText = document.getElementById('organizationSubmitText');
        
        // Reset form
        form.reset();
        document.getElementById('organization_method').value = 'POST';
        document.getElementById('organization_id').value = '';
        form.action = '{{ route("myprofile.organization.store") }}';
        
        title.textContent = 'Add Organization';
        submitText.textContent = 'Save Organization';
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Focus on the textarea
        setTimeout(() => {
            document.getElementById('organization').focus();
        }, 300);
    }

    // Open Edit Organization Modal
    function openEditOrganizationModal(organizationId) {
        // Show loading
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Loading...',
                text: 'Fetching organization data',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }
        
        fetch(`/myprofile/organization/${organizationId}/edit-data`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (typeof Swal !== 'undefined') {
                Swal.close();
            }
            
            const modal = document.getElementById('organizationModal');
            const form = document.getElementById('organizationForm');
            const title = document.getElementById('organizationModalTitle');
            const submitText = document.getElementById('organizationSubmitText');
            
            // Set form values
            document.getElementById('organization_method').value = 'PUT';
            document.getElementById('organization_id').value = data.id;
            form.action = `/myprofile/organization/${data.id}`;
            
            document.getElementById('organization').value = data.organization || '';
            
            title.textContent = 'Edit Organization';
            submitText.textContent = 'Update Organization';
            
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            // Focus on the textarea
            setTimeout(() => {
                document.getElementById('organization').focus();
            }, 300);
        })
        .catch(error => {
            console.error('Error:', error);
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load organization data. Please try again.',
                    confirmButtonColor: '#8b5cf6'
                });
            } else {
                alert('Failed to load organization data. Please try again.');
            }
        });
    }

    // Close Organization Modal
    function closeOrganizationModal() {
        const modal = document.getElementById('organizationModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        document.getElementById('organizationForm').reset();
    }

    // Handle Organization Form Submission
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('organizationForm');
        
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Validate
                const organization = document.getElementById('organization').value.trim();
                if (!organization) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            text: 'Please enter an organization name.',
                            confirmButtonColor: '#8b5cf6'
                        });
                    } else {
                        alert('Please enter an organization name.');
                    }
                    return;
                }
                
                // Show loading
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Saving...',
                        text: 'Please wait while we save the organization',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                }
                
                const formData = new FormData(this);
                const url = this.action;
                
                fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw new Error(err.message || 'Save failed');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message || 'Organization saved successfully',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                    
                    closeOrganizationModal();
                    
                    // Reload the page to show updated data
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Save Failed',
                            text: error.message || 'There was an error saving the organization. Please try again.',
                            confirmButtonColor: '#8b5cf6'
                        });
                    } else {
                        alert('Save failed: ' + error.message);
                    }
                });
            });
        }
    });

    // Delete Organization Confirmation
    function confirmDeleteOrganization(organizationId) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Delete Organization',
                text: 'Are you sure you want to delete this organization? This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteOrganization(organizationId);
                }
            });
        } else {
            if (confirm('Are you sure you want to delete this organization?')) {
                deleteOrganization(organizationId);
            }
        }
    }

    // Delete Organization
    function deleteOrganization(organizationId) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Deleting...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/myprofile/organization/${organizationId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]')?.content || '';
        form.appendChild(csrfToken);
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);
        
        document.body.appendChild(form);
        form.submit();
    }

    function openAddReferenceModal() {
    const modal = document.getElementById('referenceModal');
    const form = document.getElementById('referenceForm');
    const title = document.getElementById('referenceModalTitle');
    const submitText = document.getElementById('referenceSubmitText');
    
    // Reset form
    form.reset();
    document.getElementById('reference_method').value = 'POST';
    document.getElementById('reference_id').value = '';
    form.action = '{{ route("myprofile.reference.store") }}';
    
    title.textContent = 'Add Reference';
    submitText.textContent = 'Save Reference';
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Focus on the first input
    setTimeout(() => {
        document.getElementById('reference_name').focus();
    }, 300);
}

function openAddReferenceModal() {
    const modal = document.getElementById('referenceModal');
    const form = document.getElementById('referenceForm');
    const title = document.getElementById('referenceModalTitle');
    const submitText = document.getElementById('referenceSubmitText');
    
    // Reset form
    form.reset();
    document.getElementById('reference_method').value = 'POST';
    document.getElementById('reference_id').value = '';
    form.action = '{{ route("myprofile.reference.store") }}';
    
    title.textContent = 'Add Reference';
    submitText.textContent = 'Save Reference';
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Focus on the first input
    setTimeout(() => {
        document.getElementById('reference_name').focus();
    }, 300);
}

// Open Edit Reference Modal
function openEditReferenceModal(referenceId) {
    // Show loading
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Loading...',
            text: 'Fetching reference data',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }
    
    fetch(`/myprofile/reference/${referenceId}/edit-data`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (typeof Swal !== 'undefined') {
            Swal.close();
        }
        
        const modal = document.getElementById('referenceModal');
        const form = document.getElementById('referenceForm');
        const title = document.getElementById('referenceModalTitle');
        const submitText = document.getElementById('referenceSubmitText');
        
        // Set form values
        document.getElementById('reference_method').value = 'PUT';
        document.getElementById('reference_id').value = data.id;
        form.action = `/myprofile/reference/${data.id}`;
        
        document.getElementById('reference_name').value = data.name || '';
        document.getElementById('reference_order').value = data.order || '';
        document.getElementById('reference_occupation').value = data.occupation || '';
        document.getElementById('reference_contact').value = data.contact_number || '';
        document.getElementById('reference_email').value = data.email || '';
        document.getElementById('reference_address').value = data.address || '';
        
        title.textContent = 'Edit Reference';
        submitText.textContent = 'Update Reference';
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Focus on the first input
        setTimeout(() => {
            document.getElementById('reference_name').focus();
        }, 300);
    })
    .catch(error => {
        console.error('Error:', error);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load reference data. Please try again.',
                confirmButtonColor: '#ef4444'
            });
        } else {
            alert('Failed to load reference data. Please try again.');
        }
    });
}

// Close Reference Modal
function closeReferenceModal() {
    const modal = document.getElementById('referenceModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.getElementById('referenceForm').reset();
}

// Handle Reference Form Submission
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('referenceForm');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate
            const name = document.getElementById('reference_name').value.trim();
            const occupation = document.getElementById('reference_occupation').value.trim();
            const contact = document.getElementById('reference_contact').value.trim();
            const email = document.getElementById('reference_email').value.trim();
            
            if (!name) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Please enter the reference name.',
                        confirmButtonColor: '#ef4444'
                    });
                } else {
                    alert('Please enter the reference name.');
                }
                return;
            }
            
            if (!occupation) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Please enter the reference occupation.',
                        confirmButtonColor: '#ef4444'
                    });
                } else {
                    alert('Please enter the reference occupation.');
                }
                return;
            }
            
            if (!contact) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Please enter the reference contact number.',
                        confirmButtonColor: '#ef4444'
                    });
                } else {
                    alert('Please enter the reference contact number.');
                }
                return;
            }
            
            // Validate email format if provided
            if (email && !isValidEmail(email)) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Please enter a valid email address.',
                        confirmButtonColor: '#ef4444'
                    });
                } else {
                    alert('Please enter a valid email address.');
                }
                return;
            }
            
            // Show loading
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Saving...',
                    text: 'Please wait while we save the reference',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            }
            
            const formData = new FormData(this);
            const url = this.action;
            
            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        throw new Error(err.message || 'Save failed');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message || 'Reference saved successfully',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
                
                closeReferenceModal();
                
                // Reload the page to show updated data
                setTimeout(() => {
                    location.reload();
                }, 1500);
            })
            .catch(error => {
                console.error('Error:', error);
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Save Failed',
                        text: error.message || 'There was an error saving the reference. Please try again.',
                        confirmButtonColor: '#ef4444'
                    });
                } else {
                    alert('Save failed: ' + error.message);
                }
            });
        });
    }
});

// Helper function to validate email
function isValidEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

// Delete Reference Confirmation
function confirmDeleteReference(referenceId) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Delete Reference',
            text: 'Are you sure you want to delete this reference? This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                deleteReference(referenceId);
            }
        });
    } else {
        if (confirm('Are you sure you want to delete this reference?')) {
            deleteReference(referenceId);
        }
    }
}

// Delete Reference
function deleteReference(referenceId) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Deleting...',
            text: 'Please wait',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/myprofile/reference/${referenceId}`;
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = document.querySelector('meta[name="csrf-token"]')?.content || '';
    form.appendChild(csrfToken);
    
    const methodField = document.createElement('input');
    methodField.type = 'hidden';
    methodField.name = '_method';
    methodField.value = 'DELETE';
    form.appendChild(methodField);
    
    document.body.appendChild(form);
    form.submit();
}

function openAddEmploymentModal() {
    const modal = document.getElementById('employmentModal');
    const form = document.getElementById('employmentForm');
    const title = document.getElementById('employmentModalTitle');
    const submitText = document.getElementById('employmentSubmitText');
    
    // Reset form
    form.reset();
    document.getElementById('employment_method').value = 'POST';
    document.getElementById('employment_id').value = '';
    form.action = '{{ route("myprofile.employment.store") }}';
    
    // Set default date to today
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('hired_at').value = today;
    
    title.textContent = 'Add Employment Record';
    submitText.textContent = 'Save Employment Record';
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Focus on first input
    setTimeout(() => {
        document.getElementById('employee_id').focus();
    }, 300);
}

// Open Edit Employment Modal
function openEditEmploymentModal(employmentId) {
    // Show loading
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Loading...',
            text: 'Fetching employment data',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }
    
    fetch(`/myprofile/employment/${employmentId}/edit-data`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (typeof Swal !== 'undefined') {
            Swal.close();
        }
        
        const modal = document.getElementById('employmentModal');
        const form = document.getElementById('employmentForm');
        const title = document.getElementById('employmentModalTitle');
        const submitText = document.getElementById('employmentSubmitText');
        
        // Set form values
        document.getElementById('employment_method').value = 'PUT';
        document.getElementById('employment_id').value = data.id;
        form.action = `/myprofile/employment/${data.id}`;
        
        document.getElementById('employee_id').value = data.employee_id || '';
        document.getElementById('position_id').value = data.position_id || '';
        document.getElementById('department_id').value = data.department_id || '';
        document.getElementById('hired_at').value = data.hired_at || '';
        document.getElementById('status').value = data.status || 'active';
        document.getElementById('employment_type').value = data.employment_type || 'permanent';
        document.getElementById('date_of_original_appointment').value = data.date_of_original_appointment || '';
        document.getElementById('date_of_last_promotion').value = data.date_of_last_promotion || '';
        document.getElementById('salary').value = data.salary || '';
        document.getElementById('salary_grade').value = data.salary_grade || '';
        document.getElementById('step_increment').value = data.step_increment || '';
        
        title.textContent = 'Edit Employment Record';
        submitText.textContent = 'Update Employment Record';
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    })
    .catch(error => {
        console.error('Error:', error);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load employment data. Please try again.',
                confirmButtonColor: '#6366f1'
            });
        } else {
            alert('Failed to load employment data. Please try again.');
        }
    });
}

// Close Employment Modal
function closeEmploymentModal() {
    const modal = document.getElementById('employmentModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.getElementById('employmentForm').reset();
}

// Handle Employment Form Submission
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('employmentForm');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate dates
            const hiredAt = document.getElementById('hired_at').value;
            const originalAppointment = document.getElementById('date_of_original_appointment').value;
            const lastPromotion = document.getElementById('date_of_last_promotion').value;
            
            if (!hiredAt) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Please select the date hired.',
                        confirmButtonColor: '#6366f1'
                    });
                }
                return;
            }
            
            if (originalAppointment && new Date(originalAppointment) > new Date()) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Original appointment date cannot be in the future.',
                        confirmButtonColor: '#6366f1'
                    });
                }
                return;
            }
            
            if (lastPromotion && new Date(lastPromotion) > new Date()) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Last promotion date cannot be in the future.',
                        confirmButtonColor: '#6366f1'
                    });
                }
                return;
            }
            
            if (originalAppointment && lastPromotion && new Date(lastPromotion) < new Date(originalAppointment)) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Last promotion date cannot be earlier than original appointment date.',
                        confirmButtonColor: '#6366f1'
                    });
                }
                return;
            }
            
            // Show loading
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Saving...',
                    text: 'Please wait while we save the employment record',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            }
            
            const formData = new FormData(this);
            const url = this.action;
            
            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        throw new Error(err.message || 'Save failed');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message || 'Employment record saved successfully',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
                
                closeEmploymentModal();
                
                // Reload the page to show updated data
                setTimeout(() => {
                    location.reload();
                }, 1500);
            })
            .catch(error => {
                console.error('Error:', error);
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Save Failed',
                        text: error.message || 'There was an error saving the employment record. Please try again.',
                        confirmButtonColor: '#6366f1'
                    });
                } else {
                    alert('Save failed: ' + error.message);
                }
            });
        });
    }
});

function openAddGovernmentIdModal() {
    const modal = document.getElementById('governmentIdModal');
    const form = document.getElementById('governmentIdForm');
    const title = document.getElementById('governmentIdModalTitle');
    const submitText = document.getElementById('governmentIdSubmitText');
    
    // Reset form
    form.reset();
    document.getElementById('government_id_method').value = 'POST';
    document.getElementById('government_id').value = '';
    form.action = '{{ route("myprofile.government-id.store") }}';
    
    title.textContent = 'Add Government IDs';
    submitText.textContent = 'Save Government IDs';
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Focus on first input
    setTimeout(() => {
        document.getElementById('umid_number').focus();
    }, 300);
}

// Open Edit Government ID Modal
function openEditGovernmentIdModal(governmentId) {
    // Show loading
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Loading...',
            text: 'Fetching government ID data',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }
    
    fetch(`/myprofile/government-id/${governmentId}/edit-data`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (typeof Swal !== 'undefined') {
            Swal.close();
        }
        
        const modal = document.getElementById('governmentIdModal');
        const form = document.getElementById('governmentIdForm');
        const title = document.getElementById('governmentIdModalTitle');
        const submitText = document.getElementById('governmentIdSubmitText');
        
        // Set form values
        document.getElementById('government_id_method').value = 'PUT';
        document.getElementById('government_id').value = data.id;
        form.action = `/myprofile/government-id/${data.id}`;
        
        document.getElementById('umid_number').value = data.umid_number || '';
        document.getElementById('pagibig_number').value = data.pagibig_number || '';
        document.getElementById('philhealth_number').value = data.philhealth_number || '';
        document.getElementById('philsys_number').value = data.philsys_number || '';
        document.getElementById('tin_number').value = data.tin_number || '';
        document.getElementById('sss_number').value = data.sss_number || '';
        document.getElementById('gsis_number').value = data.gsis_number || '';
        document.getElementById('dl_number').value = data.dl_number || '';
        document.getElementById('passport_number').value = data.passport_number || '';
        document.getElementById('prc_number').value = data.prc_number || '';
        
        title.textContent = 'Edit Government IDs';
        submitText.textContent = 'Update Government IDs';
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    })
    .catch(error => {
        console.error('Error:', error);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load government ID data. Please try again.',
                confirmButtonColor: '#7c3aed'
            });
        } else {
            alert('Failed to load government ID data. Please try again.');
        }
    });
}

// Close Government ID Modal
function closeGovernmentIdModal() {
    const modal = document.getElementById('governmentIdModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.getElementById('governmentIdForm').reset();
}

// Handle Government ID Form Submission
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('governmentIdForm');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show loading
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Saving...',
                    text: 'Please wait while we save the government IDs',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            }
            
            const formData = new FormData(this);
            const url = this.action;
            
            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        throw new Error(err.message || 'Save failed');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message || 'Government IDs saved successfully',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
                
                closeGovernmentIdModal();
                
                // Reload the page to show updated data
                setTimeout(() => {
                    location.reload();
                }, 1500);
            })
            .catch(error => {
                console.error('Error:', error);
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Save Failed',
                        text: error.message || 'There was an error saving the government IDs. Please try again.',
                        confirmButtonColor: '#7c3aed'
                    });
                } else {
                    alert('Save failed: ' + error.message);
                }
            });
        });
    }
});

// Open Add Address Modal
function openAddAddressModal() {
    const modal = document.getElementById('addressModal');
    const form = document.getElementById('addressForm');
    const title = document.getElementById('addressModalTitle');
    const submitText = document.getElementById('addressSubmitText');
    const addressTypeContainer = document.getElementById('address_type_container');
    const addressTypeSelect = document.getElementById('address_type');
    const addressTypeHidden = document.getElementById('address_type_hidden');
    
    // Reset form
    form.reset();
    document.getElementById('address_method').value = 'POST';
    document.getElementById('address_id').value = '';
    form.action = '{{ route("myprofile.address.store") }}';
    
    // ✅ Show address type selection for add
    addressTypeContainer.style.display = 'block';
    addressTypeSelect.disabled = false;
    addressTypeSelect.required = true;
    addressTypeHidden.value = '';
    
    // Clear address preview
    document.getElementById('addressPreview').textContent = 'Complete the fields above to preview the address';
    
    title.textContent = 'Add Address';
    submitText.textContent = 'Save Address';
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    setTimeout(() => {
        document.getElementById('address_type').focus();
    }, 300);
}

// Open Edit Address Modal
function openEditAddressModal(addressId) {
    // Show loading
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Loading...',
            text: 'Fetching address data',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }
    
    fetch(`/myprofile/address/${addressId}/edit-data`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (typeof Swal !== 'undefined') {
            Swal.close();
        }
        
        const modal = document.getElementById('addressModal');
        const form = document.getElementById('addressForm');
        const title = document.getElementById('addressModalTitle');
        const submitText = document.getElementById('addressSubmitText');
        const addressTypeContainer = document.getElementById('address_type_container');
        const addressTypeSelect = document.getElementById('address_type');
        const addressTypeHidden = document.getElementById('address_type_hidden');
        
        // Set form values
        document.getElementById('address_method').value = 'PUT';
        document.getElementById('address_id').value = data.id;
        form.action = `/myprofile/address/${data.id}`;
        
        // ✅ Hide address type selection for edit
        addressTypeContainer.style.display = 'none';
        addressTypeSelect.disabled = true;
        addressTypeSelect.required = false;
        
        // Store the address type in hidden field
        addressTypeHidden.value = data.address_type;
        
        // Populate other fields
        document.getElementById('hbl_number').value = data.hbl_number || '';
        document.getElementById('street').value = data.street || '';
        document.getElementById('subdi_village').value = data.subdi_village || '';
        document.getElementById('barangay').value = data.barangay || '';
        document.getElementById('city_municipality').value = data.city_municipality || '';
        document.getElementById('province').value = data.province || '';
        document.getElementById('zip_code').value = data.zip_code || '';
        
        // Update preview
        updateAddressPreview();
        
        title.textContent = 'Edit Address';
        submitText.textContent = 'Update Address';
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    })
    .catch(error => {
        console.error('Error:', error);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load address data. Please try again.',
                confirmButtonColor: '#3b82f6'
            });
        } else {
            alert('Failed to load address data. Please try again.');
        }
    });
}

// Close Address Modal
function closeAddressModal() {
    const modal = document.getElementById('addressModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.getElementById('addressForm').reset();
}

// Update Address Preview
function updateAddressPreview() {
    const hbl = document.getElementById('hbl_number').value.trim();
    const street = document.getElementById('street').value.trim();
    const subdi = document.getElementById('subdi_village').value.trim();
    const barangay = document.getElementById('barangay').value.trim();
    const city = document.getElementById('city_municipality').value.trim();
    const province = document.getElementById('province').value.trim();
    const zip = document.getElementById('zip_code').value.trim();
    
    const parts = [];
    if (hbl) parts.push(hbl);
    if (street) parts.push(street);
    if (subdi) parts.push(subdi);
    if (barangay) parts.push('Brgy. ' + barangay);
    if (city) parts.push(city);
    if (province) parts.push(province);
    if (zip) parts.push(zip);
    
    const preview = document.getElementById('addressPreview');
    if (parts.length > 0) {
        preview.textContent = parts.join(', ');
        preview.className = 'text-sm text-gray-900 dark:text-white font-medium';
    } else {
        preview.textContent = 'Complete the fields above to preview the address';
        preview.className = 'text-sm text-gray-500 dark:text-gray-400';
    }
}

// Auto-update preview on input change
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('addressForm');
    if (form) {
        const inputs = form.querySelectorAll('input, select');
        inputs.forEach(input => {
            input.addEventListener('input', updateAddressPreview);
            input.addEventListener('change', updateAddressPreview);
        });
    }
});

// Handle Address Form Submission
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('addressForm');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate
            const addressType = document.getElementById('address_type').value;
            const barangay = document.getElementById('barangay').value.trim();
            const city = document.getElementById('city_municipality').value.trim();
            const province = document.getElementById('province').value.trim();
            
            if (!addressType) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Please select an address type.',
                        confirmButtonColor: '#3b82f6'
                    });
                }
                return;
            }
            
            if (!barangay) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Please enter the barangay.',
                        confirmButtonColor: '#3b82f6'
                    });
                }
                return;
            }
            
            if (!city) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Please enter the city/municipality.',
                        confirmButtonColor: '#3b82f6'
                    });
                }
                return;
            }
            
            if (!province) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Please enter the province.',
                        confirmButtonColor: '#3b82f6'
                    });
                }
                return;
            }
            
            // Show loading
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Saving...',
                    text: 'Please wait while we save the address',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            }
            
            const formData = new FormData(this);
            const url = this.action;
            
            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        throw new Error(err.message || 'Save failed');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message || 'Address saved successfully',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
                
                closeAddressModal();
                
                // Reload the page to show updated data
                setTimeout(() => {
                    location.reload();
                }, 1500);
            })
            .catch(error => {
                console.error('Error:', error);
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Save Failed',
                        text: error.message || 'There was an error saving the address. Please try again.',
                        confirmButtonColor: '#3b82f6'
                    });
                } else {
                    alert('Save failed: ' + error.message);
                }
            });
        });
    }
});

// Delete Address Confirmation
function confirmDeleteAddress(addressId) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Delete Address',
            text: 'Are you sure you want to delete this address? This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                deleteAddress(addressId);
            }
        });
    } else {
        if (confirm('Are you sure you want to delete this address?')) {
            deleteAddress(addressId);
        }
    }
}

// Delete Address
function deleteAddress(addressId) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Deleting...',
            text: 'Please wait',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/myprofile/address/${addressId}`;
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = document.querySelector('meta[name="csrf-token"]')?.content || '';
    form.appendChild(csrfToken);
    
    const methodField = document.createElement('input');
    methodField.type = 'hidden';
    methodField.name = '_method';
    methodField.value = 'DELETE';
    form.appendChild(methodField);
    
    document.body.appendChild(form);
    form.submit();
}

document.addEventListener('DOMContentLoaded', function() {
    // Toggle details visibility when radio buttons change
    const radioGroups = [
        { prefix: 'q34_a', detailsClass: '.q34-details' },
        { prefix: 'q34_b', detailsClass: '.q34-details' },
        { prefix: 'q35_a', detailsClass: '.q35-details' },
        { prefix: 'q35_b', detailsClass: '.q35-details' },
        { prefix: 'q36', detailsClass: '.q36-details' },
        { prefix: 'q37', detailsClass: '.q37-details' },
        { prefix: 'q38_a', detailsClass: '.q38-details' },
        { prefix: 'q38_b', detailsClass: '.q38-details' },
        { prefix: 'q39', detailsClass: '.q39-details' },
        { prefix: 'q40_a', detailsClass: '.q40-details' },
        { prefix: 'q40_b', detailsClass: '.q40-details' },
    ];

    radioGroups.forEach(function(group) {
        const radios = document.querySelectorAll(`input[name="${group.prefix}"]`);
        const detailsContainer = document.querySelector(group.detailsClass);
        
        if (radios.length && detailsContainer) {
            radios.forEach(function(radio) {
                radio.addEventListener('change', function() {
                    if (this.value === 'yes') {
                        detailsContainer.classList.remove('hidden');
                    } else {
                        detailsContainer.classList.add('hidden');
                    }
                });
            });
        }
    });

    // Form validation before submit
    const form = document.getElementById('backgroundForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Check if any required radio buttons are not answered
            const radioGroups = ['q34_a', 'q34_b', 'q35_a', 'q35_b', 'q36', 'q37', 'q38_a', 'q38_b', 'q39', 'q40_a', 'q40_b', 'q40_c'];
            let missing = false;
            
            radioGroups.forEach(function(name) {
                const radios = document.querySelectorAll(`input[name="${name}"]:checked`);
                if (radios.length === 0) {
                    missing = true;
                    const container = document.querySelector(`input[name="${name}"]`)?.closest('.bg-gray-50');
                    if (container) {
                        container.classList.add('border-red-500', 'border-2');
                        setTimeout(() => {
                            container.classList.remove('border-red-500', 'border-2');
                        }, 3000);
                    }
                }
            });
            
            if (missing) {
                e.preventDefault();
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Incomplete Form',
                        text: 'Please answer all questions before submitting.',
                        confirmButtonColor: '#6366f1'
                    });
                } else {
                    alert('Please answer all questions before submitting.');
                }
            }
        });
    }
});
</script>
@endpush