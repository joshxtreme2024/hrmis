@extends('layouts.app')
@section('breadcrumbs')
    <li class="flex items-center">
        <i class="bi bi-chevron-right text-gray-300 dark:text-gray-600 mx-2 text-xs"></i>
        <span class="text-gray-800 dark:text-gray-200 font-medium">Settings</span>
    </li>
    <li class="flex items-center">
        <i class="bi bi-chevron-right text-gray-300 dark:text-gray-600 mx-2 text-xs"></i>
        <span class="text-gray-800 dark:text-gray-200 font-medium">Positions</span>
    </li>
@endsection
@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section with Gradient -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-8 border px-6 py-6 bg-gradient-to-r from-[#2dd4bf]  to-[#1f2937]  rounded-lg">
            <div>
                <h1 class="text-3xl font-bold text-white">Positions</h1>
                <p class="text-sm text-gray-100 mt-1">Manage and organize organizational structure</p>
            </div>
            <a href="{{ route('positions.create') }}" class="group inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 border border-transparent rounded-xl font-semibold text-sm text-white shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-200">
                <svg class="w-5 h-5 mr-2 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add New Position
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="bg-gradient-to-r from-[#0e7490] via-[#3b82f6] to-[#4f46e5] rounded-xl p-4 border border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-white font-medium">Total Positions</p>
                        <p class="text-2xl font-bold text-white">{{ $positions->total() }}</p>
                    </div>
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-bl from-[#84cc16] via-[#16a34a] to-[#0f766e] rounded-xl p-4 border border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-white font-medium">Active Positions</p>
                        <p class="text-2xl font-bold text-white">{{ $positions->where('status', 'enabled')->count() }}</p>
                    </div>
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <a href="{{route('departments.index')}}">
                <div class="bg-gradient-to-l from-[#fb7185] via-[#a21caf] to-[#6366f1] rounded-xl p-4 border border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-white font-medium">Departments</p>
                            <p class="text-2xl font-bold text-white">{{ $depCount }}</p>
                        </div>
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Table Section with Modern Card Design -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Position</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Department</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Salary Grade</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Reports To</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Created</th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($positions as $position)
                        <tr class="hover:bg-gradient-to-r hover:from-gray-50 hover:to-transparent transition-all duration-200 group">
                            <td class="px-6 py-4">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex flex-wrap items-center gap-2 mb-1">
                                            <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-gradient-to-r from-purple-100 to-purple-50 text-purple-700">
                                                {{ $position->jobLevel->name }}
                                            </span>
                                        </div>
                                        <div class="text-base font-semibold text-gray-900">{{ $position->title }}</div>
                                        @if($position->description)
                                        <div class="text-xs text-gray-500 mt-1 line-clamp-1">{{ \Illuminate\Support\Str::limit($position->description, 80) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-lg bg-blue-100 flex items-center justify-center">
                                        <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">{{ $position->department->name ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="inline-flex items-center px-3 py-1 rounded-full bg-amber-50 text-amber-700 text-sm font-semibold">
                                    SG {{ $position->salary_grade ?: 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($position->reportsTo)
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center">
                                        <svg class="w-3 h-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm text-gray-700">{{ $position->reportsTo->title }}</span>
                                </div>
                                @else
                                <span class="text-sm text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-600">{{ $position->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-400">{{ $position->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <!-- Toggle Switch with Form Submission -->
                                <div class="flex items-center justify-center">
                                    <form action="{{ $position->status == 'enabled' ? route('positions.disable', $position) : route('positions.enable', $position) }}" 
                                          method="POST" 
                                          class="inline toggle-form"
                                          id="toggle-form-{{ $position->id }}">
                                        @csrf
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" 
                                                   class="sr-only peer toggle-checkbox" 
                                                   data-form-id="toggle-form-{{ $position->id }}"
                                                   {{ $position->status == 'enabled' ? 'checked' : '' }}>
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                            <span class="ml-3 text-sm font-medium text-gray-700" id="status-label-{{ $position->id }}">
                                                {{ $position->status == 'enabled' ? 'Enabled' : 'Disabled' }}
                                            </span>
                                        </label>
                                    </form>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('positions.edit', $position) }}" class="p-2 text-amber-600 hover:text-amber-800 hover:bg-amber-50 rounded-lg transition-all duration-200" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('positions.destroy', $position) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this position?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-all duration-200" title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center mb-4">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-1">No positions found</h3>
                                    <p class="text-sm text-gray-500 mb-4">Get started by creating your first position</p>
                                    <a href="{{ route('positions.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Create Position
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination with Modern Design -->
            @if($positions->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $positions->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get all toggle checkboxes
    const checkboxes = document.querySelectorAll('.toggle-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            // Find the form associated with this checkbox
            const formId = this.dataset.formId;
            const form = document.getElementById(formId);
            
            if (form) {
                // Show loading state on the checkbox
                this.disabled = true;
                
                // Submit the form
                form.submit();
            }
        });
    });
});
</script>

<style>
    /* Custom toggle styling */
    .peer:checked ~ .peer-checked\:bg-indigo-600 {
        background-color: #4f46e5;
    }
    
    .peer:focus ~ .peer-focus\:ring-4 {
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.3);
    }
    
    /* Disable pointer events while submitting */
    .toggle-checkbox:disabled {
        cursor: not-allowed;
        opacity: 0.6;
    }
</style>
@endpush
@endsection