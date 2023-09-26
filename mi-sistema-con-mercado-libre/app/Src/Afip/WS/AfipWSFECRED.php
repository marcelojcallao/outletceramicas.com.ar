<?php

namespace App\Src\Afip\WS;

use App\Src\Afip\WS\AfipWebService;

class AfipWSFECRED extends AfipWebService
{
    const SERVICE = 'wsfecred';

    public function __construct($environment)
    {
        parent::__construct(self::SERVICE, $environment);

        //$this->afip_params['Auth'] = $this->Auth;
        $this->afip_params = [
            'authRequest' => [
                'token' => $this->token,
                'sign' => $this->sign,
                'cuitRepresentada' => $this->cuitRepresentada
            ],
        ];
    }
    
    /**
     * consultarMontoObligadoRecepcion
     *
     * @param  mixed $cuit
     * @param  mixed $date
     * @return Afip Response +"obligado": "S" "montoDesde": "195698"
     */
    public function consultarMontoObligadoRecepcion($cuit, $date)
    {
        $this->afip_params['cuitConsultada'] = $cuit;
        $this->afip_params['fechaEmision'] = $date;

        $results = $this->soapHttp->consultarMontoObligadoRecepcion($this->afip_params);

        //checkerros acá
        return $this->getResults(__FUNCTION__, $results);
    }

    public function getResults($operation, $results)
    {
        return $results->{$operation.'Return'};
    }
}
