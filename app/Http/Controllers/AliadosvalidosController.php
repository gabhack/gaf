<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AliadosvalidosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $validos = \App\Aliadosvalidos::OrderBy('pagadurias_id')
					->OrderBy('tiposembargos_id')
					->OrderBy('aliados_id')
					->get();
		return view("aliadosvalidos/index")->with(["validos" => $validos]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$pagadurias = \App\Pagadurias::OrderBy('pagaduria')->get();
		$tiposembargos = \App\Tiposembargos::OrderBy('tipo')->get();
		$aliados = \App\Aliados::OrderBy('aliado')->get();
        return view('aliadosvalidos/crear')->with(['pagadurias' => $pagadurias, 'tiposembargos' => $tiposembargos, 'aliados' => $aliados ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$array = array();
		
		\DB::table('aliadosvalidos')
		->where('pagadurias_id', '=', $request->input('pagaduria'))
		->where('tiposembargos_id', '=', $request->input('tipoembargo'))
		->delete();

		
		foreach($request->input('aliado') as $aliado)
		{
			$array[] = [
				'pagadurias_id' => $request->input('pagaduria'),
				'tiposembargos_id' => $request->input('tipoembargo'),
				'aliados_id' => $aliado,
				'created_at' => \Carbon\Carbon::now(),
				'updated_at' => \Carbon\Carbon::now()
			];
		}
        
		
		\DB::table('aliadosvalidos')->insert($array);
		return redirect('aliadosvalidos');
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
        \App\Aliadosvalidos::find($id)->delete();		
		return redirect('aliadosvalidos');
    }
}
