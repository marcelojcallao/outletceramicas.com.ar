<?php

namespace App\Src\Models;

use App\Src\Models\PedidoCliente;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    const ACTIVO = 1;
    const PENDIENTE = 2;
    const REMITIDO = 3;
    const PRESUPUESTADO = 4;
    const FACTURADO = 5; 
    const PREPARADO = 6;
    const RETIRADO = 7;
    const DESPACHADO = 8;
    const ENTREGADO = 9;
    const ELIMINADO = 10;
    const COTIZADO = 11;
    const CUMPLIDO = 13;

    
    /* public function pedidos_clientes()
    {
        return $this->belongsToMany(PedidoCliente::class, 'pedidos_clientes_status', 'status_id', 'pedido_cliente_id')->withPivot('description', 'user_id');
    } */
}
