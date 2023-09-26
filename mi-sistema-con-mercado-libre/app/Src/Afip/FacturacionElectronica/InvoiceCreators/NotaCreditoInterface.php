<?php

namespace App\Src\Afip\FacturacionElectronica\InvoiceCreators;

interface NotaCreditoInterface
{
    public function generate_nota_credito($environment, $nota_credito_data);
    
}
