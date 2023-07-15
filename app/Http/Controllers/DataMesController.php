<?php

namespace App\Http\Controllers;

use App\DatamesFopep;
use App\Imports\DataMesImport;
use Illuminate\Http\Request;
use Excel;

class DataMesController extends Controller
{
    public function import(Request $request)
    {
        set_time_limit(0);
        if ($request->hasFile('file')) {
            $path = $request->file('file')->getRealPath();
            $data = Excel::import(new DataMesImport, request()->file('file'));
            return response()->json(['message' => 'Importación Realizada'], 200);
        } else {
            return response()->json(['message' => 'Debe Seleccionar un archivo'], 400);
        }
    }

    public function dumpDataMes()
    {
        DatamesFopep::truncate();
        return response()->json(['message' => 'Datos de tabla DatamesFopep Borrada'], 200);
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
     * @param  \App\DatamesFopep  $datamesFopep
     * @return \Illuminate\Http\Response
     */
    public function show($doc)
    {
        $datames = DatamesFopep::where('doc', $doc)->first();
        return response()->json($datames);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DatamesFopep  $datamesFopep
     * @return \Illuminate\Http\Response
     */
    public function edit(DatamesFopep $datamesFopep)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DatamesFopep  $datamesFopep
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DatamesFopep $datamesFopep)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DatamesFopep  $datamesFopep
     * @return \Illuminate\Http\Response
     */
    public function destroy(DatamesFopep $datamesFopep)
    {
        //
    }
}
