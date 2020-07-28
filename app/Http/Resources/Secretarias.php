<?php

namespace App\Http\Resources;
use Illuminate\Http\Request;
use App\Jobs\CargarDatosComprobantes;
use App\Jobs\CargarDatosMensajesLiquidacion;
use App\Jobs\CargarDatosEmbargos;
use App\Jobs\CargarDatosBase;
use App\Helper;
use Exception;

class Secretarias
{
	
	public static function base(Request $request, $plano)
	{
		try {
			$pagaduria = \App\Pagadurias::find($request->input("pagaduria"));

			//Obtener el archivo y guardarlo en la carpeta temporal

			ini_set('memory_limit', '-1');
			$archivo = $request->file('basicos');

			$nombre_original = $archivo->getClientOriginalName();
			$extension = $archivo->getClientOriginalExtension();
			$nombreArchivoTmp = rand ( 0 , 99999 );

			$re = \Storage::disk('archivos')->put($nombreArchivoTmp . "." . $extension, \File::get($archivo));
			$ruta = storage_path('archivos') . "/" . $nombreArchivoTmp . "." . $extension;
			
			//----------------------------------------
			//Tratar el archivo para recibir los datos
			$job = CargarDatosBase::dispatch($ruta, $pagaduria, $nombreArchivoTmp . "." . $extension, $plano)
					->onConnection('database')
					->onQueue('processingComprobantes');

			$response = array(
				'cod' => '200',
				'mensaje' => 'El archivo se ha subido correctamente y se estará procesando.',
			);
		} catch (Exception $e) {
			$response = array(
				'cod' => '400',
				'mensaje' => $e->getMessage(),
			);

            $plano->cont_procesos = -1;
            $plano->errors = "Error: " . $e->getMessage();
            $plano->save();

			//Eliminar archivo temporal
			\Storage::disk('archivos')->delete($nombreArchivoTmp . "." . $extension);
		}

		return $response;
	}

	public static function comprobante_pago(Request $request, $plano)
	{
		try {
			set_time_limit(0);

			$pagaduria = \App\Pagadurias::find($request->input("pagaduria"));

			//Obtener el archivo y guardarlo en la carpeta temporal

			ini_set('memory_limit', '-1');
			$archivo = $request->file('comppago');

			$nombre_original = $archivo->getClientOriginalName();
			$extension = $archivo->getClientOriginalExtension();
			$nombreArchivoTmp = rand ( 0 , 99999 );

			$re = \Storage::disk('archivos')->put($nombreArchivoTmp . "." . $extension, \File::get($archivo));
			$ruta = storage_path('archivos') . "/" . $nombreArchivoTmp . "." . $extension;

			//----------------------------------------
			//Tratar el archivo para recibir los datos
			$job = CargarDatosComprobantes::dispatch($ruta, $pagaduria, $nombreArchivoTmp . "." . $extension, $plano)
					->onConnection('database')
					->onQueue('processingComprobantes');

			$response = array(
				'cod' => '200',
				'mensaje' => 'El archivo se ha subido correctamente y se estará procesando.',
			);
		} catch (Exception $e) {
			$response = array(
				'cod' => '400',
				'mensaje' => $e->getMessage(),
			);

            $plano->cont_procesos = -1;
            $plano->errors = "Error: " . $e->getMessage();
			$plano->save();
			
			//Eliminar archivo temporal
			\Storage::disk('archivos')->delete($nombreArchivoTmp . "." . $extension);
		}
		return $response;
	}

