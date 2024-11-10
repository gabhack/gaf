<?php

namespace App\Http\Controllers;

use App\Comercial;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

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

	public function store(Request $request)
	{
		$empresaRequest = json_decode($request->empresa);
		$personalRequest = json_decode($request->personal);
		$plataformaRequest = json_decode($request->plataforma);
		try {
			DB::beginTransaction();
			$areaComercial = Comercial::create([
				'sede_id' => $empresaRequest->sede_id,
				'cargo_id' => $empresaRequest->cargo_id,
				'tipo_documento_id' => $personalRequest->tipo_documento_id,
				'ami_id' => $plataformaRequest->ami_id,
				'hego_id' => $plataformaRequest->hego_id,
				'consultas_diarias' => $request->consultas_diarias,
				'nombre_completo' => $personalRequest->nombre_apellido,
				'numero_documento' => $personalRequest->numero_documento,
				'nacionalidad' => $personalRequest->nacionalidad,
				'correo' => $personalRequest->correo_contacto,
				'numero_contacto' => $personalRequest->numero_contacto,
				'src_documento_identidad' => '404',
			]);
			$documentoIdentidadFile = $request->file('src_documento_identidad');
			$extension = $documentoIdentidadFile->getClientOriginalExtension();
			$documentoIdentidadPath = 'area-comerciales/' . $areaComercial->id . '/';
			$fileName = 'documento_identidad.' . $extension;
			$documentoIdentidadUpload = Storage::disk('archivos')->put($documentoIdentidadPath . $fileName, file_get_contents($documentoIdentidadFile));
			if ($documentoIdentidadUpload) {
				$areaComercial->update(['src_documento_identidad' => $documentoIdentidadPath . $fileName]);
			}
			DB::commit();
			return response()->json(['status' => 200]);
		} catch (Throwable $e) {
			DB::rollBack();
			$message = $e->getMessage() . ' in line ' . $e->getLine() . ' in file ' . $e->getFile();
			logger(['e' => $message]);
			return response()->json(['message' => $message], 500);
		}
		return response()->json(200);
	}

	public function edit($id)
	{
		$areaComercial = Comercial::find($id);
		if (empty($areaComercial)) abort(404, 'Area comercial no encontrada');
		$areaComercial->ciudad_id = $areaComercial->sede->ciudad_id;
		$documentoIdentidad = Storage::disk('archivos')->get($areaComercial->src_documento_identidad);
		$areaComercial->src_documento_identidad = base64_encode($documentoIdentidad);
		return view('area-comerciales.edit', [
			'comercial' => json_encode($areaComercial),
		]);
	}
}
