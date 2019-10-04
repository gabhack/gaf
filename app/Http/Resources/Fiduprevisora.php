<?php

namespace App\Http\Resources;
use Illuminate\Http\Request;

class Fiduprevisora
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
				if ($row['documento']!='') {
					$cliente = \App\Clientes::where("documento", "=", $row['documento'])->first();

					if ($cliente === null) {
						$nuevoCliente = new \App\Clientes;

						$nuevoCliente->users_id			= \Auth::user()->id;
						$nuevoCliente->tipodocumento	= $row['tipo_documento'];
						$nuevoCliente->documento		= $row['documento'];
						$nuevoCliente->nombres 			= $row['nombres'];
						$nuevoCliente->apellidos 		= $row['apellidos'];
						$nuevoCliente->fechanto 		= date('Y-m-d', strtotime($row['fecha_nacimiento_docente']));
						$nuevoCliente->sexo 			= $row['sexo'];
						$nuevoCliente->estado_civil 	= $row['descripcion_ec'];
						if ($row['telefono']!=='') {
							$nuevoCliente->telefono		= str_replace("-", "", $row['telefono']);
						}
						$nuevoCliente->direccion		= $row['direccion'];
						$nuevoCliente->correo			= $row['correo'];
						$nuevoCliente->ingresos			= $row['pago_net'];

						$nuevoCliente->save();
					} else {
						$cliente->nombres 				= $row['nombres'];
						$cliente->apellidos 			= $row['apellidos'];
						$cliente->sexo 					= $row['sexo'];
						$cliente->estado_civil 			= $row['descripcion_ec'];
						if ($row['telefono']!=='') {
							$cliente->telefono		= str_replace("-", "", $row['telefono']);
						}
						$cliente->direccion				= $row['direccion'];
						$cliente->correo				= $row['correo'];
						$cliente->ingresos			= $row['pago_net'];
						
						$cliente->save();
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