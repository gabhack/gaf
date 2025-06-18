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
        Log::info('store-in', $request->all());

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
            'resolucion'   => 'nullable|string|max:255',
            'carteras'     => 'array',
            'carteras.*.tipo_cartera'   => 'nullable|string|max:50',
            'carteras.*.nombre_entidad' => 'nullable|string|max:255',
            'carteras.*.valor_cuota'    => 'nullable|numeric|min:0',
            'carteras.*.saldo'          => 'nullable|numeric|min:0',
            'carteras.*.opera_x_desprendible' => 'boolean'
        ]);

        DB::beginTransaction();
        try {
            $credit = CreditRequest::create([
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

            foreach ($request->input('carteras', []) as $c) {
                CreditCartera::create([
                    'credit_request_id'    => $credit->id,
                    'valor_cuota'          => $c['valor_cuota'] ?? 0,
                    'saldo'                => $c['saldo'] ?? 0,
                    'tipo_cartera'         => $c['tipo_cartera'] ?? null,
                    'nombre_entidad'       => $c['nombre_entidad'] ?? null,
                    'opera_x_desprendible' => !empty($c['opera_x_desprendible']),
                ]);
            }

            DB::commit();
            Log::info('store-ok', ['id'=>$credit->id]);
            return response()->json(['message'=>'Crédito guardado','data'=>['id'=>$credit->id]],201);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('store-error',['e'=>$e->getMessage()]);
            return response()->json(['message'=>'Error','error'=>$e->getMessage()],500);
        }
    }

    public function uploadDocument($id, Request $request)
    {
        if (!$request->hasFile('archivo')) return response()->json(['error'=>'No se recibió archivo'],400);

        $request->validate(['archivo'=>'required|file|mimes:pdf,jpg,jpeg,png|max:10240']);

        $credit = CreditRequest::find($id);
        if (!$credit) return response()->json(['error'=>'Crédito no encontrado'],404);

        $path = $request->file('archivo')->store('public/documents');
        $doc  = CreditDocument::create(['credit_request_id'=>$credit->id,'file_path'=>$path]);

        Log::info('upload-doc', ['credit_id'=>$id,'doc_id'=>$doc->id]);
        return response()->json(['message'=>'Documento subido','doc_id'=>$doc->id],201);
    }

    public function index(){ return view('CreditRequest.index'); }

    public function getAll()
    {
        Log::info('getAll - inicio');
        $user = Auth::user();
        Log::info('getAll - user', [
            'id'      => $user->id ?? null,
            'role_id' => $user->role_id ?? null,
        ]);
    
        $credits = CreditRequest::with([
                'visado:id,causal',
                'documents:id,credit_request_id,file_path',
                'carteras:credit_request_id,tipo_cartera,nombre_entidad,valor_cuota,saldo,opera_x_desprendible',
            ])
            ->where('status', '!=', 'visado')
            ->when(
                ! $user || $user->role_id !== 1,
                function ($q) use ($user) {
                    return $q->where('user_id', $user->id);
                }
            )
            ->orderBy('updated_at', 'desc')
            ->get();
    
        Log::info('getAll - credits obtenidos', [
            'total'          => $credits->count(),
            'con_visado'     => $credits->filter(function ($c) {
                return ! is_null($c->visado_id);
            })->count(),
            'con_documentos' => $credits->filter(function ($c) {
                return $c->documents->isNotEmpty();
            })->count(),
            'con_carteras'   => $credits->filter(function ($c) {
                return $c->carteras->isNotEmpty();
            })->count(),
        ]);
    
        $comerciales = Comercial::with('empresa')
            ->whereIn('user_id', $credits->pluck('user_id')->unique())
            ->get()
            ->keyBy('user_id');
    
        $credits->transform(function ($c) use ($comerciales) {
            $c->empresa = optional(optional($comerciales[$c->user_id] ?? null)->empresa)->nombre;
            $c->causal  = optional($c->visado)->causal;
            return $c;
        });
    
        Log::info('getAll - fin');
    
        return response()->json($credits);
    }
    
}
