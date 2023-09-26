<?php

namespace App\Transformers\PedidoCliente;

use App\Src\Models\PedidoCliente;
use App\Src\Traits\DateFormatTrait;
use App\Src\Traits\PedidoClientesTransformerTrait;
use League\Fractal\TransformerAbstract;

class CotizacionPdfTransformer extends TransformerAbstract
{
    use DateFormatTrait, PedidoClientesTransformerTrait;

    public function transform(PedidoCliente $cotizacion)
    {
        $company_address = '';

        if ((auth()->user()->company->addresses()->exists())) {
            $first = auth()->user()->company->addresses->first();
            $company_address = "{$first->address} - {$first->city}";
        }

        return [
            'company' => [
                'name' => auth()->user()->company->name,
                'address' => $company_address,
                'inscription' => auth()->user()->company->inscription->name,
                'cuit' => auth()->user()->company->number,
                'iibb' => auth()->user()->company->iibb_conv,
                'activity_init' => $this->ShortDateToArgentinaFormat(auth()->user()->company->activity_init),
                'purchaserDocument' => auth()->user()->company->purchaserDocument->name,

            ],
            'code' => $cotizacion->code,
            'items' => $this->items($cotizacion),
            'total_neto' => $cotizacion->items->sum('neto_import'),
            'customer' => $cotizacion->customer->name,
            'customer_inscription_name' => $cotizacion->customer->inscription->name,
            'customer_document_number' => $cotizacion->customer->number,
            'customer_DocTipo' => $cotizacion->customer->purchaserDocument->name,
            'cotization_date' => $this->ShortDateToArgentinaFormat($cotizacion->date),
            'customer_address' => $this->customer_address($cotizacion),
            'delivery_addresses' => $this->addresses($cotizacion),
        ];
    }
}
