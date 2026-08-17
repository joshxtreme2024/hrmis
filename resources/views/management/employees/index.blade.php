{{-- resources/views/management/employees/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Employee Management')

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
                        <button class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-1 rounded-lg text-sm flex items-center gap-1 transition-colors" 
                                data-bs-toggle="dropdown">
                            <i class="fas fa-file-export"></i>
                            <span>Export</span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <ul class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-10 hidden" 
                            data-bs-toggle="dropdown">
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
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
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
                                            <div class="font-medium text-gray-900">{{ $employee->display_name }}</div>
                                            <div class="text-sm text-gray-500">{{ $employee->email }}</div>
                                            @if($employee->employment?->employee_id)
                                                <div class="text-xs text-gray-400">ID: {{ $employee->employment->employee_id }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $employee->employment->department->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $employee->employment->position->title ?? 'N/A' }}</td>
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
                                        <button class="p-1.5 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors view-employee" 
                                                data-id="{{ $employee->id }}"
                                                data-bs-toggle="tooltip" title="View Profile">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="p-1.5 text-yellow-600 hover:bg-yellow-100 rounded-lg transition-colors edit-employee" 
                                                data-id="{{ $employee->id }}"
                                                data-bs-toggle="tooltip" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="p-1.5 text-red-600 hover:bg-red-100 rounded-lg transition-colors delete-employee" 
                                                data-id="{{ $employee->id }}"
                                                data-name="{{ $employee->first_name }} {{ $employee->last_name }}"
                                                data-bs-toggle="tooltip" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <div class="relative inline-block">
                                            <button class="p-1.5 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors" 
                                                    data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-10 hidden" 
                                                data-bs-toggle="dropdown">
                                                <li>
                                                    <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors" href="#">
                                                        <i class="fas fa-file-contract mr-2"></i>View Contract
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors" href="#">
                                                        <i class="fas fa-clock mr-2"></i>Attendance
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors" href="#">
                                                        <i class="fas fa-calendar-alt mr-2"></i>Leave History
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors" href="#">
                                                        <i class="fas fa-tasks mr-2"></i>Performance
                                                    </a>
                                                </li>
                                                <li><hr class="my-1 border-gray-200"></li>
                                                <li>
                                                    <a class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors" href="#">
                                                        <i class="fas fa-user-slash mr-2"></i>Terminate
                                                    </a>
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
@endsection

@push('styles')
<style>
    /* Custom styles for status badges */
    .badge {
        @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium;
    }
    
    /* Hover effect for table rows */
    .hover\:bg-blue-50:hover {
        background-color: #eff6ff;
    }
    
    /* Custom scrollbar for table */
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
$(document).ready(function() {
    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();

    // Select All functionality
    $('#selectAll').change(function() {
        $('.employee-checkbox').prop('checked', $(this).prop('checked'));
    });

    // Reset filters
    $('#resetFilters').click(function() {
        $('#searchEmployee').val('');
        $('#filterDepartment').val('');
        $('#filterStatus').val('');
        $('#filterPosition').val('');
        $('#employeeFilterForm').submit();
    });

    // View Employee - using Bootstrap 5 modal
    $('.view-employee').click(function() {
        const id = $(this).data('id');
        const modal = new bootstrap.Modal(document.getElementById('viewEmployeeModal'));
        
        // Load employee data via AJAX
        $.ajax({
            url: `/management/employees/${id}/details`,
            type: 'GET',
            success: function(response) {
                // Populate view modal with data
                modal.show();
            },
            error: function() {
                if (typeof toastr !== 'undefined') {
                    toastr.error('Failed to load employee details');
                }
            }
        });
    });

    // Edit Employee
    $('.edit-employee').click(function() {
        const id = $(this).data('id');
        const modal = new bootstrap.Modal(document.getElementById('editEmployeeModal'));
        
        // Load employee data for editing
        $.ajax({
            url: `/management/employees/${id}/edit`,
            type: 'GET',
            success: function(response) {
                // Populate edit modal with data
                modal.show();
            },
            error: function() {
                if (typeof toastr !== 'undefined') {
                    toastr.error('Failed to load employee data for editing');
                }
            }
        });
    });

    // Delete Employee
    $('.delete-employee').click(function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        $('#deleteEmployeeName').text(name);
        $('#deleteEmployeeId').val(id);
        
        const modal = new bootstrap.Modal(document.getElementById('deleteEmployeeModal'));
        modal.show();
    });

    // Filter form submission
    $('#employeeFilterForm').submit(function(e) {
        e.preventDefault();
        const search = $('#searchEmployee').val();
        const department = $('#filterDepartment').val();
        const status = $('#filterStatus').val();
        const position = $('#filterPosition').val();
        
        let url = '{{ route("employees.index") }}?';
        if (search) url += `search=${search}&`;
        if (department) url += `department=${department}&`;
        if (status) url += `status=${status}&`;
        if (position) url += `position=${position}&`;
        
        window.location.href = url;
    });
});
</script>
@endpush