<?php

namespace App\Http\Resources;
use App\Jobs\CargarDatosFOPEP_base;
use App\Jobs\CargarDatosFOPEP_desc_aplic;
use App\Jobs\CargarDatosFOPEP_desc_no_aplic;
use Illuminate\Http\Request;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

class Fopep
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
			$job = CargarDatosFOPEP_base::dispatch($ruta, $pagaduria, $nombreArchivoTmp . "." . $extension, $plano)
					->onConnection('database')
					->onQueue('processingComprobantes');

			$response = array(
				'cod' => '200',
				'mensaje' => 'El archivo se ha subido correctamente y se estará procesando.',
			);
		} else {
			$response = array(
				'cod' => '400',
				'mensaje' => 'Hubo un error al leer el archivo.',
			);

            $plano->cont_procesos = -1;
            $plano->errors = "Error: " . $e->getMessage();
            $plano->save();

			//Eliminar archivo temporal
			\Storage::disk('archivos')->delete($nombreArchivoTmp . "." . $extension);
		}
		
		return $response;
	}

	public static function descuentos_aplicados(Request $request, $plano)
	{
		set_time_limit(0);

		$pagaduria = \App\Pagadurias::find($request->input("pagaduria"));
		
		ini_set('memory_limit', '-1');
		$archivo = $request->file('aplicados');

		$nombre_original = $archivo->getClientOriginalName();
		$extension = $archivo->getClientOriginalExtension();
		$nombreArchivoTmp = rand ( 0 , 99999 );

		$re = \Storage::disk('archivos')->put($nombreArchivoTmp . "." . $extension, \File::get($archivo));
		$ruta = storage_path('archivos') . "/" . $nombreArchivoTmp . "." . $extension;


		if ($re) {
			//Tratar el archivo para recibir los datos
			$job = CargarDatosFOPEP_desc_aplic::dispatch($ruta, $pagaduria, $nombreArchivoTmp . "." . $extension, $plano)
					->onConnection('database')
					->onQueue('processingComprobantes');

			$response = array(
				'cod' => '200',
				'mensaje' => 'El archivo se ha subido correctamente y se estará procesando.',
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

	public static function descuentos_no_aplicados(Request $request, $plano)
	{
		set_time_limit(0);

		$pagaduria = \App\Pagadurias::find($request->input("pagaduria"));
		
		ini_set('memory_limit', '-1');
		$archivo = $request->file('no_aplicados');

		$nombre_original = $archivo->getClientOriginalName();
		$extension = $archivo->getClientOriginalExtension();
		$nombreArchivoTmp = rand ( 0 , 99999 );

		$re = \Storage::disk('archivos')->put($nombreArchivoTmp . "." . $extension, \File::get($archivo));
		$ruta = storage_path('archivos') . "/" . $nombreArchivoTmp . "." . $extension;


		if ($re) {
			//Tratar el archivo para recibir los datos
			$job = CargarDatosFOPEP_desc_no_aplic::dispatch($ruta, $pagaduria, $nombreArchivoTmp . "." . $extension, $plano)
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