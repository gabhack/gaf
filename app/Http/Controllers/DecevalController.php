<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DOMDocument;

class DecevalController extends Controller
{

   public function __construct()
   {
      $this->middleware('auth');
      $this->middleware('role:ADMIN_SISTEMA,ADMIN_HEGO,ADMIN_AMI,COMPANY,CREAUSUARIOS');
   }

   public function index()
   {
      return view("deceval/index");
   }

   public function consultar(Request $request)
   {
      $nitEmisor = $request->nitEmisor;
      $idClaseDefinicionDocumento = $request->idClaseDefinicionDocumento;
      $fechaGrabacionPagare = $request->fechaGrabacionPagare;
      $numPagareEntidad = $request->numPagareEntidad;
      $fechaDesembolso = $request->fechaDesembolso;

      //Datos girador
      $otorganteTipoId = $request->otorganteTipoId;
      $otorganteNumId = $request->otorganteNumId;
      $otorganteCuenta = $request->otorganteCuenta;

      //datos header
      $hoy = date("Y-m-d");
      $hora = date("H:i:s");
      $codigoDepositante = '680';
      $fecha = $hoy . 'T' . $hora;
      //$hora = '11:01';
      $usuario = '90043262901';

      $url = "http://34.171.55.31/DecevalProxy/services/ProxyServicesImplPort?wsdl";
      $xml_post_string = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://services.proxy.deceval.com/">
        <soapenv:Header/>
        <soapenv:Body>
           <ser:creacionPagaresCodificado>
              <!--Optional:-->
              <arg0>
                 <!--Optional:-->
                 <documentoPagareServiceDTO>
                    <nitEmisor>' . $nitEmisor . '</nitEmisor>
                    <idClaseDefinicionDocumento>' . $idClaseDefinicionDocumento . '</idClaseDefinicionDocumento>
                    <fechaGrabacionPagare>' . $fechaGrabacionPagare . '</fechaGrabacionPagare>
                    <tipoPagare>2</tipoPagare>
                    <numPagareEntidad>' . $numPagareEntidad . '</numPagareEntidad>
                    <fechaDesembolso>' . $fechaDesembolso . '</fechaDesembolso>
                    <otorganteTipoId>' . $otorganteTipoId . '</otorganteTipoId>
                    <otorganteNumId>' . $otorganteNumId . '</otorganteNumId>
                    <otorganteCuenta>' . $otorganteCuenta . '</otorganteCuenta>
                    <creditoReembolsableEn>2</creditoReembolsableEn>
                    <valorPesosDesembolso>1500000</valorPesosDesembolso>
                    <ciudadDesembolso>11001</ciudadDesembolso>
                    <departamento>11</departamento>
                    <pais>CO</pais>
                    <tasaInteres>1</tasaInteres>
                    <mensajeRespuesta>?</mensajeRespuesta>
                    <archivosAdjuntos>
                       <contenido>/9j/4AAQSkZJR</contenido>
                       <nombreArchivo>imagen5.jpg</nombreArchivo>
                    </archivosAdjuntos>
                 </documentoPagareServiceDTO>
              <!--Optional:-->
              <header>
                 <!--Optional:-->
                 <codigoDepositante>' . $codigoDepositante . '</codigoDepositante>
                 <!--Optional:-->
                 <fecha>' . $fecha . '</fecha>
                 <!--Optional:-->
                 <hora>' . $hora . '</hora>
                 <!--Optio$hnal:-->
                 <usuario>' . $usuario . '</usuario>
              </header>
           </arg0>
        </ser:creacionPagaresCodificado>
     </soapenv:Body>
     </soapenv:Envelope>';

      $headers = array(
         "Content-type: text/xml; charset=\"utf-8\"",
         "Accept: text/xml",
         "Cache-Control: no-cache",
         "Pragma: no-cache",
         "SOAPAction: \"\"",
         "Content-lenght: " . strlen($xml_post_string)
      );

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
      curl_setopt($ch, CURLOPT_TIMEOUT, 10);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

      $response = curl_exec($ch);
      curl_close($ch);
      $doc = new DOMDocument();
      $doc->loadXML($response);
      $doc->save('demo.xml');

      $idDocumentoPagare = $doc->getElementsByTagName('idDocumentoPagare')->item(0)->nodeValue;
      $numPagareEntidad = $doc->getElementsByTagName('numPagareEntidad')->item(0)->nodeValue;
      $firma = $this->firmarPagareCaracteres($idDocumentoPagare, $otorganteNumId, $codigoDepositante, $fecha, $hora, $usuario);

      $firma->save('firma.xml');

      $response2 = $this->consultarPagares($idDocumentoPagare, $otorganteTipoId, $otorganteNumId, $numPagareEntidad, $codigoDepositante, $fecha, $hora, $usuario);

      $response2->save('consulta.xml');

      $contenido = $response2->getElementsByTagName('contenido')->item(0)->nodeValue;
      $nombreArchivo = $response2->getElementsByTagName('nombreArchivo')->item(0)->nodeValue;

      $tempArchivo = "token.txt";
      $archivo = fopen($tempArchivo, "w");
      fwrite($archivo, $contenido);
      fclose($archivo);
      $pdf_base64 = "token.txt";
      $pdf_base64_handler = fopen($pdf_base64, 'r');
      $pdf_content = fread($pdf_base64_handler, filesize($pdf_base64));
      fclose($pdf_base64_handler);
      $pdf_decoded = base64_decode($pdf_content);
      $pdf = fopen('Pagare.pdf', 'w');
      fwrite($pdf, $pdf_decoded);
      fclose($pdf);
      $link = asset($nombreArchivo);
      $demo = 'hola';
      $resul = [$link, $demo];

      return view("deceval/consulta")->with([
         "resultado" => $resul
      ]);
   }

