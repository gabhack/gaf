<?php

namespace App\Http\Controllers;

use App\DocumentoEmpresa;
use App\Empresa;
use App\RepresentanteLegalEmpresa;
use App\Roles;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Throwable;

class EmpresaController extends Controller
{
	public function index()
	{
		$empresas = Empresa::orderBy('id', 'DESC')
			->paginate(request()->query('per_page') ?? 5)
			->appends(request()->query());

		$data = [];

		foreach ($empresas as $empresa) {
			array_push($data, [
				'id' => $empresa->id,
				'tipo_empresa' => $empresa->tipo_empresa->nombre,
				'nombre' => $empresa->nombre,
				'documento' => $empresa->numero_documento,
				'ciudad' => $empresa->ciudad->nombre
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
		$empresaUsuario = json_decode($request->usuario);

		$permisos = json_decode($request->consultas_diarias);
		$permisosIds = collect($permisos)->pluck('id');

		$rolEmpresa = Roles::where('rol', 'EMPRESA')->first();
		if (!$rolEmpresa) {
			throw new Exception("El rol 'EMPRESA' no existe.");
		}

		try {
			DB::beginTransaction();
			$empresa = Empresa::create([
				'tipo_sociedad_id' => $empresaRequest->tipo_sociedad_id,
				'tipo_empresa_id' => $request->tipo_empresa_id,
				'tipo_documento_id' => $empresaRequest->tipo_documento_id,
				'consultas_diarias' => 0,
				'nombre' => $empresaRequest->nombre,
				'numero_documento' => $empresaRequest->numero_documento,
				'correo' => $empresaRequest->correo,
				'pagina_web' => $empresaRequest->pagina_web,
				'pais_id' => $empresaRequest->pais_id,
				'departamento_id' => $empresaRequest->departamento_id,
				'ciudad_id' => $empresaRequest->ciudad_id,
				'direccion' => $empresaRequest->direccion,
			]);

			$empresa->permisos()->attach($permisosIds);

			$usuario = User::create([
				'roles_id' => $rolEmpresa->id,
				'empresa_id' => $empresa->id,
				'name' => $empresaUsuario->nombre,
				'email' => $empresaUsuario->correo,
				'password' => Hash::make($empresaUsuario->contrasena),
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

			$documentoEmpresa = DocumentoEmpresa::create([
				'empresa_id' => $empresa->id,
				'iva' => $documentacionRequest->iva,
				'contribuyente' => $documentacionRequest->contribuyente,
				'autoretenedor' => $documentacionRequest->autoretenedor,
				'src_representante_legal' => '404',
				'src_camara_comercio' => '404',
				'src_rut' => '404',
			]);

			if ($request->hasFile('src_representante_legal')) {
				$representanteLegalFile = $request->file('src_representante_legal');
				$extension = $representanteLegalFile->getClientOriginalExtension();
				$representanteLegalPath = 'empresas/' . $empresa->id . '/';
				$fileName = 'representante_legal.' . $extension;
				$representanteLegalDocUpload = Storage::disk('archivos')->put($representanteLegalPath . $fileName, file_get_contents($representanteLegalFile));

				if ($representanteLegalDocUpload) {
					$documentoEmpresa->update(['src_representante_legal' => $representanteLegalPath . $fileName]);
				}
			}

			if ($request->hasFile('src_camara_comercio')) {
				$camaraComercioFile = $request->file('src_camara_comercio');
				$extension = $camaraComercioFile->getClientOriginalExtension();
				$camaraComercioPath = 'empresas/' . $empresa->id . '/';
				$fileName = 'camara_comercio.' . $extension;
				$camaraComercioDocUpload = Storage::disk('archivos')->put($camaraComercioPath . $fileName, file_get_contents($camaraComercioFile));

				if ($camaraComercioDocUpload) {
					$documentoEmpresa->update(['src_camara_comercio' => $camaraComercioPath . $fileName]);
				}
			}

			if ($request->hasFile('src_rut')) {
				$rutFile = $request->file('src_rut');
				$extension = $rutFile->getClientOriginalExtension();
				$rutDocPath = 'empresas/' . $empresa->id . '/';
				$fileName = 'rut.' . $extension;
				$rutDocPathUpload = Storage::disk('archivos')->put($rutDocPath . $fileName, file_get_contents($rutFile));

				if ($rutDocPathUpload) {
					$documentoEmpresa->update(['src_rut' => $rutDocPath . $fileName]);
				}
			}

			DB::commit();
			return response()->json(['status' => 200]);
		} catch (Throwable $e) {
			DB::rollBack();
			$message = $e->getMessage() . ' in line ' . $e->getLine() . ' in file ' . $e->getFile();
			logger(['e' => $message]);
			return response()->json(['message' => $message], 500);
		}
	}

	public function edit($id)
	{
		try {
			$empresa = Empresa::find($id);
			if (empty($empresa)) abort(404, 'Empresa no encontrada');

			if (isset($empresa->documento_empresa->src_representante_legal)) {
				$representanteLegalSrc = Storage::disk('archivos')->get($empresa->documento_empresa->src_representante_legal);
				$empresa->documento_empresa->src_representante_legal = base64_encode($representanteLegalSrc);

				$camaraComercioSrc = Storage::disk('archivos')->get($empresa->documento_empresa->src_camara_comercio);
				$empresa->documento_empresa->src_camara_comercio = base64_encode($camaraComercioSrc);

				$rutSrc = Storage::disk('archivos')->get($empresa->documento_empresa->src_rut);
				$empresa->documento_empresa->src_rut = base64_encode($rutSrc);
			}
			return view('empresas.edit', [
				'empresa' => json_encode($empresa),
				'representanteLegal' => json_encode($empresa->representante_legal_empresa),
				'documentoEmpresa' => json_encode($empresa->documento_empresa)
			]);
		} catch (Throwable $e) {
			return response()->json(['message' => $e], 500);
		}
	}

	public function update(Request $request, $id)
	{
		$empresaRequest = json_decode($request->empresa);
		$representanteLegalRequest = json_decode($request->representante_legal);
		$documentacionRequest = json_decode($request->documentacion);

		$permisos = json_decode($request->consultas_diarias);
		$permisosIds = collect($permisos)->pluck('id');

		try {
			DB::beginTransaction();
			$empresa = Empresa::find($id);
			if (empty($empresa)) abort(404, 'Empresa no encontrada');
			$empresa->update([
				'tipo_sociedad_id' => $empresaRequest->tipo_sociedad_id,
				'tipo_empresa_id' => $request->tipo_empresa_id,
				'tipo_documento_id' => $empresaRequest->tipo_documento_id,
				'ciudad_id' => $empresaRequest->ciudad_id,
				'consultas_diarias' => 0,
				'nombre' => $empresaRequest->nombre,
				'numero_documento' => $empresaRequest->numero_documento,
				'correo' => $empresaRequest->correo,
				'pagina_web' => $empresaRequest->pagina_web,
				'pais' => $empresaRequest->pais,
				'direccion' => $empresaRequest->direccion,
			]);

			$empresa->permisos()->sync($permisosIds);

			$representanteLegalEmpresa = RepresentanteLegalEmpresa::where('empresa_id', $empresa->id)->first();
			if (!empty($representanteLegalEmpresa)) {
				$representanteLegalEmpresa->update([
					'tipo_documento_id' => $representanteLegalRequest->tipo_documento_id,
					'nombres_completos' => $representanteLegalRequest->nombres_completos,
					'numero_documento' => $representanteLegalRequest->numero_documento,
					'nacionalidad' => $representanteLegalRequest->nacionalidad,
					'correo' => $representanteLegalRequest->correo,
					'numero_contacto' => $representanteLegalRequest->numero_contacto,
				]);
			}
			$documentoEmpresa = DocumentoEmpresa::where('empresa_id', $empresa->id)->first();
			if (!empty($documentoEmpresa)) {
				$documentoEmpresa->update([
					'iva' => $documentacionRequest->iva,
					'contribuyente' => $documentacionRequest->contribuyente,
					'autoretenedor' => $documentacionRequest->autoretenedor
				]);
				if ($request->hasFile('src_representante_legal')) {
					$representanteLegalFile = $request->file('src_representante_legal');
					$extension = $representanteLegalFile->getClientOriginalExtension();
					$representanteLegalPath = 'empresas/' . $empresa->id . '/';
					$fileName = 'representante_legal.' . $extension;
					$representanteLegalDocUpload = Storage::disk('archivos')->put($representanteLegalPath . $fileName, file_get_contents($representanteLegalFile));
					if ($representanteLegalDocUpload) {
						$documentoEmpresa->update(['src_representante_legal' => $representanteLegalPath . $fileName]);
					}
				}

				if ($request->hasFile('src_camara_comercio')) {
					$camaraComercioFile = $request->file('src_camara_comercio');
					$extension = $camaraComercioFile->getClientOriginalExtension();
					$camaraComercioPath = 'empresas/' . $empresa->id . '/';
					$fileName = 'camara_comercio.' . $extension;
					$camaraComercioDocUpload = Storage::disk('archivos')->put($camaraComercioPath . $fileName, file_get_contents($camaraComercioFile));
					if ($camaraComercioDocUpload) {
						$documentoEmpresa->update(['src_camara_comercio' => $camaraComercioPath . $fileName]);
					}
				}

				if ($request->hasFile('src_rut')) {
					$rutFile = $request->file('src_rut');
					$extension = $rutFile->getClientOriginalExtension();
					$rutDocPath = 'empresas/' . $empresa->id . '/';
					$fileName = 'rut.' . $extension;
					$rutDocPathUpload = Storage::disk('archivos')->put($rutDocPath . $fileName, file_get_contents($rutFile));
					if ($rutDocPathUpload) {
						$documentoEmpresa->update(['src_rut' => $rutDocPath . $fileName]);
					}
				}
			}
			DB::commit();
			return response()->json(['status' => 200]);
		} catch (Throwable $e) {
			DB::rollBack();
			$message = $e->getMessage() . ' in line ' . $e->getLine() . ' in file ' . $e->getFile();
			logger(['e' => $message]);
			return response()->json(['message' => $message], 500);
		}
	}

	public function destroy($id)
	{
		try {
			DB::beginTransaction();
			$empresa = Empresa::find($id);
			DocumentoEmpresa::where('empresa_id', $empresa->id)->delete();
			RepresentanteLegalEmpresa::where('empresa_id', $empresa->id)->delete();
			$empresa->delete();
			DB::commit();
			return response()->json(['status' => 200]);
		} catch (Throwable $e) {
			DB::rollBack();
			$message = $e->getMessage() . ' in line ' . $e->getLine() . ' in file ' . $e->getFile();
			return response()->json(['message' => $message], 500);
		}
	}
}
