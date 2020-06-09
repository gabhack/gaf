<?php

namespace App\Http\Resources;
use Illuminate\Http\Request;
use App\Jobs\LeerDatosComprobantes;
use App\Helper;
use Exception;

class Cauca
{
	
	public static function base(Request $request)
	{
		try {
			ini_set('memory_limit', '-1');
			
			$file = \File::get( $request->file('basicos') );
			
			$titulos 	= array();
			$tmpDatos 	= array();
			$datos 		= array();
			$array 		= array();

			$personas 	= array();
		
			foreach (explode("\n", $file) as $key => $line){
				if($key == 0)
				{
					$r = preg_replace(
									  '/
										^
										[\pZ\p{Cc}\x{feff}]+
										|
										[\pZ\p{Cc}\x{feff}]+$
									   /ux',
									  '', 
									  $line);
					$titulos = preg_split("/[\t]/", $r);				
				}
				else if($line == "" )
					continue;
				else
				{
					$tmpDatos = preg_split("/[\t]/", $line);
					$i = 0;
					foreach($titulos as $k => $titulo)
					{
						$datos[trim(strtolower($titulo))] = $tmpDatos[$k];
					}
					
					// Guardar en BD
					if (is_numeric(strpos($datos['ciudad'], '('))) {
						preg_match('#\((.*?)\)#', $datos['ciudad'], $match);
					}
					$ciudadstr = strtoupper(trim(str_replace($match[0], '', $datos['ciudad'])));
					
					$cliente = \App\Clientes::where("documento", "=", $datos['numvinculacion'])->first();
					$ciudad = \App\Ciudades::where('ciudad', $ciudadstr)->first();
					
					//Crear ciudad
                    if ($ciudad === null) {
						$ciudad = new \App\Ciudades;
						$ciudad->departamentos_id = 1;
						$ciudad->ciudad = $ciudadstr;
						$ciudad->save();
					}

					//Cliente crear-actualizar existente
					if ($cliente === null) 
					{
						$cliente = new \App\Clientes;
						$cliente->users_id				= \Auth::user()->id;
						$cliente->ciudades_id 			= $ciudad['id'];
						$cliente->tipodocumento			= 'CC';
						$cliente->documento 			= $datos['numvinculacion'];
						$cliente->sexo 					= $datos['genero'];
						$cliente->direccion 			= $datos['direccion'];
						$cliente->nombres 				= $datos['empleado'];
						$cliente->centro_costo 			= $datos['centrocosto'];
						$cliente->cargo 				= $datos['cargoempresa'];
						$cliente->tipo_contratacion 	= $datos['nivelcontratacion'];
						$cliente->grado 				= $datos['grado'];
						$cliente->telefono 				= $datos['telefono'];
						$cliente->correo				= $datos['email'];
						$cliente->save();
					} 
					else 
					{
						if ($cliente->ciudades_id == '') {
							$cliente->ciudades_id 			= $ciudad['id'];
						}
						if ($cliente->sexo == '') {
							$cliente->sexo 					= $datos['genero'];
						}
						if ($cliente->direccion == '') {
							$cliente->direccion 			= $datos['direccion'];
						}
						if ($cliente->nombres == '') {
							$cliente->nombres 				= $datos['empleado'];
						}
						if ($cliente->centro_costo == '') {
							$cliente->centro_costo 			= $datos['centrocosto'];
						}
						if ($cliente->cargo == '') {
							$cliente->cargo 				= $datos['cargoempresa'];
						}
						if ($cliente->tipo_contratacion == '') {
							$cliente->tipo_contratacion 	= $datos['nivelcontratacion'];
						}
						if ($cliente->grado == '') {
							$cliente->grado 				= $datos['grado'];
						}
						if ($cliente->telefono == '') {
							$cliente->telefono 				= $datos['telefono'];
						}
						if ($cliente->correo == '') {
							$cliente->correo				= $datos['email'];
						}

						//Guardamos en caso de que haya cambios
						if (count($cliente->getDirty()) > 0)
						{
							$cliente->save();
						}
					}
				}
			}
			$response = array(
				'cod' => '200',
				'mensaje' => 'Se han actualizado los datos correctamente.',
			);
		} catch (Exception $e) {
			$response = array(
				'cod' => '400',
				'mensaje' => $e->getMessage(),
			);
			echo '<pre>';
			print_r($e->getMessage());
			echo '</pre>';
		}
		return $response;
    }

