<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class JsonCiudadesController extends Controller
{
    public function xDepto(Request $request)
    {
		if($request->input('depto') != "")
			return \App\Ciudades::where("departamentos_id", $request->input("depto"))->get()->toArray();
    }    
}
