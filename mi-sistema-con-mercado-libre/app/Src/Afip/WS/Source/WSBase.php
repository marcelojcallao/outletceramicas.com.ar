<?php namespace App\Src\Afip\WS\Source;

use App\Src\Models\Afip;
use Jenssegers\Date\Date;
use App\Src\Afip\WS\Source\WSCONST;
use Illuminate\Support\Facades\Log;
use App\Exceptions\Afip\OwnerSoapFaultException;


abstract class WSBase {

    public $http;

    /**
     * Le asigno el token que viene 
     * de wsaa get_token
     */
    public $token;

    /**
     * Le asigno el token que viene 
     * de wsaa get_sigm
     */
    public $sign;

    public $afipModel;
    
    public $afip_params;

    public $web_services;

    //protected $cuit = 27341817876; //MAGU
    //protected $cuit = 30714227803; //PIAMOND
    //protected $cuit = 20080767944; //PIAMOND
    //protected $cuit = 20080767944;
    protected $cuit;
    protected $cuitRepresentada;
    //protected $cuitRepresentada = 27341817876; //MAGU
    //protected $cuitRepresentada = 30714227803; //PIAMOND
    //protected $cuitRepresentada = 20080767944; //PIAMOND

    protected $testingOrProductionWSDL;

    protected $Auth;
    
    public function __construct($service, $wsdl, $environment)
    {
        $this->afipModel = 'App\Src\Models\Afip';
        
        $this->cuit = env('WSBASE_CUIT');
        $this->cuitRepresentada = env('WSBASE_CUIT_REPRESENTADA');

        //dd($service, $wsdl);
        $this->testingOrProductionWSDL = self::testingOrProductionWSDL($wsdl);
        
        ini_set("soap.wsdl_cache_enabled", "0"); 
        ini_set('soap.wsdl_cache_ttl',0);
        
        if (! Afip::where('ws', $service)
                ->where('active', true)
                ->where('environment', $this->testingOrProductionWSDL)
                ->exists()
            ) {
            
            $this->create_TA($service);

            sleep(1);

            $this->afipModel = new Afip;
            $this->afipModel->ws = $service;
            $this->afipModel->unique_id = $this->get_unique_id();
            $this->afipModel->generation_time = $this->get_generationTime();
            $this->afipModel->expiration_time = $this->get_expirationTime();
            $this->afipModel->token = $this->get_Token();
            $this->afipModel->sign = $this->get_sign();
            $this->afipModel->environment = $this->testingOrProductionWSDL;  
            $this->afipModel->active = true;
            $this->afipModel->save();

        }else{

            $this->afipModel = Afip::where('ws', $service)->where('environment', $this->testingOrProductionWSDL)->where('active', true)->get()->first();

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
                $this->afipModel->environment = $this->testingOrProductionWSDL;
                $this->afipModel->active = true;
                $this->afipModel->save();
            }
            
        }

        $this->token = $this->afipModel->token;

        $this->sign = $this->afipModel->sign;
        
        $this->Auth = [
            'Token' => $this->token,
            'Sign'  => $this->sign,        
            //'Cuit'  => ($this->testingOrProductionWSDL == 'TESTING') ? '20083103818'/* '20008721123' */ : $this->cuit,
            'Cuit'  => $this->cuit
        ];

