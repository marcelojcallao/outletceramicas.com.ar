<?php namespace App\Src\Afip\Traits; 

use Jenssegers\Date\Date;
use App\Src\Afip\WS\Source\WSCONST;
use App\Exceptions\Afip\OwnerSoapFaultException;
	use Carbon\Carbon as Carbon;

	trait WsaaTrait
	{
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
		
		public  function sign_TRA($WSDL, $pathToPrivateKey)
		{
			$STATUS = openssl_pkcs7_sign(
				$this->TRA,
				$this->TRA_TMP,
				file_get_contents( $WSDL ),
				[
					file_get_contents($pathToPrivateKey), ''
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
		
		
		public  function call_WSAA($wsdl, $cms)
		{
			/* $client = new \SoapClient(($this->testingOrProductionWSDL == 'PRODUCTION' 
				? "file://".$this->WsaaWsdlProduction 
				: "file://".$this->WsaaWsdlTesting),  */
			$client = new \SoapClient($wsdl, 
				[
					'cache_wsdl'  => WSDL_CACHE_NONE,
					'soap_version'  => SOAP_1_2,
					/* 'location'      => ($this->testingOrProductionWSDL == 'PRODUCTION' 
										? WSCONST::WSAA_PRODUCTION 
										: WSCONST::WSAA_TESTING), */
					'location'      => $wsaa,
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

	}

 ?>