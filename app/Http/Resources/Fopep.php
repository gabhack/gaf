<?php

namespace App\Http\Resources;
use Illuminate\Http\Request;

class Fopep
{
	
	public static function base(Request $request)
	{
		ini_set('memory_limit', '-1');
		$archivo = $request->file('basicos');

		$nombre_original = $archivo->getClientOriginalName();
		$extension = $archivo->getClientOriginalExtension();
		$nombreArchivoTmp = rand ( 0 , 99999 );

		$re = \Storage::disk('archivos')->put($nombreArchivoTmp . "." . $extension, \File::get($archivo));
		$ruta = storage_path('archivos') . "/" . $nombreArchivoTmp . "." . $extension;

		$clientes = array();
		
		if ($re) {
			$cl = \Excel::load($ruta)->toArray();
			foreach ($cl as $key => $row) {
				$ciudad = \App\Ciudades::where('ciudad', $row['mnpio_nombre_municipio_de_residencia_del_pensionado'] )->first();
				$cliente = \App\Clientes::where("documento", "=", $row['documento'])->first();

				if ($cliente === null) {
					$nuevoCliente = new \App\Clientes;
					$nuevoCliente->ciudades_id		= $ciudad['id'];
					$nuevoCliente->users_id			= \Auth::user()->id;
					$nuevoCliente->tipodocumento	= $row['td_documento'];
					$nuevoCliente->documento 		= $row['documento'];
					$nuevoCliente->nombres 			= $row['pensionado_apellidos_y_nombres'];
					$nuevoCliente->fechanto 		= date('Y-m-d', strtotime($row['fecha_de_nacimiento']));
					$nuevoCliente->sexo 			= "";
					$nuevoCliente->telefono			= $row['telefono'];
					$nuevoCliente->direccion		= $row['direccion'];
					$nuevoCliente->correo			= $row['correo_electronico'];
					$nuevoCliente->save();
				} else {
					$cliente->ciudades_id		= $ciudad['id'];
					$cliente->tipodocumento		= $row['td_documento'];
					$cliente->documento 		= $row['documento'];
					$cliente->nombres 			= $row['pensionado_apellidos_y_nombres'];
					$cliente->fechanto 			= date('Y-m-d', strtotime($row['fecha_de_nacimiento']));
					$cliente->sexo 				= "";
					$cliente->telefono			= $row['telefono'];
					$cliente->direccion			= $row['direccion'];
					$cliente->correo			= $row['correo_electronico'];
					$cliente->save();
				}
			}
			$response = array(
				'cod' => '200',
				'mensaje' => 'Carga realizada con éxito',
			);
		} else {
			$response = array(
				'cod' => '400',
				'mensaje' => 'Hubo un error al leer el archivo',
			);
		}

		//Eliminar archivo temporal
		\Storage::disk('archivos')->delete($nombreArchivoTmp . "." . $extension);
		
		return $response;
	}

	public static function descuentos_aplicados(Request $request)
	{
		ini_set('memory_limit', '-1');
		$archivo = $request->file('aplicados');

		$nombre_original = $archivo->getClientOriginalName();
		$extension = $archivo->getClientOriginalExtension();
		$nombreArchivoTmp = rand ( 0 , 99999 );

		$re = \Storage::disk('archivos')->put($nombreArchivoTmp . "." . $extension, \File::get($archivo));
		$ruta = storage_path('archivos') . "/" . $nombreArchivoTmp . "." . $extension;


		if ($re) {
			$cl = \Excel::load($ruta)->toArray();
			foreach ($cl as $key => $row) {
				if ($row['documento']!='') {
					$cliente = \App\Clientes::where("documento", "=", $row['documento'])->first();
					$entidad = \App\Entidades::where("id", "=", $row['tercero'])->first();
					$pagaduria = \App\Pagadurias::find($request->input("pagaduria"));

					if ($cliente === null) {
						$cliente = new \App\Clientes;
						$cliente->users_id			= \Auth::user()->id;
						$cliente->tipodocumento		= $row['tipo_documento'];
						$cliente->documento 		= $row['documento'];
						$cliente->nombres 			= $row['nombre'];
						$cliente->save();
					}

					if ($entidad === null) {
						$entidad = new \App\Entidades;
						$entidad->id				= $row['tercero'];
						$entidad->entidad			= $row['nombre_del_tercero'];
						$entidad->save();
					}

					$descuento = \App\Descuentosaplicados::where("periodo", "=", $row['periodo'])
						->where("entidades_id", "=", $row['tercero'])
						->where("clientes_id", "=", $cliente->id)
						->first();

					
					
					if ($descuento === null) {
						$nuevoDescuentoA = new \App\Descuentosaplicados;
						$nuevoDescuentoA->clientes_id		= $cliente->id;
						$nuevoDescuentoA->entidades_id 		= $entidad->id;
						$nuevoDescuentoA->pagadurias_id		= $pagaduria->id;
						$nuevoDescuentoA->valor				= money_format('%.0n', $row['valor_aplicado']);
						$nuevoDescuentoA->pagare			= $row['pagare'];
						$nuevoDescuentoA->periodo			= $row['periodo'];
						$nuevoDescuentoA->saldo				= money_format('%.0n', $row['saldo']);
						$nuevoDescuentoA->valor_total		= money_format('%.0n', $row['valor_total']);
						$nuevoDescuentoA->valor_pagado		= money_format('%.0n', $row['valor_pagado']);
						$nuevoDescuentoA->porcentaje		= $row['porcentaje'];
						$nuevoDescuentoA->fecha				= $row['fecha_grabacion'];
						$nuevoDescuentoA->save();
					}
				}
			}

			$response = array(
				'cod' => '200',
				'mensaje' => 'Carga realizada con éxito',
			);
		} else {
			$response = array(
				'cod' => '400',
				'mensaje' => 'Hubo un error al leer el archivo',
			);
		}
		
		//Eliminar archivo temporal
		\Storage::disk('archivos')->delete($nombreArchivoTmp . "." . $extension);

		return $response;
	}

