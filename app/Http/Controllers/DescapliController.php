<?php

namespace App\Http\Controllers;

use App\Descapli;
use App\Visado;
use App\DataMes;
use App\FechaVinc;
use App\Imports\DescapliImport;
use Illuminate\Http\Request;
use Excel;
use date;

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
      $data_formulario = $request->data;
      $doc = $request->data['doc'];
      $info_datames = \App\DataMes::Where('doc',$doc)->first();
      $info_fechavinc = \App\FechaVinc::Where('doc',$doc)->first();
      $cupo = $info_datames->cupo;
      $fecha_vinculacion = isset($info_fechavinc->vinc) ? $info_fechavinc->vinc : '';
      $tipo_vinculacion = isset($info_fechavinc->tp) ? $info_fechavinc->tp : '';
      $array_entidad = $data_formulario['nomterSelect'];
      $array_entidad[]="CUPO";
      $array_pagare = $data_formulario['pagareSelected'];
      $array_cuota_compra = $data_formulario['v_aplicado'];
      $array_cuota_compra[]=$cupo;
      $total_cuota = 0;
      foreach ($array_cuota_compra as $cuota_compra) {
        $cuota_compra = str_replace('$','',$cuota_compra);
        $cuota_compra = str_replace('.','',$cuota_compra);
        $total_cuota = (int)$total_cuota + (int)$cuota_compra;
      }
      $cuota_cred = $data_formulario['cuota_cred'];
      $pct_incorporacion = ($total_cuota/(int)$cuota_cred) * 100;
      $pct_incorporacion = round($pct_incorporacion);
      $aprobado = ($pct_incorporacion<=100)?"NO":"SI";
      $data_formulario['nombre']=$info_datames->nomp;
      $data_formulario['consecutivo']="";
      $data_formulario['estado']="Consulta";
      $data_formulario['fecha_consulta']= date('Y-m-d');
      $data_formulario['entidad']=$array_entidad;
      $data_formulario['pagare']=$array_pagare;
      $data_formulario['cuota_compra']=$array_cuota_compra;
      $data_formulario['pct_incorporacion']=(int)$pct_incorporacion."%";
      $data_formulario['aprobado']=$aprobado;
      $data_formulario['max_incorporacion']='$ '.number_format($total_cuota, 2,',','.');
      $data_formulario['cuota_cred'] = '$ '.number_format($cuota_cred, 2,',','.');
      $data_formulario['vr_credito'] = '$ '.number_format($data_formulario['vr_credito'], 2,',','.');
      $data_formulario['vr_desembolso'] = '$ '.number_format($data_formulario['vr_desembolso'], 2,',','.');
      $data_formulario['fec_rta_consulta']= date('Y-m-d');
      $data_formulario['fecha_vinculacion']=$fecha_vinculacion;
      $data_formulario['tipo_vinculacion']=$tipo_vinculacion;

      $data_visado = [
        'conc'=>'',
        'estado'=>($aprobado == "SI")?"Aprobado":"Negado",
        'fconsultaami'=> date('Y-m-d'),
        'ced'=>$doc,
        'nombre'=>$info_datames->nomp,
        'pagaduria'=>$request->pagaduria,
        'tcredito'=>"Compra cartera",
        'clibinv'=>$request->clibinv,
        'ccompra'=>$request->cuota_cred,
        'entidad'=>"FOPEP",
        'pagare'=>$request->pagaduria,,
        'vcredito'=>$request->vr_credito,
        'vdesembolso'=>$request->vr_desembolso,
        'plazo'=>$request->plazo,
        'cuotacredito'=>$request->cuota_cred,
        'aprobado'=>$aprobado,
        'porcincorp'=>(int)$pct_incorporacion,
        'cmaxincorp'=>$total_cuota,
        'frespuesta'=>date('Y-m-d'),
        'fvinculacion'=>$fecha_vinculacion,
        'tvinculacion'=>$tipo_vinculacion,
        'tipo_consulta'=>'Individual'
      ]
      Visado::create($data_visado;
      return response()->json(['message'=>'Consulta exitosa.','data'=>$data_formulario],200);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

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
    public function show($doc)
    {
        $descapli = Descapli::where('doc', $doc)->get();
        return response()->json($descapli);
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
