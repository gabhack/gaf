<?php

namespace App\Jobs;

use App\Helper;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Smalot\PdfParser\Parser as Parser;

class CargarDatosComprobantes implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $ruta;
    protected $pagaduria;
    protected $nombrearchivo;
    protected $plano;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($ruta, $pagaduria, $nombrearchivo, $plano)
    {
        $this->ruta = $ruta;
        $this->pagaduria = $pagaduria;
        $this->nombrearchivo = $nombrearchivo;
        $this->plano = $plano;

        $plano->cont_procesos++;
        $plano->save();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            set_time_limit(0);

            $ruta = $this->ruta;
            $pagaduria = $this->pagaduria;
            $nombrearchivo = $this->nombrearchivo;
            $plano = $this->plano;

            $parseador = new Parser;
            $documento = $parseador->parseFile($ruta);

            $text = $documento->getText();
            $paginas = explode("Comprobante de Pago	Periodo de pago", $text);

            $personas = array();

            if (sizeof($paginas) != 1) {
                foreach ($paginas as $indice => $pagina) {
                    if ($indice > 0) {

                        $lineas = explode("\n", $pagina);
                        
                        // Extraer nombres
                        $keynombres = (array_search('Ingresos:	', $lineas))+2;
                        $nombres = trim(preg_replace('/[0-9]+/', '', $lineas[$keynombres]));
                        // Extraer documento
                        $documento = trim(intval(preg_replace('/[^0-9]+/', '', $lineas[$keynombres]), 10));
                        // Extraer cargo
                        $cargo = trim($lineas[$keynombres+1]);
                        // Extraer ingresos
                        $ingresos = trim(intval(preg_replace('/[^0-9]+/', '', $lineas[$keynombres+2]), 10));
                        // Extraer ciudad
                        $lineas[$keynombres+3] = str_replace('Ciudad:', '', $lineas[$keynombres+3]);
                        if ((strpos($lineas[$keynombres+3], '(') !== false)) {
                            preg_match('#\((.*?)\)#', $lineas[$keynombres+3], $match);
                            $ciudad = strtoupper(trim(str_replace($match[0], '', $lineas[$keynombres+3])));
                        } else {
                            $ciudad = $lineas[$keynombres+3];
                        }
                        // Extraer tipo contratación
                        $keycontr = (array_search('Capacidad de', $lineas))-1;
                        $linea_contr = explode('	', $lineas[$keycontr]);
                        $tipo_contratacion = trim($linea_contr[3]);
                        // Extraer centro de costos
                        $keycentro = (array_search('Ingresos:	', $lineas))+1;
                        $centro_costos = trim($lineas[$keycentro]);
                        // Extraer grado
                        $keygrado = (array_search('Endeudamiento:	', $lineas))+1;
                        $grado = trim(str_replace('Grado:', '', $lineas[$keygrado]));
                        // Extraer periodo
                        $keyperiodo = (array_search('Centro de Costo:', $lineas))-1;
                        setlocale (LC_TIME, "es_CO");
                        $linea_periodo = explode('	', $lineas[$keyperiodo]);
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

                        $persona = array(
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
                        
                        // $jobId = CargarDatosComprobantes::dispatch($persona, $pagaduria, $plano)
                        //     ->onConnection('database')
                        //     ->onQueue('uploadingComprobantes');

                        //Cargar datos del cliente
                        $this->upload($persona);
                            
                    }
                }

                $response = array(
                    'cod' => '200',
                    'mensaje' => 'Se han actualizado los registros encontrados en el archivo fuente.',
                );
            } else {
                unset($paginas);
                $paginas = explode(" - Comprobante de Pago", $text);
                unset($paginas[sizeof($paginas)-1]);
                if (sizeof($paginas) != 1) {
                    //Archivo nuevo
                    $en2paginas = false;
                    foreach ($paginas as $indice => $pagina) {
                        
                        $lineas = explode("\n", $pagina);
                        $indice2 = $indice + 1;
                        $lineas2 = array();

                        if (isset($paginas[$indice2])) {
                            $lineas2 = explode("\n", $paginas[$indice2]);

                            // Validación 1 para verificar si el actual índice cuenta con 2da página
                            if (!is_numeric(array_search('Comprobante de Pago', $lineas2))) {
                                $en2paginas = true;
                            } else {
                                $en2paginas = false;
                            }
                        }

                        // Validación 2 para que cuando el índice esté en la 2da pagina no la lea
                        if (!is_numeric(array_search('Comprobante de Pago', $lineas))) {
                            $en2paginas = false;
                            continue;
                        } else {
                            if ($en2paginas) {
                                //Preparación de los datos
                                $posfinal_pagina1 = sizeof($lineas)-1;
                                unset($lineas2[0]);
                                unset($lineas2[1]);
                                $cont = $posfinal_pagina1;
                                for ($i=2; $i < sizeof($lineas2)+2; $i++) {
                                    $lineas[$cont] = $lineas2[$i];
                                    $cont++;
                                }
                                //
                                // Extraer nombres
                                $keynombres = (array_search('N. Contratacion:', $lineas))-1;
                                $nombres = trim($lineas[$keynombres]);
                                // Extraer documento
                                $lineadocumento = trim($lineas[$keynombres+3]);
                                $documento = trim(intval(preg_replace('/[^0-9]+/', '', $lineadocumento), 10));
                                // Extraer cargo
                                $keycargo = (array_search('Cargo:', $lineas))+2;
                                $cargo = trim($lineas[$keycargo]);
                                // Extraer ingresos
                                $keyingresos = (array_search('Cargo:', $lineas))+1;
                                $ingresos = trim(intval(preg_replace('/[^0-9]+/', '', $lineas[$keyingresos]), 10));
                                // Extraer ciudad
                                $keyciudad = (array_search('Periodo de pago:', $lineas)-1);
                                $lineas[$keyciudad] = str_replace('Ciudad:', '', $lineas[$keyciudad]);
                                if ((strpos($lineas[$keynombres+3], '(') !== false)) {
                                    preg_match('#\((.*?)\)#', $lineas[$keynombres+3], $match);
                                    $ciudad = strtoupper(trim(str_replace($match[0], '', $lineas[$keynombres+3])));
                                } else {
                                    $ciudad = $lineas[$keynombres+3];
                                }
                                // Extraer tipo contratación
                                $linea_contr = explode('	', $lineas[$keyciudad-1]);
                                $tipo_contratacion = trim($linea_contr[0]);
                                // Extraer centro de costos
                                $centro_costos = trim($lineas[$keynombres-1]);
                                // Extraer grado
                                $keygrado = (array_search('CodConcepto	Concepto	Cuotas	Dias	Ingresos	Egresos	', $lineas)-1);
                                $grado = trim(str_replace('Grado:', '', $lineas[$keygrado]));
                                // Extraer periodo
                                setlocale (LC_TIME, "es_CO");
                                $keyperiodo = $keygrado-3;
                                $linea_periodo = explode('	', $lineas[$keyperiodo]);
                                $periodo = strftime("%Y%m", strtotime(trim($linea_periodo[0])));
                                // Extraer ingresos totales - egresos totales
                                $keyinicio = array_search('CodConcepto	Concepto	Cuotas	Dias	Ingresos	Egresos	', $lineas);
                                $keyfin = array_search('Firma', $lineas);
                                $keying_egr = $keyfin+3;
                                $validaring_egr = explode("	", $lineas[$keying_egr]);
                                if (sizeof($validaring_egr) !== 4) {
                                    $keying_egr++;
                                }
                                $ingresos_egresos = explode("	", $lineas[$keying_egr]);
                                $ingresos_totales = substr(trim(intval(preg_replace('/[^0-9]+/', '', $ingresos_egresos[1]), 10)), 0, -2);
                                $egresos_totales = substr(trim(intval(preg_replace('/[^0-9]+/', '', $ingresos_egresos[2]), 10)), 0, -2);
                                // Extracción de datos ingresos-egresos
                                $registros = array();
                                $tmp_casoespecial = array();
                                $tmp_casoespecial['codConcepto'] = '';
                                $tmp_casoespecial['concepto'] = '';
                                $tmp_casoespecial['valor'] = '';
                                $suma_temporal = 0;
                                $encasoespecial = false;
                                for ($i=$keyinicio+1; $i < $keyfin; $i++) {
                                    $datos_registro = str_replace("	", " ", $lineas[$i]);
                                    $datos_espaciados = preg_split('/\s+/', $datos_registro);
                                    // Valido si es un array completo
                                    $esvalido = false;
                                    for ($j=0; $j < sizeof($datos_espaciados); $j++) { 
                                        if (is_numeric(strpos($datos_espaciados[$j], '.00'))) {
                                            if (!is_numeric(strpos($datos_espaciados[$j], '%'))) {
                                                $esvalido = true;
                                                break;
                                            }
                                        }
                                    }

                                    if ($esvalido && !$encasoespecial) {
                                        $concepto_completo_array = array();
                                        $pos_valor = sizeof($datos_espaciados)-2;
                                        for ($j=1; $j < $pos_valor; $j++) { 
                                            array_push($concepto_completo_array, $datos_espaciados[$j]);
                                        }
                                        $concepto_completo = implode(' ', $concepto_completo_array);
                                        $valor_sin_formato = substr(trim(intval(preg_replace('/[^0-9]+/', '', $datos_espaciados[$pos_valor]), 10)), 0, -2);
                                        $suma_temporal = $suma_temporal+(int)$valor_sin_formato;
                                        if (($ingresos_totales/$suma_temporal) >= 1) {
                                            $registros['ingresos'][] = array(
                                                'codConcepto' => $datos_espaciados[0],
                                                'concepto' => $concepto_completo,
                                                'valor' => $valor_sin_formato,
                                            );
                                        } else {
                                            $registros['egresos'][] = array(
                                                'codConcepto' => $datos_espaciados[0],
                                                'concepto' => $concepto_completo,
                                                'valor' => $valor_sin_formato,
                                            );
                                        }
                                    } else {
                                        $encasoespecial = true;
                                        $keyinit_proc = 0;
                                        if ($tmp_casoespecial['codConcepto'] == '') {
                                            $tmp_casoespecial['codConcepto'] = $datos_espaciados[0];
                                            for ($j=$keyinit_proc; $j < sizeof($datos_espaciados); $j++) { 
                                                if (is_numeric(strpos($datos_espaciados[$j], '.00')) && !is_numeric(strpos($datos_espaciados[$j], '%'))) {
                                                    $encasoespecial = false;
                                                    $tmp_casoespecial['valor'] = substr(trim(intval(preg_replace('/[^0-9]+/', '', $datos_espaciados[$j]), 10)), 0, -2);
                                                    $suma_temporal = $suma_temporal+(int)$tmp_casoespecial['valor'];
                                                    if (($ingresos_totales/$suma_temporal) >= 1) {
                                                        $registros['ingresos'][] = $tmp_casoespecial;
                                                    } else {
                                                        $registros['egresos'][] = $tmp_casoespecial;
                                                    }
                                                    $tmp_casoespecial['codConcepto'] = '';
                                                    $tmp_casoespecial['concepto'] = '';
                                                    $tmp_casoespecial['valor'] = '';
                                                } else {
                                                    $tmp_casoespecial['concepto'] .= ' ' . $datos_espaciados[$j];
                                                }
                                            }
                                        } else {
                                            for ($j=0; $j < sizeof($datos_espaciados); $j++) { 
                                                if (is_numeric(strpos($datos_espaciados[$j], '.00')) && !is_numeric(strpos($datos_espaciados[$j], '%'))) {
                                                    $tmp_casoespecial['valor'] = $datos_espaciados[$j];
                                                    $encasoespecial = false;
                                                    $tmp_casoespecial['valor'] = substr(trim(intval(preg_replace('/[^0-9]+/', '', $datos_espaciados[$j]), 10)), 0, -2);
                                                    $suma_temporal = $suma_temporal+(int)$tmp_casoespecial['valor'];
                                                    if (($ingresos_totales/$suma_temporal) >= 1) {
                                                        $registros['ingresos'][] = $tmp_casoespecial;
                                                    } else {
                                                        $registros['egresos'][] = $tmp_casoespecial;
                                                    }
                                                    $tmp_casoespecial['codConcepto'] = '';
                                                    $tmp_casoespecial['concepto'] = '';
                                                    $tmp_casoespecial['valor'] = '';
                                                } else {
                                                    $tmp_casoespecial['concepto'] .= ' ' . $datos_espaciados[$j];
                                                }
                                            }
                                        }
                                    }
                                }

                                $persona = array(
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

                                // $jobId = CargarDatosComprobantes::dispatch($persona, $pagaduria, $plano)
                                //     ->onConnection('database')
                                //     ->onQueue('uploadingComprobantes');

                                //Cargar datos del cliente
                                $this->upload($persona);
                                
                            } else {
                                // Extraer nombres
                                $keynombres = (array_search('N. Contratacion:', $lineas))-1;
                                $nombres = trim($lineas[$keynombres]);
                                // Extraer documento
                                $lineadocumento = trim($lineas[$keynombres+3]);
                                $documento = trim(intval(preg_replace('/[^0-9]+/', '', $lineadocumento), 10));
                                // Extraer cargo
                                $keycargo = (array_search('Cargo:', $lineas))+2;
                                $cargo = trim($lineas[$keycargo]);
                                // Extraer ingresos
                                $keyingresos = (array_search('Cargo:', $lineas))+1;
                                $ingresos = trim(intval(preg_replace('/[^0-9]+/', '', $lineas[$keyingresos]), 10));
                                // Extraer ciudad
                                $keyciudad = (array_search('Periodo de pago:', $lineas)-1);
                                $lineas[$keyciudad] = str_replace('Ciudad:', '', $lineas[$keyciudad]);
                                if ((strpos($lineas[$keyciudad], '(') !== false)) {
                                    preg_match('#\((.*?)\)#', $lineas[$keyciudad], $match);
                                    $ciudad = strtoupper(trim(str_replace($match[0], '', $lineas[$keyciudad])));
                                } else {
                                    $ciudad = $lineas[$keyciudad];
                                }
                                // Extraer tipo contratación
                                $linea_contr = explode('	', $lineas[$keyciudad-1]);
                                $tipo_contratacion = trim($linea_contr[0]);
                                // Extraer centro de costos
                                $centro_costos = trim($lineas[$keynombres-1]);
                                // Extraer grado
                                $keygrado = (array_search('CodConcepto	Concepto	Cuotas	Dias	Ingresos	Egresos	', $lineas)-1);
                                $grado = trim(str_replace('Grado:', '', $lineas[$keygrado]));
                                // Extraer periodo
                                setlocale (LC_TIME, "es_CO");
                                $keyperiodo = $keygrado-3;
                                $linea_periodo = explode('	', $lineas[$keyperiodo]);
                                $periodo = strftime("%Y%m", strtotime(trim($linea_periodo[0])));
                                // Extraer ingresos totales - egresos totales
                                $keyinicio = array_search('CodConcepto	Concepto	Cuotas	Dias	Ingresos	Egresos	', $lineas);
                                $keyfin = array_search('Firma', $lineas);
                                $keying_egr = $keyfin+3;
                                $validaring_egr = explode("	", $lineas[$keying_egr]);
                                if (sizeof($validaring_egr) !== 4) {
                                    $keying_egr++;
                                }
                                $ingresos_egresos = explode("	", $lineas[$keying_egr]);
                                $ingresos_totales = substr(trim(intval(preg_replace('/[^0-9]+/', '', $ingresos_egresos[1]), 10)), 0, -2);
                                $egresos_totales = substr(trim(intval(preg_replace('/[^0-9]+/', '', $ingresos_egresos[2]), 10)), 0, -2);
                                // Extracción de datos ingresos-egresos
                                $registros = array();
                                $tmp_casoespecial = array();
                                $tmp_casoespecial['codConcepto'] = '';
                                $tmp_casoespecial['concepto'] = '';
                                $tmp_casoespecial['valor'] = '';
                                $suma_temporal = 0;
                                $encasoespecial = false;
                                for ($i=$keyinicio+1; $i < $keyfin; $i++) {
                                    $datos_registro = str_replace("	", " ", $lineas[$i]);
                                    $datos_espaciados = preg_split('/\s+/', $datos_registro);
                                    // Valido si es un array completo
                                    $esvalido = false;
                                    for ($j=0; $j < sizeof($datos_espaciados); $j++) { 
                                        if (is_numeric(strpos($datos_espaciados[$j], '.00'))) {
                                            if (!is_numeric(strpos($datos_espaciados[$j], '%'))) {
                                                $esvalido = true;
                                                break;
                                            }
                                        }
                                    }

                                    if ($esvalido && !$encasoespecial) {
                                        $concepto_completo_array = array();
                                        $pos_valor = sizeof($datos_espaciados)-2;
                                        for ($j=1; $j < $pos_valor; $j++) { 
                                            array_push($concepto_completo_array, $datos_espaciados[$j]);
                                        }
                                        $concepto_completo = implode(' ', $concepto_completo_array);
                                        $valor_sin_formato = substr(trim(intval(preg_replace('/[^0-9]+/', '', $datos_espaciados[$pos_valor]), 10)), 0, -2);
                                        $suma_temporal = $suma_temporal+(int)$valor_sin_formato;
                                        if (($ingresos_totales/$suma_temporal) >= 1) {
                                            $registros['ingresos'][] = array(
                                                'codConcepto' => $datos_espaciados[0],
                                                'concepto' => $concepto_completo,
                                                'valor' => $valor_sin_formato,
                                            );
                                        } else {
                                            $registros['egresos'][] = array(
                                                'codConcepto' => $datos_espaciados[0],
                                                'concepto' => $concepto_completo,
                                                'valor' => $valor_sin_formato,
                                            );
                                        }
                                    } else {
                                        $encasoespecial = true;
                                        $keyinit_proc = 0;
                                        if ($tmp_casoespecial['codConcepto'] == '') {
                                            $tmp_casoespecial['codConcepto'] = $datos_espaciados[0];
                                            for ($j=$keyinit_proc; $j < sizeof($datos_espaciados); $j++) { 
                                                if (is_numeric(strpos($datos_espaciados[$j], '.00')) && !is_numeric(strpos($datos_espaciados[$j], '%'))) {
                                                    $encasoespecial = false;
                                                    $tmp_casoespecial['valor'] = substr(trim(intval(preg_replace('/[^0-9]+/', '', $datos_espaciados[$j]), 10)), 0, -2);
                                                    $suma_temporal = $suma_temporal+(int)$tmp_casoespecial['valor'];
                                                    if (($ingresos_totales/$suma_temporal) >= 1) {
                                                        $registros['ingresos'][] = $tmp_casoespecial;
                                                    } else {
                                                        $registros['egresos'][] = $tmp_casoespecial;
                                                    }
                                                    $tmp_casoespecial['codConcepto'] = '';
                                                    $tmp_casoespecial['concepto'] = '';
                                                    $tmp_casoespecial['valor'] = '';
                                                } else {
                                                    $tmp_casoespecial['concepto'] .= ' ' . $datos_espaciados[$j];
                                                }
                                            }
                                        } else {
                                            for ($j=0; $j < sizeof($datos_espaciados); $j++) { 
                                                if (is_numeric(strpos($datos_espaciados[$j], '.00')) && !is_numeric(strpos($datos_espaciados[$j], '%'))) {
                                                    $tmp_casoespecial['valor'] = $datos_espaciados[$j];
                                                    $encasoespecial = false;
                                                    $tmp_casoespecial['valor'] = substr(trim(intval(preg_replace('/[^0-9]+/', '', $datos_espaciados[$j]), 10)), 0, -2);
                                                    $suma_temporal = $suma_temporal+(int)$tmp_casoespecial['valor'];
                                                    if (($ingresos_totales/$suma_temporal) >= 1) {
                                                        $registros['ingresos'][] = $tmp_casoespecial;
                                                    } else {
                                                        $registros['egresos'][] = $tmp_casoespecial;
                                                    }
                                                    $tmp_casoespecial['codConcepto'] = '';
                                                    $tmp_casoespecial['concepto'] = '';
                                                    $tmp_casoespecial['valor'] = '';
                                                } else {
                                                    $tmp_casoespecial['concepto'] .= ' ' . $datos_espaciados[$j];
                                                }
                                            }
                                        }
                                    }
                                }

                                $persona = array(
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
                                
                                // $jobId = CargarDatosComprobantes::dispatch($persona, $pagaduria, $plano)
                                //     ->onConnection('database')
                                //     ->onQueue('uploadingComprobantes');
                                
                                //Cargar datos del cliente
                                $this->upload($persona);
                                
                            }
                        }
                    }

                    $response = array(
                        'cod' => '200',
                        'mensaje' => 'Se han actualizado los registros encontrados en el archivo fuente.',
                    );
                } else {
                    //----------------------------------------
                    //Eliminar archivo temporal
                    \Storage::disk('archivos')->delete($nombrearchivo);
                    $plano->cont_procesos = -1;
                    $plano->errors = "Error: El archivo no es válido";
                    $plano->save();
                }
            }

            //----------------------------------------
            //Eliminar archivo temporal
            \Storage::disk('archivos')->delete($nombrearchivo);
            
            $plano->cont_procesos--;
            $plano->save();
        } catch(\Exception $ex) {
            //----------------------------------------
            //Eliminar archivo temporal
            \Storage::disk('archivos')->delete($nombrearchivo);
            $plano->cont_procesos = -1;
            $plano->errors = "Error: " . $ex->getMessage();
            $plano->save();
        }
    }

    public function upload($persona)
    {
        set_time_limit(0);

        $pagaduria = $this->pagaduria;
        $cliente = \App\Clientes::where("documento", "=", $persona['documento'])->first();
        $ciudad = \App\Ciudades::where('ciudad', $persona['ciudad'] )->first();

        try {
        
            //Crear ciudad
            if ($ciudad === null) {
                $ciudad = new \App\Ciudades;
                $ciudad->departamentos_id = 1;
                $ciudad->ciudad = $persona['ciudad'];
                $ciudad->save();
            }
            
            //Cliente crear-actualizar existente
            if ($cliente === null) {
                $cliente = new \App\Clientes;
                $cliente->users_id				= 1;
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

        } catch(\Exception $ex) {
            //----------------------------------------
            //Eliminar archivo temporal
            \Storage::disk('archivos')->delete($nombreArchivoTmp . "." . $extension);
            $plano->cont_procesos = -1;
            $plano->errors = "Error: " . $ex->getMessage();
            $plano->save();
        }
    }
}
