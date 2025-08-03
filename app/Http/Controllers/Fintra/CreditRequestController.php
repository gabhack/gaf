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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Helpers\PagaduriaHelper;

class CreditRequestController extends Controller
{

    public function __construct()
    {
        if (empty(self::$pagaduriasMap)) {
            self::$pagaduriasMap = PagaduriaHelper::map();
        }
    }
    /* ------------------------------------------------ PAGADURÍAS --------------------------------------------- */
    private static array $pagaduriasMap = [];

    private static function loadPagadurias(): void
{
    if (empty(self::$pagaduriasMap)) {
        self::$pagaduriasMap = PagaduriaHelper::map();
    }
}


    protected function mapPagaduria($value)
    {
        if (is_numeric($value)) {
            return (int) $value;
        }
        $key = trim(mb_strtolower($value));
        return self::$pagaduriaMap[$key] ?? 0;
    }

    /* -----------------------------------------------  SAVE INDIVIDUAL ----------------------------------------- */
    public function store(Request $request)
    {
        Log::info('store-in', $request->all());

        $request->validate([
            'doc'          => 'required|string|max:20',
            'name'         => 'required|string|max:255',
            'client_type'  => 'required|string|max:50',
            'pagaduria_id' => 'required',
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
            'carteras.*.opera_x_desprendible' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            $credit = CreditRequest::create([
                'doc'          => $request->doc,
                'name'         => $request->name,
                'client_type'  => $request->client_type,
                'pagaduria_id' => $this->mapPagaduria($request->pagaduria_id),
                'cuota'        => $request->cuota,
                'monto'        => $request->monto,
                'tasa'         => $request->tasa,
                'plazo'        => $request->plazo,
                'status'       => 'pendiente',
                'tipo_credito' => $request->tipo_credito,
                'user_id'      => Auth::id(),
                'tipo_pension' => $request->tipo_pension,
                'resolucion'   => $request->resolucion,
            ]);

            foreach ($request->input('carteras', []) as $c) {
                CreditCartera::create([
                    'credit_request_id'    => $credit->id,
                    'valor_cuota'          => $c['valor_cuota'] ?? 0,
                    'saldo'                => $c['saldo'] ?? 0,
                    'tipo_cartera'         => $c['tipo_cartera'] ?? null,
                    'nombre_entidad'       => $c['nombre_entidad'] ?? null,
                    'opera_x_desprendible' => ! empty($c['opera_x_desprendible']),
                ]);
            }

            DB::commit();
            Log::info('store-ok', ['id' => $credit->id]);
            return response()->json(['message' => 'Crédito guardado', 'data' => ['id' => $credit->id]], 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('store-error', ['e' => $e->getMessage()]);
            return response()->json(['message' => 'Error', 'error' => $e->getMessage()], 500);
        }
    }

    /* ----------------------------------------  CARGA MASIVA --------------------------------------------------- */
    public function bulkStore(Request $request)
    {
        Log::info('bulk-in', $request->all());

        $rows = $request->validate([
            'rows'                             => 'required|array|min:1',
            'rows.*.doc'                       => 'required|string|max:20',
            'rows.*.name'                      => 'required|string|max:255',
            'rows.*.client_type'               => 'required|string|max:50',
            'rows.*.pagaduria_id'              => 'required',
            'rows.*.cuota'                     => 'required|numeric|min:0',
            'rows.*.monto'                     => 'required|numeric|min:0',
            'rows.*.tasa'                      => 'required|numeric|min:0',
            'rows.*.plazo'                     => 'required|integer|min:1',
            'rows.*.tipo_credito'              => 'required|string|max:50',
            'rows.*.tipo_pension'              => 'nullable|string|max:100',
            'rows.*.resolucion'                => 'nullable|string|max:255',
            'rows.*.carteras'                  => 'nullable|array',
            'rows.*.carteras.*.tipo_cartera'         => 'nullable|string|max:50',
            'rows.*.carteras.*.nombre_entidad'       => 'nullable|string|max:255',
            'rows.*.carteras.*.valor_cuota'          => 'nullable|numeric|min:0',
            'rows.*.carteras.*.saldo'                => 'nullable|numeric|min:0',
            'rows.*.carteras.*.opera_x_desprendible' => 'boolean',
            'rows.*.docs'                       => 'nullable|array',
            'rows.*.docs.*.file_path'           => 'required_with:rows.*.docs|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            /*  NO se invierte el orden: se insertan tal y como vienen                                    */
            foreach ($rows['rows'] as $r) {

                $credit = CreditRequest::create([
                    'doc'          => $r['doc'],
                    'name'         => $r['name'],
                    'client_type'  => $r['client_type'],
                    'pagaduria_id' => $this->mapPagaduria($r['pagaduria_id']),
                    'cuota'        => $r['cuota'],
                    'monto'        => $r['monto'],
                    'tasa'         => $r['tasa'],
                    'plazo'        => $r['plazo'],
                    'status'       => 'pendiente',
                    'tipo_credito' => $r['tipo_credito'],
                    'user_id'      => Auth::id(),
                    'tipo_pension' => $r['tipo_pension'] ?? null,
                    'resolucion'   => $r['resolucion']   ?? null,
                ]);

                if (! empty($r['carteras'])) {
                    foreach ($r['carteras'] as $c) {
                        CreditCartera::create([
                            'credit_request_id'    => $credit->id,
                            'tipo_cartera'         => $c['tipo_cartera']   ?? null,
                            'nombre_entidad'       => $c['nombre_entidad'] ?? null,
                            'valor_cuota'          => $c['valor_cuota']    ?? 0,
                            'saldo'                => $c['saldo']          ?? 0,
                            'opera_x_desprendible' => ! empty($c['opera_x_desprendible']),
                        ]);
                    }
                }

                if (! empty($r['docs'])) {
                    foreach ($r['docs'] as $d) {
                        CreditDocument::create([
                            'credit_request_id' => $credit->id,
                            'file_path'         => $d['file_path'],
                        ]);
                    }
                }
            }

            DB::commit();
            return response()->json(['message' => 'Carga masiva completada.'], 201);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('bulk-error', ['e' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /* -------------------------------------------  RESTO MÉTODOS  --------------------------------------------- */
    public function uploadDocument($id, Request $request)
    {
        if (! $request->hasFile('archivo')) {
            return response()->json(['error' => 'No se recibió archivo'], 400);
        }

        $request->validate(['archivo' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240']);

        $credit = CreditRequest::find($id);
        if (! $credit) {
            return response()->json(['error' => 'Crédito no encontrado'], 404);
        }

        $path = $request->file('archivo')->store('public/documents');
        $doc  = CreditDocument::create(['credit_request_id' => $credit->id, 'file_path' => $path]);

        Log::info('upload-doc', ['credit_id' => $id, 'doc_id' => $doc->id]);
        return response()->json(['message' => 'Documento subido', 'doc_id' => $doc->id], 201);
    }

    public function index()
    {
        return view('CreditRequest.index');
    }

    public function getAll()
    {
        $user = Auth::user();

        $credits = CreditRequest::with([
                'visado:id,causal',
                'documents:id,credit_request_id,file_path',
                'carteras:credit_request_id,tipo_cartera,nombre_entidad,valor_cuota,saldo,opera_x_desprendible',
            ])
            ->when(! $user || $user->role_id !== 1, function ($q) use ($user) {
                return $q->where('user_id', $user->id);
            })
            ->orderByDesc('updated_at')
            ->get();

        $empresas = Comercial::with('empresa')
            ->whereIn('user_id', $credits->pluck('user_id')->unique())
            ->get()
            ->keyBy('user_id');

        $credits->transform(function ($c) use ($empresas) {
            $c->empresa = optional(optional($empresas[$c->user_id] ?? null)->empresa)->nombre;
            $c->causal  = optional($c->visado)->causal;
            return $c;
        });

        return response()->json($credits);
    }

    public function uploadVisadoPdf($id, Request $request)
    {
        Log::info('uploadVisadoPdf-in', ['credit_id' => $id]);
    
        $request->validate(['archivo' => 'required|file|mimes:pdf|max:20480']);
        $credit = CreditRequest::findOrFail($id);
    
        $path = $request->file('archivo')->store('public/visados');
        Log::info('uploadVisadoPdf-stored', ['path' => $path]);
    
        $url = Storage::url($path);
        $credit->forceFill(['pdf_path' => $url])->save();
    
        Log::info('uploadVisadoPdf-saved', ['url' => $url, 'credit_id' => $credit->id]);
        return response()->json(['url' => $url], 200);
    }
    

public function bulkForm()
{
    return view('CreditRequest.CreditRequestBulk');
}

public function updateStatus($id, Request $request)
{
    Log::info('updateStatus-in', ['credit_id' => $id, 'payload' => $request->all()]);

    $credit = CreditRequest::findOrFail($id);
    $credit->status    = $request->status;
    $credit->visado_id = $request->visado_id ?? $credit->visado_id;
    $credit->save();

    Log::info('updateStatus-saved', [
        'credit_id' => $credit->id,
        'status'    => $credit->status,
        'visado_id' => $credit->visado_id
    ]);

    return response()->json(['message' => 'Estado actualizado'], 200);
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
