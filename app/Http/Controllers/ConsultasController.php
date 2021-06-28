<?php

namespace App\Http\Controllers;

use App\Clientes as Clientes;
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
        $cliente = Clientes::where("documento", "=", $request->documento)->first();

        return view("consultas/consulta")->with([
            "cliente" => $cliente
        ]);
    }

    public function consultarprueba(Request $request){
        try {
            self::setWsdl('https://cifinpruebas.asobancaria.com/InformacionComercialWS/services/InformacionComercial?wsdl');
            $this->service = InstanceSoapClient::init();

            $datos = $this->service->consultaXml(array(
                "consultaXmlRequest" => array(
                    "codigoInformacion"        => "1401",
                    "motivoConsulta"    => "24",
                    "numeroIdentificacion" => $request->documento,
                    "tipoIdentificacion"   => "1"
                )
            ));

            // $datos = $this->service->consultaXml();

            // $response = $this->loadXmlStringAsArray($datos->consultaXml()->consultaXmlResponse());

            dd($datos);
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }
    }
}
