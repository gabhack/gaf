<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

//JOBS
use App\Jobs\ProcesarCargaMasiva;
use App\Jobs\ProcesarCargaPorCedula;

class PlanosController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$planos = \App\Planos::orderBy('created_at', 'desc')->get();
		$pagadurias = \App\Pagadurias::orderBy('pagaduria')->get();
		return view('planos/index')->with([
			'planos' => $planos, 
			'pagadurias' => $pagadurias
		]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$pagadurias = \App\Pagadurias::orderBy('pagaduria')->get();
        return view('planos/crear')->with(['pagadurias' => $pagadurias]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		ini_set('memory_limit', '-1');
		$response = array();

		if($request->file('basicos') != "")
		{
			$plano = new \App\Planos;
			$plano->pagadurias_id = $request->input("pagaduria");
			$plano->plano = "";
			$plano->tipo = 'BAS';
			$plano->save();
		}

		if($request->file('aplicados') != "")
		{
			$plano = new \App\Planos;
			$plano->pagadurias_id = $request->input("pagaduria");
			$plano->plano = "";
			$plano->tipo = 'APL';
			$plano->save();
		}

		if($request->file('no_aplicados') != "")
		{
			$plano = new \App\Planos;
			$plano->pagadurias_id = $request->input("pagaduria");
			$plano->plano = "";
			$plano->tipo = 'NAP';
			$plano->save();
		}

		if($request->file('embargos') != "")
		{
			$plano = new \App\Planos;
			$plano->pagadurias_id = $request->input("pagaduria");
			$plano->plano = "";
			$plano->tipo = 'EMB';
			$plano->save();
		}

		if($request->file('comppago'))
		{
			$plano = new \App\Planos;
			$plano->pagadurias_id = $request->input("pagaduria");
			$plano->plano = "";
			$plano->tipo = 'COM';
			$plano->save();
		}

		if($request->file('mens_liquidacion'))
		{
			$plano = new \App\Planos;
			$plano->pagadurias_id = $request->input("pagaduria");
			$plano->plano = "";
			$plano->tipo = 'MLQ';
			$plano->save();
		}

		if($request->file('concep_liquid'))
		{
			$plano = new \App\Planos;
			$plano->pagadurias_id = $request->input("pagaduria");
			$plano->plano = "";
			$plano->tipo = 'CLQ';
			$plano->save();
		}

        $pagaduria = \App\Pagadurias::find($request->input("pagaduria"));
		if($pagaduria->codigo == "SEM_POPAYAN" || $pagaduria->codigo == "SED_VALLE" || $pagaduria->codigo == "SED_CAUCA" || $pagaduria->codigo == "SEM_JAMUNDI" || $pagaduria->codigo == "SEM_QUIBDO" || $pagaduria->codigo == "SED_CHOCO")
		{
			switch ($plano->tipo) {
				case 'BAS':
					$response = \App\Http\Resources\Secretarias::base($request, $plano);
					break;
				case 'APL':
					$response = array(
						'cod' => '300',
						'mensaje' => 'Esta pagaduría no permite el tipo de archivos que seleccionó',
					);
					\App\Planos::destroy($plano->id);
					break;
				case 'NAP':
					$response = array(
						'cod' => '300',
						'mensaje' => 'Esta pagaduría no permite el tipo de archivos que seleccionó',
					);
					\App\Planos::destroy($plano->id);
					break;
				case 'EMB':
					$response = \App\Http\Resources\Secretarias::embargos($request, $plano);
					break;
				case 'COM':
					$response = \App\Http\Resources\Secretarias::comprobante_pago($request, $plano);
					break;
				case 'MLQ':
					$response = \App\Http\Resources\Secretarias::mensajes_liquidacion($request, $plano);
					break;
				case 'CLQ':
					$response = \App\Http\Resources\Secretarias::conceptos_liquidacion($request);
					break;
			}
		} 
		elseif ($pagaduria->codigo == "FOPEP")
		{
			switch ($plano->tipo) {
				case 'BAS':
					$response = \App\Http\Resources\Fopep::base($request, $plano);
					break;
				case 'APL':
					$response = \App\Http\Resources\Fopep::descuentos_aplicados($request, $plano);
					break;
				case 'NAP':
					$response = \App\Http\Resources\Fopep::descuentos_no_aplicados($request, $plano);
					break;
				case 'EMB':
					$response = array(
						'cod' => '300',
						'mensaje' => 'Esta pagaduría no permite el tipo de archivos que seleccionó',
					);
					\App\Planos::destroy($plano->id);
					break;
				case 'COM':
					$response = array(
						'cod' => '300',
						'mensaje' => 'Esta pagaduría no permite el tipo de archivos que seleccionó',
					);
					\App\Planos::destroy($plano->id);
					break;
				case 'MLQ':
					$response = array(
						'cod' => '300',
						'mensaje' => 'Esta pagaduría no permite el tipo de archivos que seleccionó',
					);
					\App\Planos::destroy($plano->id);
					break;
				case 'CLQ':
					$response = array(
						'cod' => '300',
						'mensaje' => 'Esta pagaduría no permite el tipo de archivos que seleccionó',
					);
					\App\Planos::destroy($plano->id);
					break;
			}
		}
		elseif ($pagaduria->codigo == "FIDUPREVISORA")
		{
			switch ($plano->tipo) {
				case 'BAS':
					$response = \App\Http\Resources\Fiduprevisora::base($request, $plano);
					break;
				case 'APL':
					$response = array(
						'cod' => '300',
						'mensaje' => 'Esta pagaduría no permite el tipo de archivos que seleccionó',
					);
					\App\Planos::destroy($plano->id);
					break;
				case 'NAP':
					$response = array(
						'cod' => '300',
						'mensaje' => 'Esta pagaduría no permite el tipo de archivos que seleccionó',
					);
					\App\Planos::destroy($plano->id);
					break;
				case 'EMB':
					$response = array(
						'cod' => '300',
						'mensaje' => 'Esta pagaduría no permite el tipo de archivos que seleccionó',
					);
					\App\Planos::destroy($plano->id);
					break;
				case 'COM':
					$response = array(
						'cod' => '300',
						'mensaje' => 'Esta pagaduría no permite el tipo de archivos que seleccionó',
					);
					\App\Planos::destroy($plano->id);
					break;
				case 'MLQ':
					$response = array(
						'cod' => '300',
						'mensaje' => 'Esta pagaduría no permite el tipo de archivos que seleccionó',
					);
					\App\Planos::destroy($plano->id);
					break;
				case 'CLQ':
					$response = array(
						'cod' => '300',
						'mensaje' => 'Esta pagaduría no permite el tipo de archivos que seleccionó',
					);
					\App\Planos::destroy($plano->id);
					break;
			}
		}
		elseif ($pagaduria->codigo == "COLPENSIONES")
		{
			switch ($plano->tipo) {
				case 'BAS':
					$response = array(
						'cod' => '300',
						'mensaje' => 'Esta pagaduría no permite el tipo de archivos que seleccionó',
					);
					\App\Planos::destroy($plano->id);
					break;
				case 'APL':
					$response = array(
						'cod' => '300',
						'mensaje' => 'Esta pagaduría no permite el tipo de archivos que seleccionó',
					);
					\App\Planos::destroy($plano->id);
					break;
				case 'NAP':
					$response = array(
						'cod' => '300',
						'mensaje' => 'Esta pagaduría no permite el tipo de archivos que seleccionó',
					);
					\App\Planos::destroy($plano->id);
					break;
				case 'EMB':
					$response = array(
						'cod' => '300',
						'mensaje' => 'Esta pagaduría no permite el tipo de archivos que seleccionó',
					);
					\App\Planos::destroy($plano->id);
					break;
				case 'COM':
					$response = \App\Http\Resources\Colpensiones::base($request);
					break;
				case 'MLQ':
					$response = array(
						'cod' => '300',
						'mensaje' => 'Esta pagaduría no permite el tipo de archivos que seleccionó',
					);
					\App\Planos::destroy($plano->id);
					break;
				case 'CLQ':
					$response = array(
						'cod' => '300',
						'mensaje' => 'Esta pagaduría no permite el tipo de archivos que seleccionó',
					);
					\App\Planos::destroy($plano->id);
					break;
			}
		}

        return view('planos/response')->with(['response' => $response]);
	}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_gcp()
    {
		//Variables generales
		$ruta_credentials = base_path() . DIRECTORY_SEPARATOR . "credentials.json";
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			$py_version = "python";
		} else {
			$py_version = "/usr/bin/venv_ami/bin/python";
		}

		// Extracción de entidades
		$args2 = array(
			"ami_laravel",
			"docs_uploads/masivos/20200821_180506/divididos",
			$ruta_credentials
		);

		$response_clas = shell_exec($py_version . " " . app_path() . DIRECTORY_SEPARATOR . "predict_ner_gcp_ami_folder.py \"" . $args2[0] . "\" \"" . $args2[1] . "\" \"" . $args2[2] . "\" 2>&1");
		$res = json_decode($response_clas);

		echo '<pre>';
		print_r($res);
		echo '</pre>';

		// $archivos = \App\CargaArchivo::orderBy('created_at', 'desc')->get();
		// return view('planos/crear-gcp')->with([
		// 	'archivos' => $archivos
		// ]);
    }
	
	/**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
	public function store_gcp_cedula(Request $request)
	{
		ini_set('memory_limit', '-1');
		$response = array();
		$archivos = $request->file('archivos');

		$ruta_pdfs =  DIRECTORY_SEPARATOR . "tmp" . DIRECTORY_SEPARATOR . $request->input("cedula") . DIRECTORY_SEPARATOR;
		$ruta_output =  DIRECTORY_SEPARATOR . "tmp_output";
		
		foreach ($archivos as $key => $archivo) {
			$nombre_original = $archivo->getClientOriginalName();
			$extension = $archivo->getClientOriginalExtension();

			$re = \Storage::disk('archivos')->put( $ruta_pdfs . $nombre_original . "." . $extension, \File::get($archivo));
			$ruta = storage_path('archivos') . $ruta_pdfs . $nombre_original . "." . $extension;
		}

		$this->dividir_pdf($ruta_pdfs, $ruta_output, $request->input("cedula"));

		// return view('planos/response')->with(['response' => $response]);
	}

	/**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
	public function store_gcp_masivo(Request $request)
	{
		try {
			ini_set('memory_limit', '-1');
			$response = array();
			$archivo = $request->file('archivo');
			
			$nombre_original = $archivo->getClientOriginalName();

			$time = date("Ymd_His");

			$ruta_pdfs =  DIRECTORY_SEPARATOR . "upload" . DIRECTORY_SEPARATOR . $time . DIRECTORY_SEPARATOR;
			$ruta_output =  DIRECTORY_SEPARATOR . "tmp_output";

			$re = \Storage::disk('archivos')->put( $ruta_pdfs . $nombre_original, \File::get($archivo));
			$ruta = storage_path('archivos') . $ruta_pdfs . $nombre_original;

			// Guardo la Carga del Archivo
			$carga_archivo = new \App\CargaArchivo;
            $carga_archivo->nombre_archivo = $nombre_original;
			$carga_archivo->save();
			
			//----------------------------------------
			//Tratar el archivo para recibir los datos
			$job = ProcesarCargaMasiva::dispatch($ruta, $nombre_original, $ruta_output, $ruta_pdfs, $time, $carga_archivo)
				->onConnection('database')
				->onQueue('processingComprobantes');

			$response = array(
				'cod' => '200',
				'mensaje' => 'El archivo se ha subido correctamente y se estará procesando.',
				'redirect' => 'crear_gcp',
			);

		} catch (\Exception $e) {
			$response = array(
				'cod' => '400',
				'mensaje' => $e->getMessage(),
				'redirect' => 'crear_gcp',
			);
		}

		return view('planos/response')->with(['response' => $response]);
	}




	public function dividir_pdf ($ruta_pdfs, $ruta_output, $cedula) {
		$args = array(
			storage_path('archivos') . $ruta_pdfs,
			storage_path('archivos') . $ruta_output,
			"docs_uploads/x_cedula/" . $cedula,
			base_path() . DIRECTORY_SEPARATOR . "credentials.json"
		);

		echo '<pre>';
		print_r($args);
		echo '</pre>';
		
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			$py_version = "python";
		} else {
			$py_version = "/usr/bin/venv_ami/bin/python";
		}

		$comand = $py_version . " " . app_path() . DIRECTORY_SEPARATOR . "dividir_pdf_pages_gcp.py --pdfs " . $args[0] . " --output " . $args[1] . " --cedula " . $args[2] . " --gcpfolder docs_uploads --gcp_credentials " . $args[3] . " 2>&1";
		echo '<br>' . $comand;

		$response = shell_exec($comand);
		echo '<br>' . $response;

		\Storage::disk('archivos')->deleteDirectory($ruta_pdfs); // Eliminar la carpeta en local
	}

}