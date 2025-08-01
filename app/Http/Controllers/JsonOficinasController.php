<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class JsonOficinasController extends Controller
{
    public function xCiudad(Request $request)
    {
		return \App\Oficinas::where("ciudades_id", $request->input("ciudad"))->get()->toArray();
    }    
}