	public static function comprobante_pago(Request $request)
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
			$job = LeerDatosComprobantes::dispatch($ruta, $pagaduria, $nombreArchivoTmp . "." . $extension, $plano)
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
			//Eliminar archivo temporal
			\Storage::disk('archivos')->delete($nombreArchivoTmp . "." . $extension);
		}
		return $response;
	}

	public static function mensajes_liquidacion(Request $request)
	{
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

			$parseador = new \Smalot\PdfParser\Parser();
			$documento = $parseador->parseFile($ruta);

			$text = $documento->getText();
			$lineas = explode("\n", $text);

			//Extraer el periodo
			$periodoline = explode(' ', $lineas[6]);
			$periodo = substr($periodoline[9], 0, -2);

			//Extraer Mensajes de liquidacion organizados
			$mensajes = array();
			foreach ($lineas as $key => $linea) {
				$datos = explode("	", $linea);
				$val = array_search('NumVinculacion', $datos);

				if (sizeof($datos) == 4 && $datos[0] && $val !== 0) {
					$mensajes['precaucion'][] = array(
						'documento' => $datos[0],
						'nombres' => $datos[1],
						'mensaje' => $datos[2]
					);
				} elseif (sizeof($datos) > 4) {
					$aux = 0;
					$tmpcedula = '';
					$tmpnombres = '';
					foreach ($datos as $dato) {
						$aux++;
						if ($dato != '') {
							if ($aux == 1) {
								$tmpcedula = $dato;
							} elseif ($aux == 2) {
								$tmpnombres = $dato;
							} elseif ($aux == 3) {
								$mensajes['sobreendeudado'][] = array(
									'documento' => $tmpcedula,
									'nombres' => $tmpnombres,
									'mensaje' => $dato
								);
								$tmpcedula = '';
								$tmpnombres = '';
								$aux = 0;
							}
						}
					}
				}
			}

			//Guardar los datos en base de datos
			foreach ($mensajes['precaucion'] as $key => $mensajeprecaucion) {
				$cliente = \App\Clientes::where("documento", "=", $mensajeprecaucion['documento'])->first();

				//Cliente crear-actualizar existente
				if ($cliente === null) {
					$cliente = new \App\Clientes;
					$cliente->users_id				= \Auth::user()->id;
					$cliente->tipodocumento			= 'CC';
					$cliente->documento 			= $mensajeprecaucion['documento'];
					$cliente->nombres 				= $mensajeprecaucion['nombres'];
					$cliente->save();
				}

				//Insertar mensaje de precaución
				$mensaje = \App\Mensajesprecaucion::where("periodo", "=", $periodo)
					->where("clientes_id", "=", $cliente->id)
					->where("mensaje", "=", $mensajeprecaucion['mensaje'])
					->first();
				
				if ($mensaje === null) {
					$mensaje = new \App\Mensajesprecaucion;
					$mensaje->clientes_id		= $cliente->id;
					$mensaje->tipo_mensaje		= 'Precaución';
					$mensaje->mensaje			= $mensajeprecaucion['mensaje'];
					$mensaje->periodo			= $periodo;
					$mensaje->save();
				}
			}

			//Descuentos no aplicados
			foreach ($mensajes['sobreendeudado'] as $key => $mensajesobreendeudado) {
				$cliente = \App\Clientes::where("documento", "=", $mensajesobreendeudado['documento'])->first();

				//extraer valor de cuota
				$datosmensaje = explode(" ", $mensajesobreendeudado['mensaje']);
				$searchword = 'V=';
				$match = array_filter($datosmensaje, function($var) use ($searchword) { return preg_match("/\b$searchword\b/i", $var); });
				$valor = preg_replace('/[^0-9]+/', '', reset($match));

				//Cliente crear-actualizar existente
				if ($cliente === null) {
					$cliente = new \App\Clientes;
					$cliente->users_id				= \Auth::user()->id;
					$cliente->tipodocumento			= 'CC';
					$cliente->documento 			= $mensajesobreendeudado['documento'];
					$cliente->nombres 				= $mensajesobreendeudado['nombres'];
					$cliente->save();
				}

				$descuento = \App\Descuentosnoaplicados::where("periodo", "=", $periodo)
					->where("pagadurias_id", "=", $pagaduria->id)
					->where("clientes_id", "=", $cliente->id)
					->where("inconsistencia", "=", $mensajesobreendeudado['mensaje'])
					->first();
				
				if ($descuento === null) {
					$descuento = new \App\Descuentosnoaplicados;
					$descuento->clientes_id		= $cliente->id;
					$descuento->pagadurias_id	= $pagaduria->id;
					$descuento->valor_fijo		= $valor;
					$descuento->inconsistencia	= $mensajesobreendeudado['mensaje'];
					$descuento->periodo			= $periodo;
					$descuento->save();
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

	public static function embargos(Request $request)
	{
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

			$parseador = new \Smalot\PdfParser\Parser();
			$documento = $parseador->parseFile($ruta);

			$text = $documento->getText();
			$grupoembargos = explode("Embargo Tipo:	", $text);

			$embargos = array();
			$fecha = '';
			
			//Extraer datos
			foreach ($grupoembargos as $indice => $grupo) {
				$lineasgrupo = explode("\n", $grupo);

				//Extraer fecha
				if ($indice == 0) {
					$datoslinea = explode("	", $lineasgrupo[3]);
					$ano = substr($datoslinea[1], 0, 4);
					$mes = substr($datoslinea[1], 4, 2);
					$dia = substr($datoslinea[1], -2);

					$fecha = $ano . '-' . $mes . '-' . $dia;
				}
				
				foreach ($lineasgrupo as $key => $linea) {
					$nuevoembargo = array();

					$lineaexcluida = str_replace('Total Egresos- Embargos	Total	Embargos	Neto	', '', $linea);
					$datoslinea = explode("	", $lineaexcluida);

					if (sizeof($datoslinea)>7) {
						$explode_fecha = explode('/', $datoslinea[4]);
						$embargos[] = array(
							'empleado' => array(
								'documento' => $datoslinea[0],
								'nombres' => $datoslinea[1]
							),
							'demandante' => array(
								'documento' => $datoslinea[2],
								'nombres' => $datoslinea[3]
							),
							'fecha_embargo' =>  $explode_fecha[2] . '-' . $explode_fecha[1] . '-' . $explode_fecha[0],
							'tipo_embargo' => $lineasgrupo[0],
							'valor_embargo' => trim(intval(preg_replace('/[^0-9]+/', '', $datoslinea[sizeof($datoslinea)-3]), 10))
						);
					}
				}
			}

			//Guardar en BD
			foreach ($embargos as $indice => $embargo) {

				//Cliente crear-actualizar existente
				$cliente = \App\Clientes::where("documento", "=", $embargo['empleado']['documento'])->first();
				if ($cliente === null) {
					$cliente = new \App\Clientes;
					$cliente->users_id				= \Auth::user()->id;
					$cliente->tipodocumento			= 'CC';
					$cliente->documento 			= $embargo['empleado']['documento'];
					$cliente->nombres 				= $embargo['empleado']['nombres'];
					$cliente->save();
				}

				//Motivo crear-actualizar existente
				$motivo = \App\Motivosembargos::where("motivo", "=", $embargo['tipo_embargo'])->first();
				if ($motivo === null) {
					$motivo = new \App\Motivosembargos;
					$motivo->motivo 				= $embargo['tipo_embargo'];
					$motivo->save();
				}

				//Embargo crear-actualizar existente
				$embargo_n = \App\Embargos::where("clientes_id", "=", $cliente['id'])
					->where("fecha_embargo", "=", $embargo['fecha_embargo'])
					->where("documento_demandante", "=", $embargo['demandante']['documento'])
					->first();
				if ($embargo_n === null) {
					$embargo_n = new \App\Embargos;
					$embargo_n->motivos_id 				= $motivo->id;
					$embargo_n->clientes_id 			= $cliente->id;
					$embargo_n->pagadurias_id 			= $pagaduria->id;
					$embargo_n->documento_demandante 	= $embargo['demandante']['documento'];
					$embargo_n->nombres_demandante	 	= $embargo['demandante']['nombres'];
					$embargo_n->fecha_embargo		 	= $embargo['fecha_embargo'];
					$embargo_n->valor				 	= $embargo['valor_embargo'];
					$embargo_n->fecha				 	= $fecha;
					$embargo_n->save();
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

	public static function conceptos_liquidacion(Request $request)
	{
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