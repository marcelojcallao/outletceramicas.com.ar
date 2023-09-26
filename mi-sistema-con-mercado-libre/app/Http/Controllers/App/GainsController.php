<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GainsController extends Controller
{
    public function list()
    {
        return view('app.gains.list')->with(['view_name' => 'Rentabilidad por productos']);
    }
}
