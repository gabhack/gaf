<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Estudiostr as Estudios;
use App\Parametros as Parametros;

use DateTime;

class DatasetController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:ADMIN_SISTEMA');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		return view("dataset/index");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function get()
    {
		$dataset = array();
		$estudios = Estudios::all();

		foreach ($estudios as $key => $estudio) {
			$smlv = Parametros::where('llave', 'SMLV')->first();
			$nombres = $estudio->cliente->nombres;
			$cedula = $estudio->cliente->documento;
			$tipo_pagaduria = '';
			if ($estudio->registro->pagaduria->de_pensiones) {
				$tipo_pagaduria = 'pensionado';
			} else {
				$tipo_pagaduria = (is_numeric(strpos($estudio->registro->pagaduria->pagaduria, 'SEM')) ? 'secretaria_educacion' : ( is_numeric(strpos($estudio->registro->pagaduria->pagaduria, 'SED')) ? 'secretaria_educacion' : 'otro') );
			}
			$genero = $estudio->cliente->sexo == 'M' ? 'hombre' : 'mujer' ;
			$fecha_actual = date("Y");
			$fechanto_tmp = new DateTime($estudio->cliente->fechanto);
			$edad = $fecha_actual-$fechanto_tmp->format('Y');

			$sueldobasico = $estudio->cliente->ingresos;
            $adicional = 0;
            if ($estudio->cliente->cargo) {
                if (strpos($estudio->cliente->cargo, 'Rector') !== false) {
                    $adicional = ($estudio->cliente->ingresos*.3);
                } elseif (strpos($estudio->cliente->cargo, 'Coordinador') !== false) {
                    $adicional = ($estudio->cliente->ingresos*.2);
                }
            }

			$aportes = 0;
            $vinculacion = '';		
            if($estudio->registro->pagaduria->de_pensiones)
            {
                $vinculacion = 'PENS';
                $aportes = Parametros::where('llave', 'APORTES_PENSIONADOS')->first();
            }	
            else
            {
                $aportes = Parametros::where('llave', 'APORTES_ACTIVOS')->first();
            }
            $aportes = $aportes->valor * ($sueldobasico + $adicional) ;
            
            $totaldescuentos = totalizar_concepto(descuentos_por_registro($estudio->registro->id));

            $cupos = calcularCapacidad(
                $vinculacion,
                $sueldobasico,
                $aportes,
                $adicional,
                $totaldescuentos,
                $smlv->valor
			);
			
			$cart_al_dia = 0;
			$moras_total = 0;
			$cart_mora_30_a_60 = 0;
			$cart_mora_60_a_90 = 0;
			$cart_mora_90_a_120 = 0;
			$cart_mora_120_a_150 = 0;
			$cart_mora_150_a_180 = 0;
			$cart_mora_mas_de_180 = 0;
			$cart_castigadas = 0;
			$embargos = 0;
			$deudas_en_efectivo = 0;
			$deudas_no_efectivo = 0;
			$carteras_compradas = 0;
			$carteras_wab_a_b = 0;
			$carteras_wab_c_k = 0;

			$datacarteras = $estudio->carteras;
			foreach ($datacarteras as $key => $cartera) {
				if ($cartera->solo_efectivo) {
					$deudas_en_efectivo++;
				} else {
					$deudas_no_efectivo++;
				}
				if ($cartera->compraAF1_id || $cartera->compraAF2_id) {
					$carteras_compradas++;
				}
				switch ($cartera->estado->estado) {
					case 'AL DIA':
						$cart_al_dia++;
						break;
					
					case 'MORA 30 A 60':
						$moras_total++;
						$cart_mora_30_a_60++;
						break;
					
					case 'MORA 60 A 90':
						$moras_total++;
						$cart_mora_60_a_90++;
						break;
					
					case 'MORA 90 A 120':
						$moras_total++;
						$cart_mora_90_a_120++;
						break;
					
					case 'MORA 120 A 150':
						$moras_total++;
						$cart_mora_120_a_150++;
						break;
					
					case 'MORA 150 A 180':
						$moras_total++;
						$cart_mora_150_a_180++;
						break;
					
					case 'CASTIGADA':
						$cart_castigadas++;
						break;
					
					case 'EMBARGO':
						$embargos++;
						break;
				}
				switch ($cartera->calif_wab) {
					case 'A':
						$carteras_wab_a_b++;
						break;

					case 'B':
						$carteras_wab_a_b++;
						break;

					default:
						$carteras_wab_c_k++;
						break;
				}
			}

			$score_datacredito = $estudio->central->puntaje_data;	
			$viabilidad = $estudio->decision;
			$tipoCliente = "";

			//Cálculo de tipo de cliente
			// Cliente tipo A   : No tiene NINGUNA deuda
			// Cliente tipo AA  : TIENE deudas, NINGUNA en mora y en NINGUNA deuda manejan solo efectivo
			// Cliente tipo AAA : TIENE deudas, NINGUNA en mora y al menos UNA deuda SOLO maneja efectivo
			// Cliente tipo B1  : TIENE deudas, SOLO UNA está en mora
			// Cliente tipo B2  : TIENE deudas, MAS DE UNA está en mora
			// Cliente tipo B3  : TIENE deudas, MAS DE UNA está en mora y SOLO UNA en cartera castigada
			// Cliente tipo C   : TIENE deudas, MÁS DE UNA en cartera castigada...   O...  AL MENOS UN embargo
			if (sizeof($datacarteras) === 0) {
				$tipoCliente = "AAA";
			} else {
				if ($cart_castigadas > 1 || $embargos >= 1) {
					$tipoCliente = "C";
				} else {
					if ($moras_total === 0 && $cart_castigadas === 0) {
						if ($deudas_en_efectivo === 0) {
							$tipoCliente = "AA";
						} else {
							$tipoCliente = "A";
						}
					} else {
						if ($moras_total === 1) {
							$tipoCliente = "B1";
						} else {
							$tipoCliente = "B2";
							if ($cart_castigadas === 1) {
								$tipoCliente = "B3";
							}
						}
					}
				}
			}

			$dataset[] = array(
				"nombres" => $nombres,
				"cedula" => $cedula,
				"tipo_pagaduria" => $tipo_pagaduria,
				"tipos_contratos" => $estudio->cliente->tipo_contratacion,
				"tipos_cargos" => $estudio->cliente->cargo,
				"genero" => $genero,
				"edad" => $edad,
				"cupo_potencial" => $cupos['libreInversion']['valor'],
				"cart_al_dia" => $cart_al_dia,
				"cart_mora_30_a_60" => $cart_mora_30_a_60,
				"cart_mora_60_a_90" => $cart_mora_60_a_90,
				"cart_mora_90_a_120" => $cart_mora_90_a_120,
				"cart_mora_120_a_150" => $cart_mora_120_a_150,
				"cart_mora_150_a_180" => $cart_mora_150_a_180,
				"cart_mora_mas_de_180" => $cart_mora_mas_de_180,
				"cart_castigadas" => $cart_castigadas,
				"embargos" => $embargos,
				"deudas_en_efectivo" => $deudas_en_efectivo,
				"deudas_no_efectivo" => $deudas_no_efectivo,
				"carteras_compradas" => $carteras_compradas,
				"carteras_wab_a_b" => $carteras_wab_a_b,
				"carteras_wab_c_k" => $carteras_wab_c_k,
				"score_datacredito" => $score_datacredito,
				"tipos_cliente" => $tipoCliente,
				"viabilidad" => $viabilidad
			);
		}

		return view("dataset/response")->with([
			"dataset" => $dataset
		]);
    }

    
}