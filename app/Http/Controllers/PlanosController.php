<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

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
        $this->middleware('superadmin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$planos = \App\Planos::orderBy('created_at', 'desc')->get();
        return view('planos/index')->with(['planos' => $planos]);
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
		if($pagaduria->codigo == "SEM_POPAYAN")
		{
			switch ($plano->tipo) {
				case 'BAS':
					$response = \App\Http\Resources\Popayan::base($request);
					break;
				case 'APL':
					$response = array(
						'cod' => '300',
						'mensaje' => 'Esta pagaduría no permite el tipo de archivos que seleccionó',
					);
					break;
				case 'NAP':
					$response = array(
						'cod' => '300',
						'mensaje' => 'Esta pagaduría no permite el tipo de archivos que seleccionó',
					);
					break;
				case 'EMB':
					$response = \App\Http\Resources\Popayan::embargos($request);
					break;
				case 'COM':
					$response = \App\Http\Resources\Popayan::comprobante_pago($request);
					break;
				case 'MLQ':
					$response = \App\Http\Resources\Popayan::mensajes_liquidacion($request);
					break;
				case 'CLQ':
					$response = \App\Http\Resources\Popayan::conceptos_liquidacion($request);
					break;
			}
		}
		elseif ($pagaduria->codigo == "FOPEP")
		{
			switch ($plano->tipo) {
				case 'BAS':
					$response = \App\Http\Resources\Fopep::base($request);
					break;
				case 'APL':
					$response = \App\Http\Resources\Fopep::descuentos_aplicados($request);
					break;
				case 'NAP':
					$response = \App\Http\Resources\Fopep::descuentos_no_aplicados($request);
					break;
				case 'EMB':
					$response = array(
						'cod' => '300',
						'mensaje' => 'Esta pagaduría no permite el tipo de archivos que seleccionó',
					);
					break;
				case 'COM':
					$response = array(
						'cod' => '300',
						'mensaje' => 'Esta pagaduría no permite el tipo de archivos que seleccionó',
					);
					break;
			}
		}
		elseif ($pagaduria->codigo == "FIDUPREVISORA")
		{
			switch ($plano->tipo) {
				case 'BAS':
					$response = \App\Http\Resources\Fiduprevisora::base($request);
					break;
				case 'APL':
					$response = array(
						'cod' => '300',
						'mensaje' => 'Esta pagaduría no permite el tipo de archivos que seleccionó',
					);
					break;
				case 'NAP':
					$response = array(
						'cod' => '300',
						'mensaje' => 'Esta pagaduría no permite el tipo de archivos que seleccionó',
					);
					break;
				case 'EMB':
					$response = array(
						'cod' => '300',
						'mensaje' => 'Esta pagaduría no permite el tipo de archivos que seleccionó',
					);
					break;
				case 'COM':
					$response = array(
						'cod' => '300',
						'mensaje' => 'Esta pagaduría no permite el tipo de archivos que seleccionó',
					);
					break;
			}
		}
		elseif ($pagaduria->codigo == "COLPENSIONES")
		{
			switch ($plano->tipo) {
				case 'BAS':
					$response = \App\Http\Resources\Colpensiones::base($request);
					break;
				case 'APL':
					$response = array(
						'cod' => '300',
						'mensaje' => 'Esta pagaduría no permite el tipo de archivos que seleccionó',
					);
					break;
				case 'NAP':
					$response = array(
						'cod' => '300',
						'mensaje' => 'Esta pagaduría no permite el tipo de archivos que seleccionó',
					);
					break;
				case 'EMB':
					$response = array(
						'cod' => '300',
						'mensaje' => 'Esta pagaduría no permite el tipo de archivos que seleccionó',
					);
					break;
				case 'COM':
					$response = array(
						'cod' => '300',
						'mensaje' => 'Esta pagaduría no permite el tipo de archivos que seleccionó',
					);
					break;
			}
		}

        return view('planos/response')->with(['response' => $response]);
    }

}
