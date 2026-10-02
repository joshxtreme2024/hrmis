@extends('layouts.app')

@section('breadcrumbs')
    <li class="flex items-center">
        <i class="bi bi-chevron-right text-gray-300 dark:text-gray-600 mx-2 text-xs"></i>
        @if(isset($user))
            <a href="{{ route('myprofile.show', $user) }}" class="text-gray-800 dark:text-gray-200 font-medium hover:text-blue-600 dark:hover:text-blue-400">PDS</a>
        @else
            <span class="text-gray-800 dark:text-gray-200 font-medium">PDS</span>
        @endif
    </li>
    <li class="flex items-center">
        <i class="bi bi-chevron-right text-gray-300 dark:text-gray-600 mx-2 text-xs"></i>
        <span class="text-gray-800 dark:text-gray-200 font-medium hover:text-blue-600 dark:hover:text-blue-400">Forms</span>
    </li>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                    Personal Data Sheet (PDS)
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    CS Form No. 212, Revised 2026
                </p>
            </div>
            <div class="flex items-center space-x-2">
                <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-sm rounded-full">
                    {{ $user->display_name ?? $user->email }}
                </span>
            </div>
        </div>
    </div>

    <!-- Main Action Buttons -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-8 mb-6">
        <div class="text-center">
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">
                Generate PDS Form
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                Select a page to view or download the complete PDS form
            </p>
            
            <!-- Page Navigation Buttons -->
            <div class="flex flex-wrap justify-center gap-3 mb-6">
                <a href="{{ route('pds.show', $user->id) }}?page=1" 
                   target="_blank" 
                   class="inline-flex items-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                    <i class="fas fa-file-alt mr-2"></i>
                    Page 1
                    <span class="ml-2 text-xs bg-blue-500/50 px-2 py-0.5 rounded-full">Personal Info</span>
                </a>
                
                <a href="{{ route('pds.show', $user->id) }}?page=2" 
                   target="_blank" 
                   class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                    <i class="fas fa-file-alt mr-2"></i>
                    Page 2
                    <span class="ml-2 text-xs bg-indigo-500/50 px-2 py-0.5 rounded-full">Education & Work</span>
                </a>
                
                <a href="{{ route('pds.show', $user->id) }}?page=3" 
                   target="_blank" 
                   class="inline-flex items-center px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                    <i class="fas fa-file-alt mr-2"></i>
                    Page 3
                    <span class="ml-2 text-xs bg-purple-500/50 px-2 py-0.5 rounded-full">Training & Others</span>
                </a>
                
                <a href="{{ route('pds.show', $user->id) }}?page=4" 
                   target="_blank" 
                   class="inline-flex items-center px-5 py-2.5 bg-pink-600 hover:bg-pink-700 text-white font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                    <i class="fas fa-file-alt mr-2"></i>
                    Page 4
                    <span class="ml-2 text-xs bg-pink-500/50 px-2 py-0.5 rounded-full">Declarations</span>
                </a>
            </div>

            <!-- Divider -->
            <div class="border-t border-gray-200 dark:border-gray-700 my-6"></div>
        </div>
    </div>

    <!-- Status/Progress Section
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mt-6">
        <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
            PDS Completion Status
        </h4>
        <div class="flex items-center gap-4 flex-wrap">
            @php
                $sections = [
                    'Personal Information' => $user->personalData ? true : false,
                    'Family Background' => $user->familyBackground ? true : false,
                    'Education' => $user->education && $user->education->count() > 0,
                    'Eligibility' => $user->eligibilities && $user->eligibilities->count() > 0,
                    'Work Experience' => $user->workExperiences && $user->workExperiences->count() > 0,
                    'Voluntary Work' => $user->voluntaryWorks && $user->voluntaryWorks->count() > 0,
                    'Training' => $user->trainings && $user->trainings->count() > 0,
                    'Other Info' => $user->skills && $user->skills->count() > 0,
                    'Declarations' => $user->backgroundInfo ? true : false,
                    'References' => $user->references && $user->references->count() > 0,
                ];
                $completed = collect($sections)->filter()->count();
                $total = count($sections);
                $percentage = round(($completed / $total) * 100);
            @endphp
            
            <div class="flex-1">
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                    <div class="bg-green-600 h-2.5 rounded-full transition-all duration-500" 
                         style="width: {{ $percentage }}%"></div>
                </div>
            </div>
            <div class="text-sm font-medium text-gray-700 dark:text-gray-300 whitespace-nowrap">
                {{ $completed }}/{{ $total }} sections completed ({{ $percentage }}%)
            </div>
        </div>
        <div class="flex flex-wrap gap-2 mt-3">
            @foreach($sections as $name => $completed)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                    {{ $completed ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' : 'bg-gray-100 dark:bg-gray-700/30 text-gray-500 dark:text-gray-400' }}">
                    @if($completed)
                        <i class="fas fa-check-circle mr-1 text-green-500"></i>
                    @else
                        <i class="fas fa-circle mr-1 text-gray-400"></i>
                    @endif
                    {{ $name }}
                </span>
            @endforeach
        </div>
    </div> -->
</div>
@endsection

@push('styles')
<style>
    .btn-primary, .btn-info, .btn-success {
        transition: all 0.2s ease;
    }
    .btn-primary:hover, .btn-info:hover, .btn-success:hover {
        transform: translateY(-1px);
    }
</style>
@endpush