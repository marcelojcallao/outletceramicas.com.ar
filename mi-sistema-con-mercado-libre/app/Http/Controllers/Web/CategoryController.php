<?php

namespace App\Http\Controllers\Web;

use App\Src\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transformers\Categories\CategoryTransformer;

class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::where('active', true)->orderBy('name')->get();
        
        $categories = fractal($categories, new CategoryTransformer())->toArray()['data'];
        
        return response()->json($categories, 200);
    }
    public function getAllCategories()
    {
        $categories = Category::orderBy('name')->get();
        
        $categories = fractal($categories, new CategoryTransformer())->toArray()['data'];
        
        return response()->json($categories, 200);
    }

}