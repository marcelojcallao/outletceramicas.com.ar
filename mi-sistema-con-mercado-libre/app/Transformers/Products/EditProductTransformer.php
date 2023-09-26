<?php

namespace App\Transformers\Products;

use App\Src\Models\Product;
use App\Src\Models\Provider;
use App\Src\Models\PriceList;
use App\Src\Models\PriceListProduct;
use League\Fractal\TransformerAbstract;

class EditProductTransformer extends TransformerAbstract
{

    public function setSupplier($product)
    {
        $supplier = Provider::find($product->supplier_id);

        return [
            'id' => $supplier->id,
            'name' => $supplier->name,
        ];
    }


    public function setPrice($product)
    {
        /* return collect($product->pricelists)->map(function($list, $index) {


                return [
                    'price_list_id' => $list->id,
                    'name' => $list->name,
                    'enabledPriceListToProduct' => $list->pivot->enabledPriceListToProduct,
                    'benefit' => $list->pivot->benefit,
                    'import' => $list->pivot->price,
                    'price' => $list->pivot->costo,
                ];


        })->filter()->values()->toArray(); */
        return PriceList::where('enable', 1)->get(['id', 'name'])
            ->map(function($pl) use($product) {

                $priceListProduct = PriceListProduct::where('pricelist_id', $pl->id)->where('product_id', $product->id)->get();

                return [
                    'price_list_id' => $pl->id,
                    'name' => $pl->name,
                    'enabledPriceListToProduct' => ($priceListProduct->isNotEmpty()) ? $priceListProduct->first()->enabledPriceListToProduct : false,
                    'benefit' => ($priceListProduct->isNotEmpty()) ? $priceListProduct->first()->benefit : 0,
                    'import' => ($priceListProduct->isNotEmpty()) ? $priceListProduct->first()->price : 0,
                    'price' => ($priceListProduct->isNotEmpty()) ? $priceListProduct->first()->costo : 0
                ];
            })->toArray();
    }

    public function pictures($product){

        return $product->getMedia('products')->map(function($pic) use($product){

            return [
                'id' => $pic->id,
                'name' => $pic->file_name,
                'url' => $pic->getUrl(),
                'product_id' => $product->id
            ];

        })->toArray();
    }

    public function isCHP($pr)
    {

        if (strpos(strtoupper($pr->name), 'CHAPA LISA') !== false) {
            return false;
        }

        if ($pr->isCHP) {
            return $pr->isCHP;
        }

        return collect($pr->categories_path)->map(function($cat){
            return collect($cat)->map(function($cat_child){

                $code = explode('-', $cat_child['code']);

                return collect($code)->map(function($i){
                    if($i == 'CHP') return true;
                })->filter()->first();

            })->filter()->first();
        })->first();
    }

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Product $product)
    {
        $isCHP =  $this->isCHP($product);
        $sheet_metal_cutting = false;

        if ($isCHP) {
            $sheet_metal_cutting = $product->stocks->filter(function ($smc) {
                return $smc['quantity'] > 0;
            })->values();
        }

        return [
            'id' => $product->id,
            'categories_path' => $product->categories_path,
            'selected_categories_from_root' => $product->selected_categories_from_root,
            'name' => $product->name,
            'code' => $product->code,
            'stock' => $product->stock,
            'critical_stock' => $product->critical_stock,
            'name_on_supplier' => $product->name_on_supplier,
            'code_on_supplier' => $product->code_on_supplier,
            'description' => $product->description,
            'status_id' => $product->status_id,
            'attributes' => $product->attributes,
            'active' => $product->active,
            'mts_by_unity' => $product->mts_by_unity,
            'category_id' => $product->category_id,
            'supplier' => $this->setSupplier($product),
            'price' => $this->setPrice($product),
            'isCHP' => $isCHP,
            'pictures' => $this->pictures($product),
            'see_price_on_the_web' => $product->see_price_on_the_web,
            'publish_on_web' => $product->published_here,
            'apply_discount' => $product->apply_discount,
            'apply_discount_from' => $product->apply_discount_from,
            'apply_discount_percentage' => $product->apply_discount_percentage,
			'apply_discount_pay_method' => $product->apply_discount_pay_method
        ];
    }
}
