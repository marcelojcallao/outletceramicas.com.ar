<?php

namespace App\Transformers\PresupuestoVenta;

use App\Src\Models\SaleInvoice;
use App\Src\Traits\DateFormatTrait;
use League\Fractal\TransformerAbstract;
use App\Src\Traits\PedidoClientesTransformerTrait;

class PresupuestoVentaTransformer extends TransformerAbstract
{
    use DateFormatTrait, PedidoClientesTransformerTrait;

    private function detail_of_totals($si)
    {
        $totals = $si->items->groupBy('iva_id')->map(function($iva) use($si){

            $neto = collect($iva)->reduce(function($acc, $item) {
                return $acc + $item['neto_import'];
            });

            $iva_import = collect($iva)->reduce(function($acc, $item) {
				return $acc + $item['iva_import'];
            });

            $name = collect($iva)->reduce(function($acc, $item) {
                return $item->iva->name;
            });

            $discount_import = collect($iva)->reduce(function($acc, $pedido_cliente_item) {
                return $acc + $pedido_cliente_item['discount_import'];
            });

            return [
                'neto_name' => "Subtotal",
                'neto_import' => $neto,
                'iva_name' => "IVA ${name}",
                'iva_import' => $iva_import,
                'discount_name' => 'Des. por cant..',
                'discount_import' => $discount_import,
                'aditional_payment_name' => $si->pedidos->first()->payment_type_aditional->type->name . ' ' . $si->pedidos->first()->payment_type_aditional->percentage . ' %',
                'aditional_payment_value' => $si->pedidos->first()->payment_type_aditional->value
            ];

        })->values()->all();

        $totals_collection = collect($totals);

        $total = $totals_collection->sum('neto_import') - $totals_collection->sum('discount_import') + $si->pedidos->first()->payment_type_aditional->value;

        $totals['total_name'] = 'Total';

        $totals['total_import'] = $total;

        return $totals;
    }

    private function items($si){

        return $si->items->map(function($item)
        {
            if ($item->quantity == 0 || is_null($item->quantity)) {
                $quantity = 1;
            }else{
                $quantity = $item->quantity;
            }

            return [
                'about_voucher' => $item->sale_invoice->voucher->name . ' ' . str_pad($item->sale_invoice->pto_vta, 5, "0", STR_PAD_LEFT) . ' - ' . str_pad($item->sale_invoice->cbte_desde, 8, "0", STR_PAD_LEFT),
                'discount_import' => $item->discount_import,
                'discount_import' => $item->discount,
                'isCHP' => $item->isCHP,
                'iva_import' => $item->iva_import,
                'iva_name' => $item->iva['name'],
                'iva_percentage' => $item->iva['percentage'],
                'mts_by_unity' => $item->mts_by_unity,
                'mts_to_invoiced' => $item->mts_to_invoiced,
                'neto_import' => $item->neto_import,
                'product_id' => ($item->product()->exists()) ? $item->product->id : 'ver que pasa',
                'product_name' => ($item->product()->exists()) ? $item->product->name  : $item->obs,
                'quantity' => $quantity,
                'real_mts' => $item->real_mts,
                'rounded_mts' => $item->rounded_mts,
                'total_invoiceA' => ($item->neto_import + $item->iva_import),
                'total_invoiceB' => ($item->neto_import + $item->iva_import),
                'total_Presupuesto' => ($item->neto_import),
                'total' => $item->total,
                'unit_price_invoiceA' => ($item->neto_import + $item->discount) / $quantity,
                'unit_price_invoiceB' => ($item->neto_import + $item->discount + $item->iva_import) / $quantity,
                'unit_price_Presupuesto' => ($item->neto_import + $item->discount_import) / $quantity,
            ];
        });
    }

    private function docTipo($doc){
        switch ($doc) {
            case 86:
                return 'CUIL';
                break;

            case 80:
                return 'CUIT';
                break;

            case 96:
                return 'DNI';
                break;
        }
    }

