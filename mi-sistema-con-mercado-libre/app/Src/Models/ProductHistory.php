<?php

namespace App\Src\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class ProductHistory extends Model
{
    protected $guarded = [];

    protected $casts = [
        'data' => 'array'
    ];

	public function user()
	{
		return $this->hasOne(User::class, 'id', 'user_id');
	}
}
