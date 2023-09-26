<?php

namespace App\Src\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPayment extends Model
{
    protected $guarded = [];

    public function invoices()
    {
        return $this->hasMany(OrderPaymentInvoice::class, 'order_payment_id', 'id');
    }

    public function cheques()
    {
        return $this->hasMany(CheckBookItem::class, 'order_payment_id', 'id');
    }
}
