<?php

namespace App\Src\Afip\FacturacionElectronica\InvoiceCreators;

use App\Src\Afip\FacturacionElectronica\InvoiceProducts\Invoice;
use App\Src\Afip\FacturacionElectronica\InvoiceCreators\Creators;
use App\Src\Afip\FacturacionElectronica\InvoiceProducts\FacturaB;
use App\Src\Afip\FacturacionElectronica\InvoiceCreators\InvoiceCreator;

class FacturaBCreator extends Creators implements InvoiceCreator
{
    public function factoryInvoice($environment): Invoice {
        return new FacturaB($environment);
    }

    public function generate_invoice( $customer_DocTipo_afip_code, $cuit, $items, $invoice_date, $environment='testing')
    {
        $invoice = $this->factoryInvoice($environment);

        $cbteNumero = $invoice->ultimoAutorizado();
        
        return $invoice->setDocTipo($customer_DocTipo_afip_code)
            ->setDocNro($cuit)
            ->setCbteNumero( (int) $cbteNumero + 1 )
            ->setItems($items)
            ->setInvoiceDate( $this->WSFEInvoiceDate($invoice_date) )
            ->setCbteFchMiPyme( $this->ShortDateArgentinaTo_yyyy_mm_dd($invoice_date) )
            ->operationsWithItems()
            ->setPercepIIBB()
            ->getCaeOnAfip();
         
        //perceión iibb: preguntar a arba si el cliente esta inscripto en iibb

        //dd($invoice, );
        /* if ($invoice->requiereFacturaMiPyme()) {
            throw new \Exception('es miPyme', 431);
        } */


    }
}
