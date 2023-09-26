<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentTypeController extends Controller
{
    public function list()
    {
        return view('app.admin.payment-type')->with(['view_name' => 'Modo de pagos']);
    }
}
