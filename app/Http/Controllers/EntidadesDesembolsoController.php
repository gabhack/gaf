<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EntidadesDesembolsoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:ADMIN_SISTEMA');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lista = \App\EntidadesDesembolso::all();
        return view("entidadesdesembolso/index")->with(["lista" => $lista]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {		
        return view('entidadesdesembolso/crear');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $entidad = new \App\EntidadesDesembolso;
		$entidad->nit = $request->input('nit');		
		$entidad->nombre = $request->input('nombre');
		$entidad->save();
		
		return redirect('entidadesdesembolso');
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
        $entidad = \App\EntidadesDesembolso::find($id);
        
		return view("entidadesdesembolso/editar")->with(["entidad" => $entidad]);
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
        $entidad = \App\EntidadesDesembolso::find($id);
		$entidad->nit = $request->input('nit');		
		$entidad->nombre = $request->input('nombre');
		$entidad->save();
		
		return redirect('entidadesdesembolso');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        \App\EntidadesDesembolso::find($id)->delete();		
		return redirect('entidadesdesembolso');
    }
}
