<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Src\Models\ProductStock;
use App\Http\Controllers\Controller;
use App\Src\Models\Product;
use App\Transformers\Products\SheetMetalCuttingsTransformer;

class SheetMetalCuttingController extends Controller
{
	protected function findIndexSmcByMts($mts, $product_id, $array)
	{

		foreach ($array as $key => $smc) {
			if ($mts == $smc['mts'] && (int)$product_id == (int)$smc['product_id']) {
				return $key;
			}
		}

		return false;
	}

	public function getAll()
	{
		$stock = ProductStock::where('quantity', '>', 0)->orderBy('product_id')->orderBy('mts')->get();

		$stock = fractal($stock, new SheetMetalCuttingsTransformer())->toArray()['data'];

		$sheet_metal_cuttings = [];

		$sheet_metal_cuttings = collect($stock)->reduce(function ($acc, $el) {

			$index = $this->findIndexSmcByMts($el['mts'], $el['product_id'], $acc);

			if (($index) || $index === 0) {
				$acc[$index]['quantity'] = (int)$acc[$index]['quantity'] + (int)$el['quantity'];
			} else {
				if ((int)$el['quantity'] > 0) {
					array_push($acc, ['mts' => $el['mts'], 'quantity' => $el['quantity'], 'id' => $el['id'], 'name' => $el['name'], 'product_id' => $el['product_id']]);
				}
			}

			return $acc;
		}, []);

		return $sheet_metal_cuttings;
	}

	public function index()
	{
		$stock = $this->getAll();

		return response()->json($stock, 200);
	}

	public function store(Request $request)
	{
		$sheet_metal_cutting = $request->new_sheet_metal;

		$request->validate([
			'new_sheet_metal.id' => 'required',
			'new_sheet_metal.quantity' => 'required',
			'new_sheet_metal.mts' => 'required',
		]);

		$whole_sheet_metal = Product::where('id', $sheet_metal_cutting['id'])
			->where('mts_by_unity', floatval($sheet_metal_cutting['mts']))->get()->first();

		if ($whole_sheet_metal) {
			$product = $whole_sheet_metal;
			$product->stock = $product->stock + $sheet_metal_cutting['quantity'];
			$product->save();
			$product->refresh();

			product_history($product, 'agrega stock', auth()->user()->id, ['cantidad' => $sheet_metal_cutting['quantity']], $product->stock);

			return response()->json($product->stock, 201);
		}

		$exists_sheet_metal_cutting = ProductStock::where('product_id', $sheet_metal_cutting['id'])
			->where('mts', floatval($sheet_metal_cutting['mts']))->get()->first();

		if ($exists_sheet_metal_cutting) {
			$exists_sheet_metal_cutting->quantity = $exists_sheet_metal_cutting->quantity + $sheet_metal_cutting['quantity'];
			$exists_sheet_metal_cutting->save();

			$stock = $this->getAll();
			//product_history($exists_sheet_metal_cutting, 'agrega stock', auth()->user()->id, ['mts' => $sheet_metal_cutting['mts'], 'cantidad' => $sheet_metal_cutting['quantity']], $exists_sheet_metal_cutting->stock);

			return response()->json($stock, 201);
		}

		$new = new ProductStock;
		$new->product_id = $sheet_metal_cutting['id'];
		$new->quantity = $sheet_metal_cutting['quantity'];
		$new->mts = $sheet_metal_cutting['mts'];
		$new->save();

		$stock = $this->getAll();

		return response()->json($stock, 201);
	}

	public function delete_sheet_metal_cuttings()
	{
		$resp = ProductStock::truncate();

		return response()->json('ok', 200);
	}

	public function delete_single_sheet_metal_cutting()
	{
		$stock = ProductStock::where('product_id', request()->product_id)->where('mts', request()->mts)->get();

		$stock->map(function ($smc) use ($stock) {

			$smc->delete();

			product_history($stock, 'se elimina recorte', auth()->user()->id, $stock->toArray());
		});

		$stock = $this->getAll();

		return response()->json($stock, 200);
	}

	public function get_sheet_metal_cuttings()
	{
		$stock = ProductStock::where('product_id', request()->id)->orderBy('mts')->get();

		return fractal($stock, new SheetMetalCuttingsTransformer())->toArray()['data'];
	}
}
