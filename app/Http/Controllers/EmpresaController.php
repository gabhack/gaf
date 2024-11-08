<?php

namespace App\Http\Controllers;

use App\Empresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
	public function index()
	{
		$empresas = Empresa::paginate(request()->query('per_page') ?? 5)->appends(request()->query());
		$data = [];
		foreach ($empresas as $empresa) {
			array_push($data, [
				'id' => $empresa->id,
				'tipo_empresa' => $empresa->tipo_empresa->nombre,
				'nombre' => $empresa->nombre,
				'documento' => $empresa->numero_documento,
				'ciudad' => $empresa->ciudad->ciudad
			]);
		}
		$empresas->setCollection(collect($data));
		return view('empresas.index', ['empresas' => json_encode($empresas)]);
	}

	public function crear()
	{
		return view('empresas.crear');
	}
}
