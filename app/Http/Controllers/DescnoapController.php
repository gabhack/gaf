<?php

namespace App\Http\Controllers;

use App\Descnoap;
use App\Imports\DescnoapImport;
use Illuminate\Http\Request;
use Excel;

class DescnoapController extends Controller
{
    public function import (Request $request){
      set_time_limit(0);
      if($request->hasFile('file')){
        $path = $request->file('file')->getRealPath();
        $data = Excel::import(new DescnoapImport, request()->file('file'));
        return response()->json(['message'=>'Importación Realizada'],200);
      }else{
        return response()->json(['message'=>'Debe Seleccionar un archivo'],400);
      }
    }
    public function consultaUnitaria(Request $request){
      $consulta_cedula = \App\Descnoap::Where('doc',$request->doc)->get();
      $resultados = json_decode($consulta_cedula);
      if($resultados == "" or $resultados == null){
        return response()->json(['message'=>'No se encontraron registros con el numero seleccionado.'],400);
      }
      else{
        foreach ($resultados as $resultado) {
          $incon = $resultado->incon;
        }
        if($incon != null or $incon != ""){
          return response()->json(['message'=>'El cliente seleccionado tiene inconsistencias.','data'=>$resultados],200);
        }
        else{
          return response()->json(['message'=>'Consulta exitosa.','data'=>$resultados],200);
        }
      }
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
     * @param  \App\Descnoap  $descnoap
     * @return \Illuminate\Http\Response
     */
    public function show(Descnoap $descnoap)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Descnoap  $descnoap
     * @return \Illuminate\Http\Response
     */
    public function edit(Descnoap $descnoap)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Descnoap  $descnoap
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Descnoap $descnoap)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Descnoap  $descnoap
     * @return \Illuminate\Http\Response
     */
    public function destroy(Descnoap $descnoap)
    {
        //
    }
}
