<?php

namespace App\Src\Traits\Product;

trait SheetMetalCuttingsTrait
{

	protected function findIndexSmcByMts($mts, $array)
	{

		foreach ($array as $key => $smc) {
			if ((float)$mts == (float)$smc['mts']) {
				return $key;
			}
		}

		return false;
	}

	public function unionSheetMetalCuttingByMts($pr)
	{
		$sheet_metal_cuttings = [];

		if ($pr->isCHP) {

			$sheet_metal_cuttings = $pr->stocks->reduce(function ($acc, $el) use ($sheet_metal_cuttings) {

				$index = $this->findIndexSmcByMts($el['mts'], $acc);

				if (($index) || $index === 0) {
					if ((int)$el['quantity'] && (int)$el['quantity'] > 0) {
						$acc[$index]['quantity'] = (int)$acc[$index]['quantity'] + (int)$el['quantity'];
					}
				} else {
					if ((int)$el->quantity > 0) {
						array_push($acc, [
							'mts' => $el->mts,
							'quantity' => $el->quantity,
							'product_id' => $el->product_id
						]);
					}
				}


				return $acc;
			}, []);

			array_push($sheet_metal_cuttings, [
				'mts' => $pr->mts_by_unity,
				'quantity' => $pr->stock,
				'product_id' => $pr->id
			]);

			return collect($sheet_metal_cuttings)->sortBy('mts')->toArray();
		}

		return false;
	}
}
