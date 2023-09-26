<?php

namespace App\Src\Models;

use App\Models\RemitoItem;
use App\Src\Models\PedidoCliente;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Remito extends Model
{
    public function number()
    {
        $count = $this::count();

        if ($count == 0) {
            return 1;
        }

        return $count;
    }

    /**
     * Get all of the items for the Remito
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(RemitoItem::class, 'remito_id', 'id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function pedido_cliente(): BelongsTo
    {
        return $this->belongsTo(PedidoCliente::class, 'pedido_cliente_id', 'id');
    }
}
