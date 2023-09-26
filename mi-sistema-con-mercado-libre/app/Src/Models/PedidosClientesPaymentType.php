<?php

namespace App\Src\Models;

use App\Src\Models\PaymentType;
use Illuminate\Database\Eloquent\Model;

class PedidosClientesPaymentType extends Model
{
    protected $guarded = [];

    protected $table = 'pedidos_clientes_payment_types';
    
    public function type()
    {
        return $this->hasOne(PaymentType::class, 'id', 'payment_type_id');
    }
}
