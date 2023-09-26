<?php

namespace App\Observers;

use App\Src\Models\Product;
use App\Src\Models\PedidoCliente;
use Illuminate\Support\Facades\DB;
use App\Src\Traits\PedidoCliente\PedidoClienteHistoryTrait;

class PedidoClienteObserver
{
    use PedidoClienteHistoryTrait;
    
    public function finished(PedidoCliente $pc)
    {
        $data = $this->history($pc, 'NEW_PEDIDO_CLIENTE');

        $pc->history()->create($data);

        /* $pc->items->map(function($item){
            
            $product = Product::find($item->product->id);

            $product->update(['stock' => ((int)$item->product->stock - (int)$item->quantity)]);
            
            event('eloquent.updated: ' . Product::class, $product);
        }); */
    }

    public function updated(PedidoCliente $pc)
    {
        $data = $this->history($pc, 'UPDATED_PEDIDO_CLIENTE');

        $pc->history()->create($data);
    }
}
