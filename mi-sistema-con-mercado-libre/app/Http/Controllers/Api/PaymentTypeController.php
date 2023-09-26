<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Src\Models\PaymentType;
use App\Http\Controllers\Controller;
use Exception;

class PaymentTypeController extends Controller
{
    public function index()
    {
        $pay_methods = PaymentType::orderBy('name')->get();

        return response()->json($pay_methods, 200);
    }

    public function updatePayment()
    {
        /* $validate_update = PaymentType::where('name', request()->name)->get();

        if ($validate_update->isNotEmpty()) {
            throw new Exception('Ya existe un método de pago con éste nombre', 431);
        } */

        $payment = PaymentType::find( request()->id );


        $payment->name = strtoupper( request()->name );
        $payment->percentage = request()->percentage;
        $payment->save();   
        $payment->fresh();   

        $history_data = [
            'payment_type_id' => $payment->id,
            'log_name' => strtoupper('actualiza'),
            'status_id' => $payment->status_id,
            'user_id' => auth()->user()->id,
            'data' => $payment->toArray()
        ];

        save_history($payment, $history_data);      

        return response()->json($payment, 200);
    }

    public function enable()
    {
        $payment = PaymentType::find( request()->id );

        $payment->status_id = request()->status_id;
        $payment->save();   
        $payment->fresh();   

        $history_data = [
            'payment_type_id' => $payment->id,
            'log_name' => ( $payment->status_id == 1) ? strtoupper('habilita') : strtoupper('dehabilita'),
            'status_id' => $payment->status_id,
            'user_id' => auth()->user()->id,
            'data' => $payment->toArray()
        ];

        save_history($payment, $history_data);      

        return response()->json($payment, 200);
    }

    public function delete_payment_type()
    {
        $payment = PaymentType::find( request()->id );

        $payment->status_id = request()->status_id;
        $payment->delete();   

        $history_data = [
            'payment_type_id' => $payment->id,
            'log_name' => strtoupper('elimina'),
            'status_id' => $payment->status_id,
            'user_id' => auth()->user()->id,
            'data' => $payment->toArray()
        ];

        save_history($payment, $history_data);      

        return response()->json($payment, 200);
    }

    public function store()
    {
        $payment = new PaymentType;

        $payment->name = strtoupper( request()->name );
        $payment->percentage = request()->percentage;
        $payment->status_id = 1;
        $payment->save();   

        $history_data = [
            'payment_type_id' => $payment->id,
            'log_name' => strtoupper('creado'),
            'status_id' => $payment->status_id,
            'user_id' => auth()->user()->id,
            'data' => $payment->toArray()
        ];

        save_history($payment, $history_data);      

        return response()->json($payment, 201);
    }
}
