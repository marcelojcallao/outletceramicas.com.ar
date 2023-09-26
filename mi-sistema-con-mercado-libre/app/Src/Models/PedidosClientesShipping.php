<?php

namespace App\Src\Models;

use Illuminate\Database\Eloquent\Model;

class PedidosClientesShipping extends Model
{
    protected $guarded = [];

    protected $casts = [
        'shipping' => 'array',
        'geocoder' => 'array',
    ];
}
