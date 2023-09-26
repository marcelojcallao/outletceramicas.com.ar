<?php

namespace App\Src\Traits\PedidoCliente;

trait PedidoClienteHistoryTrait
{
    public function history($pc, $history_text)
    {
        return [
            
            'data' => [
                'type' => $history_text,
                'pedido_cliente'=>  $pc,
                'items' => $pc->items,
                'addresses' => $pc->addresses,
                'payment_type_aditional' => $pc->payment_type_aditional,
                'shipping' => $pc->shipping,
            ]
        ];
    }
}
