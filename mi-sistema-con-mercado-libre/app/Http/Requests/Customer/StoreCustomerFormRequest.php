<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'customer.name' => 'required',
            'customer.number' => ['required', 'unique:customers,number,' . $this->customer['id']],
            'customer.inscription' => 'required',
            'customer.purchase_document' => 'required',
            /* 'customer.address.address' => 'sometimes|required',
            'customer.address.city' => 'sometimes|required',
            'customer.address.cp' => 'sometimes|required',
            'customer.address.state' => 'sometimes|required', */
        ];
    }

    public function messages()
    {
        return [
            'customer.name.required' => 'El nombre es requerido',
            'customer.number.required' => 'El número de DNI-CUIT-CUIL es requerido', 
            'customer.number.unique' => 'Este DNI-CUIT-CUIL ya existe en la base de datos', 
            'customer.inscription.required' => 'El tipo de inscripción es requerido',
            'customer.purchase_document.required' => 'El tipo de documento es requerido',
            'customer.address.address.required' => 'El domicilio es requerido',
            'customer.address.city.required' => 'La localidad es requerida',
            'customer.address.cp.required' => 'El código postal es requerido',
            'customer.address.state.required' => 'La provincia es requerida',
        ];
    }
}
