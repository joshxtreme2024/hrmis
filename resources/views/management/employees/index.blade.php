@extends('layouts.app')

@section('title', 'Employee Management')
@section('breadcrumbs')
    <li class="flex items-center">
        <i class="bi bi-chevron-right text-gray-300 dark:text-gray-600 mx-2 text-xs"></i>
        <span class="text-gray-800 dark:text-gray-200 font-medium">Employee Management</span>
    </li>
@endsection

@section('page-header')
    <div class="flex items-center justify-between py-4 px-6 bg-white border-b border-gray-200">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Employee Management</h1>
            <ol class="flex items-center space-x-2 text-sm text-gray-500 mt-1">
                <li><a href="{{ route('dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
                <li><span class="text-gray-300">/</span></li>
                <li class="text-gray-700 font-medium">Employee Management</li>
            </ol>
        </div>
        <div class="flex gap-2">
            <button class="btn btn-primary bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors" 
                    data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                <i class="fas fa-user-plus"></i>
                <span>Add New Employee</span>
            </button>
            <button class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg flex items-center gap-2 transition-colors" 
                    onclick="window.print()">
                <i class="fas fa-print"></i>
                <span>Export</span>
            </button>
        </div>
    </div>
@endsection

@section('content')
    <div class="p-6">
        <!-- Filters and Search -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="p-4">
                <form action="{{ route('employees.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" name="search" 
                                class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                placeholder="Name, Email, ID..." 
                                value="{{ request('search') }}">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                        <select name="department" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Departments</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" 
                                    {{ request('department') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Employment Status</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="on_leave" {{ request('status') == 'on_leave' ? 'selected' : '' }}>On Leave</option>
                            <option value="resigned" {{ request('status') == 'resigned' ? 'selected' : '' }}>Resigned</option>
                            <option value="retired" {{ request('status') == 'retired' ? 'selected' : '' }}>Retired</option>
                            <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                            <option value="terminated" {{ request('status') == 'terminated' ? 'selected' : '' }}>Terminated</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                        <select name="position" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Positions</option>
                            @foreach($positions as $position)
                                <option value="{{ $position->id }}" 
                                    {{ request('position') == $position->id ? 'selected' : '' }}>
                                    {{ $position->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Quick Actions</label>
                        <div class="flex gap-2">
                            <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2 transition-colors">
                                <i class="fas fa-filter"></i>
                                <span>Apply</span>
                            </button>
                            <a href="{{ route('employees.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                                <i class="fas fa-undo"></i>
                                <span>Reset</span>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Employee Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-sm border-l-4 border-blue-500 p-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h6 class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Employees</h6>
                        <h2 class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['total'] ?? 0 }}</h2>
                    </div>
                    <div class="text-blue-500 opacity-50">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border-l-4 border-green-500 p-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h6 class="text-xs font-medium text-gray-500 uppercase tracking-wider">Active</h6>
                        <h2 class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['active'] ?? 0 }}</h2>
                    </div>
                    <div class="text-green-500 opacity-50">
                        <i class="fas fa-user-check fa-2x"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border-l-4 border-yellow-500 p-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h6 class="text-xs font-medium text-gray-500 uppercase tracking-wider">On Leave</h6>
                        <h2 class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['on_leave'] ?? 0 }}</h2>
                    </div>
                    <div class="text-yellow-500 opacity-50">
                        <i class="fas fa-user-clock fa-2x"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border-l-4 border-red-500 p-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h6 class="text-xs font-medium text-gray-500 uppercase tracking-wider">New This Month</h6>
                        <h2 class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['new_this_month'] ?? 0 }}</h2>
                    </div>
                    <div class="text-red-500 opacity-50">
                        <i class="fas fa-user-plus fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Employee Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="flex justify-between items-center p-4 border-b border-gray-200">
                <h5 class="text-lg font-semibold text-gray-800">Employee List</h5>
                <div class="flex items-center gap-2">
                    <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-sm font-medium">
                        {{ $employees->total() ?? 0 }} Employees
                    </span>
                    <div class="relative inline-block">
                        <button class="dropdown-trigger bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-1 rounded-lg text-sm flex items-center gap-1 transition-colors">
                            <i class="fas fa-file-export"></i>
                            <span>Export</span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <ul class="dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-10 hidden">
                            <li><a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors" href="#"><i class="fas fa-file-pdf mr-2"></i>PDF</a></li>
                            <li><a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors" href="#"><i class="fas fa-file-excel mr-2"></i>Excel</a></li>
                            <li><a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors" href="#"><i class="fas fa-file-csv mr-2"></i>CSV</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="w-12 px-4 py-3">
                                <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Hired</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($employees as $employee)
                            <tr class="hover:bg-blue-50 transition-colors">
                                <td class="px-4 py-3">
                                    <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 employee-checkbox" 
                                           value="{{ $employee->id }}">
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 mr-3">
                                            @if($employee->profile_picture)
                                                <img src="{{ asset('storage/' . $employee->profile_picture) }}" 
                                                     alt="{{ $employee->getDisplayNameAttribute() }}" 
                                                     class="h-10 w-10 rounded-full object-cover">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">
                                                    @php
                                                        $firstName = $employee->personalData?->first_name ?? '';
                                                        $lastName = $employee->personalData?->last_name ?? '';
                                                        $initials = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));
                                                    @endphp
                                                    {{ $initials ?: '?' }}
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <a href="{{ route('employees.showProfile', $employee) }}">
                                                <div class="font-medium text-gray-900">{{ $employee->display_name }}</div>
                                                <div class="text-sm text-gray-500">{{ $employee->email }}</div>
                                                @if($employee->employment?->employee_id)
                                                    <div class="text-xs text-gray-400">ID: {{ $employee->employment->employee_id }}</div>
                                                @endif
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $employee->employment->department->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $employee->employment->position->title ?? 'N/A' }}<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs {{ $employee->employment?->employment_type_color ?? 'bg-gray-100 text-gray-800' }}">{{ $employee->employment->employment_type_label ?? '' }}</span></td>
                                <td class="px-4 py-3">
                                    @if($employee->employment)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $employee->employment->status_badge }}">
                                            {{ $employee->employment->status_label }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            N/A
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    {{ $employee->employment->formatted_hire_date ?? 'N/A' }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-1">
                                        <div class="relative inline-block">
                                            <button class="dropdown-trigger p-1.5 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-10 hidden">
                                                <li><a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors" href="{{ route('employees.showProfile', $employee) }}"><i class="fa-solid fa-user mr-2"></i>View Profile</a></li>
                                                <li><a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors" href="{{ route('dashboard') }}"><i class="fas fa-file-contract mr-2"></i>View Contract</a></li>
                                                <li><a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors" href="#"><i class="fas fa-clock mr-2"></i>Attendance</a></li>
                                                <li><a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors" href="#"><i class="fas fa-calendar-alt mr-2"></i>Leave History</a></li>
                                                <li><a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors" href="#"><i class="fas fa-tasks mr-2"></i>Performance</a></li>
                                                <li><hr class="my-1 border-gray-200"></li>
                                                <li>
                                                    @if($employee->employment?->status != 'terminated')
                                                        <button type="button" 
                                                                onclick="event.stopPropagation(); openTerminateModal({{ $employee->id }}, '{{ addslashes($employee->display_name) }}')"
                                                                class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors flex items-center">
                                                            <i class="bi bi-person-x mr-2"></i>
                                                            Terminate Employee
                                                        </button>
                                                    @else
                                                        <form action="{{ route('employees.termination.cancel', $employee) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" 
                                                                    onclick="return confirm('⚠️ Are you sure you want to reactivate employment status?\n\nThis action will:\n• Reactivate the employee account\n• Update employment status to active')"
                                                                    class="w-full text-left px-4 py-2 text-sm text-green-600 hover:bg-green-50 transition-colors flex items-center">
                                                                <i class="bi bi-arrow-counterclockwise mr-2"></i>
                                                                Reactivate Employment
                                                            </button>
                                                        </form>
                                                    @endif
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-12 text-center">
                                    <i class="fas fa-users text-4xl text-gray-300 mb-3 block mx-auto" style="width: fit-content;"></i>
                                    <h5 class="text-lg font-medium text-gray-500">No employees found</h5>
                                    <p class="text-sm text-gray-400 mt-1">Try adjusting your filters or add a new employee.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="flex flex-col sm:flex-row justify-between items-center p-4 border-t border-gray-200">
                <div class="text-sm text-gray-600 mb-2 sm:mb-0">
                    Showing {{ $employees->firstItem() ?? 0 }} to {{ $employees->lastItem() ?? 0 }} 
                    of {{ $employees->total() ?? 0 }} employees
                </div>
                <div>
                    {{ $employees->links() ?? '' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Terminate Modal - Moved OUTSIDE the table -->
    <div id="terminateModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[9999] hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto">
            <form action="" method="POST" id="terminateForm">
                @csrf
                <div class="px-6 py-5 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h5 class="text-red-600 text-xl font-bold">
                            <i class="bi bi-exclamation-triangle mr-2"></i>
                            Terminate Employee
                        </h5>
                        <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors text-2xl leading-none" onclick="closeTerminateModal()">
                            ×
                        </button>
                    </div>
                </div>
                <div class="px-6 py-4">
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                        <div class="flex items-start">
                            <i class="bi bi-info-circle text-red-500 mr-2 mt-0.5"></i>
                            <div>
                                <p class="text-sm text-red-800 font-medium">
                                    <strong>Warning:</strong> You are about to terminate <strong id="employeeNameDisplay"></strong>.
                                </p>
                                <p class="text-xs text-red-600 mt-1">This action will permanently deactivate their account and remove access to the system.</p>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Termination Date <span class="text-red-500">*</span></label>
                            <input type="date" name="termination_date" id="termination_date" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200" 
                                required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Reason for Termination <span class="text-red-500">*</span></label>
                            <select name="termination_reason" id="termination_reason" 
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200" 
                                    required>
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
                            <textarea name="termination_details" id="termination_details" rows="3" 
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200"
                                    placeholder="Provide additional details about the termination..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-5 border-t border-gray-100 flex justify-end gap-3">
                    <button type="button" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-all duration-200" onclick="closeTerminateModal()">
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
@endsection

@push('styles')
<style>
    .badge {
        @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium;
    }
    
    .hover\:bg-blue-50:hover {
        background-color: #eff6ff;
    }
    
    .overflow-x-auto::-webkit-scrollbar {
        height: 6px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
</style>
@endpush

@push('scripts')
<script>
// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded - setting up dropdowns');
    
    // Dropdown functionality
    document.querySelectorAll('.dropdown-trigger').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            e.preventDefault();
            const dropdown = this.nextElementSibling;
            const isHidden = dropdown.classList.contains('hidden');
            
            // Close all other open dropdowns
            document.querySelectorAll('.dropdown-menu').forEach(d => {
                if (d !== dropdown) d.classList.add('hidden');
            });
            
            // Toggle this dropdown
            if (isHidden) {
                dropdown.classList.remove('hidden');
            } else {
                dropdown.classList.add('hidden');
            }
        });
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        document.querySelectorAll('.dropdown-menu').forEach(dropdown => {
            if (!dropdown.classList.contains('hidden') && !dropdown.closest('.relative').contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    });

    // Select All checkbox
    const selectAll = document.getElementById('selectAll');
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            document.querySelectorAll('.employee-checkbox').forEach(cb => {
                cb.checked = this.checked;
            });
        });
    }

    // Confirm before termination - Attach to form
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

// Global functions for modal - Make them truly global
window.openTerminateModal = function(employeeId, employeeName) {
    console.log('Opening modal for:', employeeId, employeeName);
    
    const modal = document.getElementById('terminateModal');
    if (!modal) {
        console.error('Modal not found!');
        alert('Modal not found! Please check the page.');
        return;
    }
    
    // Close all dropdowns first
    document.querySelectorAll('.dropdown-menu').forEach(d => d.classList.add('hidden'));
    
    // Set employee name
    const nameDisplay = document.getElementById('employeeNameDisplay');
    if (nameDisplay) {
        nameDisplay.textContent = employeeName;
    }
    
    // Set form action
    const form = document.getElementById('terminateForm');
    if (form) {
        form.action = 'employees/' + employeeId + '/terminate';
        console.log('Form action set to:', form.action);
    }
    
    // Reset form
    const dateInput = document.getElementById('termination_date');
    const reasonSelect = document.getElementById('termination_reason');
    const detailsTextarea = document.getElementById('termination_details');
    
    if (dateInput) dateInput.value = '';
    if (reasonSelect) reasonSelect.value = '';
    if (detailsTextarea) detailsTextarea.value = '';
    
    // Show modal
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
    
    console.log('Modal should now be visible');
};

window.closeTerminateModal = function() {
    console.log('Closing modal');
    const modal = document.getElementById('terminateModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }
};

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('terminateModal');
    if (modal && !modal.classList.contains('hidden')) {
        const modalContent = modal.querySelector('.bg-white');
        if (modalContent && !modalContent.contains(event.target)) {
            window.closeTerminateModal();
        }
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        // Close dropdowns
        document.querySelectorAll('.dropdown-menu').forEach(d => d.classList.add('hidden'));
        // Close modal
        window.closeTerminateModal();
    }
});

// jQuery for backward compatibility (if jQuery is loaded)
if (typeof $ !== 'undefined') {
    $(document).ready(function() {
        $('#selectAll').change(function() {
            $('.employee-checkbox').prop('checked', $(this).prop('checked'));
        });
    });
}
</script>
@endpush