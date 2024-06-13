<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\ProcessColpensionesExcel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ColpensionesController extends Controller
{
    public function upload(Request $request)
    {
        Log::info('Upload endpoint hit.');
        $request->validate([
            'file' => 'required|mimes:xlsx',
        ]);

        Log::info('Validation passed. Storing file.');

        $filePath = $request->file('file')->store('uploads');

        Log::info('File stored at: ' . $filePath);

        Log::info('Dispatching job to process Excel file.');
        $job = new ProcessColpensionesExcel($filePath);
        dispatch($job);

        return response()->json(['success' => true, 'message' => 'File uploaded successfully and processing started. This may take up to 15 minutes.', 'progressKey' => $job->progressKey]);
    }

    public function checkProgress($progressKey)
    {
        $progress = Cache::get($progressKey);
        $total = Cache::get($progressKey . '_total');

        if ($progress === null || $total === null) {
            return response()->json(['completed' => true]);
        }

        return response()->json(['completed' => false, 'progress' => $progress, 'total' => $total]);
    }
}
