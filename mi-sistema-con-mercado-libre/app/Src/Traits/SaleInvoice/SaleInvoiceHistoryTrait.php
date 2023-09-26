<?php

namespace App\Src\Traits\SaleInvoice;

trait SaleInvoiceHistoryTrait 
{
    public function history($si, $history_text)
    {
        return [
            
            'data' => [
                'type' => $history_text,
                'sale_invoice'=>  $si,
                'items' => $si->items,
            ]
        ];
    }
}
