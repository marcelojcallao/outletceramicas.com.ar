<?php

namespace App\Src\Traits;

use App\Src\Models\SaleInvoice;

trait CustomerAddressTrait
{
    public function customer_address_at_sale_invoice(SaleInvoice $si)
    {
        if ($si->customer->addresses()->exists()) {
            $first = $si->customer->addresses->first();

            $state = '';

            if (! is_null($first->state)) {
                if (! is_null($first->state->name)) {
                    $state = "- {$first->state->name}";
                }
            }

            $address = $first->address;
            $city = (! is_null($first->city)) ? "- {$first->city}" : '';
            $cp = (! is_null($first->cp)) ? "- ({$first->cp})" : '';

            return "{$address} {$city} {$cp}  {$state}";
        }

        return '';
    }
}
