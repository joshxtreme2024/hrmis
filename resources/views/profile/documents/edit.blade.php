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
        <span class="text-gray-800 dark:text-gray-200 font-medium hover:text-blue-600 dark:hover:text-blue-400">Edit Document</span>
    </li>
@endsection

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header Card -->
    <div class="bg-gradient-to-r from-amber-600 to-amber-700 rounded-xl shadow-lg overflow-hidden mb-6">
        <div class="px-6 py-4 sm:px-8 sm:py-6">
            <div class="flex items-center space-x-3">
                <div class="bg-white/20 p-3 rounded-xl backdrop-blur-sm">
                    <i class="bi bi-pencil-square text-2xl text-white"></i>
                </div>
                <div>
                    <h4 class="text-xl font-bold text-white">
                        Edit Document
                    </h4>
                    <p class="text-sm text-amber-100">
                        {{ $employee->first_name ?? '' }} {{ $employee->last_name ?? '' }} - {{ $document->title ?? '' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Current File Information -->
    @if(isset($document) && $document->file_path)
    <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 rounded-lg p-4 mb-6">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="bi bi-file-earmark-text text-blue-500 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-semibold text-blue-800 dark:text-blue-300">
                    Current Document
                </h3>
                <div class="mt-1 text-sm text-blue-700 dark:text-blue-400">
                    <p>
                        <strong>File:</strong> 
                        <span class="text-blue-600 dark:text-blue-400">
                            {{ basename($document->file_path) }}
                        </span>
                    </p>
                    @if(isset($document->version_number))
                        <p><strong>Version:</strong> {{ $document->version_number }}</p>
                    @endif
                    @if(isset($document->document_year))
                        <p><strong>Year:</strong> {{ $document->document_year }}</p>
                    @endif
                    <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">
                        <i class="bi bi-info-circle"></i> 
                        Upload a new file to replace the current one, or update the information below.
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Form Card -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
            <h5 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                <i class="bi bi-pencil-square text-amber-600 mr-2"></i>
                Edit Document Information
            </h5>
        </div>
        
        <div class="p-6">
            <form action="{{ route('mydocuments.update', ['employee' => $employee, 'document' => $document]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
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
                                                {{ old('document_type_id', $document->document_type_id ?? '') == $type->id ? 'selected' : '' }}
                                                class="py-1">
                                                {{ $type->name }}
                                                @if($type->is_required)
                                                    <span class="text-red-500">*</span>
                                                @endif
                                                <!-- Show if document already exists and count (excluding current document) -->
                                                @php
                                                    $docCount = 0;
                                                    if ($hasExistingDocs ?? false) {
                                                        $docCount = $existingDocsCollection->where('document_type_id', $type->id)
                                                            ->where('id', '!=', $document->id ?? null)
                                                            ->count();
                                                    }
                                                    if ($docCount === 0 && is_array($existingDocs ?? [])) {
                                                        $docCount = collect($existingDocs ?? [])
                                                            ->where('document_type_id', $type->id)
                                                            ->where('id', '!=', $document->id ?? null)
                                                            ->count();
                                                    }
                                                @endphp
                                                @if($docCount > 0)
                                                    <span class="text-gray-400 text-xs">
                                                        ({{ $docCount }} other version{{ $docCount > 1 ? 's' : '' }} uploaded)
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
                    </div>

                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Document Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" id="title" 
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500 @error('title') border-red-500 @enderror" 
                               value="{{ old('title', $document->title ?? '') }}" 
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
                               value="{{ old('version_number', $document->version_number ?? '') }}" 
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
                                <option value="{{ $year }}" {{ old('document_year', $document->document_year ?? '') == $year ? 'selected' : '' }}>
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
                               value="{{ old('document_date', isset($document->document_date) ? $document->document_date->format('Y-m-d') : '') }}">
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
                               value="{{ old('expiry_date', isset($document->expiry_date) ? $document->expiry_date->format('Y-m-d') : '') }}">
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
                               value="{{ old('reference_number', $document->reference_number ?? '') }}" 
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
                               value="{{ old('issuing_authority', $document->issuing_authority ?? '') }}" 
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
                               value="{{ old('received_from', $document->received_from ?? '') }}" 
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
                               value="{{ old('received_date', isset($document->received_date) ? $document->received_date->format('Y-m-d') : '') }}">
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
                              placeholder="Brief description of the document">{{ old('description', $document->description ?? '') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- File Upload -->
                <div class="mb-4">
                    <label for="document" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        File <span class="text-gray-400 text-xs">(leave empty to keep current file)</span>
                    </label>
                    <div class="relative">
                        <input type="file" name="document" id="document" 
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-blue-500 focus:ring-blue-500 @error('document') border-red-500 @enderror file:mr-4 file:py-2 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900/30 dark:file:text-blue-300" 
                               accept=".pdf,.jpg,.jpeg,.png">
                    </div>
                    @error('document')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        <i class="bi bi-info-circle"></i> 
                        Max file size: 20MB. Allowed: PDF, JPG, PNG. Leave empty to keep the current file.
                    </p>
                    @if(isset($document) && $document->file_path)
                        <p class="mt-1 text-xs text-green-600 dark:text-green-400">
                            <i class="bi bi-check-circle"></i> 
                            Current file: {{ basename($document->file_path) }}
                        </p>
                    @endif
                </div>

                <!-- Checkboxes -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_original" id="is_original" 
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" 
                               value="1" {{ old('is_original', $document->is_original ?? false) ? 'checked' : '' }}>
                        <label for="is_original" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                            <i class="bi bi-check-circle text-emerald-500"></i>
                            This is the Original Document
                        </label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_confidential" id="is_confidential" 
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" 
                               value="1" {{ old('is_confidential', $document->is_confidential ?? false) ? 'checked' : '' }}>
                        <label for="is_confidential" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                            <i class="bi bi-lock text-purple-500"></i>
                            Confidential Document
                        </label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="status" id="status" 
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" 
                               value="pending" {{ old('status', ($document->status ?? '') == 'pending') ? 'checked' : '' }}>
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
                           value="{{ old('original_location', $document->original_location ?? '') }}" 
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
                              placeholder="Additional remarks or notes">{{ old('remarks', $document->remarks ?? '') }}</textarea>
                    @error('remarks')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="flex flex-col sm:flex-row justify-between items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                        <a href="{{ route('mydocuments.index', $employee) }}" 
                           class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg transition-colors duration-200">
                            <i class="bi bi-arrow-left mr-2"></i> Back to Documents
                        </a>
                        @if(isset($document) && $document->id)
                        <button type="button" onclick="confirmDelete()" 
                                class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-red-100 hover:bg-red-200 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-700 dark:text-red-300 rounded-lg transition-colors duration-200">
                            <i class="bi bi-trash mr-2"></i> Delete Document
                        </button>
                        @endif
                    </div>
                    <button type="submit" 
                            class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2 bg-gradient-to-r from-amber-600 to-amber-700 hover:from-amber-700 hover:to-amber-800 text-white rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="bi bi-save mr-2"></i> Update Document
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
                
                // Auto-generate title with year if available and title hasn't been manually edited
                let year = yearSelect ? yearSelect.value : new Date().getFullYear();
                if (!year || year === '') {
                    year = new Date().getFullYear();
                }
                
                // Only update if title matches the old auto-generated pattern or is empty
                const currentTitle = titleInput.value;
                const oldDocTypeName = '{{ $document->documentType->name ?? '' }}';
                const oldYear = '{{ $document->document_year ?? '' }}';
                const oldPattern = oldDocTypeName && oldYear ? oldDocTypeName + ' - ' + oldYear : '';
                
                if (!currentTitle || currentTitle === oldPattern || !titleInput.dataset.userEdited) {
                    titleInput.value = docTypeName + ' - ' + year;
                }
            }
        });

        // Track if user manually edited the title
        titleInput.addEventListener('input', function() {
            this.dataset.userEdited = 'true';
        });
    }

    // Auto-update title when year changes
    if (yearSelect) {
        yearSelect.addEventListener('change', function() {
            const docTypeName = docTypeSelect.options[docTypeSelect.selectedIndex]?.text.replace(/[\[\(].*$/, '').trim() || '';
            if (docTypeName && this.value) {
                const currentTitle = titleInput.value;
                const yearPattern = /-\s*\d{4}$/; // Matches "- 2024" at end
                const oldDocTypeName = '{{ $document->documentType->name ?? '' }}';
                const oldYear = '{{ $document->document_year ?? '' }}';
                const oldPattern = oldDocTypeName && oldYear ? oldDocTypeName + ' - ' + oldYear : '';
                
                if (!currentTitle || currentTitle === oldPattern || (yearPattern.test(currentTitle) && !titleInput.dataset.userEdited)) {
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

// Delete confirmation
function confirmDelete() {
    if (confirm('Are you sure you want to delete this document? This action cannot be undone.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("mydocuments.destroy", ["employee" => $employee, "document" => $document]) }}';
        form.innerHTML = '@csrf @method("DELETE")';
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
@endsection