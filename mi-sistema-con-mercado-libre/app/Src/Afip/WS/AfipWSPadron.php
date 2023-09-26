<?php

namespace App\Src\Afip\WS;

use App\Src\Afip\FacturacionElectronica\WSCONST;

class AfipWSPadron
{
    protected $padron_A5;

    protected $padron_A13;

    public function __construct($environment)
    {
        $this->padron_A5 = new AfipWSPadronA5($environment);
        
        $this->padron_A13 = new AfipWSPadronA13($environment);
    }

    public function isCUITorCUIL($value)
    {
        $dni = (string) $value;

        if (strlen($dni) == WSCONST::CUIT_LENGHT) {
            return true;
        }

        return false;
    }

    public function getPersona($value)
    {
        if ($this->isCUITorCUIL($value)) {

            $result = $this->padron_A5->getPersona($value);

            return $result;
        }

        $result = $this->padron_A13->getPersonaByDocumento($value);

        return $result;
    }
}
