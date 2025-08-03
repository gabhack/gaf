<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

use App\DatamesFidu;
use App\DatamesFopep;
use App\DatamesGen;
use App\Pagadurias;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PagaduriasController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('role:ADMIN_SISTEMA');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lista = Pagadurias::all();
        return view('pagadurias/index')->with(['lista' => $lista]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pagadurias/crear');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $pagaduria = new Pagadurias();
        $pagaduria->codigo = strtoupper(str_replace(' ', '_', $request->input('pagaduria')));
        $pagaduria->pagaduria = strtoupper($request->input('pagaduria'));
        $pagaduria->save();

        return redirect('pagadurias');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pagaduria = Pagadurias::find($id);
        return view('pagadurias/editar')->with(['pagaduria' => $pagaduria]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $pagaduria = Pagadurias::find($id);
        $pagaduria->pagaduria = strtoupper($request->input('pagaduria'));
        $pagaduria->save();

        return redirect('pagadurias');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Pagadurias::find($id)->delete();
        return redirect('pagadurias');
    }


    public function perDoc($doc)
    {
        Log::info("Entró a buscar la cédula en fast_coupons_pagaduria:", ['doc' => $doc]);

        try {
            // Define los modelos permitidos
            $models = [
                DatamesFidu::class => 'doc',
                DatamesFopep::class => 'doc',
            ];

            $results = [];

            // Consulta en los modelos permitidos
            foreach ($models as $model => $column) {
                $data = $model::where($column, 'LIKE', '%' . $doc . '%')
                    ->first();

                if ($data) {
                    $modelName = class_basename($model);
                    $results[Str::camel($modelName)] = $data;
                }
            }

            // Consulta en la vista materializada fast_coupons_pagaduria en la base de datos PostgreSQL
            Log::info("Consulta realizada a fast_coupons_pagaduria:", [
                'query' => \DB::connection('pgsql')->table('fast_coupons_pagaduria')
                    ->where('doc', '=', $doc)
                    ->toSql(),
                'bindings' => \DB::connection('pgsql')->table('fast_coupons_pagaduria')
                    ->where('doc', '=', $doc)
                    ->getBindings()
            ]);

            $fastCouponsData = \DB::connection('pgsql')->table('fast_coupons_pagaduria')
                ->where('doc', '=', $doc)
                ->get(['pagaduria']);

            Log::info("Resultados obtenidos de fast_coupons_pagaduria:", ['data' => $fastCouponsData]);

            if ($fastCouponsData->isNotEmpty()) {
                foreach ($fastCouponsData as $item) {
                    // Verifica si la pagaduría ya existe en $results
                    if (!isset($results[$item->pagaduria])) {
                        $results[$item->pagaduria] = $item;
                    }
                }
            }

            // Si no hay resultados, devuelve un objeto vacío
            $results = !empty($results) ? $results : (object) [];

            Log::info("Resultados finales:", ['results' => $results]);

            return response()->json($results, 200);
        } catch (\Exception $e) {
            Log::error("Error al buscar pagadurías por documento en fast_coupons_pagaduria: {$e->getMessage()}", [
                'doc' => $doc, // Incluye el documento para identificar la consulta que causó el error
                'exception' => $e->getTraceAsString(), // Para un diagnóstico más detallado
            ]);

            return response()->json([
                'error' => 'Ocurrió un error al procesar la solicitud',
                'message' => $e->getMessage(), // Mensaje exacto del error
            ], 500);
        }
    }

    public function getPagaduriasNames()
    {
        $nombres = Pagadurias::pluck('pagaduria');
        return response()->json($nombres, 200);
    }

    private function fetchPanelPagadurias(): \Illuminate\Support\Collection
    {
        return \DB::connection('pgsql')
            ->table('panel_pagaduria')
            ->select('nombre', 'nombre_largo', 'acronimo')
            ->orderBy('nombre')
            ->get();
    }
    
    public function getPagaduriasNamesAmi()
    {
        try {
            $rows = $this->fetchPanelPagadurias();
    
            // Si no hay nada, puedes caer en un fallback mínimo (vacío)
            if ($rows->isEmpty()) {
                return response()->json([], 200);
            }
    
            $nombres = $rows->pluck('nombre')
                ->map(fn($n) => mb_strtoupper($n))
                ->values()
                ->all();
    
            return response()->json($nombres, 200);
        } catch (\Exception $e) {
            Log::error('Error cargando pagadurias desde panel_pagaduria:', [
                'exception' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
            ]);
            return response()->json([], 200); 
        }
    }
    
    public function getPagaduriasPanel()
    {
        try {
            $rows = $this->fetchPanelPagadurias();
            return response()->json($rows, 200);
        } catch (\Exception $e) {
            Log::error('Error cargando panel_pagaduria full:', [
                'exception' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
            ]);
            return response()->json([], 200);
        }
    }
    

    public function getSituacionLaboralByDoc($doc)
    {
        try {
            Log::info("Consultando la situación laboral para el documento: {$doc}");

            $data = DatamesGen::where('doc', 'like', "%{$doc}%")
                ->latest('id')
                ->first(['situacion_laboral']);

            if (!$data) {
                Log::info("No se encontró la situación laboral para el documento proporcionado.", ['doc' => $doc]);
                return response()->json(['mensaje' => 'No se encontró la situación laboral para el documento proporcionado.'], 404);
            }

            Log::info("Situación laboral encontrada.", ['doc' => $doc, 'situacion_laboral' => $data->situacion_laboral]);

            return response()->json($data->situacion_laboral, 200);
        } catch (\Exception $e) {
            Log::error("Error al buscar la situación laboral por documento: {$doc}", [
                'doc' => $doc,
                'exception' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
            ]);

            return response()->json(['error' => 'Ocurrió un error al procesar la solicitud'], 500);
        }
    }

    public function getSituacionLaboralByDocs(Request $request)
    {
        try {
            $documentos = $request->input('documentos', []);
            Log::info("Consultando la situación laboral para múltiples documentos.");

            $situaciones = DatamesGen::whereIn('doc', $documentos)
                ->latest('id')
                ->get()
                ->keyBy('doc')  // Esto asume que 'doc' es único
                ->map(function ($item) {
                    return $item->situacion_laboral;
                });

            if ($situaciones->isEmpty()) {
                Log::info("No se encontraron situaciones laborales para los documentos proporcionados.");
                return response()->json(['mensaje' => 'No se encontraron situaciones laborales para los documentos proporcionados.'], 404);
            }

            Log::info("Situaciones laborales encontradas.", ['situaciones' => $situaciones]);

            return response()->json($situaciones, 200);
        } catch (\Exception $e) {
            Log::error("Error al buscar las situaciones laborales por documentos", [
                'documentos' => $documentos,
                'exception' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
            ]);

            return response()->json(['error' => 'Ocurrió un error al procesar la solicitud'], 500);
        }
    }
}
