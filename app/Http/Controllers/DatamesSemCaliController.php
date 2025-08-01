<?php

namespace App\Http\Controllers;

use App\DatamesSemCali;
use Illuminate\Http\Request;

class DatamesSemCaliController extends Controller
{
    public function consultaUnitaria(Request $request)
    {
        $data_formulario = $request->data;
        $doc = $request->doc;
        $consulta_cedula = DatamesSemCali::where('doc', $doc)->first();
        $resultados = json_decode($consulta_cedula);

        if ($resultados == "" or $resultados == null) {
            return response()->json([
                'message' => 'No se encontraron registros con el numero seleccionado.',
                'data' => $resultados
            ], 200);
        } else {
            return response()->json(['message' => 'Consulta exitosa.', 'data' => $resultados], 200);
        }
    }

    public function dumpDatamesSemCali()
    {
        DatamesSemCali::truncate();
        return response()->json(['message' => 'Datos de tabla DatamesSemCali Borrada'], 200);
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\DatamesSemCali $datamesSemCali
     * @return \Illuminate\Http\Response
     */
    public function show($doc)
    {
        $datamesSemCali = DatamesSemCali::where('doc', $doc)->get();
        return response()->json($datamesSemCali);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\DatamesSemCali $datamesSemCali
     * @return \Illuminate\Http\Response
     */
    public function edit(DatamesSemCali $datamesSemCali)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\DatamesSemCali $datamesSemCali
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DatamesSemCali $datamesSemCali)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\DatamesSemCali $datamesSemCali
     * @return \Illuminate\Http\Response
     */
    public function destroy(DatamesSemCali $datamesSemCali)
    {
        //
    }
}
