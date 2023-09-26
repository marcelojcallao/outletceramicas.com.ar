<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CashFlowController extends Controller
{
    public function cash_flow()
    {
        return view('app.cash_flow.cash')->with(['view_name' => 'Movimientos diarios']);
    }
}
