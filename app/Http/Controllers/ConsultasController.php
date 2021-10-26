<?php

namespace App\Http\Controllers;

use Auth;
use App\Consultas as Consultas;
use App\Clientes as Clientes;
use App\DatosHistoricos as DatosHistoricos;
use App\Parametros as Parametros;
use App\Estudiostr as Estudios;
use App\Registrosfinancieros as Registrosfinancieros;
use Illuminate\Support\Facades\DB as DB;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use Illuminate\Http\Request;

class ConsultasController extends BaseSoapController
{
    private $service;

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
     * Display the consult resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("consultas/index");
    }

    /**
     * Display the consult by document.
     *
     * @return \Illuminate\Http\Response
     */
    public function consultar(Request $request)
    {
        //Obtener consulta
        $cliente = Clientes::where("documento", "=", $request->documento)->first();
        if ( 
            (strtolower($request->file('autorizacion_file')->getClientOriginalExtension()) !== 'pdf') ||
            (strtolower($request->file('desprendible_file')->getClientOriginalExtension()) !== 'pdf')
        ) {
            return view("consultas/index")->with([
                "message" => array(
                    'tipo' => 'warning',
                    'titulo' => 'Documentos PDF en formato incorrecto.',
                    'mensaje' => '
                            Por favor intente nuevamente cargando los siguientes archivos en formato .pdf:'.
                            (strtolower($request->file('autorizacion_file')->getClientOriginalExtension()) !== 'pdf' ? "
                            - Autorización Política de datos" : "").
                            (strtolower($request->file('desprendible_file')->getClientOriginalExtension()) !== 'pdf' ? "
                            - Desprendible" : ""),
                    )
                ]);
        } else {
            if ($cliente) {
                //API's
                $url1 = 'https://cargaautorizaciones-llpftvqzdq-ue.a.run.app/webservice?';//Autorizaciones
                $url2 = 'https://cargaautorizaciones-llpftvqzdq-ue.a.run.app/webserviceDesprendibles?';//Desprendible
                $client = new Client();
                $options1 = [
                    'multipart' => [
                        [
                            'name'     => 'autorizacion',
                            'contents' => file_get_contents($request->file('autorizacion_file')->getRealPath()),
                            'filename' => $request->file('autorizacion_file')->getClientOriginalName(),
                        ],
                    ],
                ];
                $options2 = [
                    'multipart' => [
                        [
                            'name'     => 'desprendible',
                            'contents' => file_get_contents($request->file('desprendible_file')->getRealPath()),
                            'filename' => $request->file('desprendible_file')->getClientOriginalName(),
                        ],
                    ],
                ];
                $formdata = http_build_query([
                    "nombres" => $cliente->nombres,
                    "apellidos" => $cliente->apellidos,
                    "cedula" => $cliente->documento,
                    "key" => "GtsGAF2021*!",
                    "empresa" => Auth::user()->company->id
                ]);
                
                $response1 = json_decode($client->post($url1 . $formdata, $options1)->getBody());
                $response2 = json_decode($client->post($url2 . $formdata, $options2)->getBody());

                if (trim($response1[0]) !== 'Carga exitosa' || trim($response2[0]) !== 'Carga exitosa') {
                    return view("consultas/index")->with([
                        "message" => array(
                            'tipo' => 'warning',
                            'titulo' => 'Documentos PDF en formato incorrecto.',
                            'mensaje' => '
                                    Verifique los siguientes archivos:'.
                                    (trim($response1[0]) !== 'Carga exitosa' ? "- Archivo de autorización no detectado." : "").
                                    (trim($response2[0]) !== 'Carga exitosa' ? "- Archivo de desprendible de pago no detectado." : ""),
                        )
                    ]);
                }
                //FIn - API's

                $datoshistoricos = $cliente->datoshistoricos;
                //Guardar consulta en BD
                $nuevaconsulta = new Consultas;
                $nuevaconsulta->users_id = Auth::user()->id;
                $nuevaconsulta->documento = $request->input('documento');
                $nuevaconsulta->tipo_consulta = $request->input('tipo_consulta');
                $nuevaconsulta->registros_financieros_id = $request->registro_pagaduria;
                $nuevaconsulta->save();
                //Params
                $smlv = Parametros::where('llave', 'SMLV')->first();
                $registro = Registrosfinancieros::find($request->registro_pagaduria);
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
                $viabilidad = calcula_viabilidad_inicial($cliente);
                
                $cupos = calcularCapacidadAMI(
                    $vinculacion,
                    $sueldobasico,
                    $aportes,
                    $adicional,
                    $totaldescuentos,
                    $smlv->valor
                );
                
                $sueldocompleto = $sueldobasico+$adicional;
                
                return view("consultas/consulta")->with([
                    "cliente" => $cliente,
                    "consulta" => $nuevaconsulta,
                    "cupos" => $cupos,
                    "sueldocompleto" => $sueldocompleto,
                    "aportes" => $aportes,
                    "totaldescuentos" => $totaldescuentos,
                    "viabilidad" => $viabilidad,
                    "datoshistoricos" => $datoshistoricos,
                    "registro" => $registro
                ]);
            } else {
                return view("consultas/index")->with([
                    "message" => array(
                        'tipo' => 'warning',
                        'titulo' => 'Cliente no se encuentra en la base de datos.',
                        'mensaje' => '',
                        )
                    ]);
            }
        }
    }

    public function consultarprueba(Request $request){
        try {
            self::setWsdl('https://cifinpruebas.asobancaria.com/InformacionComercialWS/services/InformacionComercial?wsdl');
            $this->service = InstanceSoapClient::init();

            // $datos = $this->service->consultaXml(array(
            //     "consultaXmlRequest" => array(
            //         "codigoInformacion"        => "1401",
            //         "motivoConsulta"    => "24",
            //         "numeroIdentificacion" => $request->documento,
            //         "tipoIdentificacion"   => "1"
            //     )
            // ));

            // $datos = $this->service->__getTypes();
            $datos = $this->service->ParametrosConsultaDTO();

            // $datos = $this->service->consultaXml();

            // $response = $this->loadXmlStringAsArray($datos->consultaXml()->consultaXmlResponse());

            dd($datos);
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Show the consults list.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        //Parametros de entrada para busqueda y filtrado
        $search = '';
        $fechadesde = '';
        $fechahasta = '';
        if (isset($request->documento)) {
            $search = $request->documento;
        }
        if (isset($request->filtro['fecha_desde']) && $request->filtro['fecha_desde'] !== '') {
            $fechadesde = $request->filtro['fecha_desde'];
        } else {
            $fechadesde = '1800-01-01';
        }
        if (isset($request->filtro['fecha_hasta']) && $request->filtro['fecha_hasta'] !== '') {
            $fechahasta = $request->filtro['fecha_hasta'] . " 23:59:59";
        } else {
            $fechahasta = date("Y-m-d 23:59:59");
        }
        //Query
        if (IsUser() && !IsUserCreator()) {
            $lista = Consultas::orderBy('id', 'desc')
                ->where('users_id', Auth::user()->id)
                ->where(function($q) use($search) {
                    if ($search !== '') {
                        $q->where('documento', $search);
                    }
                })
                ->whereBetween('created_at', [$fechadesde, $fechahasta]);
        } elseif (IsUserCreator()) {
            $lista = Consultas::orderBy('id', 'desc')
                ->WhereHas('usuario', function($q) use($search) {
                    $q->where('id_padre', Auth::user()->id);
                    $q->orWhere('id', Auth::user()->id);
                })
                ->where(function($q) use($search) {
                    if ($search !== '') {
                        $q->where('documento', $search);
                    }
                })
                ->whereBetween('created_at', [$fechadesde, $fechahasta]);
        } elseif (IsCompany()) {
            $lista = Consultas::orderBy('id', 'desc')
                ->WhereHas('usuario', function($q) use($search) {
                    $q->where('id_company', Auth::user()->id);
                    $q->orWhere('id', Auth::user()->id);
                })
                ->where(function($q) use($search) {
                    if ($search !== '') {
                        $q->where('documento', $search);
                    }
                })
                ->whereBetween('created_at', [$fechadesde, $fechahasta]);
        }

        //Preparar la salida
        $listaOut = $lista->paginate(20)->appends(request()->except('page'));
        $links = $listaOut->links();
        $options = array(
            "lista" => $listaOut,
            "links" => $links
        );
        //Parametros de busqueda y filtrado para front 
        if (isset($request->documento) && $request->documento !== '') {
            $options['documento'] = $request->documento;
        }
        if (isset($request->filtro['fecha_desde']) && $request->filtro['fecha_desde'] !== '') {
            $options['filtro']['fecha_desde'] = $request->filtro['fecha_desde'];
        }
        if (isset($request->filtro['fecha_hasta']) && $request->filtro['fecha_hasta'] !== '') {
            $options['filtro']['fecha_hasta'] = $request->filtro['fecha_hasta'];
        }

        return view("consultas.lista")->with($options);
    }

    
}
