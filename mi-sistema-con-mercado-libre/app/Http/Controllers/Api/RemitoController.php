<?php

namespace App\Http\Controllers\Api;

use App\Models\RemitoItem;
use App\Src\Models\Remito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Src\Traits\DateFormatTrait;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Src\Models\PedidoCliente;
use App\Src\Models\Product;
use App\Transformers\Remito\RemitoTransformer;
use App\Src\Traits\PedidoCliente\CheckStockTrait;

class RemitoController extends Controller
{
	use DateFormatTrait, CheckStockTrait;

	const NEW_SHEET_METAL_MTS = 13;

	const PENDIENTE = 2;

	private $error_stock;

	public function __construct()
	{
		$this->error_stock = false;
	}

	public function store()
	{
		$remito = request()->remito;

		$pedido = PedidoCliente::find($remito['pedido_cliente_id']);
		/*
		if ($this->check_available_stock($pedido)) {
			dd('ok');
		} */
		DB::beginTransaction();

		try {

			$r = new Remito;
			$r->save();
			$r->fresh();

			$number = $r->number();

			$r->customer_id = $remito['customer_id'];
			$r->pedido_cliente_id = $remito['pedido_cliente_id'];
			$r->code = 'RT-' . $this->code() . '-' . $remito['customer_id'] . '-' . $number;
			$r->number = $number;
			$r->delivery_date = $remito['delivery_date'];
			$r->payment_type_id = $remito['payment_type_id'];
			$r->total = $remito['total'];
			$r->status_id = self::PENDIENTE;
			$r->user_id = auth()->user()->id;
			$r->save();
			$r->fresh();

			collect($remito['items'])->map(function ($item) use ($r) {

				$remitoItem = new RemitoItem();
				$remitoItem->remito_id = $r->id;
				$remitoItem->quantity = $item['quantity'];
				$remitoItem->mts = $item['mts_to_invoiced'];
				$remitoItem->real_mts = $item['real_mts'];
				$remitoItem->rounded_mts = $item['rounded_mts'];
				$remitoItem->product_id = $item['product_id'];
				//$remitoItem->unit_price = (float)$item['neto_import'] / (float)$item['quantity'];
				$remitoItem->neto = $item['neto_import'];
				$remitoItem->iva = $item['iva_import'];
				$remitoItem->total = $item['total_raw'];
				$remitoItem->isCHP = $item['isCHP'];

				$remitoItem->save();

				$product = Product::find($item['product_id']);

				if ($product->isCHP) {
					if (array_key_exists('sheet_metal_cuttings', $item)) {

						$product->stocks()->delete();

						collect($item['sheet_metal_cuttings'])->map(function ($smc) use ($product, $item) {

							if ((string)$smc['mts'] != (string)$product->mts_by_unity) {
								$this->add_stock($product, $smc['quantity'], $smc['mts']);
							} else {

								$product->stock = $smc['quantity'];

								$product->save();
							}
						});
					}
				} else {

					$stock = $product->stock - $item['quantity'];

					$product->stock = $stock;

					$product->save();
				}

				$product->refresh();

				event('eloquent.updated: ' . Product::class, $product);
			});

			$r->fresh();

			$remito = fractal($r, new RemitoTransformer())->toArray()['data'];

			DB::commit();

			return response()->json($remito, 201);
		} catch (\Exception  $e) {

			DB::rollback();

			Log::info("Error en el remito");
			Log::info($e);
			Log::info("Error en el remito");
			Log::info("");
			throw $e;
		}
	}
}
