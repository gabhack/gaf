<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Registrosfinancieros as Registrosfinancieros;
use App\Ingresosaplicados as Ingresosaplicados;
use App\Descuentosaplicados as Descuentosaplicados;
use App\Helper;

class JsonClientesController extends Controller
{

    /**
     * Trae las pagadurias que se encuentran en registros financieros por periodo.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getPagaduriasXPeriodo(Request $request)
    {
        $pagadurias = Registrosfinancieros::where('clientes_id', $request->idcliente)
            ->where('periodo', $request->periodo)
            ->get()->pluck('pagadurias_id');
        return $pagadurias;
    }

    /**
     * Trae los registros financieros que se encuentran por periodo y pagaduria.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getRegistrosXPagaduriayPeriodo(Request $request)
    {
        $registro = Registrosfinancieros::where('clientes_id', $request->idcliente)
            ->where('periodo', $request->periodo)
            ->where('pagadurias_id', $request->pagaduria_select)
            ->first();
        $registro->ingresos   = ingresos_por_registro($registro->id);
        $registro->egresos    = descuentos_por_registro($registro->id);

        return $registro;
    }

    /**
     * Actualiza el registro que se modificó.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function actualizarRegistro(Request $request)
    {
        $conceptos_financieros = array();
        $ingresos_totales = 0;
        $egresos_totales = 0;
        $registro = Registrosfinancieros::where('clientes_id', $request->idcliente)
            ->where('periodo', $request->periodo)
            ->where('pagadurias_id', $request->pagaduria)
            ->first();
        $ingresos   = ingresos_por_registro($registro->id);
        $egresos    = descuentos_por_registro($registro->id);
        
        //INGRESOS
        foreach ($ingresos as $key => $ingreso) {
            $ingreso->delete();
        }
        if (isset($request->ingr_valor[0])) {
            for ($i=1; $i <= sizeof($request->ingr_valor[0]); $i++) {
                $nuevo_ingreso = new Ingresosaplicados;
                $nuevo_ingreso->registros_id = $registro->id;
                $nuevo_ingreso->cod_concepto = $request->ingr_cod_concepto[0][$i];
                $nuevo_ingreso->concepto = $request->ingr_concepto[0][$i];
                $nuevo_ingreso->valor = $request->ingr_valor[0][$i];
                $nuevo_ingreso->save();
                //
                $ingresos_totales += $request->ingr_valor[0][$i];
            }
        }
        //DESCUENTOS
        foreach ($egresos as $key => $egreso) {
            $egreso->delete();
        }
        if (isset($request->desc_valor[0])) {
            for ($i=1; $i <= sizeof($request->desc_valor[0]); $i++) {
                $nuevo_egreso = new Descuentosaplicados;
                $nuevo_egreso->registros_id = $registro->id;
                $nuevo_egreso->cod_concepto = $request->desc_cod_concepto[0][$i];
                $nuevo_egreso->concepto = $request->desc_concepto[0][$i];
                $nuevo_egreso->valor = $request->desc_valor[0][$i];
                $nuevo_egreso->save();
                //
                $egresos_totales += $request->desc_valor[0][$i];
            }
        }

        $registro->ingresos_totales = $ingresos_totales;
        $registro->egresos_totales = $egresos_totales;
        $registro->save();

        return 'Se actualizó el registro!';
    }
}
