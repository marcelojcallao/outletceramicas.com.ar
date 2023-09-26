<?php

namespace App\Observers;

use App\Src\Models\Customer;
use App\Src\Afip\WS\AfipWSPadron;

class CustomerObserver
{
    private $afip_padron;

    public function __construct()
    {
        $this->afip_padron = new AfipWSPadron('production');
    }

    public function created(Customer $customer)
    {
        /* $afip_data = $this->afip_padron->getPersona($customer->number);

        $dni = $customer->number;
        
        $customer->number = $afip_data['idPersonaListReturn']['idPersona'];
        $customer->afip_data = $afip_data;
        $customer->save();

        activity('OK')
            ->performedOn($customer)
            ->causedBy(auth()->user()->id)
            ->withProperties(['DNI' => $dni, 'CUIL/CUIT' => $customer->number])
            ->log('SE ACTUALIZA CUIL/CUIT'); */
    }
}
