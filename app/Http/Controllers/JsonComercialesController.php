<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class JsonComercialesController extends Controller
{
    public function infoCliente(Request $request)
    {
		$bases = \App\Bases::with('cliente', 'pagaduria')
							->whereHas('cliente', function($q) use ($request){
								$q->where('documento', $request->input('documento'));
							})
							->whereBetween('fecha', [date('Y-m-01'), date('Y-m-31')])
							->where('pagadurias_id', $request->input('pagaduria'))
							->first();
		if($bases != null)
		{
			$departamentos = \App\Departamentos::OrderBy('departamento')->get();
			return view('comerciales/cliente')->with(['documento' => $request->input('documento'), 'departamentos' => $departamentos, 'info' => $bases]);
		}
		else
		{
			setMessage('El Cliente no Existe', 'info');
			echo getMessage();
		}
    }
}
