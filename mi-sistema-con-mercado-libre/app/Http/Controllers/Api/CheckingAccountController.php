<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Src\Models\CheckingAccount;
use App\Transformers\CheckAccount\CheckAccountTransformer;

class CheckingAccountController extends Controller
{

    public function getAll()
    {
        $checkingAccounts =  CheckingAccount::all();

        $checkingAccounts = fractal($checkingAccounts, new CheckAccountTransformer())->toArray()['data'];

        return $checkingAccounts;
    }

    public function index()
    {
        $checkingAccounts = $this->getAll();

        return response()->json($checkingAccounts, 200);
    }

    public function store()
    {
        $checkingAccount = new CheckingAccount();

        $checkingAccount->bank_id = request()->bank['id'];
        $checkingAccount->code = request()->code; 
        $checkingAccount->active = true;

        $checkingAccount->save(); 

        $checkingAccount = $this->getAll();

        return response()->json($checkingAccount, 201);

    }

    
}