<?php

namespace App\Http\Requests\SheetMetal;

use Illuminate\Foundation\Http\FormRequest;

class SheetMetalCuttingRequest extends FormRequest
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
            'new_sheet_metal.id' => 'required',
            'new_sheet_metal.quantity' => 'required',
            'new_sheet_metal.mts' => 'required',
        ];
    }

    function messages()
    {
        return [
            'new_sheet_metal.provider.id.required' => 'La chapa es obligatoria.',        
            'new_sheet_metal.quantity.required' => 'La cantidad es requerida.',        
            'new_sheet_metal.mts' => 'Los metros son requeridos.',        
        ];
    }
}
