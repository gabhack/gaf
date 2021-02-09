<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Clientes as Clientes;
use App\Helper;
use App\Http\Controllers\EstudiosController;

class ClientesController extends Controller
{
    /**
     * Create a new client on BD.
     *
     * @param  array  $request
     * @return \Illuminate\Http\Response
     */
    public function crear(Request $request)
    {
        $cargo_docente = '';
        $cargo_administrativo = '';
        $conceptos_financieros = array();
        $ingresos_totales = 0;
        $egresos_totales = 0;

        if ($request->docente_administrativo) {
            $cargo_docente = $request->cargo;
        } else {
            $cargo_administrativo = $request->cargo;
        }

        //DESCUENTOS NO APLICADOS
        if (isset($request->desc_na_valor_fijo[0])) {
            for ($i=1; $i <= sizeof($request->desc_na_valor_fijo[0]); $i++) {
                $conceptos_financieros['desc_no_aplicados'][] = array(
                    'periodo'         => $request->desc_na_periodo[0][$i],
                    'pagaduria'         => $request->desc_na_pagaduria[0][$i],
                    'cod_concepto'      => $request->desc_na_cod_concepto[0][$i],
                    'concepto'          => $request->desc_na_concepto[0][$i],
                    'inconsistencia'    => $request->desc_na_inconsistencia[0][$i],
                    'valor'             => $request->desc_na_valor_fijo[0][$i],
                    'valor_total'       => $request->desc_na_valor_total[0][$i],
                    'saldo'             => $request->desc_na_saldo[0][$i]
                );
            }
        }

        //CONCEPTOS
        if (isset($request->ingr_valor[0])) {
            for ($i=1; $i <= sizeof($request->ingr_valor[0]); $i++) {
                $conceptos_financieros['ingresos'][] = array(
                    'codConcepto'       => $request->ingr_cod_concepto[0][$i],
                    'concepto'          => $request->ingr_concepto[0][$i],
                    'valor'             => $request->ingr_valor[0][$i]
                );
                $ingresos_totales += $request->ingr_valor[0][$i];
            }
        }
        if (isset($request->desc_valor[0])) {
            for ($i=1; $i <= sizeof($request->desc_valor[0]); $i++) { 
                $conceptos_financieros['egresos'][] = array(
                    'codConcepto'       => $request->desc_cod_concepto[0][$i],
                    'concepto'          => $request->desc_concepto[0][$i],
                    'valor'             => $request->desc_valor[0][$i]
                );
                $egresos_totales += $request->desc_valor[0][$i];
            }
        }

        $personas_upload[] = array(
            'nombres' => ( isset($request->nombres) ? $request->nombres : '' ),
            'apellidos' => ( isset($request->apellidos) ? $request->apellidos : '' ),
            'documento' => ( isset($request->documento) ? $request->documento : '' ),
            'cargo_docente' => ( $cargo_docente !== '' ? $cargo_docente : '' ),
            'cargo_administrativo' => ( $cargo_administrativo !== '' ? $cargo_administrativo : '' ),
            'ciudad' => ( isset($request->ciudad) ? $request->ciudad : '' ),
            'centro_costos' => ( isset($request->centro_costos) ? $request->centro_costos : '' ),
            'grado' => ( isset($request->grado) ? utf8_encode($request->grado ."") : '' ),
            'tipo_contratacion' => ( isset($request->tipo_contratacion) ? $request->tipo_contratacion : '' ),
            'periodo' => ( isset($request->periodo[0]) ? $request->periodo[0] : '' ),
            'secretaria' => ( isset($request->pagaduria[0]) ? $request->pagaduria[0] : '' ),
            'estado_civil' => ( isset($request->estado_civil) ? $request->estado_civil : '' ),
            'sexo' => ( isset($request->genero) ? $request->genero : '' ),
            'fechanto' => ( isset($request->fecha_nto) ? $request->fecha_nto : '' ),
            'direccion' => ( isset($request->direccion) ? $request->direccion : '' ),
            'telefono' => ( isset($request->telefono) ? $request->telefono : '' ),
            'celular' => ( isset($request->celular) ? $request->celular : '' ),
            'correo' => ( isset($request->correo) ? $request->correo : '' ),
            'banco' => '',
            'cuenta' => '',
            'pension' => '',
            'caja_compensacion' => '',
            'cesantias' => '',
            'dias_laborados' => '',
            'nit' => '',
            'ingresos_base' => $request->ingresos,
            'conceptos_financieros' => array(
                'ingresos_totales' => $ingresos_totales,
                'egresos_totales' => $egresos_totales,
                'detallado_conceptos' => $conceptos_financieros
            )
        );

        $resp = upload_personas($personas_upload);

        if (strpos($resp, 'Error.') === false) {
            return view("estudios/paso1")->with([
                "documento" => $request->documento,
                "message" => array(
                    'tipo' => 'success',
                    'titulo' => 'Cliente creado con éxito',
                    'mensaje' => 'Puedes iniciar un estudio con este cliente.',
                )
            ]);
        } else {
            return view("estudios/paso1")->with([
                "documento" => $request->documento,
                "message" => array(
                    'tipo' => 'warning',
                    'titulo' => 'Error al crear el cliente',
                    'mensaje' => $resp,
                )
            ]);
        }
    }

