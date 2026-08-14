@extends('layouts.app')

@section('header-actions')
    <div class="flex items-center space-x-2">
        <a href="{{ route('employee.documents.download', $document) }}" 
           class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-xl transition-all duration-200">
            <i class="bi bi-download mr-2"></i>
            Download
        </a>
        <button onclick="window.print()" 
                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition-all duration-200">
            <i class="bi bi-printer mr-2"></i>
            Print
        </button>
        <a href="{{ route('employee.documents.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-xl transition-all duration-200">
            <i class="bi bi-x-lg mr-2"></i>
            Close
        </a>
    </div>
@endsection

@section('breadcrumbs')
    <li class="flex items-center">
        <i class="bi bi-chevron-right text-gray-300 dark:text-gray-600 mx-2 text-xs"></i>
        <a href="{{ route('employee.documents.index') }}" class="text-gray-800 dark:text-gray-200 font-medium hover:text-blue-600 dark:hover:text-blue-400">Document Management</a>
    </li>
    <li class="flex items-center">
        <i class="bi bi-chevron-right text-gray-300 dark:text-gray-600 mx-2 text-xs"></i>
        <span class="text-gray-800 dark:text-gray-200 font-medium">Preview</span>
    </li>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Document Info Bar -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-xl">
                        <i class="bi bi-file-earmark-text text-2xl text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $document->title }}</h3>
                        <div class="flex flex-wrap items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                            <span>{{ $document->documentType->name ?? 'Unknown' }}</span>
                            <span class="text-gray-300 dark:text-gray-600">|</span>
                            <span>{{ $document->file_name }}</span>
                            <span class="text-gray-300 dark:text-gray-600">|</span>
                            <span>{{ $fileSize }}</span>
                            <span class="text-gray-300 dark:text-gray-600">|</span>
                            <span class="uppercase text-xs font-medium text-gray-400">{{ $extension }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $document->status === 'approved' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200' : 
                           ($document->status === 'pending' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-200' : 
                           ($document->status === 'rejected' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200' : 
                           'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300')) }}">
                        {{ ucfirst($document->status ?? 'draft') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Employee Information -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden mb-6">
        <div class="px-6 py-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
            <h6 class="text-sm font-semibold text-gray-900 dark:text-white flex items-center">
                <i class="bi bi-person text-blue-600 mr-2"></i>
                Employee Information
            </h6>
        </div>
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Employee Name</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                        {{ $employee->semiCompleteName() ?? 'N/A' }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Employee ID</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                        {{ $employee->employment->employee_id ?? 'N/A' }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Department</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                        {{ $employee->employment->department->name ?? 'N/A' }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Position</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                        {{ $employee->employment->position->title ?? 'N/A' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Document Preview -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
            <h6 class="text-sm font-semibold text-gray-900 dark:text-white flex items-center">
                <i class="bi bi-eye text-blue-600 mr-2"></i>
                Document Preview
            </h6>
        </div>
        <div class="p-6">
            @if($previewType === 'image')
                <!-- Image Preview -->
                <div class="flex justify-center items-center bg-gray-100 dark:bg-gray-900 rounded-lg p-4 min-h-[400px]">
                    <img src="data:{{ $mimeType }};base64,{{ base64_encode($fileContent) }}" 
                         alt="{{ $document->title }}"
                         class="max-w-full max-h-[70vh] object-contain rounded-lg shadow-lg">
                </div>

            @elseif($previewType === 'pdf')
                <!-- PDF Preview -->
                <div class="w-full bg-gray-100 dark:bg-gray-900 rounded-lg p-2 min-h-[600px]">
                    <div class="flex items-center justify-between mb-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                <i class="bi bi-file-pdf text-red-500 mr-2"></i>
                                PDF Document ({{ $fileSizeFormatted }})
                            </p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('employee.documents.download', $document) }}" 
                            class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg">
                                <i class="bi bi-download mr-1"></i> Download
                            </a>
                            <a href="{{ route('employee.documents.stream', $document) }}" 
                            target="_blank"
                            class="inline-flex items-center px-3 py-1.5 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-sm rounded-lg">
                                <i class="bi bi-box-arrow-up-right mr-1"></i> Full Screen
                            </a>
                        </div>
                    </div>
                    <div class="w-full h-[75vh] rounded-lg overflow-hidden bg-white dark:bg-gray-800">
                        <div id="pdfLoading" class="flex items-center justify-center h-full">
                            <div class="text-center">
                                <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mb-4"></div>
                                <p class="text-gray-500 dark:text-gray-400">Loading PDF...</p>
                            </div>
                        </div>
                        <iframe src="{{ route('employee.documents.stream', $document) }}" 
                                class="w-full h-full rounded-lg"
                                frameborder="0"
                                allowfullscreen
                                onload="document.getElementById('pdfLoading').style.display='none'">
                        </iframe>
                    </div>
                </div>

            @elseif($previewType === 'text')
                <!-- Text Preview -->
                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4 font-mono text-sm overflow-auto max-h-[70vh]">
                    <pre class="whitespace-pre-wrap text-gray-800 dark:text-gray-200">{{ $fileContent }}</pre>
                </div>

            @elseif($previewType === 'word')
                <!-- Word Document Preview -->
                <div class="text-center py-12">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-blue-50 dark:bg-blue-900/20 rounded-full mb-4">
                        <i class="bi bi-file-word text-4xl text-blue-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Word Document Preview</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-4">
                        This document cannot be previewed directly. Please download to view its contents.
                    </p>
                    <a href="{{ route('employee.documents.download', $document) }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200">
                        <i class="bi bi-download mr-2"></i> Download Document
                    </a>
                </div>

            @elseif($previewType === 'excel')
                <!-- Excel Document Preview -->
                <div class="text-center py-12">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-green-50 dark:bg-green-900/20 rounded-full mb-4">
                        <i class="bi bi-file-excel text-4xl text-green-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Excel Document Preview</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-4">
                        This document cannot be previewed directly. Please download to view its contents.
                    </p>
                    <a href="{{ route('employee.documents.download', $document) }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200">
                        <i class="bi bi-download mr-2"></i> Download Document
                    </a>
                </div>

            @else
                <!-- Default - Show info and download option -->
                <div class="text-center py-12">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-50 dark:bg-gray-900 rounded-full mb-4">
                        <i class="bi bi-file-earmark text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Preview Not Available</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-4">
                        This file type (.{{ $extension }}) cannot be previewed directly.
                    </p>
                    <a href="{{ route('employee.documents.download', $document) }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200">
                        <i class="bi bi-download mr-2"></i> Download Document
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Document Metadata -->
    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-4">
            <p class="text-xs text-gray-500 dark:text-gray-400">Document Type</p>
            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $document->documentType->name ?? 'Unknown' }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-4">
            <p class="text-xs text-gray-500 dark:text-gray-400">Date Uploaded</p>
            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $document->created_at->format('M d, Y H:i') }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-4">
            <p class="text-xs text-gray-500 dark:text-gray-400">File Size</p>
            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $fileSize }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-4">
            <p class="text-xs text-gray-500 dark:text-gray-400">Reference</p>
            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $document->reference_number ?? 'N/A' }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-4">
            <p class="text-xs text-gray-500 dark:text-gray-400">Approved By</p>
            <p class="text-sm font-medium text-gray-900 dark:text-white">
                {{ strtoupper($document->approvedBy->role ?? 'Not yet approved') }}
            </p>
        </div>
    </div>

    <!-- Approval Actions (if pending) -->
    @if($document->status === 'pending')
        <div class="mt-6 bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                <h6 class="text-sm font-semibold text-gray-900 dark:text-white flex items-center">
                    <i class="bi bi-check2-circle text-blue-600 mr-2"></i>
                    Approval Actions
                </h6>
            </div>
            <div class="px-6 py-4">
                <div class="flex flex-wrap gap-3">
                    <form action="{{ route('employee.documents.approve', $document) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors duration-200">
                            <i class="bi bi-check-circle mr-2"></i> Approve Document
                        </button>
                    </form>
                    <button onclick="openRejectModal({{ $document->id }})" 
                            class="inline-flex items-center px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors duration-200">
                        <i class="bi bi-x-circle mr-2"></i> Reject Document
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Reject Modal -->
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
                    <button onclick="closeRejectModal()" 
                            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg transition-colors duration-200">
                        Cancel
                    </button>
                    <button onclick="confirmReject({{ $document->id }})" 
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
function openRejectModal(id) {
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}

function confirmReject(id) {
    const reason = document.getElementById('rejectionReason').value;
    if (!reason.trim()) {
        alert('Please provide a rejection reason.');
        return;
    }
    
    // Create and submit form
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("employee.documents.reject", $document) }}';
    
    const csrf = document.createElement('input');
    csrf.type = 'hidden';
    csrf.name = '_token';
    csrf.value = '{{ csrf_token() }}';
    form.appendChild(csrf);
    
    const reasonInput = document.createElement('input');
    reasonInput.type = 'hidden';
    reasonInput.name = 'rejection_reason';
    reasonInput.value = reason;
    form.appendChild(reasonInput);
    
    document.body.appendChild(form);
    form.submit();
}

// Close modal on backdrop click
document.addEventListener('DOMContentLoaded', function() {
    const backdrop = document.querySelector('#rejectModal .fixed.inset-0');
    if (backdrop) {
        backdrop.addEventListener('click', closeRejectModal);
    }
});
</script>
@endpush
@endsection