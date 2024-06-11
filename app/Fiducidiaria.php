<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\ProcessFiducidiariaExcel;
use App\Models\UploadProgress;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FiducidiariaController extends Controller
{
    public function upload(Request $request)
    {
        Log::info('Upload endpoint for Fiducidiaria hit.');

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
            $path = $request->file('file')->store('uploads');
            Log::info('File stored at: ' . $path);

            // Create upload progress record
            $progress = UploadProgress::create([
                'file_name' => $path,
                'status' => 'processing',
            ]);

            // Dispatch the job to process the Excel file
            Log::info('Dispatching job to process Fiducidiaria Excel file.');
            ProcessFiducidiariaExcel::dispatch($path, $progress->id);

            Log::info('Job dispatched.');

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

    public function progress($id)
    {
        $progress = UploadProgress::find($id);

        if ($progress) {
            return response()->json([
                'success' => true,
                'progress' => $progress->total_rows ? ($progress->processed_rows / $progress->total_rows) * 100 : 0,
                'status' => $progress->status,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Progress not found'
        ], 404);
    }
}
