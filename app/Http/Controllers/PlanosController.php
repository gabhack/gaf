<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlanosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$planos = \App\Planos::orderBy('created_at', 'desc')->get();
        return view('planos/index')->with(['planos' => $planos]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$pagadurias = \App\Pagadurias::orderBy('pagaduria')->get();
        return view('planos/crear')->with(['pagadurias' => $pagadurias]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		ini_set('memory_limit', '-1');
		
		if($request->file('basicos') != "")
		{
			$plano = new \App\Planos;		
			$plano->pagadurias_id = $request->input("pagaduria");		
			$plano->plano = ""; // \File::get( $request->file('basicos') );
			$plano->tipo = 'BAS';
			$plano->save();
		}
		
		if($request->file('aplicados') != "")
		{
			$plano = new \App\Planos;		
			$plano->pagadurias_id = $request->input("pagaduria");		
			$plano->plano = \File::get( $request->file('aplicados') );
			$plano->tipo = 'APL';
			$plano->save();
		}
		
		if($request->file('no_aplicados') != "")
		{
			$plano = new \App\Planos;		
			$plano->pagadurias_id = $request->input("pagaduria");		
			$plano->plano = \File::get( $request->file('no_aplicados') );
			$plano->tipo = 'NAP';
			$plano->save();
		}
		
		if($request->file('embargos') != "")
		{
			$plano = new \App\Planos;		
			$plano->pagadurias_id = $request->input("pagaduria");		
			$plano->plano = \File::get( $request->file('embargos') );
			$plano->tipo = 'EMB';
			$plano->save();
		}
		
        $pagaduria = \App\Pagadurias::find($request->input("pagaduria"));
		if($pagaduria->codigo == "SEM_POPAYAN")
		{
			\App\Http\Resources\Popayan::base($request);
		}
    }

}
