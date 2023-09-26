<?php

namespace App\Transformers\Categories;

use App\Src\Models\Category;
use League\Fractal\TransformerAbstract;

class CategoriesTreeTransformer extends TransformerAbstract
{
    public function transform(Category $category)
    {
        return [
            'id' => $category->id,
            'name' => $category->name,
            'code' => $category->code,
            'parent_id' => $category->parent_id,
            'status' => $category->active,
            'isFather' => ($category->parent_id == 0) ? 'SI' : 'NO', 
        ];
    }
}
