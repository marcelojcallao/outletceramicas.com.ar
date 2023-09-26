<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeliveryAddressRequest extends FormRequest
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
        //dd($this);
        return [
            'address.address.state' => 'required',
            'address.address.city' => 'required',
            'address.address.cp' => 'required',
            'address.address.address' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'address.address.state.required' => 'El campo Provincia es requerido.',        
            'address.address.city.required' => 'El campo Localidad es requerido.',        
            'address.address.cp.required' => 'El campo Código postal es requerido',        
            'address.address.address.required' => 'El campo Calle es requerido.',  
        ];
    }
}
