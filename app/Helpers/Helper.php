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
			try {
				$secretaria = explode(' ', $persona['secretaria']);

				$pagaduria = \App\Pagadurias::query()
					->where('pagaduria', 'LIKE', "%{$secretaria[count($secretaria)-1]}%")
					->first();
				
				$ciudad = \App\Ciudades::where('ciudad', $persona['ciudad'] )->first();
				if ($persona['ciudad'] !== '') {
					//Crear ciudad
					if ($ciudad === null) {
						$ciudad = new \App\Ciudades;
						$ciudad->ciudad = $persona['ciudad'];
						$ciudad->save();
					}
				}

				$cliente = \App\Clientes::where("documento", "=", $persona['documento'])->first();
				//Cliente crear-actualizar existente
				if ($cliente === null) {
					$cliente = new \App\Clientes;
					$cliente->users_id				= 1;
					if ($cliente !== null) {
						$cliente->ciudades_id 			= $ciudad['id'];					
					}
					$cliente->tipodocumento			= 'CC';
					if ($persona['documento'] !== '') {
						$cliente->documento 			= $persona['documento'];					
					}
					if ($persona['nombres'] !== '') {
						$cliente->nombres 				= $persona['nombres'];					
					}
					if ($persona['apellidos'] !== '') {
						$cliente->apellidos 			= $persona['apellidos'];					
					}
					if ($persona['centro_costos'] !== '') {
						$cliente->centro_costo 			= $persona['centro_costos'];					
					}
					if ($persona['cargo'] !== '') {
						$cliente->cargo 				= $persona['cargo'];					
					}
					if ($persona['tipo_contratacion'] !== '') {
						$cliente->tipo_contratacion 	= $persona['tipo_contratacion'];					
					}
					if ($persona['grado'] !== '') {
						$cliente->grado 				= $persona['grado'];					
					}
					if ($persona['conceptos_financieros']->ingresos_base !== '') {
						$cliente->ingresos 				= $persona['conceptos_financieros']->ingresos_base;
					}
					
					$cliente->save();
				} else {
					
					if ($persona['nombres'] !== '') {
						$cliente->nombres 				= $persona['nombres'];
					}
					if ($persona['apellidos'] !== '') {
						$cliente->apellidos 			= $persona['apellidos'];
					}
					if ($persona['centro_costos'] !== '') {
						$cliente->centro_costo 			= $persona['centro_costos'];
					}
					if ($persona['cargo'] !== '') {
						$cliente->cargo 				= $persona['cargo'];
					}
					if ($persona['tipo_contratacion'] !== '') {
						$cliente->tipo_contratacion 	= $persona['tipo_contratacion'];
					}
					if ($persona['grado'] !== '') {
						$cliente->grado 				= $persona['grado'];
					}
					if ($persona['conceptos_financieros']->ingresos_base !== '') {
						$cliente->ingresos 				= $persona['conceptos_financieros']->ingresos_base;
					}
					
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

				$conceptos_nan = 0;
				//Ingresos
				if (isset($persona['conceptos_financieros']->detallado_conceptos->ingresos)) {
					foreach ($persona['conceptos_financieros']->detallado_conceptos->ingresos as $key => $ingreso) {
						if ($ingreso->valor !== 'NaN') {
							$ingresoN = \App\Ingresosaplicados::where("registros_id", "=", $registro->id)
								->where("cod_concepto", "=", $ingreso->codConcepto)
								->first();
							
							if ($ingresoN === null) {
								$ingresoN = new \App\Ingresosaplicados;
								$ingresoN->registros_id		= $registro->id;
								$ingresoN->cod_concepto		= $ingreso->codConcepto;
								$ingresoN->concepto			= $ingreso->concepto;
								$ingresoN->valor			= $ingreso->valor;
								$ingresoN->save();
							}
						} else {
							$conceptos_nan++;
						}
					}
				}
			
				//Descuentos
				if (isset($persona['conceptos_financieros']->detallado_conceptos->egresos)) {
					foreach ($persona['conceptos_financieros']->detallado_conceptos->egresos as $key => $egreso) {
						if ($egreso->valor !== 'Nan') {
							$descuentoAplicado = \App\Descuentosaplicados::where("registros_id", "=", $registro->id)
								->where("cod_concepto", "=", $egreso->codConcepto)
								->first();
							
							if ($descuentoAplicado === null) {
								$descuentoAplicado = new \App\Descuentosaplicados;
								$descuentoAplicado->registros_id		= $registro->id;
								$descuentoAplicado->cod_concepto		= $egreso->codConcepto;
								$descuentoAplicado->concepto			= $egreso->concepto;
								$descuentoAplicado->valor				= $egreso->valor;
								$descuentoAplicado->save();
							}
						} else {
							$conceptos_nan++;
						}
					}
				}
				//-------------------
				$resp .= 'Carga con éxito. Registro actualizado: ' . $persona['documento'] . "." . ($conceptos_nan !== 0 ? (' - Advertencias: Conceptos sin procesar por falta de datos: ' . $conceptos_nan . '.') : '');
			} catch(\Exception $ex) {
				//-------------------
				$resp .= 'Error. Documento #' . $persona['documento'] . ': ' . $ex->getMessage() . ".";
			}
		}
		return $resp;
	}
}

/*if (!function_exists('calcula_plazo_viabilidad')) {
	function calcula_plazo_viabilidad($persona) {
		//Parámetros
        $edadmax_admin_hombre = \App\Parametros::where('llave', 'EDADMAX_ADMIN_H')->first()->valor;
        $edadmax_admin_mujer = \App\Parametros::where('llave', 'EDADMAX_ADMIN_M')->first()->valor;
        $edadmax_docentes = \App\Parametros::where('llave', 'EDADMAX_DOCENTES')->first()->valor;
		$edadmax_pensionados = \App\Parametros::where('llave', 'EDADMAX_PENSIONADOS')->first()->valor;
		$plazo_max_permitido = 0;

		//Datos del cliente
		if (tipo_contratacion == 'Propiedad') {
			$plazo_max_permitido = 144;
		} elseif (tipo_contratacion == 'Provisional Vacante Definitiva') {
			$plazo_max_permitido = 60;
		}
		$estado_cliente = 'pensionado|activo';
		$tipo_cargo = $persona

	}
}*/

?>
