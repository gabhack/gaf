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
    public function index(Request $request)
    {
        $options = $this->getOptions($request);

        return view("estudios/cartera")->with($options);
    }

    public function getOptions(Request $request)
    {
        //Parametros de entrada para busqueda y filtrado
        $search = '';
        $fechadesde = '';
        $fechahasta = '';
        $asesor = array();
        $periodo = '';
        $decision = '';

        if (isset($request->busq)) {
            $search = $request->busq;
        }

        if (isset($request->filtro['fecha_desde']) && $request->filtro['fecha_desde'] !== '') {
            $fechadesde = $request->filtro['fecha_desde'];
        } else {
            $fechadesde = '1800-01-01';
        }

        if (isset($request->filtro['fecha_hasta']) && $request->filtro['fecha_hasta'] !== '') {
            $fechahasta = $request->filtro['fecha_hasta'];
        } else {
            $fechahasta = date("Y-m-d");
        }

        if (isset($request->filtro['asesor']) && $request->filtro['asesor'] !== '') {
            $asesor = '';
            $asesor = $request->filtro['asesor'];
        }

        if (isset($request->filtro['decision']) && $request->filtro['decision'] !== '') {
            $decision = '';
            $decision = $request->filtro['decision'];
        }

        if (isset($request->filtro['periodo']) && $request->filtro['periodo'] !== '') {
            $periodo = $request->filtro['periodo'];
        }

        $subquery = DB::table('pagos_detalle')
            ->select('estudio_id', DB::raw('SUM(valor) as total_recaudado'))
            ->groupBy('estudio_id');

        $lista  = DB::table('estudiostr as et')
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

        // $lista = Estudiostr::where('estado', 'DESE')
        // ->orWhere(function ($query) use ($subestados_tesoreria) {
        //     $query->where('estado', 'EST')
        //     ->where('decision', 'VIABLE')
        //     ->whereIn('subestado_id', $subestados_tesoreria);
        // })
        //     ->WhereHas('asesor', function ($q) use ($asesor) {
        //         if (!is_array($asesor)) {
        //             $q->where('id', $asesor);
        //         }
        //     })
        //     ->where(function ($q) use ($decision) {
        //         if ($decision !== '') {
        //             $q->where('decision', $decision);
        //         }
        //     })
        //     ->where(function ($q) use ($periodo) {
        //         if ($periodo !== '') {
        //             $q->where('periodo_estudio', $periodo);
        //         }
        //     })
        //     ->whereBetween('fecha', [$fechadesde, $fechahasta])
        //     ->WhereHas('cliente', function ($q) use ($search) {
        //         $q->where('nombres', 'like', '%' . $search . '%');
        //         $q->orWhere('apellidos', 'like', '%' . $search . '%');
        //         $q->orWhere('documento', 'like', '%' . $search . '%');
        //         $q->orWhere(DB::raw('CONCAT_WS(" ", nombres, apellidos)'), 'like', '%' . $search . '%');
        //     });

        //Preparar la salida
        $listaOut = $lista->paginate(5)->appends(request()->except('page'));
        $links = $listaOut->links();
        $options = array(
            "lista" => $listaOut,
            "links" => $links
        );

        //Parametros de busqueda y filtrado para front
        if (isset($request->busq) && $request->busq !== '') {
            $options['busq'] = $request->busq;
        }

        if (isset($request->filtro['fecha_desde']) && $request->filtro['fecha_desde'] !== '') {
            $options['filtro']['fecha_desde'] = $request->filtro['fecha_desde'];
        }

        if (isset($request->filtro['fecha_hasta']) && $request->filtro['fecha_hasta'] !== '') {
            $options['filtro']['fecha_hasta'] = $request->filtro['fecha_hasta'];
        }

        if (isset($request->filtro['asesor']) && $request->filtro['asesor'] !== '') {
            $options['filtro']['asesor'] = $request->filtro['asesor'];
        }

        if (isset($request->filtro['decision']) && $request->filtro['decision'] !== '') {
            $options['filtro']['decision'] = $request->filtro['decision'];
        }

        if (isset($request->filtro['periodo']) && $request->filtro['periodo'] !== '') {
            $options['filtro']['periodo'] = $request->filtro['periodo'];
        }

        return $options;
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

    public function detalleCateraView($id, $tipoconsulta=null)
    {
        if($tipoconsulta==1)
        {
            // api 1
            $idpartes=explode('-', $id)
            $dataCotizer = dataCotizer::find($idpartes[0]);
            $cedula = $dataCotizer->idNumber;
            $apellido = $dataCotizer->firstLastname;

            $soapUser = env('CIFIN_USER'); //  username
            $soapPassword = env('CIFIN_PASSWORD'); // password
            $url = env('CIFIN_URL') . '?wsdl';

            $hoy = date('Y-m-d');
            $hora = date('H:i:s');
            $fecha = $hoy . 'T' . $hora;

            $xml_post_string =
                '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ws="http://ws/">
                <soapenv:Header/>
                    <soapenv:Body>
                    <ws:consultaXml>
                        <!--Optional:-->
                        <codigoInformacion>5702</codigoInformacion>
                        <!--Optional:-->
                        <motivoConsulta>24</motivoConsulta>
                        <!--Optional:-->
                        <numeroIdentificacion>' .
                $cedula .
                '</numeroIdentificacion>
                        <!--Optional:-->
                        <primerApellido>' .
                $apellido .
                '</primerApellido>
                        <!--Optional:-->
                        <tipoIdentificacion>1</tipoIdentificacion>
                    </ws:consultaXml>
                </soapenv:Body>
            </soapenv:Envelope>';

            $headers = [
                "Content-type: text/xml;charset=\"utf-8\"",
                'Accept: text/xml',
                'Cache-Control: no-cache',
                'Pragma: no-cache',
                'Accept-Encoding: gzip,deflate',
                'Pragma: no-cache',
                'X-Atlassian-Token: no-check',
                'SOAPAction: ' . env('CIFIN_URL'),
                'Content-length: ' . strlen($xml_post_string),
            ];


            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERPWD, $soapUser . ':' . $soapPassword); // username and password - declared at the top of the doc
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $response = curl_exec($ch);
            curl_close($ch);

            if ($response != false) {
                $array = XmlaPhp::createArray($response);
                $demo = $array['S:Envelope']['S:Body']['ns2:consultaXmlResponse']['return'];
                $resultado = XmlaPhp::createArray($demo);
                $sectorFinanciero = [];
                $sectorFinancieroReal = [];
                $cuentas_vigentes = $resultado['CIFIN']['Tercero']['CuentasVigentes'];
                // $sectorFinanciero = $resultado['CIFIN']['Tercero']['SectorFinancieroAlDia'];
                // $sectorFinancieroReal = $resultado['CIFIN']['Tercero']['SectorRealAlDia'];
            } else {
                $sectorFinanciero = [];
                $sectorFinancieroReal = [];
                $cuentas_vigentes = [];
            }

        }
        elseif ($tipoconsulta==2) {
             // api 2
            $idpartes=explode('-', $id)
            $dataCotizer = dataCotizer::find($idpartes[0]);
            $cedula = $dataCotizer->idNumber;
            $pagadurias = array(app(PagaduriasController::class)->perDoc($cedula));
            $pagaduria = '';
            if (isset($pagadurias[0])) {
                foreach ($pagadurias[0] as $key => $value) {
                    $pagaduria = str_replace("datames", "Embargos", $key);
                }
            }
            $embargos = array(app(EmbargosController::class)->buscar($cedula, $pagaduria));
            $arrayNewEmbargos = array();
            if (isset($embargos[0])) {

            } else {
                $embargos = [];
            }
        }
        else
        {
            // bd sistema
            $cartera=\App\Carteras::find($id);
        }

        return view("cartera/detalle",compact('cartera'));

    }

}
