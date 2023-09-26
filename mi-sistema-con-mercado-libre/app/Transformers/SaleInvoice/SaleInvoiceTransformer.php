<?php

namespace App\Transformers\SaleInvoice;

use App\Src\Models\SaleInvoice;
use App\Src\Traits\ZeroLeftTrait;
use App\Src\Traits\DateFormatTrait;
use League\Fractal\TransformerAbstract;
use Jenssegers\Date\Date;

class SaleInvoiceTransformer extends TransformerAbstract
{
    use ZeroLeftTrait, DateFormatTrait;

    public function transform(SaleInvoice $si) :array
    {
        $neto = 0;

        if ($si->pedidos()->exists()) {
            $pedido = $si->pedidos->first();
            if ($pedido->payment_type_aditional()->exists()) {
                $payment_type_aditional = $pedido->payment_type_aditional->value;
            }
        }else{
            $payment_type_aditional = 0;
        }

        $payment_type_aditional = 0;

       /*  if ($pedido->payment_type_aditional()->exists()) {
            $payment_type_aditional = $pedido->payment_type_aditional->value;
        } */
        
        $neto = $si->items->sum('neto_import') + $payment_type_aditional - $si->items->sum('discount_import');
        //$neto = $pedido->items->sum('neto_import') + $payment_type_aditional + $pedido->percerp_iibb;

        return [
            'customer_name' => $si->customer->name,
            'name' => 'PRESUPUESTO DE VENTA',
            //'name' => $pedido->voucher->name,
            //'number' => $pedido->code,
            'number' => "{$this->zeroLeft($si->pto_vta, 5)} - {$this->zeroLeft($si->cbte_desde, 8)}",
            'date' => Date::createFromFormat( 'Y-m-d', $si->cbte_fch )->format('d-m-Y'),
            //'date' => $this->ShortDateToArgentinaFormat( $pedido->date ),
            //'cae' => ' --- ',
            'cae' => ($si->cae) ? $si->cae : ' --- ',
            'neto' => $neto,
        ];

    }
}
