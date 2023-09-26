<?php

namespace App\Transformers\Products;

use App\Src\Models\ProductHistory;
use App\Src\Traits\DateFormatTrait;
use League\Fractal\TransformerAbstract;

class StockMovementsTransformer extends TransformerAbstract
{
	use DateFormatTrait;

	public function getStockDetail(ProductHistory $history)
	{
		if ($history->log_name === 'CREACIóN' || $history->log_name === 'ACTUALIZA') {
			return [
				'cantidad' => $history->data['stock']
			];
		}

		return $history->data;
	}

    public function transform(ProductHistory $history)
    {
        return [
            'product_id' => $history->product_id,
			'name' => $history->log_name,
			'data' => $this->getStockDetail($history),
			'user' => $history->user->name,
			'actual_stock' => $history->stock,
			'date' => $this->LongDateToArgentinaFormat($history->created_at)
        ];
    }
}
