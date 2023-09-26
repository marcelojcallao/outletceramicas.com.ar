<?php

namespace App\Transformers\PedidoCliente;

use App\Src\Models\PedidoCliente;
use App\Src\Traits\DateFormatTrait;
use App\Src\Traits\MoneyFormatTrait;
use League\Fractal\TransformerAbstract;
use App\Src\Traits\PedidoClientesTransformerTrait;
use Illuminate\Support\Facades\Log;

class PedidoClienteDetailShowTransformer extends TransformerAbstract
{
    use DateFormatTrait, PedidoClientesTransformerTrait, MoneyFormatTrait;

    public function transform(PedidoCliente $pc)
    {
        return [
            'code' => $pc->code,
            'come_from_meli' => $pc->come_from_meli(),
            'comments' => $this->comments($pc),
            'cotizacion' => ($pc->parent_id) ? $pc->parent_id : false,
            'created' => (($pc->date)) ? $this->ShortDateToArgentinaFormat($pc->date) : $this->LongDateToArgentinaLongDate($pc->created_at),
            'created_date' => $pc->date,
            'customer_accounting_account_child' => ($pc->customer->accounting_account_child()->exists()) ? $pc->customer->accounting_account_child->name : false,
            'customer_cellphone' => (is_null($pc->customer->cell_phone)) ? '' : $pc->customer->cell_phone,
            'customer_contact' => $pc->customer->contact,
            'customer_DocTipo_afip_code' => ($pc->customer->purchaserDocument()->exists()) ? $pc->customer->purchaserDocument->afip_code : 'Falta definir',
            'customer_DocTipo_id' => ($pc->customer->purchaserDocument()->exists()) ? $pc->customer->purchaserDocument->id : false,
            'customer_DocTipo' => ($pc->customer->purchaserDocument()->exists()) ? $pc->customer->purchaserDocument->name : false,
            'customer_document_number' => $pc->customer->number,
            'customer_has_afip_data' => $pc->customer->has_afip_data,
            'customer_id' => $pc->customer->id,
            'customer_inscription_id' => $pc->customer->inscription_id,
            'customer_inscription_name' => ($pc->customer->inscription()->exists()) ? $pc->customer->inscription->name : 'Falta definir',
            'customer_nick_name' => $pc->customer->meli_nick,
            'customer_phone1' => (is_null($pc->customer->phone_1)) ? '' : $pc->customer->phone_1,
            'customer_phone2' => (is_null($pc->customer->phone_2)) ? '' : $pc->customer->phone_2,
            'customer_phone3' => (is_null($pc->customer->phone_3)) ? '' : $pc->customer->phone_3,
            'customer_tipo_persona' => (is_null($pc->customer->datos_generales)) ? '' : $pc->customer->datos_generales['tipoPersona'],
            'customer_type_id' => $pc->customer->type->id,
            'customer_type' => $pc->customer->type->name,
            'customer' => strtoupper($pc->customer->name) . ' ' . strtoupper($pc->customer->meli_nick),
            'customer_address' => $this->customer_address($pc),
            'delivery_addresses' => $this->addresses($pc),
            'delivery_date' => $this->ShortDateToArgentinaFormat($pc->delivery_date),
            'detail_of_totals' => $this->detail_of_totals($pc),
            'email' =>  $pc->customer->email,
            'has_delivery_date' => $this->hasDeliveryDate($pc),
            'id' => $pc->id,
            'invoices' => $this->invoices($pc),
            'is_meli_order' => $pc->is_meli_order,
            'items' => $this->items($pc),
            'meli_id' => $pc->meli_id,
            'meli_messages' => $pc->messages->unique('text')->values()->all(),
            'message_pack_id' => is_null($pc->meli_data) ? '' : ( (is_null($pc->meli_data['pack_id'])) ? $pc->meli_data['id'] : $pc->meli_data['pack_id']),
            'message_seller_email' => ($pc->come_from_meli()) ? (array_key_exists('email', $pc->meli_data['seller'])) ? $pc->meli_data['seller']['email'] : 'Falta Email' : false,
            'message_seller_id' => ($pc->come_from_meli()) ? $pc->meli_data['seller']['id'] : false,
            'message_to_user' => ($pc->come_from_meli()) ? $pc->meli_data['buyer']['id'] : false,
            'parent_id' => $pc->parent_id,
            'payment_data' => $this->payment_data($pc),
            'phone_1' =>  $pc->customer->phone_1,
            'phone_2' =>  $pc->customer->phone_2,
            'remito_code' => $this->remito_code($pc),
            'remito_created_at' => ($pc->remitos()->exists()) ? $pc->remitos->first()->created_at : '',
            'shipping' => $pc->shipping,
            'status_id' => $this->status($pc),
            'status_list' => ($pc->statuses()->exists()) ? $pc->statuses : false,
            'status' => ($pc->voucher->name == 'COTIZACION') ? 'COTIZACION' : $pc->status->name,
            'total_neto' => $pc->items->sum('neto_import') - $pc->items->sum('discount_import'),
            'total_raw' => (is_null($pc->meli_data)) ? $pc->items->sum('neto_import') - $pc->items->sum('discount_import') : $pc->meli_data['total_amount'],
            'total' => (is_null($pc->meli_data)) ? $this->DisplayToUserCurrency( $pc->items->sum('neto_import') - $pc->items->sum('discount_import') ) : $this->DisplayToUserCurrency( $pc->meli_data['total_amount'] ),
            'type' => $pc->voucher->name,
            'type_user' => $pc->user->type_user_id,
            'user_on_list_status' => $this->user_on_list_status($pc),
            'who_delivered' => $pc->who_delivered,
            'who_prepared' => $pc->who_prepared,
            'is_editing' => $pc->is_editing,
        ];
    }
}
