<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Jobs\ProcessFiducidiariaCsv;
use Illuminate\Support\Facades\Cache;
use App\FileUploadLog;

class FiducidiariaController extends Controller
{
    public function index()
    {
        return view('pensiones.uploadFiducidiaria');
    }

    public function upload(Request $request)
    {
        // Aumentar el tiempo máximo de ejecución
        set_time_limit(300); // 300 segundos = 5 minutos

        Log::info('Upload endpoint hit.');
        $request->validate([
            'file' => 'required|mimes:csv,txt',
        ]);

        Log::info('Validation passed. Storing file.');

        $filePath = $request->file('file')->store('uploads');

        Log::info('File stored at: ' . $filePath);

        // Insertar registro en file_upload_logs
        $uploadLog = FileUploadLog::create([
            'file_path' => $filePath,
            'total_registros' => 0, // Aquí se actualizará después de contar los registros
            'registros_procesados' => 0,
            'total_por_registrar' => 0,
            'http_status' => null
        ]);

        // Dispatch a job to process the CSV file
        Log::info('Dispatching job to process CSV file.');
        $job = new ProcessFiducidiariaCsv($filePath);
        dispatch($job);

        // Hacer una solicitud HTTP al servidor Flask
        $url = 'http://localhost:5000/process_csv';
        $data = [
            'file_path' => storage_path('app/' . $filePath),
            'document_type' => 'fiducidiaria'
        ];
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        
        $response = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // Actualizar el estado HTTP en file_upload_logs
        $uploadLog->http_status = $http_status;
        $uploadLog->save();

        // Log the response from the Flask API
        Log::info('Flask API response: ' . $response);

        return response()->json(['success' => true, 'message' => 'File uploaded and processing started.']);
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
