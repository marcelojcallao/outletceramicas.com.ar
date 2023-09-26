<?php

namespace App\Transformers\Products;

use App\Src\Traits\Product\SheetMetalCuttingsTrait;
use League\Fractal\TransformerAbstract;

class FindProductByNameTransformer extends TransformerAbstract
{
	use SheetMetalCuttingsTrait;

	const SALE_MAN = 3;

	public function price_list($product)
	{
		return $product->pricelists->map(function ($pr, $index) {

			if ($pr->pivot->enabledPriceListToProduct) {
				return [
					'pricelist_id' => $pr->id,
					'product_id' => $pr->pivot->product_id,
					'name' => $pr->name,
					'price' => '$ ' . number_format($pr->pivot->price, 2, ',', '.'),
					'price_raw' => $pr->pivot->price,
				];
			}
		})->filter()->values()->toArray();
	}

	public function isCHP($pr)
	{

		if (strpos(strtoupper($pr->name), 'CHAPA LISA') !== false) {
			return false;
		}

		if ($pr->isCHP) {
			return $pr->isCHP;
		}

		return collect($pr->categories_path)->map(function ($cat) {
			return collect($cat)->map(function ($cat_child) {

				$code = explode('-', $cat_child['code']);

				return collect($code)->map(function ($i) {
					if ($i == 'CHP') return true;
				})->filter()->first();
			})->filter()->first();
		})->first();
	}

	public function setPrice($product)
	{
		return $product->pricelists->map(function ($lp) {
			if ($lp->enable == 1 && $lp->pivot->price > 0 && $lp->pivot->benefit > 0) {
				return [
					'price_list_id' => $lp->id,
					'product_id' => $lp->product_id,
					'name' => $lp->name,
					'price' => $lp->pivot->costo,
					'benefit' => $lp->pivot->benefit,
					'import' => $lp->pivot->price,
					'price_raw' => $lp->pivot->price,
				];
			}
		})->filter()->sortBy('name')->values()->toArray();
	}

	public function transform($pr)
	{
		return [
			'id' => $pr->id,
			'name' => ($pr->stock == 0 && !$this->unionSheetMetalCuttingByMts($pr)) ? "{$pr->name} - SIN STOCK" : strtoupper($pr->name),
			'code' => $pr->code,
			'price_list' =>  $this->price_list($pr),
			'stock' => $pr->stock,
			'sheet_metal_cuttings' => $this->unionSheetMetalCuttingByMts($pr),
			'mts_by_unity' => $pr->mts_by_unity,
			'critical_stock' => $pr->critical_stock,
			'thumbnail' => ($pr->publication()->exists()) ? $pr->publication->thumbnail : '',
			'isCHP' => $pr->isCHP,
			//'$isDisabled' => ($pr->stock == 0) ? true : false,
			'isCriticalStock' => ($pr->stock <= $pr->critical_stock) ? true : false,
			'apply_discount' => $pr->apply_discount,
			'apply_discount_from' => $pr->apply_discount_from,
			'apply_discount_percentage' => $pr->apply_discount_percentage,
			'apply_discount_pay_method' => $pr->apply_discount_pay_method
		];
	}
}
