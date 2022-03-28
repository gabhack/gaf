<?php

namespace App\Http\Controllers;

use App\DataMes;
use App\Imports\DataMesImport;
use Illuminate\Http\Request;
use Excel;

class DataMesController extends Controller
{
  public function import (Request $request){
    set_time_limit(0);
    if($request->hasFile('file')){
      $path = $request->file('file')->getRealPath();
      $data = Excel::import(new DataMesImport, request()->file('file'));
      return response()->json(['message'=>'Importación Realizada'],200);
    }else{
      return response()->json(['message'=>'Debe Seleccionar un archivo'],400);
    }
  }
    public function dumpDataMes(){
        DataMes::truncate();
        return response()->json(['message'=>'Datos de tabla DataMes Borrada'],200);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
     * @param  \App\DataMes  $dataMes
     * @return \Illuminate\Http\Response
     */
    public function show(DataMes $dataMes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DataMes  $dataMes
     * @return \Illuminate\Http\Response
     */
    public function edit(DataMes $dataMes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DataMes  $dataMes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DataMes $dataMes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DataMes  $dataMes
     * @return \Illuminate\Http\Response
     */
    public function destroy(DataMes $dataMes)
    {
        //
    }
}
