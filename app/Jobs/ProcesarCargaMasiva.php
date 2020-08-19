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
        try {
            set_time_limit(0);
            $ruta_archivo = $this->ruta_archivo;
            $nombre_archivo = $this->nombre_archivo;
            $ruta_output = $this->ruta_output;
            $ruta_pdfs = $this->ruta_pdfs;
            $time = $this->time;
            $carga_archivo = $this->carga_archivo;

            $ruta_gcp_pdf_original = 'docs_uploads/masivos/' . $time . '/' . $nombre_archivo;
            $ruta_credentials = base_path() . DIRECTORY_SEPARATOR . "credentials.json";

            // Subida a GCP archivo original
            $disk = \Storage::disk('gcs');
            $respond = $disk->put($ruta_gcp_pdf_original, \File::get($ruta_archivo));

            if ($respond == '1') {
                $response = array(
                    'cod' => '200',
                    'mensaje' => 'El archivo se ha cargado correctamente.',
                );
            } else {
                $response = array(
                    'cod' => '300',
                    'mensaje' => $respond,
                );
            }

            // División y subida a GCP 
            $ruta_gcp = "docs_uploads/masivos/" . $time . "/divididos";

            $args = array(
                storage_path('archivos') . $ruta_pdfs,
                storage_path('archivos') . $ruta_output,
                $ruta_gcp,
                $ruta_credentials
            );

            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
				$py_version = "python";
            } else {
                $py_version = "/usr/bin/venv_ami/bin/python";
            }

            $comand = $py_version . " " . app_path() . DIRECTORY_SEPARATOR . "dividir_pdf_pages_gcp.py --pdfs " . $args[0] . " --output " . $args[1] . " --cedula " . $args[2] . " --gcpfolder docs_uploads --gcp_credentials " . $args[3] . " 2>&1";
            $response = shell_exec($comand);
            // echo '<br>' . $response;

            // Clasificación
            $args2 = array(
                "ami_laravel",
                $ruta_gcp,
                $ruta_credentials
            );

            $response_clas = shell_exec($py_version . " " . app_path() . DIRECTORY_SEPARATOR . "predict_classdoc_folder_masivo.py \"" . $args2[0] . "\" \"" . $args2[1] . "\" \"" . $args2[2] . "\" 2>&1");

            //Termino el proceso
            $carga_archivo->tipo = $response_clas;
            $carga_archivo->cont_procesos = 0;
            $carga_archivo->save();

    		\Storage::disk('archivos')->deleteDirectory($ruta_pdfs); // Eliminar la carpeta en local
            
        } catch (\Exception $e) {            
            //----------------------------------------
            //Eliminar archivo temporal
            \Storage::disk('archivos')->deleteDirectory($ruta_pdfs); // Eliminar la carpeta en local
            $carga_archivo->cont_procesos = -1;
            $carga_archivo->errors = "Error: " . $e->getMessage();
            $carga_archivo->save();
        }
    }
}
