<?php

namespace App\Src\Afip\FacturacionElectronica\InvoiceCreators;

use App\Src\Afip\FacturacionElectronica\InvoiceProducts\Invoice;
use App\Src\Afip\FacturacionElectronica\InvoiceCreators\Creators;
use App\Src\Afip\FacturacionElectronica\InvoiceProducts\NotaCreditoA;
use App\Src\Afip\FacturacionElectronica\InvoiceCreators\NotaCreditoInterface;

class NotaCreditoACreator extends Creators implements NotaCreditoInterface
{
    public function factoryInvoice($environment): Invoice {
        return new NotaCreditoA($environment);
    }

    public function generate_nota_credito($environment, $nota_credito_data)
    {
        $nota_credito = $this->factoryInvoice($environment);

        $cbteNumero = $nota_credito->ultimoAutorizado();

        $nota_credito->setDocTipo($nota_credito_data['nota_credito']['customer']['purchaser_document']['afip_code']);

        $nota_credito->setDocNro($nota_credito_data['customer_cuit']);

        $nota_credito->setCbteNumero( (int) $cbteNumero + 1 );

        $nota_credito->setItems($nota_credito_data['nota_credito']['text']);

        $nota_credito->setInvoiceDate( $this->nowYMD() );

        $nota_credito->setCbteFchMiPyme( $this->nowYMD() );

        $nota_credito->setNetoImport($nota_credito_data['nota_credito']['Neto']);
        
        $nota_credito->setImpIVA($nota_credito_data['nota_credito']['IvaImport']);

        $nota_credito->setImpTotal($nota_credito_data['nota_credito']['ImpTotal']);

        $nota_credito->setCbtesAsoc($nota_credito_data['nota_credito']['CbtesAsoc']);
        
        $nota_credito->setIVA($nota_credito_data['nota_credito']['AlicIva']);

        $nota_credito->setImpTrib($nota_credito_data['nota_credito']['ImpTrib']);
        
        $nota_credito->setTributos($nota_credito_data['nota_credito']['Tributos']);

        return $nota_credito->getCaeOnAfip();
    }
}
