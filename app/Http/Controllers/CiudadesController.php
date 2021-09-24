<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CiudadesController extends Controller
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
        $lista = \App\Ciudades::whereHas('departamento', function($q){
										$q->whereNull('deleted_at');
									})->get();									
									
		return view('ciudades/index')->with(['lista' => $lista]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dptos = \App\Departamentos::all();
		return view('ciudades/crear')->with(['dptos' => $dptos]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $ciudad = new \App\Ciudades;
		$ciudad->departamentos_id = $request->input('depto');
		$ciudad->codigo = $request->input('codigo');
		$ciudad->ciudad = strtoupper($request->input('ciudad'));
		$ciudad->save();
		
		return redirect('ciudades');
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
        $ciudad = \App\Ciudades::find($id);
		$dptos = \App\Departamentos::all();
		return view("ciudades/editar")->with(['dptos' => $dptos, "ciudad" => $ciudad]);
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
        $ciudad = \App\Ciudades::find($id);
		$ciudad->departamentos_id = $request->input('depto');
		$ciudad->codigo = $request->input('codigo');
		$ciudad->ciudad = strtoupper($request->input('ciudad'));
		$ciudad->save();
		
		return redirect('ciudades');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
