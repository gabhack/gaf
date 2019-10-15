<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ComercialController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('superadmin');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$pagadurias = \App\Pagadurias::OrderBy('pagaduria')->get();
        return view('comerciales/index')->with(['pagadurias' => $pagadurias]);
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
		\DB::BeginTransaction();
		
        $cliente = \App\Clientes::find($request->input('cliente'));
		$cliente->nombres = strtoupper($request->input('nombres'));
		$cliente->apellidos = strtoupper($request->input('apellidos'));
		$cliente->fechanto = $request->input('fecha_nto');
		$cliente->estado_civil = $request->input('estado_civil');
		$cliente->direccion = strtoupper($request->input('direccion'));
		$cliente->ciudades_id = $request->input('ciudad');
		$cliente->correo = $request->input('correo');
		$cliente->telefono = $request->input('telefono');
		$cliente->save();
		
		$estudio = new \App\Estudiostr;
		$estudio->fecha = \Carbon\Carbon::now();
		$estudio->decision = 'ESTU';
		$estudio->clientes_id = $cliente->id;
		$estudio->user_id = \Auth::user()->id;
		$estudio->bases_id = $request->input('base');		
		$estudio->save();
		
		$observacion = new \App\Observaciones;
		$observacion->estudios_id = $estudio->id;
		$observacion->users_id = \Auth::user()->id;
		$observacion->observacion = strtoupper($request->input('observaciones'));
		$observacion->save();
		
		\DB::Commit();
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
        //
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
        //
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
