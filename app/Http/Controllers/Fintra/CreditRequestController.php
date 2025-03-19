<?php

namespace App\Http\Controllers\Fintra;

use App\Http\Controllers\Controller;
use App\CreditRequest;
use App\CreditCartera;
use App\CreditDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CreditRequestController extends Controller
{
    public function store(Request $request)
    {
        Log::info('=== Llamada a store() de CreditRequestController ===');
        Log::info('Request data =>', $request->all());

        $request->validate([
            'doc'          => 'required|string|max:20',
            'name'         => 'required|string|max:255',
            'client_type'  => 'required|string|max:50',
            'pagaduria_id' => 'required|integer',
            'cuota'        => 'required|numeric|min:0',
            'monto'        => 'required|numeric|min:0',
            'tasa'         => 'required|numeric|min:0',
            'plazo'        => 'required|integer|min:1',
            'tipo_credito' => 'required|string|max:50',
        ]);

        try {
            DB::beginTransaction();

            Log::info('Creando CreditRequest...');
            $credit = new CreditRequest();
            $credit->doc          = $request->doc;
            $credit->name         = $request->name;
            $credit->client_type  = $request->client_type;
            $credit->pagaduria_id = $request->pagaduria_id;
            $credit->cuota        = $request->cuota;
            $credit->monto        = $request->monto;
            $credit->tasa         = $request->tasa;
            $credit->plazo        = $request->plazo;
            $credit->status       = 'pendiente';
            $credit->tipo_credito = $request->tipo_credito;
            $credit->save();

            Log::info('CreditRequest creado con ID=' . $credit->id);

            // Carteras
            if ($request->has('carteras') && is_array($request->carteras)) {
                Log::info('Procesando carteras =>', $request->carteras);
                foreach ($request->carteras as $carItem) {
                    $cartera = new CreditCartera();
                    $cartera->credit_request_id = $credit->id;
                    $cartera->valor_cuota      = $carItem['valor_cuota'] ?? 0;
                    $cartera->saldo            = $carItem['saldo'] ?? 0;
                    $cartera->tipo_cartera     = $carItem['tipo_cartera'] ?? null;
                    $cartera->nombre_entidad   = $carItem['nombre_entidad'] ?? null;
                    $cartera->save();
                }
            }

            DB::commit();

            // Retornamos ID para que el front llame /upload-document
            return response()->json([
                'message' => 'Crédito guardado exitosamente.',
                'data'    => ['id' => $credit->id]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al guardar el crédito => ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al guardar el crédito',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function uploadDocument($id, Request $request)
    {
        Log::info("=== uploadDocument() ===");
        Log::info("Request => ", $request->all());
    
        // Validar que se recibió un archivo
        if (!$request->hasFile('archivo')) {
            Log::error("❌ No se recibió ningún archivo.");
            return response()->json(['error' => 'No se recibió ningún archivo'], 400);
        }
    
        // Validar el archivo
        $request->validate([
            'archivo' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240' // 10MB máximo
        ]);
    
        // Buscar el crédito
        $credit = CreditRequest::find($id);
        if (!$credit) {
            Log::error("❌ Crédito con ID={$id} no encontrado.");
            return response()->json(['error' => 'Crédito no encontrado'], 404);
        }
    
        // Guardar archivo en storage
        $path = $request->file('archivo')->store('public/documents');
        Log::info("✅ Archivo guardado en: {$path}");
    
        // Guardar en la base de datos
        $document = new CreditDocument();
        $document->credit_request_id = $credit->id;
        $document->file_path = $path;
        $document->save();
    
        Log::info("✅ Documento registrado en la base de datos con ID={$document->id}");
    
        return response()->json([
            'message' => 'Documento subido correctamente',
            'doc_id' => $document->id,
            'file_path' => $path
        ], 201);
    }
    

    public function index()
    {
        return view('CreditRequest.index');
    }

    public function getAll()
    {
        Log::info('=== getAll credit-requests ===');
        $credits = CreditRequest::with(['carteras', 'documents'])
            ->where('status', '!=', 'visado')
            ->orderBy('updated_at', 'DESC')
            ->get();

        Log::info('Total credit requests => ' . $credits->count());

        return response()->json($credits);
    }

    public function updateStatus($id)
    {
        try {
            $credit = CreditRequest::findOrFail($id);
            $credit->status = 'aprobado';
            $credit->save();
            return response()->json(['message' => 'Solicitud aprobada exitosamente'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al aprobar la solicitud',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function markAsVisado($id)
    {
        Log::info('markAsVisado llamado con ID=' . $id);
        try {
            $credit = CreditRequest::findOrFail($id);
            $credit->status = 'visado';
            $credit->save();
            return response()->json([
                'message' => 'Crédito marcado como visado exitosamente'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error al marcar visado => ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al marcar el crédito como visado',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
