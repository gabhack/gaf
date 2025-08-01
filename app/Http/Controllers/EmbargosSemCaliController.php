<?php

namespace App\Http\Controllers;

use App\EmbargosSemCali;
use Illuminate\Http\Request;

class EmbargosSemCaliController extends Controller
{
    public function consultaUnitaria(Request $request)
    {
        $data_formulario = $request->data;
        $doc = $request->doc;

        // dd($doc);

        $consulta_cedula = EmbargosSemCali::where('doc', $doc)->get();
        $resultados = json_decode($consulta_cedula);

        if ($resultados == "" or $resultados == null) {
            return response()->json(['message' => 'No se encontraron registros con el numero seleccionado.', 'data' => $resultados], 200);
        } else {
            return response()->json(['message' => 'Consulta exitosa.', 'data' => $resultados], 200);
        }
    }

    public function dumpEmbargosSemCali()
    {
        EmbargosSemCali::truncate();
        return response()->json(['message' => 'Datos de tabla EmbargosSemCali Borrada'], 200);
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
     * @param  \App\EmbargosSemCali  $embargosSemCali
     * @return \Illuminate\Http\Response
     */
    public function show($doc)
    {
        $embargosSemCali = EmbargosSemCali::where('doc', $doc)->get();
        return response()->json($embargosSemCali);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EmbargosSemCali  $embargosSemCali
     * @return \Illuminate\Http\Response
     */
    public function edit(EmbargosSemCali $embargosSemCali)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EmbargosSemCali  $embargosSemCali
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmbargosSemCali $embargosSemCali)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EmbargosSemCali  $embargosSemCali
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmbargosSemCali $embargosSemCali)
    {
        //
    }
}
