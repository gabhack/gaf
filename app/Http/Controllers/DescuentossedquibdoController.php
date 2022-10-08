<?php

namespace App\Http\Controllers;

use App\Descuentossedquibdo;
use Illuminate\Http\Request;

class DescuentossedquibdoController extends Controller
{
  public function consultaUnitaria(Request $request){
    $data_formulario = $request->data;
    $doc = $request->doc;
    $consulta_cedula = Descuentossedquibdo::where('doc',$doc)->get();
    $resultados = json_decode($consulta_cedula);
    if($resultados == "" or $resultados == null ){
      return response()->json(['message'=>'No se encontraron registros con el numero seleccionado.', 'data'=>$resultados],200);
    }
    else{
      return response()->json(['message'=>'Consulta exitosa.','data'=>$resultados],200);
    }
  }
    public function dumpDescuentossedquibdo(){
      Descuentossedquibdo::truncate();
        return response()->json(['message'=>'Datos de tabla Descuentossedquibdo Borrada'],200);
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
     * @param  \App\Descuentossedquibdo  $Descuentossedquibdo
     * @return \Illuminate\Http\Response
     */
    public function show($doc)
    {
        $Descuentossedquibdo = Descuentossedquibdo::where('doc',$doc)->get();
        return response()->json($Descuentossedquibdo);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Descuentossedquibdo  $Descuentossedquibdo
     * @return \Illuminate\Http\Response
     */
    public function edit(Descuentossedquibdo $Descuentossedquibdo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Descuentossedquibdo  $Descuentossedquibdo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Descuentossedquibdo $Descuentossedquibdo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Descuentossedquibdo  $Descuentossedquibdo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Descuentossedquibdo $Descuentossedquibdo)
    {
        //
    }
}
