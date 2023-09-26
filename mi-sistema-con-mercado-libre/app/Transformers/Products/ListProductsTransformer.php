<?php

namespace App\Transformers\Products;

use App\Src\Models\Product;
use App\Src\Traits\MoneyFormatTrait;
use App\Src\Unsplash\SearchUnSplash;
use League\Fractal\TransformerAbstract;

class ListProductsTransformer extends TransformerAbstract
{
    use MoneyFormatTrait;

    private $search_photos;

    public function __construct()
    {
        $this->search_photos = new SearchUnSplash();
    }

    public function setPrice($product)
    {
        return collect($product->pricelists)->map(function($list){

            return [
                'price_list_id' => $list->id,
                'benefit' => $list->benefit,
                'price' => $list->pivot->costo,
                'import' => $list->pivot->price
            ];

        })->toArray();
    }
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Product $product)
    {
        return [
            'id' => $product->id,
            'code' => strtoupper($product->code),
            'name' => $product->name,
            'description' => $product->description,
            'attributes' => $product->attributes,
            'stock' => $product->stock,
            'critical_stock' => $product->critical_stock,
            'slug' => $product->slug,
            'price' => $this->setPrice($product),   
            'isCriticalStock' => ($product->stock <= $product->critical_stock) ? true : false
        ];
    }
}
