<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class JsonCargosController extends Controller
{
    public function xVinculacion(Request $request)
    {
		if($request->input('vinculacion') != "")
			return \App\Cargos::where("estado", $request->input("vinculacion"))->get()->toArray();
    }
	
    public function xCargo(Request $request)
    {
		if($request->input('cargo') != "")
			return \App\Cargos::find($request->input('cargo'))->toArray();
    }
}
