<?php

namespace App\Http\Resources;
use App\Jobs\CargarDatosFidu_base;
use Illuminate\Http\Request;

class Fiduprevisora
{
	
	public static function base(Request $request, $plano)
	{
		set_time_limit(0);

		$pagaduria = \App\Pagadurias::find($request->input("pagaduria"));
		
		ini_set('memory_limit', '-1');
		$archivo = $request->file('basicos');

		$nombre_original = $archivo->getClientOriginalName();
		$extension = $archivo->getClientOriginalExtension();
		$nombreArchivoTmp = rand ( 0 , 99999 );

		$re = \Storage::disk('archivos')->put($nombreArchivoTmp . "." . $extension, \File::get($archivo));
		$ruta = storage_path('archivos') . "/" . $nombreArchivoTmp . "." . $extension;
		
		if ($re) {
			//Tratar el archivo para recibir los datos
			$job = CargarDatosFidu_base::dispatch($ruta, $pagaduria, $nombreArchivoTmp . "." . $extension, $plano)
					->onConnection('database')
					->onQueue('processingComprobantes');

			$response = array(
				'cod' => '200',
				'mensaje' => 'Carga realizada con éxito',
			);
		} else {
			$response = array(
				'cod' => '400',
				'mensaje' => 'Hubo un error al leer el archivo',
			);

            $plano->cont_procesos = -1;
            $plano->errors = "Error: " . $e->getMessage();
            $plano->save();

			//Eliminar archivo temporal
			\Storage::disk('archivos')->delete($nombreArchivoTmp . "." . $extension);
		}
		
		return $response;
	}
	
}