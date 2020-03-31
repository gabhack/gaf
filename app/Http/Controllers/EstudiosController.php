<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth as Auth;
use App\Clientes as Clientes;
use App\Asesores as Asesores;
use App\Estudiostr as Estudios;
use App\Registrosfinancieros as Registrosfinancieros;
use App\Parametros as Parametros;
use App\Centrales as Centrales;
use App\Condicionestr as Condicionestr;
use App\Aliados as Aliados;
use App\TiposCliente as TiposCliente;
use App\Sectores as Sectores;
use App\Estadoscartera as Estadoscartera;
use App\EntidadesCentrales as EntidadesCentrales;
use App\Condicionesaf as Condicionesaf;
use App\Carteras as Carteras;
use Illuminate\Http\Request;
use DateTime;

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
    }

    /**
     * Display the studies list.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->rol->id == 1 || Auth::user()->rol->id == 5 ) {
            $lista = Estudios::all();
        } else {
            $lista = Estudios::where('users_id', Auth::user()->rol->id);
        }
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
        $cliente = Clientes::where("documento", "=", $request->documento)->first();
        if ($cliente) {
            // Parámetros
            $smlv = Parametros::where('llave', 'SMLV')->first();
            $iva = Parametros::where('llave', 'IVA')->first()->valor;
            $tasack = Parametros::where('llave', 'TASA_CK')->first()->valor;
            $tiposcliente = TiposCliente::all();
            $extraprima = Parametros::where('llave', 'SEGURO_EXTRAPRIMA')->first()->valor;
            $p_x_millon = Parametros::where('llave', 'SEGURO_P_X_MILLON')->first()->valor;
            $aliadosCompleto = Aliados::all();

            $asesores = Asesores::all();
            $registro = $cliente->registrosfinancieros->last();
            $sueldobasico = sueldobasico(ingresos_por_registro($registro->id));

            //Parametros para datagrid
            $aliados = Aliados::all()->pluck('aliado')->toArray();
            $estadoscartera = Estadoscartera::all()->pluck('estado')->toArray();
            $sectores = Sectores::all()->pluck('sector')->toArray();

            $sueldobasico = $cliente->ingresos;
            $adicional = 0;
            if ($cliente->cargo) {
                if (strpos($cliente->cargo, 'Rector') !== false) {
                    $adicional = ($cliente->ingresos*.3);
                } elseif (strpos($cliente->cargo, 'Coordinador') !== false) {
                    $adicional = ($cliente->ingresos*.2);
                }
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
                "cupos" => $cupos,
                "iva" => $iva,
                "tasack" => $tasack,
                "tiposcliente" => $tiposcliente,
                "extraprima" => $extraprima,
                "p_x_millon" => $p_x_millon,
                "sectores" => $sectores,
                "estadoscartera" => $estadoscartera,
                "aliados" => $aliados,
                "aliadosCompleto" => $aliadosCompleto
            ]);
        } else {
            return view("estudios/paso1")->with([
                "message" => array(
                    'tipo' => 'error',
                    'titulo' => 'Error',
                    'mensaje' => 'No se encontraron clientes con el documento suministrado',
                )
            ]);
        }
    }

    /**
     * Guardar el Estudio para poderlo modificar más adelante
     *
     * @return \Illuminate\Http\Response
     */
    public function guardar(Request $request)
    {
        $tieneAF1 = false;
        $tieneAF2 = false;
        //Parametros
        $tasack = Parametros::where('llave', 'TASA_CK')->first()->valor;

        //Nuevo estudio
        $newestudio = new Estudios;
        $newestudio->clientes_id = $request->cliente_id;
        $newestudio->user_id = \Auth::user()->id;
        $newestudio->registros_id = $request->registro_id;
        $newestudio->decision = strtoupper($request->desiciones);
        if ($request->observaciones !== '') {
            $newestudio->observaciones = $request->observaciones;
        }
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

        //Nuevo registro de centrales
        $newcentrales = new Centrales;
        $newcentrales->estudios_id = $newestudio->id;
        $newcentrales->calificacion_data = $request->calif_wab;
        $newcentrales->puntaje_data = $request->puntaje_datacredito;
        if ($request->proc_en_contra !== '') {
            $newcentrales->proc_en_contra = $request->proc_en_contra;
        }
        $newcentrales->save();

        //Registro de Condiciones TR
        $condicionestr = new Condicionestr;
        $condicionestr->estudios_id = $newestudio->id;
        $condicionestr->costocertificados = $request->costo_certificados;
        $condicionestr->save();

        //Registro de Carteras
        $carteras_json = json_decode($request->json_carteras);
        if (sizeof($carteras_json) > 0) {
            foreach ($carteras_json as $key => $cartera) {
                $newcartera = new Carteras;
                $newcartera->sector_data = Sectores::where('sector', $cartera->Data)->first()->id;
                $newcartera->sector_cifin = Sectores::where('sector', $cartera->Cifin)->first()->id;
                $newcartera->estadoscarteras_id = Estadoscartera::where('estado', $cartera->Estado)->first()->id;
                $newcartera->nombre_obligacion = $cartera->Entidad;
                $newcartera->calif_wab = $cartera->CalificacionWAB;
                $newcartera->estudios_id = $newestudio->id;
                if ($cartera->CompraAF1 == 'SI') {
                    $newcartera->compraAF1_id = $request->AF1['id'];
                    $tieneAF1 = true;
                } elseif ($cartera->CompraAF2 == 'SI') {
                    $newcartera->compraAF2_id = $request->AF2['id'];
                    $tieneAF2 = true;
                }
                if ($cartera->SoloEfectivo) {
                    $newcartera->solo_efectivo = 1;
                }
                if ($cartera->EnDesprendible) {
                    $newcartera->enDesprendible = 1;
                }
                $newcartera->cuota = $cartera->Cuota;
                $newcartera->saldo = $cartera->SaldoCarteraCentrales;
                $newcartera->valor_ini = $cartera->VlrInicioNegociacion;
                $newcartera->dcto_logrado = $cartera->DescuentoLogrado;
                if (strpos($cartera->FechaVencimiento, '/') !== false) {
                    $fechaopt = explode('/', $cartera->FechaVencimiento);
                    $fecha = $fechaopt[2] . '-' . $fechaopt[0] . '-' . $fechaopt[1];
                } else {
                    $fecha = $cartera->FechaVencimiento;
                }
                $newcartera->fecha_vence = $fecha;
                $newcartera->save();
            }
        }

        //Registro condiciones AF1 y AF2
        if ($tieneAF1) {
            $newcondicionAF1 = new Condicionesaf;
            $newcondicionAF1->estudios_id = $newestudio->id;
            $newcondicionAF1->aliados_id = $request->AF1['id'];
            $newcondicionAF1->plazo = $request->AF1['plazo'];
            $newcondicionAF1->tasa = $request->AF1['tasa'];
            $newcondicionAF1->costo = $request->AF1['costos'];
            $newcondicionAF1->save();
        }
        if ($tieneAF2) {
            $newcondicionAF2 = new Condicionesaf;
            $newcondicionAF2->estudios_id = $newestudio->id;
            $newcondicionAF2->aliados_id = $request->AF2['id'];
            $newcondicionAF2->plazo = $request->AF2['plazo'];
            $newcondicionAF2->factor = $request->AF2['factor_x_millon'];
            $newcondicionAF2->cuota = $request->AF2['cuota'];
            $newcondicionAF2->save();
        }

        $lista = Estudios::all();
        return view("estudios/index")->with([
            "lista" => $lista,
            "message" => array(
                'tipo' => 'success',
                'titulo' => 'Éxito',
                'mensaje' => 'El estudio se ha creado correctamente',
            )
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editar($id)
    {
        $estudio = Estudios::find($id);
        $registro = Registrosfinancieros::find($estudio->registros_id);
        $asesor = Asesores::find($estudio->asesores_id);
        $asesores = Asesores::all();
        $cliente = Clientes::find($estudio->clientes_id);
        $datacarteras = $estudio->carteras;
        $carteras = array();
        $aliadosusados = array();

        // Parámetros
        $smlv = Parametros::where('llave', 'SMLV')->first();
        $iva = Parametros::where('llave', 'IVA')->first()->valor;
        $extraprima = Parametros::where('llave', 'SEGURO_EXTRAPRIMA')->first()->valor;
        $p_x_millon = Parametros::where('llave', 'SEGURO_P_X_MILLON')->first()->valor;
        $tiposcliente = TiposCliente::all();
        $aliadosCompleto = Aliados::all();
        
        if (sizeof($datacarteras) > 0) {
            if (isset(array_values(array_unique(array_filter($datacarteras->pluck('compraAF1_id')->toArray(), "strlen")))[0])) {
                $aliado1 = array_values(array_unique(array_filter($datacarteras->pluck('compraAF1_id')->toArray(), "strlen")))[0];
                $aliadosusados[1] = array(
                    'id' => $aliado1,
                    'condiciones' => Condicionesaf::where('estudios_id', $estudio->id)->where('aliados_id', $aliado1)->first()
                );
            }
            if (isset(array_values(array_unique(array_filter($datacarteras->pluck('compraAF2_id')->toArray(), "strlen")))[0])) {
                $aliado2 = array_values(array_unique(array_filter($datacarteras->pluck('compraAF2_id')->toArray(), "strlen")))[0];
                $aliadosusados[2] = array(
                    'id' => $aliado2,
                    'condiciones' => Condicionesaf::where('estudios_id', $estudio->id)->where('aliados_id', $aliado2)->first()
                );
            }
        }

        //Parametros para datagrid
        $aliados = Aliados::all()->pluck('aliado')->toArray();
        $estadoscartera = Estadoscartera::all()->pluck('estado')->toArray();
        $sectores = Sectores::all()->pluck('sector')->toArray();

        foreach ($datacarteras as $key => $cartera) {
            
            $date = new DateTime($cartera->fecha_vence);
            $carteras[] = array(
                "ID" => $cartera->id,
                "EnDesprendible" => ($cartera->enDesprendible == 1 ? true : false),
                "Entidad" => $cartera->nombre_obligacion,
                "SoloEfectivo" => ($cartera->solo_efectivo == 1 ? true : false),
                "Data" => $cartera->sectordata->sector,
                "Cifin" => $cartera->sectorcifin->sector,
                "Estado" => $cartera->estado->estado,
                "CompraAF1" => ($cartera->compraAF1_id !== null ? "SI" : "NO"),
                "CompraAF2" => ($cartera->compraAF2_id !== null ? "SI" : "NO"),
                "CalificacionWAB" => $cartera->calif_wab,
                "Cuota" => $cartera->cuota,
                "SaldoCarteraCentrales" => $cartera->saldo,
                "VlrInicioNegociacion" => $cartera->valor_ini,
                "DescuentoLogrado" => $cartera->dcto_logrado,
                "SaldoCarteraNegociada" => 0,
                "PctjeNegociacion" => 0,
                "FechaVencimiento" => $date->format('m/d/Y')
            );
        }

        $sueldobasico = $cliente->ingresos;
        $adicional = 0;
        if ($cliente->cargo) {
            if (strpos($cliente->cargo, 'Rector') !== false) {
                $adicional = ($cliente->ingresos*.3);
            } elseif (strpos($cliente->cargo, 'Coordinador') !== false) {
                $adicional = ($cliente->ingresos*.2);
            }
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
            "asignacionadicional" => $adicional,
            "sueldocompleto" => $sueldocompleto,
            "aportes" => $aportes,
            "totaldescuentos" => $totaldescuentos,
            "cupos" => $cupos,
            "iva" => $iva,
            "tiposcliente" => $tiposcliente,
            "aliadosCompleto" => $aliadosCompleto,
            "aliados" => $aliados,
            "estadoscartera" => $estadoscartera,
            "sectores" => $sectores,
            "carteras" => $carteras,
            "aliadosusados" => $aliadosusados,
            "extraprima" => $extraprima,
            "p_x_millon" => $p_x_millon
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
        $tieneAF1 = false;
        $tieneAF2 = false;
        $estudio = Estudios::find($request->estudio_id);
        $estudio->decision = strtoupper($request->desiciones);
        $estudio->observaciones = $request->observaciones;
        if ($request->asesor_id == 'nuevo') {
            $newasesor = new Asesores;
            $newasesor->nombres = $request->nuevo_asesor;
            $newasesor->save();
            $estudio->asesores_id = $newasesor->id;
        } else {
            $estudio->asesores_id = $request->asesor_id;
        }
        $estudio->save();
        
        //Registro de centrales
        $central = $estudio->central;
        $central->calificacion_data = $request->calif_wab;
        $central->puntaje_data = $request->puntaje_datacredito;
        $central->proc_en_contra = $request->proc_en_contra;
        $central->save();

        //Registro de Carteras
        $carteras_json = json_decode($request->json_carteras);
        //Elimino carteras
        if (sizeof($estudio->carteras) > 0) {
            $resCart = Carteras::where('estudios_id', $estudio->id)->forceDelete();
        }
        //Creo de nuevo las carteras
        if (sizeof($carteras_json) > 0) {
            foreach ($carteras_json as $key => $cartera) {
                $newcartera = new Carteras;
                $newcartera->sector_data = Sectores::where('sector', $cartera->Data)->first()->id;
                $newcartera->sector_cifin = Sectores::where('sector', $cartera->Cifin)->first()->id;
                $newcartera->estadoscarteras_id = Estadoscartera::where('estado', $cartera->Estado)->first()->id;
                $newcartera->nombre_obligacion = $cartera->Entidad;
                $newcartera->calif_wab = $cartera->CalificacionWAB;
                $newcartera->estudios_id = $estudio->id;
                if ($cartera->CompraAF1 == 'SI') {
                    $newcartera->compraAF1_id = $request->AF1['id'];
                    $tieneAF1 = true;
                } elseif ($cartera->CompraAF2 == 'SI') {
                    $newcartera->compraAF2_id = $request->AF2['id'];
                    $tieneAF2 = true;
                }
                if ($cartera->SoloEfectivo) {
                    $newcartera->solo_efectivo = 1;
                }
                if ($cartera->EnDesprendible) {
                    $newcartera->enDesprendible = 1;
                }
                $newcartera->cuota = $cartera->Cuota;
                $newcartera->saldo = $cartera->SaldoCarteraCentrales;
                $newcartera->valor_ini = $cartera->VlrInicioNegociacion;
                $newcartera->dcto_logrado = $cartera->DescuentoLogrado;
                if (strpos($cartera->FechaVencimiento, '/') !== false) {
                    $fechaopt = explode('/', $cartera->FechaVencimiento);
                    $fecha = $fechaopt[2] . '-' . $fechaopt[0] . '-' . $fechaopt[1];
                } else {
                    $fecha = $cartera->FechaVencimiento;
                }
                $newcartera->fecha_vence = $fecha;
                $newcartera->save();
            }
        }

        //Registro condiciones TR
        $condicionestr = $estudio->condicion;
        $condicionestr->costocertificados = $request->costo_certificados;
        $condicionestr->save();

        //Registro condiciones AF1 y AF2
        $condicionesaf = $estudio->condicionesaf;
        $condicionAF1 = array();
        $condicionAF2 = array();
        foreach ($condicionesaf as $key => $condicion) {
            if ($condicion->aliado->tipo_aliado == 1) {
                $condicionAF1 = $condicion;
            } else {
                $condicionAF2 = $condicion;
            }
        }
        if ($tieneAF1) {
            if ($condicionAF1) {
                $condicionAF1->aliados_id = $request->AF1['id'];
                $condicionAF1->plazo = $request->AF1['plazo'];
                $condicionAF1->tasa = $request->AF1['tasa'];
                $condicionAF1->costo = $request->AF1['costos'];
                $condicionAF1->save();
            } else {
                $newcondicionAF1 = new Condicionesaf;
                $newcondicionAF1->aliados_id = $request->AF1['id'];
                $newcondicionAF1->plazo = $request->AF1['plazo'];
                $newcondicionAF1->tasa = $request->AF1['tasa'];
                $newcondicionAF1->costo = $request->AF1['costos'];
                $newcondicionAF1->save();
            }
        }
        if ($tieneAF2) {
            if ($condicionAF2) {
                $condicionAF2->aliados_id = $request->AF2['id'];
                $condicionAF2->plazo = $request->AF2['plazo'];
                $condicionAF2->factor = $request->AF2['factor_x_millon'];
                $condicionAF2->cuota = $request->AF2['cuota'];
                $condicionAF2->save();
            } else {
                $newcondicionAF2 = new Condicionesaf;
                $newcondicionAF2->aliados_id = $request->AF2['id'];
                $newcondicionAF2->plazo = $request->AF2['plazo'];
                $newcondicionAF2->factor = $request->AF2['factor_x_millon'];
                $newcondicionAF2->cuota = $request->AF2['cuota'];
                $newcondicionAF2->save();
            }
        }

        $lista = Estudios::all();
        return view("estudios/index")->with([
            "lista" => $lista,
            "message" => array(
                'tipo' => 'success',
                'titulo' => 'Éxito',
                'mensaje' => 'El estudio se ha actualizado correctamente',
            )
        ]);
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

    public function validarCarteras($carteras)
    {
        $carteras_json = json_decode($carteras);

        foreach ($carteras_json as $key => $cartera) {
            if (
                $cartera->Entidad == '' ||
                $cartera->Data == '' ||
                $cartera->Cifin == '' ||
                $cartera->Estado == '' ||
                $cartera->CompraAF1 == '' ||
                $cartera->CompraAF2 == '' ||
                $cartera->CalificacionWAB == '' ||
                $cartera->FechaVencimiento == ''
            ) {
                return false;
            } else {
                return true;
            }
        }
    }
}
