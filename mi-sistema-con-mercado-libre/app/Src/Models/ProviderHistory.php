<?php

namespace App\Src\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderHistory extends Model
{
    protected $guarded = [];

    protected $casts = [
        'data' => 'array'
    ];
}
