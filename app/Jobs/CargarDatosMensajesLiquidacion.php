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

class CargarDatosMensajesLiquidacion implements ShouldQueue
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
