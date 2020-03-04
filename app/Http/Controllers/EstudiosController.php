<?php

namespace App\Http\Controllers;

use App\Clientes as Clientes;
use App\Asesores as Asesores;
use App\Estudiostr as Estudios;
use App\Registrosfinancieros as Registrosfinancieros;
use App\Parametros as Parametros;
use Illuminate\Http\Request;

class EstudiosController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('usuario');
    }

    /**
     * Display the studies list.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lista = Estudios::all();
        return view("estudios/index")->with([
            "lista" => $lista
            ]);
    }
    
    /**
     * Display the studies list.
     *
     * @return \Illuminate\Http\Response
     */
    public function paso1()
    {
        return view("estudios/paso1");
    }

    /**
     * Mostrar el estudio trayendo la información por medio de la cédula
     *
     * @return \Illuminate\Http\Response
     */
    public function iniciar(Request $request)
    {
        // Parámetros
        $smlv = Parametros::where('llave', 'SMLV')->first();

        $cliente = Clientes::where("documento", "=", $request->documento)->first();
        $asesores = Asesores::all();
        $registro = $cliente->registrosfinancieros->last();
        $sueldobasico = sueldobasico(ingresos_por_registro($registro->id));

        $sueldobasico = 0;
        $adicional = 0;
        if ($cliente->cargo) {
            if (strpos($cliente->cargo, 'Rector') !== false || strpos($cliente->cargo, 'Coordinador') !== false) {
                $adicional = (int)sueldoadicional(ingresos_por_registro($registro->id));
            }
            $sueldobasico = (int)sueldobasico(ingresos_por_registro($registro->id));
        } else {
            $sueldobasico = (int)sueldobasico(ingresos_por_registro($registro->id));
        }

        $aportes = 0;
        $vinculacion = '';		
		if($registro->pagaduria->de_pensiones)
		{
            $vinculacion = 'PENS';
            $aportes = Parametros::where('llave', 'APORTES_PENSIONADOS')->first();
		}	
		else
		{
			$aportes = Parametros::where('llave', 'APORTES_ACTIVOS')->first();
		}
        $aportes = $aportes->valor * ($sueldobasico + $adicional) ;
        
        $totaldescuentos = totalizar_concepto(descuentos_por_registro($registro->id));

        $cupos = calcularCapacidad(
            $vinculacion,
            $sueldobasico,
            $aportes,
            $adicional,
            $totaldescuentos,
            $smlv->valor
        );

        $sueldocompleto = $sueldobasico+$adicional;

        return view("estudios/iniciarestudio")->with([
            "cliente" => $cliente,
            "asesores" => $asesores,
            "ultimoregistro" => $registro,
            "sueldocompleto" => $sueldocompleto,
            "aportes" => $aportes,
            "totaldescuentos" => $totaldescuentos,
            "cupos" => $cupos
        ]);
    }

    /**
     * Guardar el Estudio para poderlo modificar más adelante
     *
     * @return \Illuminate\Http\Response
     */
    public function guardar(Request $request)
    {
        $newestudio = new Estudios;
        $newestudio->clientes_id = $request->cliente_id;
        $newestudio->user_id = \Auth::user()->id;
        $newestudio->registros_id = $request->registro_id;

        if ($request->asesor_id == 'nuevo') {
            $newasesor = new Asesores;
            $newasesor->nombres = $request->nuevo_asesor;
            $newasesor->save();
            $newestudio->asesores_id = $newasesor->id;
        } else {
            $newestudio->asesores_id = $request->asesor_id;
        }

        $newestudio->fecha = date("Y-m-d");
        $newestudio->periodo_estudio = date("Ym");

        $newestudio->save();

        return redirect('estudios');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editar($id)
    {
        // Parámetros
        $smlv = Parametros::where('llave', 'SMLV')->first();

        $estudio = Estudios::find($id);
        $registro = Registrosfinancieros::find($estudio->registros_id);
        $asesor = Asesores::find($estudio->asesores_id);
        $asesores = Asesores::all();
        $cliente = Clientes::find($estudio->clientes_id);

        $sueldobasico = 0;
        $adicional = 0;
        if ($cliente->cargo) {
            if (strpos($cliente->cargo, 'Rector') !== false || strpos($cliente->cargo, 'Coordinador') !== false) {
                $adicional = (int)sueldoadicional(ingresos_por_registro($registro->id));
            }
            $sueldobasico = (int)sueldobasico(ingresos_por_registro($registro->id));
        } else {
            $sueldobasico = (int)sueldobasico(ingresos_por_registro($registro->id));
        }

        $aportes = 0;
        $vinculacion = '';		
		if($estudio->registro->pagaduria->de_pensiones)
		{
            $vinculacion = 'PENS';
            $aportes = Parametros::where('llave', 'APORTES_PENSIONADOS')->first();
		}	
		else
		{
			$aportes = Parametros::where('llave', 'APORTES_ACTIVOS')->first();
		}
		$aportes = $aportes->valor * ($sueldobasico + $adicional) ;

        $totaldescuentos = totalizar_concepto(descuentos_por_registro($registro->id));

        $cupos = calcularCapacidad(
            $vinculacion,
            $sueldobasico,
            $aportes,
            $adicional,
            $totaldescuentos,
            $smlv->valor
        );

        $sueldocompleto = $sueldobasico+$adicional;

        return view("estudios/editar")->with([
            "estudio" => $estudio,
            "registro" => $registro,
            "asesor" => $asesor,
            "asesores" => $asesores,
            "cliente" => $cliente,
            "sueldocompleto" => $sueldocompleto,
            "aportes" => $aportes,
            "totaldescuentos" => $totaldescuentos,
            "cupos" => $cupos
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function actualizar(Request $request)
    {
        $estudio = Estudios::find($request->estudio_id);

        if ($request->asesor_id == 'nuevo') {
            $newasesor = new Asesores;
            $newasesor->nombres = $request->nuevo_asesor;
            $newasesor->save();
            $estudio->asesores_id = $newasesor->id;
        } else {
            $estudio->asesores_id = $request->asesor_id;
        }
        $estudio->save();
		
		return redirect('estudios');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function eliminar($id)
    {
        Estudios::find($id)->delete();		
		return redirect('estudios');
    }
}
