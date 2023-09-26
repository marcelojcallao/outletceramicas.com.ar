<?php

namespace App\Src\Afip\FacturacionElectronica\InvoiceCreators;

use App\Src\Afip\FacturacionElectronica\InvoiceProducts\Invoice;


interface InvoiceCreator
{
    /**
     * operations
     *
     * @param  mixed $cuit
     * @param  mixed $neto_import
     * @param  mixed $invoice_date
     * @param  mixed $environment
     * @return void
     */
    public function generate_invoice($customer_DocTipo_afip_code, $cuit, $neto_import, $invoice_date, $environment );
}
