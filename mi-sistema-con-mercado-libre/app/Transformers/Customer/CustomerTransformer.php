<?php

namespace App\Transformers\Customer;

use App\Src\Models\City;
use App\Src\Models\Customer;
use League\Fractal\TransformerAbstract;

class CustomerTransformer extends TransformerAbstract
{
    public function addresses($customer)
    {
        if ($customer->addresses->isEmpty()) {
            return false;
        }
        
        return $customer->addresses->map(function($address){

            return [

                'id' => $address->id,
                
                'country' => 'AGENTINA',

                'state_id' => $address->state['id'] ,

                'state' =>  $address->state['name'] ,
                            
                //'city_id' =>  City::find($address->city_id)->id,
                
                'city' =>  $address->city,
                
                'obs' =>  $address->obs,
                            
                'cp' =>  $address->cp,
                            
                'domicilio' =>  strtoupper($address->address),

                'between_streets' => strtoupper($address->between_streets),

                'name' => strtoupper($address->address) . ', ' . $address->city . ', ' .  $address->cp  . ' ' . $address->state['name'] . ', AGENTINA' 
            ];

        });

    }
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Customer $customer)
    {
        return [
            'id' => $customer->id,

            'number' => $customer->number,

            'name' => strtoupper($customer->name),

            'purchaser_document_id' => (($customer->purchaserDocument()->exists())) ? $customer->purchaserDocument->id : '',
            
            'purchaser_document' => (($customer->purchaserDocument()->exists())) ? $customer->purchaserDocument->name : '',

            'inscription_id' => (($customer->inscription()->exists())) ? $customer->inscription_id : '',
            
            'inscription' => (($customer->inscription()->exists())) ? $customer->inscription->name : '',

            'contact' => $customer->contact,

            'cell_phone' => $customer->cell_phone,

            'phone_1' => $customer->phone_1,

            'phone_2' => $customer->phone_2,

            'phone_3' => $customer->phone_3,

            'email' => $customer->email,

            'addresses' => $this->addresses($customer),

            'customer_has_afip_data' => $customer->has_afip_data,
        ];
    }
}
