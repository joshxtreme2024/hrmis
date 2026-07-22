<!-- Form Body -->
<form action="{{ isset($department) ? route('departments.update', $department->id) : route('departments.store') }}" 
      method="POST" 
      class="p-8">
    @csrf
    @if(isset($department))
        @method('PUT')
    @endif

    <div class="space-y-8">
        <!-- Row 1: Name and Code -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Name -->
            <div class="space-y-1">
                <label for="name" class="block text-sm font-semibold text-gray-700">
                    Department Name <span class="text-red-500">*</span>
                </label>
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <input type="text" name="name" id="name" 
                           value="{{ old('name', isset($department) ? $department->name : '') }}"
                           class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('name') border-red-300 text-red-900 placeholder-red-300 @enderror"
                           placeholder="e.g., Information Technology" required>
                </div>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Code -->
            <div class="space-y-1">
                <label for="code" class="block text-sm font-semibold text-gray-700">
                    Department Code <span class="text-red-500">*</span>
                </label>
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                        </svg>
                    </div>
                    <input type="text" name="code" id="code" 
                           value="{{ old('code', isset($department) ? $department->code : '') }}"
                           class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('code') border-red-300 text-red-900 placeholder-red-300 @enderror"
                           placeholder="e.g., IT-DEPT-001" required>
                </div>
                @error('code')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Row 2: Status (Full Width) -->
        <div class="space-y-1">
            <label for="status" class="block text-sm font-semibold text-gray-700">
                Status <span class="text-red-500">*</span>
            </label>
            <div class="relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex space-x-6 pl-10 py-2">
                    <label class="inline-flex items-center">
                        <input type="radio" name="status" value="active" 
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300" 
                               {{ old('status', isset($department) ? $department->status : 'active') == 'active' ? 'checked' : '' }} required>
                        <span class="ml-2 text-sm text-gray-700">Active</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="status" value="inactive" 
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300" 
                               {{ old('status', isset($department) ? $department->status : '') == 'inactive' ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-700">Inactive</span>
                    </label>
                </div>
            </div>
            @error('status')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Row 3: Description (Full Width) -->
        <div class="space-y-1">
            <label for="description" class="block text-sm font-semibold text-gray-700">
                Description
            </label>
            <div class="relative rounded-md shadow-sm">
                <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                </div>
                <textarea name="description" id="description" rows="4"
                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200"
                    placeholder="Describe the department's purpose, responsibilities, and scope...">{{ old('description', isset($department) ? $department->description : '') }}</textarea>
            </div>
            <p class="mt-1 text-xs text-gray-500">Provide a comprehensive description of the department's role in the organization.</p>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
            <a href="{{ route('departments.index') }}" 
                class="px-6 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                Cancel
            </a>
            <button type="submit"
                class="px-6 py-3 border border-transparent rounded-lg text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ isset($department) ? 'Update Department' : 'Create Department' }}
            </button>
        </div>
    </div>
</form>