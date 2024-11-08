<?php

namespace App\Http\Controllers;

use App\Ciudades;
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
		$ciudades = Ciudades::all();
		return response()->json($ciudades);
	}
}
