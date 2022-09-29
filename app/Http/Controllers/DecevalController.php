<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DOMDocument;

class DecevalController extends Controller
{
   public $ambiente = 1; // 0 = pruebas, 1 = produccion

   // public $urlTransUnion = "https://www.transuniondecisioncentreuat.com.mx/TU.IDS.ExternalServices_mex_latam/SolutionExecution/ExternalSolutionExecution.svc";
   // public $userTransUnion = "IDV_Ckcomercializadora.DEV1";
   // public $passTransUnion = "CK%comer2022*";

   public $urlTransUnion = "https://www.transuniondecisioncentre.com.mx/TU.IDS.ExternalServices_latam_prod/SolutionExecution/ExternalSolutionExecution.svc";
   public $userTransUnion = "IDV_CKcomercilizadora.PROD1";
   public $passTransUnion = "]LLB&5FYyPmd[";

   public function __construct()
   {
      $this->middleware('auth');
      $this->middleware('role:ADMIN_SISTEMA,ADMIN_HEGO,ADMIN_AMI,COMPANY,CREAUSUARIOS');
   }

   public function index()
   {
      return view("deceval/index");
   }

   public function firmar(Request $request)
   {
      $usuario = '90043262901';
      $nombreArchivo = "pagare.pdf";

      try {
         if ($this->ambiente === 0) {
            $expeditionDate = "27/09/2011";
            $recentPhone = "3115879658";
            $cedula = "1216713792";
            $lastName = "VERGARA";
         } else {
            $expeditionDate = $request->expeditionDate;
            $recentPhone = $request->phone;
            $cedula = "1216713792";
            $lastName = explode(" ", $response2->getElementsByTagName('nombreOtorgante'))[2];
         }

         $inicioFlujoFirma = $this->iniciarFlujoFirma($cedula, $expeditionDate, $recentPhone, $lastName);
      } catch (\Exception $ex) {
         $errorFirma = "Ocurrio un error al solicitar el numero de aplicacion, para continuar el proceso puede hacer click en el boton 'Regenerar Flujo'    ";
         $step = "failCreateFlow";
         $link = asset($nombreArchivo);
         $resul = [$link];
         return view("deceval/consulta")->with([
            "resultado" => $resul,
            "errorFirma" => $errorFirma,
            "idDocumentoPagare" => $request->idDocumentoPagare,
            "otorganteTipoId" => $request->otorganteTipoId,
            "otorganteNumId" => $request->otorganteNumId,
            "numPagareEntidad" => $request->numPagareEntidad,
            "codigoDepositante" => $request->codigoDepositante,
            "fecha" => $request->fecha,
            "hora" => $request->hora,
            "application" => null,
            "phoneList" => null,
            "phoneListStr" => null,
            "step" => $step,
            "confirmCode" => "",
            "ambiente" => $this->ambiente
         ]);
      }

      $application  = $request->application;
      $phoneList = explode(",", $request->phoneListStr);

      // Se genera codigo
      if ($request->step === 'initial') {
         $code = "";

         try {
            $code = $this->generarCode($application, $request->phone);
         } catch (Exeption $ex) {
            //
         }

         $link = asset($nombreArchivo);
         $resul = [$link];

         return view("deceval/consulta")->with([
            "resultado" => $resul,
            "errorFirma" => "",
            "idDocumentoPagare" => $request->idDocumentoPagare,
            "otorganteTipoId" => $request->otorganteTipoId,
            "otorganteNumId" => $request->otorganteNumId,
            "numPagareEntidad" => $request->numPagareEntidad,
            "codigoDepositante" => $request->codigoDepositante,
            "fecha" => $request->fecha,
            "hora" => $request->hora,
            "application" => $application,
            "phoneList" => $phoneList,
            "phoneListStr" => $request->phoneListStr,
            "step" => "confirmCode",
            "code" => $code,
            "confirmCode" => "confirmCode",
            "ambiente" => $this->ambiente
         ]);
      }

      if ($request->step === 'confirmCode') {
         $this->confirmarCode($application, $request->code);
         $link = asset($nombreArchivo);
         $resul = [$link];

         return view("deceval/consulta")->with([
            "resultado" => $resul,
            "errorFirma" => "",
            "idDocumentoPagare" => $request->idDocumentoPagare,
            "otorganteTipoId" => $request->otorganteTipoId,
            "otorganteNumId" => $request->otorganteNumId,
            "numPagareEntidad" => $request->numPagareEntidad,
            "codigoDepositante" => $request->codigoDepositante,
            "fecha" => $request->fecha,
            "hora" => $request->hora,
            "application" => $application,
            "phoneList" => $phoneList,
            "phoneListStr" => $request->phoneListStr,
            "step" => "confirmCode2",
            "code" => $request->code,
            "confirmCode" => "confirmCode",
            "ambiente" => $this->ambiente
         ]);
      }



      try {
         //======================FIRMAR PAGARE
         try {
            $firma = $this->firmarPagareCaracteres(
               $request->idDocumentoPagare,
               $request->otorganteNumId,
               $request->codigoDepositante,
               $request->fecha,
               $request->hora,
               $usuario
            );
            $firma->save('firmaExecutionResponse.xml');
         } catch (\Exception $ex) {
            //
         }

         //=======================CONSULTAR NUEVO PAGARE
         $response2 = $this->consultarPagares(
            $request->idDocumentoPagare,
            $request->otorganteTipoId,
            $request->otorganteNumId,
            $request->numPagareEntidad,
            $request->codigoDepositante,
            $request->fecha,
            $request->hora,
            $usuario
         );

         $response2->save('consultaFirmadoResponse.xml');
         $contenido = $response2->getElementsByTagName('contenido')->item(0)->nodeValue;
         $nombreArchivo = $response2->getElementsByTagName('nombreArchivo')->item(0)->nodeValue;
         $this->generatePdf($contenido, $nombreArchivo);
      } catch (\Exception $ex) {
         $link = asset($nombreArchivo);
         $resul = [$link];

         return view("deceval/consulta")->with([
            "resultado" => $resul,
            "errorFirma" => "Error ejecutando la firma del documento, favor intente mas tarde...",
            "idDocumentoPagare" => $request->idDocumentoPagare,
            "otorganteTipoId" => $request->otorganteTipoId,
            "otorganteNumId" => $request->otorganteNumId,
            "numPagareEntidad" => $request->numPagareEntidad,
            "codigoDepositante" => $request->codigoDepositante,
            "fecha" => $request->fecha,
            "hora" => $request->hora,
            "application" => $application,
            "phoneList" => $phoneList,
            "step" => "confirmCode2",
            "phoneListStr" => $request->phoneListStr,
            "ambiente" => $this->ambiente
         ]);
      }

      $link = asset($nombreArchivo);
      $resul = [$link];

      return view("deceval/consulta")->with([
         "resultado" => $resul,
         "errorFirma" => "",
         "idDocumentoPagare" => $request->idDocumentoPagare,
         "otorganteTipoId" => $request->otorganteTipoId,
         "otorganteNumId" => $request->otorganteNumId,
         "numPagareEntidad" => $request->numPagareEntidad,
         "codigoDepositante" => $request->codigoDepositante,
         "fecha" => $request->fecha,
         "hora" => $request->hora,
         "application" => $application,
         "phoneList" => $phoneList,
         "step" => "firmado",
         "phoneListStr" => $request->phoneListStr,
         "ambiente" => $this->ambiente
      ]);
   }

