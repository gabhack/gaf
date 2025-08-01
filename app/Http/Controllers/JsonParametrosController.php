<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class JsonParametrosController extends Controller
{
    public function xVinculacion(Request $request)
    {
		if($request->input('vinculacion') == 'ACTI')
			return \App\Parametros::where('llave', 'APORTES_ACTIVOS')->first()->toArray();
		else if($request->input('vinculacion') == 'PENS')
			return \App\Parametros::where('llave', 'APORTES_PENSIONADOS')->first()->toArray();
		else
			return 0;
    }
}
