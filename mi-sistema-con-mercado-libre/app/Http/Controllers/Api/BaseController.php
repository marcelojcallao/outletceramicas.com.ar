<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Src\Traits\ErrorTrait;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    use ErrorTrait;

    protected $auth_user;

    public function __construct()
    {
        $this->auth_user = auth()->guard('api')->user();

    }
}
