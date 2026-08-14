@extends('layouts.app')

@section('header-actions')
    <div class="flex items-center space-x-2">
        <button onclick="window.print()" 
                class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 hover:border-gray-400 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-xl transition-all duration-200">
            <i class="bi bi-printer mr-2"></i>
            Print
        </button>
        <a href="{{ route('employee.documents.export') }}" 
           class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-xl transition-all duration-200">
            <i class="bi bi-download mr-2"></i>
            Export Report
        </a>
    </div>
@endsection

@section('breadcrumbs')
    <li class="flex items-center">
        <i class="bi bi-chevron-right text-gray-300 dark:text-gray-600 mx-2 text-xs"></i>
        <span class="text-gray-800 dark:text-gray-200 font-medium hover:text-blue-600 dark:hover:text-blue-400">Document Management</span>
    </li>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header Card -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-700 rounded-xl shadow-lg overflow-hidden mb-8">
        <div class="px-6 py-4 sm:px-8 sm:py-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex items-center space-x-3">
                    <div class="bg-white/20 p-3 rounded-xl backdrop-blur-sm">
                        <i class="bi bi-folder2-open text-2xl text-white"></i>
                    </div>
                    <div>
                        <h4 class="text-xl font-bold text-white">
                            Employee Document Management
                        </h4>
                        <p class="text-sm text-indigo-100">Manage all employee 201 files and documents</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('employee.documents.report') }}" 
                       class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg transition-all duration-200 text-sm font-medium">
                        <i class="bi bi-file-earmark-text mr-2"></i> Reports
                    </a>
                    <a href="{{ route('employee.documents.settings') }}"
                        target="blank" 
                       class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg transition-all duration-200 text-sm font-medium">
                        <i class="bi bi-gear mr-2"></i> Settings
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-5 text-white transform hover:scale-105 transition-all duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-100">Total Documents</p>
                    <p class="text-3xl font-bold mt-1">{{ $statistics['total_documents'] ?? 0 }}</p>
                    <p class="text-xs text-blue-200 mt-1">Across all employees</p>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <i class="bi bi-file-earmark-text text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl shadow-lg p-5 text-white transform hover:scale-105 transition-all duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-amber-100">Pending Review</p>
                    <p class="text-3xl font-bold mt-1">{{ $statistics['pending_documents'] ?? 0 }}</p>
                    <p class="text-xs text-amber-200 mt-1">Need approval</p>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <i class="bi bi-clock-history text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-red-500 to-rose-600 rounded-xl shadow-lg p-5 text-white transform hover:scale-105 transition-all duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-red-100">Rejected</p>
                    <p class="text-3xl font-bold mt-1">{{ $statistics['rejected_documents'] ?? 0 }}</p>
                    <p class="text-xs text-red-200 mt-1">Need attention</p>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <i class="bi bi-x-circle text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl shadow-lg p-5 text-white transform hover:scale-105 transition-all duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-purple-100">Expiring Soon</p>
                    <p class="text-3xl font-bold mt-1">{{ $statistics['expiring_documents'] ?? 0 }}</p>
                    <p class="text-xs text-purple-200 mt-1">Within 30 days</p>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <i class="bi bi-exclamation-triangle text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl shadow-lg p-5 text-white transform hover:scale-105 transition-all duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-emerald-100">Employees</p>
                    <p class="text-3xl font-bold mt-1">{{ $statistics['total_employees'] ?? 0 }}</p>
                    <p class="text-xs text-emerald-200 mt-1">With 201 files</p>
                </div>
                <div class="bg-white/20 p-3 rounded-xl">
                    <i class="bi bi-people text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
            <h6 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                <i class="bi bi-funnel text-blue-600 mr-2"></i>
                Filter Documents
            </h6>
        </div>
        <div class="p-6">
            <form action="{{ route('employee.documents.index') }}" method="GET" id="filterForm">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500" 
                               placeholder="Name, document, title...">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        <select name="status" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category</label>
                        <select name="category" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">All Categories</option>
                            @foreach($categories ?? [] as $category)
                                <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                    {{ $category }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Department</label>
                        <select name="department" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">All Departments</option>
                            @foreach($departments ?? [] as $dept)
                                <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>
                                    {{ $dept }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end space-x-2">
                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200">
                            <i class="bi bi-search mr-2"></i> Filter
                        </button>
                        <a href="{{ route('employee.documents.index') }}" class="w-full px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg transition-colors duration-200 text-center">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Documents Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center space-x-4">
                <h6 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                    <i class="bi bi-table text-blue-600 mr-2"></i>
                    Documents List
                </h6>
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $documents->total() ?? 0 }} document(s) found
                </span>
            </div>
            <div class="flex items-center space-x-2">
                <button id="toggleBulkBtn" class="inline-flex items-center px-3 py-1.5 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg transition-colors duration-200 text-sm">
                    <i class="bi bi-check2-square mr-1"></i> Bulk Actions
                </button>
                <span class="text-sm text-gray-500 dark:text-gray-400">|</span>
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    <i class="bi bi-clock"></i> Last updated: {{ now()->format('Y-m-d H:i') }}
                </span>
            </div>
        </div>

        <!-- Bulk Actions Bar -->
        <div id="bulkActions" class="hidden px-6 py-3 bg-blue-50 dark:bg-blue-900/20 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        <span id="selectedCount">0</span> documents selected
                    </span>
                    <button type="button" id="bulkApproveBtn" 
                            class="inline-flex items-center px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg transition-colors duration-200 text-sm">
                        <i class="bi bi-check-circle mr-1"></i> Approve
                    </button>
                    <button type="button" id="bulkRejectBtn" 
                            class="inline-flex items-center px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors duration-200 text-sm">
                        <i class="bi bi-x-circle mr-1"></i> Reject
                    </button>
                    <button type="button" id="bulkDeleteBtn" 
                            class="inline-flex items-center px-3 py-1.5 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors duration-200 text-sm">
                        <i class="bi bi-trash mr-1"></i> Delete
                    </button>
                </div>
                <button id="closeBulkBtn" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800/50">
                    <tr>
                        <th class="px-4 py-3 text-left">
                            <input type="checkbox" id="selectAll" 
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Employee</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Document Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Title / Version</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Department</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($documents ?? [] as $doc)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors duration-150 document-row" data-status="{{ $doc->status }}">
                            <td class="px-4 py-3 whitespace-nowrap">
                                <input type="checkbox" class="document-checkbox w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" 
                                       value="{{ $doc->id }}">
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-9 w-9 rounded-lg bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center text-white text-sm font-semibold">
                                        {{ strtoupper(substr($doc->employee->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr($doc->employee->last_name ?? 'N', 0, 1)) }}
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $doc->employee->semiCompleteName() ?? '' }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $doc->employee->employment->employee_id ?? '' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200">
                                        {{ $doc->documentType->code ?? 'N/A' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm text-gray-900 dark:text-gray-200 font-medium">
                                    {{ $doc->title }}
                                </div>
                                <div class="flex flex-wrap items-center gap-1 mt-1">
                                    @if($doc->version_number)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-200">
                                            <i class="bi bi-tag text-xs mr-1"></i> v{{ $doc->version_number }}
                                        </span>
                                    @endif
                                    @if($doc->document_year)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                            <i class="bi bi-calendar text-xs mr-1"></i> {{ $doc->document_year }}
                                        </span>
                                    @endif
                                    @if($doc->is_original)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-200">
                                            <i class="bi bi-check-circle text-xs mr-1"></i> Original
                                        </span>
                                    @endif
                                    @if($doc->is_confidential)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-200">
                                            <i class="bi bi-lock text-xs mr-1"></i> Confidential
                                        </span>
                                    @endif
                                    @if($doc->is_latest_version ?? false)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200">
                                            <i class="bi bi-star-fill text-xs mr-1"></i> Latest
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $doc->employee->employment->department->code ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                <div>{{ $doc->document_date ? $doc->document_date->format('Y-m-d') : '-' }}</div>
                                @if($doc->expiry_date)
                                    <div class="text-xs {{ $doc->isExpired() ? 'text-red-500' : ($doc->isExpiringSoon() ? 'text-amber-500' : 'text-green-500') }}">
                                        Expires: {{ $doc->expiry_date->format('Y-m-d') }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="status-badge inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $doc->status === 'approved' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200' : 
                                       ($doc->status === 'pending' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-200' : 
                                       ($doc->status === 'rejected' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200' : 
                                       'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300')) }}">
                                    {{ ucfirst($doc->status ?? 'draft') }}
                                </span>
                                @if($doc->status === 'rejected' && $doc->remarks)
                                    <div class="mt-1 text-xs text-red-600 dark:text-red-400">
                                        <i class="bi bi-info-circle"></i> {{ Str::limit($doc->remarks, 30) }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center space-x-1">
                                    <a href="{{ route('employees.show', $doc->employee) }}" 
                                       class="inline-flex items-center p-2 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg transition-colors duration-200" 
                                       title="View Employee">
                                        <i class="bi bi-person"></i>
                                    </a>
                                    
                                    <a href="{{ route('employee.documents.preview', $doc) }}" 
                                       class="inline-flex items-center p-2 bg-purple-50 hover:bg-purple-100 text-purple-600 rounded-lg transition-colors duration-200" 
                                       title="Preview">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <a href="{{ route('employee.documents.download', $doc) }}" 
                                       class="inline-flex items-center p-2 bg-green-50 hover:bg-green-100 text-green-600 rounded-lg transition-colors duration-200" 
                                       title="Download">
                                        <i class="bi bi-download"></i>
                                    </a>

                                    @if(($doc->status ?? '') === 'pending')
                                        <button onclick="quickApprove({{ $doc->id }})" 
                                                class="inline-flex items-center p-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-600 rounded-lg transition-colors duration-200" 
                                                title="Approve">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                        <button onclick="quickReject({{ $doc->id }})" 
                                                class="inline-flex items-center p-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition-colors duration-200" 
                                                title="Reject">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    @endif

                                    <button onclick="deleteDocument({{ $doc->id }})" 
                                            class="inline-flex items-center p-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition-colors duration-200" 
                                            title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>

                                <!-- Hidden forms -->
                                <form id="approve-form-{{ $doc->id }}" 
                                      action="{{ route('employee.documents.approve', $doc) }}" 
                                      method="POST" class="hidden">
                                    @csrf
                                </form>
                                <form id="reject-form-{{ $doc->id }}" 
                                      action="{{ route('employee.documents.reject', $doc) }}" 
                                      method="POST" class="hidden">
                                    @csrf
                                    <input type="hidden" name="rejection_reason" id="rejection-reason-{{ $doc->id }}" value="">
                                </form>
                                <form id="delete-form-{{ $doc->id }}" 
                                      action="{{ route('employee.documents.destroy', $doc) }}" 
                                      method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full mb-4">
                                    <i class="bi bi-inbox text-3xl text-gray-400"></i>
                                </div>
                                <p class="text-gray-500 dark:text-gray-400">No documents found</p>
                                <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Try adjusting your filters</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($documents) && $documents->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $documents->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Bulk Action Forms (Hidden) -->
<form id="bulkApproveForm" action="{{ route('employee.documents.bulk-approve') }}" method="POST" class="hidden">
    @csrf
    <input type="hidden" name="document_ids" id="bulkApproveIds" value="">
</form>

<form id="bulkRejectForm" action="{{ route('employee.documents.bulk-reject') }}" method="POST" class="hidden">
    @csrf
    <input type="hidden" name="document_ids" id="bulkRejectIds" value="">
    <input type="hidden" name="rejection_reason" id="bulkRejectReason" value="">
</form>

<form id="bulkDeleteForm" action="{{ route('employee.documents.bulk-delete') }}" method="POST" class="hidden">
    @csrf
    <input type="hidden" name="document_ids" id="bulkDeleteIds" value="">
</form>

<!-- Quick Action Modals -->
<div id="approveModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-md w-full p-6">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-emerald-100 dark:bg-emerald-900/20">
                    <i class="bi bi-check-circle text-emerald-600 text-2xl"></i>
                </div>
                <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-white">Approve Document</h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Are you sure you want to approve this document? This action will make it visible to the employee.
                </p>
                <div class="mt-4 flex justify-center space-x-3">
                    <button onclick="closeModal('approveModal')" 
                            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg transition-colors duration-200">
                        Cancel
                    </button>
                    <button id="approveConfirmBtn" 
                            class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg transition-colors duration-200">
                        Approve
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="rejectModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-md w-full p-6">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/20">
                    <i class="bi bi-x-circle text-red-600 text-2xl"></i>
                </div>
                <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-white">Reject Document</h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Please provide a reason for rejecting this document.
                </p>
                <div class="mt-4">
                    <textarea id="rejectionReason" rows="3" 
                              class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-red-500 focus:ring-red-500" 
                              placeholder="Enter rejection reason..."></textarea>
                </div>
                <div class="mt-4 flex justify-center space-x-3">
                    <button onclick="closeModal('rejectModal')" 
                            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg transition-colors duration-200">
                        Cancel
                    </button>
                    <button id="rejectConfirmBtn" 
                            class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors duration-200">
                        Reject
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ========================
    // DOM Elements
    // ========================
    const selectAllCheckbox = document.getElementById('selectAll');
    const documentCheckboxes = document.querySelectorAll('.document-checkbox');
    const selectedCountSpan = document.getElementById('selectedCount');
    const bulkActionsDiv = document.getElementById('bulkActions');
    const toggleBulkBtn = document.getElementById('toggleBulkBtn');
    const closeBulkBtn = document.getElementById('closeBulkBtn');
    
    const bulkApproveBtn = document.getElementById('bulkApproveBtn');
    const bulkRejectBtn = document.getElementById('bulkRejectBtn');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    
    // ========================
    // Helper Functions
    // ========================
    function getSelectedIds() {
        return Array.from(document.querySelectorAll('.document-checkbox:checked'))
            .map(cb => cb.value);
    }
    
    function getSelectedCount() {
        return document.querySelectorAll('.document-checkbox:checked').length;
    }
    
    function updateSelectedCount() {
        const count = getSelectedCount();
        if (selectedCountSpan) {
            selectedCountSpan.textContent = count;
        }
        return count;
    }
    
    function hasNonPendingSelected() {
        const selectedCheckboxes = document.querySelectorAll('.document-checkbox:checked');
        return Array.from(selectedCheckboxes).some(cb => {
            const row = cb.closest('tr');
            if (!row) return false;
            const statusBadge = row.querySelector('.status-badge');
            if (!statusBadge) return false;
            return !statusBadge.textContent.trim().toLowerCase().includes('pending');
        });
    }
    
    // ========================
    // Toggle Bulk Actions
    // ========================
    function toggleBulkActions(show) {
        if (bulkActionsDiv) {
            if (show === undefined) {
                bulkActionsDiv.classList.toggle('hidden');
            } else if (show) {
                bulkActionsDiv.classList.remove('hidden');
            } else {
                bulkActionsDiv.classList.add('hidden');
            }
        }
    }
    
    if (toggleBulkBtn) {
        toggleBulkBtn.addEventListener('click', function(e) {
            e.preventDefault();
            toggleBulkActions();
            // Reset checkboxes when showing
            if (!bulkActionsDiv.classList.contains('hidden')) {
                selectAllCheckbox.checked = false;
                documentCheckboxes.forEach(cb => cb.checked = false);
                updateSelectedCount();
            }
        });
    }
    
    if (closeBulkBtn) {
        closeBulkBtn.addEventListener('click', function(e) {
            e.preventDefault();
            toggleBulkActions(false);
        });
    }
    
    // ========================
    // Select All
    // ========================
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            documentCheckboxes.forEach(cb => {
                cb.checked = selectAllCheckbox.checked;
            });
            updateSelectedCount();
        });
    }
    
    // ========================
    // Individual Checkboxes
    // ========================
    documentCheckboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            updateSelectedCount();
            // Update select all state
            if (selectAllCheckbox) {
                const checked = document.querySelectorAll('.document-checkbox:checked');
                selectAllCheckbox.checked = checked.length === documentCheckboxes.length;
            }
        });
    });
    
    // ========================
    // Bulk Approve
    // ========================
    if (bulkApproveBtn) {
        bulkApproveBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const selectedIds = getSelectedIds();
            
            if (selectedIds.length === 0) {
                alert('Please select at least one document.');
                return;
            }
            
            // Check if any selected is not pending
            if (hasNonPendingSelected()) {
                if (!confirm('Some selected documents are not pending. Only pending documents can be approved. Continue?')) {
                    return;
                }
            }
            
            if (confirm(`Approve ${selectedIds.length} selected document(s)?`)) {
                document.getElementById('bulkApproveIds').value = JSON.stringify(selectedIds);
                document.getElementById('bulkApproveForm').submit();
            }
        });
    }
    
    // ========================
    // Bulk Reject
    // ========================
    if (bulkRejectBtn) {
        bulkRejectBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const selectedIds = getSelectedIds();
            
            if (selectedIds.length === 0) {
                alert('Please select at least one document.');
                return;
            }
            
            const reason = prompt(`Enter rejection reason for ${selectedIds.length} document(s):`);
            if (reason === null) return;
            if (!reason.trim()) {
                alert('Rejection reason is required.');
                return;
            }
            
            if (confirm(`Reject ${selectedIds.length} selected document(s)?`)) {
                document.getElementById('bulkRejectIds').value = JSON.stringify(selectedIds);
                document.getElementById('bulkRejectReason').value = reason;
                document.getElementById('bulkRejectForm').submit();
            }
        });
    }
    
    // ========================
    // Bulk Delete
    // ========================
    if (bulkDeleteBtn) {
        bulkDeleteBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const selectedIds = getSelectedIds();
            
            if (selectedIds.length === 0) {
                alert('Please select at least one document.');
                return;
            }
            
            if (confirm(`Delete ${selectedIds.length} selected document(s)? This action cannot be undone.`)) {
                document.getElementById('bulkDeleteIds').value = JSON.stringify(selectedIds);
                document.getElementById('bulkDeleteForm').submit();
            }
        });
    }
    
    // ========================
    // Quick Approve / Reject / Delete
    // ========================
    window.currentDocumentId = null;
    
    window.quickApprove = function(id) {
        if (confirm('Are you sure you want to approve this document?')) {
            document.getElementById('approve-form-' + id).submit();
        }
    };
    
    window.quickReject = function(id) {
        const reason = prompt('Please enter the rejection reason:');
        if (reason !== null && reason.trim() !== '') {
            document.getElementById('rejection-reason-' + id).value = reason;
            document.getElementById('reject-form-' + id).submit();
        } else if (reason !== null) {
            alert('Rejection reason is required.');
        }
    };
    
    window.deleteDocument = function(id) {
        if (confirm('Are you sure you want to delete this document? This action cannot be undone.')) {
            document.getElementById('delete-form-' + id).submit();
        }
    };
    
    // ========================
    // Modal Functions
    // ========================
    window.closeModal = function(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    };
    
    // Approve Modal
    const approveConfirmBtn = document.getElementById('approveConfirmBtn');
    if (approveConfirmBtn) {
        approveConfirmBtn.addEventListener('click', function() {
            if (window.currentDocumentId) {
                document.getElementById('approve-form-' + window.currentDocumentId).submit();
            }
        });
    }
    
    // Reject Modal
    const rejectConfirmBtn = document.getElementById('rejectConfirmBtn');
    if (rejectConfirmBtn) {
        rejectConfirmBtn.addEventListener('click', function() {
            const reason = document.getElementById('rejectionReason').value;
            if (!reason.trim()) {
                alert('Please provide a rejection reason.');
                return;
            }
            if (window.currentDocumentId) {
                document.getElementById('rejection-reason-' + window.currentDocumentId).value = reason;
                document.getElementById('reject-form-' + window.currentDocumentId).submit();
            }
        });
    }
    
    // Close modals on backdrop click
    document.querySelectorAll('.fixed.inset-0.bg-gray-500').forEach(backdrop => {
        backdrop.addEventListener('click', function() {
            const modal = this.closest('.fixed');
            if (modal) {
                modal.classList.add('hidden');
            }
        });
    });
    
    // ========================
    // Auto-submit filter on change
    // ========================
    document.querySelectorAll('#filterForm select').forEach(select => {
        select.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    });
});
</script>
@endpush
@endsection