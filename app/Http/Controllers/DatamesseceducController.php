<?php

namespace App\Http\Controllers;

use App\DataMes;
use App\Datamesfidu;
use App\Datamesseccali;
use App\Datamesseceduc;
use App\Imports\DatamesseceducImport;
use Illuminate\Http\Request;
use Excel;

class DatamesseceducController extends Controller
{
    public function import(Request $request)
    {
        set_time_limit(0);
        if ($request->hasFile('file')) {
            $path = $request->file('file')->getRealPath();
            $data = Excel::import(new DatamesseceducImport, request()->file('file'));
            return response()->json(['message' => 'Importación Realizada'], 200);
        } else {
            return response()->json(['message' => 'Debe Seleccionar un archivo'], 400);
        }
    }

    public function consultaUnitaria(Request $request)
    {
        $data_formulario = $request->data;
        $doc = $request->doc;
        $consulta_cedula = Datamesseceduc::where('doc', $doc)->first();
        $resultados = json_decode($consulta_cedula);
        if ($resultados == "" or $resultados == null) {
            return response()->json(['message' => 'No se encontraron registros con el numero seleccionado.', 'data' => $resultados], 200);
        } else {
            return response()->json(['message' => 'Consulta exitosa.', 'data' => $resultados], 200);
        }
    }

    public function dumpDatamesseceduc()
    {
        Datamesseceduc::truncate();

        return response()->json(['message' => 'Datos de tabla Datamesseceduc Borrada'], 200);
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
     * @param  \App\Datamesseceduc  $Datamesseceduc
     * @return \Illuminate\Http\Response
     */
    public function show($doc)
    {
        $Datamesseceduc = Datamesseceduc::where('doc', $doc)->get();
        return response()->json($Datamesseceduc);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Datamesseceduc  $Datamesseceduc
     * @return \Illuminate\Http\Response
     */
    public function edit(Datamesseceduc $Datamesseceduc)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Datamesseceduc  $Datamesseceduc
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Datamesseceduc $Datamesseceduc)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Datamesseceduc  $Datamesseceduc
     * @return \Illuminate\Http\Response
     */
    public function destroy(Datamesseceduc $Datamesseceduc)
    {
        //
    }

    public function allPagadurias(Request $request)
    {
        $doc = $request->doc;
        $datames = DataMes::where('doc', $doc)->first();
        $datamesseceduc = Datamesseceduc::where('doc', $doc)->first();
        $datamesfidu = Datamesfidu::where('doc', $doc)->first();
        $datamesseccali = Datamesseccali::where('doc', $doc)->first();

        $results = [
            'datames' => $datames,
            'datamesseceduc' => $datamesseceduc,
            'datamesfidu' => $datamesfidu,
            'datamesseccali' => $datamesseccali,
        ];

        return response()->json($results, 200);
    }
}
