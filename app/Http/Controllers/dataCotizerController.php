<?php

namespace App\Http\Controllers;

use App\dataCotizer;
use App\Estudiostr;
use App\Pagadurias;
use App\SolicitudCredito;
use Illuminate\Http\Request;
use App\PlanPago;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Auth;

class dataCotizerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Query
        $lista = dataCotizer::orderBy('id', 'desc');

        // Preparar la salida
        $listaOut = $lista->paginate(20)->appends(request()->except('page'));
        $links = $listaOut->links();
        $options = array(
            "lista" => $listaOut,
            "links" => $links
        );

        // Parámetros de búsqueda y filtrado para front
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
        // Registro inicial de la recepción de la solicitud
        Log::info('Inicio del proceso de almacenamiento de la solicitud.');

        // Extracción de datos de cotizador y crédito del request
        $input = $request['cotizerData'];
        $pagaduriaCode = $request['cotizerData']['pagaduria'];
        unset($input['pagaduria']); // Eliminación de pagaduria del array para evitar conflictos en la creación del modelo
        Log::info('Datos de cotizador y crédito extraídos.', ['cotizerData' => $input, 'pagaduriaCode' => $pagaduriaCode]);

        // Creación y guardado del cotizador
        $cotizador = new dataCotizer($input);
        $cotizador->save();
        Log::info('Cotizador creado y guardado con éxito.', ['cotizadorId' => $cotizador->id]);

        // Obtención del ID de la pagaduría seleccionada
        $pagaduria = Pagadurias::where('codigo', $pagaduriaCode)->first();
        if ($pagaduria === null) {
            // Manejar el caso en el que no se encuentra la pagaduría
            Log::error('No se encontró la pagaduría con el código proporcionado.', ['codigo' => $pagaduriaCode]);
            return response()->json(['error' => 'Pagaduría no encontrada.'], 404);
        }

        // Creación y guardado del estudio
        $estudio = new Estudiostr();
        $estudio->user_id = auth()->user()->id;
        $estudio->pagaduria_id = $pagaduria->id;
        $estudio->clientes_id = 200;
        $estudio->fecha = Carbon::now()->toDateString();
        $estudio->decision = 'PROS';
        $estudio->data_cotizer_id = $cotizador->id;
        $estudio->save();
        Log::info('Estudio creado y guardado.', ['estudioId' => $estudio->id]);

        // Creación y guardado de la solicitud de crédito
        $credit = new SolicitudCredito($request['creditInfo']);
        $credit->estudio_id = $estudio->id;
        $credit->save();
        Log::info('Solicitud de crédito creada y guardada.', ['solicitudCreditoId' => $credit->id]);

        // Cálculo de la cuota mensual y creación del plan de pagos
        $tasaInteresMensual = $credit->tasa_interes / 100;
        $saldoCapital = $credit->valor_solicitado;
        $costoSeguro = $credit->seguro;
        $numCuotas = $credit->nro_cuotas;
        $cuotaMensual = ($saldoCapital * $tasaInteresMensual) / (1 - pow(1 + $tasaInteresMensual, -$numCuotas));
        $saldoActual = $saldoCapital;

        for ($i = 1; $i <= $numCuotas; $i++) {
            $interesMensual = $saldoActual * $tasaInteresMensual;
            $capitalMensual = $cuotaMensual - $interesMensual;
            $saldoActual -= $capitalMensual;

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
        Log::info('Plan de pagos calculado y guardado.', ['solicitudCreditoId' => $credit->id, 'numCuotas' => $numCuotas]);

        // Final del proceso de almacenamiento
        Log::info('Proceso de almacenamiento completado con éxito.');

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
