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
			echo ('Carga realizada con éxito');
		} else {
			echo ('Hubo un error al leer el archivo');
		}

		//Eliminar archivo temporal
		\Storage::disk('archivos')->delete($nombreArchivoTmp . "." . $extension);
		
		?>
		<pre>
			<?php print_r($clientes); ?>
		</pre>
		<?php
		
	}
	
}