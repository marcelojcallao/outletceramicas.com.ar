<?php

namespace App\Src\Afip\FacturacionElectronica\InvoiceCreators;

use App\Src\Afip\FacturacionElectronica\InvoiceProducts\Invoice;
use App\Src\Afip\FacturacionElectronica\InvoiceCreators\Creators;
use App\Src\Afip\FacturacionElectronica\InvoiceProducts\FacturaA;
use App\Src\Afip\FacturacionElectronica\InvoiceCreators\InvoiceCreator;

class FacturaACreator extends Creators implements InvoiceCreator
{
    public function factoryInvoice($environment): Invoice {
        return new FacturaA($environment);
    }

    public function generate_invoice( $customer_DocTipo_afip_code, $cuit, $items, $invoice_date, $environment)
    {
        $invoice = $this->factoryInvoice($environment);

        $cbteNumero = $invoice->ultimoAutorizado();

        return $invoice->setDocTipo($customer_DocTipo_afip_code)
            ->setDocNro($cuit)
            ->setCbteNumero( (int) $cbteNumero + 1 )
            ->setInvoiceDate( $this->WSFEInvoiceDate($invoice_date))
            ->setCbteFchMiPyme( $this->ShortDateArgentinaTo_yyyy_mm_dd($invoice_date) )
            ->setItems($items)
            ->operationsWithItems()
            ->setPercepIIBB()
            ->getCaeOnAfip();
        //dd($invoice);
        //perceión iibb: preguntar a arba si el cliente esta inscripto en iibb

        //dd($invoice);
        /* if ($invoice->requiereFacturaMiPyme()) {
            throw new \Exception('es miPyme', 431);
        } */
    }
}
