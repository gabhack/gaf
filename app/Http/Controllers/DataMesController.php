<?php

namespace App\Http\Controllers;

use App\DataMes;
use Illuminate\Http\Request;
use DB;

class DataMesController extends Controller
{
    public function import(){

    }

    public function dumpDataMes(){
        DB::table('datames')->delete();
        return response()->json(['message'=>'Datos de tabla DataMes Borrada'],200);
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
     * @param  \App\DataMes  $dataMes
     * @return \Illuminate\Http\Response
     */
    public function show(DataMes $dataMes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DataMes  $dataMes
     * @return \Illuminate\Http\Response
     */
    public function edit(DataMes $dataMes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DataMes  $dataMes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DataMes $dataMes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DataMes  $dataMes
     * @return \Illuminate\Http\Response
     */
    public function destroy(DataMes $dataMes)
    {
        //
    }
}
