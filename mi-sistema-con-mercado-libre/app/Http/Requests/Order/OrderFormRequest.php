<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class OrderFormRequest extends FormRequest
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
        $order = $this->request->get('order');

        $products = $order['products'];

        $rules = [
            'order.customer' => ['required'],
            'order.date' => ['required'],
            'order.delivery_date' => ['required'],
            'order.payment' => ['required'],
            'order.products' => ['required'],
        ];
        
        collect($products)->map(function($product, $index) use ($rules){
            $rules['product.name'] = 'required';
            $rules['product.code'] = 'required';
            $rules['product.price_list'] = 'required';
            $rules['product.quantity'] = 'required';
        });

        return $rules;
    }

    public function messages()
    {
        return [
            'order.customer.required' => 'El campo Cliente es obligatorio.',
            'order.date.required' => 'El campo fecha es obligatorio.',
            'order.delivery_date.required' => 'El campo fecha de entrega es obligatorio.',
            'order.payment.required' => 'El campo modo de pago es obligatorio.',
            'order.products.required' => 'El pedido debe tener al menos un producto.',
        ];
    }
}
