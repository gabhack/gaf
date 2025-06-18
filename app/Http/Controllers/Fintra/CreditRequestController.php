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
        $user = Auth::user();
        $credits = CreditRequest::with([
                'visado:id,causal',
                'documents:id,credit_request_id,file_path',
                'carteras:credit_request_id,tipo_cartera,nombre_entidad,valor_cuota,saldo,opera_x_desprendible'
            ])
            ->where('status','!=','visado')
            ->when(!$user || $user->role_id!==1, fn($q)=>$q->where('user_id',$user->id))
            ->orderByDesc('updated_at')
            ->get();

        $empresas = Comercial::with('empresa')
            ->whereIn('user_id',$credits->pluck('user_id')->unique())
            ->get()->keyBy('user_id');

        $credits->transform(function($c)use($empresas){
            $c->empresa = optional(optional($empresas[$c->user_id]??null)->empresa)->nombre;
            $c->causal  = optional($c->visado)->causal;
            return $c;
        });

        return response()->json($credits);
    }

    public function updateStatus($id,Request $request)
    {
        try{
            $credit = CreditRequest::findOrFail($id);
            $credit->status = $request->status ?? 'aprobado';
            if($request->has('visado_id')) $credit->visado_id = $request->visado_id;
            $credit->save();
            return response()->json(['message'=>'Estado actualizado'],200);
        }catch(\Throwable $e){
            return response()->json(['message'=>'Error','error'=>$e->getMessage()],500);
        }
    }

    public function markAsVisado($id)
    {
        try{
            $c = CreditRequest::findOrFail($id);
            $c->status='visado';
            $c->save();
            return response()->json(['message'=>'Crédito visado'],200);
        }catch(\Throwable $e){
            return response()->json(['message'=>'Error','error'=>$e->getMessage()],500);
        }
    }

    public function bulkStore(Request $request)
    {
        Log::info('bulk-in', $request->all());

        $rows = $request->validate([
            'rows'                           => 'required|array|min:1',
            'rows.*.doc'                     => 'required|string|max:20',
            'rows.*.name'                    => 'required|string|max:255',
            'rows.*.client_type'             => 'required|string|max:50',
            'rows.*.pagaduria_id'            => 'required|integer',
            'rows.*.cuota'                   => 'required|numeric|min:0',
            'rows.*.monto'                   => 'required|numeric|min:0',
            'rows.*.tasa'                    => 'required|numeric|min:0',
            'rows.*.plazo'                   => 'required|integer|min:1',
            'rows.*.tipo_credito'            => 'required|string|max:50',
            'rows.*.tipo_pension'            => 'nullable|string|max:100',
            'rows.*.resolucion'              => 'nullable|string|max:255',
            'rows.*.carteras'                        => 'array',
            'rows.*.carteras.*.tipo_cartera'         => 'nullable|string|max:50',
            'rows.*.carteras.*.nombre_entidad'       => 'nullable|string|max:255',
            'rows.*.carteras.*.valor_cuota'          => 'nullable|numeric|min:0',
            'rows.*.carteras.*.saldo'                => 'nullable|numeric|min:0',
            'rows.*.carteras.*.opera_x_desprendible' => 'boolean',
            'rows.*.docs'                 => 'array',
            'rows.*.docs.*.file_path'     => 'required_with:rows.*.docs|string|max:255'
        ]);

        DB::beginTransaction();
        try{
            foreach($rows['rows'] as $idx=>$r){
                Log::info('bulk-row', ['index'=>$idx,'data'=>$r]);

                $credit = CreditRequest::create([
                    'doc'          => $r['doc'],
                    'name'         => $r['name'],
                    'client_type'  => $r['client_type'],
                    'pagaduria_id' => $r['pagaduria_id'],
                    'cuota'        => $r['cuota'],
                    'monto'        => $r['monto'],
                    'tasa'         => $r['tasa'],
                    'plazo'        => $r['plazo'],
                    'status'       => 'pendiente',
                    'tipo_credito' => $r['tipo_credito'],
                    'user_id'      => Auth::id(),
                    'tipo_pension' => $r['tipo_pension'] ?? null,
                    'resolucion'   => $r['resolucion']   ?? null
                ]);

                foreach($r['carteras'] ?? [] as $c){
                    CreditCartera::create([
                        'credit_request_id'    => $credit->id,
                        'valor_cuota'          => $c['valor_cuota'] ?? 0,
                        'saldo'                => $c['saldo'] ?? 0,
                        'tipo_cartera'         => $c['tipo_cartera'] ?? null,
                        'nombre_entidad'       => $c['nombre_entidad'] ?? null,
                        'opera_x_desprendible' => !empty($c['opera_x_desprendible']),
                    ]);
                }
                Log::info('bulk-row-carteras', ['row'=>$idx,'created'=>count($r['carteras'] ?? [])]);

                foreach($r['docs'] ?? [] as $d){
                    CreditDocument::create([
                        'credit_request_id'=>$credit->id,
                        'file_path'=>$d['file_path']
                    ]);
                }
                Log::info('bulk-row-docs', ['row'=>$idx,'created'=>count($r['docs'] ?? [])]);
            }
            DB::commit();
            Log::info('bulk-ok');
            return response()->json(['message'=>'Carga masiva completada.'],201);
        }catch(\Throwable $e){
            DB::rollBack();
            Log::error('bulk-error',['e'=>$e->getMessage()]);
            return response()->json(['error'=>$e->getMessage()],500);
        }
    }
}
