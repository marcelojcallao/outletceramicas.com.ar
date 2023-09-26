<?php

namespace App\Http\Controllers\Web;

use App\Src\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transformers\Products\ListProductsTransformer;
use App\Transformers\Products\ProductAtWebTransformer;
use App\Transformers\PublicationHere\ResultProductSearch;

class ProductController extends Controller
{
    public function index()
    {   
        $productsAtWeb = Product::query();

        $productsAtWeb =  $productsAtWeb = Product::whereHas('categories', function($q){
            $q->where('active','=',1);//categoría activa
        })->whereHas('pricelists', function($pl){
            $pl->where('pricelist_id',4);//lista de precio para la tiend
        });
        
        $productsAtWeb = $productsAtWeb->where('active', true)->where('published_here', true);

        if (request()->product_name) {
        
            $name = request()->product_name;
            $productsAtWeb = $productsAtWeb->where('name', 'like', "%{$name}%")
                ->orWhere('code', 'like', "%{$name}%");
        }
        
        if (request()->has('by_category')) {
            
            if(! (request()->by_category == 'ALL-CATEGORIES')){
                $category_code = request()->by_category;
                $productsAtWeb = $productsAtWeb->where('code', 'like', "%{$category_code}%");
            }
        }

        if (request()->sort == 'name_a_z') {
            $productsAtWeb = $productsAtWeb->orderBy('name', 'asc');
        }

        if (request()->sort == 'name_z_z') {
            $productsAtWeb = $productsAtWeb-> orderByDesc('name');
        }

        $productsAtWeb = $productsAtWeb->paginate(12);
        
        $pagination = [
            'total' => $productsAtWeb->total(),
            'per_page' => $productsAtWeb->perPage(),
            'current_page' => $productsAtWeb->currentPage(),
            'last_page' => $productsAtWeb->lastPage(),
            'from' => $productsAtWeb->firstItem(),
            'to' => $productsAtWeb->lastItem()
        ];

        $products = fractal()
            ->collection($productsAtWeb, new ProductAtWebTransformer())->toArray()['data'];

        $response = [
            'pagination' => $pagination,
            'data' => $products
        ];

        return response()->json($response, 200);
    }

    public function by_category(Request $request)
    {
        $slug = $request->slug;

        //$post = Post::whereSlug($slugString)->get();
        
        $productsAtWeb = Product::whereHas('categories', function($q) use($slug){
            $q->where('slug', $slug);
        })->get();

        $products = fractal()
            ->collection($productsAtWeb, new ListProductsTransformer())
            ->toArray()['data'];
            
        return response()->json($products, 200);
    }


    public function show(Request $request)
    {
        $productOnSale = Product::where('id', $id)->get();

        $product = fractal()
            ->collection($productOnSale, new ListProductsTransformer())
            ->toArray()['data'];
            
        return response()->json($product, 200);
    }

    public function search_products(Request $request)
    {
        /* $s = explode(" ", $request->search);

        $search = collect($s)->map(function($i){
            return '*' . $i . '*';
        })->toArray(); */
        
        $products = Product::where('name', 'like', '%' . $request->search . '%')
            ->paginate(50);

        $products_results = fractal()
                ->collection($products, new ProductAtWebTransformer())
                ->toArray()['data'];

        $response = [
            'pagination' => [
                'total' => $products->total(),
                'per_page' => $products->perPage(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem()
            ],
            'data' => $products_results
        ];

        return response()->json($response, 200);
    }

}
