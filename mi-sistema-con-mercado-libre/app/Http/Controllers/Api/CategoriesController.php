<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Str;
use App\Src\Models\Category;
use Illuminate\Http\Request;
use App\Src\Meli\MeliCategories;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Category\CategoryFormRequest;
use App\Src\Categories\CategoryBase;
use App\Transformers\Categories\CategoryTransformer;

class CategoriesController extends Controller
{
    const PARENT_CATEGORY = 0;

    public function store(CategoryFormRequest $request)
    {
        $att = $request->all();
        
        $filteredAttributes = collect($att['attributes'])->reject(function ($value, $key) {
            return $value['name'] == null;
        })->toArray();
        $code = '';
        if ($request->parent_id == 0) {
           $code = $request->code;
        }else{
            $code = "{$request->category_path}-{$request->code}";
        }
        $category = new Category;
        $category->name = strtoupper($request->name);
        $category->code = strtoupper($code);
        $category->parent_id = $request->parent_id;
        $category->slug =  Str::slug($request->name);
        $category->attributes = $filteredAttributes;
        $category->save();

        return response()->json($category, 201);

    }

    public function index()
    {
        $categories = Category::all();
        
        $categories = fractal($categories, new CategoryTransformer())->toArray()['data'];

        return response()->json($categories, 200);
    }
    
    public function parent()
    {
        $categories = Category::where('parent_id', self::PARENT_CATEGORY)
            ->where('active', true)->get();
        
        return response()->json($categories, 200);
    }

    public function child()
    {
        $categories = Category::where('parent_id', request()->category_id)
            ->where('active', true)->get();
        
        return response()->json($categories, 200);
    }

    public function update_status_on_children_categories($category, $status)
    {
        $categories = Category::all();

        $categories->chunk(10)->map(function($chunk) use($category, $status){
            $chunk->map(function($cat) use($category, $status) {

                $category->active = $status;
                $category->save();
                $category->fresh();

                $category = Category::where('parent_id', $category->id)->get()->first();

                if ($category) {
                    $this->update_status_on_children_categories($category, $status);
                }
                

            });
        }); 


    }
    public function set_status()
    {
        $status = request()->status;

        $category_code = request()->code;

        $category = Category::find(request()->category_id);
        $category->active = $status;
        $category->save();
        $category->fresh();
        
        $categories = Category::where('code', 'like', "{$category_code}%")->get();

        $updated_categories = $categories->map(function($cat) use($status) {
            $cat->update(['active' => $status]);
            return $cat;
        });
        
        return response()->json($updated_categories, 200);
        
    }

    public function update_name()
    {
        $category = Category::find(request()->category_id);

        $category->name = strtoupper(request()->category_name);
        $category->slug =  Str::slug(request()->category_name);
        $category->save();
        
        return response()->json($category, 201);
    }


}
