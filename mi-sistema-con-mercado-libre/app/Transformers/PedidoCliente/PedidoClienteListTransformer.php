<?php

namespace App\Transformers\PedidoCliente;

use App\Src\Models\PedidoCliente;
use App\Src\Traits\DateFormatTrait;

use App\Src\Models\PedidoClienteStatus;
use League\Fractal\TransformerAbstract;
use App\Src\Traits\PedidoClientesTransformerTrait;

class PedidoClienteListTransformer extends TransformerAbstract
{
    use DateFormatTrait, PedidoClientesTransformerTrait;

    public function transform(PedidoCliente $pc)
    {
        $name = '';
        $voucher_name = '';
        $status_name = '';

        if ($pc->customer) {
            $name = $pc->customer->name;
        }

        if ($pc->customer) {
            $name = "{$name} {$pc->customer->meli_nick}";
        }

        if ($pc->voucher) {
            $voucher_name = $pc->voucher->name;
        }

        if ($pc->status) {
            $status_name = $pc->status->name;
        }

        return [
            'id' => $pc->id,
            'code' => $pc->code,
            'customer' => strtoupper($name),
            'delivery_date' => $this->ShortDateToArgentinaFormat($pc->delivery_date),
            'created' => (($pc->date)) ? $this->ShortDateToArgentinaFormat($pc->date) : $this->LongDateToArgentinaLongDate($pc->created_at),
            'come_from_meli' => false,
            'comments' => false,
            'customer_DocTipo' => false,
            'customer_DocTipo_id' => false,
            'customer_DocTipo_afip_code' => 'Falta definir',
            'customer_document_number' => '',
            'customer_id' => '',
            'customer_inscription_id' => '',
            'customer_inscription_name' =>  'Falta definir',
            'customer_cellphone' => '',
            'customer_phone1' => '',
            'customer_phone2' => '',
            'customer_phone3' => '',
            'customer_nick_name' => '',
            'customer_has_afip_data' => '',
            'customer_tipo_persona' => '',
            'customer_type_id' => '',
            'customer_type' => '',
            'customer_contact' => '',
            'customer_accounting_account_child' =>  false,
            'email' =>  '',
            'is_meli_order' => '',
            'items' => null,
            'payment_data' =>[
                'id' =>'',
                'name' =>'',
                'percentage' =>'',
                'value' =>'',
            ],
            'phone_1' => '',
            'phone_2' => '',
            'status_id' => $this->status($pc),
            'status' => ($voucher_name == 'COTIZACION') ? 'COTIZACION' : $status_name,
            'status_list' =>false,
            'total_neto' => $pc->items->sum('neto_import') - $pc->items->sum('discount_import'),
            'total_raw' => 0,
            'total' => $this->total($pc),
            'user_on_list_status' => false,
            'has_delivery_date' => false,
            'meli_id' => false,
            'meli_messages' => false,
            'message_pack_id' => false,
            'message_seller_id' => false,
            'message_seller_email' => false,
            'message_to_user' => false,
            'delivery_addresses' => false,
            'shipping' => false,
            'who_prepared' => false,
            'who_delivered' => false,
            'type' => $pc->voucher->name,
            'parent_id' => $pc->parent_id,
            'remito_code' => $this->remito_code($pc),
            'is_editing' => $pc->is_editing,
            'is_editing_by_user' => $pc->is_editing_by_user,
        ];
    }
}
