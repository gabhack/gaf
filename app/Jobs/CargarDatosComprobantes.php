<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Exception;

class CargarDatosComprobantes implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $persona;
    protected $pagaduria;
    protected $plano;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($persona, $pagaduria, $plano)
    {
        $this->persona = $persona;
        $this->pagaduria = $pagaduria;
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
        set_time_limit(0);

        $persona = $this->persona;
        $pagaduria = $this->pagaduria;
        $plano = $this->plano;

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
        
            $plano->cont_procesos--;
            $plano->save();

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
