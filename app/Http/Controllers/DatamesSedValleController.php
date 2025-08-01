<?php

namespace App\Http\Controllers;

use App\DatamesFopep;
use App\DatamesFidu;
use App\DatamesSemCali;
use App\DatamesSedValle;
use App\Imports\DatamesSedValleImport;
use Illuminate\Http\Request;
use Excel;

class DatamesSedValleController extends Controller
{
    public function import(Request $request)
    {
        set_time_limit(0);
        if ($request->hasFile('file')) {
            $path = $request->file('file')->getRealPath();
            $data = Excel::import(new DatamesSedValleImport, request()->file('file'));
            return response()->json(['message' => 'Importación Realizada'], 200);
        } else {
            return response()->json(['message' => 'Debe Seleccionar un archivo'], 400);
        }
    }

    public function consultaUnitaria(Request $request)
    {
        $data_formulario = $request->data;
        $doc = $request->doc;
        $consulta_cedula = DatamesSedValle::where('doc', $doc)->first();
        $resultados = json_decode($consulta_cedula);

        if ($resultados == "" or $resultados == null) {
            return response()->json(['message' => 'No se encontraron registros con el numero seleccionado.', 'data' => $resultados], 200);
        } else {
            return response()->json(['message' => 'Consulta exitosa.', 'data' => $resultados], 200);
        }
    }

    public function dumpDatamesSedValle()
    {
        DatamesSedValle::truncate();
        return response()->json(['message' => 'Datos de tabla DatamesSedValle Borrada'], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DatamesSedValle  $datamesSedValle
     * @return \Illuminate\Http\Response
     */
    public function show($doc)
    {
        $datamesSedValle = DatamesSedValle::where('doc', $doc)->get();
        return response()->json($datamesSedValle);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DatamesSedValle  $datamesSedValle
     * @return \Illuminate\Http\Response
     */
    public function edit(DatamesSedValle $datamesSedValle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DatamesSedValle  $datamesSedValle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DatamesSedValle $datamesSedValle)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DatamesSedValle  $DatamesSedValle
     * @return \Illuminate\Http\Response
     */
    public function destroy(DatamesSedValle $DatamesSedValle)
    {
        //
    }

    public function allPagadurias(Request $request)
    {
        $doc = $request->doc;
        $datamesFopep = DatamesFopep::where('doc', $doc)->first();
        $datamesSedValle = DatamesSedValle::where('doc', $doc)->first();
        $datamesFidu = DatamesFidu::where('doc', $doc)->first();
        $datamesSemCali = DatamesSemCali::where('doc', $doc)->first();

        $results = [
            'datamesFopep' => $datamesFopep,
            'datamesSedValle' => $datamesSedValle,
            'datamesFidu' => $datamesFidu,
            'datamesSemCali' => $datamesSemCali,
        ];

        return response()->json($results, 200);
    }
}
