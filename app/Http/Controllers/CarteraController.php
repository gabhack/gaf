<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CarteraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subquery = DB::table('pagos_detalle')
            ->select('estudio_id', DB::raw('SUM(valor) as total_recaudado'))
            ->groupBy('estudio_id');

        $carteras = DB::table('estudiostr as et')
            ->select(
                'et.id',
                //'et.cedula',
                'et.fecha_desembolso',
                DB::raw("DATE_FORMAT(et.fecha_cartera, '%Y-%m') as mes_prod"),
                //'et.nombre',
                'et.numero_libranza',
                'et.tasa_interes',
                'et.opcion_credito',
                'et.opcion_cuota_cli',
                'et.opcion_desembolso_cli',
                'et.opcion_cuota_ccc',
                'et.opcion_desembolso_ccc',
                'et.opcion_cuota_cmp',
                'et.opcion_desembolso_cmp',
                'et.opcion_cuota_cso',
                'et.opcion_desembolso_cso',
                'et.valor_credito',
                //'et.pagaduria',
                'et.plazo',
                DB::raw("CASE WHEN et.estado = 'CAN' THEN 'CANCELADO' ELSE 'VIGENTE' END as estado"),
                DB::raw("et.total_recaudado"),
                'et.fecha_prepago',
                'et.prepago_aprobado',
                DB::raw("SUM(CASE WHEN cu.pagada = '1' THEN 1 ELSE 0 END) as cuotas_pagadas"),
                DB::raw("SUM(CASE WHEN cu.fecha <= CURDATE() THEN 1 ELSE 0 END) as cuotas_causadas"),
                DB::raw("SUM(CASE WHEN cu.fecha < CURDATE() AND cu.pagada = '0' THEN 1 ELSE 0 END) as cuotas_mora")
            )
            ->leftJoin('cuotas as cu', 'et.id', '=', 'cu.estudio_id')
            ->leftJoinSub($subquery, 'pg', 'pg.estudio_id', '=', 'et.id')

            ->whereIn('et.estado', ['DES', 'CAN'])
            ->groupBy(
                'et.id',
                //'et.cedula',
                'et.fecha_desembolso',
                'mes_prod',
                //'et.nombre',
                'et.numero_libranza',
                'et.tasa_interes',
                'et.opcion_credito',
                'et.opcion_cuota_cli',
                'et.opcion_desembolso_cli',
                'et.opcion_cuota_ccc',
                'et.opcion_desembolso_ccc',
                'et.opcion_cuota_cmp',
                'et.opcion_desembolso_cmp',
                'et.opcion_cuota_cso',
                'et.opcion_desembolso_cso',
                'et.valor_credito',
                //'et.pagaduria',
                'et.plazo',
                'estado',
                'pg.total_recaudado',
                'et.fecha_prepago',
                'et.prepago_aprobado'
            )
            ->get();

        // por el momento API para mirar los datos en el navegador
        return response()->json($carteras);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
