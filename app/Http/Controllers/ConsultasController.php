<?php

namespace App\Http\Controllers;

use Auth;
use App\Consultas as Consultas;
use App\Clientes as Clientes;
use App\Parametros as Parametros;
use App\Estudiostr as Estudios;
use Illuminate\Support\Facades\DB as DB;
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

        if ($cliente) {
            //Guardar consulta en BD
            $nuevaconsulta = new Consultas;
            $nuevaconsulta->users_id = Auth::user()->id;
            $nuevaconsulta->documento = $request->input('documento');
            $nuevaconsulta->tipo_consulta = $request->input('tipo_consulta');
            $nuevaconsulta->save();
            //Params
            $smlv = Parametros::where('llave', 'SMLV')->first();
            $registro = $cliente->registrosfinancieros->last();
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

            return view("consultas/consulta")->with([
                "cliente" => $cliente,
                "consulta" => $nuevaconsulta,
                "cupos" => $cupos,
                "sueldocompleto" => $sueldocompleto,
                "aportes" => $aportes,
                "totaldescuentos" => $totaldescuentos
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
        $nuevaconsulta->users_id = Auth::user()->id;
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

    /**
     * Download PDF.
     *
     * @return \Illuminate\Http\Response
     */
    public function downloadPDF(Request $request)
    {
        $consulta = Consultas::find($request->input(''));

        $data = array(
            "cliente" => $cliente,
            "consulta" => $nuevaconsulta,
            "cupos" => $cupos,
            "sueldocompleto" => $sueldocompleto,
            "aportes" => $aportes,
            "totaldescuentos" => $totaldescuentos
        );

        return PDF::loadView('vista-pdf', $data)
            ->stream('archivo.pdf');

            return view("consultas/consulta")->with([
                "cliente" => $cliente,
                "consulta" => $nuevaconsulta,
                "cupos" => $cupos,
                "sueldocompleto" => $sueldocompleto,
                "aportes" => $aportes,
                "totaldescuentos" => $totaldescuentos
            ]);
    }
}
