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
      $info_datames = \App\DataMes::Where('doc',$detalle_consulta->ced)->first();
      $info_fechavinc = \App\FechaVinc::Where('doc',$detalle_consulta->ced)->first();
      $resultado = [];
      $info_obligaciones = $detalle_consulta->info_obligaciones;
      $resultado['info_datames'] = $info_datames;
      $resultado['info_fechavinc'] = $info_fechavinc;
      $resultado['info_obligaciones'] = json_decode($info_obligaciones);
      $resultado['detalle_consulta'] = $detalle_consulta;
      if($resultado == "" or $resultado == null ){
        return response()->json(['message'=>'No se encontraron registros.', 'data'=>$resultado],200);
      }
      else{
        return response()->json(['message'=>'Consulta exitosa.','data'=>$resultado],200);
      }
    }

    public function pdfDetalle(Request $request){
      $detalle_consulta = \App\Visado::where('id',$request->id_consulta)->first();
      $detalle_consulta = json_decode($detalle_consulta);
      $info_datames = \App\DataMes::Where('doc',$detalle_consulta->ced)->first();
      $info_fechavinc = \App\FechaVinc::Where('doc',$detalle_consulta->ced)->first();
      $htmldata = "
      <html>
        <div>Consecutivo: {$request->id_consulta}</div>
        <div>Estado: {$detalle_consulta->estado}</div>
        <div>Fecha consulta: {$detalle_consulta->fconsultaami}</div>
        <div>Cedula: {$detalle_consulta->ced}</div>
        <div>Nombre: {$detalle_consulta->nombre}</div>
        <div>Tipo de credito: {$detalle_consulta->tcredito}</div>
        <div>Cupo Lib inversión: {$detalle_consulta->clibinv}</div>
        <div>Pagaduria: {$detalle_consulta->pagaduria}</div>
        <div>Vr Credito: {$detalle_consulta->vcredito}</div>
        <div>Vr Desembolso: {$detalle_consulta->vdesembolso}</div>
        <div>Plazo: {$detalle_consulta->plazo}</div>
        <div>Cuota: {$detalle_consulta->cuotacredito}</div>
        <div>Aprobado: {$detalle_consulta->aprobado}</div>
        <div>% incorporacion: {$detalle_consulta->porcincorp}</div>
        <div>Cuota max incorporación: {$detalle_consulta->cmaxincorp}</div>
        <div>Fecha respuesta: {$detalle_consulta->frespuesta}</div>
        <div>Fecha vinculacion: {$detalle_consulta->fvinculacion}</div>
        <div>Tipo vinculacion: {$detalle_consulta->tvinculacion}</div>
      </html>
      "
      $dompdf = new DOMPDF();
      $dompdf->load_html($htmldata);
      $dompdf->render();
      $pdfdata = $dompdf->download();
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