   public function consultarPagares($idDocumentoPagare, $otorganteTipoId, $otorganteNumId, $numPagareEntidad, $codigoDepositante, $fecha, $hora, $usuario)
   {
      $url = "http://34.171.55.31/DecevalProxy/services/ProxyServicesImplPort?wsdl";
      $xml_post_string = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://services.proxy.deceval.com/">
        <soapenv:Header/>
        <soapenv:Body>
           <ser:consultarPagares>
              <!--Optional:-->
              <arg0>
                 <!--Optional:-->
                 <consultaPagareServiceDTO>
                    <!--Optional:-->
                    <codigoDeceval>' . $idDocumentoPagare . '</codigoDeceval>
                    <!--Optional:-->
                    <idTipoIdentificacionFirmante>' . $otorganteTipoId . '</idTipoIdentificacionFirmante>
                    <!--Optional:-->
                    <numIdentificacionFirmante>' . $otorganteNumId . '</numIdentificacionFirmante>
                    <!--Optional:-->
                    <numPagareEntidad>' . $numPagareEntidad . '</numPagareEntidad>
                 </consultaPagareServiceDTO>
                 <!--Optional:-->
                 <header>
                    <!--Optional:-->
                    <codigoDepositante>' . $codigoDepositante . '</codigoDepositante>
                    <!--Optional:-->
                    <fecha>' . $fecha . '</fecha>
                    <!--Optional:-->
                    <hora>' . $hora . '</hora>
                    <!--Optional:-->
                    <usuario>' . $usuario . '</usuario>
                 </header>
              </arg0>
           </ser:consultarPagares>
        </soapenv:Body>
     </soapenv:Envelope>';
      $doc = new DOMDocument();
      $doc->loadXML($xml_post_string);
      $doc->save('consultaresponse.xml');

      $headers = array(
         "Content-type: text/xml; charset=\"utf-8\"",
         "Accept: text/xml",
         "Cache-Control: no-cache",
         "Pragma: no-cache",
         "SOAPAction: \"\"",
         "Content-lenght: " . strlen($xml_post_string)
      );

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
      curl_setopt($ch, CURLOPT_TIMEOUT, 10);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

      $response = curl_exec($ch);
      curl_close($ch);
      $doc = new DOMDocument();
      $doc->loadXML($response);
      return $doc;
   }

   public function firmarPagareCaracteres($idDocumentoPagare, $otorganteNumId, $codigoDepositante, $fecha, $hora, $usuario)
   {
      $url = "http://34.171.55.31/DecevalProxy/services/ProxyServicesImplPort?wsdl";
      $xml_post_string = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://services.proxy.deceval.com/">
        <soapenv:Header/>
        <soapenv:Body>
           <ser:firmarPagareCaracteres>
              <!--Optional:-->
              <arg0>
                 <!--Optional:-->
                 <header>
                    <!--Optional:-->
                    <codigoDepositante>' . $codigoDepositante . '</codigoDepositante>
                    <!--Optional:-->
                    <fecha>' . $fecha . '</fecha>
                    <!--Optional:-->
                    <hora>' . $hora . '</hora>
                    <!--Optional:-->
                    <usuario>' . $usuario . '</usuario>
                 </header>
                 <!--Optional:-->
                 <informacionFirmaPagareCaracteresDTO>
                    <!--Optional:-->
                    <clave>Cambiar1*</clave>
                    <idDocumentoPagare>' . $idDocumentoPagare . '</idDocumentoPagare>
                    <idRolFirmante>5</idRolFirmante>
                    <numeroDocumento>' . $otorganteNumId . '</numeroDocumento>
                    <tipoDocumento>1</tipoDocumento>
                 </informacionFirmaPagareCaracteresDTO>
              </arg0>
           </ser:firmarPagareCaracteres>
        </soapenv:Body>
     </soapenv:Envelope>';

      $headers = array(
         "Content-type: text/xml; charset=\"utf-8\"",
         "Accept: text/xml",
         "Cache-Control: no-cache",
         "Pragma: no-cache",
         "SOAPAction: \"\"",
         "Content-lenght: " . strlen($xml_post_string)
      );

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
      curl_setopt($ch, CURLOPT_TIMEOUT, 10);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

      $response = curl_exec($ch);
      curl_close($ch);
      $doc = new DOMDocument();
      $doc->loadXML($response);
      return $doc;
   }

   public function consulta()
   {
      return view('deceval/consulta')->with();
   }
}
