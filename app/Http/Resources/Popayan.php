<?php

namespace App\Http\Resources;
use Illuminate\Http\Request;
use App\Helper;
use Exception;

class Popayan
{
	
	public static function base(Request $request)
	{
		ini_set('memory_limit', '-1');
		
		$file = \File::get( $request->file('basicos') );
		
		$titulos 	= array();
		$tmpDatos 	= array();
		$datos 		= array();
		$array 		= array();
		
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
					$datos[trim(strtolower($titulo))] = $tmpDatos[$i];
					$datos['created_at'] = \Carbon\Carbon::now();
					$datos['updated_at'] = \Carbon\Carbon::now();
					$i++;
				}
				
				$array[] = $datos;				
			}
			
			 if($key % 1500 == 0 && count($array) != 0)
			{				
				$plano = \DB::table('tmp_base_secretarias')->insert($array);
				$array = array();
			}
		}
		
		$plano = \DB::table('tmp_base_secretarias')->insert($array);
	}

	public static function comprobante_pago(Request $request)
	{
		try {
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

			$parseador = new \Smalot\PdfParser\Parser();
			$documento = $parseador->parseFile($ruta);

			$text = $documento->getText();
			$paginas = explode("Comprobante de Pago	Periodo de pago:", $text);

			$personas = array();

			if (sizeof($paginas) != 1) {
				foreach ($paginas as $indice => $pagina) {

					if ($indice > 0) {
						// printf("<h1>Persona #%02d</h1>", $indice);
						$lineas = explode("\n", $pagina);

						// Extraer nombres
						$nombres = trim(preg_replace('/[0-9]+/', '', $lineas[7]));
						// Extraer documento
						$documento = trim(intval(preg_replace('/[^0-9]+/', '', $lineas[7]), 10));
						// Extraer cargo
						$cargo = trim($lineas[8]);
						// Extraer ingresos
						$ingresos = trim(intval(preg_replace('/[^0-9]+/', '', $lineas[9]), 10));
						// Extraer ciudad
						$lineas[10] = str_replace('Ciudad:', '', $lineas[10]);
						preg_match('#\((.*?)\)#', $lineas[10], $match);
						$ciudad = strtoupper(trim(str_replace($match[0], '', $lineas[10])));
						// Extraer tipo contratación
						$linea_contr = explode('	', $lineas[13]);
						$tipo_contratacion = trim($linea_contr[3]);
						// Extraer centro de costos
						$centro_costos = trim($lineas[6]);
						// Extraer grado
						$grado = trim(str_replace('Grado:', '', $lineas[16]));
						// Extraer periodo
						setlocale (LC_TIME, "es_CO");
						$linea_periodo = explode('	', $lineas[0]);
						$periodo = strftime("%Y%m", strtotime(trim($linea_periodo[1])));
						// Extraer ingresos totales - egresos totales
						$keyinicio = array_search('Concepto	Ingresos	Egresos	Cuotas	Dias	CodConcepto	', $lineas);
						$keyfin = array_search('Firma	', $lineas);
						$ingresos_egresos = explode("	", $lineas[$keyfin+1]);
						$ingresos_totales = substr(trim(intval(preg_replace('/[^0-9]+/', '', $ingresos_egresos[0]), 10)), 0, -2);
						$egresos_totales = substr(trim(intval(preg_replace('/[^0-9]+/', '', $ingresos_egresos[1]), 10)), 0, -2);
						// Extracción de datos ingresos-egresos
						$registros = array();
						$tmp_casoespecial = array();
						$tmp_casoespecial['codConcepto'] = '';
						$tmp_casoespecial['concepto'] = '';
						$tmp_casoespecial['valor'] = '';
						$encasoespecial = false;
						$suma_temporal = 0;
						for ($i=$keyinicio+1; $i < $keyfin; $i++) {
							$datos_registro = explode("	", $lineas[$i]);
							if (isset($datos_registro[1])) {
								if((strpos($datos_registro[1], '0') !== false)){
									$valor_sin_formato = substr(trim(intval(preg_replace('/[^0-9]+/', '', $datos_registro[1]), 10)), 0, -2);
									$suma_temporal = $suma_temporal+(int)$valor_sin_formato;
									if (($ingresos_totales/$suma_temporal) >= 1) {
										$registros['ingresos'][] = array(
											'codConcepto' => $datos_registro[sizeof($datos_registro)-2],
											'concepto' => $datos_registro[0],
											'valor' => $valor_sin_formato,
										);
									} else {
										$registros['egresos'][] = array(
											'codConcepto' => $datos_registro[2],
											'concepto' => $datos_registro[0],
											'valor' => $valor_sin_formato,
										);
									}
								}
							} else {
								$encasoespecial = true;
							}

							if ($encasoespecial) {
								$stringcomparar = limpiarCaracteresEspeciales($datos_registro[0]);
								if (ctype_alnum($stringcomparar)) {
									$tmp_casoespecial['concepto'] .= ' ' . $datos_registro[0];
								} elseif ($stringcomparar !== '') {
									$encasoespecial = false;

									if (is_numeric($datos_registro[0][0])){
										$valor_sin_formato = substr(trim(intval(preg_replace('/[^0-9]+/', '', $datos_registro[0]), 10)), 0, -2);
										$suma_temporal = $suma_temporal+(int)$valor_sin_formato;
										$tmp_casoespecial['codConcepto'] = $datos_registro[1];
										$tmp_casoespecial['concepto'] = trim($tmp_casoespecial['concepto']);
										$tmp_casoespecial['valor'] = $valor_sin_formato;
									}
									if (($ingresos_totales/$suma_temporal) >= 1) {
										$registros['ingresos'][] = $tmp_casoespecial;
									} else {
										$registros['egresos'][] = $tmp_casoespecial;
									}
									$tmp_casoespecial['concepto'] = '';
								}
							}
						}

						$personas[] = array(
							'nombres' => $nombres,
							'documento' => $documento,
							'cargo' => $cargo,
							'ciudad' => $ciudad,
							'centro_costos' => $centro_costos,
							'grado' => $grado,
							'tipo_contratacion' => $tipo_contratacion,
							'ingresos_base' => $ingresos,
							'periodo' => $periodo,
							'conceptos_financieros' => array(
								'ingresos_totales' => $ingresos_totales,
								'egresos_totales' => $egresos_totales,
								'detallado_conceptos' => $registros
							)
						);
					}
				}

				//Registrar nuevo cliente
				foreach ($personas as $indice => $persona) {
					$cliente = \App\Clientes::where("documento", "=", $persona['documento'])->first();
					$ciudad = \App\Ciudades::where('ciudad', $persona['ciudad'] )->first();

					try {
						//Cliente crear-actualizar existente
						if ($cliente === null) {
							$cliente = new \App\Clientes;
							$cliente->users_id				= \Auth::user()->id;
							$cliente->ciudades_id 			= $ciudad['id'];
							$cliente->tipodocumento			= 'CC';
							$cliente->documento 			= $persona['documento'];
							$cliente->nombres 				= $persona['nombres'];
							$cliente->centro_costo 			= $persona['centro_costos'];
							$cliente->cargo 				= $persona['cargo'];
							$cliente->tipo_contratacion 	= $persona['tipo_contratacion'];
							$cliente->grado 				= $persona['grado'];
							$cliente->ingresos 				= $persona['ingresos_base'];
							$cliente->save();
						} else {
							if (!$cliente['apellidos']) {
								$cliente->nombres 				= $persona['nombres'];
							}

							$cliente->nombres 				= $persona['nombres'];
							$cliente->centro_costo 			= $persona['centro_costos'];
							$cliente->cargo 				= $persona['cargo'];
							$cliente->tipo_contratacion 	= $persona['tipo_contratacion'];
							$cliente->grado 				= $persona['grado'];
							$cliente->ingresos 				= $persona['ingresos_base'];

							$cliente->save();
						}

						//creación de registro
						$registro = \App\Registrosfinancieros::where("periodo", "=", $persona['periodo'])
							->where("pagadurias_id", "=", $pagaduria->id)
							->where("clientes_id", "=", $cliente->id)
							->first();
						
						if ($registro === null) {
							$registro = new \App\Registrosfinancieros;
							$registro->clientes_id		= $cliente->id;
							$registro->pagadurias_id	= $pagaduria->id;
							$registro->periodo			= $persona['periodo'];
							$registro->save();
						}

						//Ingresos
						if (isset($persona['conceptos_financieros']['detallado_conceptos']['ingresos'])) {
							foreach ($persona['conceptos_financieros']['detallado_conceptos']['ingresos'] as $key => $ingreso) {
								$ingresoN = \App\Ingresosaplicados::where("registros_id", "=", $registro->id)
									->where("cod_concepto", "=", $ingreso['codConcepto'])
									->first();
								
								if ($ingresoN === null) {
									$ingresoN = new \App\Ingresosaplicados;
									$ingresoN->registros_id		= $registro->id;
									$ingresoN->cod_concepto		= $ingreso['codConcepto'];
									$ingresoN->concepto			= $ingreso['concepto'];
									$ingresoN->valor			= $ingreso['valor'];
									$ingresoN->save();
								}
							}
						}

						//Descuentos
						if (isset($persona['conceptos_financieros']['detallado_conceptos']['egresos'])) {
							foreach ($persona['conceptos_financieros']['detallado_conceptos']['egresos'] as $key => $egreso) {
								$descuentoAplicado = \App\Descuentosaplicados::where("registros_id", "=", $registro->id)
									->where("cod_concepto", "=", $egreso['codConcepto'])
									->first();
								
								if ($descuentoAplicado === null) {
									$descuentoAplicado = new \App\Descuentosaplicados;
									$descuentoAplicado->registros_id		= $registro->id;
									$descuentoAplicado->cod_concepto		= $egreso['codConcepto'];
									$descuentoAplicado->concepto			= $egreso['concepto'];
									$descuentoAplicado->valor				= $egreso['valor'];
									$descuentoAplicado->save();
								}
							}
						}

					} catch(Exception $ex) {
						//----------------------------------------
						//Eliminar archivo temporal
						\Storage::disk('archivos')->delete($nombreArchivoTmp . "." . $extension);
						throw new Exception("Error: No fue posible actualizar el registro #" . ($indice+1) . ". - Mensaje de error:" . $ex->getMessage());
					}
				}

				$response = array(
					'cod' => '200',
					'mensaje' => 'Se han actualizado los registros encontrados en el archivo fuente.',
				);
			} else {
				//----------------------------------------
				//Eliminar archivo temporal
				\Storage::disk('archivos')->delete($nombreArchivoTmp . "." . $extension);
				throw new Exception("Error: El archivo no es válido");
			}
			//----------------------------------------
			//Eliminar archivo temporal
			\Storage::disk('archivos')->delete($nombreArchivoTmp . "." . $extension);
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
	
}