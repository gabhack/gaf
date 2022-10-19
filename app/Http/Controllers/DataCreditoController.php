<?php

namespace App\Http\Controllers;

use App\DataCredito;
use Auth;
use App\DataCotizer;
use Illuminate\Http\Request;

class DataCreditoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

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
            $lista = DataCredito::all()->orderBy('id', 'DESC')->paginate(20);
        } else {
            $lista = DataCredito::where('usuarioid', Auth::user()->id)->orderBy('id', 'DESC')->paginate(15);
        }

        $links = $lista->links();

        return view("datacredito/index")->with(["links" => $links, "lista" => $lista]);
    }

    public function consultar(Request $request)
    {
        $cotizer = DataCotizer::find($request->cotizerId);

        $cedula = '900432629';
        $apellido = 'CK COMERCIALIZADORA UN MUNDO DE OPORTUNIDADES SAS';
        //$typeDocument = $cotizer->idType == 'CC' ? '1' : '2';

        $soapUser = '900432629';  //  username
        $soapPassword = '39RBJ'; // password
        $url = 'http://34.171.55.31/datacreditointermediate/DataCreditoIntermediate/ws/intermediateService?wsdl';

        $xml_post_string = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ws="http://gafsolutions.co/intermediatedata">
        <soapenv:Header/>
            <soapenv:Body>
                <ws:consultaClienteRequest>
                    <numeroIdentificacion>' . $cedula . '</numeroIdentificacion>
                    <primerApellido>' . $apellido . '</primerApellido>
                    <tipoIdentificacion>2</tipoIdentificacion>
                </ws:consultaClienteRequest>
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
            "SOAPAction: http://34.171.55.31/datacreditointermediate/DataCreditoIntermediate/ws/intermediateService?wsdl",
            "Content-length: " . strlen($xml_post_string),
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $soapUser . ":" . $soapPassword); // username and password - declared at the top of the doc
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        curl_close($ch);

        $array = XmlaPhp::createArray($response);
        $demo = $array['SOAP-ENV:Envelope']['SOAP-ENV:Body']['ns2:getInformationClientResponse']['ns2:data'];
        $demo2 = str_replace("&lt;", "<", $demo);
        $resultado = XmlaPhp::createArray($demo2)['Informes']['Informe'];
        //dd($resultado);
        return view('datacredito/consulta', ['resultado' => $resultado]);
    }

    public function consulta()
    {
        return view('datacredito/consulta');
    }
}
