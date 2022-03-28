<?php

namespace App\Http\Controllers;

use App\FechaVinc;
use Illuminate\Http\Request;
use Excel;

class FechaVincController extends Controller
{
  public function import (Request $request){
    if($request->hasFile('file')){
      $path = $request->file('file')->getRealPath();
      $data = Excel::import(new FechaVincImport, request()->file('file'));
      return response()->json(['message'=>'Importación Realizada'],200);
    }else{
      return response()->json(['message'=>'Debe Seleccionar un archivo'],400);
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
     * @param  \App\FechaVinc  $fechaVinc
     * @return \Illuminate\Http\Response
     */
    public function show(FechaVinc $fechaVinc)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FechaVinc  $fechaVinc
     * @return \Illuminate\Http\Response
     */
    public function edit(FechaVinc $fechaVinc)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FechaVinc  $fechaVinc
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FechaVinc $fechaVinc)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FechaVinc  $fechaVinc
     * @return \Illuminate\Http\Response
     */
    public function destroy(FechaVinc $fechaVinc)
    {
        //
    }
}
