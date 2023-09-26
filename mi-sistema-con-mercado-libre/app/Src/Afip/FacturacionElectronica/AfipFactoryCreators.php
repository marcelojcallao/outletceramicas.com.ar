<?php

namespace App\Src\Afip\FacturacionElectronica;

use App\Src\Afip\FacturacionElectronica\WSCONST;

class AfipFactoryCreators
{
    public static function createInstance($invoice_type, $inscription_company, $inscription_customer)
    {
        $class = collect(WSCONST::CLASSES)->map(function($c, $index) use($invoice_type, $inscription_company, $inscription_customer){
            
            if ($c['invoice_type'] == $invoice_type && $c['inscription_company'] == $inscription_company && $c['inscription_customer'] == $inscription_customer) {
                return WSCONST::NAMESPACE_INVOICE_CREATORS . $c['class'];
            }
            
        })->filter()->values()->first();
        
        return new $class;

    }
}