    private function Voucher_number($PtoVta, $CbteDesde) {

        return 'N° ' .  $this->zeroLeft($PtoVta, 5) . ' - ' . $this->zeroLeft($CbteDesde, 8);
    }

    private function zeroLeft($data, $lenght)
    {
        return str_pad($data, $lenght, "0", STR_PAD_LEFT);
    }

    public function customer($si)
    {
        return [
            'name' => $si->customer->name,
            'address' => 'address',
            'id_number' => $si->customer->number,
            'inscription' => ($si->customer->inscription()->exists())?$si->customer->inscription->name: 'Falta Inscrisi->pción',
        ];
    }

    public function total($si)
    {
        return $si->items->sum('total');
    }

    private function totals($si)
    {
        $totals = collect();

        $neto = (float) $si->items->sum('neto_import');

        $iva = (float) $si->items->sum('iva_import');

        $aditional = ($si->pedidos->first()->payment_type_aditional()->exists()) ? $si->pedidos->first()->payment_type_aditional->value : 0;

        $shipping = ($si->pedidos->first()->shipping()->exists()) ? $si->pedidos->first()->shipping->value : 0;

        $totals->push([
            [
                'name' => 'Subtotal',
                'value' => $neto //se borra iva por que es actura en negro
            ]
        ]);

        if($si->pedidos->first()->payment_type_aditional()->exists())
        {
            $totals->push([
                [
                    'name' => ($si->pedidos->first()->payment_type_aditional->payment_type_id == 3) ? 'Desc. pago efectivo' : 'Ad. Pago diferido',
                    'value' => $aditional + $iva //se le resta iva por que cuando se crea el pedido lo agrega, pero ahora se factura en negro
                ]
            ]);
        }

        if($si->pedidos->first()->shipping()->exists())
        {
            $totals->push([
                [
                    'name' => 'Gastos de envío',
                    'value' => $shipping
                ]
            ]);
        }


        $totals->push([
            [
                'name' => 'Total',
                'value' =>  $neto + $iva +  $aditional + $shipping
            ]
        ]);

        return $totals->toArray();
    }

    public function transform(SaleInvoice $si)
    {
        return [
            //'addresses' => $this->addresses($si->pedidos->first()),
            'aditional_payment' => $si->pedidos->first()->payment_type_aditional,
            'afip_data' => $si->afip_data,
            'bill_type' => $si->voucher_id,
            'cae_vto' => $this->ShortDateToArgentinaFormat($si->cae_fch_vto),
            'cae' => $si->cae,
            'caeVto' => $si->cae_fch_vto,
            'cbte_desde' => $si->cbte_desde,
            'cuit' => $si->doc_nro,
            'customer_address' => $this->customer_address($si->pedidos->first()),
            'customer_purchaser_document' => ($si->customer->purchaserDocument()->exists()) ? $si->customer->purchaserDocument->afip_code : 'Sin definir',
            'customer' => $this->customer($si),
            'date' => $this->ShortDateToArgentinaFormat($si->cbte_fch),
            'delivery_address' => $this->addresses($si->pedidos->first()),
            'docTipo' => ($si->customer->purchaserDocument()->exists()) ? $this->docTipo($si->customer->purchaserDocument->afip_code) : 'Sin definir',
            'id' => $si->id,
            'iibb_percentage' => $si->iibb_percentage,
            'invoice_letter' => substr($si->voucher->name, -1),//$this->invoice_letter($si->voucher_id),
            'percep_iibb' => $si->percerp_iibb,
            'products' => $this->items($si),
            'ptoVta' => $si->pto_vta,
            'shipping' => $si->pedidos->first()->shipping,
            'total' => $this->total($si),
            'totals' => $this->detail_of_totals($si),
            'voucher_name' => $si->voucher->name,
            'voucher_number' => $this->Voucher_number($si->pto_vta, $si->cbte_desde),
            'voucher' => $si->voucher->name,
			'comments' => $this->comments($si->pedidos->first())
        ];
    }
}
