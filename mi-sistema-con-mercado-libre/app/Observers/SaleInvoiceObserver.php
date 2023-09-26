<?php

namespace App\Observers;

use App\Src\Models\SaleInvoice;
use App\Src\Traits\SaleInvoice\SaleInvoiceHistoryTrait;


class SaleInvoiceObserver
{
    use SaleInvoiceHistoryTrait;
    
    public function created(SaleInvoice $si)
    {
        $data = $this->history($si, 'NEW_SALE_INVOICE');

        $si->history()->create($data);
    }

    public function updated(SaleInvoice $si)
    {
        $data = $this->history($si, 'UPDATE_SALE_INVOICE');

        $si->history()->create($data);
    }
}
