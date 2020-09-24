<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FactoresXMillonKreditController extends Controller
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
        $lista = \App\FactorXMillonKredit::all();
        return view("factorxmillonkredit/index")->with(["lista" => $lista]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('factorxmillonkredit/crear');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $parametro = new \App\Parametros;
		$parametro->parametro = strtoupper($request->input('factorxmillonkredit'));
		$parametro->save();
		
		return redirect('factorxmillonkredit');
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
        $factorxmillonkredit = \App\FactorXMillonKredit::find($id);
		return view("factorxmillonkredit/editar")->with(["factorxmillonkredit" => $factorxmillonkredit]);
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
        $parametro = \App\FactorXMillonKredit::find($id);
		$parametro->valor = $request->input('valor');
		$parametro->save();
		
		return redirect('factorxmillonkredit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        \App\FactorXMillonKredit::find($id)->delete();		
		return redirect('factorxmillonkredit');
    }
}
