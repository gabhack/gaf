<?php

namespace App\Http\Controllers;

use App\Datamesfidu;
use App\Imports\DatamesfiduImport;
use Illuminate\Http\Request;
use Excel;

class DatamesfiduController extends Controller
{
  public function import (Request $request){
    set_time_limit(0);
    if($request->hasFile('file')){
      $path = $request->file('file')->getRealPath();
      $data = Excel::import(new DatamesfiduImport, request()->file('file'));
      return response()->json(['message'=>'Importación Realizada'],200);
    }else{
      return response()->json(['message'=>'Debe Seleccionar un archivo'],400);
    }
  }
  public function consultaUnitaria(Request $request){
    $data_formulario = $request->data;
    $doc = $request->data['doc'];
    $consulta_cedula = \App\Datamesfidu::Where('doc',$doc)->get();
    $resultados = json_decode($consulta_cedula);
    if($resultados == "" or $resultados == null ){
      return response()->json(['message'=>'No se encontraron registros con el numero seleccionado.', 'data'=>$resultados],200);
    }
    else{
      return response()->json(['message'=>'Consulta exitosa.','data'=>$resultados],200);
    }
  }
    public function dumpDatamesfidu(){
        Datamesfidu::truncate();
        return response()->json(['message'=>'Datos de tabla Datamesfidu Borrada'],200);
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
     * @param  \App\Datamesfidu  $Datamesfidu
     * @return \Illuminate\Http\Response
     */
    public function show($doc)
    {
        $Datamesfidu = Datamesfidu::where('doc',$doc)->get();
        return response()->json($Datamesfidu);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Datamesfidu  $Datamesfidu
     * @return \Illuminate\Http\Response
     */
    public function edit(Datamesfidu $Datamesfidu)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Datamesfidu  $Datamesfidu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Datamesfidu $Datamesfidu)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Datamesfidu  $Datamesfidu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Datamesfidu $Datamesfidu)
    {
        //
    }
}
