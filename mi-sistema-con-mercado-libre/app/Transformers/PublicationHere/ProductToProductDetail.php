<?php

namespace App\Transformers\PublicationHere;

use App\Src\Models\Product;
use App\Src\Traits\MoneyFormatTrait;
use League\Fractal\TransformerAbstract;
use App\Src\Traits\PublicationTransformerTrait;

class ProductToProductDetail extends TransformerAbstract
{
    use MoneyFormatTrait, PublicationTransformerTrait;
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Product $product)
    {
        if (app()->environment('production')) {
            if($product->getMedia('products')->isEmpty()){
                $pics = $product->pictures->toArray();
            }else{
                $pics = $product->getMedia('products')->map(function($image){
                    return $image->getFullUrl();
                })->toArray(); 
            }
        }
        
        if (app()->environment('local')) {
            if($product->getMedia('products')->isEmpty()){
                $pics = $product->pictures->toArray();
            }else{
                $pics = $product->getMedia('products')->map(function($i){
                    return 'https://images.unsplash.com/photo-1504285519797-3b0349c4c170?ixlib=rb-1.2.1&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=1080&fit=max&ixid=eyJhcHBfaWQiOjcwNjE0fQ';
                })->toArray(); 
            }
        }

        $variations = $product->variations->map(function($variation){
            return [
                'product_id' => $variation->product_id,
                'meli_id' => $variation->meli_id,
                'attribute_combinations' => $variation->attribute_combinations,
                'attributes' => $variation->attributes,
                'stock' => $variation->stock->total_stock,
            ];
        });

        $vars = collect($product->variations);

        $attribute_combinations = $vars->map(function($v){
            return $v->attribute_combinations;
        });

        $data_for_select = collect($attribute_combinations)->map(function($attr){
            return [
                'color_value_name' => $attr[0]['value_name'],
                'color_value_id' => $attr[0]['value_id'],
                'size_value_name' => $attr[1]['value_name'],
                'size_value_id' => $attr[1]['value_id'],
                '$isDisabled' => false,
            ];
        });
        
        return [
            'id' => $product->id,
            "title"=>$product->name,   
            "category_id"=>$product->code,
            "price"=> $this->DisplayToUserCurrency($product->sale_price),
            "iva"=> $product->iva,
            "raw_price"=> $product->sale_price,
            "buying_mode"=>$product->buying_mode['name'],
            "listing_type_id"=>$product->listing_type['id'],
            "description"=> [
                'plain_text'=>$product->description
            ],
            'stock' => $product->total_stock(),
            "attributes"=> $this->attributes($product->attributes), 
            "seller_custom_field" => $product->seller_custom_field,
            "sale_terms"=>[],
            "pictures"=> $pics,
            "variations" => $variations,
            "colors" => $data_for_select,
            "sizes" => $data_for_select,
            'slug' => $product->slug,
            'brand_id' => $product->brand_id,
            'gender_id' => $product->gender_id,
            'type_shoes_id' => $product->type_shoes_id,
            'material_id' => $product->material_id,
            'activity_id' => $product->activity_id,
            'season_id' => $product->season_id,
        ];
    }
}
