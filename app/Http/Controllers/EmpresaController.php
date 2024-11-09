<?php

namespace App\Http\Controllers;

use App\DocumentoEmpresa;
use App\Empresa;
use App\RepresentanteLegalEmpresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
		$representanteLegalRequest = json_decode($request->representante_legal);
		$documentacionRequest = json_decode($request->documentacion);
		try {
			DB::beginTransaction();
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
			RepresentanteLegalEmpresa::create([
				'empresa_id' => $empresa->id,
				'tipo_documento_id' => $representanteLegalRequest->tipo_documento_id,
				'nombres_completos' => $representanteLegalRequest->nombres_completos,
				'numero_documento' => $representanteLegalRequest->numero_documento,
				'nacionalidad' => $representanteLegalRequest->nacionalidad,
				'correo' => $representanteLegalRequest->correo,
				'numero_contacto' => $representanteLegalRequest->numero_contacto,
			]);
			DocumentoEmpresa::create([
				'empresa_id' => $empresa->id,
				'iva' => $documentacionRequest->iva,
				'contribuyente' => $documentacionRequest->contribuyente,
				'autoretenedor' => $documentacionRequest->autoretenedor,
				'src_representante_legal' => $documentacionRequest->src_representante_legal,
				'src_camara_comercio' => $documentacionRequest->src_camara_comercio,
				'src_rut' => $documentacionRequest->src_rut,
			]);
			DB::commit();
			return response()->json(['status' => 200]);
		} catch (Throwable $e) {
			DB::rollBack();
			logger(['e' => $e->getMessage() . ' in line ' . $e->getLine() . ' in file ' . $e->getFile()]);
		}
	}

	public function edit($id)
	{
		$empresa = Empresa::find($id);
		if (empty($empresa)) abort(404, 'Empresa no encontrada');
		return view('empresas.edit', [
			'empresa' => json_encode($empresa),
			'representanteLegal' => json_encode($empresa->representante_legal_empresa),
			'documentoEmpresa' => json_encode($empresa->documento_empresa)
		]);
	}
}
