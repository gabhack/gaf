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
    /**
     * Guarda un crédito junto con sus carteras y el documento (volante de pago) obligatorio.
     */
    public function store(Request $request)
    {
        // Validación
        $request->validate([
            'doc'          => 'required|string|max:20',
            'name'         => 'required|string|max:255',
            'client_type'  => 'required|string|max:50',
            'pagaduria_id' => 'required|integer',
            'cuota'        => 'required|numeric|min:0',
            'monto'        => 'required|numeric|min:0',
            'tasa'         => 'required|numeric|min:0',
            'plazo'        => 'required|integer|min:1',
            'volante'      => 'required|file|mimes:pdf,jpg,jpeg,png', // Volante de pago
        ]);

        try {
            DB::beginTransaction();

            // 1. Crear el registro principal de crédito
            $credit = new CreditRequest();
            $credit->doc          = $request->input('doc');
            $credit->name         = $request->input('name');
            $credit->client_type  = $request->input('client_type');
            $credit->pagaduria_id = $request->input('pagaduria_id');
            $credit->cuota        = $request->input('cuota');
            $credit->monto        = $request->input('monto');
            $credit->tasa         = $request->input('tasa');
            $credit->plazo        = $request->input('plazo');
            // Ejemplo de status por defecto (puedes ajustar a tu criterio):
            $credit->status       = 'pendiente';
            $credit->save();

            // 2. Guardar carteras asociadas
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

            // 3. Subir el documento (volante de pago) y guardarlo
            if ($request->hasFile('volante')) {
                $path = $request->file('volante')->store('public/documents');

                $document = new CreditDocument();
                $document->credit_request_id = $credit->id;
                $document->file_path         = $path;
                $document->save();
            }

            $credit->load('carteras', 'documents');
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
     * Retorna la vista principal (puedes ajustar la ruta de tu Blade según tu estructura).
     */
    public function index()
    {
        return view('CreditRequest.index');
    }

    /**
     * Retorna todos los créditos (con carteras y documentos),
     * omitiendo los que tengan status 'visado' (según el ejemplo original).
     */
    public function getAll()
    {
        $credits = CreditRequest::with(['carteras', 'documents'])
            ->where('status', '!=', 'visado')
            ->orderBy('updated_at', 'DESC')
            ->get();

        foreach ($credits as $credit) {
            Log::info('Carteras del crédito ID ' . $credit->id . ': ', $credit->carteras->toArray());
        }

        return response()->json($credits);
    }

    /**
     * Actualiza el estado de un crédito a 'aprobado' (si lo necesitaras; 
     * en tu solicitud se dejó de usar el botón, pero se mantiene el método por si acaso).
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
     * Marcar como visado (si se necesitase).
     */
    public function markAsVisado($id)
    {
        Log::info("markAsVisado llamado con ID: " . $id);

        try {
            $credit = CreditRequest::findOrFail($id);
            $credit->status = 'visado';
            $credit->save();

            return response()->json([
                'message' => 'Crédito marcado como visado exitosamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al marcar el crédito como visado',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
