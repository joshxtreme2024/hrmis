@extends('layouts.app')

@section('content')
<div class="py-1">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-6 flex items-center space-x-2 text-sm text-gray-600">
            <a href="{{ route('departments.index') }}" class="hover:text-indigo-600 transition-colors duration-200">Departments</a>
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
            <span class="text-gray-800 font-medium">Create New Department</span>
        </nav>

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Create New Department</h1>
            <p class="mt-2 text-sm text-gray-600">Fill in the details below to add a new department.</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
            <!-- Form Header with Icon -->
            <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-white bg-opacity-20 rounded-lg p-2">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-lg font-semibold text-white">Department Information</h2>
                        <p class="text-sm text-indigo-100">Enter the details for the new department</p>
                    </div>
                </div>
            </div>

            <!-- Form Body -->
            @include('departments._form')
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
                        <span class="font-medium">Note:</span> Fields marked with <span class="text-red-500">*</span> are required.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection