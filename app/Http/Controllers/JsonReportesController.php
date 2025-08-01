<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JsonReportesController extends Controller
{
    public function consultas(Request $request)
	{
		if($request->input('consulta') == 'BAS')
		{
			return \App\Http\Resources\Consultas::basico($request);
		}
		elseif($request->input('consulta') == 'PLU')
		{
			return \App\Http\Resources\Consultas::plus($request);
		}
		elseif($request->input('consulta') == 'PRE')
		{
			return \App\Http\Resources\Consultas::premium($request);
		}
		elseif($request->input('consulta') == 'PPL')
		{
			return \App\Http\Resources\Consultas::premiumplus($request);
		}
		elseif($request->input('consulta') == 'ELI')
		{
			return \App\Http\Resources\Consultas::elite($request);
		}
		elseif($request->input('consulta') == 'EPR')
		{
			return \App\Http\Resources\Consultas::elitepremium($request);
		}
	}
}
