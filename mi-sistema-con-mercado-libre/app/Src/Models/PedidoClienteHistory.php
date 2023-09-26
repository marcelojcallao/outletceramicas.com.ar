<?php

namespace App\Src\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoClienteHistory extends Model
{
    protected $guarded = [];
    
    protected $casts = [
        'data' => 'array'
    ];
}
