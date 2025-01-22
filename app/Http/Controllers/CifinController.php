<?php

namespace App\Http\Controllers;

use App\Cifin;
use App\dataCotizer;
use Auth;
use Carbon\Carbon;
use DOMDocument;
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
            $lista = Cifin::all()
                ->orderBy('id', 'DESC')
                ->paginate(20);
        } else {
            $lista = Cifin::where('usuarioid', Auth::user()->id)
                ->orderBy('id', 'DESC')
                ->paginate(15);
        }

        $links = $lista->links();

        return view('cifin/index')->with(['links' => $links, 'lista' => $lista]);
    }

    public function consultarAdmin(Request $request)
    {
        $cedula = $request->cedula;
        $apellido = $request->apellido;

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

        $xml_name = $cedula . '_' . Carbon::parse($fecha)->format('d-m-Y') . '.xml';
        $doc = new DOMDocument();
        $doc->loadXML($response);
        $doc->save('cifinResponseAdmin_' . $xml_name);

        $array = XmlaPhp::createArray($response);
        $demo = $array['S:Envelope']['S:Body']['ns2:consultaXmlResponse']['return'];
        $resultado = XmlaPhp::createArray($demo);

        return view('cifin/consulta')->with(['resultado' => (object) $resultado]);
    }

    //CONSULTAR CIFIN se llama en el registro de crédito
    //PARA no borrar los comments hice otro metodo y este lo deje de base. Gabriel
    public function consultar(Request $request)
    {
        $cotizerId = $request->query('cotizerId');
        $decevalProcess = $request->query('decevalProcess');

        $cotizer = DataCotizer::find($cotizerId);

        $cedula = $cotizer->idNumber;
        $apellido = strtoupper($cotizer->firstLastname);
        $typeDocument = $cotizer->idType == 'CC' ? '1' : '2';

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


        //****************LEER**************** */


        //La ultima semana de Febrero, Carlos pidió que ya no se consultara cifin automaticamente
        //por lo cual, se arma la data, no se consulta cifin, y vamos a DECEVAL
        //al día 5 de marzo, deceval aun no ha respondido sobre la falla

        ////////////
        /* $doc = new DOMDocument();
        $doc->loadXML($xml_post_string);
        $doc->save('cifinRequest_' . $xml_name);

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

        $xml_name = $cedula . '_' . Carbon::parse($fecha)->format('d-m-Y') . '.xml';
        $doc = new DOMDocument();
        $doc->loadXML($response);
        $doc->save('cifinResponse_' . $xml_name);

        $array = XmlaPhp::createArray($response);
        $demo = $array['S:Envelope']['S:Body']['ns2:consultaXmlResponse']['return'];
        $resultado = XmlaPhp::createArray($demo);

        $score = $resultado['CIFIN']['Tercero']['Score']['Puntaje'];*/

        $data = [
            'nitEmisor' => '9004326290',
            'idClaseDefinicionDocumento' => env('DECEVEL_ID_CLASE_DOCUMENTO'),
            'fechaGrabacionPagare' => date('Y-m-d'),
            'numPagareEntidad' => date('Y-m-d-h:i') . '_PAG',
            'fechaDesembolso' => date('Y-m-d'),
            'otorganteTipoId' => $typeDocument,
            'otorganteNumId' => $cotizer->idNumber,
            'otorganteCuenta' => '103869',
            'expeditionDate' => $cotizer->idExpeditionDate,
            'decevalProcess' => $request->decevalProcess,
            'girador' => [
                'tipoDocumento' => $typeDocument,
                'numeroDocumento' => $cotizer->idNumber,
                'correoElectronico' => $cotizer->email,
                'direccion' => 'CALLE 1 # 2 - 3',
                'telefono' => $cotizer->phoneNumber,
                'pais' => 'CO',
                'departamento' => '11',
                'ciudad' => '11001',
                'nombres' => $cotizer->firstName . ' ' . $cotizer->middleName,
                'primerApellido' => $cotizer->firstLastname,
                'segundoApellido' => $cotizer->secondLastname,
            ],
        ];

        \Log::info('Data para Deceval Consultar:', $data);

        if ($decevalProcess) {
            return redirect()->route('deceval.consultar', $data);
        }

        return;
    }

    public function consulta()
    {
        return view('cifin/consulta');
    }
}
