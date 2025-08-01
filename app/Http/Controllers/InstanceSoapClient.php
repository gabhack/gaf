<?php
namespace App\Http\Controllers;
use SoapClient;

class InstanceSoapClient extends BaseSoapController implements InterfaceInstanceSoap
{
    public static function init(){
        $wsdlUrl = self::getWsdl();
        $soapClientOptions = [
            'stream_context'    => self::generateContext(),
            'encoding' => 'UTF-8',
            'verifypeer' => false,
            'verifyhost' => false,
            'soap_version' => SOAP_1_1,
            'trace' => 1,
            'exceptions' => 1,
            'connection_timeout' => 180,
            //
            'local_cert'        => base_path() . DIRECTORY_SEPARATOR . "app" . DIRECTORY_SEPARATOR . "Cert" . DIRECTORY_SEPARATOR . "certificate.pem",
            'login'             => '565746',
            'password'          => 'C89k/.'
        ];
        return new SoapClient($wsdlUrl, $soapClientOptions);
    }
}