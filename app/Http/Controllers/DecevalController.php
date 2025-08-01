<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use DOMDocument;
use Exception;
use SimpleXMLElement;

class DecevalController extends Controller
{
   public $ambiente = 0; // 0 = pruebas, 1 = produccion

   //public $userTU = 'IDV_ABC.GM1';
   //public $passTU = '1024.TUOpRzT25sC';
   public $mod = 'pruebas'; //
   public $userTU = 'IDV_ABC.CH2';
   public $passTU = '2025.Gmm;0p[yTU16';
   public $urlTU = "https://www.transuniondecisioncentreuat.com.mx/TU.DE.PONT_LATAM/";
   //datos produccion
   //public $mod = 'produccion';
   //public $urlTU = "https://www.transuniondecisioncentre.com.mx/TU.DE.PONT_LATAM/";
   //public $userTU = 'GO3+FE_Ckcomercializadora_PROD_38903032';
   //public $passTU = '15.LrtU]}QpeWX';


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
      try {
         $token = $this->GetAccessToken($this->urlTU, $this->userTU, $this->passTU);
         \Log::info("Token retrieved successfully");

         $applicationId  = $request->application;
         $nombrelibranza = $request->nombrelibranza;
         $step = $request->step ?? 'initial';
         $phoneList = '';
         $mailList = '';
         $OTPCode = "";

         // Manejo del flujo OTP
        if ($step === 'phone-selection' || $step === 'otp-verification' || $step === 'resend-otp' || $step === 'bypass-otp') {
            return $this->handleOTPProcess($request, $token, $applicationId, $nombrelibranza);
        }

         // Manejo de respuestas del cuestionario
         if ($request->step === 'answerExam' && $request->validation === 'ShowExam') {
            $answers = $request->answers ?? [];
            $examData = $request->examData;

            if (empty($answers)) {
               throw new \Exception('No se recibieron respuestas del cuestionario');
            }

            $response = $this->sendExamAnswers($this->urlTU, $token, $applicationId, $examData, $answers);
            $data = json_decode($response, true);

            if (!isset($data['Status'])) {
               throw new \Exception('Respuesta inválida del servicio');
            }

            if ($data['Status'] === 'Success' && $data['ResponseInfo']['CurrentQueue'] === 'WaitDocToSignQueue') {
               $applicationId3 = $data['ResponseInfo']['ApplicationId'];

               $fileData = [
                  'Description' => 'Document',
                  'FileName' => $nombrelibranza . '.pdf',
                  'Note' => $nombrelibranza
               ];

               $response4 = $this->uploadDocumentAndGetId($this->urlTU, $token, $applicationId3, $fileData);

               if (!$response4['success']) {
                  throw new \Exception('Error al subir documento: ' . ($response4['error'] ?? 'Desconocido'));
               }

               $documentId = $response4['documentId'];
               \Log::info("Document ID: " . $documentId);

               $filePath = storage_path('app/public/pdfs/sinfirmar/' . $nombrelibranza . '.pdf');

               if (!file_exists($filePath)) {
                  throw new \Exception('El archivo a firmar no existe en la ruta especificada');
               }

               $response5 = $this->uploadBinaryDocumentToTransUnion($this->urlTU, $token, $documentId, $filePath);
               $data5 = json_decode($response5, true);

               if (!isset($data5['Message']) || $data5['Message'] !== 'Document uploaded successfully') {
                  throw new \Exception('Error al subir documento binario: ' . ($data5['Message'] ?? 'Desconocido'));
               }

               $response6 = $this->postToDecisionCentre($this->urlTU, $token, $applicationId3, $documentId);
               $data6 = json_decode($response6, true);

               if ($data6['Status'] !== 'Success') {
                  throw new \Exception('Error en el centro de decisiones: ' . ($data6['Message'] ?? 'Desconocido'));
               }

               $idNumber = $data6['Fields']['Applicants']['Applicant'][0]['IdNumber'];
               \Log::info("IdNumber: " . $idNumber);
               $downloadURL = $data6['Fields']['Applicants']['Applicant'][0]['DSElectronicSignatureCOLData']['Response']['DownLoadURL'][0];
               \Log::info("Download URL: " . $downloadURL);
               $nombreArchivo = $nombrelibranza . ".pdf";

               try {
                  $contenidoPDF = file_get_contents($downloadURL);
                  if ($contenidoPDF === false) {
                     throw new \Exception("Error al descargar el PDF desde la URL proporcionada.");
                  }
                  \Storage::disk('public')->put('pdfs/firmados/' . $nombreArchivo, $contenidoPDF);
               } catch (\Exception $e) {
                  \Log::error("Error al guardar el PDF", [
                     'error' => $e->getMessage(),
                     'url' => $downloadURL,
                     'nombre_archivo' => $nombreArchivo
                  ]);
                  throw $e;
               }

               return view("deceval/consulta")->with([
                  "error_code" => '',
                  "documentName" => $nombrelibranza,
                  "application" => $applicationId,
                  "phoneList" => $phoneList,
                  "phoneListStr" => $request->phoneListStr ?? '',
                  "mailList" => $mailList,
                  "mailListStr" => $request->mailListStr ?? '',
                  "step" => "firmado",
                  "code" => $request->code,
                  "validation" => '',
                  "confirmCode" => "confirmCode",
                  "ambiente" => $this->ambiente
               ]);
            } else {
               // Manejar error en las respuestas
               $errorMsg = $this->getExamErrorMessage($data);
               return view("deceval/consulta")->with([
                  "error_code" => $errorMsg,
                  "documentName" => $nombrelibranza,
                  "application" => $applicationId,
                  "phoneList" => $phoneList,
                  "phoneListStr" => $request->phoneListStr,
                  "mailList" => $mailList,
                  "mailListStr" => $request->mailListStr,
                  "step" => "error",
                  "OTPCode" => $OTPCode,
                  "validation" => '',
                  "confirmCode" => "confirmCode",
                  "ambiente" => $this->ambiente
               ]);
            }
         }

         if ($request->validation === 'PhoneSelection') {
            $phoneList = explode(",", $request->phoneListStr);
            $mailList = explode(",", $request->mailListStr);
            $phoneNumber = $request->phone;
            $mailNumber = $request->email;

            $OTPCode = "";
            // Se genera codigo
            if ($request->step === 'initial') {
               $response2 = $this->sendPhoneSelectionRequest($this->urlTU, $token, $applicationId, 'SMS', $phoneNumber, $mailNumber, 'Mobile');
               $data2 = json_decode($response2, true);

               if ($data2['Status'] === 'Success' && $data2['ResponseInfo']['CurrentQueue'] === 'PinVerification_OTPInput') {

                  $OTPCode = $data2['Fields']['OTPCode'];
                  \Log::info("OTP Code: " . $OTPCode);
               }

               return view("deceval/consulta")->with([
                  "error_code" => '',
                  "documentName" => $nombrelibranza,
                  "application" => $applicationId,
                  "phoneList" => $phoneList,
                  "phoneListStr" => $request->phoneListStr,
                  "mailList" => $mailList,
                  "mailListStr" => $request->mailListStr,
                  "step" => "confirmCode",
                  "validation" => '',
                  "OTPCode" => $OTPCode,
                  "confirmCode" => "confirmCode",
                  "ambiente" => $this->ambiente
               ]);
            }
         }

         if ($request->step === 'confirmCode') {
            \Log::info("Document ID: jjjj");

            $response3 = $this->verifyPinWithTransunion($this->urlTU, $token, $applicationId, $request->code);
            $data3 = json_decode($response3, true);

            if ($data3['Status'] === 'Success' && $data3['ResponseInfo']['CurrentQueue'] === 'WaitDocToSignQueue') {
               $applicationId3 = $data3['ResponseInfo']['ApplicationId'];

               $fileData = [
                  'Description' => 'Document',
                  'FileName' => $nombrelibranza . '.pdf',
                  'Note' => $nombrelibranza
               ];

               $response4 = $this->uploadDocumentAndGetId($this->urlTU, $token, $applicationId3, $fileData);

               if ($response4['success']) {

                  $documentId = $response4['documentId'];
                  \Log::info("Document ID: " . $documentId);

                  $filePath = storage_path('app/public/pdfs/sinfirmar/' . $nombrelibranza . '.pdf');

                  $response5 = $this->uploadBinaryDocumentToTransUnion($this->urlTU, $token, $documentId, $filePath);
                  $data5 = json_decode($response5, true);
                  if ($data5['Message'] === 'Document uploaded successfully') {

                     $response6 = $this->postToDecisionCentre($this->urlTU, $token, $applicationId3, $documentId);
                     $data6 = json_decode($response6, true);

                     if ($data6['Status'] === 'Success') {
                        $idNumber = $data6['Fields']['Applicants']['Applicant'][0]['IdNumber'];
                        \Log::info("IdNumber: " . $idNumber);
                        $downloadURL = $data6['Fields']['Applicants']['Applicant'][0]['DSElectronicSignatureCOLData']['Response']['DownLoadURL'][0];
                        \Log::info("Download URL: " . $downloadURL);
                        $nombreArchivo = $nombrelibranza . ".pdf";
                        $carpetaDestino = 'pdfs';

                        try {
                           $contenidoPDF = file_get_contents($downloadURL);
                           if ($contenidoPDF !== false) {
                              /*\Storage::disk('local')->put($carpetaDestino . '/' . $nombreArchivo, $contenidoPDF);

                           $rutaGuardada = storage_path('app/' . $carpetaDestino . '/' . $nombreArchivo);
                           \Log::info("PDF guardado correctamente en: " . $rutaGuardada);*/
                              \Storage::disk('public')->put('pdfs/firmados/' . $nombreArchivo, $contenidoPDF);
                           } else {
                              throw new \Exception("Error al descargar el PDF desde la URL proporcionada.");
                           }
                        } catch (\Exception $e) {
                           \Log::error("Error al guardar el PDF", [
                              'error' => $e->getMessage(),
                              'url' => $downloadURL,
                              'nombre_archivo' => $nombreArchivo
                           ]);
                           throw $e;
                        }
                     }

                     $link = asset($nombreArchivo);


                     return view("deceval/consulta")->with([
                        "error_code" => '',
                        "documentName" => $nombrelibranza,
                        "application" => $applicationId,
                        "phoneList" => $phoneList,
                        "phoneListStr" => $request->phoneListStr ?? '',
                        "mailList" => $mailList,
                        "mailListStr" => $request->mailListStr ?? '',
                        "step" => "firmado",
                        "code" => $request->code,
                        "validation" => '',
                        "confirmCode" => "confirmCode",
                        "ambiente" => $this->ambiente
                     ]);
                  } else {
                     \Log::error("Error al subir el documento: " . $data5['Message']);
                  }
               }
            } else {
               \Log::error("Error en la verificación del PIN: FAIL");
               return view("deceval/consulta")->with([
                  "error_code" => '',
                  "documentName" => $nombrelibranza,
                  "application" => $applicationId,
                  "phoneList" => $phoneList,
                  "phoneListStr" => $request->phoneListStr,
                  "mailList" => $mailList,
                  "mailListStr" => $request->mailListStr,
                  "step" => "confirmCode",
                  "OTPCode" => $OTPCode,
                  "validation" => '',
                  "confirmCode" => "confirmCode",
                  "ambiente" => $this->ambiente
               ]);
            }
         }
      } catch (\Exception $e) {
         \Log::error("Error en el proceso de firma", [
            'exception' => $e->getMessage(),
            'request' => $request->all()
         ]);

         return view("deceval/consulta")->with([
            "error" => "Error en el proceso: " . $e->getMessage(),
            "documentName" => $nombrelibranza ?? '',
            "step" => "error",
            "validation" => '',
            "ambiente" => $this->ambiente
         ]);
      }
   }


   //VIENE DE CIFIN en la creacion del credito
   public function consultar(Request $request)
   {
      // Validación inicial y preparación de datos
      $originalDate = $request->expeditionDate;
      $expeditionDate = date("d/m/Y", strtotime($originalDate));
      $datosGirador = (object) $request->girador;
      $recentPhone = $datosGirador->telefono;
      $cedula = $datosGirador->numeroDocumento;
      $lastName = $datosGirador->primerApellido;
      $nombrelibranza = $request->numPagareEntidad;
      $step = "initial"; // Valor por defecto

      $phoneListStr = '';
      $mailListStr = '';

      try {
         \Log::info("Iniciando proceso Deceval", ['documento' => $nombrelibranza, 'cedula' => $cedula]);

         // Obtener token de acceso
         $token = $this->GetAccessToken($this->urlTU, $this->userTU, $this->passTU);
         if (!$token) {
            throw new \Exception('No se pudo obtener el token de acceso');
         }
         \Log::info("Token obtenido correctamente");

         // Configurar datos del solicitante
         $codCuestionario = $this->mod === 'pruebas' ? "7487" : "7563";
         $applicantData = [
            "IdNumber" => $cedula,
            "IdType" => $request->otorganteTipoId,
            "IdExpeditionDate" => $expeditionDate,
            "RecentPhoneNumber" => $recentPhone,
            "LastName" => $lastName,
            "CountDigitOTP" => "6",
            "MessageOTP" => "<MessageOTP>IDV Go3 Código de verificación de identidad - OTP: OTPcode.</MessageOTP>",
            "MaxTimeOTP_Seconds" => "3600",
            "MaxAttempOTP" => "2",
            "MaxRetryOTP" => "2",
            "MessageOTPVoice" => "<MessageOTPVoice>IDV Go3 Voz Código de verificación de identidad - OTP: OTPcode.</MessageOTPVoice>",
            "MessageOTPEmail" => "Código de verificación de identidad - OTP: OTPcode.</ MessageOTPEmail>",
            "NumPhonesToShow" => "3",
            "CuestionarioRiesgoMedio" => $codCuestionario,
         ];

         // Enviar solicitud al centro de decisiones
         $response = $this->sendDecisionCentreRequest($this->urlTU, $token, $applicantData);
         $data = json_decode($response, true);

         \Log::debug('Respuesta completa de TransUnion', [
            'status' => $data['Status'] ?? 'none',
            'currentQueue' => $data['ResponseInfo']['CurrentQueue'] ?? 'none',
            'decision' => $data['Decision'] ?? 'none'
         ]);

         // Verificación básica de respuesta
         if (!isset($data['Status'])) {
            throw new \Exception('Respuesta inválida del servicio');
         }

         // 1. Verificar DCResponse/Status sea Success
         if ($data['Status'] !== 'Success') {
            throw new \Exception('El procesamiento en DecisionEdge no fue exitoso');
         }

         // 2. Verificar ResponseInfo/ApplicationId existe (eliminar verificación de Authentication)
         /*$authStatus = $data['Authentication']['Status'] ?? null;
        if (!in_array($authStatus, ['Success', 'PasswordHasExpired'])) {
            throw new \Exception('Autenticación fallida con el servicio');
        }*/
         if (!isset($data['ResponseInfo']['ApplicationId'])) {
            throw new \Exception('No se recibió el ApplicationId en la respuesta');
         }
         $applicationId = $data['ResponseInfo']['ApplicationId'];

         // 3. Verificar VelocityCheckStatus (Bloqueo por múltiples intentos - 2A)
         if (isset($data['VelocityCheckReason']['Reasons'])) {
            $reasons = $data['VelocityCheckReason']['Reasons'];
            if (is_array($reasons)) {
               foreach ($reasons as $reason) {
                  if (isset($reason['Code']) && $reason['Code'] == '101') {
                     \Log::warning('Usuario bloqueado por seguridad - Código 101');
                     return view("deceval/consulta")->with([
                        "error" => 'Usuario bloqueado por seguridad',
                        "documentName" => $nombrelibranza,
                        "application" => $applicationId,
                        "step" => "blocked",
                        "validation" => 'Blocked',
                        "phoneListStr" => $phoneListStr,
                        "mailListStr" => $mailListStr,
                        "ambiente" => $this->ambiente
                     ]);
                  }
               }
            }
         }

         // 5. Verificar IDVReasonsCode (Estado de documento inválido - 2B)
         if (isset($data['IDVReasonsCode']['Reason'])) {
            $reason = $data['IDVReasonsCode']['Reason'];
            if (isset($reason['Code']) && $reason['Code'] == '105') {
               \Log::warning('Validación fallida por estado de documento - Código 105');
               return view("deceval/consulta")->with([
                  "error" => 'Verificación de identidad fallida',
                  "error_type" => "invalid_document",
                  "documentName" => $nombrelibranza,
                  "application" => $applicationId,
                  "step" => "error",
                  "validation" => '',
                  "phoneListStr" => $phoneListStr,
                  "mailListStr" => $mailListStr,
                  "ambiente" => $this->ambiente
               ]);
            }
         }

         // 6. Verificar IDVReasonsCode (No coincidencias requeridas - 2C)
         if (isset($data['IDVReasonsCode']['Reason'])) {
            $reason = $data['IDVReasonsCode']['Reason'];
            if (isset($reason['Code']) && $reason['Code'] == '103') {
               \Log::warning('Validación fallida por no coincidencias requeridas - Código 103');
               return view("deceval/consulta")->with([
                  "error" => 'Verificación de identidad fallida',
                  "error_type" => "no_matches",
                  "documentName" => $nombrelibranza,
                  "application" => $applicationId,
                  "step" => "error",
                  "validation" => '',
                  "phoneListStr" => $phoneListStr,
                  "mailListStr" => $mailListStr,
                  "ambiente" => $this->ambiente
               ]);
            }
         }

         // 7. Verificar IDMReasonCodes (Validación global fallida - 700)
         if (isset($data['IDMReasonCodes']['Reason'])) {
            $reason = $data['IDMReasonCodes']['Reason'];
            if (isset($reason['Code']) && $reason['Code'] == '700') {
               \Log::warning('Validación de identidad fallida - Código 700');
               return view("deceval/consulta")->with([
                  "error" => 'Verificación de identidad fallida',
                  "error_type" => "identity_verification",
                  "documentName" => $nombrelibranza,
                  "application" => $applicationId,
                  "step" => "error",
                  "validation" => '',
                  "phoneListStr" => $phoneListStr,
                  "mailListStr" => $mailListStr,
                  "ambiente" => $this->ambiente
               ]);
            }
         }

         if ($data['Status'] === 'Success') {

            if (isset($data['Decision']) && $data['Decision'] === 'Fail') {
               $errorMsg = "Verificación de identidad fallida";
               \Log::warning('Fallo en verificación de identidad', ['response' => $data]);

               return view("deceval/consulta")->with([
                  "error" => $errorMsg,
                  "error_type" => "identity_verification",
                  "documentName" => $nombrelibranza,
                  "step" => "error",
                  "phoneListStr" => $request->phoneListStr ?? '',
                  "mailListStr" => $request->mailListStr ?? '',
                  "validation" => '',
                  "application" => $data['ResponseInfo']['ApplicationId'] ?? null,
                  "ambiente" => $this->ambiente
               ]);
            }


            // Verificar si hay bloqueo en CIFIN
            if (isset($data['Decision']) && $data['Decision'] === 'Fail') {
               $errorMsg = $this->getCifinErrorMessage($data);
               \Log::warning('Bloqueo detectado en CIFIN', ['mensaje' => $errorMsg]);

               return view("deceval/consulta")->with([
                  "error" => $errorMsg,
                  "documentName" => $nombrelibranza,
                  "application" => $data['ResponseInfo']['ApplicationId'] ?? null,
                  "step" => "blocked",
                  "validation" => 'Blocked',
                  "ambiente" => $this->ambiente
               ]);
            }

            // Manejar flujo normal según CurrentQueue
            if (!isset($data['ResponseInfo']['CurrentQueue']) || $data['ResponseInfo']['CurrentQueue'] === 'none') {
               \Log::warning('CurrentQueue no definido o es "none"', ['response' => $data]);
               return view("deceval/consulta")->with([
                  "error" => '“Verificación de identidad fallida”',
                  "documentName" => $nombrelibranza,
                  "step" => "error",
                  "phoneListStr" => '', // Asegurar que siempre esté definida
                  "mailListStr" => '',  // Asegurar que siempre esté definida
                  "validation" => '',
                  "application" => $data['ResponseInfo']['ApplicationId'],
                  "ambiente" => $this->ambiente
               ]);
            }

            switch ($data['ResponseInfo']['CurrentQueue']) {
               case 'PhoneSelection':
                  $phoneList = array_filter(explode(',', $data['Fields']['MobilePhoneList'] ?? ''));
                  $mailList = array_filter(explode(',', $data['Fields']['EmailList'] ?? ''));

                  return view("deceval/consulta")->with([
                     "error_code" => '',
                     "documentName" => $nombrelibranza,
                     "application" => $data['ResponseInfo']['ApplicationId'],
                     "phoneList" => $phoneList,
                     "phoneListStr" => implode(', ', $phoneList),
                     "mailList" => $mailList,
                     "mailListStr" => implode(', ', $mailList),
                     "step" => $step,
                     "confirmCode" => "",
                     "validation" => 'PhoneSelection',
                     "ambiente" => $this->ambiente
                  ]);
               case 'ShowExam':
                  $examData = $data['Fields']['ExamData'] ?? '';
                  if (empty($examData)) {
                     throw new \Exception('Datos de examen no recibidos');
                  }

                  // Parsear XML con manejo de errores
                  libxml_use_internal_errors(true);
                  $xml = simplexml_load_string($examData);

                  if ($xml === false) {
                     $errors = libxml_get_errors();
                     libxml_clear_errors();
                     $errorMessages = array_map(function ($error) {
                        return trim($error->message);
                     }, $errors);

                     throw new \Exception('Error al parsear XML del examen: ' . implode(', ', $errorMessages));
                  }

                  // Inicializar todas las variables requeridas por la vista
                  $phoneList = [];
                  $phoneListStr = '';
                  $mailList = [];
                  $mailListStr = '';

                  return view("deceval/consulta")->with([
                     "error_code" => '',
                     "documentName" => $nombrelibranza,
                     "application" => $data['ResponseInfo']['ApplicationId'],
                     "exam" => $xml,
                     "examData" => $examData, // Pasamos el XML original para reconstruir las respuestas
                     "step" => $step,
                     "confirmCode" => "",
                     "validation" => 'ShowExam',
                     "phoneList" => $phoneList,
                     "phoneListStr" => $phoneListStr,
                     "mailList" => $mailList,
                     "mailListStr" => $mailListStr,
                     "ambiente" => $this->ambiente
                  ]);

               default:
                  \Log::warning('CurrentQueue no manejado', ['response' => $data]);
                  return view("deceval/consulta")->with([
                     "error" => 'Estado no manejado: ' . $data['ResponseInfo']['CurrentQueue'],
                     "documentName" => $nombrelibranza,
                     "step" => "error",
                     "application" => $data['ResponseInfo']['ApplicationId'],
                     "validation" => '',
                     "ambiente" => $this->ambiente
                  ]);
            }
         } else {
            // Manejar errores explícitos del servicio
            $errorMsg = $data['Message'] ?? 'Error desconocido en el servicio';
            throw new \Exception($errorMsg);
         }
      } catch (\Exception $e) {
         \Log::error("Error en el proceso Deceval", [
            'exception' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'request' => $request->all()
         ]);

         return view("deceval/consulta")->with([
            "error" => "Ocurrió un error: " . $e->getMessage(),
            "documentName" => $nombrelibranza,
            "step" => "error",
            "validation" => '',
            "confirmCode" => "",
            "ambiente" => $this->ambiente
         ]);
      }
   }
   private function getExamErrorMessage($data)
   {
      if (isset($data['IDAReasons']['Reason'])) {
         $reason = $data['IDAReasons']['Reason'];
         switch ($reason['Code']) {
            case '401':
               return 'Falló el cuestionario. Por favor verifique sus respuestas.';
            case '403':
               return 'Usuario bloqueado por superar intentos fallidos. Contacte al soporte.';
            case '404':
               return 'No hay suficiente información para generar el cuestionario.';
            case '405':
            case '406':
               return 'Error técnico, por favor contacte al soporte.';
            case '407':
               return 'Tiempo excedido para responder el cuestionario.';
         }
      }
      return 'Error al validar las respuestas del cuestionario.';
   }

   // Método auxiliar para obtener mensajes de error de CIFIN
   private function getCifinErrorMessage(array $data): string
   {
      $defaultMessage = 'Bloqueo en CIFIN por exceso de consultas';

      try {
         if (isset($data['Fields']['Applicants']['Applicant'][0]['DSConfrontaCOLData']['Response']['COLConfrontaGetQuestionnaire']['Response']['COLConfrontaGetQuestionnaire']['descripcionRespuesta'])) {
            return $data['Fields']['Applicants']['Applicant'][0]['DSConfrontaCOLData']['Response']['COLConfrontaGetQuestionnaire']['Response']['COLConfrontaGetQuestionnaire']['descripcionRespuesta'];
         }
      } catch (\Exception $e) {
         \Log::warning('Error al extraer mensaje CIFIN', ['error' => $e->getMessage()]);
      }

      return $defaultMessage;
   }
   public function consulta()
   {
      return view('deceval/consulta')->with();
   }

   //metodo de libranza:
   protected function GetAccessToken($urlTU, $user, $pass)
   {
      $curl = curl_init();

      curl_setopt_array($curl, [
         CURLOPT_URL => $urlTU . 'Token',
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => '',
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 0,
         CURLOPT_FOLLOWLOCATION => true,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_CUSTOMREQUEST => 'POST',
         CURLOPT_POSTFIELDS => http_build_query([
            'grant_type' => 'password',
            'username' => $user,
            'password' => $pass
         ]),
         CURLOPT_HTTPHEADER => [
            'Content-Type: application/x-www-form-urlencoded',
         ],
      ]);

      $response = curl_exec($curl);

      // Verificar si hubo errores en la solicitud cURL
      if (curl_errno($curl)) {
         throw new Exception('Error en cURL: ' . curl_error($curl));
      }

      // Obtener el código de estado HTTP
      $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
      if ($httpCode !== 200) {
         throw new Exception('Error en la solicitud: Código HTTP ' . $httpCode);
      }

      curl_close($curl);

      // Decodificar la respuesta JSON
      $data = json_decode($response, true);
      if (json_last_error() !== JSON_ERROR_NONE) {
         throw new Exception('Error al decodificar la respuesta JSON: ' . json_last_error_msg());
      }

      // Verificar si el token está presente en la respuesta
      if (!isset($data['access_token'])) {
         throw new Exception('No se encontró el access_token en la respuesta');
      }

      return $data['access_token'];
   }

   protected function handleOTPProcess($request, $token, $applicationId, $nombrelibranza)
   {
      $step = $request->step;

      if ($step === 'phone-selection') {
         // Obtener métodos disponibles
         $response = $this->getCurrentApplicationState($token, $applicationId);
         $data = json_decode($response, true);

         return view('deceval.consulta', [
            'step' => 'phone-selection',
            'phoneList' => explode(',', $data['Fields']['MobilePhoneList'] ?? ''),
            'landLineList' => explode(',', $data['Fields']['LandLineList'] ?? ''),
            'emailList' => explode(',', $data['Fields']['EmailList'] ?? ''),
            'application' => $applicationId,
            'documentName' => $nombrelibranza,
            'validation' => 'PhoneSelection'
         ]);
      } elseif ($step === 'otp-verification') {
         // Enviar OTP
         $response = $this->sendOTPRequest(
            $token,
            $applicationId,
            $request->validation_method,
            $request->selected_phone,
            $request->selected_email,
            $request->phone_type
         );

         $data = json_decode($response, true);

         return view('deceval.consulta', [
            'step' => 'otp-verification',
            'validationMethod' => $request->validation_method,
            'selectedPhone' => $request->selected_phone,
            'selectedEmail' => $request->selected_email,
            'application' => $applicationId,
            'documentName' => $nombrelibranza,
            'validation' => 'OTPVerification',
            'OTPCode' => $data['Fields']['OTPCode'] ?? '' // Solo para pruebas
         ]);
      } elseif ($step === 'resend-otp') {
         // Primero volver a PhoneSelection
         $response = $this->resendOTPRequest($token, $applicationId);
         $data = json_decode($response, true);

         if ($data['Status'] === 'Success') {
            // Luego enviar nuevo OTP
            return $this->handleOTPProcess(
               new Request([
                  'step' => 'otp-verification',
                  'validation_method' => $request->validation_method,
                  'selected_phone' => $request->selected_phone,
                  'selected_email' => $request->selected_email,
                  'phone_type' => $request->phone_type,
                  'application' => $applicationId,
                  'nombrelibranza' => $nombrelibranza
               ]),
               $token,
               $applicationId,
               $nombrelibranza
            );
         }
      } elseif ($step === 'bypass-otp') {
         // Bypass OTP a preguntas de seguridad
         $response = $this->bypassOTPRequest($token, $applicationId);
         $data = json_decode($response, true);

         if ($data['Status'] === 'Success' && $data['ResponseInfo']['CurrentQueue'] === 'ShowExam') {
            return $this->showChallengeQuestions($data, $applicationId, $nombrelibranza);
         }
      } elseif ($step === 'verify-otp') {
         // Verificar código OTP
         $response = $this->verifyOTPRequest($token, $applicationId, $request->code);
         $data = json_decode($response, true);

         if ($data['Status'] === 'Success' && $data['ResponseInfo']['CurrentQueue'] === 'WaitDocToSignQueue') {
            // Proceso exitoso, proceder con firma
            return $this->handleDocumentSigning($token, $applicationId, $nombrelibranza);
         } else {
            // Error en verificación
            return view('deceval.consulta', [
               'step' => 'otp-verification',
               'validationMethod' => $request->validation_method,
               'selectedPhone' => $request->selected_phone,
               'selectedEmail' => $request->selected_email,
               'application' => $applicationId,
               'documentName' => $nombrelibranza,
               'validation' => 'OTPVerification',
               'error' => $this->getOTPErrorMessage($data)
            ]);
         }
      }
   }

   protected function sendOTPRequest($token, $applicationId, $method, $phone, $email, $phoneType)
   {
      $postFields = [
         "Fields" => [
            "ValidationMethod" => $method,
            "SelectedPhoneNumber" => $phone,
            "SelectedEmail" => $email,
            "PhoneType" => $phoneType
         ]
      ];

      return $this->makeRequest(
         $token,
         "applications/$applicationId/queues/PhoneSelection",
         $postFields
      );
   }

   protected function verifyOTPRequest($token, $applicationId, $otpCode)
   {
      $postFields = [
         "Fields" => [
            "Action" => "Continue",
            "PinNumber" => $otpCode
         ]
      ];

      return $this->makeRequest(
         $token,
         "applications/$applicationId/queues/PinVerification_OTPInput",
         $postFields
      );
   }

   protected function resendOTPRequest($token, $applicationId)
   {
      $postFields = [
         "Fields" => [
            "Action" => "ResendOTP"
         ]
      ];

      return $this->makeRequest(
         $token,
         "applications/$applicationId/queues/PinVerification_OTPInput",
         $postFields
      );
   }

   protected function bypassOTPRequest($token, $applicationId)
   {
      $postFields = [
         "Fields" => [
            "Action" => "Bypass"
         ]
      ];

      return $this->makeRequest(
         $token,
         "applications/$applicationId/queues/PinVerification_OTPInput",
         $postFields
      );
   }

   protected function getOTPErrorMessage($responseData)
   {
      if (isset($responseData['OTPReasons']['Reason'])) {
         $reason = $responseData['OTPReasons']['Reason'];
         switch ($reason['Code']) {
            case '301':
               return 'Código OTP expirado. Por favor solicite uno nuevo.';
            case '302':
               return 'Código OTP incorrecto. Intente nuevamente.';
            case '303':
               return 'Ha excedido el número máximo de intentos.';
            default:
               return $reason['Description'] ?? 'Error al verificar OTP';
         }
      }
      return $responseData['Fields']['ReturnMessage'] ?? 'Error desconocido al verificar OTP';
   }

   protected function sendExamAnswers($urlTU, $token, $applicationId, $examData, $answers)
   {
      try {
         // 1. Validar datos de entrada
         if (empty($examData)) {
            throw new \Exception('Los datos del examen están vacíos');
         }
         if (empty($answers)) {
            throw new \Exception('No se recibieron respuestas');
         }

         // 2. Decodificar XML si está escapado
         if (strpos($examData, '&lt;') !== false) {
            $examData = html_entity_decode($examData, ENT_QUOTES | ENT_XML1, 'UTF-8');
         }

         // 3. Cargar y validar XML
         libxml_use_internal_errors(true);
         $xml = simplexml_load_string($examData);
         if ($xml === false) {
            $errors = libxml_get_errors();
            libxml_clear_errors();
            throw new \Exception('XML inválido: ' . implode(', ', array_map(function ($e) {
               return trim($e->message);
            }, $errors)));
         }

         // 4. Procesar respuestas - VERSIÓN CORREGIDA
         foreach ($xml->Questions->Question as $question) {
            $questionId = (string)$question['QuestionId'];

            if (isset($answers[$questionId])) {
               $selectedAnswer = $answers[$questionId];

               foreach ($question->Answer as $answer) {
                  // Comparar el texto de la respuesta después de trim() y normalización
                  $answerText = trim((string)$answer);
                  $isSelected = ($answerText === trim($selectedAnswer));

                  // Actualizar el atributo IsSelected
                  $answer['IsSelected'] = $isSelected ? 'true' : 'false';

                  // Para depuración
                  \Log::debug('Procesando respuesta', [
                     'questionId' => $questionId,
                     'answerText' => $answerText,
                     'selectedAnswer' => $selectedAnswer,
                     'isSelected' => $isSelected
                  ]);
               }
            }
         }

         // 5. Convertir a XML y validar estructura
         $answerData = $xml->asXML();
         if ($answerData === false) {
            throw new \Exception('Error al generar XML de respuestas');
         }

         // 6. Log del XML generado (para depuración)
         \Log::debug('XML generado con respuestas', ['xml' => $answerData]);

         // 7. Preparar solicitud a TransUnion
         $postFields = json_encode([
            "Fields" => [
               "AnswerData" => $answerData
            ]
         ]);

         $curl = curl_init();
         curl_setopt_array($curl, [
            CURLOPT_URL => $urlTU . "applications/{$applicationId}/queues/ShowExam",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $postFields,
            CURLOPT_HTTPHEADER => [
               'Content-Type: application/json',
               "Authorization: Bearer {$token}"
            ],
         ]);

         // 8. Ejecutar y validar respuesta
         $response = curl_exec($curl);
         $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

         if (curl_errno($curl)) {
            throw new \Exception('Error cURL: ' . curl_error($curl));
         }
         curl_close($curl);

         if ($httpCode !== 200) {
            throw new \Exception("Error HTTP {$httpCode} al enviar respuestas");
         }

         // 9. Validar estructura de respuesta
         $data = json_decode($response, true);
         if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Respuesta JSON inválida: ' . json_last_error_msg());
         }

         return $response;
      } catch (\Exception $e) {
         \Log::error("Error en sendExamAnswers", [
            'exception' => $e->getMessage(),
            'applicationId' => $applicationId,
            'examData' => substr($examData, 0, 200) . '...', // Log parcial para no saturar
            'answers' => $answers,
            'trace' => $e->getTraceAsString()
         ]);
         throw $e;
      }
   }

   protected function sendDecisionCentreRequest($urlTU, $token, $applicantData)
   {
      $curl = curl_init();

      // Preparar los campos de la solicitud
      $postFields = json_encode(array(
         "RequestInfo" => array(
            "SolutionSetId" => "156",
            "ExecuteLatestVersion" => ($this->mod === 'pruebas') ? "false" : "true",
         ) + ($this->mod === 'pruebas' ? ["SolutionSetVersion" => "284"] : []),
         "Fields" => array(
            "Applicants" => array(
               "Applicant" => $applicantData
            )
         )
      ));

      curl_setopt_array($curl, array(
         CURLOPT_URL => $urlTU . 'applications',
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => '',
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 0,
         CURLOPT_FOLLOWLOCATION => true,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_CUSTOMREQUEST => 'POST',
         CURLOPT_POSTFIELDS => $postFields,
         CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            "Authorization: Bearer {$token}"
         ),
      ));

      $response = curl_exec($curl);
      curl_close($curl);

      return $response;
   }

   protected function sendPhoneSelectionRequest($urlTU, $token, $applicationId, $validationMethod, $phoneNumber, $email, $phoneType)
   {
      $curl = curl_init();

      $postFields = json_encode(array(
         "Fields" => array(
            "ValidationMethod" => $validationMethod,
            "SelectedPhoneNumber" => $phoneNumber,
            "SelectedEmail" => $email,
            "PhoneType" => $phoneType
         )
      ));

      curl_setopt_array($curl, array(
         CURLOPT_URL => $urlTU . "applications/$applicationId/queues/PhoneSelection",
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => '',
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 0,
         CURLOPT_FOLLOWLOCATION => true,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_CUSTOMREQUEST => 'POST',
         CURLOPT_POSTFIELDS => $postFields,
         CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            "Authorization: Bearer {$token}"
         ),
      ));

      $response = curl_exec($curl);
      curl_close($curl);

      return $response;
   }

   protected function verifyPinWithTransunion($urlTU, $token, $applicationId, $pinNumber)
   {
      $curl = curl_init();

      $postData = json_encode([
         'Fields' => [
            'Action' => 'Continue',
            'PinNumber' => $pinNumber
         ]
      ]);

      curl_setopt_array($curl, array(
         CURLOPT_URL => $urlTU . "applications/{$applicationId}/queues/PinVerification_OTPInput",
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => '',
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 0,
         CURLOPT_FOLLOWLOCATION => true,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_CUSTOMREQUEST => 'POST',
         CURLOPT_POSTFIELDS => $postData,
         CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            "Authorization: Bearer {$token}"
         ),
      ));

      $response = curl_exec($curl);
      $error = curl_error($curl);
      curl_close($curl);

      if ($error) {
         return ['error' => $error];
      }

      return $response;
   }

   protected function uploadDocumentAndGetId($urlTU, $token, $applicationId, $fileData)
   {
      $url = $urlTU . "applications/{$applicationId}/documents";

      $curl = curl_init();

      curl_setopt_array($curl, array(
         CURLOPT_URL => $url,
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => '',
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 0,
         CURLOPT_FOLLOWLOCATION => true,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_CUSTOMREQUEST => 'POST',
         CURLOPT_POSTFIELDS => json_encode($fileData),
         CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            "Authorization: Bearer {$token}"
         ),
         CURLOPT_HEADER => true
      ));

      $response = curl_exec($curl);
      $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
      $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
      $headers = substr($response, 0, $header_size);
      $body = substr($response, $header_size);

      curl_close($curl);

      // Validar código de estado
      if ($httpCode != 201) {
         return [
            'success' => false,
            'error' => "La solicitud falló con código HTTP {$httpCode}",
            'response' => $body
         ];
      }

      // Validar mensaje de éxito en el JSON
      $responseData = json_decode($body, true);
      if (!isset($responseData['Message']) || $responseData['Message'] !== 'Document created successfully') {
         return [
            'success' => false,
            'error' => 'El documento no se creó correctamente según la respuesta',
            'response' => $body
         ];
      }

      // Extraer DocumentID del header Location
      if (!preg_match('/Location:\s*(.*?)\s*\r\n/i', $headers, $matches)) {
         return [
            'success' => false,
            'error' => 'No se encontró el encabezado Location en la respuesta',
            'response' => $body
         ];
      }

      $location = trim($matches[1]);
      $parts = explode('/', $location);
      $documentId = end($parts);

      if (empty($documentId)) {
         return [
            'success' => false,
            'error' => 'No se pudo extraer el DocumentID de la URL',
            'location' => $location
         ];
      }

      return [
         'success' => true,
         'documentId' => $documentId,
         'response' => $body
      ];
   }

   protected function uploadBinaryDocumentToTransUnion($urlTU, $token, $documentId, $filePath)
   {
      // Verifica si el archivo existe
      if (!file_exists($filePath)) {
         throw new \Exception("El archivo no existe: " . $filePath);
      }

      // Lee el contenido del archivo en modo binario
      $fileContents = file_get_contents($filePath);

      $curl = curl_init();

      curl_setopt_array($curl, array(
         CURLOPT_URL => $urlTU . "documents/$documentId",
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => '',
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 0,
         CURLOPT_FOLLOWLOCATION => true,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_CUSTOMREQUEST => 'POST',
         CURLOPT_POSTFIELDS => $fileContents, // Envía el contenido binario
         CURLOPT_HTTPHEADER => array(
            'Content-Type: application/octet-stream', // Indica que es un flujo de bytes
            "Authorization: Bearer {$token}"
         ),
      ));

      $response = curl_exec($curl);
      $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
      curl_close($curl);

      /*return [
        'status_code' => $httpCode,
        'response' => $response
    ];*/

      return $response;
   }

   protected function postToDecisionCentre($urlTU, $token, $applicationId, $documentId)
   {
      $curl = curl_init();

      $postData = json_encode([
         'Fields' => [
            'DocumentIDs:' => $documentId
         ]
      ]);

      curl_setopt_array($curl, array(
         CURLOPT_URL => $urlTU . "applications/{$applicationId}/queues/WaitDocToSignQueue",
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => '',
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 0,
         CURLOPT_FOLLOWLOCATION => true,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_CUSTOMREQUEST => 'POST',
         CURLOPT_POSTFIELDS => $postData,
         CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            "Authorization: Bearer {$token}"
         ),
      ));

      $response = curl_exec($curl);
      curl_close($curl);

      return $response;
   }

   protected function consultaTransunion($urlTU, $token, $applicationId, $idNumber, $date)
   {
      $curl = curl_init();

      $postData = [
         "RequestInfo" => [
            "SolutionSetId" => "133",
            "ExecuteLatestVersion" => "true"
         ],
         "Fields" => [
            "Applicants" => [
               "Applicant" => [
                  "IdNumber" => $idNumber,
                  "Date" => $date,
                  "ApplicationId" => $applicationId
               ]
            ]
         ]
      ];

      curl_setopt_array($curl, array(
         CURLOPT_URL => $urlTU . '/applications',
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => '',
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 0,
         CURLOPT_FOLLOWLOCATION => true,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_CUSTOMREQUEST => 'POST',
         CURLOPT_POSTFIELDS => json_encode($postData),
         CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            "Authorization: Bearer {$token}"
         ),
      ));

      $response = curl_exec($curl);
      curl_close($curl);

      return $response;
   }

   //metodo de prueba:
   /* public function testService()
   {
      $idDocumentoPagare = "TEST";
      $otorganteTipoId = "1";
      $otorganteNumId = "12345678";
      $numPagareEntidad = "12345";
      $codigoDepositante = env('DECEVEL_CODIGO_DEPOSITANTE');
      $usuario = env('DECEVAL_USUARIO');
      $hoy = date("Y-m-d");
      $hora = date("H:i:s");
      $fecha = $hoy . 'T' . $hora;

      try {
         $response = $this->consultarPagares(
            $idDocumentoPagare,
            $otorganteTipoId,
            $otorganteNumId,
            $numPagareEntidad,
            $codigoDepositante,
            $fecha,
            $hora,
            $usuario
         );

         // Si el servicio responde, deberías poder obtener algunos nodos. Por ejemplo, intenta obtener 'codigoError'
         $codigoError = $response->getElementsByTagName('codigoError')->item(0);
         if ($codigoError) {
            return "Servicio Deceval responde: Código de error encontrado: " . $codigoError->nodeValue;
         } else {
            return "Servicio Deceval responde, pero no se encontró código de error, posiblemente está OK.";
         }
      } catch (\Exception $e) {
         return "Error al conectar con Deceval: " . $e->getMessage();
      }
   }*/
}
