<?php

namespace App\Src\Traits\Product;


trait ProductHistoryTrait 
{
    public function images_info($product)
    {
        $medias = $product->getMedia();

        if ($medias->isNotEmpty()) {
            
            return $medias->map(function($media){
                return $media->getUrl();
            })->toArray();
        }

        return;
    }

    public function prices($product)
    {
        return $product->pricelists->map(function($list){
            return [
                'name' => $list->name,
                'costo' => $list->pivot->costo,
                'price' => $list->pivot->price,
            ];
        })->toArray();
    }

    public function history($product, $history_text)
    {
        return [
            
            'data' => [
                'type' => $history_text,
                'product'=>  $product,
                'price_list' => $product->prices,
                'images' => $this->images_info($product),
                'prices' => $this->prices($product)
            ]
        ];
    }
}
