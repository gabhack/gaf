<?php

namespace App\Http\Controllers;

use App\Empresa;
use Illuminate\Http\Request;
use Throwable;

class EmpresaController extends Controller
{
	public function index()
	{
		$empresas = Empresa::orderBy('id', 'DESC')->paginate(request()->query('per_page') ?? 5)->appends(request()->query());
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

	public function store(Request $request)
	{
		$empresaRequest = json_decode($request->empresa);
		try {
			$empresa = Empresa::create([
				'tipo_sociedad_id' => $empresaRequest->tipo_sociedad_id,
				'tipo_empresa_id' => $request->tipo_empresa_id,
				'tipo_documento_id' => $empresaRequest->tipo_documento_id,
				'ciudad_id' => $empresaRequest->ciudad_id,
				'consultas_diarias' => $request->consultas_diarias,
				'nombre' => $empresaRequest->nombre,
				'numero_documento' => $empresaRequest->numero_documento,
				'correo' => $empresaRequest->correo,
				'pagina_web' => $empresaRequest->pagina_web,
				'pais' => $empresaRequest->pais,
				'direccion' => $empresaRequest->direccion,
			]);
			return response()->json(['status' => 200]);
		} catch (Throwable $e) {
			logger(['e' => $e->getMessage() . ' in line ' . $e->getLine() . ' in file ' . $e->getFile()]);
		}
	}

	public function edit($id)
	{
		$empresa = Empresa::find($id);
		if (empty($empresa)) abort(404, 'Empresa no encontrada');
		return view('empresas.edit', ['empresa' => json_encode($empresa)]);
	}
}
