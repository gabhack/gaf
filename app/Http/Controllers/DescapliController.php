<?php

namespace App\Http\Controllers;

use App\Descapli;
use App\DataMes;
use App\FechaVinc;
use App\Imports\DescapliImport;
use Illuminate\Http\Request;
use Excel;

class DescapliController extends Controller
{
  public function import (Request $request){
    set_time_limit(0);
    if($request->hasFile('file')){
      $path = $request->file('file')->getRealPath();
      $data = Excel::import(new DescapliImport, request()->file('file'));
      return response()->json(['message'=>'Importación Realizada'],200);
    }else{
      return response()->json(['message'=>'Debe Seleccionar un archivo'],400);
    }
  }


    public function consultaUnitaria(Request $request){
      $data_formulario = $request->data;
      $doc = $request->data['doc'];
      $consulta_cedula = \App\Descapli::Where('doc',$doc)->get();
      $resultados = json_decode($consulta_cedula);
      if($resultados == "" or $resultados == null ){
        return response()->json(['message'=>'No se encontraron registros con el numero seleccionado.', 'data'=>$resultados],200);
      }
      else{
        return response()->json(['message'=>'Consulta exitosa.','data'=>$resultados],200);
      }
    }
    public function resultadoAprobacion(Request $request){
      dd($request->all());
      $data_formulario = $request->data;
      $doc = $request->data['doc'];
      $info_datames = \App\DataMes::Where('doc',$doc)->select('cupo')->first();
      $info_fechavinc = \App\FechaVinc::Where('doc',$doc)->first();
      $cupo = $info_datames->cupo;
      $fecha_vinculacion = $info_fechavinc->vinc;
      $tipo_vinculacion = $info_fechavinc->tp;
      $array_entidad = $data_formulario['nomterSelect'];
      $array_entidad[]="CUPO";
      $array_pagare = $data_formulario['pagareSelected'];
      $array_cuota_compra = $data_formulario['v_aplicado'];
      $array_cuota_compra[]=$cupo;
      $data_formulario['consecutivo']="";
      $data_formulario['estado']="Consulta";
      $data_formulario['fecha_consulta']= new Date();
      $data_formulario['entidad']=$array_entidad;
      $data_formulario['pagare']=$array_pagare;
      $data_formulario['cuota_compra']=$array_cuota_compra;
      $data_formulario['aprobado']="APROBADO";
      $data_formulario['pct_incorporacion']="PCT";
      $data_formulario['fec_rta_consulta']= new Date();
      $data_formulario['fecha_vinculacion']=$fecha_vinculacion;
      $data_formulario['tipo_vinculacion']=$tipo_vinculacion;
      dd($data_formulario);
      return response()->json(['message'=>'Consulta exitosa.','data'=>$data_formulario],200);
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
     * @param  \App\Descapli  $descapli
     * @return \Illuminate\Http\Response
     */
    public function show(Descapli $descapli)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Descapli  $descapli
     * @return \Illuminate\Http\Response
     */
    public function edit(Descapli $descapli)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Descapli  $descapli
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Descapli $descapli)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Descapli  $descapli
     * @return \Illuminate\Http\Response
     */
    public function destroy(Descapli $descapli)
    {
        //
    }
}
