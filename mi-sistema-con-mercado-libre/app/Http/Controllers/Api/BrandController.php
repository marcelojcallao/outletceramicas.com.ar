<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BrandController extends Controller
{
    public function index()
    {
        return response()->json(Brand::all(), 200);
    }

    
}
