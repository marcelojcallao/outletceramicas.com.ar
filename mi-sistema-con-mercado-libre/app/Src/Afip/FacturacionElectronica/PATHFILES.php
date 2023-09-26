<?php

namespace App\Src\Afip\FacturacionElectronica;

class PATHFILES
{
    const WSFE_PRODUCTION = __DIR__. DIRECTORY_SEPARATOR . 'Wsdl' . DIRECTORY_SEPARATOR . 'Production' . DIRECTORY_SEPARATOR . 'WSFE.wsdl';
    const WSFE_TESTING = __DIR__. DIRECTORY_SEPARATOR . 'Wsdl' . DIRECTORY_SEPARATOR . 'Testing' . DIRECTORY_SEPARATOR . 'WSFE.wsdl';
   
    const WSFECRED_PRODUCTION = __DIR__. DIRECTORY_SEPARATOR . 'Wsdl' . DIRECTORY_SEPARATOR . 'Production' . DIRECTORY_SEPARATOR . 'WSFECRED.wsdl';
    const WSFECRED_TESTING = __DIR__. DIRECTORY_SEPARATOR . 'Wsdl' . DIRECTORY_SEPARATOR . 'Testing' . DIRECTORY_SEPARATOR . 'WSFECRED.wsdl';
    
    const WS_SR_PADRON_A5_PRODUCTION = __DIR__. DIRECTORY_SEPARATOR . 'Wsdl' . DIRECTORY_SEPARATOR . 'Production' . DIRECTORY_SEPARATOR . 'WS_SR_PADRON_A5.wsdl';
    const WS_SR_PADRON_A5_TESTING = __DIR__. DIRECTORY_SEPARATOR . 'Wsdl' . DIRECTORY_SEPARATOR . 'Testing' . DIRECTORY_SEPARATOR . 'WS_SR_PADRON_A5.wsdl';
    
    const WS_SR_PADRON_A13_PRODUCTION = __DIR__. DIRECTORY_SEPARATOR . 'Wsdl' . DIRECTORY_SEPARATOR . 'Production' . DIRECTORY_SEPARATOR . 'WS_SR_PADRON_A13.wsdl';
    const WS_SR_PADRON_A13_TESTING = __DIR__. DIRECTORY_SEPARATOR . 'Wsdl' . DIRECTORY_SEPARATOR . 'Testing' . DIRECTORY_SEPARATOR . 'WS_SR_PADRON_A13.wsdl';

    // PRODUCTION//
    const PRODUCTION_CERTIFICATE = __DIR__.DIRECTORY_SEPARATOR.'Certificates'.DIRECTORY_SEPARATOR.'Production'.DIRECTORY_SEPARATOR.'usar_este_1.crt';
    const PRODUCTION_PRIVATE_KEY = __DIR__.DIRECTORY_SEPARATOR.'Certificates'.DIRECTORY_SEPARATOR.'Production'.DIRECTORY_SEPARATOR.'Produccion_Asaliah_Clave_Privada'; 
    
    // TESTING//
    const TESTING_CERTIFICATE = __DIR__.DIRECTORY_SEPARATOR.'Certificates'.DIRECTORY_SEPARATOR.'Testing'.DIRECTORY_SEPARATOR.'TestingCertificate';
    const TESTING_PRIVATE_KEY = __DIR__.DIRECTORY_SEPARATOR.'Certificates'.DIRECTORY_SEPARATOR.'Testing'.DIRECTORY_SEPARATOR.'ClavePrivadaCoto.key';
    
    const TA = __DIR__.DIRECTORY_SEPARATOR.'Xml'.DIRECTORY_SEPARATOR.'TA.xml';

    const TRA = __DIR__.DIRECTORY_SEPARATOR. 'Xml'.DIRECTORY_SEPARATOR.'TRA.xml';

    const TRA_TMP = __DIR__.DIRECTORY_SEPARATOR.'Xml'.DIRECTORY_SEPARATOR.'TRA.tmp';

    const WSAA_WSDL_PRODUCTION = __DIR__. DIRECTORY_SEPARATOR . 'Wsdl' . DIRECTORY_SEPARATOR . 'wsaa_produccion.wsdl';
    
    const WSAA_WSDL_TESTING = __DIR__. DIRECTORY_SEPARATOR . 'Wsdl' . DIRECTORY_SEPARATOR . 'wsaa_homo.wsdl';
    
    const REQUEST_LOGIN_CMS  = __DIR__.DIRECTORY_SEPARATOR.'Xml'.DIRECTORY_SEPARATOR.'request-loginCms.xml';

    const RESPONSE_LOGIN_CMS = __DIR__.DIRECTORY_SEPARATOR.'Xml'.DIRECTORY_SEPARATOR.'response-loginCms.xml';
    
    public static function getWSDL($wsdl)
    {
        $class = new \ReflectionClass(__CLASS__);

        $constants = $class->getConstants();

        return $constants[$wsdl];
    }
}