	public static function mensajes_liquidacion(Request $request, $plano)
	{
		set_time_limit(0);
		
		try {
			$pagaduria = \App\Pagadurias::find($request->input("pagaduria"));

			//Obtener el archivo y guardarlo en la carpeta temporal

			ini_set('memory_limit', '-1');
			$archivo = $request->file('mens_liquidacion');

			$nombre_original = $archivo->getClientOriginalName();
			$extension = $archivo->getClientOriginalExtension();
			$nombreArchivoTmp = rand ( 0 , 99999 );

			$re = \Storage::disk('archivos')->put($nombreArchivoTmp . "." . $extension, \File::get($archivo));
			$ruta = storage_path('archivos') . "/" . $nombreArchivoTmp . "." . $extension;

			//----------------------------------------
			//Tratar el archivo para recibir los datos
			$job = CargarDatosMensajesLiquidacion::dispatch($ruta, $pagaduria, $nombreArchivoTmp . "." . $extension, $plano)
					->onConnection('database')
					->onQueue('processingComprobantes');
			
			$response = array(
				'cod' => '200',
				'mensaje' => 'El archivo se ha subido correctamente y se estará procesando.',
			);
		} catch (Exception $e) {
			$response = array(
				'cod' => '400',
				'mensaje' => $e->getMessage(),
			);

            $plano->cont_procesos = -1;
            $plano->errors = "Error: " . $e->getMessage();
			$plano->save();
			
			//Eliminar archivo temporal
			\Storage::disk('archivos')->delete($nombreArchivoTmp . "." . $extension);
		}
		return $response;
	}

	public static function embargos(Request $request, $plano)
	{
		set_time_limit(0);
		
		try {
			$pagaduria = \App\Pagadurias::find($request->input("pagaduria"));

			//Obtener el archivo y guardarlo en la carpeta temporal

			ini_set('memory_limit', '-1');
			$archivo = $request->file('embargos');

			$nombre_original = $archivo->getClientOriginalName();
			$extension = $archivo->getClientOriginalExtension();
			$nombreArchivoTmp = rand ( 0 , 99999 );

			$re = \Storage::disk('archivos')->put($nombreArchivoTmp . "." . $extension, \File::get($archivo));
			$ruta = storage_path('archivos') . "/" . $nombreArchivoTmp . "." . $extension;

			//----------------------------------------
			//Tratar el archivo para recibir los datos

			$job = CargarDatosEmbargos::dispatch($ruta, $pagaduria, $nombreArchivoTmp . "." . $extension, $plano)
					->onConnection('database')
					->onQueue('processingComprobantes');
			
			$response = array(
				'cod' => '200',
				'mensaje' => 'El archivo se ha subido correctamente y se estará procesando.',
			);
		} catch (Exception $e) {
			$response = array(
				'cod' => '400',
				'mensaje' => $e->getMessage(),
			);

            $plano->cont_procesos = -1;
            $plano->errors = "Error: " . $e->getMessage();
            $plano->save();

			//Eliminar archivo temporal
			\Storage::disk('archivos')->delete($nombreArchivoTmp . "." . $extension);
		}
		return $response;
	}

