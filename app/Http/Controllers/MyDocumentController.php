<?php

namespace App\Http\Controllers;

use App\Models\PDSPersonalData;
use App\Models\EmployeeDocument;
use App\Models\DocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MyDocumentController extends Controller
{
    public function index()
    {
        $employee = PDSPersonalData::find(auth()->user()->id);
        $documents = $employee->documents()
            ->with('documentType')
            ->get()
            ->groupBy(function($doc) {
                return $doc->documentType->category ?? 'Uncategorized';
            });

        // Add version metadata to each document
        $documents->each(function($group) {
            $group->each(function($doc) use ($group) {
                // Check if this is the latest version of its type
                $latestVersion = $group->where('document_type_id', $doc->document_type_id)
                    ->sortByDesc('created_at')
                    ->first();
                $doc->is_latest_version = $latestVersion && $latestVersion->id === $doc->id;
                
                // Check if new version can be created
                $doc->canCreateNewVersion = true; // Or based on your business rules
            });
        });

        // Statistics
        $totalDocuments = $employee->documents()->count();
        $approvedDocuments = $employee->documents()->where('status', 'approved')->count();
        $pendingDocuments = $employee->documents()->where('status', 'pending')->count();
        $expiringDocuments = $employee->documents()
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', now()->addDays(30))
            ->where('expiry_date', '>=', now())
            ->count();

        // Completeness calculation
        $requiredTypes = DocumentType::where('is_required', true)->get();
        $uploadedTypes = $employee->documents()
            ->where('status', 'approved')
            ->pluck('document_type_id')
            ->unique();
        
        $completeness = [
            'total_required' => $requiredTypes->count(),
            'completed' => $requiredTypes->whereIn('id', $uploadedTypes)->count(),
            'percentage' => $requiredTypes->count() > 0 
                ? round(($requiredTypes->whereIn('id', $uploadedTypes)->count() / $requiredTypes->count()) * 100)
                : 0,
            'is_complete' => $requiredTypes->count() > 0 
                ? $requiredTypes->whereIn('id', $uploadedTypes)->count() === $requiredTypes->count()
                : false,
            'items' => $requiredTypes->mapWithKeys(function($type) use ($uploadedTypes, $employee) {
                $versions = $employee->documents()
                    ->where('document_type_id', $type->id)
                    ->count();
                return [
                    $type->code => [
                        'name' => $type->name,
                        'is_completed' => $uploadedTypes->contains($type->id),
                        'version_count' => $versions,
                    ]
                ];
            })
        ];

        return view('profile.documents.index', compact(
            'employee', 
            'documents', 
            'completeness',
            'totalDocuments',
            'approvedDocuments',
            'pendingDocuments',
            'expiringDocuments'
        ));
    }

    private function get201FileCompleteness()
    {
        $employee = PDSPersonalData::find(auth()->user()->id);
        $requiredTypes = DocumentType::where('is_required', true)->get();
        $hasDocuments = $employee->documents()
            ->whereIn('document_type_id', $requiredTypes->pluck('id'))
            ->where('status', 'approved')
            ->pluck('document_type_id')
            ->toArray();

        $completeness = [];
        foreach ($requiredTypes as $type) {
            $completeness[$type->code] = [
                'name' => $type->name,
                'is_completed' => in_array($type->id, $hasDocuments),
                'document_type_id' => $type->id,
                'sort_order' => $type->sort_order
            ];
        }

        // Calculate percentage
        $totalRequired = $requiredTypes->count();
        $completed = count($hasDocuments);
        $percentage = $totalRequired > 0 ? round(($completed / $totalRequired) * 100) : 0;

        return [
            'items' => collect($completeness)->sortBy('sort_order'),
            'total_required' => $totalRequired,
            'completed' => $completed,
            'percentage' => $percentage,
            'is_complete' => $percentage == 100
        ];
    }

    public function create()
    {
        $employee = PDSPersonalData::find(auth()->user()->id);
        
        // Get document types and group them by category
        $documentTypes = DocumentType::active()
            ->orderBy('category')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('category');

        // Get employee's existing documents as FULL OBJECTS
        $existingDocs = $employee->documents()
            ->whereIn('status', ['approved', 'pending'])
            ->with('documentType')  // Eager load the relationship
            ->get();  // 👈 Get full objects, not just IDs

        return view('profile.documents.create', compact(
            'employee',
            'documentTypes',
            'existingDocs'  // Now this is a Collection of EmployeeDocument objects
        ));
    }

    public function store(Request $request)
    {
        $employee = PDSPersonalData::find(auth()->user()->id);
        $validator = Validator::make($request->all(), [
            'document_type_id' => 'required|exists:document_types,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:20480', // 20MB
            'document_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:document_date',
            'reference_number' => 'nullable|string|max:100',
            'issuing_authority' => 'nullable|string|max:255',
            'received_from' => 'nullable|string|max:255',
            'received_date' => 'nullable|date',
            'is_confidential' => 'boolean',
            'is_original' => 'boolean',
            'original_location' => 'nullable|string|max:255',
            'remarks' => 'nullable|string',
            'version_number' => 'nullable|string|max:50',
            'document_year' => 'nullable|integer|min:1900|max:' . date('Y'),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Upload file
        $file = $request->file('document');
        $fileName = $file->getClientOriginalName();
        $fileSize = $file->getSize();
        $mimeType = $file->getMimeType();

        // Generate unique filename
        $uniqueName = Str::uuid() . '-' . $fileName;
        $path = 'employees/' . $employee->id . '/' . $request->document_type_id;
        
        $filePath = $file->storeAs($path, $uniqueName, 'documents');

        // Create document record
        $document = $employee->documents()->create([
            'document_type_id' => $request->document_type_id,
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_size' => $fileSize,
            'mime_type' => $mimeType,
            'document_date' => $request->document_date,
            'expiry_date' => $request->expiry_date,
            'reference_number' => $request->reference_number,
            'issuing_authority' => $request->issuing_authority,
            'received_from' => $request->received_from,
            'received_date' => $request->received_date,
            'metadata' => $request->metadata ?? [],
            'status' => $request->status ?? 'pending',
            'remarks' => $request->remarks,
            'uploaded_by' => auth()->id(),
            'is_confidential' => $request->is_confidential ?? false,
            'is_original' => $request->is_original ?? false,
            'original_location' => $request->original_location,
            'version_number' => $request->version_number ?? $request->document_year,
            'document_year' => $request->document_year,
        ]);

        return redirect()
            ->route('mydocuments.index', $employee)
            ->with('success', 'Document uploaded successfully.');
    }

    public function edit(EmployeeDocument $document)
    {
        $employee = PDSPersonalData::find(auth()->user()->id);
        
        // Verify the document belongs to this employee
        if ($document->user_id !== $employee->id) {
            abort(403, 'You do not have permission to edit this document.');
        }
        
        // Get document types and group them by category
        $documentTypes = DocumentType::active()
            ->orderBy('category')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('category');

        // Get employee's existing documents (excluding the current document being edited)
        $existingDocs = $employee->documents()
            ->whereIn('status', ['approved', 'pending'])
            ->where('id', '!=', $document->id) // Exclude current document
            ->pluck('document_type_id')
            ->toArray();

        return view('profile.documents.edit', compact(
            'employee',
            'document',
            'documentTypes',
            'existingDocs'
        ));
    }

    public function update(Request $request, EmployeeDocument $document)
    {
        $employee = PDSPersonalData::find(auth()->user()->id);
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'document' => 'nullable|file|max:20480',
            'document_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:document_date',
            'reference_number' => 'nullable|string|max:100',
            'issuing_authority' => 'nullable|string|max:255',
            'received_from' => 'nullable|string|max:255',
            'received_date' => 'nullable|date',
            'is_confidential' => 'boolean',
            'is_original' => 'boolean',
            'original_location' => 'nullable|string|max:255',
            'remarks' => 'nullable|string',
            'version_number' => 'nullable|string|max:50',
            'document_year' => 'nullable|integer|min:1900|max:' . date('Y'),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->except(['document']);

        // Handle file upload if new file is provided
        if ($request->hasFile('document')) {
            // Delete old file
            if (Storage::disk('documents')->exists($document->file_path)) {
                Storage::disk('documents')->delete($document->file_path);
            }

            $file = $request->file('document');
            $fileName = $file->getClientOriginalName();
            $uniqueName = Str::uuid() . '-' . $fileName;
            $path = 'employees/' . $employee->id . '/' . $document->document_type_id;
            $filePath = $file->storeAs($path, $uniqueName, 'documents');

            $data['file_path'] = $filePath;
            $data['file_name'] = $fileName;
            $data['file_size'] = $file->getSize();
            $data['mime_type'] = $file->getMimeType();
            $data['version'] = $document->version + 0.1;
        }

        $document->update($data);

        return redirect()
            ->route('mydocuments.index')
            ->with('success', 'Document updated successfully.');
    }

    public function approve(EmployeeDocument $document)
    {
        $employee = PDSPersonalData::find(auth()->user()->id);
        $document->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()
            ->route('mydocuments.index')
            ->with('success', 'Document approved successfully.');
    }

    public function reject(Request $request, EmployeeDocument $document)
    {
        $employee = PDSPersonalData::find(auth()->user()->id);
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $document->update([
            'status' => 'rejected',
            'remarks' => $request->rejection_reason,
        ]);

        return redirect()
            ->route('mydocuments.index', $employee)
            ->with('success', 'Document rejected.');
    }

    public function destroy(EmployeeDocument $document)
    {
        $employee = PDSPersonalData::find(auth()->user()->id);
        // Delete file
        if (Storage::disk('documents')->exists($document->file_path)) {
            Storage::disk('documents')->delete($document->file_path);
        }

        $document->delete();

        return redirect()
            ->route('mydocuments.index', $employee)
            ->with('success', 'Document deleted successfully.');
    }

    /**
     * Download a document
     */
    public function download(EmployeeDocument $document)
    {
        // Check if the document belongs to the authenticated user
        $employee = PDSPersonalData::find(auth()->user()->id);
        
        // Verify the document belongs to this employee
        if ($document->user_id !== $employee->id) {
            abort(403, 'You do not have permission to download this document.');
        }

        // Check if file exists
        if (!Storage::disk('documents')->exists($document->file_path)) {
            abort(404, 'File not found.');
        }

        // Log the download (optional)
        // You can add audit logging here

        // Return the file for download
        return Storage::disk('documents')->download(
            $document->file_path,
            $document->file_name
        );
    }
}