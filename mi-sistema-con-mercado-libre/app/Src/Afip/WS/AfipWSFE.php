<?php

namespace App\Src\Afip\WS;

use App\Src\Afip\WS\AfipWebService;
use Exception;

class AfipWSFE extends AfipWebService
{
    const SERVICE = 'wsfe';

    public function __construct($environment)
    {
        parent::__construct(self::SERVICE, $environment);

        $this->Auth['Cuit'] = $this->cuitRepresentada;

        $this->afip_params['Auth'] = $this->Auth;
    }
    
    public function getCaeOnAfip($FeCabReq, $FECAEDetRequest)
    {
        $this->afip_params['FeCAEReq'] = [
            'FeCabReq' => $FeCabReq,
            'FeDetReq' => [
                'FECAEDetRequest' => $FECAEDetRequest
            ],
            
        ];
        //dd($this->afip_params);
        try {
            
            $result =  $this->soapHttp->FECAESolicitar($this->afip_params);
            
            return $result;

        }catch (\SoapFault $e)
        {
            return $e;
        }
    }

    /**
     * FECompUltimoAutorizado
     *
     * @param  mixed $ptoVta
     * @param  mixed $cbteTipo
     * @return Afip Response Processed
     */
    public function FECompUltimoAutorizado($ptoVta = null, $cbteTipo = null)
    {
        $this->afip_params['PtoVta'] = $ptoVta;
        $this->afip_params['CbteTipo'] = $cbteTipo;
        //dd($this->soapHttp);
        try {
            
            $result = $this->soapHttp->FECompUltimoAutorizado($this->afip_params);
            
            $this->__checkErrors(__FUNCTION__, $result);

            return $result->{__FUNCTION__.'Result'};

        }catch (\SoapFault $e)
        {
            $err = collect([
                'Code' => $e->getCode(),
                'Msg' => $e->getMessage(),
            ]);

            throw new \Exception($err->toJson(), 444);
        }
    }

    public function FEParamGetTiposPaises()
    {
        return $this->soapHttp->FEParamGetTiposPaises($this->afip_params);
    }

    public function FEParamGetTiposTributos()
    {
        return $this->soapHttp->FEParamGetTiposTributos($this->afip_params);
    }

    public function FEParamGetPtosVenta()
    {
        return $this->soapHttp->FEParamGetPtosVenta($this->afip_params);
    }

    public function dummy()
    {
        return $this->soapHttp->FEDummy();
    }
    
    public function ConsultarComprobanteEmitido($CbteTipo, $PtoVta, $CbteNro)
    {
        $FeCompConsReq = [
            'CbteTipo' => (int) $CbteTipo,
            'CbteNro' =>  $CbteNro,
            'PtoVta' => (int) $PtoVta,
        ];

        $this->afip_params['FeCompConsReq'] = $FeCompConsReq;

        return $this->soapHttp->FECompConsultar($this->afip_params);
    }

    public function __checkErrors($operation, $results)
    {
        $res = $results->{$operation.'Result'};

        if ($operation == 'FECAESolicitar') {
			if (isset($res->FeDetResp->FECAEDetResponse->Observaciones) && $res->FeDetResp->FECAEDetResponse->Resultado != 'A') {
				$res->Errors = new \StdClass();
				$res->Errors->Err = $res->FeDetResp->FECAEDetResponse->Observaciones->Obs;
			}
		}

		if (isset($res->Errors)) {
			$err = is_array($res->Errors->Err) ? $res->Errors->Err[0] : $res->Errors->Err;
			throw new Exception('('.$err->Code.') '.$err->Msg, $err->Code);
		}

        return $res;
        /* if (isset($res)) {
            throw new \Exception("{$operation} 'results'", 431);
        } */
    }
}