	public static function conceptos_liquidacion(Request $request)
	{
		set_time_limit(0);
		
		try {
			$pagaduria = \App\Pagadurias::find($request->input("pagaduria"));

			//Obtener el archivo y guardarlo en la carpeta temporal
			ini_set('memory_limit', '-1');
			$archivo = $request->file('concep_liquid');

			$nombre_original = $archivo->getClientOriginalName();
			$extension = $archivo->getClientOriginalExtension();
			$nombreArchivoTmp = rand ( 0 , 99999 );

			$re = \Storage::disk('archivos')->put($nombreArchivoTmp . "." . $extension, \File::get($archivo));
			$ruta = storage_path('archivos') . "/" . $nombreArchivoTmp . "." . $extension;

			//----------------------------------------
			//Tratar el archivo para recibir los datos

			$parseador = new \Smalot\PdfParser\Parser();
			$documento = $parseador->parseFile($ruta);

			$text = $documento->getText();
			$lineas = explode("\n", $text);
			$grupoconceptos = explode("TOTAL POR CONCEPTO", $text);

			$conceptos = array();
			
			//Extraer datos
			// $fecha = 
			$keyfecha = (array_search('LISTADO DE CONCEPTOS	', $lineas))+1;
			$lineafecha = explode("	", $lineas[$keyfecha]);
			$periodo = strftime("%Y%m", strtotime(trim($lineafecha[1])));

			//Extraer conceptos liquidados
			foreach ($grupoconceptos as $key => $concepto) {
				$lineasconcepto = explode("\n", $concepto);
				$det_concepto = '';

				if ($key == 0) {
					$det_concepto = $lineasconcepto[9];
					foreach ($lineasconcepto as $i => $linea) {
						$datoslinea = explode("	", $linea);
						if (sizeof($datoslinea)>= 4) {
							if ((strpos($datoslinea[2], '0') !== false)) {
								$conceptos[] = array(
									'concepto' => $det_concepto,
									'documento' => trim(intval(preg_replace('/[^0-9]+/', '', $datoslinea[0]), 10)),
									'nombres' => $datoslinea[1],
									'valor_desc' => $datoslinea[2]
								);
							}
						}
					}
				} else {
					$det_concepto = $lineasconcepto[1];
					foreach ($lineasconcepto as $i => $linea) {
						$datoslinea = explode("	", $linea);
						if (sizeof($datoslinea)>= 4) {
							if ((strpos($datoslinea[2], '0') !== false)) {
								$conceptos[] = array(
									'concepto' => $det_concepto,
									'documento' => trim(intval(preg_replace('/[^0-9]+/', '', $datoslinea[0]), 10)),
									'nombres' => $datoslinea[1],
									'valor_desc' => $datoslinea[2]
								);
							}
						}
					}
				}
			}

			//Guardar en BD
			foreach ($conceptos as $indice => $concepto) {
				$cliente = \App\Clientes::where("documento", "=", $concepto['documento'])->first();
				$ciudad = \App\Ciudades::where('ciudad', 'POPAYAN' )->first();

				try {
					//Cliente crear-actualizar existente
					if ($cliente !== null && $indice < 100) {
						echo '<pre>';
						print_r($concepto);
						echo '</pre>';

						//creación de registro
						/*$registro = \App\Registrosfinancieros::where("periodo", "=", $periodo)
							->where("pagadurias_id", "=", $pagaduria->id)
							->where("clientes_id", "=", $cliente->id)
							->first();
					
						if ($registro === null) {
							$registro = new \App\Registrosfinancieros;
							$registro->clientes_id		= $cliente->id;
							$registro->pagadurias_id	= $pagaduria->id;
							$registro->periodo			= $periodo;
							$registro->save();
						}
						
						// Descuentos
						foreach ($concepto as $key => $egreso) {
							$descuentoAplicado = \App\Descuentosaplicados::where("registros_id", "=", $registro->id)
								->where("concepto", "=", $egreso['concepto'])
								->first();
							
							if ($descuentoAplicado === null) {
								$descuentoAplicado = new \App\Descuentosaplicados;
								$descuentoAplicado->registros_id		= $registro->id;
								$descuentoAplicado->concepto			= $egreso['concepto'];
								$descuentoAplicado->valor				= $egreso['valor_desc'];
								$descuentoAplicado->save();
							}
						}*/
					}
				} catch(Exception $ex) {
					//----------------------------------------
					//Eliminar archivo temporal
					\Storage::disk('archivos')->delete($nombreArchivoTmp . "." . $extension);
					throw new Exception("Error: No fue posible actualizar el registro #" . ($indice+1) . ". - Mensaje de error:" . $ex->getMessage());
				}
			}
			
			//----------------------------------------
			//Eliminar archivo temporal
			\Storage::disk('archivos')->delete($nombreArchivoTmp . "." . $extension);
			
			$response = array(
				'cod' => '200',
				'mensaje' => 'Se ha registrado la información correctamente',
			);
		} catch (Exception $e) {
			$response = array(
				'cod' => '400',
				'mensaje' => $e->getMessage(),
			);

			//Eliminar archivo temporal
			\Storage::disk('archivos')->delete($nombreArchivoTmp . "." . $extension);
		}
		return $response;
	}
	
}