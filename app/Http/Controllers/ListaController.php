<?php

namespace App\Http\Controllers;

use App\Ami;
use App\Cargos;
use App\Ciudades;
use App\Hego;
use App\Sede;
use App\TipoDocumento;
use App\TipoEmpresa;
use App\TipoSociedad;
use Illuminate\Http\Request;

class ListaController extends Controller
{
	public function listarTipoEmpresas()
	{
		$tipoEmpresas = TipoEmpresa::all();
		return response()->json($tipoEmpresas);
	}

	public function listarTipoSociedades()
	{
		$tipoSociedades = TipoSociedad::all();
		return response()->json($tipoSociedades);
	}

	public function listarTipoDocumentos()
	{
		$tipoDocumentos = TipoDocumento::all();
		return response()->json($tipoDocumentos);
	}

	public function listarCiudades()
	{
		$ciudades = Ciudades::orderBy('ciudad', 'ASC')->get();
		return response()->json($ciudades);
	}

	public function listarSedesPorCiudad($ciudadId)
	{
		$sedes = Sede::where('ciudad_id', $ciudadId)->orderBy('nombre', 'ASC')->get();
		return response()->json($sedes);
	}

	public function listarCargos()
	{
		$cargos = Cargos::orderBy('cargo', 'ASC')->get();
		return response()->json($cargos);
	}

	public function listarAmis()
	{
		$amis = Ami::all();
		return response()->json($amis);
	}

	public function listarHegos()
	{
		$hegos = Hego::all();
		return response()->json($hegos);
	}
}
