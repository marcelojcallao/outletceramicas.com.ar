<?php

namespace App\Transformers\Remito;

use App\Src\Models\Remito;
use App\Src\Traits\DateFormatTrait;
use League\Fractal\TransformerAbstract;
use App\Src\Traits\PedidoClientesTransformerTrait;

class RemitoTransformer extends TransformerAbstract
{
    use DateFormatTrait, PedidoClientesTransformerTrait;

    public function items($remito)
    {
        return $remito->items->map(function($item){
            return [
                'name' => $item->product->name,
                'quantity' => $item->quantity,
                'mts' => $item->mts,
            ];
        });
    }

    public function transform(Remito $remito)
    {
        return [

            'code' => $remito->code,
            'comments' => $this->comments($remito->pedido_cliente),
            'customer_cellphone' => (is_null($remito->customer->cell_phone)) ? '' : $remito->customer->cell_phone,
            'customer_contact' => $remito->customer->contact,
            'customer_DocTipo_afip_code' => ($remito->customer->purchaserDocument()->exists()) ? $remito->customer->purchaserDocument->afip_code : 'Falta definir',
            'customer_DocTipo_id' => ($remito->customer->purchaserDocument()->exists()) ? $remito->customer->purchaserDocument->id : false,
            'customer_DocTipo' => ($remito->customer->purchaserDocument()->exists()) ? $remito->customer->purchaserDocument->name : false,
            'customer_document_number' => $remito->customer->number,
            'customer_has_afip_data' => $remito->customer->has_afip_data,
            'customer_id' => $remito->customer->id,
            'customer_inscription_id' => $remito->customer->inscription_id,
            'customer_inscription_name' => ($remito->customer->inscription()->exists()) ? $remito->customer->inscription->name : 'Falta definir',
            'customer_nick_name' => $remito->customer->meli_nick,
            'customer_phone1' => (is_null($remito->customer->phone_1)) ? '' : $remito->customer->phone_1,
            'customer_phone2' => (is_null($remito->customer->phone_2)) ? '' : $remito->customer->phone_2,
            'customer_phone3' => (is_null($remito->customer->phone_3)) ? '' : $remito->customer->phone_3,
            'customer_tipo_persona' => (is_null($remito->customer->datos_generales)) ? '' : $remito->customer->datos_generales['tipoPersona'],
            'customer_type_id' => $remito->customer->type->id,
            'customer_type' => $remito->customer->type->name,
            'customer' => strtoupper($remito->customer->name) . ' ' . strtoupper($remito->customer->meli_nick),
            'delivery_addresses' => $this->addresses($remito->pedido_cliente),
            'delivery_date' => $remito->delivery_date,
            'email' =>  $remito->customer->email,
            'has_delivery_date' => $this->hasDeliveryDate($remito),
            'id' => $remito->id,
            'items' => $this->items($remito),
            'phone_1' =>  $remito->customer->phone_1,
            'phone_2' =>  $remito->customer->phone_2,
            'remito_created_at' => $remito->created_at,
            'status_id' => $remito->status_id,
            'pedido_code' => $remito->pedido_cliente->code
        ];
    }
}
