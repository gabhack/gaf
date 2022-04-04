<?php

namespace App\Http\Controllers;

use App\Visado;
use Illuminate\Http\Request;

class VisadoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function historialConsultas(Request $request){
      // $data_formulario = $request->data;
      // $doc = $request->data['doc'];
      // $historial_consultas = \App\Descapli::Where('doc',$doc)->get();
      $historial_consultas = \App\Visado::get();
      $resultados = json_decode($historial_consultas);
      if($resultados == "" or $resultados == null ){
        return response()->json(['message'=>'No se encontraron registros.', 'data'=>$resultados],200);
      }
      else{
        return response()->json(['message'=>'Consulta exitosa.','data'=>$resultados],200);
      }
    }
    public function detalleConsulta(Request $request){
      // $data_formulario = $request->data;
      // $doc = $request->data['doc'];
      // $historial_consultas = \App\Descapli::Where('doc',$doc)->get();
      $detalle_consulta = \App\Visado::where('id',$request->id)->first();
      $detalle_consulta = json_decode($detalle_consulta);
      $info_datames = \App\DataMes::Where('doc',$detalle_consulta->doc)->first();
      $info_fechavinc = \App\FechaVinc::Where('doc',$detalle_consulta->doc)->first();
      $resultado = [];
      $resultado['info_datames'] = $info_datames;
      $resultado['info_fechavinc'] = $info_fechavinc;
      $resultado['info_obligaciones'] = $info_obligaciones;
      $resultado['detalle_consulta'] = $detalle_consulta;
      if($resultados == "" or $resultados == null ){
        return response()->json(['message'=>'No se encontraron registros.', 'data'=>$resultados],200);
      }
      else{
        return response()->json(['message'=>'Consulta exitosa.','data'=>$resultados],200);
      }
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
     * @param  \App\Visado  $visado
     * @return \Illuminate\Http\Response
     */
    public function show(Visado $visado)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Visado  $visado
     * @return \Illuminate\Http\Response
     */
    public function edit(Visado $visado)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Visado  $visado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Visado $visado)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Visado  $visado
     * @return \Illuminate\Http\Response
     */
    public function destroy(Visado $visado)
    {
        //
    }
}
