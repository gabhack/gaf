<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CargosController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lista = \App\Cargos::OrderBy('estado')->OrderBy('cargo')->get();
        return view("cargos/index")->with(["lista" => $lista]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cargos/crear');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cargo = new \App\Cargos;
		$cargo->estado = $request->input('estado');
		$cargo->cargo = strtoupper($request->input('cargo'));
		$cargo->asignacion_adicional = $request->input('asignacion_adicional');
		$cargo->save();
		
		return redirect('cargos');
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
        $cargo = \App\Cargos::find($id);
		return view("cargos/editar")->with(["cargo" => $cargo]);
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
        $cargo = \App\Cargos::find($id);
		$cargo->estado = $request->input('estado');
		$cargo->cargo = strtoupper($request->input('cargo'));
		$cargo->asignacion_adicional = $request->input('asignacion_adicional');
		$cargo->save();
		
		return redirect('cargos');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        \App\Cargos::find($id)->delete();		
		return redirect('cargos');
    }
}
