<?php

namespace App\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcesarCargaMasiva implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $ruta_archivo;
    protected $nombre_archivo;
    protected $ruta_output;
    protected $ruta_pdfs;
    protected $time;
    protected $carga_archivo;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($ruta_archivo, $nombre_archivo, $ruta_output, $ruta_pdfs, $time, $carga_archivo)
    {
        $this->ruta_archivo = $ruta_archivo;
        $this->nombre_archivo = $nombre_archivo;
        $this->ruta_output = $ruta_output;
        $this->ruta_pdfs = $ruta_pdfs;
        $this->time = $time;
        $this->carga_archivo = $carga_archivo;

        $carga_archivo->cont_procesos++;
        $carga_archivo->save();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $log = '';
        try {
            set_time_limit(0);
            $ruta_archivo = $this->ruta_archivo;
            $nombre_archivo = $this->nombre_archivo;
            $ruta_output = $this->ruta_output;
            $ruta_pdfs = $this->ruta_pdfs;
            $time = $this->time;
            $carga_archivo = $this->carga_archivo;

            //Variables generales
            $ruta_credentials = base_path() . DIRECTORY_SEPARATOR . "credentials.json";
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
				$py_version = "python";
            } else {
                $py_version = "/usr/bin/venv_ami/bin/python";
            }

            // División y subida a GCP 
            $ruta_gcp = "docs_uploads/masivos/" . $time . "/divididos";
            $ruta_gcp_pdf_original = 'docs_uploads/masivos/' . $time;

            $args = array(
                storage_path('archivos') . $ruta_pdfs,
                $ruta_gcp_pdf_original,
                storage_path('archivos') . DIRECTORY_SEPARATOR . 'tmp',
                storage_path('archivos') . DIRECTORY_SEPARATOR . 'tmp_output',
                $ruta_gcp,
                $ruta_credentials
            );

            $comand = $py_version . " " . app_path() . DIRECTORY_SEPARATOR . "dividir_pdf_pages_gcp_upload.py --localfolder " . $args[0] . " --gcpfolder " . $args[1] . " --pdfs " . $args[2] . " --output " . $args[3] . " --cedula " . $args[4] . " --gcp_credentials " . $args[5] . " 2>&1";
            $response = shell_exec($comand);
            $log .= '*****Proceso de división: ' . $response;


            // Clasificación
            $args2 = array(
                "ami_laravel",
                $ruta_gcp,
                $ruta_credentials
            );

            $response_clas = shell_exec($py_version . " " . app_path() . DIRECTORY_SEPARATOR . "predict_classdoc_folder_masivo.py \"" . $args2[0] . "\" \"" . $args2[1] . "\" \"" . $args2[2] . "\" 2>&1");
            $log .= '*****Proceso de Clasificación: ' . $response_clas;
            

            // Extracción de entidades
            $args3 = array(
                "ami_laravel",
                "docs_uploads/masivos/" . $time . "/" . $response_clas
            );

            $response_extract = shell_exec($py_version . " " . app_path() . DIRECTORY_SEPARATOR . "predict_ner_gcp_ami_folder_full.py --bucket " . $args3[0] . " --folder " . $args3[1] . " 2>&1");
            $personas = json_decode($response_extract);
            $personas_upload = array();
            $log .= '*****Proceso de Extracción de entidades: ' . count($personas);

            foreach ($personas as $key => $persona) {
                $personas_upload[] = array(
                    'nombres' => ( isset($persona[0]->nombres) ? $persona[0]->nombres : '' ),
                    'apellidos' => ( isset($persona[0]->apellidos) ? $persona[0]->apellidos : '' ),
                    'documento' => ( isset($persona[0]->documento) ? $persona[0]->documento : '' ),
                    'cargo' => ( isset($persona[0]->cargo) ? $persona[0]->cargo : '' ),
                    'ciudad' => ( isset($persona[0]->ciudad) ? $persona[0]->ciudad : '' ),
                    'centro_costos' => ( isset($persona[0]->centro_costos) ? $persona[0]->centro_costos : '' ),
                    'grado' => ( isset($persona[0]->grado) ? $persona[0]->grado : '' ),
                    'tipo_contratacion' => ( isset($persona[0]->tipo_contratacion) ? $persona[0]->tipo_contratacion : '' ),
                    'periodo' => ( isset($persona[0]->periodo) ? $persona[0]->periodo : '' ),
                    'secretaria' => ( isset($persona[0]->Secretaria_Educacion) ? $persona[0]->Secretaria_Educacion : '' ),
                    'conceptos_financieros' => ( isset($persona[0]->conceptos_financieros[0]) ? $persona[0]->conceptos_financieros[0] : '' )
                );
            }
	        $respuesta_upload = upload_personas($personas_upload);
            $log .= '*****Proceso de ingreso a BD: ' . $response;

            //Termino el proceso
            $carga_archivo->tipo = $response_clas;
            $carga_archivo->logs = $log;
            $carga_archivo->cont_procesos = 0;
            $carga_archivo->save();
            

    		\Storage::disk('archivos')->deleteDirectory($ruta_pdfs); // Eliminar la carpeta en local
            
        } catch (\Exception $e) {            
            //----------------------------------------
            //Eliminar archivo temporal
            \Storage::disk('archivos')->deleteDirectory($ruta_pdfs); // Eliminar la carpeta en local
            $carga_archivo->cont_procesos = -1;
            $carga_archivo->logs = "Error: " . $e->getMessage();
            $carga_archivo->logs .= "Logs: " . $log;
            $carga_archivo->save();
        }
    }
}
