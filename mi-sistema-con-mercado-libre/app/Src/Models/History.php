<?php

namespace App\Src\Models;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $guarded = [];
    
    protected $casts = [
        'data' => 'array'
    ];    

   
    public function historiable()
    {
        return $this->morphTo();
    }
}
