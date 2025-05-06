<?php

namespace App\Http\Controllers\Fintra;

use App\Http\Controllers\Controller;
use App\CreditRequest;
use App\CreditCartera;
use App\CreditDocument;
use App\Comercial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CreditRequestController extends Controller
{
    public function store(Request $request)
    {
        Log::info('store - inicio', $request->all());

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
            'tipo_pension' => 'nullable|string|max:100',
            'resolucion'   => 'nullable|string|max:255'
        ]);

        DB::beginTransaction();
        try {
            $credit = new CreditRequest([
                'doc'          => $request->doc,
                'name'         => $request->name,
                'client_type'  => $request->client_type,
                'pagaduria_id' => $request->pagaduria_id,
                'cuota'        => $request->cuota,
                'monto'        => $request->monto,
                'tasa'         => $request->tasa,
                'plazo'        => $request->plazo,
                'status'       => 'pendiente',
                'tipo_credito' => $request->tipo_credito,
                'user_id'      => Auth::id(),
                'tipo_pension' => $request->tipo_pension,
                'resolucion'   => $request->resolucion
            ]);
            $credit->save();
            Log::info('store - credit id', ['id' => $credit->id]);

            if ($request->filled('carteras')) {
                foreach ($request->carteras as $item) {
                    CreditCartera::create([
                        'credit_request_id'    => $credit->id,
                        'valor_cuota'          => $item['valor_cuota'] ?? 0,
                        'saldo'                => $item['saldo'] ?? 0,
                        'tipo_cartera'         => $item['tipo_cartera'] ?? null,
                        'nombre_entidad'       => $item['nombre_entidad'] ?? null,
                        'opera_x_desprendible' => !empty($item['opera_x_desprendible']),
                    ]);
                }
            }

            DB::commit();
            Log::info('store - fin');
            return response()->json(['message' => 'Crédito guardado', 'data' => ['id' => $credit->id]], 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('store - error', ['e' => $e->getMessage()]);
            return response()->json(['message' => 'Error al guardar', 'error' => $e->getMessage()], 500);
        }
    }

    public function uploadDocument($id, Request $request)
    {
        Log::info('uploadDocument - inicio', ['credit_id' => $id]);

        if (!$request->hasFile('archivo')) {
            return response()->json(['error' => 'No se recibió archivo'], 400);
        }

        $request->validate([
            'archivo' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240'
        ]);

        $credit = CreditRequest::find($id);
        if (!$credit) {
            return response()->json(['error' => 'Crédito no encontrado'], 404);
        }

        $path = $request->file('archivo')->store('public/documents');
        $doc  = CreditDocument::create([
            'credit_request_id' => $credit->id,
            'file_path'         => $path
        ]);

        Log::info('uploadDocument - doc id', ['doc_id' => $doc->id]);
        return response()->json(['message' => 'Documento subido', 'doc_id' => $doc->id], 201);
    }

    public function index()
    {
        return view('CreditRequest.index');
    }

    public function getAll()
{
    Log::info('getAll - inicio');

    $user = Auth::user();
    Log::info('getAll - user', ['id' => $user->id ?? null, 'role_id' => $user->role_id ?? null]);

    $credits = CreditRequest::with('visado') // Se carga la relación visado
        ->where('status', '!=', 'visado')
        ->when(!$user || $user->role_id !== 1, function ($q) use ($user) {
            return $q->where('user_id', $user->id);
        })
        ->orderBy('updated_at', 'DESC')
        ->get();

    Log::info('getAll - credits obtenidos', ['total' => $credits->count()]);

    $ids = $credits->pluck('user_id')->unique();
    Log::info('getAll - user_ids únicos', $ids->all());

    $comerciales = Comercial::with('empresa')
        ->whereIn('user_id', $ids)
        ->get()
        ->keyBy('user_id');

    Log::info('getAll - comerciales encontrados', ['total' => $comerciales->count()]);

    $credits->transform(function ($c) use ($comerciales) {
        $c->empresa = optional(optional($comerciales[$c->user_id] ?? null)->empresa)->nombre;
        return $c;
    });

    Log::info('getAll - fin');
    return response()->json($credits);
}


    public function updateStatus($id, Request $request)
{
    Log::info('updateStatus - inicio', [
        'id' => $id,
        'request_data' => $request->all()
    ]);

    try {
        $credit = CreditRequest::findOrFail($id);
        $credit->status = $request->status ?? 'aprobado';

        if ($request->has('visado_id')) {
            $credit->visado_id = $request->visado_id;
            Log::info('updateStatus - visado_id asignado', ['visado_id' => $request->visado_id]);
        }

        $credit->save();

        Log::info('updateStatus - actualizado', [
            'id' => $credit->id,
            'status' => $credit->status,
            'visado_id' => $credit->visado_id
        ]);

        return response()->json(['message' => 'Estado actualizado'], 200);

    } catch (\Throwable $e) {
        Log::error('updateStatus - error', ['id' => $id, 'e' => $e->getMessage()]);
        return response()->json(['message' => 'Error', 'error' => $e->getMessage()], 500);
    }
}


    public function markAsVisado($id)
    {
        Log::info('markAsVisado - inicio', ['id' => $id]);
        try {
            $credit         = CreditRequest::findOrFail($id);
            $credit->status = 'visado';
            $credit->save();
            Log::info('markAsVisado - visado', ['id' => $id]);
            return response()->json(['message' => 'Crédito visado'], 200);
        } catch (\Throwable $e) {
            Log::error('markAsVisado - error', ['id' => $id, 'e' => $e->getMessage()]);
            return response()->json(['message' => 'Error al visar', 'error' => $e->getMessage()], 500);
        }
    }
}
