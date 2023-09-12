<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CuentasBancariasController extends Controller
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
        $lista = \App\CuentasBancarias::all();
        return view("cuentasbancarias/index")->with(["lista" => $lista]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$entidades = \App\Entidades::all();
		
        return view('cuentasbancarias/crear')->with(['entidades' => $entidades]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cuenta = new \App\CuentasBancarias;
		$cuenta->id_entidad = $request->input('id_entidad');		
		$cuenta->tipo_cuenta = $request->input('tipo_cuenta');
		$cuenta->nro_cuenta = $request->input('nro_cuenta');
		$cuenta->nombre = $request->input('nombre');
		$cuenta->save();
		
		return redirect('cuentasbancarias');
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
        $cuenta = \App\CuentasBancarias::find($id);
		$entidades = \App\Entidades::all();
        
		return view("cuentasbancarias/editar")->with(["cuenta" => $cuenta, 'entidades' => $entidades]);
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
        $cuenta = \App\CuentasBancarias::find($id);
		$cuenta->id_entidad = $request->input('id_entidad');		
		$cuenta->tipo_cuenta = $request->input('tipo_cuenta');
		$cuenta->nro_cuenta = $request->input('nro_cuenta');
		$cuenta->nombre = $request->input('nombre');
		$cuenta->save();
		
		return redirect('cuentasbancarias');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        \App\CuentasBancarias::find($id)->delete();		
		return redirect('cuentasbancarias');
    }
}
