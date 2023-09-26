<?php

namespace App\Src\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTypeHistory extends Model
{
    protected $guarded = [];
    
    protected $casts = [
        'data' => 'array'
    ];
}
