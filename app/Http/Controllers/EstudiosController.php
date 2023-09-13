<?php

namespace App\Http\Controllers;
//
use App\Clientes as Clientes;
use App\Asesores as Asesores;
use App\Estudiostr as Estudios;
use App\dataCotizer;
use App\Registrosfinancieros as Registrosfinancieros;
use App\Parametros as Parametros;
use App\Centrales as Centrales;
use App\Condicionestr as Condicionestr;
use App\Aliados as Aliados;
use App\TiposCliente as TiposCliente;
use App\Sectores as Sectores;
use App\Estadoscartera as Estadoscartera;
use App\Condicionesaf as Condicionesaf;
use App\Carteras as Carteras;
use App\FactorXMillonKredit as FactorXMillonKredit;
use App\FactorXMillonGnb as FactorXMillonGnb;
use Carbon\Carbon;
use DOMDocument;
//
use Illuminate\Support\Facades\DB as DB;
use Illuminate\Support\Facades\Auth as Auth;
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
    public function index(Request $request)
    {
        $options = $this->getOptions($request);
        return view("estudios/index")->with($options);
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

        //Query
        $lista = Estudios::orderBy('id', 'desc')
            ->WhereHas('asesor', function ($q) use ($asesor) {
                if (!is_array($asesor)) {
                    $q->where('id', $asesor);
                }
            })
            ->where(function ($q) use ($decision) {
                if ($decision !== '') {
                    $q->where('decision', $decision);
                }
            })
            ->where(function ($q) use ($periodo) {
                if ($periodo !== '') {
                    $q->where('periodo_estudio', $periodo);
                }
            })
            ->whereBetween('fecha', [$fechadesde, $fechahasta])
            ->WhereHas('cliente', function ($q) use ($search) {
                $q->where('nombres', 'like', '%' . $search . '%');
                $q->orWhere('apellidos', 'like', '%' . $search . '%');
                $q->orWhere('documento', 'like', '%' . $search . '%');
                $q->orWhere(DB::raw('CONCAT_WS(" ", nombres, apellidos)'), 'like', '%' . $search . '%');
            });

        //Preparar la salida
        $listaOut = $lista->paginate(20)->appends(request()->except('page'));
        $links = $listaOut->links();

        $dataCotizer = dataCotizer::orderBy('id', 'desc');
        $listaCotizer = $dataCotizer->paginate(20)->appends(request()->except('page'));
        $links = $listaCotizer->links();

        $options = array(
            //"lista" => $listaOut,
            "lista" => $listaCotizer,
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
        try {
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
                $factores_x_millon_kredit = array();
                $factores_kredit = FactorXMillonKredit::all();

                foreach ($factores_kredit as $key => $factor) {
                    $factores_x_millon_kredit[$factor->llave] = $factor->valor;
                }

                $factores_x_millon_gnb = array();
                $factores_gnb = FactorXMillonGnb::all();

                foreach ($factores_gnb as $key => $factor) {
                    $factores_x_millon_gnb[$factor->pagaduria][$factor->plazo]['normal'] = $factor->normal;
                    $factores_x_millon_gnb[$factor->pagaduria][$factor->plazo]['saneamiento'] = $factor->saneamiento;
                }

                $viabilidad = calcula_viabilidad_inicial($cliente);

                $registrosf = $cliente->registrosfinancieros->pluck('periodo')->unique();

                $asesores = Asesores::all();
                $registro = $cliente->registrosfinancieros->last();

                //Parametros para datagrid
                $aliados = Aliados::all()->pluck('aliado')->toArray();
                $estadoscartera = Estadoscartera::all()->pluck('estado')->toArray();
                $sectores = Sectores::all()->pluck('sector')->toArray();

                $sueldobasico = $cliente->ingresos;
                $adicional = 0;

                if ($cliente->cargo) {
                    if (strpos($cliente->cargo, 'Rector') !== false) {
                        $adicional = ($cliente->ingresos * .3);
                    } elseif (strpos($cliente->cargo, 'Coordinador') !== false) {
                        $adicional = ($cliente->ingresos * .2);
                    }
                }

                $aportes = 0;
                $vinculacion = '';

                if ($registro->pagaduria->de_pensiones) {
                    $vinculacion = 'PENS';
                    $aportes = Parametros::where('llave', 'APORTES_PENSIONADOS')->first();
                } else {
                    $aportes = Parametros::where('llave', 'APORTES_ACTIVOS')->first();
                }

                $aportes = $aportes->valor * ($sueldobasico + $adicional);

                $totaldescuentos = totalizar_concepto(descuentos_por_registro($registro->id));

                $cupos = calcularCapacidad(
                    $vinculacion,
                    $sueldobasico,
                    $aportes,
                    $adicional,
                    $totaldescuentos,
                    $smlv->valor
                );

                $sueldocompleto = $sueldobasico + $adicional;

                $options = array(
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
                    "aliadosCompleto" => $aliadosCompleto,
                    "p_x_millon" => $p_x_millon,
                    "factores_x_millon_kredit" => $factores_x_millon_kredit,
                    "factores_x_millon_gnb" => $factores_x_millon_gnb,
                    "viabilidad" => $viabilidad,
                    "registrosf" => $registrosf
                );

                if (isset($request->message)) {
                    $options["message"] = $request->message;
                }

                return view("estudios/iniciarestudio")->with($options);
            } else {
                return view("clientes/nuevocliente")->with([
                    "documento" => $request->documento,
                    "message" => array(
                        'tipo' => 'warning',
                        'titulo' => 'Cliente no se encuentra en la base de datos',
                        'mensaje' => 'Por favor ingrese la información del cliente para iniciar un estudio.',
                    )
                ]);
            }
        } catch (\Exception $e) {
            return view("estudios/paso1")->with([
                "message" => array(
                    'tipo' => 'warning',
                    'titulo' => 'Información incompleta del cliente',
                    'mensaje' => 'El cliente que se está consultando no tiene la información suficiente para iniciar un estudio.
                    Se necesitan datos de ingresos y descuentos aplicados como mínimo para su estudio. Error: ' . $e->getMessage(),
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
        try {

            //Deformateo
            $request->costo_certificados = deformat_autonumeric($request->costo_certificados);
            $cuota_mensual = deformat_autonumeric($request->AF1['cuota_mensual']);
            $cuota = deformat_autonumeric($request->AF2['cuota']);

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
                $asesor = \App\Clientes::where("nombres", "=", $request->nuevo_asesor)->first();

                if ($asesor === null) {
                    $newasesor = new Asesores;
                    $newasesor->nombres = $request->nuevo_asesor;
                    $newasesor->save();
                    $newestudio->asesores_id = $newasesor->id;
                } else {
                    $newestudio->asesores_id = $asesor->id;
                }
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

            if ($request->puntaje_datacredito !== '') {
                $newcentrales->puntaje_data = $request->puntaje_datacredito;
            }

            if ($request->puntaje_sifin !== '') {

                $newcentrales->puntaje_sifin = $request->puntaje_sifin;
            }
            if ($request->proc_en_contra !== '') {
                $newcentrales->proc_en_contra = $request->proc_en_contra;
            }

            $newcentrales->save();

            //Registro de Condiciones TR
            $condicionestr = new Condicionestr;
            $condicionestr->estudios_id = $newestudio->id;
            $condicionestr->costocertificados = $request->costo_certificados;
            $condicionestr->costo_servicio = $request->costo_servicio_tr_ptg;
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
                    if ($cartera->FechaVencimiento !== '') {
                        if (strpos($cartera->FechaVencimiento, '/') !== false) {
                            $fechaopt = explode('/', $cartera->FechaVencimiento);
                            $fecha = $fechaopt[2] . '-' . $fechaopt[0] . '-' . $fechaopt[1];
                        } else {
                            $fecha = $cartera->FechaVencimiento;
                        }
                        $newcartera->fecha_vence = $fecha;
                    }
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
                $newcondicionAF1->cuota = $cuota_mensual;
                $newcondicionAF1->saldo_refinanciacion = $request->AF1['saldo_refinanciacion'];
                $newcondicionAF1->intereses_anticipados = $request->AF1['intereses_anticipados'];
                $newcondicionAF1->costo = $request->AF1['costos'];
                $newcondicionAF1->save();

                //AF2
                $newcondicionAF2 = new Condicionesaf;
                $newcondicionAF2->estudios_id = $newestudio->id;
                $newcondicionAF2->aliados_id = $request->AF2['id'];
                $newcondicionAF2->plazo = $request->AF2['plazo'];
                $newcondicionAF2->factor = $request->AF2['factor_x_millon'];
                $newcondicionAF2->cuota = $cuota;
                $newcondicionAF2->save();
            }

            if (Auth::user()->rol->id == 1 || Auth::user()->rol->id == 5) {
                $lista = Estudios::orderBy('id', 'desc')->paginate(20);
            } else {
                $lista = Estudios::orderBy('id', 'desc')->where('user_id', Auth::user()->id)->paginate(20);
            }
            $links = $lista->links();
            $options = array(
                "lista" => $lista,
                "links" => $links,
                "message" => array(
                    'tipo' => 'success',
                    'titulo' => 'Éxito',
                    'mensaje' => 'El estudio se ha creado correctamente',
                )
            );
            return view("estudios/index")->with($options);
        } catch (\Exception $e) {
            if (Auth::user()->rol->id == 1 || Auth::user()->rol->id == 5) {
                $lista = Estudios::orderBy('id', 'desc')->paginate(20);
            } else {
                $lista = Estudios::orderBy('id', 'desc')->where('user_id', Auth::user()->id)->paginate(20);
            }
            $links = $lista->links();
            $options = array(
                "lista" => $lista,
                "links" => $links,
                "message" => array(
                    'tipo' => 'error',
                    'titulo' => 'Error',
                    'mensaje' => 'No se pudo actualizar el estudio. Error: ' . $e->getMessage(),
                )
            );
            return view("estudios/index")->with($options);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editar($id)
    {
        try {
            $dataCotizer = dataCotizer::find($id);
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

            $xml_name = $cedula . '_' . Carbon::parse($fecha)->format('d-m-Y') . '.xml';

            $doc = new DOMDocument();
            $doc->loadXML($xml_post_string);
            $doc->save('cifinRequestAdmin_' . $xml_name);

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
            
            if($response != false){
                $array = XmlaPhp::createArray($response);
                $demo = $array['S:Envelope']['S:Body']['ns2:consultaXmlResponse']['return'];
                $resultado = XmlaPhp::createArray($demo);
                $sectorFinanciero = $resultado['CIFIN']['Tercero']['SectorFinancieroAlDia'];
                $sectorFinancieroReal = $resultado['CIFIN']['Tercero']['SectorRealAlDia'];
            }else{
                $sectorFinanciero = [];
                $sectorFinancieroReal = [];
            }

            return view("estudios/editar")->with([
                "dataCotizer" => $dataCotizer,
                "sectorFinanciero" => $sectorFinanciero,
                "sectorFinancieroReal" => $sectorFinancieroReal
            ]);

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
            $factores_x_millon_kredit = array();
            $factores_kredit = FactorXMillonKredit::all();
            foreach ($factores_kredit as $key => $factor) {
                $factores_x_millon_kredit[$factor->llave] = $factor->valor;
            }
            $factores_x_millon_gnb = array();
            $factores_gnb = FactorXMillonGnb::all();
            foreach ($factores_gnb as $key => $factor) {
                $factores_x_millon_gnb[$factor->pagaduria][$factor->plazo]['normal'] = $factor->normal;
                $factores_x_millon_gnb[$factor->pagaduria][$factor->plazo]['saneamiento'] = $factor->saneamiento;
            }
            $viabilidad = calcula_viabilidad_inicial($cliente);

            if (sizeof($datacarteras) > 0) {
                // Se comenta las siguientes líneas porque actualmente se modificó la funcionalidad del aliado financiero 2, antes se podía tener al aliado 1 sin aliado 2, ahora, si o si se tienen a los dos aliados o sólo el aliado 2
                /*if (isset(array_values(array_unique(array_filter($datacarteras->pluck('compraAF1_id')->toArray(), "strlen")))[0])) {
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
                }*/

                $aliadosenestudio = Condicionesaf::where('estudios_id', $estudio->id)->get();

                if (isset($aliadosenestudio[0])) {
                    $aliadosusados[1] = array(
                        'id' => $aliadosenestudio[0]->aliados_id,
                        'condiciones' => $aliadosenestudio[0]
                    );
                }

                if (isset($aliadosenestudio[1])) {
                    $aliadosusados[2] = array(
                        'id' => $aliadosenestudio[1]->aliados_id,
                        'condiciones' => $aliadosenestudio[1]
                    );
                }
            }

            //Parametros para datagrid
            $aliados = Aliados::all()->pluck('aliado')->toArray();
            $estadoscartera = Estadoscartera::all()->pluck('estado')->toArray();
            $sectores = Sectores::all()->pluck('sector')->toArray();
            $cont = 0;

            foreach ($datacarteras as $key => $cartera) {

                $date = new DateTime($cartera->fecha_vence);
                $cont++;
                $carteras[] = array(
                    "ID" => $cont,
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
                    "FechaVencimiento" => $cartera->fecha_vence
                );
            }

            $sueldobasico = $cliente->ingresos;
            $adicional = 0;
            if ($cliente->cargo) {
                if (strpos($cliente->cargo, 'Rector') !== false) {
                    $adicional = ($cliente->ingresos * .3);
                } elseif (strpos($cliente->cargo, 'Coordinador') !== false) {
                    $adicional = ($cliente->ingresos * .2);
                }
            }

            $aportes = 0;
            $vinculacion = '';
            if ($estudio->registro->pagaduria->de_pensiones) {
                $vinculacion = 'PENS';
                $aportes = Parametros::where('llave', 'APORTES_PENSIONADOS')->first();
            } else {
                $aportes = Parametros::where('llave', 'APORTES_ACTIVOS')->first();
            }
            $aportes = $aportes->valor * ($sueldobasico + $adicional);

            $totaldescuentos = totalizar_concepto(descuentos_por_registro($registro->id));

            $cupos = calcularCapacidad(
                $vinculacion,
                $sueldobasico,
                $aportes,
                $adicional,
                $totaldescuentos,
                $smlv->valor
            );

            $sueldocompleto = $sueldobasico + $adicional;

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
                "p_x_millon" => $p_x_millon,
                "factores_x_millon_kredit" => $factores_x_millon_kredit,
                "factores_x_millon_gnb" => $factores_x_millon_gnb,
                "viabilidad" => $viabilidad
            ]);
        } catch (\Exception $e) {
            if (Auth::user()->rol->id == 1 || Auth::user()->rol->id == 5) {
                $lista = Estudios::all();
            } else {
                $lista = Estudios::where('user_id', Auth::user()->id)->get();
            }
            return view("estudios/index")->with([
                "lista" => $lista,
                "message" => array(
                    'tipo' => 'error',
                    'titulo' => 'Error',
                    'mensaje' => 'No se pudo leer el estudio debido a un error interno. Detalles del error: ' . $e->getMessage(),
                )
            ]);
        }
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
        try {

            //Deformateo
            $request->costo_certificados = deformat_autonumeric($request->costo_certificados);
            $cuota_mensual = deformat_autonumeric($request->AF1['cuota_mensual']);
            $cuota = deformat_autonumeric($request->AF2['cuota']);

            $tieneAF1 = false;
            $tieneAF2 = false;
            $estudio = Estudios::find($request->estudio_id);
            $estudio->decision = strtoupper($request->desiciones);
            $estudio->observaciones = $request->observaciones;
            if ($request->asesor_id == 'nuevo') {
                $asesor = \App\Clientes::where("nombres", "=", $request->nuevo_asesor)->first();
                if ($asesor === null) {
                    $newasesor = new Asesores;
                    $newasesor->nombres = $request->nuevo_asesor;
                    $newasesor->save();
                    $newestudio->asesores_id = $newasesor->id;
                } else {
                    $newestudio->asesores_id = $asesor->id;
                }
            } else {
                $estudio->asesores_id = $request->asesor_id;
            }
            $estudio->save();

            //Registro de centrales
            $central = $estudio->central;
            $central->calificacion_data = $request->calif_wab;
            $central->puntaje_data = $request->puntaje_datacredito;
            $central->puntaje_sifin = $request->puntaje_sifin;
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
                    if ($cartera->FechaVencimiento !== '') {
                        if (strpos($cartera->FechaVencimiento, '/') !== false) {
                            $fechaopt = explode('/', $cartera->FechaVencimiento);
                            $fecha = $fechaopt[2] . '-' . $fechaopt[0] . '-' . $fechaopt[1];
                        } else {
                            $fecha = $cartera->FechaVencimiento;
                        }
                        $newcartera->fecha_vence = $fecha;
                    }
                    $newcartera->save();
                }
            }

            //Registro condiciones TR
            $condicionestr = $estudio->condicion;
            $condicionestr->costocertificados = $request->costo_certificados;
            $condicionestr->costo_servicio = $request->costo_servicio_tr_ptg;
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
                    $condicionAF1->cuota = $cuota;
                    $condicionAF1->saldo_refinanciacion = $request->AF1['saldo_refinanciacion'];
                    $condicionAF1->intereses_anticipados = $request->AF1['intereses_anticipados'];
                    $condicionAF1->costo = $request->AF1['costos'];
                    $condicionAF1->save();
                } else {
                    $newcondicionAF1 = new Condicionesaf;
                    $newcondicionAF1->estudios_id = $estudio->id;
                    $newcondicionAF1->aliados_id = $request->AF1['id'];
                    $newcondicionAF1->plazo = $request->AF1['plazo'];
                    $newcondicionAF1->tasa = $request->AF1['tasa'];
                    $newcondicionAF1->cuota = $cuota_mensual;
                    $newcondicionAF1->saldo_refinanciacion = $request->AF1['saldo_refinanciacion'];
                    $newcondicionAF1->intereses_anticipados = $request->AF1['intereses_anticipados'];
                    $newcondicionAF1->costo = $request->AF1['costos'];
                    $newcondicionAF1->save();
                }

                //AF2
                $cuota = deformat_autonumeric($request->AF2['cuota']);
                if ($condicionAF2) {
                    $condicionAF2->aliados_id = $request->AF2['id'];
                    $condicionAF2->plazo = $request->AF2['plazo'];
                    $condicionAF2->factor = $request->AF2['factor_x_millon'];
                    $condicionAF2->cuota = $cuota;
                    $condicionAF2->save();
                } else {
                    $newcondicionAF2 = new Condicionesaf;
                    $newcondicionAF2->estudios_id = $estudio->id;
                    $newcondicionAF2->aliados_id = $request->AF2['id'];
                    $newcondicionAF2->plazo = $request->AF2['plazo'];
                    $newcondicionAF2->factor = $request->AF2['factor_x_millon'];
                    $newcondicionAF2->cuota = $cuota;
                    $newcondicionAF2->save();
                }
            }

            if (Auth::user()->rol->id == 1 || Auth::user()->rol->id == 5) {
                $lista = Estudios::orderBy('id', 'desc')->paginate(20);
            } else {
                $lista = Estudios::orderBy('id', 'desc')->where('user_id', Auth::user()->id)->paginate(20);
            }
            $links = $lista->links();
            $options = array(
                "lista" => $lista,
                "links" => $links,
                "message" => array(
                    'tipo' => 'success',
                    'titulo' => 'Éxito',
                    'mensaje' => 'El estudio se ha actualizado correctamente',
                )
            );
            return view("estudios/index")->with($options);
        } catch (\Exception $e) {
            if (Auth::user()->rol->id == 1 || Auth::user()->rol->id == 5) {
                $lista = Estudios::orderBy('id', 'desc')->paginate(20);
            } else {
                $lista = Estudios::orderBy('id', 'desc')->where('user_id', Auth::user()->id)->paginate(20);
            }
            $links = $lista->links();
            $options = array(
                "lista" => $lista,
                "links" => $links,
                "message" => array(
                    'tipo' => 'error',
                    'titulo' => 'Error',
                    'mensaje' => 'No se pudo actualizar el estudio. Error: ' . $e->getMessage(),
                )
            );
            return view("estudios/index")->with($options);
        }
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

    public function tesoreria(Request $request)
    {
        $options = $this->getOptions($request);
        return view("estudios/tesoreria")->with($options);
    }

    public function cartera(Request $request)
    {
        $options = $this->getOptions($request);
        return view("estudios/cartera")->with($options);
    }

    public function ventaCartera(Request $request)
    {
        $options = $this->getOptions($request);
        return view("estudios/venta-cartera")->with($options);
    }
}
