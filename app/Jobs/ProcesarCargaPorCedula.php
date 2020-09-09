<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcesarCargaPorCedula implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $ruta_output;
    protected $ruta_pdfs;
    protected $cedula;
    protected $carga_archivo;
    protected $time;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($ruta_pdfs, $ruta_output, $cedula, $carga_archivo, $time)
    {
        $this->ruta_output = $ruta_output;
        $this->ruta_pdfs = $ruta_pdfs;
        $this->cedula = $cedula;
        $this->carga_archivo = $carga_archivo;
        $this->time = $time;

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
            $ruta_output = $this->ruta_output;
            $ruta_pdfs = $this->ruta_pdfs;
            $cedula = $this->cedula;
            $carga_archivo = $this->carga_archivo;
            $time = $this->time;

            //Variables generales
            $ruta_credentials = base_path() . DIRECTORY_SEPARATOR . "credentials.json";
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
				$py_version = "python";
            } else {
                $py_version = "/usr/bin/venv_ami/bin/python";
            }

            //División de archivos
            $ruta_gcp = "docs_uploads/x_cedula/" . $cedula . "/" . $time . "/divididos";
            $ruta_gcp_pdf_original = "docs_uploads/x_cedula/" . $cedula . "/" . $time . "/originales";

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

            $response_clas = shell_exec($py_version . " " . app_path() . DIRECTORY_SEPARATOR . "predict_classdoc_folder_cedula.py \"" . $args2[0] . "\" \"" . $args2[1] . "\" \"" . $args2[2] . "\" 2>&1");
            $log .= '*****Proceso de Clasificación: ' . $response_clas;
            $archivos_clasificados = json_decode($response_clas);
            $string_clases = "";
            

            //Termino el proceso
            $carga_archivo->clases_detectadas = $response_clas;
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
            $carga_archivo->logs .= " - Logs: " . $log;
            $carga_archivo->save();
        }
    }
}
