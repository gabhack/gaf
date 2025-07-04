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

class CreditRequestController extends Controller
{
    /* ------------------------------------------------ PAGADURÍAS --------------------------------------------- */
    protected static $pagaduriaMap = [
        'sed amazonas'                 => 1,
        'sed antioquia'                => 130,
        'sem armenia'                  => 34,
        'sed arauca'                   => 109,
        'sed atlantico'                => 121,
        'sem barrancabermeja'          => 160,
        'sem barranquilla'             => 106,
        'sem bello'                    => 111,
        'sed bolivar'                  => 293,
        'sed boyaca'                   => 110,
        'sem bucaramanga'              => 39,
        'sem buenaventura'             => 40,
        'sem buga'                     => 157,
        'sed caldas'                   => 139,
        'sem cali'                     => 42,
        'sed caqueta'                  => 140,
        'sed casanare'                 => 104,
        'sem cartagena'                => 189,
        'sem cartago'                  => 136,
        'sed cauca'                    => 177,
        'sem chia'                     => 45,
        'sem cienaga'                  => 103,
        'sed cesar'                    => 11,
        'sem cucuta'                   => 286,
        'sed choco'                    => 294,
        'sed cordoba'                  => 182,
        'sed cundinamarca'             => 163,
        'sem dosquebradas'             => 112,
        'sem duitama'                  => 49,
        'sem envigado'                 => 115,
        'sem estrella'                 => 168,
        'sem facatativa'               => 164,
        'sem florencia'                => 55,
        'sem floridablanca'            => 170,
        'sem funza'                    => 117,
        'sem fusagasuga'               => 151,
        'sem girardot'                 => 179,
        'sem giron'                    => 287,
        'sem guainia'                  => 116,
        'sed guajira'                  => 192,
        'sed guaviare'                 => 173,
        'sed huila'                    => 178,
        'sem ibague'                   => 147,
        'sem ipiales'                  => 134,
        'sem itagui'                   => 135,
        'sem jamundi'                  => 146,
        'sem lorica'                   => 67,
        'sed magdalena'                => 145,
        'sem magangue'                 => 133,
        'sem maicao'                   => 69,
        'sem malambo'                  => 161,
        'sed meta'                     => 113,
        'sem manizales'                => 174,
        'sem medellin'                 => 180,
        'sem monteria'                 => 176,
        'sem mosquera'                 => 153,
        'sem neiva'                    => 105,
        'sed narino'                   => 143,
        'sed norte de santander'       => 154,
        'sem palmira'                  => 152,
        'sem pasto'                    => 125,
        'sem pereira'                  => 78,
        'sem piedecuesta'              => 79,
        'sem pitalito'                 => 138,
        'sed putumayo'                 => 184,
        'sed quindio'                  => 166,
        'sem quibdo'                   => 162,
        'sem riohacha'                 => 150,
        'sem rionegro'                 => 129,
        'sed risaralda'                => 114,
        'sed santander'                => 26,
        'sem sabaneta'                 => 108,
        'sem sahagun'                  => 142,
        'sem san andres'                      => 158,
        'sem santa marta'              => 126,
        'sed sucre'                    => 175,
        'sem soacha'                   => 119,
        'sem sogamoso'                 => 172,
        'sem soledad'                  => 123,
        'sed tolima'                   => 122,
        'sem tulua'                    => 120,
        'sem tunja'                    => 141,
        'sem turbo'                    => 137,
        'sem tumaco'                   => 93,
        'sem uribia'                   => 144,
        'sed valle'                    => 165,
        'sem valledupar'               => 171,
        'sed vaupes'                   => 132,
        'sed vichada'                  => 32,
        'sem villavicencio'            => 124,
        'sed sincelejo'                => 27,
        'sem yopal'                    => 289,
        'sem yumbo'                    => 169,
        'sem zipaquira'                => 156,
        'colpensiones'                 => 200,
        'fopep'                        => 201,
        'casur'                        => 296,
        'fiduprevisora'                => 297,
    ];

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
