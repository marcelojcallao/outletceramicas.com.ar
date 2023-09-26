<?php

namespace App\Src\Models;

use Illuminate\Database\Eloquent\Model;

class CheckingAccount extends Model
{
    protected $guarded = [];

    public function checkBooks()
    {
        return $this->hasMany( CheckBook::class, 'check_book_id', 'id');
    }

    public function bank()
    {
       return $this->hasOne(Bank::class, 'id', 'bank_id');
    }
}