    /**
     * Show view edit.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editar($id)
    {
        $cliente = Clientes::find($id);
        $registros = $cliente->registrosfinancieros->pluck('periodo')->unique();

        foreach ($cliente->registrosfinancieros as $key => $registro) {
            $ingresos_cliente[$registro->id] = ingresos_por_registro($registro->id);
            $egresos_cliente[$registro->id] = descuentos_por_registro($registro->id);
        }

        return view("clientes/editarcliente")->with([
            "cliente" => $cliente,
            "registros" => $registros
        ]);
    }

    /**
     * Edit a client on BD.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function actualizar(Request $request)
    {
        $cargo_docente = '';
        $cargo_administrativo = '';
        $conceptos_financieros = array();

        if ($request->docente_administrativo) {
            $cargo_docente = $request->cargo;
        } else {
            $cargo_administrativo = $request->cargo;
        }

        //DESCUENTOS NO APLICADOS
        if (isset($request->desc_na_valor_fijo[0])) {
            for ($i=1; $i <= sizeof($request->desc_na_valor_fijo[0]); $i++) {
                $conceptos_financieros['desc_no_aplicados'][] = array(
                    'periodo'           => $request->desc_na_periodo[0][$i],
                    'pagaduria'         => $request->desc_na_pagaduria[0][$i],
                    'cod_concepto'      => $request->desc_na_cod_concepto[0][$i],
                    'concepto'          => $request->desc_na_concepto[0][$i],
                    'inconsistencia'    => $request->desc_na_inconsistencia[0][$i],
                    'valor'             => $request->desc_na_valor_fijo[0][$i],
                    'valor_total'       => $request->desc_na_valor_total[0][$i],
                    'saldo'             => $request->desc_na_saldo[0][$i]
                );
            }
        }

        $personas_upload[] = array(
            'nombres' => ( isset($request->nombres) ? $request->nombres : '' ),
            'apellidos' => ( isset($request->apellidos) ? $request->apellidos : '' ),
            'documento' => ( isset($request->documento) ? $request->documento : '' ),
            'cargo_docente' => ( $cargo_docente !== '' ? $cargo_docente : '' ),
            'cargo_administrativo' => ( $cargo_administrativo !== '' ? $cargo_administrativo : '' ),
            'ciudad' => ( isset($request->ciudad) ? $request->ciudad : '' ),
            'centro_costos' => ( isset($request->centro_costos) ? $request->centro_costos : '' ),
            'grado' => ( isset($request->grado) ? utf8_encode($request->grado ."") : '' ),
            'tipo_contratacion' => ( isset($request->tipo_contratacion) ? $request->tipo_contratacion : '' ),
            'periodo' => ( isset($request->periodo[0]) ? $request->periodo[0] : '' ),
            'secretaria' => ( isset($request->pagaduria[0]) ? $request->pagaduria[0] : '' ),
            'estado_civil' => ( isset($request->estado_civil) ? $request->estado_civil : '' ),
            'sexo' => ( isset($request->genero) ? $request->genero : '' ),
            'fechanto' => ( isset($request->fecha_nto) ? $request->fecha_nto : '' ),
            'direccion' => ( isset($request->direccion) ? $request->direccion : '' ),
            'telefono' => ( isset($request->telefono) ? $request->telefono : '' ),
            'celular' => ( isset($request->celular) ? $request->celular : '' ),
            'correo' => ( isset($request->correo) ? $request->correo : '' ),
            'banco' => '',
            'cuenta' => '',
            'pension' => '',
            'caja_compensacion' => '',
            'cesantias' => '',
            'dias_laborados' => '',
            'nit' => '',
            'ingresos_base' => $request->ingresos,
            'conceptos_financieros' => array(
                'detallado_conceptos' => $conceptos_financieros
            )
        );

        $resp = upload_personas_without_concepts($personas_upload);

        if (strpos($resp, 'Error.') === false) {
            return redirect()->action('EstudiosController@iniciar', [
                'documento' => $request->documento,
                'message' => array(
                    'tipo' => 'success',
                    'titulo' => 'Cliente actualizado con éxito',
                    'mensaje' => 'Puedes iniciar un estudio con este cliente.',
                )
            ]);
        } else {
            return redirect()->action('EstudiosController@iniciar', [
                'documento' => $request->documento,
                'message' => array(
                    'tipo' => 'warning',
                    'titulo' => 'No se actualizó cliente.',
                    'mensaje' => $resp,
                )
            ]);
        }
    }
}
