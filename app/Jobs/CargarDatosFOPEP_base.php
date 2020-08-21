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
                            $nuevoCliente = new \App\Clientes;
                            $nuevoCliente->users_id			= 1;
                            if ( $datos[$keys['mnpionombremunicipioderesidenciadelpensionado']] !== '') {
                                $nuevoCliente->ciudades_id		= $ciudad['id'];
                            }
                            $nuevoCliente->tipodocumento	= $datos[$keys['tddocumento']];
                            $nuevoCliente->documento 		= $datos[$keys['documento']];
                            $nuevoCliente->nombres 			= $datos[$keys['pensionadoapellidosynombres']];
                            $nuevoCliente->fechanto 		= date_format($datos[$keys['fechadenacimiento']], 'Y-m-d');
                            $nuevoCliente->sexo 			= "";
                            $nuevoCliente->telefono			= $datos[$keys['telfono']];
                            $nuevoCliente->direccion		= $datos[$keys['direccion']];
                            $nuevoCliente->correo			= $datos[$keys['correoelectrnico']];
                            $nuevoCliente->save();
                        } else {
                            if ($cliente->apellidos = '') {
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
                                $cliente->save();
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
