<?php

namespace App\Src\Afip\FacturacionElectronica\InvoiceProducts;

use App\Src\Afip\FacturacionElectronica\WSCONST;
use App\Src\Afip\FacturacionElectronica\InvoiceProducts\Invoice;

class NotaCreditoA extends Invoice
{
    public function __construct($environment='testing')
    {
        $this->CbteTipo = WSCONST::NOTA_CREDITO_A;

        parent::__construct($environment);
    }
}
