<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AliadosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lista = \App\Aliados::OrderBy('aliado')->get();
        return view("aliados/index")->with(["lista" => $lista]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('aliados/crear');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $aliado = new \App\Aliados;
		$aliado->aliado = strtoupper($request->input('aliado'));
		$aliado->max_plazo = $request->input('plazo');
		$aliado->estado = $request->input('estado');
		$aliado->save();
		
		return redirect('aliados');
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
        $aliado = \App\Aliados::find($id);
		return view("aliados/editar")->with(["aliado" => $aliado]);
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
        $aliado = \App\Aliados::find($id);
		$aliado->aliado = strtoupper($request->input('aliado'));
		$aliado->max_plazo = $request->input('plazo');
		$aliado->estado = $request->input('estado');
		$aliado->save();
		
		return redirect('aliados');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        \App\Aliados::find($id)->delete();		
		return redirect('aliados');
    }
	
	
	public function parametrizar()
	{
		
	}
}