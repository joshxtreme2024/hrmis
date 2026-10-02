@extends('layouts.app')

@section('title', 'Employee Profile - ' . ($employee->display_name ?? ''))
@section('header-actions')
    <button onclick="window.history.back()" 
            class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 hover:border-gray-400 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-xl transition-all duration-200">
        <i class="bi bi-arrow-left mr-2"></i>
        Back
    </button>
@endsection

@section('breadcrumbs')
    <li class="flex items-center">
        <a href="{{ route('employees.index') }}">
            <i class="bi bi-chevron-right text-gray-300 dark:text-gray-600 mx-2 text-xs"></i>
            <span class="text-gray-800 dark:text-gray-200 font-medium">Employee Management</span>
        </a>
    </li>
    <li class="flex items-center">
        <i class="bi bi-chevron-right text-gray-300 dark:text-gray-600 mx-2 text-xs"></i>
        <span class="text-gray-800 dark:text-gray-200 font-medium">Employee Profile</span>
    </li>
@endsection

@section('page-header')
<div class="flex items-center justify-between py-4 px-6 bg-gradient-to-r from-blue-600 to-indigo-600">
    <div>
        <h1 class="text-2xl font-bold text-white">Employee Profile</h1>
        <ol class="flex items-center space-x-2 text-sm text-blue-100 mt-1">
            <li><a href="{{ route('dashboard') }}" class="hover:text-white transition-colors">Dashboard</a></li>
            <li><span class="text-blue-300">/</span></li>
            <li><a href="{{ route('employees.index') }}" class="hover:text-white transition-colors">Employee Management</a></li>
            <li><span class="text-blue-300">/</span></li>
            <li class="text-white font-medium">Employee Profile</li>
        </ol>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('employees.edit', $employee) }}" 
           class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-all duration-200 backdrop-blur-sm">
            <i class="bi bi-pencil"></i>
            <span>Edit Profile</span>
        </a>
        <button class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-all duration-200 backdrop-blur-sm" 
                onclick="window.print()">
            <i class="bi bi-printer"></i>
            <span>Print</span>
        </button>
        <div class="relative">
            <button class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-all duration-200 backdrop-blur-sm" 
                    onclick="toggleDropdown(event)">
                <i class="bi bi-three-dots-vertical"></i>
            </button>
            <ul class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-2xl border border-gray-100 py-1 z-50 hidden" 
                id="dropdownMenu">
                <li>
                    <a class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors rounded-t-xl" href="#">
                        <i class="bi bi-file-pdf mr-2"></i>Export as PDF
                    </a>
                </li>
                <li>
                    <a class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors" href="#">
                        <i class="bi bi-envelope mr-2"></i>Send Email
                    </a>
                </li>
                <li><hr class="my-1 border-gray-100"></li>
                <li>
                    <a class="block px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors rounded-b-xl" href="#">
                        <i class="bi bi-exclamation-triangle mr-2"></i>Report Issue
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="p-6 bg-gray-50 min-h-screen">
    <!-- Employee Quick Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Employee ID</p>
                    <p class="text-xl font-bold text-gray-900 mt-1">{{ $employee->employment?->employee_id ?? 'N/A' }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center text-blue-500">
                    <i class="bi bi-person-badge text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Department</p>
                    <p class="text-xl font-bold text-gray-900 mt-1">{{ $employee->employment?->department?->name ?? 'N/A' }}</p>
                </div>
                <div class="w-10 h-10 bg-indigo-50 rounded-lg flex items-center justify-center text-indigo-500">
                    <i class="bi bi-building text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Position</p>
                    <p class="text-xl font-bold text-gray-900 mt-1">{{ $employee->employment?->position?->title ?? 'N/A' }}</p>
                </div>
                <div class="w-10 h-10 bg-purple-50 rounded-lg flex items-center justify-center text-purple-500">
                    <i class="bi bi-briefcase text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</p>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold mt-1
                        @if($employee->employment?->status === 'active') bg-green-100 text-green-700
                        @elseif($employee->employment?->status === 'on_leave') bg-yellow-100 text-yellow-700
                        @elseif($employee->employment?->status === 'resigned') bg-gray-100 text-gray-700
                        @elseif($employee->employment?->status === 'terminated') bg-red-100 text-red-700
                        @else bg-gray-100 text-gray-700
                        @endif">
                        <span class="w-1.5 h-1.5 rounded-full inline-block mr-1.5
                            @if($employee->employment?->status === 'active') bg-green-500
                            @elseif($employee->employment?->status === 'on_leave') bg-yellow-500
                            @elseif($employee->employment?->status === 'resigned') bg-gray-500
                            @elseif($employee->employment?->status === 'terminated') bg-red-500
                            @else bg-gray-400
                            @endif
                        "></span>
                        {{ ucfirst(str_replace('_', ' ', $employee->employment?->status ?? 'N/A')) }}
                    </span>
                </div>
                <div class="w-10 h-10 bg-gray-50 rounded-lg flex items-center justify-center text-gray-400">
                    <i class="bi bi-circle-fill text-xl
                        @if($employee->employment?->status === 'active') text-green-500
                        @elseif($employee->employment?->status === 'on_leave') text-yellow-500
                        @elseif($employee->employment?->status === 'resigned') text-gray-500
                        @elseif($employee->employment?->status === 'terminated') text-red-500
                        @else text-gray-400
                        @endif
                    "></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Date Hired</p>
                    <p class="text-xl font-bold text-gray-900 mt-1">{{ $employee->employment?->hired_at?->format('M d, Y') ?? 'N/A' }}</p>
                </div>
                <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-500">
                    <i class="bi bi-calendar-event text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Profile Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Personal Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Profile Header -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0 relative">
                        @if($employee->profile_picture)
                            <img src="{{ asset('storage/' . $employee->profile_picture) }}" 
                                 alt="{{ $employee->display_name }}" 
                                 class="h-24 w-24 rounded-full object-cover border-4 border-white shadow-lg">
                        @else
                            <div class="h-24 w-24 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 text-white flex items-center justify-center text-3xl font-bold shadow-lg">
                                @php
                                    $firstName = $employee->personalData?->first_name ?? '';
                                    $lastName = $employee->personalData?->last_name ?? '';
                                    $initials = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));
                                @endphp
                                {{ $initials ?: '?' }}
                            </div>
                        @endif
                        <div class="absolute bottom-0 right-0 w-6 h-6 bg-green-500 rounded-full border-2 border-white"></div>
                    </div>
                    <div class="ml-5 flex-1">
                        <h2 class="text-2xl font-bold text-gray-900">{{ $employee->display_name }}</h2>
                        <div class="flex flex-wrap items-center gap-2 mt-1">
                            <span class="text-sm text-gray-500 flex items-center">
                                <i class="bi bi-envelope mr-1"></i>
                                {{ $employee->email }}
                            </span>
                            <span class="text-gray-300">|</span>
                            <span class="text-sm text-gray-500 flex items-center">
                                <i class="bi bi-phone mr-1"></i>
                                {{ $employee->personalData?->contact_number ?? 'No contact' }}
                            </span>
                        </div>
                        <div class="flex flex-wrap items-center gap-2 mt-2">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                <i class="bi bi-person mr-1"></i>
                                {{ ucfirst($employee->personalData?->sex ?? 'Not specified') }}
                            </span>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-gray-50 text-gray-700 border border-gray-100">
                                <i class="bi bi-calendar3 mr-1"></i>
                                {{ $employee->personalData?->birth_date?->format('M d, Y') ?? 'N/A' }}
                            </span>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                <i class="bi bi-clock mr-1"></i>
                                Member since {{ $employee->created_at->format('M d, Y') }}
                            </span>
                        </div>
                    </div>
                    <!-- <div class="flex gap-1">
                        <a href="{{ route('employees.edit', $employee) }}" 
                           class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" 
                           title="Edit Profile">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <button class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors" 
                                title="Send Message">
                            <i class="bi bi-envelope"></i>
                        </button>
                        <button class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors" 
                                title="More Options">
                            <i class="bi bi-three-dots"></i>
                        </button>
                    </div> -->
                </div>
            </div>

            <!-- Tabbed PDS Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="border-b border-gray-200">
                    <nav class="flex flex-wrap -mb-px" id="pdsTabs" role="tablist">
                        <button class="inline-flex items-center px-4 py-3 text-sm font-medium text-blue-600 border-b-2 border-blue-600 rounded-t-lg active" 
                                id="personal-tab" 
                                data-tab-target="personal-content" 
                                type="button" 
                                role="tab" 
                                onclick="switchTab('personal')">
                            <i class="bi bi-person mr-2"></i>
                            Personal
                        </button>
                        <button class="inline-flex items-center px-4 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent hover:border-gray-300 rounded-t-lg" 
                                id="family-tab" 
                                data-tab-target="family-content" 
                                type="button" 
                                role="tab"
                                onclick="switchTab('family')">
                            <i class="bi bi-people mr-2"></i>
                            Family
                        </button>
                        <button class="inline-flex items-center px-4 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent hover:border-gray-300 rounded-t-lg" 
                                id="education-tab" 
                                data-tab-target="education-content" 
                                type="button" 
                                role="tab"
                                onclick="switchTab('education')">
                            <i class="bi bi-mortarboard mr-2"></i>
                            Education
                        </button>
                        <button class="inline-flex items-center px-4 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent hover:border-gray-300 rounded-t-lg" 
                                id="eligibility-tab" 
                                data-tab-target="eligibility-content" 
                                type="button" 
                                role="tab"
                                onclick="switchTab('eligibility')">
                            <i class="bi bi-award mr-2"></i>
                            CS Eligibilities
                        </button>
                        <button class="inline-flex items-center px-4 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent hover:border-gray-300 rounded-t-lg" 
                                id="work-tab" 
                                data-tab-target="work-content" 
                                type="button" 
                                role="tab"
                                onclick="switchTab('work')">
                            <i class="bi bi-briefcase mr-2"></i>
                            Work Experience
                        </button>
                        <button class="inline-flex items-center px-4 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent hover:border-gray-300 rounded-t-lg" 
                                id="voluntary-tab" 
                                data-tab-target="voluntary-content" 
                                type="button" 
                                role="tab"
                                onclick="switchTab('voluntary')">
                            <i class="fa-solid fa-person-digging mr-2"></i>
                            Voluntary Works
                        </button>
                        <button class="inline-flex items-center px-4 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent hover:border-gray-300 rounded-t-lg" 
                                id="trainings-tab" 
                                data-tab-target="trainings-content" 
                                type="button" 
                                role="tab"
                                onclick="switchTab('trainings')">
                            <i class="fa-solid fa-dumbbell mr-2"></i>
                            Trainings
                        </button>
                        <button class="inline-flex items-center px-4 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent hover:border-gray-300 rounded-t-lg" 
                                id="skills-tab" 
                                data-tab-target="skills-content" 
                                type="button" 
                                role="tab"
                                onclick="switchTab('skills')">
                            <i class="fa-solid fa-person-skiing mr-2"></i>
                            Skills
                        </button>
                        <button class="inline-flex items-center px-4 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent hover:border-gray-300 rounded-t-lg" 
                                id="distinctions-tab" 
                                data-tab-target="distinctions-content" 
                                type="button" 
                                role="tab"
                                onclick="switchTab('distinctions')">
                            <i class="fa-solid fa-trophy mr-2"></i>
                            Distinctions
                        </button>
                        <button class="inline-flex items-center px-4 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent hover:border-gray-300 rounded-t-lg" 
                                id="organizations-tab" 
                                data-tab-target="organizations-content" 
                                type="button" 
                                role="tab"
                                onclick="switchTab('organizations')">
                            <i class="fa-solid fa-people-group mr-2"></i>
                            Organizations
                        </button>
                        <button class="inline-flex items-center px-4 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent hover:border-gray-300 rounded-t-lg" 
                                id="background-tab" 
                                data-tab-target="background-content" 
                                type="button" 
                                role="tab"
                                onclick="switchTab('background')">
                            <i class="bi bi-shield-check mr-2"></i>
                            Background Info
                        </button>
                        <button class="inline-flex items-center px-4 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent hover:border-gray-300 rounded-t-lg" 
                                id="references-tab" 
                                data-tab-target="references-content" 
                                type="button" 
                                role="tab"
                                onclick="switchTab('references')">
                            <i class="fa-solid fa-user-check mr-2"></i>
                            References
                        </button>
                        <button class="inline-flex items-center px-4 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent hover:border-gray-300 rounded-t-lg" 
                                id="employment-tab" 
                                data-tab-target="employment-content" 
                                type="button" 
                                role="tab"
                                onclick="switchTab('employment')">
                            <i class="fa-solid fa-building-circle-check mr-2"></i>
                            Employment
                        </button>
                    </nav>
                </div>

                <div class="p-6">
                    <!-- Personal Information Tab -->
                    <div id="personal-content" class="tab-content active">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="bi bi-person-vcard mr-2 text-blue-600"></i>
                            Personal Information
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">First Name</label>
                                <p class="text-sm text-gray-900 font-medium mt-1">{{ $employee->personalData?->first_name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Middle Name</label>
                                <p class="text-sm text-gray-900 font-medium mt-1">{{ $employee->personalData?->middle_name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Last Name</label>
                                <p class="text-sm text-gray-900 font-medium mt-1">{{ $employee->personalData?->last_name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Extension Name</label>
                                <p class="text-sm text-gray-900 font-medium mt-1">{{ $employee->personalData?->ext_name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Birth Date</label>
                                <p class="text-sm text-gray-900 font-medium mt-1">{{ $employee->personalData?->birth_date?->format('M d, Y') ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Place of Birth</label>
                                <p class="text-sm text-gray-900 font-medium mt-1">{{ $employee->personalData?->place_of_birth ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Sex</label>
                                <p class="text-sm text-gray-900 font-medium mt-1">{{ ucfirst($employee->personalData?->sex ?? 'N/A') }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Civil Status</label>
                                <p class="text-sm text-gray-900 font-medium mt-1">{{ ucfirst($employee->personalData?->civil_status ?? 'N/A') }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Height(cm)</label>
                                <p class="text-sm text-gray-900 font-medium mt-1">{{ $employee->personalData?->height ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Weight(kg)</label>
                                <p class="text-sm text-gray-900 font-medium mt-1">{{ $employee->personalData?->weight ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Blood Type</label>
                                <p class="text-sm text-gray-900 font-medium mt-1">{{ ucfirst($employee->personalData?->blood_type ?? 'N/A') }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Nationality</label>
                                <p class="text-sm text-gray-900 font-medium mt-1">{{ $employee->personalData?->nationality ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Religion</label>
                                <p class="text-sm text-gray-900 font-medium mt-1">{{ $employee->personalData?->religion ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Contact Number</label>
                                <p class="text-sm text-gray-900 font-medium mt-1">Mobile: {{ $employee->personalData?->mobile_no ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-900 font-medium mt-1">Tel.: {{ $employee->personalData?->telephone_no ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</label>
                                <p class="text-sm text-gray-900 font-medium mt-1">{{ $employee->email }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Addresses</label>
                                <p class="text-sm text-gray-900 font-medium mt-1"><span class="text-gray-600">Residential: </span>{{ $employee->residentialAddress?->full_address ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-900 font-medium mt-1"><span class="text-gray-600">Permanent:</span> {{ $employee->permanentAddress?->full_address ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Family Background Tab -->
                    <div id="family-content" class="tab-content hidden">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="bi bi-people mr-2 text-blue-600"></i>
                            Family Background
                        </h4>
                        @if($employee->familyBackground)
                            @if($employee->familyBackground->has_spouse)
                                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-2">
                                    <h5 class="text-sm font-semibold text-gray-700 flex items-center gap-2 mb-3">
                                        <i class="bi bi-person-heart text-blue-600"></i>
                                        Spouse Information
                                    </h5>
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-sm text-gray-600">Full Name:</span>
                                            <span class="text-sm text-gray-900 font-medium">{{ $employee->familyBackground->spouse_full_name }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-sm text-gray-600">Occupation:</span>
                                            <span class="text-sm text-gray-900">{{ $employee->familyBackground->spouse_occupation ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-sm text-gray-600">Employer/Business:</span>
                                            <span class="text-sm text-gray-900">{{ $employee->familyBackground->spouse_employer_business ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-sm text-gray-600">Business Address:</span>
                                            <span class="text-sm text-gray-900">{{ $employee->familyBackground->spouse_business_address ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-sm text-gray-600">Telephone No.:</span>
                                            <span class="text-sm text-gray-900">{{ $employee->familyBackground->spouse_telephone_no ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                                <h5 class="text-sm font-semibold text-gray-700 flex items-center gap-2 mb-3">
                                    <i class="bi bi-people text-indigo-600"></i>
                                    Parents Information
                                </h5>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Father's Name</label>
                                        <p class="text-sm text-gray-900 font-medium">{{ $employee->familyBackground->father_full_name }}</p>
                                        <p class="text-xs text-gray-500">Initials: {{ $employee->familyBackground->father_initials }}</p>
                                    </div>
                                    <div>
                                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Mother's Name</label>
                                        <p class="text-sm text-gray-900 font-medium">{{ $employee->familyBackground->mother_full_name }}</p>
                                        <p class="text-xs text-gray-500">Maiden: {{ $employee->familyBackground->mother_maiden_name }}</p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <i class="bi bi-people text-3xl block mb-2"></i>
                                <p>No family background information available</p>
                            </div>
                        @endif
                    </div>

                    <!-- Education Tab -->
                    <div id="education-content" class="tab-content hidden">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="bi bi-mortarboard mr-2 text-blue-600"></i>
                            Educational Background
                        </h4>
                        @if($employee->education && $employee->education->count() > 0)
                            <div class="space-y-4">
                                @foreach($employee->education->sortBy('order') as $education)
                                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $education->level_badge_color }}">
                                                {{ $education->level_label }}
                                            </span>
                                            <span class="text-xs text-gray-500">{{ $education->getPeriodFormattedAttribute() }}</span>
                                        </div>
                                        <h5 class="text-sm font-semibold text-gray-900">{{ $education->school_name }}</h5>
                                        @if($education->degree_course)
                                            <p class="text-sm text-gray-600">{{ $education->degree_course }}</p>
                                        @endif
                                        @if($education->scholarship_honors)
                                            <p class="text-xs text-blue-600 mt-1">
                                                <i class="bi bi-award mr-1"></i>
                                                {{ $education->scholarship_honors }}
                                            </p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <i class="bi bi-mortarboard text-3xl block mb-2"></i>
                                <p>No educational background available</p>
                            </div>
                        @endif
                    </div>

                    <!-- Eligibility Tab -->
                    <div id="eligibility-content" class="tab-content hidden">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="bi bi-award mr-2 text-blue-600"></i>
                            Civil Service Eligibilities
                        </h4>
                        @if($employee->eligibilities && $employee->eligibilities->count() > 0)
                            <div class="space-y-4">
                                @foreach($employee->eligibilities->sortBy('order') as $eligibility)
                                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:shadow-md transition-shadow duration-200">
                                        <div class="flex items-center justify-between mb-2">
                                            <h5 class="text-sm font-semibold text-gray-900">
                                                {{ $eligibility->career_service }}
                                            </h5>
                                            <span class="text-sm font-medium text-gray-600">
                                                Rating: {{ $eligibility->formatted_rating ?? 'N/A' }}
                                            </span>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                                            <div>
                                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Examination Date</span>
                                                <p class="text-gray-900">{{ $eligibility->examination_date?->format('M d, Y') ?? 'N/A' }}</p>
                                            </div>
                                            <div>
                                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Examination Place</span>
                                                <p class="text-gray-900">{{ $eligibility->examination_place ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                        @if($eligibility->license_number)
                                            <div class="mt-2 pt-2 border-t border-gray-200">
                                                <div class="flex items-center justify-between">
                                                    <div>
                                                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">License Number</span>
                                                        <p class="text-sm text-gray-900 font-medium">{{ $eligibility->license_number }}</p>
                                                    </div>
                                                    <div class="text-right">
                                                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Validity</span>
                                                        <p class="text-sm text-gray-900">{{ $eligibility->license_date_validity?->format('M d, Y') ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                                <div class="mt-2">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $eligibility->license_status_badge ?? 'bg-gray-100 text-gray-700' }}">
                                                        {{ $eligibility->license_status_label ?? 'No License' }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <i class="bi bi-award text-3xl block mb-2"></i>
                                <p>No eligibility records available</p>
                            </div>
                        @endif
                    </div>

                    <!-- Work Experience Tab -->
                    <div id="work-content" class="tab-content hidden">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="bi bi-briefcase mr-2 text-blue-600"></i>
                            Work Experiences
                        </h4>
                        @if($employee->workExperiences && $employee->workExperiences->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Position</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Organization</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Period</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Duration</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Sector</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Salary</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($employee->workExperiences->sortBy('order') as $index => $experience)
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="px-4 py-3 text-gray-500">{{ $index + 1 }}</td>
                                                <td class="px-4 py-3 font-medium text-gray-900">{{ $experience->position_title }}</td>
                                                <td class="px-4 py-3 text-gray-700">{{ $experience->department_agency_office }}</td>
                                                <td class="px-4 py-3 text-gray-700 text-xs">{{ $experience->period_range }}</td>
                                                <td class="px-4 py-3 text-gray-700">{{ $experience->duration }}</td>
                                                <td class="px-4 py-3">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $experience->sector_badge_color }}">
                                                        {{ $experience->sector_label }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $experience->status_badge_color }}">
                                                        {{ $experience->status_of_appointment_label }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 text-gray-700">{{ $experience->formatted_salary }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <i class="bi bi-briefcase text-3xl block mb-2"></i>
                                <p>No work experience records available</p>
                            </div>
                        @endif
                    </div>

                    <!-- Voluntary Works -->
                    <div id="voluntary-content" class="tab-content hidden">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fa-solid fa-person-digging mr-2 text-blue-600"></i>
                            Voluntary Works
                        </h4>
                        @if($employee->voluntaryWorks && $employee->voluntaryWorks->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead>
                                        <tr class="bg-gray-50 dark:bg-gray-700/50">
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">#</th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Organization</th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Position</th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Period</th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Hours</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($employee->voluntaryWorks as $index => $work)
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

                    <div id="distinctions-content" class="tab-content hidden">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fa-solid fa-trophy mr-2 text-blue-600"></i>
                            Distinctions
                        </h4>
                        @if($employee->distinctions && $employee->distinctions->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead>
                                        <tr class="bg-gray-50 dark:bg-gray-700/50">
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">#</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Distinction / Award</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($employee->distinctions as $index => $distinction)
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

                    <div id="organizations-content" class="tab-content hidden">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fa-solid fa-people-group mr-2 text-blue-600"></i>
                            Organizations
                        </h4>
                        @if($employee->organizations && $employee->organizations->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead>
                                        <tr class="bg-gray-50 dark:bg-gray-700/50">
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">#</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Organization Name</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($employee->organizations as $index => $organization)
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

                    <div id="background-content" class="tab-content hidden">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="bi bi-person-check mr-2 text-blue-600"></i>
                            Background Information
                        </h4>
                        <!-- Question 34: Relationship to Appointing Authority -->
                        <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-5 border border-gray-200 dark:border-gray-600">
                            <div class="flex items-start">
                                <!-- <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-bold text-sm flex-shrink-0 mr-3 mt-0.5">
                                    34
                                </span> -->
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
                                                <div class="flex items-center space-x-4">
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                                        @if($employee->backgroundInfo && $employee->backgroundInfo->q34_a === 'yes') 
                                                            bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400
                                                        @elseif($employee->backgroundInfo && $employee->backgroundInfo->q34_a === 'no')
                                                            bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-300
                                                        @else
                                                            bg-gray-100 dark:bg-gray-600 text-gray-400 dark:text-gray-500
                                                        @endif">
                                                        @if($employee->backgroundInfo && $employee->backgroundInfo->q34_a === 'yes')
                                                            <i class="bi bi-check-circle mr-1.5"></i> Yes
                                                        @elseif($employee->backgroundInfo && $employee->backgroundInfo->q34_a === 'no')
                                                            <i class="bi bi-x-circle mr-1.5"></i> No
                                                        @else
                                                            <i class="bi bi-dash-circle mr-1.5"></i> Not Answered
                                                        @endif
                                                    </span>
                                                </div>
                                                @if($employee->backgroundInfo && $employee->backgroundInfo->q34_a_details)
                                                    <div class="mt-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg p-3 border border-blue-200 dark:border-blue-800">
                                                        <p class="text-xs text-gray-600 dark:text-gray-400">
                                                            <i class="bi bi-info-circle mr-1 text-blue-500"></i>
                                                            <span class="font-medium">Details:</span> {{ $employee->backgroundInfo->q34_a_details }}
                                                        </p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- 34b: Within fourth degree -->
                                        <div class="flex items-start pt-2 border-t border-gray-200 dark:border-gray-600 mt-2">
                                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400 w-6">b.</span>
                                            <div class="flex-1">
                                                <p class="text-sm text-gray-700 dark:text-gray-300 mb-2">Within the fourth degree (for Local Government Unit - Career Employees)?</p>
                                                <div class="flex items-center space-x-4">
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                                        @if($employee->backgroundInfo && $employee->backgroundInfo->q34_b === 'yes') 
                                                            bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400
                                                        @elseif($employee->backgroundInfo && $employee->backgroundInfo->q34_b === 'no')
                                                            bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-300
                                                        @else
                                                            bg-gray-100 dark:bg-gray-600 text-gray-400 dark:text-gray-500
                                                        @endif">
                                                        @if($employee->backgroundInfo && $employee->backgroundInfo->q34_b === 'yes')
                                                            <i class="bi bi-check-circle mr-1.5"></i> Yes
                                                        @elseif($employee->backgroundInfo && $employee->backgroundInfo->q34_b === 'no')
                                                            <i class="bi bi-x-circle mr-1.5"></i> No
                                                        @else
                                                            <i class="bi bi-dash-circle mr-1.5"></i> Not Answered
                                                        @endif
                                                    </span>
                                                </div>
                                                @if($employee->backgroundInfo && $employee->backgroundInfo->q34_b_details)
                                                    <div class="mt-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg p-3 border border-blue-200 dark:border-blue-800">
                                                        <p class="text-xs text-gray-600 dark:text-gray-400">
                                                            <i class="bi bi-info-circle mr-1 text-blue-500"></i>
                                                            <span class="font-medium">Details:</span> {{ $employee->backgroundInfo->q34_b_details }}
                                                        </p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Question 35: Administrative Offense & Criminal Charges -->
                        <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-5 border border-gray-200 dark:border-gray-600 mt-2">
                            <div class="flex items-start">
                                <!-- <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-bold text-sm flex-shrink-0 mr-3 mt-0.5">
                                    35
                                </span> -->
                                <div class="flex-1 space-y-4">
                                    <!-- 35a -->
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white mb-2">a. Have you ever been found guilty of any administrative offense?</p>
                                        <div class="flex items-center space-x-4">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                                @if($employee->backgroundInfo && $employee->backgroundInfo->q35_a === 'yes') 
                                                    bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400
                                                @elseif($employee->backgroundInfo && $employee->backgroundInfo->q35_a === 'no')
                                                    bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-300
                                                @else
                                                    bg-gray-100 dark:bg-gray-600 text-gray-400 dark:text-gray-500
                                                @endif">
                                                @if($employee->backgroundInfo && $employee->backgroundInfo->q35_a === 'yes')
                                                    <i class="bi bi-check-circle mr-1.5"></i> Yes
                                                @elseif($employee->backgroundInfo && $employee->backgroundInfo->q35_a === 'no')
                                                    <i class="bi bi-x-circle mr-1.5"></i> No
                                                @else
                                                    <i class="bi bi-dash-circle mr-1.5"></i> Not Answered
                                                @endif
                                            </span>
                                        </div>
                                        @if($employee->backgroundInfo && $employee->backgroundInfo->q35_a_details)
                                            <div class="mt-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg p-3 border border-blue-200 dark:border-blue-800">
                                                <p class="text-xs text-gray-600 dark:text-gray-400">
                                                    <i class="bi bi-info-circle mr-1 text-blue-500"></i>
                                                    <span class="font-medium">Details:</span> {{ $employee->backgroundInfo->q35_a_details }}
                                                </p>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- 35b -->
                                    <div class="pt-3 border-t border-gray-200 dark:border-gray-600">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white mb-2">b. Have you been criminally charged before any court?</p>
                                        <div class="flex items-center space-x-4">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                                @if($employee->backgroundInfo && $employee->backgroundInfo->q35_b === 'yes') 
                                                    bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400
                                                @elseif($employee->backgroundInfo && $employee->backgroundInfo->q35_b === 'no')
                                                    bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-300
                                                @else
                                                    bg-gray-100 dark:bg-gray-600 text-gray-400 dark:text-gray-500
                                                @endif">
                                                @if($employee->backgroundInfo && $employee->backgroundInfo->q35_b === 'yes')
                                                    <i class="bi bi-check-circle mr-1.5"></i> Yes
                                                @elseif($employee->backgroundInfo && $employee->backgroundInfo->q35_b === 'no')
                                                    <i class="bi bi-x-circle mr-1.5"></i> No
                                                @else
                                                    <i class="bi bi-dash-circle mr-1.5"></i> Not Answered
                                                @endif
                                            </span>
                                        </div>
                                        @if($employee->backgroundInfo && $employee->backgroundInfo->q35_b_details)
                                            <div class="mt-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg p-3 border border-blue-200 dark:border-blue-800">
                                                <p class="text-xs text-gray-600 dark:text-gray-400">
                                                    <i class="bi bi-info-circle mr-1 text-blue-500"></i>
                                                    <span class="font-medium">Details:</span> {{ $employee->backgroundInfo->q35_b_details }}
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Question 36: Conviction -->
                        <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-5 border border-gray-200 dark:border-gray-600 mt-2">
                            <div class="flex items-start">
                                <!-- <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-bold text-sm flex-shrink-0 mr-3 mt-0.5">
                                    36
                                </span> -->
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white mb-2">
                                        Have you ever been convicted of any crime or violation of any law, decree, ordinance or regulation by any court or tribunal?
                                    </p>
                                    <div class="flex items-center space-x-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                            @if($employee->backgroundInfo && $employee->backgroundInfo->q36 === 'yes') 
                                                bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400
                                            @elseif($employee->backgroundInfo && $employee->backgroundInfo->q36 === 'no')
                                                bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-300
                                            @else
                                                bg-gray-100 dark:bg-gray-600 text-gray-400 dark:text-gray-500
                                            @endif">
                                            @if($employee->backgroundInfo && $employee->backgroundInfo->q36 === 'yes')
                                                <i class="bi bi-check-circle mr-1.5"></i> Yes
                                            @elseif($employee->backgroundInfo && $employee->backgroundInfo->q36 === 'no')
                                                <i class="bi bi-x-circle mr-1.5"></i> No
                                            @else
                                                <i class="bi bi-dash-circle mr-1.5"></i> Not Answered
                                            @endif
                                        </span>
                                    </div>
                                    @if($employee->backgroundInfo && $employee->backgroundInfo->q36_details)
                                        <div class="mt-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg p-3 border border-blue-200 dark:border-blue-800">
                                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                                <i class="bi bi-info-circle mr-1 text-blue-500"></i>
                                                <span class="font-medium">Details:</span> {{ $employee->backgroundInfo->q36_details }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Question 37: Separation from Service -->
                        <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-5 border border-gray-200 dark:border-gray-600 mt-2">
                            <div class="flex items-start">
                                <!-- <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-bold text-sm flex-shrink-0 mr-3 mt-0.5">
                                    37
                                </span> -->
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white mb-2">
                                        Have you ever been separated from the service in any of the following modes: resignation, retirement, dropped from the rolls, dismissal, termination, end of term, finished contract or phased out (abolition) in the public or private sector?
                                    </p>
                                    <div class="flex items-center space-x-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                            @if($employee->backgroundInfo && $employee->backgroundInfo->q37 === 'yes') 
                                                bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400
                                            @elseif($employee->backgroundInfo && $employee->backgroundInfo->q37 === 'no')
                                                bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-300
                                            @else
                                                bg-gray-100 dark:bg-gray-600 text-gray-400 dark:text-gray-500
                                            @endif">
                                            @if($employee->backgroundInfo && $employee->backgroundInfo->q37 === 'yes')
                                                <i class="bi bi-check-circle mr-1.5"></i> Yes
                                            @elseif($employee->backgroundInfo && $employee->backgroundInfo->q37 === 'no')
                                                <i class="bi bi-x-circle mr-1.5"></i> No
                                            @else
                                                <i class="bi bi-dash-circle mr-1.5"></i> Not Answered
                                            @endif
                                        </span>
                                    </div>
                                    @if($employee->backgroundInfo && $employee->backgroundInfo->q37_details)
                                        <div class="mt-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg p-3 border border-blue-200 dark:border-blue-800">
                                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                                <i class="bi bi-info-circle mr-1 text-blue-500"></i>
                                                <span class="font-medium">Details:</span> {{ $employee->backgroundInfo->q37_details }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Question 38: Election Candidate -->
                        <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-5 border border-gray-200 dark:border-gray-600 mt-2">
                            <div class="flex items-start">
                                <!-- <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-bold text-sm flex-shrink-0 mr-3 mt-0.5">
                                    38
                                </span> -->
                                <div class="flex-1 space-y-4">
                                    <!-- 38a -->
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white mb-2">a. Have you ever been a candidate in a national or local election held within the last year (except Barangay election)?</p>
                                        <div class="flex items-center space-x-4">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                                @if($employee->backgroundInfo && $employee->backgroundInfo->q38_a === 'yes') 
                                                    bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400
                                                @elseif($employee->backgroundInfo && $employee->backgroundInfo->q38_a === 'no')
                                                    bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-300
                                                @else
                                                    bg-gray-100 dark:bg-gray-600 text-gray-400 dark:text-gray-500
                                                @endif">
                                                @if($employee->backgroundInfo && $employee->backgroundInfo->q38_a === 'yes')
                                                    <i class="bi bi-check-circle mr-1.5"></i> Yes
                                                @elseif($employee->backgroundInfo && $employee->backgroundInfo->q38_a === 'no')
                                                    <i class="bi bi-x-circle mr-1.5"></i> No
                                                @else
                                                    <i class="bi bi-dash-circle mr-1.5"></i> Not Answered
                                                @endif
                                            </span>
                                        </div>
                                        @if($employee->backgroundInfo && $employee->backgroundInfo->q38_a_details)
                                            <div class="mt-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg p-3 border border-blue-200 dark:border-blue-800">
                                                <p class="text-xs text-gray-600 dark:text-gray-400">
                                                    <i class="bi bi-info-circle mr-1 text-blue-500"></i>
                                                    <span class="font-medium">Details:</span> {{ $employee->backgroundInfo->q38_a_details }}
                                                </p>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- 38b -->
                                    <div class="pt-3 border-t border-gray-200 dark:border-gray-600">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white mb-2">b. Have you resigned from the government service during the three (3)-month period before the last election to promote/actively campaign for a national or local candidate?</p>
                                        <div class="flex items-center space-x-4">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                                @if($employee->backgroundInfo && $employee->backgroundInfo->q38_b === 'yes') 
                                                    bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400
                                                @elseif($employee->backgroundInfo && $employee->backgroundInfo->q38_b === 'no')
                                                    bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-300
                                                @else
                                                    bg-gray-100 dark:bg-gray-600 text-gray-400 dark:text-gray-500
                                                @endif">
                                                @if($employee->backgroundInfo && $employee->backgroundInfo->q38_b === 'yes')
                                                    <i class="bi bi-check-circle mr-1.5"></i> Yes
                                                @elseif($employee->backgroundInfo && $employee->backgroundInfo->q38_b === 'no')
                                                    <i class="bi bi-x-circle mr-1.5"></i> No
                                                @else
                                                    <i class="bi bi-dash-circle mr-1.5"></i> Not Answered
                                                @endif
                                            </span>
                                        </div>
                                        @if($employee->backgroundInfo && $employee->backgroundInfo->q38_b_details)
                                            <div class="mt-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg p-3 border border-blue-200 dark:border-blue-800">
                                                <p class="text-xs text-gray-600 dark:text-gray-400">
                                                    <i class="bi bi-info-circle mr-1 text-blue-500"></i>
                                                    <span class="font-medium">Details:</span> {{ $employee->backgroundInfo->q38_b_details }}
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Question 39: Immigrant Status -->
                        <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-5 border border-gray-200 dark:border-gray-600 mt-2">
                            <div class="flex items-start">
                                <!-- <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-bold text-sm flex-shrink-0 mr-3 mt-0.5">
                                    39
                                </span> -->
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white mb-2">
                                        Have you acquired the status of an immigrant or permanent resident of another country?
                                    </p>
                                    <div class="flex items-center space-x-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                            @if($employee->backgroundInfo && $employee->backgroundInfo->q39 === 'yes') 
                                                bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400
                                            @elseif($employee->backgroundInfo && $employee->backgroundInfo->q39 === 'no')
                                                bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-300
                                            @else
                                                bg-gray-100 dark:bg-gray-600 text-gray-400 dark:text-gray-500
                                            @endif">
                                            @if($employee->backgroundInfo && $employee->backgroundInfo->q39 === 'yes')
                                                <i class="bi bi-check-circle mr-1.5"></i> Yes
                                            @elseif($employee->backgroundInfo && $employee->backgroundInfo->q39 === 'no')
                                                <i class="bi bi-x-circle mr-1.5"></i> No
                                            @else
                                                <i class="bi bi-dash-circle mr-1.5"></i> Not Answered
                                            @endif
                                        </span>
                                    </div>
                                    @if($employee->backgroundInfo && $employee->backgroundInfo->q39_details)
                                        <div class="mt-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg p-3 border border-blue-200 dark:border-blue-800">
                                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                                <i class="bi bi-info-circle mr-1 text-blue-500"></i>
                                                <span class="font-medium">Country:</span> {{ $employee->backgroundInfo->q39_details }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Question 40: Special Status -->
                        <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-5 border border-gray-200 dark:border-gray-600 mt-2">
                            <div class="flex items-start">
                                <!-- <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-bold text-sm flex-shrink-0 mr-3 mt-0.5">
                                    40
                                </span> -->
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
                                                <div class="flex items-center space-x-4">
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                                        @if($employee->backgroundInfo && $employee->backgroundInfo->q40_a === 'yes') 
                                                            bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400
                                                        @elseif($employee->backgroundInfo && $employee->backgroundInfo->q40_a === 'no')
                                                            bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-300
                                                        @else
                                                            bg-gray-100 dark:bg-gray-600 text-gray-400 dark:text-gray-500
                                                        @endif">
                                                        @if($employee->backgroundInfo && $employee->backgroundInfo->q40_a === 'yes')
                                                            <i class="bi bi-check-circle mr-1.5"></i> Yes
                                                        @elseif($employee->backgroundInfo && $employee->backgroundInfo->q40_a === 'no')
                                                            <i class="bi bi-x-circle mr-1.5"></i> No
                                                        @else
                                                            <i class="bi bi-dash-circle mr-1.5"></i> Not Answered
                                                        @endif
                                                    </span>
                                                </div>
                                                @if($employee->backgroundInfo && $employee->backgroundInfo->q40_a_details)
                                                    <div class="mt-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg p-3 border border-blue-200 dark:border-blue-800">
                                                        <p class="text-xs text-gray-600 dark:text-gray-400">
                                                            <i class="bi bi-info-circle mr-1 text-blue-500"></i>
                                                            <span class="font-medium">Indigenous Group:</span> {{ $employee->backgroundInfo->q40_a_details }}
                                                        </p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- 40b -->
                                        <div class="flex items-start pt-2 border-t border-gray-200 dark:border-gray-600">
                                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400 w-6">b.</span>
                                            <div class="flex-1">
                                                <p class="text-sm text-gray-700 dark:text-gray-300 mb-2">Are you a person with disability?</p>
                                                <div class="flex items-center space-x-4">
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                                        @if($employee->backgroundInfo && $employee->backgroundInfo->q40_b === 'yes') 
                                                            bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400
                                                        @elseif($employee->backgroundInfo && $employee->backgroundInfo->q40_b === 'no')
                                                            bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-300
                                                        @else
                                                            bg-gray-100 dark:bg-gray-600 text-gray-400 dark:text-gray-500
                                                        @endif">
                                                        @if($employee->backgroundInfo && $employee->backgroundInfo->q40_b === 'yes')
                                                            <i class="bi bi-check-circle mr-1.5"></i> Yes
                                                        @elseif($employee->backgroundInfo && $employee->backgroundInfo->q40_b === 'no')
                                                            <i class="bi bi-x-circle mr-1.5"></i> No
                                                        @else
                                                            <i class="bi bi-dash-circle mr-1.5"></i> Not Answered
                                                        @endif
                                                    </span>
                                                </div>
                                                @if($employee->backgroundInfo && $employee->backgroundInfo->q40_b_details)
                                                    <div class="mt-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg p-3 border border-blue-200 dark:border-blue-800">
                                                        <p class="text-xs text-gray-600 dark:text-gray-400">
                                                            <i class="bi bi-info-circle mr-1 text-blue-500"></i>
                                                            <span class="font-medium">Disability Type:</span> {{ $employee->backgroundInfo->q40_b_details }}
                                                        </p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- 40c -->
                                        <div class="flex items-start pt-2 border-t border-gray-200 dark:border-gray-600">
                                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400 w-6">c.</span>
                                            <div class="flex-1">
                                                <p class="text-sm text-gray-700 dark:text-gray-300 mb-2">Are you a solo parent?</p>
                                                <div class="flex items-center space-x-4">
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                                        @if($employee->backgroundInfo && $employee->backgroundInfo->q40_c === 'yes') 
                                                            bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400
                                                        @elseif($employee->backgroundInfo && $employee->backgroundInfo->q40_c === 'no')
                                                            bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-300
                                                        @else
                                                            bg-gray-100 dark:bg-gray-600 text-gray-400 dark:text-gray-500
                                                        @endif">
                                                        @if($employee->backgroundInfo && $employee->backgroundInfo->q40_c === 'yes')
                                                            <i class="bi bi-check-circle mr-1.5"></i> Yes
                                                        @elseif($employee->backgroundInfo && $employee->backgroundInfo->q40_c === 'no')
                                                            <i class="bi bi-x-circle mr-1.5"></i> No
                                                        @else
                                                            <i class="bi bi-dash-circle mr-1.5"></i> Not Answered
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="references-content" class="tab-content hidden">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fa-solid fa-people-group mr-2 text-blue-600"></i>
                            References
                        </h4>
                        @if($employee->references && $employee->references->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead>
                                        <tr class="bg-gray-50 dark:bg-gray-700/50">
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">#</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Occupation</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Contact</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($employee->references as $index => $reference)
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

                    <div id="employment-content" class="tab-content hidden">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fa-solid fa-building-circle-check mr-2 text-blue-600"></i>
                            Employment
                        </h4>
                        @if($employee->employment)
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 px-6 py-4 bg-gray-50 border-b border-gray-100">
                                    <div>
                                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Employee ID</p>
                                        <p class="text-sm font-bold text-gray-900">{{ $employee->employment->employee_id ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Department</p>
                                        <p class="text-sm font-bold text-gray-900">{{ $employee->employment->department->name ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Position</p>
                                        <p class="text-sm font-bold text-gray-900">{{ $employee->employment->position->title ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="p-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                                        <div class="space-y-4">
                                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                                <span class="text-sm font-medium text-gray-600">Date Hired</span>
                                                <span class="text-sm text-gray-900 font-medium">{{ $employee->employment->formatted_hire_date }}</span>
                                            </div>
                                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                                <span class="text-sm font-medium text-gray-600">Employment Type</span>
                                                <span class="text-sm text-gray-900 font-medium px-2.5 py-0.5 rounded-full {{ $employee->employment->employment_type_color }}">
                                                    {{ $employee->employment->employment_type_label }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="space-y-4">
                                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                                <span class="text-sm font-medium text-gray-600">Monthly Salary</span>
                                                <span class="text-sm text-gray-900 font-medium">{{ $employee->employment->formatted_salary }}</span>
                                            </div>
                                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                                <span class="text-sm font-medium text-gray-600">Status</span>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $employee->employment->status_badge }}">
                                                    {{ $employee->employment->status_label }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <i class="bi bi-briefcase text-3xl block mb-2"></i>
                                <p>No employment information available.</p>
                            </div>
                        @endif
                    </div>

                    <div id="trainings-content" class="tab-content hidden">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fa-solid fa-dumbbell mr-2 text-blue-600"></i>
                            Trainings
                        </h4>
                        @if($employee->trainings && $employee->trainings->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead>
                                        <tr class="bg-gray-50 dark:bg-gray-700/50">
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">#</th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Training Program</th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Conducted By</th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Period</th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Hours</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($employee->trainings as $index => $training)
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

                    <div id="skills-content" class="tab-content hidden">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fa-solid fa-person-skiing mr-2 text-blue-600"></i>
                            Skills
                        </h4>
                        @if($employee->skills && $employee->skills->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead>
                                        <tr class="bg-gray-50 dark:bg-gray-700/50">
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">#</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Skill / Hobby</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($employee->skills as $index => $skill)
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

        <!-- Right Column: Sensitive Data & Actions -->
        <div class="space-y-6">
            @if($employee->employment?->status == 'terminated' && $employee->latestTermination())
                <div class="bg-white rounded-xl border-2 border-red-500 overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200">
                    <!-- Compact Header -->
                    <div class="bg-gradient-to-r from-red-600 to-red-700 px-4 py-3" style="background: linear-gradient(to right, #b91c1c, #dc2626);">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <i class="bi bi-person-x text-white text-xl"></i>
                                <span class="text-white font-bold text-md">Terminated</span>
                            </div>
                            <span class="inline-flex items-center bg-white/20 backdrop-blur-sm rounded-lg font-semibold text-xs text-white px-3 py-1 border border-white/30">
                                <i class="bi bi-calendar3 mr-1.5"></i>
                                {{ $employee->latestTermination?->termination_date->format('M d, Y') }}
                            </span>
                        </div>
                    </div>

                    <!-- Compact Body -->
                    <div class="p-4 space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Reason:</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold 
                                @if($employee->latestTermination?->termination_reason === 'resignation') bg-blue-100 text-blue-700
                                @elseif($employee->latestTermination?->termination_reason === 'retirement') bg-purple-100 text-purple-700
                                @elseif($employee->latestTermination?->termination_reason === 'redundancy') bg-yellow-100 text-yellow-700
                                @elseif($employee->latestTermination?->termination_reason === 'misconduct') bg-red-100 text-red-700
                                @elseif($employee->latestTermination?->termination_reason === 'death') bg-gray-800 text-white
                                @elseif($employee->latestTermination?->termination_reason === 'dismissal') bg-orange-100 text-orange-700
                                @elseif($employee->latestTermination?->termination_reason === 'drop_from_roll') bg-gray-100 text-gray-700
                                @elseif($employee->latestTermination?->termination_reason === 'end_of_term') bg-indigo-100 text-indigo-700
                                @elseif($employee->latestTermination?->termination_reason === 'laid_off') bg-amber-100 text-amber-700
                                @elseif($employee->latestTermination?->termination_reason === 'separation') bg-pink-100 text-pink-700
                                @elseif($employee->latestTermination?->termination_reason === 'termination') bg-red-100 text-red-700
                                @else bg-gray-100 text-gray-700
                                @endif capitalize">
                                <span class="w-1.5 h-1.5 rounded-full 
                                    @if($employee->latestTermination?->termination_reason === 'resignation') bg-blue-500
                                    @elseif($employee->latestTermination?->termination_reason === 'retirement') bg-purple-500
                                    @elseif($employee->latestTermination?->termination_reason === 'redundancy') bg-yellow-500
                                    @elseif($employee->latestTermination?->termination_reason === 'misconduct') bg-red-500
                                    @elseif($employee->latestTermination?->termination_reason === 'death') bg-gray-800
                                    @elseif($employee->latestTermination?->termination_reason === 'dismissal') bg-orange-500
                                    @elseif($employee->latestTermination?->termination_reason === 'drop_from_roll') bg-gray-500
                                    @elseif($employee->latestTermination?->termination_reason === 'end_of_term') bg-indigo-500
                                    @elseif($employee->latestTermination?->termination_reason === 'laid_off') bg-amber-500
                                    @elseif($employee->latestTermination?->termination_reason === 'separation') bg-pink-500
                                    @elseif($employee->latestTermination?->termination_reason === 'termination') bg-red-500
                                    @else bg-gray-500
                                    @endif mr-1.5"></span>
                                {{ str_replace('_', ' ', $employee->latestTermination?->termination_reason ?? 'N/A') }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Remarks:</span>
                            <div>
                                <i class='bi bi-info-circle'></i>
                                {{ $employee->latestTermination?->remarks }}
                            </div>
                        </div>
                        @if($employee->latestTermination?->termination_details)
                        <div class="text-xs text-gray-600 bg-gray-50 p-2 rounded border border-gray-200">
                            <i class="bi bi-chat-left-text mr-1"></i>
                            {{ $employee->latestTermination->termination_details }}
                        </div>
                        @endif

                        <!-- View All Terminations Button - Always show if there are any terminations -->
                        <div class="pt-2 border-t border-gray-200">
                            <button type="button" 
                                    onclick="openTerminationHistoryModal()"
                                    class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium rounded-lg transition-all duration-200 border border-gray-300 hover:border-gray-400 w-full justify-center">
                                <i class="bi bi-clock-history mr-1.5"></i>
                                View All Terminations ({{ $employee->terminationDetail->count() }})
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Sensitive Information (HR Only) -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-200">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-blue-700 to-blue-600">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-white">
                            <i class="fa-solid fa-id-card mr-2"></i>
                            Government IDs
                        </h3>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- GSIS -->
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-start justify-between">
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">GSIS</label>
                                    <p class="text-sm text-gray-900 font-medium mt-1">{{ $sensitiveData['gsis_number'] ?? 'N/A' }}</p>
                                </div>
                                <span class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600">
                                    <i class="bi bi-shield-check"></i>
                                </span>
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-start justify-between">
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">SSS</label>
                                    <p class="text-sm text-gray-900 font-medium mt-1">{{ $sensitiveData['sss_number'] ?? 'N/A' }}</p>
                                </div>
                                <span class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600">
                                    <i class="bi bi-shield-check"></i>
                                </span>
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-start justify-between">
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pag-IBIG</label>
                                    <p class="text-sm text-gray-900 font-medium mt-1">{{ $sensitiveData['pagibig_number'] ?? 'N/A' }}</p>
                                </div>
                                <span class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center text-green-600">
                                    <i class="bi bi-house-heart"></i>
                                </span>
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-start justify-between">
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">PhilHealth</label>
                                    <p class="text-sm text-gray-900 font-medium mt-1">{{ $sensitiveData['philhealth_number'] ?? 'N/A' }}</p>
                                </div>
                                <span class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center text-red-600">
                                    <i class="bi bi-heart-pulse"></i>
                                </span>
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-start justify-between">
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">TIN</label>
                                    <p class="text-sm text-gray-900 font-medium mt-1">{{ $sensitiveData['tin_number'] ?? 'N/A' }}</p>
                                </div>
                                <span class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center text-yellow-600">
                                    <i class="bi bi-receipt"></i>
                                </span>
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-start justify-between">
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Driver's License</label>
                                    <p class="text-sm text-gray-900 font-medium mt-1">{{ $sensitiveData['dl_number'] ?? 'N/A' }}</p>
                                </div>
                                <span class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center text-purple-600">
                                    <i class="bi bi-car-front"></i>
                                </span>
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-start justify-between">
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">UMID</label>
                                    <p class="text-sm text-gray-900 font-medium mt-1">{{ $sensitiveData['umid_number'] ?? 'N/A' }}</p>
                                </div>
                                <span class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600">
                                    <i class="bi bi-person-badge"></i>
                                </span>
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-start justify-between">
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">PRC</label>
                                    <p class="text-sm text-gray-900 font-medium mt-1">{{ $sensitiveData['prc_number'] ?? 'N/A' }}</p>
                                </div>
                                <span class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center text-orange-600">
                                    <i class="bi bi-award"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- HR Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-200">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-600 to-gray-500">
                    <h3 class="text-lg font-semibold text-white">
                        <i class="bi bi-gear mr-2 text-white"></i>
                        Actions
                    </h3>
                </div>
                <div class="p-4 space-y-2">
                    @if($employee->employment?->status != 'terminated')
                        <button class="w-full flex items-center justify-center px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-all duration-200 font-medium hover:shadow-lg" 
                                id="terminateBtn">
                            <i class="bi bi-person-x mr-2"></i>
                            Terminate Employee
                        </button>
                    @else
                        <form action="{{ route('employees.termination.cancel', $employee) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="w-full flex items-center justify-center px-4 py-2.5 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-all duration-200 font-medium hover:shadow-lg"
                                    onclick="return confirm('⚠️ Are you sure you want to reactivate employment status?\n\nThis action will:\n• Reactivate the employee account\n• Update employment status to active')">
                                <i class="bi bi-arrow-counterclockwise mr-1.5"></i>
                                Reactivate Employment
                            </button>
                        </form>
                    @endif
                    <button class="w-full flex items-center justify-center px-4 py-2.5 bg-gray-700 hover:bg-gray-800 text-white rounded-lg transition-all duration-200 font-medium hover:shadow-lg">
                        <i class="bi bi-file-pdf mr-2"></i>
                        Generate Report
                    </button>
                </div>
            </div>

            <!-- Activity Log -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-200">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-orange-600 to-orange-500">
                    <h3 class="text-lg font-semibold text-white">
                        <i class="bi bi-clock-history mr-2 text-white"></i>
                        Recent Activity
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-start gap-3 p-2 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 mt-1.5"></div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-900 font-medium">Profile updated by HR</p>
                                <p class="text-xs text-gray-500 mt-0.5">2 hours ago</p>
                            </div>
                            <span class="text-xs bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-full">Updated</span>
                        </div>
                        <div class="flex items-start gap-3 p-2 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="w-2.5 h-2.5 rounded-full bg-blue-500 mt-1.5"></div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-900 font-medium">Leave request approved</p>
                                <p class="text-xs text-gray-500 mt-0.5">1 day ago</p>
                            </div>
                            <span class="text-xs bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full">Approved</span>
                        </div>
                        <div class="flex items-start gap-3 p-2 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="w-2.5 h-2.5 rounded-full bg-amber-500 mt-1.5"></div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-900 font-medium">Attendance record updated</p>
                                <p class="text-xs text-gray-500 mt-0.5">3 days ago</p>
                            </div>
                            <span class="text-xs bg-amber-50 text-amber-700 px-2 py-0.5 rounded-full">Pending</span>
                        </div>
                    </div>
                    <a href="#" class="text-sm text-blue-600 hover:text-blue-800 font-medium mt-4 inline-flex items-center group">
                        View all activity 
                        <i class="bi bi-arrow-right ml-1 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Terminate Modal -->
<div id="terminateModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[9999] hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto">
        <form action="{{ route('employees.terminate', $employee) }}" method="POST" id="terminateForm">
            @csrf
            <div class="px-6 py-5 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <h5 class="text-red-600 text-xl font-bold">
                        <i class="bi bi-exclamation-triangle mr-2"></i>
                        Terminate Employee
                    </h5>
                    <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors text-2xl leading-none" id="closeModalBtn">
                        ×
                    </button>
                </div>
            </div>
            <div class="px-6 py-4">
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                    <div class="flex items-start">
                        <i class="bi bi-info-circle text-red-500 mr-2 mt-0.5"></i>
                        <p class="text-sm text-red-800">
                            <strong>Warning:</strong> You are about to terminate <strong>{{ $employee->display_name }}</strong>. 
                            This action will permanently deactivate their account and remove access to the system.
                        </p>
                    </div>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Termination Date <span class="text-red-500">*</span></label>
                        <input type="date" name="termination_date" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Reason for Termination <span class="text-red-500">*</span></label>
                        <select name="termination_reason" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200" required>
                            <option value="">Select reason...</option>
                            <option value="resignation">Resignation</option>
                            <option value="retirement">Retirement</option>
                            <option value="death">Death</option>
                            <option value="dismissal">Dismissal</option>
                            <option value="drop_from_roll">Drop From the Roll</option>
                            <option value="end_of_term">End of Term</option>
                            <option value="laid_off">Laid Off</option>
                            <option value="separation">Separation</option>
                            <option value="termination">Termination</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Additional Details</label>
                        <textarea name="termination_details" rows="3" 
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200"
                                  placeholder="Provide additional details about the termination..."></textarea>
                    </div>
                </div>
            </div>
            <div class="px-6 py-5 border-t border-gray-100 flex justify-end gap-3">
                <button type="button" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-all duration-200" id="cancelModalBtn">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-all duration-200 hover:shadow-lg">
                    <i class="bi bi-person-x mr-2"></i>
                    Terminate Employee
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Termination History Modal -->
<div id="terminationHistoryModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[9999] hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4 flex items-center justify-between" style="background: linear-gradient(to right, #dc2626, #b91c1c);">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                    <i class="bi bi-clock-history text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-white font-bold text-lg">Termination History</h3>
                    <p class="text-red-100 text-sm">
                        {{ $employee->display_name }} · {{ $employee->terminationDetail->count() }} record(s)
                    </p>
                </div>
            </div>
            <button type="button" 
                    onclick="closeTerminationHistoryModal()"
                    class="text-white/80 hover:text-white transition-colors text-2xl leading-none hover:bg-white/10 rounded-lg w-8 h-8 flex items-center justify-center">
                ×
            </button>
        </div>

        <!-- Modal Body -->
        <div class="overflow-y-auto max-h-[calc(90vh-140px)] p-6">
            @if($employee->terminationDetail->count() > 0)
                <div class="space-y-4">
                    @foreach($employee->terminationDetail->sortByDesc('termination_date') as $index => $termination)
                        <div class="bg-white rounded-xl border {{ $loop->first ? 'border-red-500 border-2 shadow-md' : 'border-gray-200' }} overflow-hidden hover:shadow-md transition-shadow duration-200">
                            <!-- Record Header -->
                            <div class="{{ $loop->first ? 'bg-red-50' : 'bg-gray-50' }} px-4 py-3 border-b {{ $loop->first ? 'border-red-200' : 'border-gray-200' }}">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $loop->first ? 'bg-red-100 text-red-600' : 'bg-gray-200 text-gray-600' }} text-sm font-bold">
                                            {{ $loop->iteration }}
                                        </span>
                                        <div>
                                            <span class="font-semibold text-gray-900 text-sm">
                                                Termination Record #{{ $loop->iteration }}
                                            </span>
                                            @if($loop->first)
                                                <span class="inline-flex items-center ml-2 px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-500 text-white">
                                                    <i class="bi bi-star-fill mr-1 text-[8px]"></i>
                                                    Most Recent
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <span class="text-xs text-gray-500">
                                        <i class="bi bi-clock mr-1"></i>
                                        {{ $termination->created_at->format('M d, Y h:i A') }}
                                    </span>
                                </div>
                            </div>

                            <!-- Record Body -->
                            <div class="p-4">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Termination Date</p>
                                        <p class="text-sm font-bold text-gray-900 mt-1">
                                            <i class="bi bi-calendar-x text-red-500 mr-1.5"></i>
                                            {{ $termination->termination_date->format('F d, Y') }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Reason</p>
                                        <span class="text-sm capitalize inline-flex items-center px-2 py-0.5 rounded-full font-medium mt-1
                                            @if($termination->termination_reason === 'resignation') bg-blue-100 text-blue-700
                                            @elseif($termination->termination_reason === 'retirement') bg-purple-100 text-purple-700
                                            @elseif($termination->termination_reason === 'redundancy') bg-yellow-100 text-yellow-700
                                            @elseif($termination->termination_reason === 'misconduct') bg-red-100 text-red-700
                                            @elseif($termination->termination_reason === 'death') bg-gray-800 text-white
                                            @elseif($termination->termination_reason === 'dismissal') bg-orange-100 text-orange-700
                                            @elseif($termination->termination_reason === 'drop_from_roll') bg-gray-100 text-gray-700
                                            @elseif($termination->termination_reason === 'end_of_term') bg-indigo-100 text-indigo-700
                                            @elseif($termination->termination_reason === 'laid_off') bg-amber-100 text-amber-700
                                            @elseif($termination->termination_reason === 'separation') bg-pink-100 text-pink-700
                                            @elseif($termination->termination_reason === 'termination') bg-red-100 text-red-700
                                            @else bg-gray-100 text-gray-700
                                            @endif">
                                            <i class="bi bi-tag mr-1"></i>
                                            {{ ucfirst(str_replace('_', ' ', $termination->termination_reason ?? 'N/A')) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Remarks</p>
                                        <p class="text-sm text-gray-900 mt-1">
                                            <i class="bi bi-info-circle text-gray-500 mr-1.5"></i>
                                            {{ $termination->remarks}}
                                        </p>
                                    </div>
                                </div>

                                @if($termination->termination_details)
                                    <div class="mt-3 pt-3 border-t border-gray-100">
                                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Remarks</p>
                                        <p class="text-sm text-gray-700 mt-1 bg-gray-50 p-3 rounded-lg border border-gray-200 leading-relaxed">
                                            {{ $termination->termination_details }}
                                        </p>
                                    </div>
                                @endif

                                <!-- Record Footer -->
                                <div class="mt-3 pt-3 border-t border-gray-100 flex flex-wrap items-center justify-between gap-2 text-[10px] text-gray-400">
                                    <span>
                                        <i class="bi bi-person mr-1"></i>
                                        Recorded by: {{ $termination->createdBy->email ?? 'System' }}
                                    </span>
                                    
                                    @if($termination->updated_at != $termination->created_at)
                                        <span>
                                            <i class="bi bi-pencil mr-1"></i>
                                            Updated: {{ $termination->updated_at->format('M d, Y h:i A') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="bi bi-inbox text-3xl text-gray-400"></i>
                    </div>
                    <p class="text-gray-500">No termination records found</p>
                </div>
            @endif
        </div>

        <!-- Modal Footer -->
        <div class="border-t border-gray-200 px-6 py-4 bg-gray-50 flex flex-wrap items-center justify-between gap-2">
            <div class="flex items-center gap-3">
                <span class="text-xs text-gray-500">
                    <i class="bi bi-info-circle mr-1"></i>
                    Showing {{ $employee->terminationDetail->count() }} termination record(s)
                </span>
                <span class="text-xs text-gray-300">|</span>
                <span class="text-xs text-gray-500">
                    <i class="bi bi-arrow-up mr-1"></i>
                    Sorted by date (newest first)
                </span>
            </div>
            <button type="button" 
                    onclick="closeTerminationHistoryModal()"
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-all duration-200 hover:shadow-md">
                <i class="bi bi-x-lg mr-1.5"></i>
                Close
            </button>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .tab-content {
        display: none;
    }
    
    .tab-content.active {
        display: block;
        animation: tabFadeIn 0.3s ease-out;
    }
    
    @keyframes tabFadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    #terminateModal .bg-white,
    #terminationHistoryModal .bg-white {
        animation: modalFadeIn 0.3s ease-out;
    }
    
    @keyframes modalFadeIn {
        from {
            opacity: 0;
            transform: scale(0.95) translateY(-20px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }
    
    [data-tab-target].active {
        color: #2563eb;
        border-color: #2563eb;
    }
    
    ::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    
    ::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
</style>
@endpush

@push('scripts')
<script>
// Tab switching function
function switchTab(tabName) {
    const contents = document.querySelectorAll('.tab-content');
    const buttons = document.querySelectorAll('[data-tab-target]');
    
    contents.forEach(function(content) {
        content.classList.add('hidden');
        content.classList.remove('active');
    });
    
    buttons.forEach(function(button) {
        button.classList.remove('active');
        button.classList.remove('border-blue-600');
        button.classList.remove('text-blue-600');
        button.classList.add('border-transparent');
        button.classList.add('text-gray-500');
    });
    
    const selectedContent = document.getElementById(tabName + '-content');
    if (selectedContent) {
        selectedContent.classList.remove('hidden');
        selectedContent.classList.add('active');
    }
    
    const selectedButton = document.getElementById(tabName + '-tab');
    if (selectedButton) {
        selectedButton.classList.add('active');
        selectedButton.classList.add('border-blue-600');
        selectedButton.classList.add('text-blue-600');
        selectedButton.classList.remove('border-transparent');
        selectedButton.classList.remove('text-gray-500');
    }
}

// Terminate Modal functions
function showModal() {
    const modal = document.getElementById('terminateModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
}

function hideModal() {
    const modal = document.getElementById('terminateModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }
}

// Termination History Modal functions
function openTerminationHistoryModal() {
    const modal = document.getElementById('terminationHistoryModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
}

function closeTerminationHistoryModal() {
    const modal = document.getElementById('terminationHistoryModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }
}

// Toggle dropdown
function toggleDropdown(event) {
    event.stopPropagation();
    const dropdown = document.getElementById('dropdownMenu');
    if (dropdown) {
        dropdown.classList.toggle('hidden');
    }
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('dropdownMenu');
    if (dropdown && !dropdown.classList.contains('hidden')) {
        const button = event.target.closest('button[onclick="toggleDropdown(event)"]');
        if (!button) {
            dropdown.classList.add('hidden');
        }
    }
});

// Set up event listeners when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Set default tab
    switchTab('personal');
    
    // Terminate button - show modal
    const terminateBtn = document.getElementById('terminateBtn');
    if (terminateBtn) {
        terminateBtn.addEventListener('click', function(e) {
            showModal();
        });
    }
    
    // Close button - hide modal
    const closeBtn = document.getElementById('closeModalBtn');
    if (closeBtn) {
        closeBtn.addEventListener('click', function(e) {
            hideModal();
        });
    }
    
    // Cancel button - hide modal
    const cancelBtn = document.getElementById('cancelModalBtn');
    if (cancelBtn) {
        cancelBtn.addEventListener('click', function(e) {
            hideModal();
        });
    }
    
    // Close terminate modal when clicking outside
    const terminateModal = document.getElementById('terminateModal');
    if (terminateModal) {
        terminateModal.addEventListener('click', function(e) {
            if (e.target === terminateModal) {
                hideModal();
            }
        });
    }
    
    // Close history modal when clicking outside
    const historyModal = document.getElementById('terminationHistoryModal');
    if (historyModal) {
        historyModal.addEventListener('click', function(e) {
            if (e.target === historyModal) {
                closeTerminationHistoryModal();
            }
        });
    }
    
    // Close modals with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const terminateModal = document.getElementById('terminateModal');
            if (terminateModal && !terminateModal.classList.contains('hidden')) {
                hideModal();
            }
            const historyModal = document.getElementById('terminationHistoryModal');
            if (historyModal && !historyModal.classList.contains('hidden')) {
                closeTerminationHistoryModal();
            }
        }
    });
    
    // Confirm before termination
    const terminateForm = document.getElementById('terminateForm');
    if (terminateForm) {
        terminateForm.addEventListener('submit', function(e) {
            if (!confirm('⚠️ Are you sure you want to terminate this employee? This action cannot be undone.')) {
                e.preventDefault();
                return false;
            }
        });
    }
});
</script>
@endpush