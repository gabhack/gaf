<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Traits\ParsesPgTzDates;
use App\Helpers\PagaduriaHelper;
use App\PanelPagaduria;
use App\Pagadurias;
use App\DatamesGen;

class PagaduriasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $lista = Pagadurias::all();
        return view('pagadurias/index')->with(['lista' => $lista]);
    }

    public function create()
    {
        return view('pagadurias/crear');
    }

    public function store(Request $request)
    {
        $pagaduria = new Pagadurias();
        $pagaduria->codigo     = strtoupper(str_replace(' ', '_', $request->input('pagaduria')));
        $pagaduria->pagaduria  = strtoupper($request->input('pagaduria'));
        $pagaduria->save();
        return redirect('pagadurias');
    }

    public function show($id) {}

    public function edit($id)
    {
        $pagaduria = Pagadurias::find($id);
        return view('pagadurias/editar')->with(['pagaduria' => $pagaduria]);
    }

    public function update(Request $request, $id)
    {
        $pagaduria             = Pagadurias::find($id);
        $pagaduria->pagaduria  = strtoupper($request->input('pagaduria'));
        $pagaduria->save();
        return redirect('pagadurias');
    }

    public function destroy($id)
    {
        Pagadurias::find($id)->delete();
        return redirect('pagadurias');
    }

    public function perDoc($doc)
    {
        $startTotal = microtime(true);
        Log::info('Inició búsqueda por documento', ['doc' => $doc]);

        $user     = Auth::user();
        $userType = IsCompany() ? 'empresa' : (IsComercial() ? 'comercial' : null);

        if ($userType && $user->$userType->consultas_diarias <= 0) {
            Log::warning('Usuario sin consultas disponibles', ['user_id' => $user->id, 'tipo' => $userType]);
            return response()->json(['message' => 'No tienes consultas disponibles'], 400);
        }

        DB::beginTransaction();
        try {
            $results = [];

            $latestIds = DatamesGen::select('pagaduria', DB::raw('MAX(id) as id'))
                ->where('doc', $doc)
                ->groupBy('pagaduria')
                ->pluck('id', 'pagaduria');

            if ($latestIds->isNotEmpty()) {
                $items = DatamesGen::whereIn('id', $latestIds->values())->get()->keyBy('pagaduria');
                foreach ($items as $pagaduria => $item) {
                    $panel = PanelPagaduria::where('nombre', $pagaduria)->first();
                    if ($panel) {
                        $item->panel_id = $panel->id;
                    }
                    $results[$pagaduria] = $item;
                }
            }

            if ($userType) {
                $user->$userType->decrement('consultas_diarias');
            }

            DB::commit();

            $payload = !empty($results) ? $results : (object) [];

            Log::info('Resultados finales', [
                'total'     => count($results),
                'registros' => collect($results)->map(function ($x) {
                    return [
                        'pagaduria'     => $x->pagaduria,
                        'id'            => $x->id,
                        'panel_id'      => $x->panel_id ?? null,
                        'inicioperiodo' => $x->inicioperiodo,
                        'finperiodo'    => $x->finperiodo,
                    ];
                })->values()->all(),
            ]);

            Log::info('Tiempo total del método perDoc()', ['segundos' => microtime(true) - $startTotal]);
            return response()->json($payload, 200);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error al buscar pagadurías por documento', [
                'doc'       => $doc,
                'exception' => $e->getMessage(),
                'stack'     => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getPagaduriasNames()
    {
        $nombres = PanelPagaduria::orderBy('nombre')->pluck('nombre');
        return response()->json($nombres, 200);
    }

    public function getPagaduriasNamesAmi()
    {
        $nombres = PanelPagaduria::orderBy('nombre')->pluck('nombre');
        return response()->json($nombres, 200);
    }

    public function getSituacionLaboralByDoc($doc)
    {
        try {
            $data = DatamesGen::where('doc', 'like', "%{$doc}%")->latest('id')->first(['situacion_laboral']);
            if (!$data) {
                return response()->json(['mensaje' => 'No se encontró la situación laboral para el documento proporcionado.'], 404);
            }
            return response()->json($data->situacion_laboral, 200);
        } catch (\Exception $e) {
            Log::error('Error al buscar la situación laboral', ['doc' => $doc, 'e' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getSituacionLaboralByDocs(Request $request)
    {
        try {
            $documentos = $request->input('documentos', []);
            $situaciones = DatamesGen::whereIn('doc', $documentos)
                ->latest('id')
                ->get()
                ->keyBy('doc')
                ->map(function ($item) {
                    return $item->situacion_laboral;
                });
            if ($situaciones->isEmpty()) {
                return response()->json(['mensaje' => 'No se encontraron situaciones laborales.'], 404);
            }
            return response()->json($situaciones, 200);
        } catch (\Exception $e) {
            Log::error('Error al buscar situaciones laborales', ['e' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
