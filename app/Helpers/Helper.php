<?php

if(!function_exists('insertOrUpdate')) {
	function insertOrUpdate($table, array $rows){
        
		$first = reset($rows);
        
        $columns = implode( ',',
            array_map( function( $value ) { return "$value"; } , array_keys($first) )
        );
        
        $values = implode( ',', array_map( function( $row ) {
                return '('.implode( ',',
                    array_map( function( $value ) { return '"'.str_replace('"', '""', $value).'"'; } , $row )
                ).')';
            } , $rows )
        );
        
        $updates = implode( ',',
            array_map( function( $value ) { return "$value = VALUES($value)"; } , array_keys($first) )
        );
        
        $sql = "INSERT INTO {$table}({$columns}) VALUES {$values} ON DUPLICATE KEY UPDATE {$updates}";
        
        return $sql;
        // return \DB::statement( $sql );
	}
}

if(!function_exists('planos')) {
	function planos()
	{
		return array(
			'BAS' => 'Datos Basicos',
			'APL' => 'Descuentos Aplicados',
			'NAP' => 'Descuentos No Aplicados',
			'EMB' => 'Embargos',
			'COM' => 'Comprobantes de pago',
			'MLQ' => 'Mensajes de liquidación',
			'CLQ' => 'Conceptos de liquidación',
			'GCP' => 'Detección GCP',
		);
	}
}

if(!function_exists('format')) {
	function format($number)
	{
		return number_format($number, 0, ',', '.');
	}
}

if(!function_exists('mneyformat')) {
	function mneyformat($number)
	{
		return '$ ' . number_format($number, 0, ',', '.');
	}
}

if (!function_exists('consultas')) {
	function consultas()
	{
		return array(
			'BAS' => 'BASIC',
			'PLU' => 'PLUS',
			'PRE' => 'PREMIUM',
			'PPL' => 'PREMIUM PLUS',
			'ELI' => 'ELITE',
			'EPR' => 'ELITE PREMIUM',
		);
	}
}

if (!function_exists('calcularCapacidad')) {
	function calcularCapacidad($vinculacion, $ingresos, $valor_aportes, $asignacion_adicional, $total_dctos, $smlv)
	{
		
		
		// "PENS, 1000000, 0, 0, 96000, 790000"
		if($vinculacion == 'PENS')
		{			
			$compraCartera 	= 	( $ingresos + $asignacion_adicional - $valor_aportes ) / 2;
			$libreInversion = ( ( $ingresos + $asignacion_adicional - $valor_aportes ) / 2 ) - $total_dctos +  $valor_aportes;
			
			// echo ("( $ingresos + $asignacion_adicional - $valor_aportes ) / 2 = array('compraCartera' => $compraCartera, 'libreInversion' => $libreInversion)");
		}
		else
		{
			$base = ( $ingresos - $valor_aportes ) / 2;
			
			if($base > $smlv)
			{
				$libreInversion = ( ( $ingresos + $asignacion_adicional - $valor_aportes ) / 2 ) - $total_dctos + $valor_aportes;
				$compraCartera 	= 	$base - $total_dctos + $valor_aportes;
			}
			else
			{
				$libreInversion = $ingresos + $asignacion_adicional - $smlv - $total_dctos;
				$compraCartera 	= $ingresos + $asignacion_adicional - $valor_aportes - $smlv;
			}			
		}
		
		
		return array(
			'compraCartera' => array(
				'valor' => $compraCartera,
				'color' => ($compraCartera > 0 ? 'verde' : ($compraCartera == 0 ? 'amarillo' : 'rojo'))
			), 
			'libreInversion' => array(
				'valor' => $libreInversion,
				'color' => ($libreInversion > 0 ? 'verde' : ($libreInversion == 0 ? 'amarillo' : 'rojo'))
			)
		);
	}
}

if (!function_exists('getMessage')) {
	function getMessage()
	{
		if(\Session::has('mensaje')){
			return \Session::pull('mensaje');
			\Session::forget('mensaje');
		}
	}
}