	public static function descuentos_no_aplicados(Request $request)
	{
		ini_set('memory_limit', '-1');
		$archivo = $request->file('no_aplicados');

		$nombre_original = $archivo->getClientOriginalName();
		$extension = $archivo->getClientOriginalExtension();
		$nombreArchivoTmp = rand ( 0 , 99999 );

		$re = \Storage::disk('archivos')->put($nombreArchivoTmp . "." . $extension, \File::get($archivo));
		$ruta = storage_path('archivos') . "/" . $nombreArchivoTmp . "." . $extension;


		if ($re) {
			$cl = \Excel::load($ruta)->toArray();
			foreach ($cl as $key => $row) {
				if ($row['documento']!='') {
					$cliente = \App\Clientes::where("documento", "=", $row['documento'])->first();
					$entidad = \App\Entidades::where("id", "=", $row['tercero'])->first();
					$pagaduria = \App\Pagadurias::find($request->input("pagaduria"));

					if ($cliente === null) {
						$cliente = new \App\Clientes;
						$cliente->users_id			= \Auth::user()->id;
						$cliente->tipodocumento		= $row['tipo_documento'];
						$cliente->documento 		= $row['documento'];
						$cliente->nombres 			= $row['nombre'];
						$cliente->save();
					}

					if ($entidad === null) {
						$entidad = new \App\Entidades;
						$entidad->id				= $row['tercero'];
						$entidad->entidad			= $row['nombre_tercero'];
						$entidad->save();
					}

					$descuento = \App\Descuentosnoaplicados::where("fecha", "=", $row['fecha_grabacion'])
						->where("entidades_id", "=", $row['tercero'])
						->where("clientes_id", "=", $cliente->id)
						->first();

					
					
					if ($descuento === null) {
						$nuevoDescuentoA = new \App\Descuentosnoaplicados;
						$nuevoDescuentoA->clientes_id		= $cliente->id;
						$nuevoDescuentoA->entidades_id 		= $entidad->id;
						$nuevoDescuentoA->pagadurias_id		= $pagaduria->id;
						$nuevoDescuentoA->valor_total		= money_format('%.0n', $row['valor_total']);
						$nuevoDescuentoA->valor_pagado		= money_format('%.0n', $row['valor_pagado']);
						$nuevoDescuentoA->porcentaje		= $row['porcentaje'];
						$nuevoDescuentoA->valor_fijo		= money_format('%.0n', $row['valor_fijo']);
						$nuevoDescuentoA->valor_aplicado	= money_format('%.0n', $row['valor_aplicado']);
						$nuevoDescuentoA->pagare			= $row['pagare'];
						$nuevoDescuentoA->fecha				= $row['fecha_grabacion'];
						$nuevoDescuentoA->inconsistencia	= $row['inconsistencia'];
						$nuevoDescuentoA->saldo				= money_format('%.0n', $row['saldo']);
						$nuevoDescuentoA->save();
					}
				}
			}

			$response = array(
				'cod' => '200',
				'mensaje' => 'Carga realizada con éxito',
			);
		} else {
			$response = array(
				'cod' => '400',
				'mensaje' => 'Hubo un error al leer el archivo',
			);
		}
		
		//Eliminar archivo temporal
		\Storage::disk('archivos')->delete($nombreArchivoTmp . "." . $extension);

		return $response;
	}
	
}