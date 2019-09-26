<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JsonCarterasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        return \App\Carteras::find($request->input('cartera'));
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
    public function destroy(Request $request)
    {
        \App\Carteras::find($request->input('cartera'))->delete();
    }
	
	
	public function total(Request $request)
    {
		$retorno['ck'] = \App\Carteras::where('estudios_id', $request->input('estudio') )
							->where('comprado_por', 'CK')
							->sum('saldo_negociado');
							
		$retorno['alia'] = \App\Carteras::where('estudios_id', $request->input('estudio') )
							->where('comprado_por', 'ALIA')
							->sum('saldo_negociado');
		
		$total = \App\Condicionesck::where('estudios_id', $request->input('estudio') )->first();
		
		$retorno['alia'] += $total->total;
        
		return $retorno;
    }
	
	
	public function compraCk(Request $request)
    {
         return \App\Carteras::where('estudios_id', $request->input('estudio') )
							->where('comprado_por', 'CK')
							->sum('cuota');
    }
}
