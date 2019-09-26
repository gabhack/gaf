<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DemandantesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lista = \App\Demandantes::all();
        return view("demandantes/index")->with(["lista" => $lista]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('demandantes/crear');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $demandante = new \App\Demandantes;
		$demandante->documento = $request->input('documento');
		$demandante->demandante = strtoupper($request->input('demandante'));
		$demandante->save();
		
		return redirect('demandantes');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $demandante = \App\Demandantes::find($id);
		return view("demandantes/editar")->with(["demandante" => $demandante]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $demandante = \App\Demandantes::find($id);
		$demandante->documento = $request->input('documento');
		$demandante->demandante = strtoupper($request->input('demandante'));
		$demandante->save();
		
		return redirect('demandantes');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        \App\Demandantes::find($id)->delete();		
		return redirect('demandantes');
    }
}
