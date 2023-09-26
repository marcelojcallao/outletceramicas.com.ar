<?php

namespace App\Src\Afip\FacturacionElectronica\InvoiceProducts;

use App\Src\Afip\FacturacionElectronica\InvoiceProducts\Invoice;
use App\Src\Afip\FacturacionElectronica\WSCONST;

class FacturaB extends Invoice
{
    public function __construct($environment='testing')
    {
        $this->CbteTipo = WSCONST::FACTURA_B;

        parent::__construct($environment);
    }
}
