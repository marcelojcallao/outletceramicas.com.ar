<?php

namespace App\Src\Services\Afip;

use App\Src\Models\Afip;
use App\Src\Afip\Traits\WsaaTrait;

class AfipSoapWS 
{
    use WsaaTrait;

    public static function getSoapClient($wsdl)
    {
        try {
            return new \SoapClient($wsdl);
        } catch (\Exception $e) {
            activity("Error")->withProperties(
                [
                    'Message' => $e->getMessage(), 
                    'Code' => $e->getCode()
                ]
                )->log('WSBASE');
            return response()->json('Error en el constructor de WSBASE', 431);
        }
    }

    public function create()
    {
        $aipModel = new Afip;
        $aipModel->ws = $service;
        $aipModel->unique_id = $this->get_unique_id();
        $aipModel->generation_time = $this->get_generationTime();
        $aipModel->expiration_time = $this->get_expirationTime();
        $aipModel->token = $this->get_Token();
        $aipModel->sign = $this->get_sign();
        $aipModel->environment = $this->testingOrProductionWSDL;  
        $aipModel->active = true;
        $aipModel->save();
    }
}
