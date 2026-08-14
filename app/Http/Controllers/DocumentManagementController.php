<?php

namespace App\Http\Controllers;
use App\Models\PDSPersonalData;
use App\Models\EmployeeDocument;
use App\Models\Departments;
use App\Models\DocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = EmployeeDocument::with(['employee', 'documentType'])
            ->when($request->search, function($q, $search) {
                return $q->whereHas('employee', function($q) use ($search) {
                    $q->where('first_name', 'LIKE', "%{$search}%")
                    ->orWhere('middle_name', 'LIKE', "%{$search}%")
                    ->orWhere('last_name', 'LIKE', "%{$search}%");
                })->orWhere('title', 'LIKE', "%{$search}%");
            })
            ->when($request->status, function($q, $status) {
                return $q->where('status', $status);
            })
            ->when($request->category, function($q, $category) {
                return $q->whereHas('documentType', function($q) use ($category) {
                    $q->where('category', $category);
                });
            })
            ->when($request->department, function($q, $department) {
                // Fix: Use employment relationship to filter by department
                return $q->whereHas('employee', function($q) use ($department) {
                    $q->whereHas('employment', function($q) use ($department) {
                        $q->whereHas('department', function($q) use ($department) {
                            $q->where('name', $department);
                        });
                    });
                });
            });

        $documents = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Add version metadata
        $documents->getCollection()->each(function($doc) {
            $latest = EmployeeDocument::where('user_id', $doc->user_id)
                ->where('document_type_id', $doc->document_type_id)
                ->orderBy('created_at', 'desc')
                ->first();
            $doc->is_latest_version = $latest && $latest->id === $doc->id;
        });

        $statistics = [
            'total_documents' => EmployeeDocument::count(),
            'pending_documents' => EmployeeDocument::where('status', 'pending')->count(),
            'rejected_documents' => EmployeeDocument::where('status', 'rejected')->count(),
            'expiring_documents' => EmployeeDocument::whereNotNull('expiry_date')
                ->where('expiry_date', '<=', now()->addDays(30))
                ->where('expiry_date', '>=', now())
                ->count(),
            'total_employees' => PDSPersonalData::has('documents')->count(),
        ];

        $categories = DocumentType::select('category')->distinct()->pluck('category');
        $departments = Departments::select('name')->distinct()->pluck('name');

        return view('documents.employee.index', compact(
            'documents', 
            'statistics', 
            'categories', 
            'departments'
        ));
    }

    public function approve(EmployeeDocument $document)
    {
        $document->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Document approved successfully.');
    }

    public function reject(Request $request, EmployeeDocument $document)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $document->update([
            'status' => 'rejected',
            'remarks' => $request->rejection_reason,
        ]);

        return redirect()->back()->with('success', 'Document rejected successfully.');
    }

    public function destroy(EmployeeDocument $document)
    {
        if (Storage::disk('documents')->exists($document->file_path)) {
            Storage::disk('documents')->delete($document->file_path);
        }

        $document->delete();

        return redirect()->back()->with('success', 'Document deleted successfully.');
    }

    public function download(EmployeeDocument $document)
    {
        if (!Storage::disk('documents')->exists($document->file_path)) {
            abort(404, 'File not found.');
        }

        return Storage::disk('documents')->download(
            $document->file_path,
            $document->file_name
        );
    }

    public function export()
    {
        // Export logic here
        // Generate CSV or Excel report
    }

    public function preview(EmployeeDocument $document)
    {
        $filePath = $document->file_path;
        
        if (!Storage::disk('documents')->exists($filePath)) {
            abort(404, 'File not found.');
        }
        
        $extension = strtolower(pathinfo($document->file_name, PATHINFO_EXTENSION));
        $mimeType = $document->mime_type ?: Storage::disk('documents')->mimeType($filePath);
        $fileSize = Storage::disk('documents')->size($filePath);
        $fileSizeFormatted = $this->formatSize($fileSize);
        
        // Determine preview type
        $previewType = $this->getPreviewType($extension, $mimeType);
        
        // For large PDFs, use streaming instead of base64 embedding
        $useStreaming = false;
        $fileContent = null;
        
        if ($previewType === 'pdf' && $fileSize > 5 * 1024 * 1024) { // > 5MB
            $useStreaming = true;
        } else {
            // Only load small files into memory
            $fileContent = Storage::disk('documents')->get($filePath);
        }
        
        $employee = $document->employee;
        
        return view('documents.employee.preview', compact(
            'document',
            'employee',
            'fileContent',
            'fileSize',
            'fileSizeFormatted',
            'extension',
            'mimeType',
            'previewType',
            'useStreaming'
        ));
    }

    private function formatSize($bytes)
    {
        if ($bytes === null || $bytes === 0) {
            return '0 B';
        }
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function stream(EmployeeDocument $document)
    {
        $filePath = $document->file_path;
        
        if (!Storage::disk('documents')->exists($filePath)) {
            abort(404);
        }
        
        $fullPath = Storage::disk('documents')->path($filePath);
        $mimeType = $document->mime_type ?: Storage::disk('documents')->mimeType($filePath);
        
        // Return the file as a stream
        return response()->file($fullPath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $document->file_name . '"',
            'Cache-Control' => 'private, max-age=86400',
            'Accept-Ranges' => 'bytes',
        ]);
    }

    private function getPreviewType($extension, $mimeType)
    {
        // Define preview types based on extension
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'];
        $pdfExtensions = ['pdf'];
        $textExtensions = ['txt', 'csv', 'log', 'md', 'xml', 'json'];
        $wordExtensions = ['doc', 'docx'];
        $excelExtensions = ['xls', 'xlsx'];
        $powerpointExtensions = ['ppt', 'pptx'];
        
        if (in_array($extension, $imageExtensions) || str_starts_with($mimeType, 'image/')) {
            return 'image';
        }
        
        if (in_array($extension, $pdfExtensions) || $mimeType === 'application/pdf') {
            return 'pdf';
        }
        
        if (in_array($extension, $textExtensions) || str_starts_with($mimeType, 'text/')) {
            return 'text';
        }
        
        if (in_array($extension, $wordExtensions)) {
            return 'word';
        }
        
        if (in_array($extension, $excelExtensions)) {
            return 'excel';
        }
        
        if (in_array($extension, $powerpointExtensions)) {
            return 'powerpoint';
        }
        
        return 'unsupported';
    }

    public function bulkApprove(Request $request)
    {
        $request->validate([
            'document_ids' => 'required'
        ]);

        // Decode the JSON string to an array
        $documentIds = json_decode($request->document_ids, true);
        
        // Check if decoding was successful and it's an array
        if (!is_array($documentIds) || empty($documentIds)) {
            return redirect()->back()->with('error', 'Invalid document IDs provided.');
        }

        $count = 0;
        $errors = [];

        foreach ($documentIds as $id) {
            try {
                $document = EmployeeDocument::find($id);
                if ($document && $document->status === 'pending') {
                    $document->update([
                        'status' => 'approved',
                        'approved_by' => auth()->id(),
                        'approved_at' => now(),
                    ]);
                    $count++;
                }
            } catch (\Exception $e) {
                $errors[] = "Failed to approve document ID: {$id}";
            }
        }

        if ($count > 0) {
            return redirect()->back()->with('success', "{$count} document(s) approved successfully.");
        }

        return redirect()->back()->with('error', 'No documents were approved. Please ensure they are pending.');
    }

    public function bulkReject(Request $request)
    {
        $request->validate([
            'document_ids' => 'required',
            'rejection_reason' => 'required|string|max:500'
        ]);

        // Decode the JSON string to an array
        $documentIds = json_decode($request->document_ids, true);
        
        // Check if decoding was successful and it's an array
        if (!is_array($documentIds) || empty($documentIds)) {
            return redirect()->back()->with('error', 'Invalid document IDs provided.');
        }

        $count = 0;
        $errors = [];

        foreach ($documentIds as $id) {
            try {
                $document = EmployeeDocument::find($id);
                if ($document && $document->status === 'pending') {
                    $document->update([
                        'status' => 'rejected',
                        'remarks' => $request->rejection_reason,
                    ]);
                    $count++;
                }
            } catch (\Exception $e) {
                $errors[] = "Failed to reject document ID: {$id}";
            }
        }

        if ($count > 0) {
            return redirect()->back()->with('success', "{$count} document(s) rejected successfully.");
        }

        return redirect()->back()->with('error', 'No documents were rejected. Please ensure they are pending.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'document_ids' => 'required'
        ]);

        // Decode the JSON string to an array
        $documentIds = json_decode($request->document_ids, true);
        
        // Check if decoding was successful and it's an array
        if (!is_array($documentIds) || empty($documentIds)) {
            return redirect()->back()->with('error', 'Invalid document IDs provided.');
        }

        $count = 0;
        $errors = [];

        foreach ($documentIds as $id) {
            try {
                $document = EmployeeDocument::find($id);
                if ($document) {
                    // Delete file
                    if (Storage::disk('documents')->exists($document->file_path)) {
                        Storage::disk('documents')->delete($document->file_path);
                    }
                    $document->delete();
                    $count++;
                }
            } catch (\Exception $e) {
                $errors[] = "Failed to delete document ID: {$id}";
            }
        }

        if ($count > 0) {
            return redirect()->back()->with('success', "{$count} document(s) deleted successfully.");
        }

        return redirect()->back()->with('error', 'No documents were deleted.');
    }
}