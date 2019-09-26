<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OficinasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lista = \App\Oficinas::whereHas('ciudad', function($q){
										$q->whereNull('deleted_at');
									})->get();									
									
		return view('oficinas/index')->with(['lista' => $lista]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dptos = \App\Departamentos::all();
		return view('oficinas/crear')->with(['dptos' => $dptos]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $oficina = new \App\Oficinas;
		$oficina->oficina = strtoupper($request->input('oficina'));
		$oficina->oficinas_id = $request->input('ciudad');
		$oficina->save();
		
		return redirect('oficinas');
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
		$dptos = \App\Departamentos::all();
		$oficina = \App\Oficinas::find($id);
		return view("oficinas/editar")->with(['dptos' => $dptos, "oficina" => $oficina]);
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
        $oficina = \App\Oficinas::find($id);
		$oficina->oficina = strtoupper($request->input('oficina'));
		$oficina->ciudades_id = $request->input('ciudad');
		$oficina->save();
		
		return redirect('oficinas');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        \App\Oficinas::find($id)->delete();		
		return redirect('oficinas');
    }
}
