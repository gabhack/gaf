<?php

namespace App\Http\Controllers\Fintra;

use App\Http\Controllers\Controller;
use App\CreditRequest;
use App\CreditCartera;
use App\CreditDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CreditRequestController extends Controller
{
    public function store(Request $request)
    {
        Log::info('store() - Ingreso');
        Log::info('Datos recibidos:', $request->all());

        $request->validate([
            'doc'          => 'required|string|max:20',
            'name'         => 'required|string|max:255',
            'client_type'  => 'required|string|max:50',
            'pagaduria_id' => 'required|integer',
            'cuota'        => 'required|numeric|min:0',
            'monto'        => 'required|numeric|min:0',
            'tasa'         => 'required|numeric|min:0',
            'plazo'        => 'required|integer|min:1',
            'tipo_credito' => 'required|string|max:50'
        ]);

        try {
            DB::beginTransaction();
            Log::info('Creando nuevo CreditRequest');

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
            $credit->user_id      = Auth::id();
            $credit->save();

            Log::info('CreditRequest creado con id=' . $credit->id);

            if ($request->has('carteras') && is_array($request->carteras)) {
                Log::info('Guardando carteras:', $request->carteras);
                foreach ($request->carteras as $carItem) {
                    $cartera                       = new CreditCartera();
                    $cartera->credit_request_id    = $credit->id;
                    $cartera->valor_cuota          = $carItem['valor_cuota'] ?? 0;
                    $cartera->saldo                = $carItem['saldo'] ?? 0;
                    $cartera->tipo_cartera         = $carItem['tipo_cartera'] ?? null;
                    $cartera->nombre_entidad       = $carItem['nombre_entidad'] ?? null;
                    $cartera->opera_x_desprendible = !empty($carItem['opera_x_desprendible']);
                    $cartera->save();
                }
            }

            DB::commit();
            return response()->json([
                'message' => 'Crédito guardado exitosamente.',
                'data'    => ['id' => $credit->id]
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al guardar el crédito: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al guardar el crédito',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function uploadDocument($id, Request $request)
    {
        Log::info('uploadDocument() - Ingreso');
        Log::info('Datos recibidos:', $request->all());

        if (!$request->hasFile('archivo')) {
            Log::error('No se recibió ningún archivo');
            return response()->json(['error' => 'No se recibió ningún archivo'], 400);
        }

        $request->validate([
            'archivo' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240'
        ]);

        $credit = CreditRequest::find($id);
        if (!$credit) {
            Log::error('Crédito no encontrado id=' . $id);
            return response()->json(['error' => 'Crédito no encontrado'], 404);
        }

        $path = $request->file('archivo')->store('public/documents');
        Log::info('Archivo guardado en: ' . $path);

        $document                     = new CreditDocument();
        $document->credit_request_id  = $credit->id;
        $document->file_path          = $path;
        $document->save();

        Log::info('Documento registrado en la BD con id=' . $document->id);

        return response()->json([
            'message'    => 'Documento subido correctamente',
            'doc_id'     => $document->id,
            'file_path'  => $path
        ], 201);
    }

    public function index()
    {
        return view('CreditRequest.index');
    }

    public function getAll()
    {
        Log::info('getAll() - Ingreso');
        $user = Auth::user();

        $query = CreditRequest::with(['carteras', 'documents'])
            ->where('status', '!=', 'visado');

        if (!$user || $user->role_id !== 1) {
            $query->where('user_id', $user->id);
            Log::info('Filtrando por usuario id=' . $user->id);
        } else {
            Log::info('Usuario admin, mostrando todos los registros');
        }

        $credits = $query->orderBy('updated_at', 'DESC')->get();
        Log::info('Total registros: ' . $credits->count());

        return response()->json($credits);
    }

    public function updateStatus($id)
    {
        Log::info('updateStatus() - Ingreso id=' . $id);
        try {
            $credit         = CreditRequest::findOrFail($id);
            $credit->status = 'aprobado';
            $credit->save();
            Log::info('Solicitud aprobada id=' . $id);
            return response()->json(['message' => 'Solicitud aprobada exitosamente'], 200);
        } catch (\Exception $e) {
            Log::error('Error al aprobar: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al aprobar la solicitud',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function markAsVisado($id)
    {
        Log::info('markAsVisado() - Ingreso id=' . $id);
        try {
            $credit         = CreditRequest::findOrFail($id);
            $credit->status = 'visado';
            $credit->save();
            Log::info('Crédito marcado como visado id=' . $id);
            return response()->json([
                'message' => 'Crédito marcado como visado exitosamente'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error al marcar como visado: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al marcar el crédito como visado',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
