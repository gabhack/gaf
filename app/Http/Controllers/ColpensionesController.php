<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\ColpensionesImport;
use App\Imports\FiduciariaImport;
use App\UploadProgress;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ColpensionesController extends Controller
{
    public function upload(Request $request)
    {
        Log::info('Upload endpoint hit.');

        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed.', ['errors' => $validator->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            Log::info('Validation passed. Storing file.');
            $file = $request->file('file');
            $path = $file->store('uploads');
            Log::info('File stored at: ' . $path);

            // Create a record in upload_progress table
            $filename = $file->getClientOriginalName();
            $progress = UploadProgress::create([
                'file_name' => $filename,
                'total_rows' => 0,
                'processed_rows' => 0,
                'status' => 'pending'
            ]);

            Log::info('Created progress record.', ['progressId' => $progress->id]);

            // Process the Excel file
            Log::info('Processing Excel file.');
            if (Str::contains($filename, 'colpensar')) {
                $this->processExcelFile($path, $progress->id, new ColpensionesImport);
            } elseif (Str::contains($filename, 'fiduciaria')) {
                $this->processExcelFile($path, $progress->id, new FiduciariaImport);
            }

            return response()->json([
                'success' => true,
                'progressId' => $progress->id,
                'message' => 'File uploaded successfully and processing started.'
            ]);
        } catch (\Exception $e) {
            Log::error('An error occurred while uploading the file.', ['exception' => $e]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while uploading the file.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function processExcelFile($path, $progressId, $importClass)
    {
        try {
            Excel::import($importClass, $path, null, \Maatwebsite\Excel\Excel::XLSX);
            UploadProgress::where('id', $progressId)->update([
                'processed_rows' => 100,
                'status' => 'completed',
                'updated_at' => now()
            ]);
            Log::info('Excel file processed successfully.', ['progressId' => $progressId]);
        } catch (\Exception $e) {
            UploadProgress::where('id', $progressId)->update([
                'status' => 'failed',
                'updated_at' => now()
            ]);
            Log::error('An error occurred while processing the Excel file.', ['exception' => $e, 'progressId' => $progressId]);
        }
    }

    public function progress($progressId)
    {
        Log::info('Progress endpoint hit.', ['progressId' => $progressId]);

        $progress = UploadProgress::find($progressId);

        if ($progress) {
            return response()->json([
                'progress' => $progress->processed_rows,
                'status' => $progress->status,
            ]);
        }

        Log::error('Progress not found.', ['progressId' => $progressId]);
        return response()->json([
            'success' => false,
            'message' => 'Progress not found'
        ], 404);
    }
}
