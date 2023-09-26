<?php

namespace App\Src\Afip\WS;

use App\Src\Models\Afip;
use Jenssegers\Date\Date;
use App\Src\Afip\FacturacionElectronica\WSCONST;
use Illuminate\Support\Facades\Log;
use App\Src\Afip\FacturacionElectronica\PATHFILES;
use Exception;

abstract class AfipWebService
{
    public $soapHttp;

    public $TA;

    public $TRA;

    public $TRA_TMP;

    public $environment;
    
    public $service;

    public $Auth;

    public $afip_params;

    public $token;

    public $sign;

    public $cuit;

    public $cuitRepresentada;

    public function __construct($service, $environment)
    {
        $this->service = strtoupper($service);
        $this->environment = strtoupper($environment);

        if ($environment === 'testing' || $environment === 'TESTING') {
            $this->cuit = env('CUIT_TESTING');
            $this->cuitRepresentada = env('CUIT_TESTING');
        }else{

            $this->cuit = env('WSBASE_CUIT');
            $this->cuitRepresentada = env('WSBASE_CUIT_REPRESENTADA');
        }
        //dd('cuit ' . $this->cuit, 'environment ' . $environment);

        ini_set("soap.wsdl_cache_enabled", 0); 
        ini_set('soap.wsdl_cache_ttl',0);
        
        if (! Afip::where('ws', $service)
                ->where('active', true)
                ->where('environment', $this->environment)
                ->exists()
            ) {
            
            $this->create_TA($service, $this->environment);

            sleep(1);

            $this->afipModel = new Afip;
            $this->afipModel->ws = $service;
            $this->afipModel->unique_id = $this->get_unique_id();
            $this->afipModel->generation_time = $this->get_generationTime();
            $this->afipModel->expiration_time = $this->get_expirationTime();
            $this->afipModel->token = $this->get_Token();
            $this->afipModel->sign = $this->get_sign();
            $this->afipModel->environment = $this->environment;  
            $this->afipModel->active = true;
            $this->afipModel->save();

        }else{

            $this->afipModel = Afip::where('ws', $service)->where('environment', $this->environment)->where('active', true)->get()->first();

            if (! $this->afipModel->isActive()) {

                $this->afipModel->active = false;
                $this->afipModel->save();

                $this->create_TA($service);

                sleep(1);

                $this->afipModel = new Afip;
                $this->afipModel->ws = $service;
                $this->afipModel->unique_id = $this->get_unique_id();
                $this->afipModel->generation_time = $this->get_generationTime();
                $this->afipModel->expiration_time = $this->get_expirationTime();
                $this->afipModel->token = $this->get_Token();
                $this->afipModel->sign = $this->get_sign();
                $this->afipModel->environment = $this->environment;
                $this->afipModel->active = true;
                $this->afipModel->save();
            }
            
        }

        $this->token = $this->afipModel->token;

        $this->sign = $this->afipModel->sign;
        
        $this->Auth = [
            'Token' => $this->token,
            'Sign'  => $this->sign,        
            'Cuit'  => $this->cuitRepresentada
        ];

        try {

            $wsdl = "{$this->service}_{$this->environment}";

            $wsdl = PATHFILES::getWSDL($wsdl);
            
            $this->soapHttp = new \SoapClient($wsdl);

        } catch (\Exception $e) {
            Log::error("Error en try catch WSBASE" . $e->getMessage() . ' - ' . $e->getCode() . ' WsbService : ' . $wsdl);
        }
    }

    /**
     * Abre el archivo de TA xml,
     * si hay algun problema devuelve false
     */
    public function openTA()
    {
        $TA = simplexml_load_file(PATHFILES::TA);

        return $TA == false ? false : true;
    }

    /**
     * Crea el archivo xml de TRA
     * $service - se reemplaza por el ws que se desea usar
     */
    public  function create_TRA($service)
    {
        $TRA = new \SimpleXMLElement(
            '<?xml version="1.0" encoding="UTF-8"?>' .
            '<loginTicketRequest version="1.0">'.
            '</loginTicketRequest>');
        $TRA->addChild('header');
        $TRA->header->addChild('uniqueId', date('U'));
        $TRA->header->addChild('generationTime', date('c',date('U')-60));
        $TRA->header->addChild('expirationTime', date('c',date('U')+60));
        $TRA->addChild('service', $service);
        $TRA->asXML(PATHFILES::TRA);
    }

    public function get_service()
    {
        $TRA = simplexml_load_file(PATHFILES::TRA);

        return $TRA->service;
    }
      
