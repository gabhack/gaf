<?php

namespace App\Http\Controllers;

use App\Detalledecliente;
use Illuminate\Http\Request;

class Detalledecliente extends Controller
{
  public function consultaUnitaria(Request $request){
    $data_formulario = $request->data;
    $doc = $request->doc;
    $consulta_cedula = Detalledecliente::where('doc',$doc)->get();
    $resultados = json_decode($consulta_cedula);
    if($resultados == "" or $resultados == null ){
      return response()->json(['message'=>'No se encontraron registros con el numero seleccionado.', 'data'=>$resultados],200);
    }
    else{
      return response()->json(['message'=>'Consulta exitosa.','data'=>$resultados],200);
    }
  }
    public function dumpDetalledecliente(){
      Detalledecliente::truncate();
        return response()->json(['message'=>'Datos de tabla Detalledecliente Borrada'],200);
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
     * @param  \App\Detalledecliente  $Detalledecliente
     * @return \Illuminate\Http\Response
     */
    public function show($doc)
    {
        $Descuentossedcauca = Detalledecliente::where('doc',$doc)->get();
        return response()->json($Detalledecliente);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Detalledecliente  $Detalledecliente
     * @return \Illuminate\Http\Response
     */
    public function edit(Detalledecliente $Detalledecliente)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Detalledecliente  $Detalledecliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Detalledecliente $Detalledecliente)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Detalledecliente  $Detalledecliente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Detalledecliente $Detalledecliente)
    {
        //
    }
}
