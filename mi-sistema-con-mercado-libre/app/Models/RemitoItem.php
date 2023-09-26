<?php

namespace App\Models;

use App\Src\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RemitoItem extends Model
{
    protected $table = 'remito_items';
    
    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}