    public  function sign_TRA()
    {
        $STATUS = openssl_pkcs7_sign(
            PATHFILES::TRA,
            PATHFILES::TRA_TMP,
            file_get_contents(
                (
                    $this->environment == 'PRODUCTION' 
                    ? PATHFILES::PRODUCTION_CERTIFICATE 
                    : PATHFILES::TESTING_CERTIFICATE
                ) 
            ),
            [
                file_get_contents(
                    (
                        $this->environment == 'PRODUCTION' 
                        ? PATHFILES::PRODUCTION_PRIVATE_KEY 
                        : PATHFILES::TESTING_PRIVATE_KEY
                    )
                ), ''
            ],
            [],
            !PKCS7_DETACHED
        );
        
        if (!$STATUS)
            throw new \Exception("ERROR generating PKCS#7 signature");
        
        $inf = fopen(PATHFILES::TRA_TMP, "r");
        $i = 0;
        $CMS = "";
        while (!feof($inf)) { 
            $buffer = fgets($inf);
            if ( $i++ >= 4 ) $CMS .= $buffer;
        }
        fclose($inf);
        //---## BORRO EL TEMPORAL ##---//
        unlink(PATHFILES::TRA_TMP);
        return $CMS;
    }
    
    
    public function call_WSAA($cms)
    {
        $ops = [
            'ssl' => 
                [
                    //'ciphers' => 'AES256-SHA',
                    'verify_peer' => false,
                    'verify_peer_name' => false
                ]
        ];
        
        $client = new \SoapClient(
                ($this->environment == 'PRODUCTION' 
                ? "file://" . PATHFILES::WSAA_WSDL_PRODUCTION
                : "file://" . PATHFILES::WSAA_WSDL_TESTING
            ), 

            [
                'cache_wsdl'  => WSDL_CACHE_NONE,
                'soap_version'  => SOAP_1_2,
                'location'      => 
                    (
                        ($this->environment == 'PRODUCTION') 
                        ? WSCONST::WSAA_PRODUCTION_LOGINCMS 
                        : WSCONST::WSAA_TESTING_LOGINCMS
                    ),
                'trace'         => 1,
                'exceptions'    => 0,
                //'stream_opts' => stream_context_create($ops)
            ]
        );
        /* $client->__setLocation(($this->environment == 'PRODUCTION' 
            ? "file://" . WSCONST::WSAA_PRODUCTION_LOGINCMS
            : "file://" . WSCONST::WSAA_TESTING_LOGINCMS
        )); */

        $results = $client->loginCms(['in0' => $cms]);
        //dd($results);
        file_put_contents(
            PATHFILES::REQUEST_LOGIN_CMS, 
            $client->__getLastRequest()
        );
        file_put_contents(
            PATHFILES::RESPONSE_LOGIN_CMS,
            $client->__getLastResponse()
        );
        if (is_soap_fault($results)) 
            throw new \Exception("SOAP Fault: ".$results->faultcode.' : '.$results->faultstring);
        
        return $results->loginCmsReturn;
    }
    
    /*
    * Convertir un XML en Array
    */
    public  function xml2array($xml) {    

        $json = json_encode( simplexml_load_string($xml));

        return json_decode($json, TRUE);
    }    
    
    public function create_TA($web_services)
    {
        $this->create_TRA($web_services);
        
        $CMS=$this->sign_TRA();

        $TICKET=$this->call_WSAA($CMS);

        if (!file_put_contents(PATHFILES::TA, $TICKET))
            throw new \Exception("Error al generar al archivo TA.xml");

        return true;
    }

    public function get_unique_id()
    {
        $TA_file = file(PATHFILES::TA, FILE_IGNORE_NEW_LINES);
        $TA_xml = '';
        for($i=0; $i < sizeof($TA_file); $i++)
            $TA_xml.= $TA_file[$i];  

            $TA = $this->xml2Array($TA_xml);
            $uniqueId = $TA['header']['uniqueId'];

        return $uniqueId;
    }
    
    public function get_expirationTime()                   
    {    
        $TA_file = file(PATHFILES::TA, FILE_IGNORE_NEW_LINES);
        $TA_xml = '';
        for($i=0; $i < sizeof($TA_file); $i++)
            $TA_xml.= $TA_file[$i];  

            $TA = $this->xml2Array($TA_xml);
            $expirationTime = $TA['header']['expirationTime'];

        return $expirationTime;
    }

    public function get_generationTime()                   
    {    
        $TA_file = file(PATHFILES::TA, FILE_IGNORE_NEW_LINES);
        $TA_xml = '';
        for($i=0; $i < sizeof($TA_file); $i++)
            $TA_xml.= $TA_file[$i];  

            $TA = $this->xml2Array($TA_xml);
            $generationTime = $TA['header']['generationTime'];

        return $generationTime;
    }

    public function get_Token()
    {
        $TA_file = file(PATHFILES::TA, FILE_IGNORE_NEW_LINES);
        $TA_xml = '';
        for($i=0; $i < sizeof($TA_file); $i++)
            $TA_xml.= $TA_file[$i];  

            $TA = $this->xml2Array($TA_xml);
            $token = $TA['credentials']['token'];

        return $token;
    }

    public function get_sign()
    {
        $TA_file = file(PATHFILES::TA, FILE_IGNORE_NEW_LINES);
        $TA_xml = '';
        for($i=0; $i < sizeof($TA_file); $i++)
        $TA_xml.= $TA_file[$i];  

        $TA = $this->xml2Array($TA_xml);
        $sign = $TA['credentials']['sign'];

        return $sign;
    }

    public function is_validTA()
    {
        $c =  new Date;
        $expirationTime = $c->parse($this->get_expirationTime());
        $currentTime    = $c->parse($c->now());

        if (strtotime($currentTime) >= strtotime($expirationTime)) {
            return false;
        }
        
        return true; 
    }

    
    

}
