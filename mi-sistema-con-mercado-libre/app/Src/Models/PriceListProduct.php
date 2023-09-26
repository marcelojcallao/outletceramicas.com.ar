<?php

namespace App\Src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PriceListProduct extends Model
{
    protected $table = 'pricelist_products';

    /**
     * Get the lp associated with the PriceListProduct
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function lp(): HasOne
    {
        return $this->hasOne(PriceList::class, 'id', 'pricelist_id');
    }

}