if (!function_exists('setMessage')) {
	function setMessage($message, $class)
	{
		\Session::flash('mensaje', '<div class="alert alert-'.$class.'" role="alert">
										<h4 class="alert-heading">Mensaje!</h4>
										<hr />
										<p>'.$message.'</p>
									</div>');
	}
}

if (!function_exists('estados_civiles')) {
	function estados_civiles()
	{
		return array(
			'SOL' => 'SOLTERO',
			'CAS' => 'CASADO',
			'UNI' => 'UNION LIBRE',
			'SEP' => 'SEPARADO',
			'DIV' => 'DIVORCIADO',
			'VIU' => 'VIUDO',
		);
		
	}
}

if (!function_exists('decisiones_capacidades')) {
	function decisiones_capacidades()
	{
		return array(
			'COMP' => 'COMPRA DE CARTERA',
			'LIBR' => 'LIBRE INVERSION',
		);
		
	}
}

if (!function_exists('decisiones_estudios')) {
	function decisiones_estudios()
	{
		return array(
			'APRO' => 'APROBADO',
			'NEGA' => 'NEGADO',
			'ESTU' => 'ESTUDIO',
			'AVCH' => 'PTE. APROBACION VCH',
			'SALD' => 'PTE. SALDO CARTERA',
		);
		
	}
}

if (!function_exists('decisiones_aliados')) {
	function decisiones_aliados()
	{
		return array(
			'VIAB' => 'VIABLE',
			'NOVI' => 'NO VIABLE',
		);
		
	}
}

if (!function_exists('estados_cargos')) {
	function estados_cargos()
	{
		return array(
			'ACTI' => 'ACTIVO',
			'PENS' => 'PENSIONADO',
		);
		
	}
}

if (!function_exists('estados_aliados')) {
	function estados_aliados()
	{
		return array(
			'A' => 'ACTIVO',
			'I' => 'INACTIVO',
		);
		
	}
}

if (!function_exists('estados_solicitudes')) {
	function estados_solicitudes()
	{
		return array(
			'S' => 'SOLICITADO',
			'E' => 'EVALUADO',
		);
		
	}
}

if (!function_exists('sexos')) {
	function sexos()
	{
		return array(
			'F' => 'FEMENINO',
			'M' => 'MASCULINO',
		);
		
	}
}

if (!function_exists('tipos_documento')) {
	function tipos_documento()
	{
		return array(
			'CC' => 'CEDULA DE CIUDADAN&Iacute;A',
			'CE' => 'CEDULA DE EXTRANJER&Iacute;A',
		);
		
	}
}

if (!function_exists('meses')) {
	function meses()
	{
		return array(
			'ENE' => 'ENERO',
			'FEB' => 'FEBRERO',
			'MAR' => 'MARZO',
			'ABR' => 'ABRIL',
			'MAY' => 'MAYO',
			'JUN' => 'JUNIO',
			'JUL' => 'JULIO',
			'AGO' => 'AGOSTO',
			'SEP' => 'SEPTIEMBRE',
			'OCT' => 'OCTUBRE',
			'NOV' => 'NOVIEMBRE',
			'DIC' => 'DICIEMBRE',
		);
		
	}
}

if (!function_exists('calificaciones')) {
	function calificaciones()
	{
		return array(
			'A' => 'A',
			'B' => 'B',
			'C' => 'C',
			'D' => 'D',
			'F' => 'F',
			'G' => 'G',
			'H' => 'H',
			'I' => 'I',
			'J' => 'J',
			'K' => 'K',
		);
		
	}
}

if (!function_exists('compradores')) {
	function compradores()
	{
		return array(
			'CK' => 'CK',
			'ALIA' => 'ALIADO',
		);
	}
}

if (!function_exists('getentidad')) {
	function getentidad($id)
	{
		return App\Entidades::find($id)->entidad;	
	}
}

if (!function_exists('getciudad')) {
	function getciudad($id)
	{
		return App\Ciudades::find($id)->ciudad;	
	}
}

if (!function_exists('ciudades')) {
	function ciudades()
	{
		return App\Ciudades::all();	
	}
}

if (!function_exists('asesores')) {
	function asesores()
	{
		return App\Asesores::all();	
	}
}

if (!function_exists('pagadurias')) {
	function pagadurias()
	{
		return App\Pagadurias::all();	
	}
}

if (!function_exists('limpiarCaracteresEspeciales')) {
	function limpiarCaracteresEspeciales($string){
        $string = str_replace(' ', '', $string);
        $string = htmlentities($string);
        $string = preg_replace('/\&(.)[^;]*;/', '\\1', $string);
        return $string;
    }
}

if(!function_exists('descuentos_por_registro')) {
	function descuentos_por_registro($id_registro){
		try {
			return App\Descuentosaplicados::where("registros_id", "=", $id_registro)->get();
		} catch (\Exception $ex) {
			return $ex-getMessage();
		}	
	}
}

if(!function_exists('ingresos_por_registro')) {
	function ingresos_por_registro($id_registro){
		try {
			return App\Ingresosaplicados::where("registros_id", "=", $id_registro)->get();
		} catch (\Exception $ex) {
			return $ex-getMessage();
		}	
	}
}

if(!function_exists('get_string_between')) {
	function get_string_between($string, $start, $end){
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }
}

if (!function_exists('mes_esp_a_ing')) {
	function mes_esp_a_ing($mes)
	{
		$mesingles = '';
		switch ($mes) {
			case 'Enero':
				$mesingles = 'Jan';
				break;

			case 'Febrero':
				$mesingles = 'Feb';
				break;

			case 'Marzo':
				$mesingles = 'Mar';
				break;

			case 'Abril':
				$mesingles = 'Apr';
				break;

			case 'Mayo':
				$mesingles = 'May';
				break;
				
			case 'Junio':
				$mesingles = 'Jun';
				break;

			case 'Julio':
				$mesingles = 'Jul';
				break;

			case 'Agosto':
				$mesingles = 'Aug';
				break;
				
			case 'Septiembre':
				$mesingles = 'Sep';
				break;

			case 'Octubre':
				$mesingles = 'Oct';
				break;
			
			case 'Noviembre':
				$mesingles = 'Nov';
				break;
				
			case 'Diciembre':
				$mesingles = 'Dec';
				break;
			
			default:
				break;
		}
		return $mesingles;
	}
}

if (!function_exists('totalizar_concepto')) {
	function totalizar_concepto($data)
	{
		$total = 0;
		foreach ($data as $key => $concepto) {
			$total += $concepto->valor;
		}
		return $total;
	}
}

if (!function_exists('sueldobasico')) {
	function sueldobasico($data)
	{
		$res = 0;
		foreach ($data as $key => $concepto) {
			if ($concepto->cod_concepto == 'SUEBA') {
				$res = $concepto->valor;
			}
		}
		return $res;
	}
}

if (!function_exists('sueldoadicional')) {
	function sueldoadicional($data)
	{
		$res = 0;
		foreach ($data as $key => $concepto) {
			if (strpos($concepto->concepto, 'AA-Asignacion Adicional Rector') !== false || strpos($concepto->concepto, 'AA-Asignacion Adicional Coordinador') !== false) {
				$res = $concepto->valor;
			}
		}
		return $res;
	}
}

if (!function_exists('upload_personas')) {
	function upload_personas($personas)
	{
		$resp = "";
		$advertencias = "";
		foreach ($personas as $key => $persona) {
			$arraypersona = (array) $persona;
			try {
				$arraypersona['grado'] = iconv("UTF-8", "ISO-8859-1//IGNORE", $arraypersona['grado']);
				if (mb_detect_encoding($arraypersona['grado']) !== 'ASCII') {
					$arraypersona['grado'] = '';
				}
				$secretaria = explode(' ', $arraypersona['secretaria']);
				$pagaduria = \App\Pagadurias::query()
					->where('pagaduria', 'LIKE', "%{$secretaria[count($secretaria)-1]}%")
					->first();

				if ($arraypersona['nit'] !== '') {
					if ($pagaduria->nit == '') {
						$pagaduria->nit = $arraypersona['nit'];
						$pagaduria->save();
					}
				}
				
				$ciudad = \App\Ciudades::where('ciudad', $arraypersona['ciudad'] )->first();
				if ($arraypersona['ciudad'] !== '') {
					//Crear ciudad
					if ($ciudad === null) {
						$ciudad = new \App\Ciudades;
						$ciudad->ciudad = $arraypersona['ciudad'];
						$ciudad->save();
					}
				}

				$cliente = \App\Clientes::where("documento", "=", $arraypersona['documento'])->first();
				//Cliente crear-actualizar existente
				if ($cliente === null) {
					$cliente = new \App\Clientes;
					$cliente->users_id				= 1;
					if ($cliente !== null) {
						$cliente->ciudades_id 			= $ciudad['id'];
					}
					$cliente->tipodocumento			= 'CC';
					if ($arraypersona['documento'] !== '') {
						$cliente->documento 			= $arraypersona['documento'];
					}
					if ($arraypersona['nombres'] !== '') {
						$cliente->nombres 				= $arraypersona['nombres'];
					}
					if ($arraypersona['apellidos'] !== '') {
						$cliente->apellidos 			= $arraypersona['apellidos'];
					}
					if ($arraypersona['centro_costos'] !== '') {
						$cliente->centro_costo 			= $arraypersona['centro_costos'];
					}
					if ($arraypersona['cargo_docente'] !== '') {
						$cliente->cargo 				= $arraypersona['cargo_docente'];
						$cliente->docente 				= '1';
					} elseif ($arraypersona['cargo_administrativo'] !== '') {
						$cliente->cargo 				= $arraypersona['cargo_administrativo'];
						$cliente->docente 				= null;
					}
					if ($arraypersona['banco'] !== '') {
						$cliente->banco 			= $arraypersona['banco'];
					}
					if ($arraypersona['cuenta'] !== '') {
						$cliente->cuenta 			= $arraypersona['cuenta'];
					}
					if ($arraypersona['caja_compensacion'] !== '') {
						$cliente->caja_compensacion 			= $arraypersona['caja_compensacion'];
					}
					if ($arraypersona['cesantias'] !== '') {
						$cliente->cesantias 			= $arraypersona['cesantias'];
					}
					if ($arraypersona['pension'] !== '') {
						$cliente->pension 			= $arraypersona['pension'];
					}
					if ($arraypersona['tipo_contratacion'] !== '') {
						$cliente->tipo_contratacion 	= $arraypersona['tipo_contratacion'];
					}
					if ($arraypersona['grado'] !== '') {
						$cliente->grado 				= $arraypersona['grado'];
					}
					if ($arraypersona['ingresos_base'] !== '') {
						$cliente->ingresos 				= $arraypersona['ingresos_base'];
					}
					if ($arraypersona['sexo'] !== '') {
						$cliente->sexo 				= $arraypersona['sexo'];
					}
					if ($arraypersona['estado_civil'] !== '') {
						$cliente->estado_civil 				= $arraypersona['estado_civil'];
					}
					if ($arraypersona['fechanto'] !== '') {
						$cliente->fechanto 				= $arraypersona['fechanto'];
					}
					if ($arraypersona['direccion'] !== '') {
						$cliente->direccion 				= $arraypersona['direccion'];
					}
					if ($arraypersona['celular'] !== '') {
						$cliente->celular 				= $arraypersona['celular'];
					}
					if ($arraypersona['telefono'] !== '') {
						$cliente->telefono 				= $arraypersona['telefono'];
					}
					if ($arraypersona['correo'] !== '') {
						$cliente->correo 				= $arraypersona['correo'];
					}
					
					$cliente->save();
				} else {
					
					if ($arraypersona['nombres'] !== '') {
						$cliente->nombres 				= $arraypersona['nombres'];
					}
					if ($arraypersona['apellidos'] !== '') {
						$cliente->apellidos 			= $arraypersona['apellidos'];
					}
					if ($arraypersona['centro_costos'] !== '') {
						$cliente->centro_costo 			= $arraypersona['centro_costos'];
					}
					if ($arraypersona['cargo_docente'] !== '') {
						$cliente->cargo 				= $arraypersona['cargo_docente'];
						$cliente->docente 				= '1';
					} elseif ($arraypersona['cargo_administrativo'] !== '') {
						$cliente->cargo 				= $arraypersona['cargo_administrativo'];
						$cliente->docente 				= null;
					}
					if ($arraypersona['banco'] !== '') {
						$cliente->banco 			= $arraypersona['banco'];
					}
					if ($arraypersona['cuenta'] !== '') {
						$cliente->cuenta 			= $arraypersona['cuenta'];
					}
					if ($arraypersona['caja_compensacion'] !== '') {
						$cliente->caja_compensacion 			= $arraypersona['caja_compensacion'];
					}
					if ($arraypersona['cesantias'] !== '') {
						$cliente->cesantias 			= $arraypersona['cesantias'];
					}
					if ($arraypersona['pension'] !== '') {
						$cliente->pension 			= $arraypersona['pension'];
					}
					if ($arraypersona['tipo_contratacion'] !== '') {
						$cliente->tipo_contratacion 	= $arraypersona['tipo_contratacion'];
					}
					if ($arraypersona['grado'] !== '') {
						$cliente->grado 				= $arraypersona['grado'];
					}
					if ($arraypersona['ingresos_base'] !== '') {
						$cliente->ingresos 				= $arraypersona['ingresos_base'];
					}
					if ($arraypersona['sexo'] !== '') {
						$cliente->sexo 				= $arraypersona['sexo'];
					}
					if ($arraypersona['estado_civil'] !== '') {
						$cliente->estado_civil 				= $arraypersona['estado_civil'];
					}
					if ($arraypersona['fechanto'] !== '') {
						$cliente->fechanto 				= $arraypersona['fechanto'];
					}
					if ($arraypersona['direccion'] !== '') {
						$cliente->direccion 				= $arraypersona['direccion'];
					}
					if ($arraypersona['celular'] !== '') {
						$cliente->celular 				= $arraypersona['celular'];
					}
					if ($arraypersona['telefono'] !== '') {
						$cliente->telefono 				= $arraypersona['telefono'];
					}
					if ($arraypersona['correo'] !== '') {
						$cliente->correo 				= $arraypersona['correo'];
					}
					
					$cliente->save();
				}
				
				//creación de registro
				$registro = \App\Registrosfinancieros::where("periodo", "=", $arraypersona['periodo'])
					->where("pagadurias_id", "=", $pagaduria->id)
					->where("clientes_id", "=", $cliente->id)
					->first();
				
				if ($registro === null) {
					$registro = new \App\Registrosfinancieros;
					$registro->clientes_id		= $cliente->id;
					if ($arraypersona['dias_laborados'] !== '') {
						$registro->dias_laborados = $arraypersona['dias_laborados'];
					}
					if (isset($arraypersona['conceptos_financieros']['ingresos_totales'])) {
						if ($arraypersona['conceptos_financieros']['ingresos_totales'] !== '') {
							$registro->ingresos_totales = $arraypersona['conceptos_financieros']['ingresos_totales'];
						}
					}
					if ($arraypersona['conceptos_financieros']['egresos_totales'] !== '') {
						$registro->egresos_totales = $arraypersona['conceptos_financieros']['egresos_totales'];
					}
					$registro->pagadurias_id	= $pagaduria->id;
					$registro->periodo			= $arraypersona['periodo'];
					$registro->save();
				}

				$conceptos_nan = 0;
				//Ingresos
				if (isset($arraypersona['conceptos_financieros']['detallado_conceptos']['ingresos'])) {
					foreach ($arraypersona['conceptos_financieros']['detallado_conceptos']['ingresos'] as $key => $ingreso) {
						if ($ingreso['valor'] !== 'NaN') {
							$ingresoN = \App\Ingresosaplicados::where("registros_id", "=", $registro->id)
								->where("concepto", "=", $ingreso['concepto'])
								->where("valor", "=", $ingreso['valor'])
								->first();
							if ($ingresoN === null) {
								$ingresoN = new \App\Ingresosaplicados;
								$ingresoN->registros_id		= $registro->id;
								$ingresoN->cod_concepto		= $ingreso['codConcepto'];
								$ingresoN->concepto			= $ingreso['concepto'];
								$ingresoN->valor			= $ingreso['valor'];
								$ingresoN->save();
							}
						} else {
							$conceptos_nan++;
						}
					}
				}
			
				//Descuentos
				if (isset($arraypersona['conceptos_financieros']['detallado_conceptos']['egresos'])) {
					foreach ($arraypersona['conceptos_financieros']['detallado_conceptos']['egresos'] as $key => $egreso) {
						if ($egreso['valor'] !== 'Nan') {
							$descuentoAplicado = \App\Descuentosaplicados::where("registros_id", "=", $registro->id)
								->where("concepto", "=", $egreso['concepto'])
								->where("valor", "=", $egreso['valor'])
								->first();
							if ($descuentoAplicado === null) {
								$descuentoAplicado = new \App\Descuentosaplicados;
								$descuentoAplicado->registros_id		= $registro->id;
								$descuentoAplicado->cod_concepto		= $egreso['codConcepto'];
								$descuentoAplicado->concepto			= $egreso['concepto'];
								$descuentoAplicado->valor				= $egreso['valor'];
								$descuentoAplicado->save();
							}
						} else {
							$conceptos_nan++;
						}
					}
				}
			
				//Descuentos no aplicados
				if (isset($arraypersona['conceptos_financieros']['detallado_conceptos']['desc_no_aplicados'])) {
					//Elimino los que están en BD
					\App\Descuentosnoaplicados::where('clientes_id', $cliente->id)->delete();
					foreach ($arraypersona['conceptos_financieros']['detallado_conceptos']['desc_no_aplicados'] as $key => $descuentoNoAplicado) {
						$descuento = new \App\Descuentosnoaplicados;
						$descuento->clientes_id 		= $cliente->id;
						$descuento->periodo 			= $descuentoNoAplicado['periodo'];
						$descuento->pagadurias_id 		= $descuentoNoAplicado['pagaduria'];
						$descuento->cod_concepto		= $descuentoNoAplicado['cod_concepto'];
						$descuento->concepto			= $descuentoNoAplicado['concepto'];
						$descuento->inconsistencia		= $descuentoNoAplicado['inconsistencia'];
						$descuento->valor_fijo			= $descuentoNoAplicado['valor'];
						$descuento->valor_total			= $descuentoNoAplicado['valor_total'];
						$descuento->saldo				= $descuentoNoAplicado['saldo'];
						$descuento->save();
					}
				}
				//-------------------
				$resp .= 'Carga con éxito. Registro actualizado: ' . $arraypersona['documento'] . "." . ($conceptos_nan !== 0 ? (' - Advertencias: Conceptos sin procesar por falta de datos: ' . $conceptos_nan . '.') : '');
			} catch(\Exception $ex) {
				//-------------------
				$resp .= 'Error. Documento #' . $arraypersona['documento'] . ': ' . $ex->getMessage() . ".";
			}
		}
		return $resp;
	}
}

if (!function_exists('upload_personas_without_concepts')) {
	function upload_personas_without_concepts($personas)
	{
		$resp = "";
		$advertencias = "";
		foreach ($personas as $key => $persona) {
			$arraypersona = (array) $persona;
			try {
				$arraypersona['grado'] = iconv("UTF-8", "ISO-8859-1//IGNORE", $arraypersona['grado']);
				if (mb_detect_encoding($arraypersona['grado']) !== 'ASCII') {
					$arraypersona['grado'] = '';
				}
				$secretaria = explode(' ', $arraypersona['secretaria']);
				$pagaduria = \App\Pagadurias::query()
					->where('pagaduria', 'LIKE', "%{$secretaria[count($secretaria)-1]}%")
					->first();

				if ($arraypersona['nit'] !== '') {
					if ($pagaduria->nit == '') {
						$pagaduria->nit = $arraypersona['nit'];
						$pagaduria->save();
					}
				}
				
				$ciudad = \App\Ciudades::where('ciudad', $arraypersona['ciudad'] )->first();
				if ($arraypersona['ciudad'] !== '') {
					//Crear ciudad
					if ($ciudad === null) {
						$ciudad = new \App\Ciudades;
						$ciudad->ciudad = $arraypersona['ciudad'];
						$ciudad->save();
					}
				}

				$cliente = \App\Clientes::where("documento", "=", $arraypersona['documento'])->first();
				//Cliente crear-actualizar existente
				if ($cliente === null) {
					$cliente = new \App\Clientes;
					$cliente->users_id				= 1;
					if ($cliente !== null) {
						$cliente->ciudades_id 			= $ciudad['id'];
					}
					$cliente->tipodocumento			= 'CC';
					if ($arraypersona['documento'] !== '') {
						$cliente->documento 			= $arraypersona['documento'];
					}
					if ($arraypersona['nombres'] !== '') {
						$cliente->nombres 				= $arraypersona['nombres'];
					}
					if ($arraypersona['apellidos'] !== '') {
						$cliente->apellidos 			= $arraypersona['apellidos'];
					}
					if ($arraypersona['centro_costos'] !== '') {
						$cliente->centro_costo 			= $arraypersona['centro_costos'];
					}
					if ($arraypersona['cargo_docente'] !== '') {
						$cliente->cargo 				= $arraypersona['cargo_docente'];
						$cliente->docente 				= '1';
					} elseif ($arraypersona['cargo_administrativo'] !== '') {
						$cliente->cargo 				= $arraypersona['cargo_administrativo'];
						$cliente->docente 				= null;
					}
					if ($arraypersona['banco'] !== '') {
						$cliente->banco 			= $arraypersona['banco'];
					}
					if ($arraypersona['cuenta'] !== '') {
						$cliente->cuenta 			= $arraypersona['cuenta'];
					}
					if ($arraypersona['caja_compensacion'] !== '') {
						$cliente->caja_compensacion 			= $arraypersona['caja_compensacion'];
					}
					if ($arraypersona['cesantias'] !== '') {
						$cliente->cesantias 			= $arraypersona['cesantias'];
					}
					if ($arraypersona['pension'] !== '') {
						$cliente->pension 			= $arraypersona['pension'];
					}
					if ($arraypersona['tipo_contratacion'] !== '') {
						$cliente->tipo_contratacion 	= $arraypersona['tipo_contratacion'];
					}
					if ($arraypersona['grado'] !== '') {
						$cliente->grado 				= $arraypersona['grado'];
					}
					if ($arraypersona['ingresos_base'] !== '') {
						$cliente->ingresos 				= $arraypersona['ingresos_base'];
					}
					if ($arraypersona['sexo'] !== '') {
						$cliente->sexo 				= $arraypersona['sexo'];
					}
					if ($arraypersona['estado_civil'] !== '') {
						$cliente->estado_civil 				= $arraypersona['estado_civil'];
					}
					if ($arraypersona['fechanto'] !== '') {
						$cliente->fechanto 				= $arraypersona['fechanto'];
					}
					if ($arraypersona['direccion'] !== '') {
						$cliente->direccion 				= $arraypersona['direccion'];
					}
					if ($arraypersona['celular'] !== '') {
						$cliente->celular 				= $arraypersona['celular'];
					}
					if ($arraypersona['telefono'] !== '') {
						$cliente->telefono 				= $arraypersona['telefono'];
					}
					if ($arraypersona['correo'] !== '') {
						$cliente->correo 				= $arraypersona['correo'];
					}
					
					$cliente->save();
				} else {
					
					if ($arraypersona['nombres'] !== '') {
						$cliente->nombres 				= $arraypersona['nombres'];
					}
					if ($arraypersona['apellidos'] !== '') {
						$cliente->apellidos 			= $arraypersona['apellidos'];
					}
					if ($arraypersona['centro_costos'] !== '') {
						$cliente->centro_costo 			= $arraypersona['centro_costos'];
					}
					if ($arraypersona['cargo_docente'] !== '') {
						$cliente->cargo 				= $arraypersona['cargo_docente'];
						$cliente->docente 				= '1';
					} elseif ($arraypersona['cargo_administrativo'] !== '') {
						$cliente->cargo 				= $arraypersona['cargo_administrativo'];
						$cliente->docente 				= null;
					}
					if ($arraypersona['banco'] !== '') {
						$cliente->banco 			= $arraypersona['banco'];
					}
					if ($arraypersona['cuenta'] !== '') {
						$cliente->cuenta 			= $arraypersona['cuenta'];
					}
					if ($arraypersona['caja_compensacion'] !== '') {
						$cliente->caja_compensacion 			= $arraypersona['caja_compensacion'];
					}
					if ($arraypersona['cesantias'] !== '') {
						$cliente->cesantias 			= $arraypersona['cesantias'];
					}
					if ($arraypersona['pension'] !== '') {
						$cliente->pension 			= $arraypersona['pension'];
					}
					if ($arraypersona['tipo_contratacion'] !== '') {
						$cliente->tipo_contratacion 	= $arraypersona['tipo_contratacion'];
					}
					if ($arraypersona['grado'] !== '') {
						$cliente->grado 				= $arraypersona['grado'];
					}
					if ($arraypersona['ingresos_base'] !== '') {
						$cliente->ingresos 				= $arraypersona['ingresos_base'];
					}
					if ($arraypersona['sexo'] !== '') {
						$cliente->sexo 				= $arraypersona['sexo'];
					}
					if ($arraypersona['estado_civil'] !== '') {
						$cliente->estado_civil 				= $arraypersona['estado_civil'];
					}
					if ($arraypersona['fechanto'] !== '') {
						$cliente->fechanto 				= $arraypersona['fechanto'];
					}
					if ($arraypersona['direccion'] !== '') {
						$cliente->direccion 				= $arraypersona['direccion'];
					}
					if ($arraypersona['celular'] !== '') {
						$cliente->celular 				= $arraypersona['celular'];
					}
					if ($arraypersona['telefono'] !== '') {
						$cliente->telefono 				= $arraypersona['telefono'];
					}
					if ($arraypersona['correo'] !== '') {
						$cliente->correo 				= $arraypersona['correo'];
					}
					
					$cliente->save();

					//Descuentos no aplicados
					if (isset($arraypersona['conceptos_financieros']['detallado_conceptos']['desc_no_aplicados'])) {
						//Elimino los que están en BD
						\App\Descuentosnoaplicados::where('clientes_id', $cliente->id)->delete();
						foreach ($arraypersona['conceptos_financieros']['detallado_conceptos']['desc_no_aplicados'] as $key => $descuentoNoAplicado) {
							$descuento = new \App\Descuentosnoaplicados;
							$descuento->clientes_id 		= $cliente->id;
							$descuento->periodo 			= $descuentoNoAplicado['periodo'];
							$descuento->pagadurias_id 		= $descuentoNoAplicado['pagaduria'];
							$descuento->cod_concepto		= $descuentoNoAplicado['cod_concepto'];
							$descuento->concepto			= $descuentoNoAplicado['concepto'];
							$descuento->inconsistencia		= $descuentoNoAplicado['inconsistencia'];
							$descuento->valor_fijo			= $descuentoNoAplicado['valor'];
							$descuento->valor_total			= $descuentoNoAplicado['valor_total'];
							$descuento->saldo				= $descuentoNoAplicado['saldo'];
							$descuento->save();
						}
					}
				}
				//-------------------
				$resp .= 'Carga con éxito. Registro actualizado: ' . $arraypersona['documento'] . ".";
			} catch(\Exception $ex) {
				//-------------------
				$resp .= 'Error. Documento #' . $arraypersona['documento'] . ': ' . $ex->getMessage() . ".";
			}
		}
		return $resp;
	}
}

if (!function_exists('calcula_viabilidad_inicial')) {
	function calcula_viabilidad_inicial($persona, $fecha_estudio = NULL) {
		//Variables
		$faltantes = array();
		$plazomax = 0;
		$analisis = "";
		$viable = true;
		$limite_cupo = 0;
		if (!$fecha_estudio) {
			$fecha_estudio = new DateTime(date("Y") . "-" . date("m") . "-" . date("d"));
		}
		//Parámetros
        $edadmax_admin_hombre = (\App\Parametros::where('llave', 'EDADMAX_ADMIN_H')->first()->valor)*12;
        $edadmax_admin_mujer = (\App\Parametros::where('llave', 'EDADMAX_ADMIN_M')->first()->valor)*12;
        $edadmax_docentes = (\App\Parametros::where('llave', 'EDADMAX_DOCENTES')->first()->valor)*12;
		$edadmax_pensionados = (\App\Parametros::where('llave', 'EDADMAX_PENSIONADOS')->first()->valor)*12;
		$cupomax_vacantedef = \App\Parametros::where('llave', 'CUPOMAX_VACANTEDEF')->first()->valor;
		$plazo_max_permitido = 0;

		//Datos del cliente
		$fechanac = new DateTime($persona->fechanto);
		$edad = $fecha_estudio->diff($fechanac);
		$meses_edad = ($edad->y*12)+$edad->m;
		$es_pensionado = $persona->registrosfinancieros->last()->pagaduria->de_pensiones;
		if ($persona->tipo_contratacion == 'Propiedad' || $persona->tipo_contratacion == 'Indefinido') {
			$plazo_max_permitido = 144;
		} elseif ($persona->tipo_contratacion == 'Provisional Vacante Definitiva' || $persona->tipo_contratacion == 'Fijo') {
			$plazo_max_permitido = 60;
			$limite_cupo = $cupomax_vacantedef;
		} else {
			$analisis = "No es viable: tipo de contrato inviable";
			$viable = false;
		}

		//Validación de datos
		if ($persona->fechanto == '') {
			$faltantes[] = 'Fecha de nacimiento';
		}
		if ($persona->tipo_contratacion == '') {
			$faltantes[] = 'Tipo de contratación';
		}
		if ($persona->cargo == '') {
			$faltantes[] = 'Cargo';
		}

		//Cálculos
		if ($viable) {
			if ($es_pensionado) {
				if ($meses_edad < $edadmax_pensionados) {
					$plazomax = $plazo_max_permitido;
				}
			} else {
				if ($persona->docente == '1') {
					if ($edadmax_docentes-$meses_edad <= $plazo_max_permitido) {
						$plazomax = $edadmax_docentes-$meses_edad; //Medida en meses
					} elseif ($edadmax_docentes-$meses_edad > $plazo_max_permitido) {
						$plazomax = $plazo_max_permitido;
					} else {
						$analisis = "No es viable: Edad inviable";
					}
				} else {
					if ($persona->sexo == 'M') {
						if ($edadmax_admin_hombre-$meses_edad <= $plazo_max_permitido) {
							$plazomax = $edadmax_admin_hombre-$meses_edad; //Medida en meses
						} elseif ($edadmax_admin_hombre-$meses_edad > $plazo_max_permitido) {
							$plazomax = $plazo_max_permitido;
						} elseif ($edadmax_admin_hombre-$meses_edad <= 0) {
							$analisis = "No es viable: Edad inviable";
						}
					} elseif ($persona->sexo == 'F') {
						if ($edadmax_admin_mujer-$meses_edad <= $plazo_max_permitido) {
							$plazomax = $edadmax_admin_mujer-$meses_edad; //Medida en meses
						} elseif ($edadmax_admin_mujer-$meses_edad > $plazo_max_permitido) {
							$plazomax = $plazo_max_permitido;
						} elseif ($edadmax_admin_mujer-$meses_edad <= 0) {
							$analisis = "No es viable: Edad inviable";
						}
					} else {
						$analisis = "Sin datos suficientes para hallar viabilidad preliminar";
					}
				}
			}
		}

		return array(
			"plazomax" => $plazomax,
			"analisis" => $analisis,
			"edad" => $edad->y,
			"limite_cupo" => $limite_cupo,
			"faltantes" => $faltantes
		);
	}
}

if (!function_exists('deformat_autonumeric')) {
	function deformat_autonumeric($value){
		$valuesinpeso = str_replace("$", "", $value);
		$valuesindecimal = str_replace(",00", "", $valuesinpeso);
		$valuelimpio = str_replace(".", "", $valuesindecimal);
		return trim($valuelimpio);
	}
}

if(!function_exists('roles_label')) {
	function roles_label($rol){
		switch ($rol) {
			case 'ADMIN_SISTEMA':
				return 'SuperAdmin';
				break;
			case 'ADMIN_HEGO':
				return 'Administrador HEGO';
				break;
			case 'ADMIN_AMI':
				return 'Administrador AMI';
				break;
			case 'USUARIO':
				return 'Usuario';
				break;
		}
	}
}

if(!function_exists('IsSuperAdmin')) {
	function IsSuperAdmin() {
		switch (Auth::user()->rol->rol) {
			case 'ADMIN_SISTEMA':
				return true;
				break;
			default:
				return false;
				break;
		}
	}
}

if(!function_exists('IsAMIAdmin')) {
	function IsAMIAdmin() {
		switch (Auth::user()->rol->rol) {
			case 'ADMIN_AMI':
				return true;
				break;
			default:
				return false;
				break;
		}
	}
}

if(!function_exists('IsHEGOAdmin')) {
	function IsHEGOAdmin() {
		switch (Auth::user()->rol->rol) {
			case 'ADMIN_HEGO':
				return true;
				break;
			default:
				return false;
				break;
		}
	}
}

if(!function_exists('IsUser')) {
	function IsUser() {
		if (IsCompany()) {
			return false;
		} else {
			switch (Auth::user()->rol->rol) {
				case 'USUARIO':
					return true;
					break;
				default:
					return false;
					break;
			}
		}
	}
}

if(!function_exists('IsUserCreator')) {
	function IsUserCreator() {
		if (Auth::user()->creausuarios) {
			return true;
		} else {
			return false;
		}
	}
}

if(!function_exists('IsCompany')) {
	function IsCompany() {
		if (!Auth::user()->id_company) {
			return true;
		} else {
			return false;
		}
	}
}

if(!function_exists('HEGOAccess')) {
	function HEGOAccess() {
		if (Auth::user()->hego) {
			return true;
		} else {
			return false;
		}
	}
}

if (!function_exists('AMISilverHabilitado')) {
	function AMISilverHabilitado()
	{
		if (Auth::user()->ami_silver || IsCompany()) {
			return true;
		} else {
			return false;
		}	
	}
}

if (!function_exists('AMIGoldHabilitado')) {
	function AMIGoldHabilitado()
	{
		if (Auth::user()->ami_gold || IsCompany()) {
			return true;
		} else {
			return false;
		}		
	}
}

if (!function_exists('AMIDiamondHabilitado')) {
	function AMIDiamondHabilitado()
	{
		if (Auth::user()->ami_diamond || IsCompany()) {
			return true;
		} else {
			return false;
		}		
	}
}

if (!function_exists('getConsultasXUsuario')) {
	function getConsultasXUsuario($user)
	{
		return App\Consultas::where( 'users_id', $user->id )->get();	
	}
}

?>
