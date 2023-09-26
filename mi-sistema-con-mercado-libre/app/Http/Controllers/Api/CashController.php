<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Src\Models\PettyCash;
use App\Transformers\Cash\CashTransformer;

class CashController extends Controller
{
	public function index()
	{
		$petty_cash = PettyCash::all();

		$petty_cash = fractal($petty_cash, new CashTransformer())->toArray()['data'];

		$data = [
			'petty_cash' => $petty_cash,
			'saldo' => PettyCash::sum('import')
		];


		return response()->json($data, 200);
	}

	public function store()
	{
		$movement = request()->all();

		$cash = new PettyCash;
		$cash->type_id = $movement['type']['id'];
		$cash->description = $movement['description']['name'];
		$cash->date = $movement['date'];
		$cash->import = ($movement['type']['id'] == 1) ?  $movement['import'] : $movement['import'] * -1;

		$cash->save();
		$cash->fresh();
		$petty_cash = PettyCash::all();

		$petty_cash = fractal($petty_cash, new CashTransformer())->toArray()['data'];

		$data = [
			'petty_cash' => $petty_cash,
			'saldo' => PettyCash::sum('import')
		];

		return response()->json($data, 200);
	}

	public function delete()
	{
		$cash = PettyCash::find(request()->id);

		$cash->delete();

		return response()->json($cash, 200);
	}
}
