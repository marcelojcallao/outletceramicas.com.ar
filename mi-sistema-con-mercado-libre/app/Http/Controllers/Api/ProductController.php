<?php

namespace App\Http\Controllers\Api;

use App\Src\Models\Status;
use App\Src\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Media;
use App\Src\Traits\ProductTrait;
use Illuminate\Support\Facades\Log;
use App\Src\Models\PriceListProduct;
use App\Src\Traits\MoneyFormatTrait;
use App\Http\Controllers\Api\BaseController;
use App\Src\Traits\PublicationTransformerTrait;
use App\Http\Requests\Products\ProductsFormRequest;
use App\Transformers\Products\EditProductTransformer;
use App\Transformers\Products\ListProductsTransformer;
use App\Transformers\Products\FindProductByNameTransformer;
use App\Transformers\Products\StockMovementsTransformer;

class ProductController extends BaseController
{
	use MoneyFormatTrait, PublicationTransformerTrait, ProductTrait;

	const MAX_PRIORITY = 3;

	const ACTIVE_STATUS = 1;

	const CRITICAL_STOCK = 10;

	const FIRST_VARIATION = 1;

	const PESOS = 1;

	public function total()
	{
		return Product::count();
	}

	public function index()
	{
		$products = Product::paginate(10);

		$products_list = fractal($products, new ListProductsTransformer())->toArray()['data'];

		$response = [
			'pagination' => [
				'total' => $products->total(),
				'per_page' => $products->perPage(),
				'current_page' => $products->currentPage(),
				'last_page' => $products->lastPage(),
				'from' => $products->firstItem(),
				'to' => $products->lastItem()
			],
			'data' => $products_list,
		];

		return response()->json($response, 200);
	}

	public function save_product($request, $product)
	{

		$data = collect($request->all());

		$pr = $data->get('product');
		$categories_path = $data->get('categories_path');
		$selected_categories_from_root = $data->get('selected_categories_from_root');

		$product->supplier_id = $pr['supplier']['id'];
		$product->brand_id = null;
		$product->attr_item_condition = null;
		$product->buying_mode = null;
		$product->category_id = $pr['category_id'];
		$product->path_from_root = null;
		$product->children_category = null;
		$product->categories_path = $categories_path;
		$product->selected_categories_from_root = $selected_categories_from_root;
		$product->name = strtoupper($pr['name']);
		$product->code = strtoupper("{$request->category_path}-{$pr['code']}");
		$product->name_on_supplier = $pr['name_on_supplier'];
		$product->code_on_supplier = $pr['code_on_supplier'];
		$product->sub_title = null;
		$product->description = $pr['description'];
		$product->original_price = 0;
		$product->sale_price = 0;
		$product->seller_custom_field = 0;
		$product->meta_keywords = null;
		$product->iva = null;
		$product->slug = Str::slug($pr['name']);
		$product->listing_type = 0;
		$product->money = self::PESOS;
		$product->status_id = Status::ACTIVO;
		$product->priority_id = self::MAX_PRIORITY;
		$product->attributes = $pr['attributes'];
		$product->stock = $pr['stock'];
		$product->critical_stock = $pr['critical_stock'];
		$product->mts_by_unity = $pr['mts_by_unity'];
		$product->see_price_on_the_web = $pr['see_price_on_web'];
		$product->isCHP = $pr['isCHP'];
		$product->published_here = $pr['publish_on_web'];
		$product->apply_discount = $pr['apply_discount'];
		$product->apply_discount_from = $pr['apply_discount_from'];
		$product->apply_discount_percentage = $pr['apply_discount_percentage'];
		$product->apply_discount_pay_method = $pr['apply_discount_pay_method'];
		$product->save();

		$last = collect($selected_categories_from_root)->last();
		$product->categories()->sync($last['id']);

		$product->refresh();

		if ($data->has('file')) {

			$photos = $product->addMultipleMediaFromRequest(['file'])
				->each(function ($photo) {
					$photo->toMediaCollection('products');
				});
		}

		collect($pr['price'])->map(function ($price, $index) use ($product, $pr) {

			$price_list_product = PriceListProduct::where('product_id', $product->id)
				->where('pricelist_id', $price['price_list_id'])->get();

			if ($price_list_product->isEmpty()) {
				$pl = new PriceListProduct;
				$pl->pricelist_id = $price['price_list_id'];
				$pl->product_id = $product->id;
				$pl->costo = $price['price'];
				$pl->price = $price['import'];
				$pl->benefit = $price['benefit'];
				$pl->enabledPriceListToProduct = $price['enabledPriceListToProduct'];
				$pl->save();
			} else {
				Log::info('Product id : ' . $product->id . ' - pricelist_id :' . $price['price_list_id'] . ' costo :' . $price['price'] . ' price : ' . $price['import'] . ' benefit : ' . $price['benefit']);

				$priceProduct = PriceListProduct::where('pricelist_id', $price['price_list_id'])
					->where('product_id', $product->id)->get();

				/* $priceProduct->first()->costo = ($price['benefit'] == 0 || $price['benefit'] == '0.0') ? 0 : $price['price'];
                $priceProduct->first()->price = ($price['benefit'] == 0 || $price['benefit'] == '0.0') ? 0 : $price['import'];
                $priceProduct->first()->benefit = ($price['benefit'] == 0 || $price['benefit'] == '0.0') ? 0 : $price['benefit']; */
				$priceProduct->first()->costo = $price['price'];
				$priceProduct->first()->price = $price['import'];
				$priceProduct->first()->benefit = $price['benefit'];
				$priceProduct->first()->enabledPriceListToProduct = $price['enabledPriceListToProduct'];
				$priceProduct->first()->save();
			}
		});

		return $product;
	}

