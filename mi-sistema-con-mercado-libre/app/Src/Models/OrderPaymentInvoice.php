<?php

namespace App\Src\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPaymentInvoice extends Model
{
    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo( OrderPayment::class, 'order_payment_id', 'id' );
    }

    public function invoice()
    {
        return $this->hasOne( PurchaseInvoice::class, 'invoice_id', 'id');
    }
}