   public function consultar(Request $request)
   {
      try {
         $nitEmisor = $request->nitEmisor;
         $idClaseDefinicionDocumento = $request->idClaseDefinicionDocumento;
         $fechaGrabacionPagare = $request->fechaGrabacionPagare;
         $numPagareEntidad = $request->numPagareEntidad;
         $fechaDesembolso = $request->fechaDesembolso;

         //Datos girador
         $otorganteTipoId = $request->otorganteTipoId;
         $otorganteNumId = $request->otorganteNumId;
         $otorganteCuenta = $request->otorganteCuenta;

         // crear otorgante
         $this->crearOtorgante();

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
         //echo $xml_post_string;
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
         //echo  $response ;
         $doc = new DOMDocument();
         $doc->loadXML($response);
         $doc->save('demoxds.xml');

         $idDocumentoPagare = $doc->getElementsByTagName('idDocumentoPagare')->item(0)->nodeValue;
         $numPagareEntidad = $doc->getElementsByTagName('numPagareEntidad')->item(0)->nodeValue;

         $response2 = $this->consultarPagares(
            $idDocumentoPagare,
            $otorganteTipoId,
            $otorganteNumId,
            $numPagareEntidad,
            $codigoDepositante,
            $fecha,
            $hora,
            $usuario
         );

         $response2->save('consulta.xml');

         $contenido = $response2->getElementsByTagName('contenido')->item(0)->nodeValue;
         $nombreArchivo = $response2->getElementsByTagName('nombreArchivo')->item(0)->nodeValue;

         $step = "initial";
         $errorFirma = "";

         try {
            if ($this->ambiente === 0) {
               $expeditionDate = "27/09/2011";
               $recentPhone = "3115879658";
               $cedula = "1216713792";
               $lastName = "VERGARA";
            } else {
               $expeditionDate = $request->expeditionDate;
               $recentPhone = $request->phone;
               $cedula = "1216713792";
               $lastName = explode(" ", $response2->getElementsByTagName('nombreOtorgante'))[2];
            }

            $inicioFlujoFirma = $this->iniciarFlujoFirma($cedula, $expeditionDate, $recentPhone, $lastName);
         } catch (\Exception $ex) {
            $link = asset($nombreArchivo);
            $resul = [$link];
            $errorFirma = "Ocurrio un error al solicitar el numero de aplicacion, para continuar el proceso puede hacer click en el boton 'Regenerar Flujo'    ";
            $step = "failCreateFlow";

            return view("deceval/consulta")->with([
               "resultado" => $resul,
               "errorFirma" => $errorFirma,
               "idDocumentoPagare" => $idDocumentoPagare,
               "otorganteTipoId" => $otorganteTipoId,
               "otorganteNumId" => $otorganteNumId,
               "numPagareEntidad" => $numPagareEntidad,
               "codigoDepositante" => $codigoDepositante,
               "fecha" => $fecha,
               "hora" => $hora,
               "application" => null,
               "phoneList" => null,
               "phoneListStr" => null,
               "step" => $step,
               "confirmCode" => "",
               "ambiente" => $this->ambiente
            ]);
         }

         $this->generatePdf($contenido, $nombreArchivo);

         $link = asset($nombreArchivo);
         $resul = [$link];

         return view("deceval/consulta")->with([
            "resultado" => $resul,
            "errorFirma" => $errorFirma,
            "idDocumentoPagare" => $idDocumentoPagare,
            "otorganteTipoId" => $otorganteTipoId,
            "otorganteNumId" => $otorganteNumId,
            "numPagareEntidad" => $numPagareEntidad,
            "codigoDepositante" => $codigoDepositante,
            "fecha" => $fecha,
            "hora" => $hora,
            "application" => $inicioFlujoFirma['application'],
            "phoneList" => $inicioFlujoFirma['phoneList'],
            "phoneListStr" => implode(",", $inicioFlujoFirma['phoneList']),
            "step" => $step,
            "confirmCode" => "",
            "ambiente" => $this->ambiente
         ]);
      } catch (Exception $e) {
         $error = $e->getMessage();
      }

      return view("deceval/consulta")->with([
         "resultado" => null,
         "errorFirma" => "",
         "application" => $inicioFlujoFirma['application'],
         "phoneList" => $inicioFlujoFirma['phoneList'],
         "phoneListStr" => implode(",", $inicioFlujoFirma['phoneList']),
         "error" => $error,
         "step" => "initial",
         "confirmCode" => "",
         "ambiente" => $this->ambiente
      ]);
   }

