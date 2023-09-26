<?php

namespace App\Transformers\Products;

use App\Src\Models\ProductStock;
use League\Fractal\TransformerAbstract;

class SheetMetalCuttingsTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(ProductStock $stock)
    {
        return [
            'id' => $stock->id,
            'product_id' => $stock->product_id,
            'name' => $stock->product->name,
            'quantity' => $stock->quantity,
            'mts' => $stock->mts,
        ];
    }
}
