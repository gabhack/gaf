<?php

namespace App\Http\Controllers;

use App\Descnoap;
use App\Imports\DescnoapImport;
use Illuminate\Http\Request;
use Excel;

class DescnoapController extends Controller
{
    public function import (Request $request){
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
      dd($resultados);
      return response()->json($consulta_cedula);
      //return response()->json(['message'=>$th->errorInfo[2]],200)
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
