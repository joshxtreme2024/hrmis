@extends('layouts.app')

@section('title', 'PDS - Edit Family Background')
@section('breadcrumbs')
    <li class="flex items-center">
        <i class="bi bi-chevron-right text-gray-300 dark:text-gray-600 mx-2 text-xs"></i>
        <span class="text-gray-800 dark:text-gray-200 font-medium">PDS</span>
    </li>
    <li class="flex items-center">
        <i class="bi bi-chevron-right text-gray-300 dark:text-gray-600 mx-2 text-xs"></i>
        <span class="text-gray-800 dark:text-gray-200 font-medium">Edit Family Background</span>
    </li>
@endsection

@section('content')
<div class="py-1">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Edit Family Background</h1>
            <p class="mt-2 text-sm text-gray-600">Update your family background information.</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
            <!-- Form Header with Icon -->
            <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-white bg-opacity-20 rounded-lg p-2">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-lg font-semibold text-white">Edit Family Background</h2>
                        <p class="text-sm text-indigo-100">Update your family information</p>
                    </div>
                    <div class="ml-auto flex items-center space-x-2">
                        <a href="{{ route('myprofile.show') }}" 
                           class="inline-flex items-center px-3 py-1.5 bg-white bg-opacity-20 hover:bg-opacity-30 text-white text-sm font-medium rounded-lg transition-all duration-200">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            View Profile
                        </a>
                        <button onclick="window.history.back()" 
                                class="inline-flex items-center px-3 py-1.5 bg-white bg-opacity-20 hover:bg-opacity-30 text-white text-sm font-medium rounded-lg transition-all duration-200">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back
                        </button>
                    </div>
                </div>
            </div>

            <!-- Form Body -->
            <form action="{{ route('myprofile.udpateFamilyDetails', $familyBackground->id) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-400 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-sm text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-400 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-sm text-red-700">Please fix the errors below.</p>
                        </div>
                    </div>
                @endif

                <!-- Spouse Information Section -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Spouse Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Spouse First Name</label>
                            <input type="text" name="spouse_first_name" value="{{ old('spouse_first_name', $familyBackground->spouse_first_name) }}" 
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 transition-colors @error('spouse_first_name') border-red-500 @enderror" 
                                   placeholder="Enter spouse first name">
                            @error('spouse_first_name')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Spouse Middle Name</label>
                            <input type="text" name="spouse_middle_name" value="{{ old('spouse_middle_name', $familyBackground->spouse_middle_name) }}" 
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 transition-colors @error('spouse_middle_name') border-red-500 @enderror" 
                                   placeholder="Enter spouse middle name">
                            @error('spouse_middle_name')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Spouse Last Name</label>
                            <input type="text" name="spouse_last_name" value="{{ old('spouse_last_name', $familyBackground->spouse_last_name) }}" 
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 transition-colors @error('spouse_last_name') border-red-500 @enderror" 
                                   placeholder="Enter spouse last name">
                            @error('spouse_last_name')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Spouse Name Extension</label>
                            <input type="text" name="spouse_name_extension" value="{{ old('spouse_name_extension', $familyBackground->spouse_name_extension) }}" 
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 transition-colors @error('spouse_name_extension') border-red-500 @enderror" 
                                   placeholder="e.g., Jr., Sr., III">
                            @error('spouse_name_extension')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Spouse Occupation</label>
                            <input type="text" name="spouse_occupation" value="{{ old('spouse_occupation', $familyBackground->spouse_occupation) }}" 
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 transition-colors @error('spouse_occupation') border-red-500 @enderror" 
                                   placeholder="Enter spouse occupation">
                            @error('spouse_occupation')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Spouse Employer/Business</label>
                            <input type="text" name="spouse_employer_business" value="{{ old('spouse_employer_business', $familyBackground->spouse_employer_business) }}" 
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 transition-colors @error('spouse_employer_business') border-red-500 @enderror" 
                                   placeholder="Enter employer or business name">
                            @error('spouse_employer_business')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Spouse Business Address</label>
                            <input type="text" name="spouse_business_address" value="{{ old('spouse_business_address', $familyBackground->spouse_business_address) }}" 
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 transition-colors @error('spouse_business_address') border-red-500 @enderror" 
                                   placeholder="Enter business address">
                            @error('spouse_business_address')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Spouse Telephone No.</label>
                            <input type="text" name="spouse_telephone_no" value="{{ old('spouse_telephone_no', $familyBackground->spouse_telephone_no) }}" 
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 transition-colors @error('spouse_telephone_no') border-red-500 @enderror" 
                                   placeholder="Enter telephone number">
                            @error('spouse_telephone_no')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Father's Information Section -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2v4m0 0v4m0-4h4m-4 0v4" />
                        </svg>
                        Father's Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Father First Name</label>
                            <input type="text" name="father_first_name" value="{{ old('father_first_name', $familyBackground->father_first_name) }}" 
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 transition-colors @error('father_first_name') border-red-500 @enderror" 
                                   placeholder="Enter father first name">
                            @error('father_first_name')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Father Middle Name</label>
                            <input type="text" name="father_middle_name" value="{{ old('father_middle_name', $familyBackground->father_middle_name) }}" 
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 transition-colors @error('father_middle_name') border-red-500 @enderror" 
                                   placeholder="Enter father middle name">
                            @error('father_middle_name')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Father Last Name</label>
                            <input type="text" name="father_last_name" value="{{ old('father_last_name', $familyBackground->father_last_name) }}" 
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 transition-colors @error('father_last_name') border-red-500 @enderror" 
                                   placeholder="Enter father last name">
                            @error('father_last_name')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Father Name Extension</label>
                            <input type="text" name="father_name_extension" value="{{ old('father_name_extension', $familyBackground->father_name_extension) }}" 
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 transition-colors @error('father_name_extension') border-red-500 @enderror" 
                                   placeholder="e.g., Jr., Sr., III">
                            @error('father_name_extension')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Mother's Information Section -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        Mother's Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Mother First Name</label>
                            <input type="text" name="mother_first_name" value="{{ old('mother_first_name', $familyBackground->mother_first_name) }}" 
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 transition-colors @error('mother_first_name') border-red-500 @enderror" 
                                   placeholder="Enter mother first name">
                            @error('mother_first_name')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Mother Middle Name</label>
                            <input type="text" name="mother_middle_name" value="{{ old('mother_middle_name', $familyBackground->mother_middle_name) }}" 
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 transition-colors @error('mother_middle_name') border-red-500 @enderror" 
                                   placeholder="Enter mother middle name">
                            @error('mother_middle_name')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Mother Last Name</label>
                            <input type="text" name="mother_last_name" value="{{ old('mother_last_name', $familyBackground->mother_last_name) }}" 
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 transition-colors @error('mother_last_name') border-red-500 @enderror" 
                                   placeholder="Enter mother last name">
                            @error('mother_last_name')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Mother Maiden Last Name</label>
                            <input type="text" name="mother_maiden_last_name" value="{{ old('mother_maiden_last_name', $familyBackground->mother_maiden_last_name) }}" 
                                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 transition-colors @error('mother_maiden_last_name') border-red-500 @enderror" 
                                   placeholder="Enter mother maiden last name">
                            @error('mother_maiden_last_name')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-8 pt-6 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-sm text-gray-500">
                        <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        All fields are optional. Fill in what you know.
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ route('myprofile.show') }}" 
                           class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium rounded-lg transition-all duration-200">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                            <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            Update Family Background
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Help Section -->
        <div class="mt-6 bg-blue-50 rounded-lg p-4 border border-blue-200">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3 flex-1 md:flex md:justify-between">
                    <p class="text-sm text-blue-700">
                        <span class="font-medium">Note:</span> Update your family background information. All fields are optional.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection