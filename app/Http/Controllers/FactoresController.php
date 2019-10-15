<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FactoresController extends Controller
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
        $lista = \App\Factores::orderBy('aliados_id')
								->orderBy('pagadurias_id')
								->orderBy('edad_min')
								->orderBy('edad_max')
								->orderBy('plazo')
								->get();
        return view("factores/index")->with(["lista" => $lista]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$aliados = \App\Aliados::where('estado', 'A')->orderBy('aliado')->get();
		$pagadurias = \App\Pagadurias::orderBy('pagaduria')->get();
		
        return view('factores/crear')->with(['aliados' => $aliados, 'pagadurias' => $pagadurias]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $factor = new \App\Factores;
		$factor->aliados_id = $request->input('aliado');		
		$factor->tasa = $request->input('tasa');
		$factor->pagadurias_id = $request->input('pagaduria');
		$factor->plazo = $request->input('plazo');
		$factor->edad_min = $request->input('edadMin');
		$factor->edad_max = $request->input('edadMax');
		$factor->factor = $request->input('factor');
		$factor->save();
		
		return redirect('factores');
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
        $factor = \App\Factores::find($id);
		$aliados = \App\Aliados::where('estado', 'A')->orderBy('aliado')->get();
		$pagadurias = \App\Pagadurias::orderBy('pagaduria')->get();
        
		return view("factores/editar")->with(["factor" => $factor, 'aliados' => $aliados, 'pagadurias' => $pagadurias]);
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
        $factor = \App\Factores::find($id);
		$factor->aliados_id = $request->input('aliado');
		$factor->tasa = $request->input('tasa');
		$factor->pagadurias_id = $request->input('pagaduria');
		$factor->plazo = $request->input('plazo');
		$factor->edad_min = $request->input('edadMin');
		$factor->edad_max = $request->input('edadMax');
		$factor->factor = $request->input('factor');
		$factor->save();
		
		return redirect('factores');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        \App\Factores::find($id)->delete();		
		return redirect('factores');
    }
}
