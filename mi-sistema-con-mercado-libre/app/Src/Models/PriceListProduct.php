<?php

namespace App\Src\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PriceListProduct extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;

	protected $table = 'pricelist_products';

	protected static $logName = 'PriceListProduct Pivot';

	/**
	 * Get the lp associated with the PriceListProduct
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function lp(): HasOne
	{
		return $this->hasOne(PriceList::class, 'id', 'pricelist_id');
	}
}
