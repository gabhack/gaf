<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Cifin;
use App\dataCotizer;
use App\Estudiostr;
use App\SolicitudCredito;
use App\Pagadurias;
use Auth;
use Carbon\Carbon;
use DOMDocument;
use Illuminate\Http\Request;


use PhpOffice\PhpWord\TemplateProcessor;
use DateTime;


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
        $estudio = Estudiostr::where('data_cotizer_id', $cotizer->id)->first();

        $solicitud = SolicitudCredito::where('estudio_id', $estudio->id)->first();
        $pagadurias = Pagadurias::where('id', $estudio->pagaduria_id)->first();

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
            'numPagareEntidad' => 'LIBRANZA_CK_' . $cedula . '_' . $cotizerId,
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

        Log::info('Data para Deceval Consultar:', $data);

        // Generar imagen de firma
        $signaturePath = $this->generateSignatureStamp($cotizer->phoneNumber, $cotizer->idType, $cotizer->idNumber);
        $signatureText = $this->generateSigningText($cotizer->department, date('Y-m-d'));

        // Preparar datos para el documento
        // Datos dinámicos a completar en la plantilla

        $fechaHoy = date("Ymd");
        $casillas = str_split($fechaHoy);

        $fechanacimiento = $cotizer->birthday;
        $fechaNac = new DateTime($fechanacimiento);
        $hoy = new DateTime();
        $edad = $hoy->diff($fechaNac)->y;
        $accountTypes = json_decode($cotizer->foreignAccountTypes, true);

        $data_doc = [
            'num_solicitud' => '',
            'pnombre_s' => $cotizer->firstName,
            'snombre_s' => $cotizer->middleName,
            'papellido_s' => $cotizer->firstLastname,
            'sapellido_s' => $cotizer->secondLastname,
            'num_cedula_s' => $cotizer->idNumber,
            'edad' => $edad,
            'sexo_s' => $cotizer->gender,
            'ent_pagaduria' => $pagadurias->pagaduria,
            'clave' => ' ' . (string)' ',
            'ciudad' => $cotizer->department,
            'comercial' => '',
            'coordinador' => '',
            'cc' => ($cotizer->idType === 'CC') ? 'X' : '',
            'ti' => ($cotizer->idType === 'TI') ? 'X' : '',
            'rc' => ($cotizer->idType === 'RC') ? 'X' : '',
            'ce' => ($cotizer->idType === 'CE') ? 'X' : '',
            // Otros datos adicionales            
            'fecha_expedicion_s' => $cotizer->idExpeditionDate,
            'lugar_expedicion_s' => $cotizer->idExpeditionPlace,
            'fecha_nacimiento_s' => $cotizer->birthday,
            'lugar_nacimiento_s' => $cotizer->placeBirth,
            'soltero' => ($cotizer->maritalStatus === 'Soltero (a)') ? 'X' : '',
            'casado' => ($cotizer->maritalStatus === 'Casado (a)') ? 'X' : '',
            'libre' => ($cotizer->maritalStatus === 'Union Libre') ? 'X' : '',
            'viudo' => ($cotizer->maritalStatus === 'Viudo (a)') ? 'X' : '',
            'bachiller' => ($cotizer->studies === 'Bachiller') ? 'X' : '',
            'tecnologo' => ($cotizer->studies === 'Tecnologo') ? 'X' : '',
            'universitario' => ($cotizer->studies === 'Universitario') ? 'X' : '',
            'especializacion' => ($cotizer->studies === 'Especializacion') ? 'X' : '',
            'pais_residencia_s' => $cotizer->country,
            'departamento_s' => $cotizer->department,
            'municipio_s' => $cotizer->municipality,
            'barrio_s' => $cotizer->barrio,
            'viv_familiar_s' => ($cotizer->houseType === 'Familiar') ? 'X' : '',
            'viv_arrendada_s' => ($cotizer->houseType === 'Arrendada') ? 'X' : '',
            'viv_propia_s' => ($cotizer->houseType === 'Propia') ? 'X' : '',
            'dir_residencia_s' => $cotizer->address,
            'telefonoFijo_s' => $cotizer->phoneNumberFijo,
            'celular_s' => $cotizer->phoneNumber,
            'nombre_eps_s' => $cotizer->epsEntity,
            'env_casa_s' => ($cotizer->correspondencyType === 'Casa') ? 'X' : '',
            'env_email_s' => ($cotizer->correspondencyType === 'Mail') ? 'X' : '',
            'email_s' => $cotizer->email,
            'anio_res_s' => ' ' . (string) $cotizer->time,
            'mes_res_s' => ' ' . (string) '0',
            'num_per_cargo_s' => ' ' . (string) $cotizer->living,
            'pnombre_c' => $cotizer->firstNameSpouse, //DATOS CONYUGE
            'snombre_c' => $cotizer->middleNameSpouse,
            'papellido_c' => $cotizer->firstLastnameSpouse,
            'sapellido_c' => $cotizer->secondLastnameSpouse,
            'cc_c' => ($cotizer->idTypeDocSpouse === 'CC') ? 'X' : '',
            'ti_c' => ($cotizer->idTypeDocSpouse === 'TI') ? 'X' : '',
            'rc_c' => ($cotizer->idTypeDocSpouse === 'RC') ? 'X' : '',
            'ce_c' => ($cotizer->idTypeDocSpouse === 'CE') ? 'X' : '',
            'num_cedula_c' => $cotizer->idNumberSpouse,
            'fecha_expedicion_c' => $cotizer->idExpeditionDateSpouse,
            'celular_c' => $cotizer->phoneNumberSpouse,
            'ent_pagaduria_s' => $pagadurias->pagaduria, //DATOS LABORAL
            'ae_activo_s' => ($cotizer->economicActivity === 'Activo') ? 'X' : '',
            'ae_pensionado_s' => ($cotizer->economicActivity === 'Pensionado') ? 'X' : '',
            'tipo_nom_pen_s' =>  $cotizer->workType,
            'fecha_ingreso_s' => $cotizer->startDate,
            'cargo_s' => $cotizer->workTitle,
            'escala_s' => $cotizer->workScale,
            'nom_cole_s' => $cotizer->addressWork,
            'telefono_l' => $cotizer->phoneWork,
            'barrio_l' => $cotizer->workCity,
            'municipio_l' => $cotizer->workMunicipality,
            'departamento_l' => $cotizer->workDepartment,
            'desembolso_c' => ($cotizer->expenditureType === 'Consignacion') ? 'X' : '',
            'desembolso_g' => ($cotizer->expenditureType === 'Giro') ? 'X' : '',
            'num_cuenta _s' => $cotizer->accountNumber,
            'tipo_cuenta _s' => $cotizer->accountType,
            'num_pin_s' => $cotizer->pinNumber,
            'entidad_bancaria _s' => $cotizer->bankingEntity,
            'nom_comple_ref_per' => $cotizer->referenceName, //DATOS REF PERSONAL
            'direc_ref_per' => $cotizer->referenceAddress,
            'barrio_ref_per' => $cotizer->referenceBarrio,
            'municipo_ref_per' => $cotizer->referenceMunicipality,
            'parentesco_ref_per' => $cotizer->referenceParent,
            'depto_ref_per' => $cotizer->referenceDepartment,
            'actividad_ref_per' => $cotizer->referenceActivity,
            'celular_ref_per' => $cotizer->referencePhone,
            'nom_comple_ref_fam' => $cotizer->referenceFName, //DATOS REF FAMILIAR
            'direc_ref_fam' => $cotizer->referenceFAddress,
            'barrio_ref_fam' => $cotizer->referenceFBarrio,
            'municipo_ref_fam' => $cotizer->referenceFMunicipality,
            'parentesco_ref_fam' => $cotizer->referenceFParent,
            'depto_ref_fam' => $cotizer->referenceFDepartment,
            'actividad_ref_fam' => $cotizer->referenceFActivity,
            'celular_ref_fam' => $cotizer->referenceFPhone,
            'actividad_licita' => $cotizer->economicActivity,
            'op_ext_si' => ($cotizer->operationForeign === 'SI') ? 'X' : '',
            'op_ext_no' => ($cotizer->operationForeign === 'NO') ? 'X' : '',
            'cuenta_ext_si' => ($cotizer->accountForeign === 'SI') ? 'X' : '',
            'cuenta_ext_no' => ($cotizer->accountForeign === 'NO') ? 'X' : '',
            'cuenta_foreing_exp' => in_array("exp", $accountTypes) ? 'X' : '',
            'cuenta_foreing_imp' => in_array("imp", $accountTypes) ? 'X' : '',
            'cuenta_foreing_inv' => in_array("inv", $accountTypes) ? 'X' : '',
            'cuenta_foreing_monex' => in_array("monex", $accountTypes) ? 'X' : '',
            'cuenta_foreing_otra' => in_array("otra", $accountTypes) ? 'X' : '',

            'op_cripto_si' => ($cotizer->operationCrypto === 'SI') ? 'X' : '',
            'op_cipto_no' => ($cotizer->operationCrypto === 'NO') ? 'X' : '',
            'op_apnfn_si' => ($cotizer->activityAPNFD === 'SI') ? 'X' : '',
            'op_apnfn_no' => ($cotizer->activityAPNFD === 'NO') ? 'X' : '',

            'nombre_apnfd' => $cotizer->foreignEntity,
            'cuenta_apnfd'    => $cotizer->foreignAccount,
            'tipo_apnfd' => $cotizer->foreignProduct,
            'monto_apnfd' => $cotizer->foreignAmount,
            'moneda_apnfd' => $cotizer->foreignCurrency,
            'ciudad_apnfd' => $cotizer->foreignCity,
            'país_apnfd' => $cotizer->foreignCountry,

            'op_persona_pu_si' => ($cotizer->personPublic === 'SI') ? 'X' : '',
            'op_persona_pu_no' => ($cotizer->personPublic === 'NO') ? 'X' : '',
            'op_recurso_pu_si' => ($cotizer->resourcePublic === 'SI') ? 'X' : '',
            'op_recurso_pu_no' => ($cotizer->resourcePublic === 'NO') ? 'X' : '',
            'op_des_poli_si' => ($cotizer->politicalInfluence === 'SI') ? 'X' : '',
            'op_des_poli_no' => ($cotizer->politicalInfluence === 'NO') ? 'X' : '',
            'op_grupo_et_si' => ($cotizer->ethnicGroup === 'SI') ? 'X' : '',
            'op_grupo_et_no' => ($cotizer->ethnicGroup === 'NO') ? 'X' : '',
            'nombre_etnia' => $cotizer->ethnicName,



            'pregunta1_si' => ($cotizer->question1 === 'SI') ? 'X' : '',
            'pregunta1_no' => ($cotizer->question1 === 'NO') ? 'X' : '',
            'pregunta2_si' => ($cotizer->question2 === 'SI') ? 'X' : '',
            'pregunta2_no' => ($cotizer->question2 === 'NO') ? 'X' : '',
            'pregunta3_si' => ($cotizer->question3 === 'SI') ? 'X' : '',
            'pregunta3_no' => ($cotizer->question3 === 'NO') ? 'X' : '',
            'pregunta4_si' => ($cotizer->question4 === 'SI') ? 'X' : '',
            'pregunta4_no' => ($cotizer->question4 === 'NO') ? 'X' : '',
            'pregunta5_si' => ($cotizer->question5 === 'SI') ? 'X' : '',
            'pregunta5_no' => ($cotizer->question5 === 'NO') ? 'X' : '',
            'empleado' => ($cotizer->ocupations === 'empleado') ? 'X' : '',
            'pensionado' => ($cotizer->ocupations === 'pensionado') ? 'X' : '',
            'amacasa' => ($cotizer->ocupations === 'amacasa') ? 'X' : '',
            'estudiante' => ($cotizer->ocupations === 'estudiante') ? 'X' : '',
            'renta_si' => ($cotizer->rents === 'SI') ? 'X' : '',
            'renta_no' => ($cotizer->rents === 'NO') ? 'X' : '',

            'actividad_economica_l' => $cotizer->economicActivity,
            'nombre_empresa_l' => $cotizer->workName,
            'cargo_l' => $cotizer->workTitle,
            'contrato_l' => $cotizer->workType,
            'fecha_vinc_l' => $cotizer->startDate,
            'direccion_l' => $cotizer->workAddress,
            'telefono_l' => $cotizer->phoneWork,
            'ciudad_l' => $cotizer->workCity,
            'emp_publica' => ($cotizer->bussinesType === 'Publica') ? 'X' : '',
            'emp_privada' => ($cotizer->bussinesType === 'Privada') ? 'X' : '',
            'emp_mixta' => ($cotizer->bussinesType === 'Mixta') ? 'X' : '',
            'nit_l' => $cotizer->bussinesNit,





            'iniciales' => (!empty($cotizer->firstName) ? substr($cotizer->firstName, 0, 1) : '') .
                (!empty($cotizer->middleName) ? substr($cotizer->middleName, 0, 1) : '') .
                (!empty($cotizer->firstLastname) ? substr($cotizer->firstLastname, 0, 1) : '') .
                (!empty($cotizer->secondLastname) ? substr($cotizer->secondLastname, 0, 1) : ''),
            'text_firma' => $signatureText,
            'valor_credito' => $solicitud->valor_solicitado, //DATOS CREDITO
            'tasa_credito' => $solicitud->tasa_interes,
            'valor_cuota_credito' => $solicitud->cuota,
            'num_cuota_credito' => $solicitud->nro_cuotas,
            'valor_total_credito' => $solicitud->credito_total,
            'valor_total_credito_letras' => $this->convertirLetras($solicitud->credito_total, 'PESOS COLOMBIANOS', 'CENTAVOS'),
            'valor_cuota_credito_letras' => $this->convertirLetras($solicitud->cuota, 'PESOS COLOMBIANOS', 'CENTAVOS'),

        ];


        // Generar documento
        $pdfPath = $this->generateDocument($cotizerId, $cedula, $data_doc, $signaturePath);

        Log::info('Generar pdf:');

        if ($decevalProcess) {
            return redirect()->route('deceval.consultar', $data);
        }

        return;
    }

    public function consulta()
    {
        return view('cifin/consulta');
    }

    //Generar pdf con los datos del solicitante
    protected function generateDocument($cotizerId, $document, $data, $imagePathSing)
    {
        $templatePath = storage_path('app/templates/LIBRANZA_CK.docx');
        $outputWordPath = storage_path('app/generated/LIBRANZA_CK_' . $document . '_' . $cotizerId . '.docx');
        //$pdfPath1 = 'app/generated/LIBRANZA_CK_' . $document . '_' . $cotizerId . '.pdf';
        $pdfPath = 'pdfs/sinfirmar/LIBRANZA_CK_' . $document . '_' . $cotizerId . '.pdf';
        $outputPDFPath = Storage::disk('public')->path($pdfPath);

        if (!file_exists($templatePath)) {
            throw new \Exception("El archivo de plantilla no existe.");
        }

        $templateProcessor = new TemplateProcessor($templatePath);

        foreach ($data as $key => $value) {
            $templateProcessor->setValue($key, $value);
        }

        if (file_exists($imagePathSing)) {
            $templateProcessor->setImageValue('firma', [
                'path' => $imagePathSing,
                'width' => 200,
                'height' => 100,
                'ratio' => true
            ]);
        }

        $templateProcessor->saveAs($outputWordPath);
        $this->convertToPdf($outputWordPath, $outputPDFPath);

        // Eliminar imagen temporal
        unlink($imagePathSing);

        return $outputPDFPath;
    }
    protected function convertToPdf($wordPath, $pdfPath)
    {
        $libreofficePath = '"C:\\Program Files\\LibreOffice\\program\\soffice.exe"';
        $command = $libreofficePath . ' --headless --convert-to pdf --outdir ' .
            escapeshellarg(dirname($pdfPath)) . " " . escapeshellarg($wordPath);

        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            throw new \Exception("Error al generar PDF.");
        }
    }

    protected function generateSignatureStamp($mobile, $idType, $document, $dateTime = null)
    {
        $dateTime = $dateTime ? date('M d Y h:iA', strtotime($dateTime)) : date('M d Y h:iA');
        $imagePath = storage_path('app/templates/firma.png');
        $lines = [
            "Log Formulario Libranza Privada",
            "Fecha Creacion: " . $dateTime,
            "Envio OTP a " . $mobile . " Codigo de verificacion Firma",
            "Documento: " . $idType . " : " . $document . " " . $dateTime,
            "Fecha Firma: " . $dateTime,
        ];

        $padding = 10;
        $lineHeight = 15;
        $font = 5; // Misma fuente para todo (5 es negrita y más grande)
        $imagePadding = 10; // Espacio entre imagen y texto

        // Calcular ancho máximo del texto
        $maxTextWidth = 0;
        foreach ($lines as $text) {
            $width = strlen($text) * 10; // Todos usan el mismo tamaño ahora
            if ($width > $maxTextWidth) $maxTextWidth = $width;
        }

        // Altura total del texto
        $textTotalHeight = count($lines) * $lineHeight + ($padding * 2);

        // Procesar imagen si existe
        $imageWidth = 0;
        $imageAdjustedHeight = 0;
        $sourceImage = null;

        if ($imagePath && file_exists($imagePath)) {
            $imageInfo = getimagesize($imagePath);
            $imageWidth = $imageInfo[0];
            $originalImageHeight = $imageInfo[1];

            // Redimensionar imagen para que coincida con la altura del texto
            $imageAdjustedHeight = $textTotalHeight;
            $ratio = $originalImageHeight / $imageAdjustedHeight;
            $imageWidth = (int)($imageWidth / $ratio);

            // Cargar imagen según su tipo
            $imageType = exif_imagetype($imagePath);
            switch ($imageType) {
                case IMAGETYPE_JPEG:
                    $sourceImage = imagecreatefromjpeg($imagePath);
                    break;
                case IMAGETYPE_PNG:
                    $sourceImage = imagecreatefrompng($imagePath);
                    break;
                case IMAGETYPE_GIF:
                    $sourceImage = imagecreatefromgif($imagePath);
                    break;
            }
        }

        // Crear el canvas (ancho: imagen + texto + paddings)
        $stampWidth = $maxTextWidth + ($padding * 2) + $imageWidth + $imagePadding;
        $stampHeight = $textTotalHeight; // La altura ya incluye el padding

        $stamp = imagecreatetruecolor($stampWidth, $stampHeight);
        $transparent = imagecolorallocatealpha($stamp, 0, 0, 0, 127);
        imagefill($stamp, 0, 0, $transparent);
        imagesavealpha($stamp, true);

        // Pegar la imagen (si existe) centrada verticalmente
        if ($sourceImage) {
            $imageY = ($stampHeight - $imageAdjustedHeight) / 2;
            imagecopyresampled(
                $stamp,
                $sourceImage,
                $padding,
                $imageY,
                0,
                0,
                $imageWidth,
                $imageAdjustedHeight,
                $imageInfo[0],
                $originalImageHeight
            );
            imagedestroy($sourceImage);
        }

        // Escribir el texto (a la derecha de la imagen)
        $textColor = imagecolorallocate($stamp, 0, 0, 0);
        $textX = $padding + $imageWidth + $imagePadding;
        $textY = $padding + $lineHeight;

        foreach ($lines as $text) {
            imagestring($stamp, $font, $textX, $textY - 15, $text, $textColor);
            $textY += $lineHeight;
        }

        // Guardar el resultado
        $tempPath = storage_path('app/temp/signature_' . $document . '.png');
        if (!is_dir(dirname($tempPath))) {
            mkdir(dirname($tempPath), 0777, true);
        }
        imagepng($stamp, $tempPath, 9);
        imagedestroy($stamp);

        return $tempPath;
    }

    protected function generateSigningText($city, $dateTime)
    {
        $date = new DateTime($dateTime);
        $day = $date->format('j');
        $monthNumber = $date->format('n');
        $year = $date->format('Y');

        $months = [
            1 => 'enero',
            2 => 'febrero',
            3 => 'marzo',
            4 => 'abril',
            5 => 'mayo',
            6 => 'junio',
            7 => 'julio',
            8 => 'agosto',
            9 => 'septiembre',
            10 => 'octubre',
            11 => 'noviembre',
            12 => 'diciembre'
        ];

        $month = $months[$monthNumber];


        // Convertir día a texto (ej: 30 → "treinta")
        $dayInWords = [
            1 => 'uno',
            2 => 'dos',
            3 => 'tres',
            4 => 'cuatro',
            5 => 'cinco',
            6 => 'seis',
            7 => 'siete',
            8 => 'ocho',
            9 => 'nueve',
            10 => 'diez',
            11 => 'once',
            12 => 'doce',
            13 => 'trece',
            14 => 'catorce',
            15 => 'quince',
            16 => 'dieciséis',
            17 => 'diecisiete',
            18 => 'dieciocho',
            19 => 'diecinueve',
            20 => 'veinte',
            21 => 'veintiuno',
            22 => 'veintidós',
            23 => 'veintitrés',
            24 => 'veinticuatro',
            25 => 'veinticinco',
            26 => 'veintiséis',
            27 => 'veintisiete',
            28 => 'veintiocho',
            29 => 'veintinueve',
            30 => 'treinta',
            31 => 'treinta y uno'
        ];

        $dayText = $dayInWords[$day] ?? $day;

        return "Para constancia se firma en la ciudad de $city a los $dayText($day) días del mes de $month del año $year.";
    }
    public static function convertirLetras($numero, $moneda = 'PESOS COLOMBIANOS', $centimos = 'CENTAVOS')
    {
        $unidades = ['', 'UN', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO', 'NUEVE'];
        $decimas = ['DIEZ', 'ONCE', 'DOCE', 'TRECE', 'CATORCE', 'QUINCE', 'DIECISEIS', 'DIECISIETE', 'DIECIOCHO', 'DIECINUEVE'];
        $decenas = ['', 'DIEZ', 'VEINTE', 'TREINTA', 'CUARENTA', 'CINCUENTA', 'SESENTA', 'SETENTA', 'OCHENTA', 'NOVENTA'];
        $centenas = ['', 'CIENTO', 'DOSCIENTOS', 'TRESCIENTOS', 'CUATROCIENTOS', 'QUINIENTOS', 'SEISCIENTOS', 'SETECIENTOS', 'OCHOCIENTOS', 'NOVECIENTOS'];

        $numero = number_format($numero, 2, '.', '');
        list($entero, $decimal) = explode('.', $numero);

        $converted = '';

        if ($entero == 0) {
            $converted = 'CERO';
        } else if ($entero < 10) {
            $converted = $unidades[$entero];
        } else if ($entero < 20) {
            $converted = $decimas[$entero - 10];
        } else if ($entero < 30) {
            $converted = 'VEINTI' . $unidades[$entero - 20];
        } else if ($entero < 100) {
            $d = floor($entero / 10);
            $u = $entero % 10;
            $converted = $decenas[$d];
            if ($u > 0) {
                $converted .= ' Y ' . $unidades[$u];
            }
        } else if ($entero == 100) {
            $converted = 'CIEN';
        } else if ($entero < 1000) {
            $c = floor($entero / 100);
            $resto = $entero % 100;
            $converted = $centenas[$c];
            if ($resto > 0) {
                $converted .= ' ' . self::convertirLetras($resto, '', '');
            }
        } else if ($entero < 2000) {
            $resto = $entero % 1000;
            $converted = 'MIL';
            if ($resto > 0) {
                $converted .= ' ' . self::convertirLetras($resto, '', '');
            }
        } else if ($entero < 1000000) {
            $miles = floor($entero / 1000);
            $resto = $entero % 1000;
            $converted = self::convertirLetras($miles, '', '') . ' MIL';
            if ($resto > 0) {
                $converted .= ' ' . self::convertirLetras($resto, '', '');
            }
        } else if ($entero < 2000000) {
            $resto = $entero % 1000000;
            $converted = 'UN MILLÓN';
            if ($resto > 0) {
                $converted .= ' ' . self::convertirLetras($resto, '', '');
            }
        } else if ($entero < 1000000000) {
            $millones = floor($entero / 1000000);
            $resto = $entero % 1000000;
            $converted = self::convertirLetras($millones, '', '') . ' MILLONES';
            if ($resto > 0) {
                $converted .= ' ' . self::convertirLetras($resto, '', '');
            }
        }

        // Agregar la moneda al final
        $resultado = trim($converted) . ' ' . trim($moneda);

        // Manejo correcto de los decimales
        if ($decimal > 0) {
            $decimalesTexto = self::convertirLetras(intval($decimal), '', '');
            $resultado .= ' CON ' . $decimalesTexto . ' ' . trim($centimos);
        }

        return $resultado;
    }
}
