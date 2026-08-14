@extends('layouts.app')
@section('breadcrumbs')
    <li class="flex items-center">
        <i class="bi bi-chevron-right text-gray-300 dark:text-gray-600 mx-2 text-xs"></i>
        <span class="text-gray-800 dark:text-gray-200 font-medium">Settings</span>
    </li>
    <li class="flex items-center">
        <i class="bi bi-chevron-right text-gray-300 dark:text-gray-600 mx-2 text-xs"></i>
        <span class="text-gray-800 dark:text-gray-200 font-medium">Users</span>
    </li>
@endsection
@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section with Gradient -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-8 border px-6 py-6 bg-gradient-to-r from-[#2dd4bf] to-[#1f2937] rounded-lg">
            <div>
                <h1 class="text-3xl font-bold text-white">Users</h1>
                <p class="text-sm text-gray-100 mt-1">Manage and organize user accounts</p>
            </div>
            <!-- <a href="{{ route('users.create') }}" class="group inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 border border-transparent rounded-xl font-semibold text-sm text-white shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-200">
                <svg class="w-5 h-5 mr-2 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add New User
            </a> -->
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
            <div class="bg-gradient-to-r from-[#0e7490] via-[#3b82f6] to-[#4f46e5] rounded-xl p-4 border border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-white font-medium">Total Users</p>
                        <p class="text-2xl font-bold text-white">{{ $users->total() }}</p>
                    </div>
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-bl from-[#84cc16] via-[#16a34a] to-[#0f766e] rounded-xl p-4 border border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-white font-medium">Active Users</p>
                        <p class="text-2xl font-bold text-white">{{ $users->where('status', 'enabled')->count() }}</p>
                    </div>
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-l from-[#fb7185] via-[#a21caf] to-[#6366f1] rounded-xl p-4 border border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-white font-medium">Disabled Users</p>
                        <p class="text-2xl font-bold text-white">{{ $users->where('status', 'disabled')->count() }}</p>
                    </div>
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-r from-[#f59e0b] via-[#d97706] to-[#b45309] rounded-xl p-4 border border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-white font-medium">Admin Users</p>
                        <p class="text-2xl font-bold text-white">{{ $users->where('role', 'admin')->count() }}</p>
                    </div>
                    <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Section with Modern Card Design -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">User</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Role</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Created</th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($users as $user)
                        <tr class="hover:bg-gradient-to-r hover:from-gray-50 hover:to-transparent transition-all duration-200 group">
                            <td class="px-6 py-4">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-green-100 to-green-100 flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                                            @if($user->avatar)
                                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->getDisplayInitialsAttribute() ?? strtoupper(substr($user->email, 0, 1)) }}" class="w-10 h-10 rounded-xl object-cover">
                                            @else
                                                <span class="text-lg font-semibold text-green-600">{{ $user->getDisplayInitialsAttribute() ?? strtoupper(substr($user->email, 0, 1)) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex flex-wrap items-center gap-2 mb-1">
                                            @if($user->role == 'admin')
                                            <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-gradient-to-r from-red-400 to-red-300 text-red-700">
                                                <div class="text-md font-semibold text-gray-900">{{ $user->getDisplayNameAttribute() ?? 'Admin' }}</div>
                                            </span>
                                            @elseif($user->role == 'hr')
                                            <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-gradient-to-r from-purple-400 to-purple-300 text-purple-700">
                                                <div class="text-md font-semibold text-gray-900">{{ $user->getDisplayNameAttribute() ?? 'HR Manager' }}</div>
                                            </span>
                                            @elseif($user->role == 'employee')
                                            <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-gradient-to-r from-orange-400 to-orange-300 text-orange-700">
                                                <div class="text-md font-semibold text-gray-900">{{ $user->getDisplayNameAttribute() ?? 'Employee' }}</div>
                                            </span>
                                            @elseif($user->role == 'finance')
                                            <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-gradient-to-r from-blue-400 to-blue-300 text-blue-700">
                                                <div class="text-md font-semibold text-gray-900">{{ $user->getDisplayNameAttribute() ?? 'Finance' }}</div>
                                            </span>
                                            @else
                                            <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-gradient-to-r from-gray-400 to-gray-300 text-gray-700">
                                                <div class="text-md font-semibold text-gray-900">{{ $user->getDisplayNameAttribute() ?? 'N/A' }}</div>
                                            </span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="inline-flex items-center px-3 py-1 rounded-full {{ $user->role == 'admin' ? 'bg-red-50 text-red-700' : 'bg-blue-50 text-blue-700' }} text-sm font-semibold">
                                    <span class="capitalize">{{ $user->role }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-600">{{ $user->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-400">{{ $user->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <!-- Toggle Switch with Form Submission -->
                                <div class="flex items-center justify-center">
                                    <form action="{{ $user->status == 'enabled' ? route('users.disable', $user) : route('users.enable', $user) }}" 
                                          method="POST" 
                                          class="inline toggle-form"
                                          id="toggle-form-{{ $user->id }}">
                                        @csrf
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" 
                                                   class="sr-only peer toggle-checkbox" 
                                                   data-form-id="toggle-form-{{ $user->id }}"
                                                   {{ $user->status == 'enabled' ? 'checked' : '' }}>
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                            <span class="ml-3 text-sm font-medium text-gray-700" id="status-label-{{ $user->id }}">
                                                {{ $user->status == 'enabled' ? 'Enabled' : 'Disabled' }}
                                            </span>
                                        </label>
                                    </form>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <!-- <a href="{{ route('users.edit', $user) }}" class="p-2 text-amber-600 hover:text-amber-800 hover:bg-amber-50 rounded-lg transition-all duration-200" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a> -->
                                    <a href="{{ route('users.show', $user) }}" class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-all duration-200" title="View Details">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    @if(auth()->id() !== $user->id)
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-all duration-200" title="Delete Account">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center mb-4">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-1">No users found</h3>
                                    <p class="text-sm text-gray-500 mb-4">Get started by creating your first user</p>
                                    <a href="{{ route('users.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Create User
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination with Modern Design -->
            @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $users->links() }}
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