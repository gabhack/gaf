<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FileUploadLog;

class FileUploadLogController extends Controller
{
    public function getLogs()
    {
        $logs = FileUploadLog::all();
        return response()->json($logs);
    }
}
