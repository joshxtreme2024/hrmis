@extends('layouts.app')

@section('header-actions')
    <button onclick="window.history.back()" 
            class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 hover:border-gray-400 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-xl transition-all duration-200">
        <i class="bi bi-arrow-left mr-2"></i>
        Back
    </button>
@endsection

@section('breadcrumbs')
    <li class="flex items-center">
        <i class="bi bi-chevron-right text-gray-300 dark:text-gray-600 mx-2 text-xs"></i>
        @if(isset($employee))
            <a href="{{ route('myprofile.show', $employee) }}" class="text-gray-800 dark:text-gray-200 font-medium hover:text-blue-600 dark:hover:text-blue-400">PDS</a>
        @else
            <span class="text-gray-800 dark:text-gray-200 font-medium">PDS</span>
        @endif
    </li>
    <li class="flex items-center">
        <i class="bi bi-chevron-right text-gray-300 dark:text-gray-600 mx-2 text-xs"></i>
        <span class="text-gray-800 dark:text-gray-200 font-medium hover:text-blue-600 dark:hover:text-blue-400">My 201 File</span>
    </li>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header Card -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl shadow-lg overflow-hidden mb-8">
        <div class="px-6 py-4 sm:px-8 sm:py-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex items-center space-x-3">
                    <div class="bg-white/20 p-3 rounded-xl backdrop-blur-sm">
                        <i class="bi bi-folder2-open text-2xl text-white"></i>
                    </div>
                    <div>
                        <h4 class="text-xl font-bold text-white">
                            201 File - 
                            @if(isset($employee))
                                {{ $employee->first_name ?? '' }} {{ $employee->last_name ?? '' }}
                            @else
                                {{ auth()->user()->name ?? 'User' }}
                            @endif
                        </h4>
                        <p class="text-sm text-blue-100">Employee Document Management System</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <button onclick="window.print()" class="inline-flex items-center px-3 py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg transition-all duration-200 text-sm font-medium">
                        <i class="bi bi-printer mr-2"></i> Print
                    </button>
                    @if(isset($employee))
                        <a href="{{ route('mydocuments.create', $employee) }}" class="inline-flex items-center px-4 py-2 bg-white text-blue-700 hover:bg-blue-50 rounded-lg transition-all duration-200 text-sm font-medium shadow-sm">
                            <i class="bi bi-upload mr-2"></i> Upload Document
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if(!isset($employee))
        <!-- No Employee Profile Found -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
            <div class="p-12 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-amber-50 dark:bg-amber-900/20 rounded-full mb-4">
                    <i class="bi bi-exclamation-triangle text-4xl text-amber-500"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Employee Profile is not complete.</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-4">
                    Please complete your Personal Data Sheet (PDS) profile first before uploading documents.
                </p>
                <a href="{{ route('myprofile.show') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200">
                    <i class="bi bi-person-plus mr-2"></i> Complete Profile
                </a>
            </div>
        </div>
    @else
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <!-- Completeness Card -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-5 text-white transform hover:scale-105 transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-100">201 File Completeness</p>
                        <p class="text-3xl font-bold mt-1">{{ $completeness['percentage'] ?? 0 }}%</p>
                        <p class="text-xs text-blue-200 mt-1">{{ $completeness['completed'] ?? 0 }} of {{ $completeness['total_required'] ?? 0 }} required</p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-xl">
                        <i class="bi bi-check2-circle text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Documents Card -->
            <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl shadow-lg p-5 text-white transform hover:scale-105 transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-emerald-100">Total Documents</p>
                        <p class="text-3xl font-bold mt-1">{{ $totalDocuments ?? 0 }}</p>
                        <p class="text-xs text-emerald-200 mt-1">Approved: {{ $approvedDocuments ?? 0 }}</p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-xl">
                        <i class="bi bi-file-earmark-text text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Pending Review Card -->
            <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl shadow-lg p-5 text-white transform hover:scale-105 transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-amber-100">Pending Review</p>
                        <p class="text-3xl font-bold mt-1">{{ $pendingDocuments ?? 0 }}</p>
                        <p class="text-xs text-amber-200 mt-1">Need approval</p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-xl">
                        <i class="bi bi-clock-history text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Expiring Documents Card -->
            <div class="bg-gradient-to-br from-rose-500 to-red-600 rounded-xl shadow-lg p-5 text-white transform hover:scale-105 transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-rose-100">Expiring Documents</p>
                        <p class="text-3xl font-bold mt-1">{{ $expiringDocuments ?? 0 }}</p>
                        <p class="text-xs text-rose-200 mt-1">Expiring within 30 days</p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-xl">
                        <i class="bi bi-exclamation-triangle text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Required Documents Checklist -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden mb-8">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h6 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                    <i class="bi bi-list-check text-blue-600 mr-2"></i>
                    Required Documents Checklist
                </h6>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    @if(isset($completeness['items']) && $completeness['items']->count() > 0)
                        @foreach($completeness['items'] as $code => $item)
                            <div class="flex items-center p-2 rounded-lg {{ $item['is_completed'] ? 'bg-green-50 dark:bg-green-900/20' : 'bg-red-50 dark:bg-red-900/20' }}">
                                <span class="flex-shrink-0 w-6 h-6 rounded-full {{ $item['is_completed'] ? 'bg-green-500' : 'bg-red-500' }} flex items-center justify-center mr-3">
                                    <i class="bi {{ $item['is_completed'] ? 'bi-check' : 'bi-x' }} text-white text-sm"></i>
                                </span>
                                <span class="text-sm font-medium {{ $item['is_completed'] ? 'text-green-700 dark:text-green-300' : 'text-red-700 dark:text-red-300' }}">
                                    {{ $item['name'] }}
                                    @if(isset($item['version_count']) && $item['version_count'] > 1)
                                        <span class="text-xs text-gray-500 dark:text-gray-400 ml-1">
                                            ({{ $item['version_count'] }} versions)
                                        </span>
                                    @endif
                                </span>
                            </div>
                        @endforeach
                    @else
                        <div class="col-span-full text-center py-4 text-gray-500 dark:text-gray-400">
                            <i class="bi bi-inbox text-3xl block mb-2"></i>
                            <p>No required document types defined.</p>
                        </div>
                    @endif
                </div>

                <!-- Completeness Alert -->
                @if(isset($completeness['is_complete']) && !$completeness['is_complete'])
                    <div class="mt-4 bg-amber-50 dark:bg-amber-900/20 border-l-4 border-amber-500 rounded-lg p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="bi bi-exclamation-triangle text-amber-500 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-semibold text-amber-800 dark:text-amber-300">Incomplete 201 File!</h3>
                                <p class="text-sm text-amber-700 dark:text-amber-400">
                                    Please upload all required documents. Missing: 
                                    {{ ($completeness['total_required'] ?? 0) - ($completeness['completed'] ?? 0) }} document(s)
                                </p>
                            </div>
                        </div>
                    </div>
                @elseif(isset($completeness['is_complete']) && $completeness['is_complete'])
                    <div class="mt-4 bg-emerald-50 dark:bg-emerald-900/20 border-l-4 border-emerald-500 rounded-lg p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="bi bi-check-circle-fill text-emerald-500 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-semibold text-emerald-800 dark:text-emerald-300">Complete!</h3>
                                <p class="text-sm text-emerald-700 dark:text-emerald-400">
                                    All required documents are uploaded.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Documents by Category -->
        @if(isset($documents) && $documents->count() > 0)
            @foreach($documents as $category => $docs)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden mb-6">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                        <h6 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <i class="bi bi-folder text-blue-600 mr-2"></i>
                            {{ $category }}
                            <span class="ml-3 text-xs bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200 px-2 py-1 rounded-full">
                                {{ $docs->count() }} document(s)
                            </span>
                        </h6>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800/50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Document Type</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Title / Version</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Expiry</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($docs as $doc)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors duration-150">
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200 mr-2">
                                                    {{ $doc->documentType->code ?? 'N/A' }}
                                                </span>
                                                <!-- <span class="text-sm text-gray-900 dark:text-gray-200">
                                                    {{ $doc->documentType->name ?? 'Unknown' }}
                                                </span> -->
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
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                        <i class="bi bi-files text-xs mr-1"></i> Copy
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
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                            {{ $doc->document_date ? $doc->document_date->format('Y-m-d') : '-' }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            @if($doc->expiry_date)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                    {{ $doc->isExpired() ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200' : 
                                                       ($doc->isExpiringSoon() ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-200' : 
                                                       'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200') }}">
                                                    {{ $doc->expiry_date->format('Y-m-d') }}
                                                    @if($doc->isExpired())
                                                        <i class="bi bi-exclamation-triangle ml-1"></i>
                                                    @elseif($doc->isExpiringSoon())
                                                        <i class="bi bi-clock ml-1"></i>
                                                    @endif
                                                </span>
                                            @else
                                                <span class="text-sm text-gray-400 dark:text-gray-500">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
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
                                                @if($doc->file_path)
                                                    <a href="{{ route('mydocuments.download', $doc) }}" 
                                                       class="inline-flex items-center p-2 bg-green-50 hover:bg-green-100 text-green-600 rounded-lg transition-colors duration-200" 
                                                       title="Download">
                                                        <i class="bi bi-download"></i>
                                                    </a>
                                                @endif
                                                <a href="{{ route('mydocuments.edit', ['employee' => $employee, 'document' => $doc]) }}" 
                                                   class="inline-flex items-center p-2 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg transition-colors duration-200" 
                                                   title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <button onclick="deleteDocument({{ $doc->id }})" 
                                                        class="inline-flex items-center p-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition-colors duration-200" 
                                                        title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                                <!-- @if($doc->canCreateNewVersion ?? false)
                                                    <a href="{{ route('mydocuments.create', ['employee' => $employee, 'type' => $doc->document_type_id]) }}" 
                                                       class="inline-flex items-center p-2 bg-amber-50 hover:bg-amber-100 text-amber-600 rounded-lg transition-colors duration-200" 
                                                       title="Upload New Version">
                                                        <i class="bi bi-plus-circle"></i>
                                                    </a>
                                                @endif -->
                                            </div>
                                            <form id="delete-form-{{ $doc->id }}" 
                                                  action="{{ route('mydocuments.destroy', ['employee' => $employee, 'document' => $doc]) }}" 
                                                  method="POST" class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        @else
            <!-- Empty State -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
                <div class="p-12 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-50 dark:bg-blue-900/20 rounded-full mb-4">
                        <i class="bi bi-inbox text-4xl text-blue-500"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No Documents Found</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-4">
                        No documents found for this employee. Click the "Upload Document" button to add documents to the 201 file.
                    </p>
                    <a href="{{ route('mydocuments.create', $employee) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200">
                        <i class="bi bi-upload mr-2"></i> Upload First Document
                    </a>
                </div>
            </div>
        @endif
    @endif
</div>

@push('scripts')
<script>
function deleteDocument(id) {
    if (confirm('Are you sure you want to delete this document? This action cannot be undone.')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endpush
@endsection