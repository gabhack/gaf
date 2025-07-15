<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Document;
use Response;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DocumentsImport;
use App\Exceptions\ImportValidationException;
use Illuminate\Support\Facades\Storage;


class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::all();
        return response()->json($documents);
    }

    public function store(Request $request)
    {
        $document = Document::create($request->all());
        return response()->json($document, 201);
    }

    public function update($id, Request $request)
    {
        $document = Document::findOrFail($id);
        $document->update($request->all());
        return response()->json($document);
    }

    public function uploadPdf($id, Request $request)
    {
        $document = Document::findOrFail($id);
        if ($request->hasFile('pdf') && $request->file('pdf')->isValid()) {
            $path = $request->pdf->store('public/documents');
            $document->update([
                'pdfUrl' => $path,
                'status' => 'Procesado'
            ]);
            return response()->json($document);
        }
        return response()->json(['error' => 'Invalid file or no file uploaded'], 400);
    }

    public function deletePdf($id)
    {
        $document = Document::findOrFail($id);
        if ($document->pdfUrl && Storage::delete($document->pdfUrl)) {
            $document->update([
                'pdfUrl' => null,
                'status' => 'Pendiente'
            ]);
            return response()->json(['message' => 'PDF deleted successfully and status updated to Pendiente']);
        }
        return response()->json(['error' => 'PDF not found or already deleted'], 404);
    }

    public function downloadPdf($id)
    {
        $document = Document::findOrFail($id);

        // Remove 'public/' from the pdfUrl for internal Laravel path
        $internalPath = str_replace('public/', '', $document->pdfUrl);
        $fullPath = storage_path('app/public/' . $internalPath);

        if (Storage::disk('public')->exists($internalPath)) {
            return response()->download($fullPath);
        } else {
            \Log::error("File not found at path: {$fullPath}");
            return response()->json(['error' => 'File not found', 'path' => $fullPath], 404);
        }
    }

    public function destroy($id)
    {
        Document::destroy($id);
        return response()->json(['message' => 'Document deleted successfully']);
    }

    public function uploadBulk(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx',
        ]);

        try {
            Excel::import(new DocumentsImport, $request->file('file'));
            return response()->json(['message' => 'Documents uploaded successfully'], 200);
        } catch (ImportValidationException $e) {
            return response()->json(['error' => 'Validation failed', 'messages' => $e->getErrors()], 422);
        }
    }
}