        try {
            $this->http = new \SoapClient($wsdl);
        } catch (\Exception $e) {
            Log::error("Error en try catch WSBASE" . $e->getMessage() . ' - ' . $e->getCode() . ' WsbService : ' . $wsdl);
            activity("Error")->withProperties(
                [
                    'Message' => $e->getMessage(), 
                    'Code' => $e->getCode()
                ]
                )->log('WSBASE');
            return response()->json('Error en el constructor de WSBASE', 431);
        }
    }

    public static function testingOrProductionWSDL($i)
    {
        $class = new \ReflectionClass(__CLASS__);

        $constants = $class->getConstants();

        $constName = null;

        foreach ( $constants as $name => $value )
        {
            if ( $value == $i )
            {
                $constName = $name;
                break;
            }
        }
        
        $arr = explode ("_", $constName); 

        return $arr[1];
    }
   
    /**
     * Abre el archivo de TA xml,
     * si hay algun problema devuelve false
     */
    public function openTA()
    {
        $this->TA = simplexml_load_file($this->TA);

        return $this->TA == false ? false : true;
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
        $TRA->asXML($this->TRA);
    }

    public function get_service()
    {
        $TRA = simplexml_load_file($this->TRA);

        return $TRA->service;
    }
      
    public  function sign_TRA()
    {
        $STATUS = openssl_pkcs7_sign(
            $this->TRA,
            $this->TRA_TMP,
            file_get_contents(($this->testingOrProductionWSDL == 'PRODUCTION' ? $this->CertificadoProduction : $this->CertificadoTesting) ),
            [
                file_get_contents(($this->testingOrProductionWSDL == 'PRODUCTION' ? $this->PrivadaKeyProduction : $this->PrivadaKeyTesting)), ''
            ],
            [],
            !PKCS7_DETACHED
        );
        
        if (!$STATUS)
            throw new \Exception("ERROR generating PKCS#7 signature");
        
        $inf = fopen($this->TRA_TMP, "r");
        $i = 0;
        $CMS = "";
        while (!feof($inf)) { 
            $buffer = fgets($inf);
            if ( $i++ >= 4 ) $CMS .= $buffer;
        }
        fclose($inf);
        //---## BORRO EL TEMPORAL ##---//
        unlink($this->TRA_TMP);
        return $CMS;
    }
    
    
    public  function call_WSAA($cms, $environment)
    {
        $path= getcwd();
        $client = new \SoapClient(($this->testingOrProductionWSDL == 'PRODUCTION' 
            ? "file://".$this->WsaaWsdlProduction 
            : "file://".$this->WsaaWsdlTesting), 

            [
                'cache_wsdl'  => WSDL_CACHE_NONE,
                'soap_version'  => SOAP_1_2,
                'location'      => ($this->testingOrProductionWSDL == 'PRODUCTION' 
                                    ? WSCONST::WSAA_PRODUCTION 
                                    : WSCONST::WSAA_TESTING),
                'trace'         => 1,
                'exceptions'    => 0
            ]
        );     
        $results = $client->loginCms(array('in0' => $cms));

        file_put_contents(
            $this->requestLoginCms, 
            $client->__getLastRequest()
        );
        file_put_contents(
            $this->responseLoginCms,
            $client->__getLastResponse()
        );
    
        if (is_soap_fault($results)) 
            throw new OwnerSoapFaultException("SOAP Fault: ".$results->faultcode.' : '.$results->faultstring);
        
        return $results->loginCmsReturn;
    }
    
    /*
    * Convertir un XML en Array
    */
    public  function xml2array($xml) {    

        $json = json_encode( simplexml_load_string($xml));

        return json_decode($json, TRUE);
    }    
    
    public function create_TA($web_services, $environment)
    {
        $this->create_TRA($web_services);
        
        $CMS=$this->sign_TRA();
        
        $TICKET=$this->call_WSAA($CMS, $environment);

        if (!file_put_contents($this->TA, $TICKET))
            throw new \Exception("Error al generar al archivo TA.xml");

        return true;
    }

    public function get_unique_id()
    {
        $TA_file = file($this->TA, FILE_IGNORE_NEW_LINES);
        $TA_xml = '';
        for($i=0; $i < sizeof($TA_file); $i++)
            $TA_xml.= $TA_file[$i];  

            $TA = $this->xml2Array($TA_xml);
            $uniqueId = $TA['header']['uniqueId'];

        return $uniqueId;
    }
    
    public function get_expirationTime()                   
    {    
        $TA_file = file($this->TA, FILE_IGNORE_NEW_LINES);
        $TA_xml = '';
        for($i=0; $i < sizeof($TA_file); $i++)
            $TA_xml.= $TA_file[$i];  

            $TA = $this->xml2Array($TA_xml);
            $expirationTime = $TA['header']['expirationTime'];

        return $expirationTime;
    }

    public function get_generationTime()                   
    {    
        $TA_file = file($this->TA, FILE_IGNORE_NEW_LINES);
        $TA_xml = '';
        for($i=0; $i < sizeof($TA_file); $i++)
            $TA_xml.= $TA_file[$i];  

            $TA = $this->xml2Array($TA_xml);
            $generationTime = $TA['header']['generationTime'];

        return $generationTime;
    }

    public function get_Token()
    {
        $TA_file = file($this->TA, FILE_IGNORE_NEW_LINES);
        $TA_xml = '';
        for($i=0; $i < sizeof($TA_file); $i++)
            $TA_xml.= $TA_file[$i];  

            $TA = $this->xml2Array($TA_xml);
            $token = $TA['credentials']['token'];

        return $token;
    }

    public function get_sign()
    {
        $TA_file = file($this->TA, FILE_IGNORE_NEW_LINES);
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

    /**
     * M�todo de prueba del WS
     */
    public function dummy()
    {
        return $this->http->dummy();
    }

    /**
     * devuelve las funciones del webservices
     * @return [type] [description]
     */
    public function getFunctions()
    {
        return $this->http->__getFunctions();
    }

    public function getTypes()
    {
        return $this->http->__getTypes();
    }

}

?>
