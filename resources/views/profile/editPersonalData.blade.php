@extends('layouts.app')

@section('title', 'PDS - Edit Personal Information')

@section('header', 'Edit Personal Information')
@section('subheader', 'Update your personal details')

@section('header-actions')
    <a href="{{ route('myprofile.show') }}" 
       class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-medium rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
        <i class="bi bi-eye mr-2"></i>
        View Profile
    </a>
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
    <li class="flex items-center">
        <i class="bi bi-chevron-right text-gray-300 dark:text-gray-600 mx-2 text-xs"></i>
        <span class="text-gray-800 dark:text-gray-200 font-medium">Edit Personal Info</span>
    </li>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <form action="{{ route('myprofile.updatePersonalData') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Profile Photo Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-700/50 overflow-hidden mb-6">
            <div class="px-6 py-6">
                <div class="flex flex-col md:flex-row items-center gap-6">
                    <div class="relative">
                        <div class="w-32 h-32 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 p-1 shadow-xl">
                            <div class="w-full h-full rounded-2xl bg-white dark:bg-gray-800 flex items-center justify-center overflow-hidden relative">
                                @php
                                    $photoPath = null;
                                    if(isset($personalData) && isset($personalData->photo_path)) {
                                        $photoPath = $personalData->photo_path;
                                    }
                                    $initials = '?';
                                    if(isset($personalData)) {
                                        $initials = substr($personalData->first_name ?? '?', 0, 1) . substr($personalData->last_name ?? '?', 0, 1);
                                    }
                                @endphp
                                
                                @if($photoPath)
                                    <img src="{{ asset('storage/'.$photoPath) }}" 
                                         alt="Profile Photo" 
                                         class="w-full h-full object-cover"
                                         id="photoPreview">
                                @else
                                    <span class="text-4xl font-bold text-gray-400 dark:text-gray-500" id="photoPlaceholder">
                                        {{ $initials }}
                                    </span>
                                    <img src="" alt="Preview" class="w-full h-full object-cover hidden" id="photoPreview">
                                @endif
                            </div>
                        </div>
                        <label for="photo" class="absolute bottom-0 right-0 w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center cursor-pointer hover:bg-blue-700 transition-colors shadow-lg">
                            <i class="bi bi-camera text-white text-sm"></i>
                        </label>
                        <input type="file" name="photo" id="photo" class="hidden" accept="image/*" onchange="previewPhoto(event)">
                    </div>
                    
                    <div class="flex-1 text-center md:text-left">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                            {{ $personalData ? $personalData->first_name . ' ' . $personalData->last_name : 'Your Profile' }}
                        </h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            <i class="bi bi-info-circle mr-1"></i>
                            Click the camera icon to update your profile photo
                        </p>
                        <div class="mt-2 flex flex-wrap gap-2 justify-center md:justify-start">
                            <span class="px-3 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 text-xs font-medium rounded-full">
                                <i class="bi bi-pencil mr-1"></i> Editing Mode
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Personal Information Form -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-700/50 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-800">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                        <i class="bi bi-person text-white"></i>
                    </div>
                    <h3 class="ml-3 text-lg font-semibold text-gray-900 dark:text-white">Personal Information</h3>
                    <span class="ml-auto text-xs text-red-500">* Required fields</span>
                </div>
            </div>
            
            <div class="p-6">
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-400 rounded-xl">
                        <i class="bi bi-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-400 rounded-xl">
                        <i class="bi bi-exclamation-circle mr-2"></i>
                        Please fix the errors below.
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- First Name -->
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">First Name <span class="text-red-500">*</span></label>
                        <input type="text" name="first_name" value="{{ old('first_name', $personalData->first_name ?? '') }}" 
                               class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500 transition-colors @error('first_name') border-red-500 @enderror" 
                               placeholder="Enter first name" required>
                        @error('first_name')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Middle Name -->
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Middle Name</label>
                        <input type="text" name="middle_name" value="{{ old('middle_name', $personalData->middle_name ?? '') }}" 
                               class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500 transition-colors" 
                               placeholder="Enter middle name">
                               
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Last Name <span class="text-red-500">*</span></label>
                        <input type="text" name="last_name" value="{{ old('last_name', $personalData->last_name ?? '') }}" 
                               class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500 transition-colors @error('last_name') border-red-500 @enderror" 
                               placeholder="Enter last name" required>
                        @error('last_name')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Name Extension -->
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Name Extension</label>
                        <input type="text" name="ext_name" value="{{ old('ext_name', $personalData->ext_name ?? '') }}" 
                               class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500 transition-colors" 
                               placeholder="e.g., Jr., Sr., III">
                    </div>

                    <!-- sex -->
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">sex <span class="text-red-500">*</span></label>
                        <select name="sex" class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500 transition-colors @error('sex') border-red-500 @enderror" required>
                            <option value="">Select sex</option>
                            <option value="Male" {{ old('sex', $personalData->sex ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('sex', $personalData->sex ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                        @error('sex')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Birth Date -->
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Birth Date <span class="text-red-500">*</span></label>
                        <input type="date" name="birth_date" value="{{ old('birth_date', optional($personalData->birth_date)->format('Y-m-d')) }}" 
                               class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500 transition-colors @error('birth_date') border-red-500 @enderror" required>
                        @error('birth_date')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Place of Birth -->
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Place of Birth <span class="text-red-500">*</span></label>
                        <input type="text" name="place_of_birth" value="{{ old('place_of_birth', $personalData->place_of_birth ?? '') }}" 
                               class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500 transition-colors @error('place_of_birth') border-red-500 @enderror" 
                               placeholder="Enter place of birth" required>
                        @error('place_of_birth')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Marital Status -->
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Marital Status <span class="text-red-500">*</span></label>
                        <select name="civil_status" class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500 transition-colors @error('civil_status') border-red-500 @enderror" required>
                            <option value="">Select Status</option>
                            <option value="Single" {{ old('civil_status', $personalData->civil_status ?? '') == 'Single' ? 'selected' : '' }}>Single</option>
                            <option value="Married" {{ old('civil_status', $personalData->civil_status ?? '') == 'Married' ? 'selected' : '' }}>Married</option>
                            <option value="Divorced" {{ old('civil_status', $personalData->civil_status ?? '') == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                            <option value="Widowed" {{ old('civil_status', $personalData->civil_status ?? '') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                            <option value="Separated" {{ old('civil_status', $personalData->civil_status ?? '') == 'Separated' ? 'selected' : '' }}>Separated</option>
                        </select>
                        @error('civil_status')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nationality -->
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Nationality <span class="text-red-500">*</span></label>
                        <input type="text" name="nationality" value="{{ old('nationality', $personalData->nationality ?? '') }}" 
                               class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500 transition-colors @error('nationality') border-red-500 @enderror" 
                               placeholder="Enter nationality" required>
                        @error('nationality')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Religion -->
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Religion</label>
                        <input type="text" name="religion" value="{{ old('religion', $personalData->religion ?? '') }}" 
                               class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500 transition-colors" 
                               placeholder="Enter religion">
                    </div>

                    <!-- Height -->
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Height (cm)</label>
                        <input type="number" step="0.01" name="height" value="{{ old('height', $personalData->height ?? '') }}" 
                               class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500 transition-colors" 
                               placeholder="e.g., 165.5">
                    </div>

                    <!-- Weight -->
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Weight (kg)</label>
                        <input type="number" step="0.01" name="weight" value="{{ old('weight', $personalData->weight ?? '') }}" 
                               class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500 transition-colors" 
                               placeholder="e.g., 65.5">
                    </div>

                    <!-- Blood Type -->
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Blood Type</label>
                        <select name="blood_type" class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500 transition-colors">
                            <option value="">Select Blood Type</option>
                            <option value="A+" {{ old('blood_type', $personalData->blood_type ?? '') == 'A+' ? 'selected' : '' }}>A+</option>
                            <option value="A-" {{ old('blood_type', $personalData->blood_type ?? '') == 'A-' ? 'selected' : '' }}>A-</option>
                            <option value="B+" {{ old('blood_type', $personalData->blood_type ?? '') == 'B+' ? 'selected' : '' }}>B+</option>
                            <option value="B-" {{ old('blood_type', $personalData->blood_type ?? '') == 'B-' ? 'selected' : '' }}>B-</option>
                            <option value="AB+" {{ old('blood_type', $personalData->blood_type ?? '') == 'AB+' ? 'selected' : '' }}>AB+</option>
                            <option value="AB-" {{ old('blood_type', $personalData->blood_type ?? '') == 'AB-' ? 'selected' : '' }}>AB-</option>
                            <option value="O+" {{ old('blood_type', $personalData->blood_type ?? '') == 'O+' ? 'selected' : '' }}>O+</option>
                            <option value="O-" {{ old('blood_type', $personalData->blood_type ?? '') == 'O-' ? 'selected' : '' }}>O-</option>
                        </select>
                    </div>

                    <!-- telephone -->
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Telephone Number</label>
                        <input type="text" name="telephone_no" value="{{ old('telephone_no', $personalData->telephone_no ?? '') }}" 
                               class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500 transition-colors" 
                               placeholder="e.g., 123-456-7890">
                    </div>

                    <!-- mobile -->
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Mobile Number</label>
                        <input type="text" name="mobile_no" value="{{ old('mobile_no', $personalData->mobile_no ?? '') }}" 
                               class="mt-1 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500 transition-colors" 
                               placeholder="e.g., 123-456-7890">
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        <i class="bi bi-info-circle mr-1"></i>
                        Fields marked with <span class="text-red-500">*</span> are required
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ route('myprofile.show') }}" 
                           class="px-6 py-2.5 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-xl transition-all duration-200">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-medium rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                            <i class="bi bi-save mr-2"></i>
                            Update Profile
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
@push('scripts')
<script>
    function previewPhoto(event) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('photoPreview');
            const placeholder = document.getElementById('photoPlaceholder');
            if (preview) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                if (placeholder) {
                    placeholder.classList.add('hidden');
                }
            }
        }
        if (event.target.files && event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }
</script>
@endpush