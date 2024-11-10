<?php

namespace App\Http\Controllers;

use App\Comercial;
use Illuminate\Http\Request;

class AreaComercialController extends Controller
{
	public function index()
	{
		$comerciales = Comercial::orderBy('id', 'DESC')->paginate(request()->query('per_page') ?? 5)->appends(request()->query());
		$data = [];
		foreach ($comerciales as $comercial) {
			array_push($data, [
				'id' => $comercial->id,
				'nombre_completo' => $comercial->nombre_completo,
				'cargo' => $comercial->cargo->cargo,
				'sede' => $comercial->sede->nombre,
				'ciudad' => $comercial->sede->ciudad->ciudad,
				'telefono' => $comercial->numero_contacto
			]);
		}
		$comerciales->setCollection(collect($data));
		return view('area-comerciales.index', ['comerciales' => json_encode($comerciales)]);
	}

	public function crear()
	{
		return view('area-comerciales.crear');
	}
}
