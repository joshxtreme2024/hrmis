@extends('layouts.app')

@section('title', 'PDS - Add Children')
@section('breadcrumbs')
    <li class="flex items-center">
        <i class="bi bi-chevron-right text-gray-300 dark:text-gray-600 mx-2 text-xs"></i>
        <span class="text-gray-800 dark:text-gray-200 font-medium">PDS</span>
    </li>
    <li class="flex items-center">
        <i class="bi bi-chevron-right text-gray-300 dark:text-gray-600 mx-2 text-xs"></i>
        <span class="text-gray-800 dark:text-gray-200 font-medium">Add Children</span>
    </li>
@endsection

@section('content')
<div class="py-1">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Add Children</h1>
            <p class="mt-2 text-sm text-gray-600">Add your children's information to your PDS record. You can add multiple children at once.</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
            <!-- Form Header with Icon -->
            <div class="bg-gradient-to-r from-pink-600 to-rose-600 px-6 py-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-white bg-opacity-20 rounded-lg p-2">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-lg font-semibold text-white">Children Information</h2>
                        <p class="text-sm text-rose-100">Enter your children's details</p>
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
            <form action="{{ route('myprofile.storeChildren') }}" method="POST" class="p-6">
                @csrf

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

                <!-- Children Container -->
                <div id="children-container">
                    <!-- Child Entry Template -->
                    <div class="child-entry bg-gray-50 dark:bg-gray-700/30 rounded-xl p-6 mb-4 relative">
                        <!-- Remove Button -->
                        <button type="button" onclick="removeChild(this)" 
                                class="absolute top-3 right-3 text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <div class="flex items-center mb-4">
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Child #<span class="child-number">1</span></span>
                            <span class="ml-2 text-xs text-gray-500 dark:text-gray-400">(Complete all fields)</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Full Name -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="children[0][name]" value="{{ old('children.0.name') }}" 
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 focus:border-rose-500 focus:ring-rose-500 transition-colors @error('children.0.name') border-red-500 @enderror" 
                                       placeholder="Enter child's full name">
                                @error('children.0.name')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Date of Birth -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Date of Birth <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="children[0][date_of_birth]" value="{{ old('children.0.date_of_birth') }}" 
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 focus:border-rose-500 focus:ring-rose-500 transition-colors @error('children.0.date_of_birth') border-red-500 @enderror">
                                @error('children.0.date_of_birth')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Gender -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Gender <span class="text-red-500">*</span>
                                </label>
                                <select name="children[0][sex]" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 focus:border-rose-500 focus:ring-rose-500 transition-colors @error('children.0.sex') border-red-500 @enderror">
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('children.0.sex') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('children.0.sex') == 'female' ? 'selected' : '' }}>Female</option>
                                </select>
                                @error('children.0.sex')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Birth Order -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Birth Order <span class="text-gray-400 text-xs">(Optional)</span>
                                </label>
                                <input type="number" name="children[0][order]" value="{{ old('children.0.order') }}" 
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 focus:border-rose-500 focus:ring-rose-500 transition-colors @error('children.0.order') border-red-500 @enderror" 
                                       placeholder="e.g., 1, 2, 3" min="1">
                                @error('children.0.order')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add More Button -->
                <div class="mt-4">
                    <button type="button" onclick="addChild()" 
                            class="inline-flex items-center px-4 py-2 border-2 border-dashed border-pink-300 dark:border-pink-700 hover:border-pink-500 dark:hover:border-pink-500 text-pink-600 dark:text-pink-400 hover:text-pink-700 dark:hover:text-pink-300 text-sm font-medium rounded-lg transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Another Child
                    </button>
                </div>

                <!-- Form Actions -->
                <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Fields marked with <span class="text-red-500">*</span> are required.
                        <span class="block text-xs mt-1">You can add multiple children at once.</span>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ route('myprofile.show') }}" 
                           class="px-6 py-2.5 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-all duration-200">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-2.5 bg-gradient-to-r from-pink-600 to-rose-600 hover:from-pink-700 hover:to-rose-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                            <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            Save All Children
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Help Section -->
        <div class="mt-6 bg-pink-50 dark:bg-pink-900/20 rounded-lg p-4 border border-pink-200 dark:border-pink-800">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3 flex-1 md:flex md:justify-between">
                    <p class="text-sm text-pink-700 dark:text-pink-300">
                        <span class="font-medium">Note:</span> Provide accurate information about your children. 
                        You can add multiple children by clicking <span class="font-medium">"Add Another Child"</span>.
                        All children will be saved to your PDS record.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    let childCount = 1;

    function addChild() {
        const container = document.getElementById('children-container');
        const entry = document.createElement('div');
        entry.className = 'child-entry bg-gray-50 dark:bg-gray-700/30 rounded-xl p-6 mb-4 relative';
        entry.innerHTML = `
            <button type="button" onclick="removeChild(this)" 
                    class="absolute top-3 right-3 text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <div class="flex items-center mb-4">
                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Child #<span class="child-number">${childCount + 1}</span></span>
                <span class="ml-2 text-xs text-gray-500 dark:text-gray-400">(Complete all fields)</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="children[${childCount}][name]" 
                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 focus:border-rose-500 focus:ring-rose-500 transition-colors" 
                           placeholder="Enter child's full name">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Date of Birth <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="children[${childCount}][date_of_birth]" 
                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 focus:border-rose-500 focus:ring-rose-500 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Gender <span class="text-red-500">*</span>
                    </label>
                    <select name="children[${childCount}][sex]" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 focus:border-rose-500 focus:ring-rose-500 transition-colors">
                        <option value="">Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Birth Order <span class="text-gray-400 text-xs">(Optional)</span>
                    </label>
                    <input type="number" name="children[${childCount}][order]" 
                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 focus:border-rose-500 focus:ring-rose-500 transition-colors" 
                           placeholder="e.g., 1, 2, 3" min="1" value="${childCount + 1}">
                </div>
            </div>
        `;
        container.appendChild(entry);
        childCount++;
    }

    function removeChild(button) {
        const entry = button.closest('.child-entry');
        const container = document.getElementById('children-container');
        
        if (container.children.length > 1) {
            entry.remove();
            // Re-index the children
            reindexChildren();
        } else {
            // Show a message that at least one child is required
            alert('You need to keep at least one child entry. If you want to remove all, please cancel the form.');
        }
    }

    function reindexChildren() {
        const entries = document.querySelectorAll('.child-entry');
        entries.forEach((entry, index) => {
            // Update the child number label
            const numberSpan = entry.querySelector('.child-number');
            if (numberSpan) {
                numberSpan.textContent = index + 1;
            }

            // Update name attributes
            const inputs = entry.querySelectorAll('input, select');
            inputs.forEach(input => {
                const name = input.getAttribute('name');
                if (name) {
                    const newName = name.replace(/children\[\d+\]/, `children[${index}]`);
                    input.setAttribute('name', newName);
                }
            });

            // Update order field value if it exists and is empty
            const orderInput = entry.querySelector('input[name$="[order]"]');
            if (orderInput && !orderInput.value) {
                orderInput.value = index + 1;
            }
        });

        // Update the global counter
        childCount = entries.length;
    }

    // Auto-populate order when adding new child
    document.addEventListener('DOMContentLoaded', function() {
        // Set initial order for the first child if not set
        const firstOrder = document.querySelector('input[name="children[0][order]"]');
        if (firstOrder && !firstOrder.value) {
            firstOrder.value = 1;
        }
    });
</script>
@endpush