   private function generatePdf($contenido, $nombreArchivo)
   {
      // Se escribe contenido en token.txt
      $tempArchivo = "token.txt";
      $archivo = fopen($tempArchivo, "w");
      fwrite($archivo, $contenido);
      fclose($archivo);

      //Ese se abre para convertirlo de base 64
      $pdf_base64 = "token.txt";
      $pdf_base64_handler = fopen($pdf_base64, 'r');
      $pdf_content = fread($pdf_base64_handler, filesize($pdf_base64));
      fclose($pdf_base64_handler);
      $pdf_decoded = base64_decode($pdf_content);

      // Se escribe al file pdf
      $pdf = fopen('pagare.pdf', 'w');
      fwrite($pdf, $pdf_decoded);
      fclose($pdf);
   }

   public function crearOtorgante()
   {
      $url = "http://34.171.55.31/DecevalProxy/services/ProxyServicesImplPort?wsdl";

      $xml_post_string = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://services.proxy.deceval.com/">
            <soapenv:Header/>
            <soapenv:Body>
               <ser:creacionGiradoresCodificados>
                  <!--Optional:-->
                  <arg0>
                     <!--Optional:-->
                     <crearGiradorDTO>
                        <!--Optional:-->
                        <identificacionEmisor>9004326290</identificacionEmisor>
                        <fkIdClasePersona>1</fkIdClasePersona>
                        <fkIdTipoDocumento>1</fkIdTipoDocumento>
                        <numeroDocumento>800941010</numeroDocumento>
                        <correoElectronico>demo@bvc.com.co</correoElectronico>
                        <direccion1PersonaGrupo_PGP>Calle 1#1-1</direccion1PersonaGrupo_PGP>
                        <telefono1PersonaGrupo_PGP>1111111</telefono1PersonaGrupo_PGP>
                        <fax1PersonaGrupo_PGP>1111111</fax1PersonaGrupo_PGP>
                        <fkIdPaisExpedicion_Nat>CO</fkIdPaisExpedicion_Nat>
                        <fkIdDepartamentoExpedicion_Nat>11</fkIdDepartamentoExpedicion_Nat>
                        <fkIdCiudadExpedicion_Nat>11001</fkIdCiudadExpedicion_Nat>
                        <fkIdPaisDomicilio_Nat>CO</fkIdPaisDomicilio_Nat>
                        <fkIdDepartamentoDomicilio_Nat>11</fkIdDepartamentoDomicilio_Nat>
                        <fkIdCiudadDomicilio_Nat>11001</fkIdCiudadDomicilio_Nat>
                        <fechaExpedicion_Nat>2000-12-24T00:00:00</fechaExpedicion_Nat>
                        <fechaNacimiento_Nat>1980-12-24T00:00:00</fechaNacimiento_Nat>
                        <nombresNat_Nat>JUAN</nombresNat_Nat>
                        <primerApellido_Nat>PEREZ</primerApellido_Nat>
                        <segundoApellido_Nat>MARTINEZ</segundoApellido_Nat>
                        <fkIdPaisNacionalidad_Nat>CO</fkIdPaisNacionalidad_Nat>
                        <mensajeRespuesta>?</mensajeRespuesta>
                     </crearGiradorDTO>
                     <!--Optional:-->
                     <header>
                        <!--Optional:-->
                        <codigoDepositante>680</codigoDepositante>
                        <!--Optional:-->
                        <fecha>2022-08-02T11:01:00</fecha>
                        <!--Optional:-->
                        <hora>11:01</hora>
                        <!--Optional:-->
                        <usuario>90043262901</usuario>
                     </header>
                  </arg0>
               </ser:creacionGiradoresCodificados>
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

      return $response;
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
      $doc->save('consultaRequest.xml');

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
      curl_setopt($ch, CURLOPT_TIMEOUT, 100);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

      $response = curl_exec($ch);

      curl_close($ch);
      $doc = new DOMDocument();
      //echo $response;
      $doc->loadXML($response);
      $doc->save('consultaResponseFrm.xml');
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

   public function iniciarFlujoFirma($documentId, $expeditionDate, $recentPhone, $lastName)
   {
      //DocumentId:1216713792
      //expeditionDate27/09/2011
      //ResentPhone 3115879658

      $xml_post_string = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:tem="http://tempuri.org/">
         <soapenv:Header/>
         <soapenv:Body>
            <tem:ExecuteXMLString>
               <!--Optional:-->
               <tem:request>
                     <![CDATA[
                  <DCRequest xmlns="http://transunion.com/dc/extsvc">
                        <Authentication type="OnDemand">
                           <UserId>' . $this->userTransUnion . '</UserId>
                           <Password>' . $this->passTransUnion . '</Password>
                        </Authentication>
                        ' . $this->createRequestInfoFlow("NewWithContext") . '
                        <UserData></UserData>
                        <Fields>
                           <Field key="PrimaryApplicant">
                              &lt;Applicant&gt;
                                 &lt;IdNumber&gt;' . $documentId . '&lt;/IdNumber&gt;
                                 &lt;IdType&gt;1&lt;/IdType&gt;
                                 &lt;IdExpeditionDate&gt;' . $expeditionDate . '&lt;/IdExpeditionDate&gt;
                                 &lt;RecentPhoneNumber&gt;' . $recentPhone . '&lt;/RecentPhoneNumber&gt;
                                 &lt;LastName&gt;' . $lastName . '&lt;/LastName&gt;
                              &lt;/Applicant&gt;
                           </Field>	
                        </Fields>	
                     </DCRequest>
                  ]]>
               </tem:request>
            </tem:ExecuteXMLString>
         </soapenv:Body>
      </soapenv:Envelope>';
      //echo $xml_post_string;
      $headers = array(
         "Content-type: text/xml; charset=\"utf-8\"",
         "Accept: text/xml",
         "Cache-Control: no-cache",
         'SOAPAction: "http://tempuri.org/IExternalSolutionExecution/ExecuteXMLString"',
         "Pragma: no-cache",
         "Content-lenght: " . strlen($xml_post_string)
      );

      $doc = new DOMDocument();
      $doc->loadXML($xml_post_string);
      $doc->save('initialRequest.xml');

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($ch, CURLOPT_URL, $this->urlTransUnion);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      //curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
      //curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
      curl_setopt($ch, CURLOPT_TIMEOUT, 10);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

      $response = curl_exec($ch);

      curl_close($ch);
      //echo  $response ;
      $doc = new DOMDocument();
      $doc->loadXML($response);
      $doc->save('initialFirmProcess.xml');

      $dataUNScape = $doc->getElementsByTagName('ExecuteXMLStringResult')->item(0)->nodeValue;
      $docResponse = new DOMDocument();
      $docResponse->loadXML($dataUNScape);
      $docResponse->save('initialFirmProcessContent.xml');
      $application = null;
      $phoneList[] = "";

      if ($docResponse->getElementsByTagName('Status')->item(0)->nodeValue === 'Success') {
         $application = $docResponse->getElementsByTagName('ApplicationId')->item(0)->nodeValue;
         foreach ($docResponse->getElementsByTagName('Field') as $valor) {
            if ($valor->hasAttributes()) {
               foreach ($valor->attributes as $attr) {
                  $name = $attr->nodeName;
                  $value = $attr->nodeValue;
                  if ($name === 'key' && $value === 'MobilePhoneList') {
                     $phoneList = explode(",", $valor->nodeValue);
                  }
               }
            }
         }
      }

      if ($application === null) {
         throw new Exception('Aplicaicon no creada');
      }

      $retorno['application'] = $application;
      $retorno['phoneList'] = $phoneList;

      return  $retorno;
   }

   public function generarCode($application, $phoneSelection)
   {
      $xml_post_string = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:tem="http://tempuri.org/">
      <soapenv:Header/>
      <soapenv:Body>
         <tem:ExecuteXMLString>
            <!--Optional:-->
            <tem:request>
                  <![CDATA[
               <DCRequest xmlns="http://transunion.com/dc/extsvc">
                     <Authentication type="OnDemand">
                     <UserId>' . $this->userTransUnion . '</UserId>
                     <Password>' . $this->passTransUnion . '</Password>
                     </Authentication>
                     ' . $this->createRequestInfoFlow("Send", "PhoneSelection", $application) . '
                     <Fields>
                        <Field key="PhoneType">Mobile</Field>	
                        <Field key="SelectedPhoneNumber">' . $phoneSelection . '</Field>	
                  <Field key="ValidationMethod">SMS</Field>
                     </Fields>	
                  </DCRequest>
               ]]>
            </tem:request>
         </tem:ExecuteXMLString>
      </soapenv:Body>
      </soapenv:Envelope>';
      //echo $xml_post_string;
      $headers = array(
         "Content-type: text/xml; charset=\"utf-8\"",
         "Accept: text/xml",
         "Cache-Control: no-cache",
         'SOAPAction: "http://tempuri.org/IExternalSolutionExecution/ExecuteXMLString"',
         "Pragma: no-cache",
         "Content-lenght: " . strlen($xml_post_string)
      );

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($ch, CURLOPT_URL,  $this->urlTransUnion);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      //curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
      //curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
      curl_setopt($ch, CURLOPT_TIMEOUT, 10);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

      $response = curl_exec($ch);
      curl_close($ch);
      //echo  $response ;
      $doc = new DOMDocument();
      $doc->loadXML($response);
      $doc->save('generateCodeResponse.xml');

      $dataUNScape = $doc->getElementsByTagName('ExecuteXMLStringResult')->item(0)->nodeValue;
      $docResponse = new DOMDocument();
      $docResponse->loadXML($dataUNScape);
      $docResponse->save('generateCodeResponseContent.xml');
      $OTPCode = "";

      if (
         $docResponse->getElementsByTagName('Status')->item(0)->nodeValue === 'Success' &&
         $docResponse->getElementsByTagName('CurrentQueue')->item(0)->nodeValue === 'PinVerification_OTPInput'
      ) {
         if ($this->ambiente === 0) {
            foreach ($docResponse->getElementsByTagName('Field') as $valor) {
               if ($valor->hasAttributes()) {
                  foreach ($valor->attributes as $attr) {
                     $name = $attr->nodeName;
                     $value = $attr->nodeValue;
                     if ($name === 'key' && $value === 'OTPCode') {
                        $OTPCode = $valor->nodeValue;
                     }
                  }
               }
            }
         }
      }
      return $OTPCode;
   }

   private function confirmarCode($application, $code)
   {
      $xml_post_string = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:tem="http://tempuri.org/">
      <soapenv:Header/>
      <soapenv:Body>
         <tem:ExecuteXMLString>
            <!--Optional:-->
            <tem:request>
                  <![CDATA[
               <DCRequest xmlns="http://transunion.com/dc/extsvc">
                     <Authentication type="OnDemand">
                        <UserId>' . $this->userTransUnion . '</UserId>
                        <Password>' . $this->passTransUnion . '</Password>
                     </Authentication>
                     ' . $this->createRequestInfoFlow("Send", "PinVerification_OTPInput", $application) . '
                     <Fields>
                      <Field key="PinNumber">' . $code . '</Field>	
         				   <Field key="Action">Continue</Field>
                     </Fields>	
                  </DCRequest>
               ]]>
            </tem:request>
         </tem:ExecuteXMLString>
      </soapenv:Body>
      </soapenv:Envelope>';
      //echo $xml_post_string;
      $headers = array(
         "Content-type: text/xml; charset=\"utf-8\"",
         "Accept: text/xml",
         "Cache-Control: no-cache",
         'SOAPAction: "http://tempuri.org/IExternalSolutionExecution/ExecuteXMLString"',
         "Pragma: no-cache",
         "Content-lenght: " . strlen($xml_post_string)
      );

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($ch, CURLOPT_URL,  $this->urlTransUnion);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      //curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
      //curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
      curl_setopt($ch, CURLOPT_TIMEOUT, 10);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

      $response = curl_exec($ch);
      curl_close($ch);
      //echo  $response ;
      $doc = new DOMDocument();
      $doc->loadXML($response);
      $doc->save('confirmarCodeResponse.xml');
   }

   private function createRequestInfoFlow($executionMode, $queuestep = null, $application = null)
   {
      if ($application !== null) {
         return "<RequestInfo>
                     <QueueName>" . $queuestep . "</QueueName>
                     <ApplicationId>" . $application . "</ApplicationId>
                     <ExecutionMode>" . $executionMode . "</ExecutionMode>
                  </RequestInfo>";
      }

      if ($this->ambiente === 0) {
         return "<RequestInfo>
                  <SolutionSetId>156</SolutionSetId>
                  <SolutionSetVersion>250</SolutionSetVersion>
                  <ExecutionMode>" . $executionMode . "</ExecutionMode>
              </RequestInfo>";
      }

      return "<RequestInfo>
                     <SolutionSetId>156</SolutionSetId>
                     <ExecutelatestVersion>true</ExecutelatestVersion>
                     <ExecutionMode>" . $executionMode . "</ExecutionMode>
               </RequestInfo>";
   }
}