	public function store(ProductsFormRequest $request)
	{
		$pr = new Product;

		$product = $this->save_product($request, $pr);

		$prToArray = fractal($product, new EditProductTransformer())->toArray()['data'];

		product_history($product, 'creación', auth()->user()->id, $prToArray, $product->stock);

		return response()->json($product, 201);
	}

	public function update(ProductsFormRequest $request)
	{
		$data = collect($request->all());

		$product = $data->get('product');

		$pr = Product::find($product['id']);

		$product = $this->save_product($request, $pr);

		$prToArray = fractal($product, new EditProductTransformer())->toArray()['data'];

		product_history($product, 'actualiza', auth()->user()->id, $prToArray, $product->stock);

		return response()->json($product, 201);
	}

	/*   public function fetchlistingtypes()
    {
        return $this->meliproducts->fetch_listing_types();
    }

    public function fetchattributes(Request $request)
    {
        $category = $request->category;

        $attributes = $this->meliproducts->fetch_attributes($category);

        return response()->json($attributes, 200);
    }

    public function fetchsubcategories(Request $request)
    {
        $category = $request->category['code'];

        return $this->meliproducts->fetch_sub_categories($category);
    } */

	public function update_ok()
	{
		return response()->json(1);
	}

	public function iva_update(Request $request)
	{
		$product = Product::find($request->product['product_id']);

		$product->iva = $request->iva;
		$product->save();

		product_history($product, 'se cambia iva', auth()->user()->id, $product->iva->toArray(), $product->stock);

		return response()->json($product, 200);
	}

	public function findProductByName()
	{
		$product_name = request()->product_name;

		$prs = Product::where('code', 'like', '%' . $product_name . '%')
			->orWhere('name', 'like', '%' . $product_name . '%')
			->orWhere('description', 'like', '%' . $product_name . '%')
			->where('active', self::ACTIVE_STATUS)->take(31)->get();

		$products = fractal($prs, new FindProductByNameTransformer())->toArray()['data'];

		return response()->json($products, 200);
	}

	public function findById()
	{
		$product = Product::find((int)request()->product_id);

		$product->prices->sortBy('pricelist_id');

		$product = fractal($product, new EditProductTransformer())->toArray()['data'];
		//dd($product);

		return response()->json($product, 200);
	}

	public function delete_picture()
	{
		$mediaTodelete = Media::find(request()->picture_id)->delete();

		return response()->json(request()->picture_id, 200);
	}

	public function image_remove()
	{
		try {
			$product = Product::find(request()->img['product_id']);

			$product_image = $product->getMedia('products')->filter(function ($i) {
				return $i->id == request()->img['id'];
			})->first();

			product_history($product, 'se elimina imagen', auth()->user()->id, $product->toArray(), $product->stock);

			$product_image->delete();

			return response()->json($product_image, 200);
		} catch (\Exception $e) {
			throw new \Exception('Ha ocurrido un error al intentar eliminar la imagen', 431);
		}
	}

	public function add_stock()
	{
		$product_id = request()->product_id;

		$stock = request()->stock;

		$product = Product::find($product_id);

		$product->stock = (int) $product->stock + (int) $stock;

		$product->save();

		$product->refresh();

		product_history($product, 'agrega stock', auth()->user()->id, ['cantidad' => $stock], $product->stock);

		return response()->json($product, 201);
	}

	public function delete_product()
	{
		try {

			$product = Product::find(request()->product_id);

			$product->delete();

			product_history($product, 'eliminado', auth()->user()->id, $product->toArray(), $product->stock);

			return response()->json('ok', 200);
		} catch (\Exception $e) {

			throw new \Exception('Ha ocurrido un error al intentar eliminar el producto.', 431);
		}
	}

	public function critical_stock()
	{
		$products = Product::select('name', 'code', 'critical_stock', 'stock')->get();

		$result = $products->chunk(10)->map(function ($chunk) {

			return $chunk->map(function ($product) {
				if ($product->stock <= $product->critical_stock) {
					return $product;
				}
			})->filter()->values();
		});

		return response()->json($result, 200);
	}

	public function getStockMovements()
	{
		$product = Product::find(request()->product_id);

		$stock_movements = fractal($product->stock_movements, new StockMovementsTransformer())->toArray()['data'];

		return response()->json($stock_movements, 200);
	}
}
