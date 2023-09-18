<?php

namespace App\Http\Controllers;

use App\dataCotizer;
use App\Estudiostr;
use Illuminate\Http\Request;
use App\PlanPago;

class dataCotizerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //Query
        $lista = dataCotizer::orderBy('id', 'desc');

        //Preparar la salida
        $listaOut = $lista->paginate(20)->appends(request()->except('page'));
        $links = $listaOut->links();
        $options = array(
            "lista" => $listaOut,
            "links" => $links
        );

        //Parametros de busqueda y filtrado para front
        if (isset($request->busq) && $request->busq !== '') {
            $options['busq'] = $request->busq;
        }

        return view("cotizer/index")->with($options);
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
        $input = $request['cotizerData'];
        $pagaduria = $request['cotizerData']['pagaduria'];
        unset($input['pagaduria']);

        // se guarda el cotizer
        $cotizador = new dataCotizer($input);
        $cotizador->save();

        // obtenemos el id de la pagaduria seleccionada
        $pagaduria = \App\Pagadurias::where('codigo', $pagaduria)->first();

        // guardamos en estudiostr
        $estudio = new Estudiostr();
        $estudio->user_id = auth()->user()->id;
        $estudio->pagaduria_id = $pagaduria->id;
        $estudio->fecha = \Carbon\Carbon::now()->toDateString();
        $estudio->decision = 'PROSP';
        $estudio->data_cotizer_id = $cotizador->id;
        $estudio->save();

        // se procede a guardar la solicitud de credito
        $credit = new \App\SolicitudCredito($request['creditInfo']);
        $credit->estudio_id = $estudio->id;
        $credit->save();

        //Obtén los datos del formulario

        $tasaInteresMensual = $credit->tasa_interes; // Supongamos que ya está en forma decimal
        $saldoCapital = $credit->credito_total;
        $costoSeguro = $credit->seguro;
        $numCuotas = $credit->cuota;

        // Calcula la cuota mensual
        $cuotaMensual = ($saldoCapital * $tasaInteresMensual) / (1 - pow(1 + $tasaInteresMensual, -$numCuotas));

        // Inicializa el saldo de capital
        $saldoActual = $saldoCapital;

        // Calcula y guarda los pagos mensuales en la tabla "plan_pagos"
        for ($i = 1; $i <= $numCuotas; $i++) {
            $interesMensual = $saldoActual * $tasaInteresMensual;
            $capitalMensual = $cuotaMensual - $interesMensual;
            $saldoActual -= $capitalMensual;

            // Guarda el pago en la tabla "plan_pagos"
            PlanPago::create([
                'fecha' => now()->addMonths($i)->format('Y-m-d'),
                'num_cuota' => $i,
                'cuota' => $cuotaMensual,
                'capital' => $capitalMensual,
                'interes' => $interesMensual,
                'seguro_vida' => $costoSeguro,
                'total_cuota' => $cuotaMensual + $costoSeguro,
                'saldo_capital' => $saldoActual,
                'estudio_id' => $estudio->id
            ]);
        }

        return $cotizador;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\dataCotizer  $dataCotizer
     * @return \Illuminate\Http\Response
     */
    public function show(dataCotizer $dataCotizer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\dataCotizer  $dataCotizer
     * @return \Illuminate\Http\Response
     */
    public function edit(dataCotizer $dataCotizer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\dataCotizer  $dataCotizer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, dataCotizer $dataCotizer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\dataCotizer  $dataCotizer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        dataCotizer::find($id)->delete();
        return redirect()->route('cotizer-data.index');
    }
}
