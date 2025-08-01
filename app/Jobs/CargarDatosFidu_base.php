<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Exception;

class CargarDatosFidu_base implements ShouldQueue
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
                        $cliente = \App\Clientes::where("documento", "=", $datos[$keys['documento']])->first();
                        if ($cliente === null) {
                            $nuevoCliente = new \App\Clientes;
                            $nuevoCliente->users_id			= 1;
                            $nuevoCliente->tipodocumento	= $datos[$keys['tipodocumento']];
                            $nuevoCliente->documento		= $datos[$keys['documento']];
                            $nuevoCliente->nombres 			= $datos[$keys['nombres']];
                            $nuevoCliente->apellidos 		= $datos[$keys['apellidos']];
                            $nuevoCliente->fechanto 		= date_format($datos[$keys['fechanacimientodocente']], 'Y-m-d');
                            $nuevoCliente->sexo 			= $datos[$keys['sexo']];
                            $nuevoCliente->estado_civil 	= $datos[$keys['descripcionec']];
                            if ($datos[$keys['telefono']]!=='') {
                                $nuevoCliente->telefono		= str_replace("-", "", $datos[$keys['telefono']]);
                            }
                            $nuevoCliente->direccion		= $datos[$keys['direccion']];
                            $nuevoCliente->correo			= $datos[$keys['correo']];
                            $nuevoCliente->ingresos			= $datos[$keys['pagonet']];
                            $nuevoCliente->save();
                        } else {
                            if ($cliente->apellidos = '') {
                                $cliente->nombres 				= $datos[$keys['nombres']];
                                $cliente->apellidos 			= $datos[$keys['apellidos']];
                                $cliente->sexo 					= $datos[$keys['sexo']];
                                $cliente->estado_civil 			= $datos[$keys['descripcion_ec']];
                                if ($datos[$keys['telefono']]!=='') {
                                    $cliente->telefono		= str_replace("-", "", $datos[$keys['telefono']]);
                                }
                                $cliente->direccion				= $datos[$keys['direccion']];
                                $cliente->correo				= $datos[$keys['correo']];
                                $cliente->ingresos			= $datos[$keys['pago_net']];
                                
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
