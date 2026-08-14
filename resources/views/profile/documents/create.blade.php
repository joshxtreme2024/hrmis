@extends('layouts.app')

@section('breadcrumbs')
    <li class="flex items-center">
        <i class="bi bi-chevron-right text-gray-300 dark:text-gray-600 mx-2 text-xs"></i>
        <a href="{{ route('myprofile.show', $employee) }}" class="text-gray-800 dark:text-gray-200 font-medium hover:text-blue-600 dark:hover:text-blue-400">PDS</a>
    </li>
    <li class="flex items-center">
        <i class="bi bi-chevron-right text-gray-300 dark:text-gray-600 mx-2 text-xs"></i>
        <a href="{{ route('mydocuments.index', $employee) }}" class="text-gray-800 dark:text-gray-200 font-medium hover:text-blue-600 dark:hover:text-blue-400">My Documents</a>
    </li>
    <li class="flex items-center">
        <i class="bi bi-chevron-right text-gray-300 dark:text-gray-600 mx-2 text-xs"></i>
        <span class="text-gray-800 dark:text-gray-200 font-medium hover:text-blue-600 dark:hover:text-blue-400">Upload Document</span>
    </li>
@endsection

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header Card -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl shadow-lg overflow-hidden mb-6">
        <div class="px-6 py-4 sm:px-8 sm:py-6">
            <div class="flex items-center space-x-3">
                <div class="bg-white/20 p-3 rounded-xl backdrop-blur-sm">
                    <i class="bi bi-upload text-2xl text-white"></i>
                </div>
                <div>
                    <h4 class="text-xl font-bold text-white">
                        Upload Document to 201 File
                    </h4>
                    <p class="text-sm text-blue-100">
                        {{ $employee->first_name ?? '' }} {{ $employee->last_name ?? '' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Existing Documents Alert -->
    @php
        // Convert array to collection if needed, or check if it's a collection
        $existingDocs = is_array($existingDocs ?? []) 
            ? collect($existingDocs ?? []) 
            : ($existingDocs ?? collect());
        $hasExistingDocs = $existingDocs->count() > 0;
    @endphp

    @if($hasExistingDocs)
    <div class="bg-amber-50 dark:bg-amber-900/20 border-l-4 border-amber-500 rounded-lg p-4 mb-6">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="bi bi-info-circle text-amber-500 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-semibold text-amber-800 dark:text-amber-300">
                    Existing Documents
                </h3>
                <div class="mt-1 text-sm text-amber-700 dark:text-amber-400">
                    <!-- <p>You already have the following documents uploaded. You can upload new versions/updates:</p> -->
                    <ul class="list-disc list-inside mt-1">
                        @foreach($existingDocs as $doc)
                            <li>
                                <strong>{{ $doc->documentType->name ?? $doc['document_type_name'] ?? 'Document' }}</strong> 
                                (Uploaded: {{ isset($doc->created_at) ? $doc->created_at->format('M d, Y') : (isset($doc['created_at']) ? date('M d, Y', strtotime($doc['created_at'])) : 'N/A') }})
                                @if(isset($doc->version_number) && $doc->version_number)
                                    - Version {{ $doc->version_number }}
                                @elseif(isset($doc['version_number']) && $doc['version_number'])
                                    - Version {{ $doc['version_number'] }}
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Form Card -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
            <h5 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                <i class="bi bi-file-earmark-plus text-blue-600 mr-2"></i>
                Document Information
            </h5>
        </div>
        
        <div class="p-6">
            <form action="{{ route('mydocuments.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- Document Type & Title -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="document_type_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Document Type <span class="text-red-500">*</span>
                        </label>
                        <select name="document_type_id" id="document_type_id" 
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500 @error('document_type_id') border-red-500 @enderror" 
                                required>
                            <option value="">Select Document Type</option>
                            @if(isset($documentTypes) && $documentTypes->count() > 0)
                                @foreach($documentTypes as $category => $types)
                                    <optgroup label="{{ $category }}" class="font-bold text-gray-900 dark:text-gray-200">
                                        @foreach($types as $type)
                                            <option value="{{ $type->id }}" 
                                                {{ $type->is_required ? 'data-required="true"' : '' }}
                                                {{ old('document_type_id') == $type->id ? 'selected' : '' }}
                                                class="py-1">
                                                {{ $type->name }}
                                                @if($type->is_required)
                                                    <span class="text-red-500">*</span>
                                                @endif
                                                <!-- Show if document already exists and count -->
                                                @php
                                                    $docCount = $hasExistingDocs ? $existingDocs->where('document_type_id', $type->id)->count() : 0;
                                                    if ($docCount === 0 && is_array($existingDocs ?? [])) {
                                                        $docCount = collect($existingDocs ?? [])->where('document_type_id', $type->id)->count();
                                                    }
                                                @endphp
                                                @if($docCount > 0)
                                                    <span class="text-gray-400 text-xs">
                                                        ({{ $docCount }} version{{ $docCount > 1 ? 's' : '' }} uploaded)
                                                    </span>
                                                @endif
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            @else
                                <option value="">No document types available</option>
                            @endif
                        </select>
                        @error('document_type_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-blue-600 dark:text-blue-400">
                            <i class="bi bi-plus-circle"></i> You can upload multiple versions of the same document type (e.g., yearly SALN updates)
                        </p>
                    </div>

                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Document Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" id="title" 
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500 @error('title') border-red-500 @enderror" 
                               value="{{ old('title') }}" 
                               placeholder="Enter document title" 
                               required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Version Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="version_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Version Number 
                            <span class="text-gray-400 text-xs">(optional)</span>
                        </label>
                        <input type="text" name="version_number" id="version_number" 
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500 @error('version_number') border-red-500 @enderror" 
                               value="{{ old('version_number') }}" 
                               placeholder="e.g., 2024, 2.0, Q4-2024">
                        @error('version_number')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="document_year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Document Year 
                            <span class="text-gray-400 text-xs">(for annual documents)</span>
                        </label>
                        <select name="document_year" id="document_year" 
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500 @error('document_year') border-red-500 @enderror">
                            <option value="">Select Year</option>
                            @php
                                $currentYear = date('Y');
                                $startYear = $currentYear - 10;
                            @endphp
                            @for($year = $currentYear; $year >= $startYear; $year--)
                                <option value="{{ $year }}" {{ old('document_year') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endfor
                        </select>
                        @error('document_year')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Document Date & Expiry Date -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="document_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Document Date
                        </label>
                        <input type="date" name="document_date" id="document_date" 
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500 @error('document_date') border-red-500 @enderror" 
                               value="{{ old('document_date') }}">
                        @error('document_date')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="expiry_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Expiry Date
                        </label>
                        <input type="date" name="expiry_date" id="expiry_date" 
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500 @error('expiry_date') border-red-500 @enderror" 
                               value="{{ old('expiry_date') }}">
                        @error('expiry_date')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Reference Number & Issuing Authority -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="reference_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Reference Number
                        </label>
                        <input type="text" name="reference_number" id="reference_number" 
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500 @error('reference_number') border-red-500 @enderror" 
                               value="{{ old('reference_number') }}" 
                               placeholder="e.g., CSC-2024-001">
                        @error('reference_number')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="issuing_authority" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Issuing Authority
                        </label>
                        <input type="text" name="issuing_authority" id="issuing_authority" 
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500 @error('issuing_authority') border-red-500 @enderror" 
                               value="{{ old('issuing_authority') }}" 
                               placeholder="e.g., Civil Service Commission">
                        @error('issuing_authority')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Received From & Received Date -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="received_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Received From <span class="text-gray-400 text-xs">(for Transferees)</span>
                        </label>
                        <input type="text" name="received_from" id="received_from" 
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500 @error('received_from') border-red-500 @enderror" 
                               value="{{ old('received_from') }}" 
                               placeholder="Previous agency for transferees">
                        @error('received_from')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="received_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Received Date
                        </label>
                        <input type="date" name="received_date" id="received_date" 
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500 @error('received_date') border-red-500 @enderror" 
                               value="{{ old('received_date') }}">
                        @error('received_date')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Description
                    </label>
                    <textarea name="description" id="description" rows="2" 
                              class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500 @error('description') border-red-500 @enderror" 
                              placeholder="Brief description of the document">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- File Upload -->
                <div class="mb-4">
                    <label for="document" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        File <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="file" name="document" id="document" 
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500 @error('document') border-red-500 @enderror file:mr-4 file:py-2 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900/30 dark:file:text-blue-300" 
                               required accept=".pdf,.jpg,.jpeg,.png">
                    </div>
                    @error('document')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        <i class="bi bi-info-circle"></i> 
                        Max file size: 20MB. Allowed: PDF, JPG, PNG
                    </p>
                </div>

                <!-- Checkboxes -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_original" id="is_original" 
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" 
                               value="1" {{ old('is_original') ? 'checked' : '' }}>
                        <label for="is_original" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                            <i class="bi bi-check-circle text-emerald-500"></i>
                            This is the Original Document
                        </label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_confidential" id="is_confidential" 
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" 
                               value="1" {{ old('is_confidential') ? 'checked' : '' }}>
                        <label for="is_confidential" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                            <i class="bi bi-lock text-purple-500"></i>
                            Confidential Document
                        </label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="status" id="status" 
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" 
                               value="pending" {{ old('status') ? 'checked' : '' }}>
                        <label for="status" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                            <i class="bi bi-clock-history text-amber-500"></i>
                            Mark as Pending Approval
                        </label>
                    </div>
                </div>

                <!-- Original Location -->
                <div class="mb-4">
                    <label for="original_location" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Original Document Location
                    </label>
                    <input type="text" name="original_location" id="original_location" 
                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500 @error('original_location') border-red-500 @enderror" 
                           value="{{ old('original_location') }}" 
                           placeholder="Where is the original document stored?">
                    @error('original_location')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remarks -->
                <div class="mb-6">
                    <label for="remarks" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Remarks
                    </label>
                    <textarea name="remarks" id="remarks" rows="2" 
                              class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500 @error('remarks') border-red-500 @enderror" 
                              placeholder="Additional remarks or notes">{{ old('remarks') }}</textarea>
                    @error('remarks')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="flex flex-col sm:flex-row justify-between items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('mydocuments.index') }}" 
                       class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg transition-colors duration-200">
                        <i class="bi bi-arrow-left mr-2"></i> Back to Documents
                    </a>
                    <button type="submit" 
                            class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="bi bi-upload mr-2"></i> Upload Document
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const docTypeSelect = document.getElementById('document_type_id');
    const titleInput = document.getElementById('title');
    const yearSelect = document.getElementById('document_year');
    
    if (docTypeSelect) {
        docTypeSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption && selectedOption.text && !selectedOption.disabled) {
                // Get document type name without the indicators
                const docTypeName = selectedOption.text.replace(/[\[\(].*$/, '').trim();
                
                // Auto-generate title with year if available
                let year = yearSelect ? yearSelect.value : new Date().getFullYear();
                if (!year || year === '') {
                    year = new Date().getFullYear();
                }
                
                if (!titleInput.value || titleInput.value === '') {
                    titleInput.value = docTypeName + ' - ' + year;
                }
            }
        });
    }

    // Auto-update title when year changes
    if (yearSelect) {
        yearSelect.addEventListener('change', function() {
            const docTypeName = docTypeSelect.options[docTypeSelect.selectedIndex]?.text.replace(/[\[\(].*$/, '').trim() || '';
            if (docTypeName && this.value) {
                // Only update if title is empty or was auto-generated
                const titleValue = titleInput.value;
                const yearPattern = /-\s*\d{4}$/; // Matches "- 2024" at end
                
                if (!titleValue || yearPattern.test(titleValue)) {
                    titleInput.value = docTypeName + ' - ' + this.value;
                }
            }
        });
    }

    // File input validation
    const fileInput = document.getElementById('document');
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                // Check file size (20MB = 20 * 1024 * 1024)
                const maxSize = 20 * 1024 * 1024;
                if (file.size > maxSize) {
                    alert('File size exceeds 20MB limit. Please choose a smaller file.');
                    this.value = '';
                }
            }
        });
    }

    // Show version number suggestion based on document type
    docTypeSelect?.addEventListener('change', function() {
        const versionInput = document.getElementById('version_number');
        if (versionInput && !versionInput.value) {
            const selectedText = this.options[this.selectedIndex]?.text || '';
            const annualDocs = ['SALN', 'IPCR', 'OPCR', 'Medical'];
            const isAnnual = annualDocs.some(doc => selectedText.includes(doc));
            
            if (isAnnual) {
                const year = new Date().getFullYear();
                versionInput.placeholder = `e.g., ${year} (recommended)`;
            }
        }
    });
});
</script>
@endpush
@endsection