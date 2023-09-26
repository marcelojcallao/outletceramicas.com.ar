<?php

namespace App\Src\Models;

use Illuminate\Database\Eloquent\Model;

class CheckBook extends Model
{
    protected $guarded = [];

    public function cheques()
    {
        return $this->hasMany( CheckBookItem::class , 'check_book_id', 'id');
    }

    public function checkingAccount()
    {
        return $this->belongsTo( CheckingAccount::class, 'checking_account_id', 'id');
    }
}
