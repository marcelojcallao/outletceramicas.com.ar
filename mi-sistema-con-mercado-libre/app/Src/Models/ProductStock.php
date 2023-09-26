<?php

namespace App\Src\Models;

use App\Src\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;

class ProductStock extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;

	protected $guarded = [];

	public function product(): BelongsTo
	{
		return $this->belongsTo(Product::class);
	}
}
