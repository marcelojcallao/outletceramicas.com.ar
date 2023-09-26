<?php
namespace App\Observers;

use App\Src\Models\Product;
use App\Src\Traits\Product\ProductHistoryTrait;

class ProductObserver 
{
    use ProductHistoryTrait;
    
    public function finished(Product $product)
    {
        $data = $this->history($product, 'NEW_PRODUCT');

        $product->history()->create($data);
    }

    public function updated(Product $product)
    {
        $data = $this->history($product, 'UPDATE_PRODUCT');

        $product->history()->create($data);
    }
   
    
}