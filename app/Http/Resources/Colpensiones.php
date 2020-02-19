<?php

namespace App\Http\Resources;
use Illuminate\Http\Request;

class Colpensiones
{
	
	public static function base(Request $request)
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

			$parseador = new \Smalot\PdfParser\Parser();
			$documento = $parseador->parseFile($ruta);

			$text = $documento->getText();
            $lineas = explode("\n", $text);

            $textprocesado = str_replace("\n", ' ', $text);
            
            //Extraer datos basicos
            //Nombres
            $posinitnombre = strpos($textprocesado, 'señor(a)')+8;
            $posfinnombre = strpos($textprocesado, 'identificado(a)');
            $nombres = trim(get_string_between($textprocesado, 'señor(a)', 'identificado(a)'));
            //cedula
            $cedula = trim(get_string_between($textprocesado, 'No. ', ', con Número de Afiliación'));
            //periodo
            setlocale (LC_ALL, "es_ES");
            $periodosinproc = trim(get_string_between($textprocesado, 'NOMINA de ', 'en la Entidad'));
            $periodoarray = explode(" de ", $periodosinproc);
            $mes = mes_esp_a_ing($periodoarray[0]);
            $ano = $periodoarray[1] . '';
            $fecha = '01-' . $mes . '-' . $periodoarray[1];
            $date = trim($mes . ' 1 ' . trim($ano));
            $periodo = date("Ym", strtotime($date));
            

            //Extraer la información de ingresos y deducciones
            $keyinicio = array_search('	DEDUCIDOS	', $lineas);
            $keyfin = array_search('TOTAL DEVENGADOS	', $lineas);
            $ingydeducciones = array();
            $arraytmp = array();
            for ($i=$keyinicio+1; $i < $keyfin; $i++) { 
                if ($lineas[$i] == '	') {
                    if(sizeof($arraytmp) != 0){
                        if(sizeof($arraytmp) == 2){
                            $ingydeducciones['deducciones'][] = array(
                                'concepto' => trim($arraytmp[0]), 
                                'valor' => substr(trim(intval(preg_replace('/[^0-9]+/', '', $arraytmp[1]), 10)), 0, -2)
                            );
                        } elseif (sizeof($arraytmp) == 4) {
                            $ingydeducciones['ingresos'][] = array(
                                'concepto' => trim($arraytmp[0]), 
                                'valor' => substr(trim(intval(preg_replace('/[^0-9]+/', '', $arraytmp[2]), 10)), 0, -2)
                            );
                            $ingydeducciones['deducciones'][] = array(
                                'concepto' => trim($arraytmp[1]), 
                                'valor' => substr(trim(intval(preg_replace('/[^0-9]+/', '', $arraytmp[3]), 10)), 0, -2)
                            );
                        }
                    }
                    unset($arraytmp);
                } else {
                    $arraytmp[] = $lineas[$i];
                }
            }

            $persona = array(
                'nombres' => $nombres,
                'cedula' => $cedula,
                'periodo' => $periodo,
                'ingresos' => $ingydeducciones['ingresos'],
                'deducciones' => $ingydeducciones['deducciones']
            );

            // Inserción a BD
            $cliente = \App\Clientes::where("documento", "=", $persona['cedula'])->first();

            if ($cliente === null) {
                $cliente = new \App\Clientes;
                $cliente->users_id				= \Auth::user()->id;
                $cliente->tipodocumento			= 'CC';
                $cliente->documento 			= $persona['cedula'];
                $cliente->nombres 				= $persona['nombres'];
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
            if (isset($persona['ingresos'])) {
                foreach ($persona['ingresos'] as $key => $ingreso) {
                    $ingresoN = \App\Ingresosaplicados::where("registros_id", "=", $registro->id)
                        ->where("concepto", "=", $ingreso['concepto'])
                        ->first();
                    
                    if ($ingresoN === null) {
                        $ingresoN = new \App\Ingresosaplicados;
                        $ingresoN->registros_id		= $registro->id;
                        $ingresoN->concepto			= $ingreso['concepto'];
                        $ingresoN->valor			= $ingreso['valor'];
                        $ingresoN->save();
                    }
                }
            }

            //Descuentos
            if (isset($persona['deducciones'])) {
                foreach ($persona['deducciones'] as $key => $egreso) {
                    $descuentoAplicado = \App\Descuentosaplicados::where("registros_id", "=", $registro->id)
                        ->where("concepto", "=", $egreso['concepto'])
                        ->first();
                    
                    if ($descuentoAplicado === null) {
                        $descuentoAplicado = new \App\Descuentosaplicados;
                        $descuentoAplicado->registros_id		= $registro->id;
                        $descuentoAplicado->concepto			= $egreso['concepto'];
                        $descuentoAplicado->valor				= $egreso['valor'];
                        $descuentoAplicado->save();
                    }
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