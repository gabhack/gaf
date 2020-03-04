<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TerecuperamosController extends Controller
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
        $lista = \App\Estudiostr::all();
        return view("terecuperamos/index")->with(["lista" => $lista]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		return view('terecuperamos/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $estudio = new \App\Estudiostr;
		$estudio->estudio = strtoupper($request->input('estudio'));
		$estudio->save();
		
		return redirect('terecuperamos');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $estudio = \App\Estudiostr::find($id);
        $tipo_embargos = \App\Tiposembargos::OrderBy('tipo')->get();
		
		$periodo = date('Ym', strtotime(date('Y-m'). " -1 month"));
		$dctos_aplicados = \App\Descuentosaplicados::where('clientes_id', $estudio->cliente->id)
							->where('pagadurias_id', $estudio->base->pagaduria->id)
							->count();
							
		$otros_ingresos = \App\Bases::where('clientes_id', $estudio->cliente->id)
							->where('pagadurias_id', '<>', $estudio->base->pagaduria->id)
							->first();
		
		$aliados = \App\Aliados::with('aliados_validos')
								->whereHas('aliados_validos', function($q) use ($estudio){
									$q->where('pagadurias_id', $estudio->base->pagaduria->id);
									$q->where('tiposembargos_id', $estudio->tiposembargos_id);
								})
								->OrderBy('aliado')
								->get();
		
		$carteras = \App\Carteras::where('estudios_id', $estudio->id)->get();
		
		$totalCarteras = \App\Carteras::where('estudios_id', $estudio->id )
							->where('comprado_por', 'CK')
							->sum('saldo_negociado');
		
		$sectores = \App\Sectores::OrderBy('sector')->get();
		$estados_cartera = \App\Estadoscartera::OrderBy('estado')->get();
		
		$tasa_ck = \App\Parametros::where('llave', 'TASA')->first();
		$iva = \App\Parametros::where('llave', 'IVA')->first();
		$smlv = \App\Parametros::where('llave', 'SMLV')->first();
		
		$compras = \App\Carteras::where('estudios_id', $estudio->id )
							->where('comprado_por', 'CK')
							->sum('cuota');
		
		$aportes = 0;		
		if($estudio->adicional->cargo->estado == 'ACTI')
		{
			$aportes = \App\Parametros::where('llave', 'APORTES_ACTIVOS')->first();
		}	
		else if($estudio->adicional->cargo->estado == 'PENS')
		{
			$aportes = \App\Parametros::where('llave', 'APORTES_PENSIONADOS')->first();
		}
		
		$asignacion = \App\Cargos::find($estudio->adicional->cargo->id);		
		$adicional = $estudio->capacidad->ingresos * $asignacion->asignacion_adicional;
		$aportes = $aportes->valor * ($estudio->capacidad->ingresos + $adicional) ;
		
		
		$cupos = calcularCapacidad(
					$estudio->adicional->cargo->estado, $estudio->capacidad->ingresos,
					$aportes, $adicional,
					$estudio->capacidad->descuentos, $smlv->valor
				);
		
		$cupoMaximo = $cupos['libreInversion'] + $compras;
		
		$valor_juridico = \App\Parametros::where('llave', 'COSTOS_JURIDICOS')->first();
		$costos = \App\Parametros::where('llave', 'COSTOS')->first();
		$anticipo = \App\Parametros::where('llave', 'ANTICIPO')->first();
		$gmf = \App\Parametros::where('llave', 'GMF')->first();
		$seguro = \App\Parametros::where('llave', 'SEGURO')->first();
		$tasaAf = \App\Parametros::where('llave', 'TASA_ALIADO')->first();
		
		
		$comprasAliado = \App\Carteras::where('estudios_id', $estudio->id )
							->where('comprado_por', 'ALIA')
							->sum('cuota');
		
		$saldoNegociado = \App\Carteras::where('estudios_id', $estudio->id )
							->where('comprado_por', 'ALIA')
							->sum('saldo_negociado');
		
		$maxCupo = $cupoMaximo + $comprasAliado;
		
		$observaciones = \App\Observaciones::where('estudios_id', $estudio->id)->orderBy('created_at', 'DESC')->get();
		
		return view("terecuperamos/editar")->with([
            "estudio" => $estudio,
            "tipo_embargos" => $tipo_embargos,
            "dctos_aplicados" => $dctos_aplicados,
            "otros_ingresos" => $otros_ingresos,
            "aliados" => $aliados,
            "sectores" => $sectores,
            "estados_cartera" => $estados_cartera,
            "carteras" => $carteras,
            "tasa_ck" => $tasa_ck,
            "totalCarteras" => $totalCarteras,
            "iva" => $iva,
            "cupoMaximo" => $cupoMaximo,
            'valor_juridico' => $valor_juridico,
            "costos" => $costos,
            "anticipo" => $anticipo,
            "gmf" => $gmf,
            "seguro" => $seguro,
            "maxCupo" => $maxCupo,
            "saldoNegociado" => $saldoNegociado,
            "tasaAf" => $tasaAf,
            "observaciones" => $observaciones,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $estudio = \App\Estudiostr::find($id);
		$estudio->decision = strtoupper($request->input('decisiontr'));
		$estudio->save();
		
		return redirect('terecuperamos');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        \App\Estudiostr::find($id)->delete();		
		return redirect('terecuperamos');
    }
	
	
    public function saveObservaciones(Request $request, $id)
    {
        $observacion = new \App\Observaciones;
		$observacion->estudios_id = $id;
		$observacion->users_id = \Auth::user()->id;
		$observacion->observacion = strtoupper($request->input('observacion'));
		$observacion->save();
		
		return redirect('terecuperamos');
    }
}