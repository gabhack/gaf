<?php

namespace App\Http\Controllers\Fintra;

use App\Http\Controllers\Controller;
use App\CreditRequest;
use App\CreditCartera;
use App\CreditDocument;  // Modelo para documentos
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CreditRequestController extends Controller
{
    /**
     * Guarda un crédito junto con sus carteras
     */
    public function store(Request $request)
    {
        $request->validate([
            'doc'          => 'required|string|max:20',
            'name'         => 'required|string|max:255',
            'client_type'  => 'required|string|max:50',
            'pagaduria_id' => 'required|integer',
            'cuota'        => 'required|numeric|min:0',
            'monto'        => 'required|numeric|min:0',
            'tasa'         => 'required|numeric|min:0',
            'plazo'        => 'required|integer|min:1'
        ]);

        try {
            DB::beginTransaction();

            $credit = new CreditRequest();
            $credit->doc           = $request->input('doc');
            $credit->name          = $request->input('name');
            $credit->client_type   = $request->input('client_type');
            $credit->pagaduria_id  = $request->input('pagaduria_id');
            $credit->cuota         = $request->input('cuota');
            $credit->monto         = $request->input('monto');
            $credit->tasa          = $request->input('tasa');
            $credit->plazo         = $request->input('plazo');
            $credit->save();

            // Guardar carteras asociadas
            if ($request->has('carteras') && is_array($request->carteras)) {
                foreach ($request->carteras as $carteraItem) {
                    $cartera = new CreditCartera();
                    $cartera->credit_request_id = $credit->id;
                    $cartera->valor_cuota      = $carteraItem['valor_cuota'] ?? 0;
                    $cartera->saldo            = $carteraItem['saldo'] ?? 0;
                    $cartera->tipo_cartera     = $carteraItem['tipo_cartera'] ?? null;
                    $cartera->nombre_entidad   = $carteraItem['nombre_entidad'] ?? null;
                    $cartera->save();
                }
            }

            $credit->load('carteras');
            Log::info('Carteras del crédito ID ' . $credit->id . ': ', $credit->carteras->toArray());

            DB::commit();

            return response()->json([
                'message' => 'Crédito guardado exitosamente.',
                'data'    => $credit
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al guardar el crédito', [
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            return response()->json([
                'message' => 'Error al guardar el crédito',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retorna la vista principal
     */
    public function index()
    {
        return view('CreditRequest.index');
    }

    /**
     * Retorna todos los créditos con carteras y documentos
     */
    public function getAll()
    {
        // Incluimos 'carteras' y 'documents' en la carga
        $credits = CreditRequest::with(['carteras', 'documents'])
            ->orderBy('updated_at', 'DESC')
            ->get();

        // Ejemplo de logging en el servidor
        foreach ($credits as $credit) {
            Log::info('Carteras del crédito ID ' . $credit->id . ': ', $credit->carteras->toArray());
        }

        return response()->json($credits);
    }

    /**
     * Actualiza el estado de un crédito a 'aprobado'
     */
    public function updateStatus($id)
    {
        try {
            $credit = CreditRequest::findOrFail($id);
            $credit->status = 'aprobado';
            $credit->save();

            return response()->json([
                'message' => 'Solicitud aprobada exitosamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al aprobar la solicitud',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sube un documento (archivo) asociado a un crédito
     */
    public function uploadDocument(Request $request, $id)
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png'
        ]);

        try {
            // 1. Encontrar el crédito
            $credit = CreditRequest::findOrFail($id);

            // 2. Subir el archivo al storage (carpeta "public/documents")
            //    => Guardará la ruta "public/documents/xxxxxx"
            $path = $request->file('document')->store('public/documents');

            // 3. Crear el registro en la tabla 'credit_documents'
            $document = new CreditDocument();
            $document->credit_request_id = $credit->id;
            $document->file_path         = $path;
            $document->save();

            return response()->json([
                'message' => 'Documento subido exitosamente',
                'data'    => $document
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error al subir el documento: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al subir el documento',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
