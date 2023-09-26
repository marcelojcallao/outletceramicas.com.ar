<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class CustomerFromModalRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'customer.name' => 'required',
            'customer.dni' => 'required|unique:customers,number',

        ];
    }

    public function messages()
    {
        return [
            'customer.name.required' => 'El campo nombre es requerido.',        
            'customer.dni.required' => 'El campo DNI es requerido.',        
            'customer.dni.unique' => 'Existe un cliente con ese número de DNI.',         
        ];
    }
}
