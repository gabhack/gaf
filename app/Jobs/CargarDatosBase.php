<?php

namespace App\Jobs;

use App\Helper;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CargarDatosBase implements ShouldQueue
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
            ini_set('memory_limit', '-1');

            $ruta = $this->ruta;
            $pagaduria = $this->pagaduria;
            $nombrearchivo = $this->nombrearchivo;
            $plano = $this->plano;
			
			$titulos 	= array();
			$tmpDatos 	= array();
			$datos 		= array();
			$array 		= array();

            $personas 	= array();
            
            $file = \File::get($ruta);
		
			foreach (explode("\n", $file) as $key => $line){
				if($key == 0)
				{
					$r = preg_replace(
									  '/
										^
										[\pZ\p{Cc}\x{feff}]+
										|
										[\pZ\p{Cc}\x{feff}]+$
									   /ux',
									  '', 
									  $line);
					$titulos = preg_split("/[\t]/", $r);				
				}
				else if($line == "" )
					continue;
				else
				{
					$tmpDatos = preg_split("/[\t]/", $line);
					$i = 0;
					foreach($titulos as $k => $titulo)
					{
						$datos[trim(strtolower($titulo))] = $tmpDatos[$k];
					}
					
					// Guardar en BD
					if (is_numeric(strpos($datos['ciudad'], '('))) {
						preg_match('#\((.*?)\)#', $datos['ciudad'], $match);
					}
					$ciudadstr = strtoupper(trim(str_replace($match[0], '', $datos['ciudad'])));
					
					$cliente = \App\Clientes::where("documento", "=", $datos['numvinculacion'])->first();
					$ciudad = \App\Ciudades::where('ciudad', $ciudadstr)->first();
					
					//Crear ciudad
                    if ($ciudad === null) {
						$ciudad = new \App\Ciudades;
						$ciudad->departamentos_id = 1;
						$ciudad->ciudad = $ciudadstr;
						$ciudad->save();
					}

					//Cliente crear-actualizar existente
					if ($cliente === null) 
					{
						$cliente = new \App\Clientes;
						$cliente->users_id				= \Auth::user()->id;
						$cliente->ciudades_id 			= $ciudad['id'];
						$cliente->tipodocumento			= 'CC';
						$cliente->documento 			= $datos['numvinculacion'];
						$cliente->sexo 					= $datos['genero'];
						$cliente->direccion 			= $datos['direccion'];
						$cliente->nombres 				= $datos['empleado'];
						$cliente->centro_costo 			= $datos['centrocosto'];
						$cliente->cargo 				= $datos['cargoempresa'];
						$cliente->tipo_contratacion 	= $datos['nivelcontratacion'];
						$cliente->grado 				= $datos['grado'];
						$cliente->telefono 				= $datos['telefono'];
						$cliente->correo				= $datos['email'];
						$cliente->save();
					} 
					else 
					{
						if ($cliente->ciudades_id == '') {
							$cliente->ciudades_id 			= $ciudad['id'];
						}
						if ($cliente->sexo == '') {
							$cliente->sexo 					= $datos['genero'];
						}
						if ($cliente->direccion == '') {
							$cliente->direccion 			= $datos['direccion'];
						}
						if ($cliente->nombres == '') {
							$cliente->nombres 				= $datos['empleado'];
						}
						if ($cliente->centro_costo == '') {
							$cliente->centro_costo 			= $datos['centrocosto'];
						}
						if ($cliente->cargo == '') {
							$cliente->cargo 				= $datos['cargoempresa'];
						}
						if ($cliente->tipo_contratacion == '') {
							$cliente->tipo_contratacion 	= $datos['nivelcontratacion'];
						}
						if ($cliente->grado == '') {
							$cliente->grado 				= $datos['grado'];
						}
						if ($cliente->telefono == '') {
							$cliente->telefono 				= $datos['telefono'];
						}
						if ($cliente->correo == '') {
							$cliente->correo				= $datos['email'];
						}

						//Guardamos en caso de que haya cambios
						if (count($cliente->getDirty()) > 0)
						{
							$cliente->save();
						}
					}
				}
			}
			
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