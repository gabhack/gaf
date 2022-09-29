<?php

namespace App\Http\Controllers;

use App\Cifin;
use Auth;
use App\DataCotizer;
use Illuminate\Http\Request;

class CifinController extends Controller
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
            $lista = Cifin::all()->orderBy('id', 'DESC')->paginate(20);
        } else {
            $lista = Cifin::where('usuarioid', Auth::user()->id)->orderBy('id', 'DESC')->paginate(15);
        }

        $links = $lista->links();

        return view("cifin/index")->with(["links" => $links, "lista" => $lista]);
    }

    public function consultar(Request $request)
    {
        $cotizer = DataCotizer::find($request->cotizerId);

        $cedula = $cotizer->idNumber;
        $apellido = $cotizer->firstLastname;
        $typeDocument = $cotizer->idType == 'CC' ? '1' : '2';
        $phone = $cotizer->phoneNumber;

        $soapUser = env('CIFIN_USER');  //  username
        $soapPassword = env('CIFIN_PASSWORD'); // password
        $url = env('CIFIN_URL') . "?wsdl";

        $xml_post_string = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ws="http://ws/">
            <soapenv:Header/>
                <soapenv:Body>
                <ws:consultaXml>
                    <!--Optional:-->
                    <codigoInformacion>154</codigoInformacion>
                    <!--Optional:-->
                    <motivoConsulta>24</motivoConsulta>
                    <!--Optional:-->
                    <numeroIdentificacion>' . $cedula . '</numeroIdentificacion>
                    <!--Optional:-->
                    <primerApellido>' . $apellido . '</primerApellido>
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
            "SOAPAction: " . env('CIFIN_URL'),
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
        $demo = $array['S:Envelope']['S:Body']['ns2:consultaXmlResponse']['return'];
        $resultado = XmlaPhp::createArray($demo);

        $score = $resultado['CIFIN']['Tercero']['Score']['Puntaje'];

        $data = [
            'nitEmisor' => "9004326290",
            'idClaseDefinicionDocumento' => "4115",
            'fechaGrabacionPagare' => date('Y-m-d'),
            'numPagareEntidad' => date('Y-m-d-h:i') . '2_PAG',
            'fechaDesembolso' => date('Y-m-d'),
            'otorganteTipoId' => $typeDocument,
            'otorganteNumId' => "1144200155",
            'otorganteCuenta' => "103869",
            'expeditionDate' => "27/09/2011",
            'phone' => $phone,
        ];

        // $cifin = Cifin::create([
        //     'usuarioid' => Auth::user()->id,
        //     'cedula' => $cedula,
        //     'apellido' => $apellido,
        //     'score' => $score,
        //     'data' => json_encode($data),
        // ]);

        if ($score >= 100) {
            return redirect()->route('deceval.consultar', $data);
        } else {
            return redirect()->route('register.credit', ['status' => 'awaiting']);
        }
    }

    public function consulta()
    {
        return view('cifin/consulta');
    }
}
