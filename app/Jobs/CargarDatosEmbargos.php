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

class CargarDatosEmbargos implements ShouldQueue
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
}