<?php

namespace App\Src\Models;

use Illuminate\Database\Eloquent\Model;

class CheckBookItem extends Model
{
    protected $guarded = [];

    public function chequera()
    {
        return $this->belongsTo( CheckBook::class, 'check_book_id', 'id');
    }
}
