<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Exception;

class CargarDatosFOPEP_desc_no_aplic implements ShouldQueue
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
                        if ($datos[$keys['documento']]!='') {
                            $cliente = \App\Clientes::where("documento", "=", $datos[$keys['documento']])->first();
                            $entidad = \App\Entidades::where("id", "=", $datos[$keys['tercero']])->first();
        
                            if ($cliente === null) {
                                $cliente = new \App\Clientes;
                                $cliente->users_id			= \Auth::user()->id;
                                $cliente->tipodocumento		= $datos[$keys['tipodocumento']];
                                $cliente->documento 		= $datos[$keys['documento']];
                                $cliente->nombres 			= $datos[$keys['nombre']];
                                $cliente->save();
                            }
        
                            if ($entidad === null) {
                                $entidad = new \App\Entidades;
                                $entidad->id				= $datos[$keys['tercero']];
                                $entidad->entidad			= $datos[$keys['nombretercero']];
                                $entidad->save();
                            }
        
                            $descuento = \App\Descuentosnoaplicados::where("fecha", "=", $datos[$keys['fechagrabacion']])
                                ->where("entidades_id", "=", $datos[$keys['tercero']])
                                ->where("clientes_id", "=", $cliente->id)
                                ->first();
        
                            
                            
                            if ($descuento === null) {
                                $nuevoDescuentoA = new \App\Descuentosnoaplicados;
                                $nuevoDescuentoA->clientes_id		= $cliente->id;
                                $nuevoDescuentoA->entidades_id 		= $entidad->id;
                                $nuevoDescuentoA->pagadurias_id		= $pagaduria->id;
                                $nuevoDescuentoA->valor_total		= number_format($datos[$keys['valortotal']], 0, '', '');
                                $nuevoDescuentoA->valor_pagado		= number_format($datos[$keys['valorpagado']], 0, '', '');
                                $nuevoDescuentoA->porcentaje		= $datos[$keys['porcentaje']];
                                $nuevoDescuentoA->valor_fijo		= number_format($datos[$keys['valorfijo']], 0, '', '');
                                $nuevoDescuentoA->valor_aplicado	= number_format($datos[$keys['valoraplicado']], 0, '', '');
                                $nuevoDescuentoA->pagare			= $datos[$keys['pagare']];
                                $nuevoDescuentoA->fecha				= date_format($datos[$keys['fechagrabacion']], 'Y-m-d');
                                $nuevoDescuentoA->inconsistencia	= $datos[$keys['inconsistencia']];
                                $nuevoDescuentoA->saldo				= number_format($datos[$keys['saldo']], 0, '', '');
                                $nuevoDescuentoA->save();
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
