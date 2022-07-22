<?php

namespace App\Http\Controllers;
use Orchestra\Parser\Xml\Facade as XmlParser;
use App\Cifin;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use DOMDocument;
class CifinController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $idOpenpay="mbj7d0ylmxkrlg4m1tcu";
    private $keyOpenpay="sk_382ccfcb3356474082d575c4facfefb6: ";
    private $identifiacador="DEVJR";

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:ADMIN_SISTEMA,ADMIN_HEGO,ADMIN_AMI,COMPANY,CREAUSUARIOS');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (IsSuperAdmin() || IsAMIAdmin() || IsHEGOAdmin()) {
            $lista = Cifin::all()->orderBy('id', 'DESC')->paginate(20);
        } else {
            $lista = Cifin::where('usuarioid', Auth::user()->id)->orderBy('id', 'DESC')->paginate(15);
        }
        $links = $lista->links();

        return view("cifin/index")->with(["links" => $links, "lista" => $lista]);
    }


    public function consultar(Request $request)
    {
        $cedula=$request->cedula;
        $apellido=$request->apellido;
        $soapUser = "405485";  //  username
        $soapPassword = "2AMIG*"; // password
        $url = "http://webservicecf.herokuapp.com/CifinWS?wsdl";
        $xml_post_string = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ws="http://ws/">
            <soapenv:Header/>
                <soapenv:Body>
                <ws:consultaXml>
                    <!--Optional:-->
                    <codigoInformacion>154</codigoInformacion>
                    <!--Optional:-->
                    <motivoConsulta>24</motivoConsulta>
                    <!--Optional:-->
                    <numeroIdentificacion>'.$cedula.'</numeroIdentificacion>
                    <!--Optional:-->
                    <primerApellido>'.$apellido.'</primerApellido>
                    <!--Optional:-->
                    <tipoIdentificacion>1</tipoIdentificacion>
                </ws:consultaXml>
            </soapenv:Body>
        </soapenv:Envelope>'; 

        $headers = array(
            "Content-type: text/xml;charset=\"utf-8\"",
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "Accept-Encoding: gzip,deflate",
            "Pragma: no-cache",
            "X-Atlassian-Token: no-check",
            "SOAPAction: http://webservicecf.herokuapp.com/CifinWS", 
            "Content-length: ".strlen($xml_post_string),
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $soapUser.":".$soapPassword); // username and password - declared at the top of the doc
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch); 
        curl_close($ch);
        $array = XmlaPhp::createArray($response);
        $demo= $array['S:Envelope']['S:Body']['ns2:consultaXmlResponse']['return'];
        $resultado = XmlaPhp::createArray($demo);

        return view("cifin/consulta")->with([
            "resultado" => (object)$resultado
        ]);
    }

    public function consulta()
    {
        return view('cifin/consulta')->with();
    }
}
