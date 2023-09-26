<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;

class ProductsFormRequest extends FormRequest
{
    
    public function authorize()
    {
        return true;
    }

    public function prepareForValidation ()
    {
        if ($this->has('file')) {
            $this->merge([
                'product' => json_decode($this->product, true),
            ]);
            $this->merge([
                'categories_path' => json_decode($this->categories_path, true),
            ]);
            $this->merge([
                'selected_categories_from_root' => json_decode($this->selected_categories_from_root, true),
            ]);
        }
    }
    
    public function rules()
    {
        //dd(gettype($this->product), json_decode($this->product, true));
        return [
            'categories_path' => ['required'],
            'product.code' => ['required', 'unique:products,code,' . $this->product['id']],
            'product.critical_stock' => ['required', 'numeric'],
            'product.name' => ['required', 'min:5', 'max:100', 'unique:products,name,' . $this->product['id']],
            'product.price_base' => ['required'],
            //'product.price_base' => ['required', 'regex:[0-9]+(\.[0-9][0-9]?)?'],
            'product.supplier' => ['required'],
            'selected_categories_from_root' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'categories_path.required' => 'El producto debe pertenecer a una categoría.',
            'product.code.required' => 'El producto debe poseer un código.',
            'product.code.unique' => 'Este código ya pertenece a otro producto.',
            'product.critical_stock.required' => 'El producto debe poseer un stock crítico.',
            'product.name.required' => 'El producto debe poseer un nombre.',
            'product.name.unique' => 'Ya se encuentra un producto con este nombre.',
            'product.name.min' => 'El nombre del producto debe contener más de 5 caracteres.',
            'product.name.max' => 'El nombre del producto debe contener menos de 100 caracteres.',
            'product.price_base.required' => 'El producto debe poseer el costo.',
            'product.supplier.required' => 'Se debe informar quien es el proveedor del producto.',
            'selected_categories_from_root.required' => 'Se debe informar la ruta de categorías del producto.',
        ];
    }
}
