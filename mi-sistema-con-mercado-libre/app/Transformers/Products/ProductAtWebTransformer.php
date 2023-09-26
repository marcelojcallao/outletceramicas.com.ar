<?php

namespace App\Transformers\Products;

use App\Src\Models\Product;
use League\Fractal\TransformerAbstract;

class ProductAtWebTransformer extends TransformerAbstract
{
    public function getPictures($product)
    {
        if ($product->getMedia('products')->isNotEmpty()) 
        {
            return $product->getMedia('products')->map(function($img){
                return $img->getUrl();
            })->toArray();
        }

        return false;
    }

    public function getPrice($p)
    {
        if ($p->see_price_on_the_web) {
            return $p->pricelists->filter(function($q){
                return $q->id == 4;
            })->first()->pivot->price;
        }

        return 0;
    }

    public function transform( Product $product)
    {
        return [
            'id' => $product->id,
            'code' => strtoupper($product->code),
            'name' => strtoupper($product->name),
            'description' => $product->description,
            'attributes' => $product->attributes,
            'stock' => $product->stock,
            //WWW' => $product->priority->name,
            'slug' => $product->slug,
            'hasPrice' => $product->see_price_on_the_web,
            //'price' => $product->pricelists,
            'price' => $this->getPrice($product),
            'pictures' => $this->getPictures($product)
        ];
    }
}
