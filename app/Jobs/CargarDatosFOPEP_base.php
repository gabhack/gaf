<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Exception;

class CargarDatosFOPEP_base implements ShouldQueue
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

            
            $reader = ReaderEntityFactory::createReaderFromFile($ruta);
            $reader->open($ruta);

            $keys = array();

            foreach ($reader->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $key => $row) {
                    $datos = $row->toArray();
                    if ($key == 1) {
                        foreach ($datos as $key => $value) {
                            $keys[strtolower(preg_replace('([^A-Za-z0-9])', '', $value))] = $key;
                        }
                    } else {

                        if ( $datos[$keys['mnpionombremunicipioderesidenciadelpensionado']] !== '') {
                            $ciudad = \App\Ciudades::where('ciudad', $datos[$keys['mnpionombremunicipioderesidenciadelpensionado']] )->first();

                            if ($ciudad === null) {
                                $ciudad = new \App\Ciudades;
                                $ciudad->ciudad = $datos[$keys['mnpionombremunicipioderesidenciadelpensionado']];
                                $ciudad->save();
                            }
                        }
                        $cliente = \App\Clientes::where("documento", "=", $datos[$keys['documento']])->first();

                        if ($cliente === null) {
                            $cliente = new \App\Clientes;
                            $cliente->users_id			= 1;
                            if ( $datos[$keys['mnpionombremunicipioderesidenciadelpensionado']] !== '') {
                                $cliente->ciudades_id		= $ciudad['id'];
                            }
                            $cliente->tipodocumento	= $datos[$keys['tddocumento']];
                            $cliente->documento 		= $datos[$keys['documento']];
                            $cliente->nombres 			= $datos[$keys['pensionadoapellidosynombres']];
                            $cliente->fechanto 		= date_format($datos[$keys['fechadenacimiento']], 'Y-m-d');
                            $cliente->sexo 			= "";
                            $cliente->telefono			= $datos[$keys['telfono']];
                            $cliente->direccion		= $datos[$keys['direccion']];
                            $cliente->correo			= $datos[$keys['correoelectrnico']];
                            $cliente->ingresos         = $datos[$keys['valorpensiones']];
                            $cliente->save();
                        } else {
                            // if ($cliente->apellidos = '') {
                                if ( $datos[$keys['mnpionombremunicipioderesidenciadelpensionado']] !== '') {
                                    $cliente->ciudades_id		= $ciudad['id'];
                                }
                                $cliente->tipodocumento		= $datos[$keys['tddocumento']];
                                $cliente->documento 		= $datos[$keys['documento']];
                                $cliente->nombres 			= $datos[$keys['pensionadoapellidosynombres']];
                                $cliente->fechanto 			= date_format($datos[$keys['fechadenacimiento']], 'Y-m-d');
                                $cliente->sexo 				= "";
                                $cliente->telefono			= $datos[$keys['telfono']];
                                $cliente->direccion			= $datos[$keys['direccion']];
                                $cliente->correo			= $datos[$keys['correoelectrnico']];
                                $cliente->ingresos          = $datos[$keys['valorpensiones']];
                                $cliente->save();
                            // }
                        }

                        if ($datos[$keys['valorembargos']] != '0') {
                            print_r(array(
                                'cliente' => $datos[$keys['documento']],
                                'embargos' => $datos[$keys['valorembargos']]
                            ));

                            $motivo_embargo = \App\Motivosembargos::where('motivo', 'GENERAL REPORTE EN FOPEP' )->first();
                            if ($motivo_embargo === null) {
                                $motivo_embargo = new \App\Motivosembargos;
                                $motivo_embargo->motivo = 'GENERAL REPORTE EN FOPEP';
                                $motivo_embargo->save();
                            }

                            //creación de embargo genérico
                            $embargo = \App\Embargos::where("motivos_id", "=", $motivo_embargo->id)
                                ->where("clientes_id", "=", $cliente->id)
                                ->where("pagadurias_id", "=", $pagaduria->id)
                                ->where("valor", "=", $datos[$keys['valorembargos']])
                                ->where("fecha", "=", date('Y-m-d'))
                                ->first();

                            
                            print_r(array(
                                'embargo' => $embargo
                            ));

                            if ($embargo === null) {
                                $nuevoEmbargo = new \App\Embargos;
                                $nuevoEmbargo->motivos_id       = $motivo_embargo->id;
                                $nuevoEmbargo->clientes_id      = $cliente->id;
                                $nuevoEmbargo->pagadurias_id    = $pagaduria->id;
                                $nuevoEmbargo->valor            = $datos[$keys['valorembargos']];
                                $nuevoEmbargo->fecha            = date('Y-m-d');
                                $nuevoEmbargo->save();
                            
                                print_r(array(
                                    'nuevoEmbargo' => $nuevoEmbargo
                                ));
                            }
                        }
                    }
                }
            }

            $reader->close();
            
            $plano->cont_procesos = 0;
            $plano->save();
            
            //Eliminar archivo temporal
            \Storage::disk('archivos')->delete($nombrearchivo);

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
