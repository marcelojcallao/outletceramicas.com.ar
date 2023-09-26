<?php

namespace App\Src\Afip\WS;

use App\Src\Afip\WS\AfipWebService;

class AfipWSPadronA13 extends AfipWebService
{
    const SERVICE = 'ws_sr_padron_a13';

    public function __construct($environment)
    {
        parent::__construct(self::SERVICE, $environment);

        $this->afip_params = [];
        $this->afip_params['token'] = $this->Auth['Token'];
        $this->afip_params['sign'] = $this->Auth['Sign'];
        $this->afip_params['cuitRepresentada'] = $this->cuitRepresentada;
    }

    public function getPersonaByDocumento($dni)
    {
        $this->afip_params['documento'] = floatval($dni);

        try {

            $result = $this->soapHttp->getIdPersonaListByDocumento($this->afip_params);

            if (is_soap_fault($result)) {
                return response()->json($result, 500);
            }

            $r =  json_decode(json_encode($result), true);
            
            return $r;

        } 
        catch (\Exception $e) {

            activity('Error')
                ->withProperties(['Afip' => $e->getMessage()])
                ->log('Ha ocurrido un error');

                throw new \Exception($e->getMessage(), $e->getCode());
        }
    }
}
