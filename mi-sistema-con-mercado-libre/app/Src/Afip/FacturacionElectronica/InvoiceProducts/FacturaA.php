<?php

namespace App\Src\Afip\FacturacionElectronica\InvoiceProducts;

use App\Src\Afip\FacturacionElectronica\InvoiceProducts\Invoice;
use App\Src\Afip\FacturacionElectronica\WSCONST;

class FacturaA extends Invoice
{
    public function __construct($environment='testing')
    {
        $this->CbteTipo = WSCONST::FACTURA_A;
        
        parent::__construct($environment);
    }

    
}
