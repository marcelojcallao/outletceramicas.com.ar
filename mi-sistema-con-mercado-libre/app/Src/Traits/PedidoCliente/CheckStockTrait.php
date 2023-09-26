<?php

namespace App\Src\Traits\PedidoCliente;

use App\Src\Models\Product;
use App\Src\Models\ProductStock;
use Illuminate\Support\Facades\Log;

trait CheckStockTrait
{

	public function add_stock($product, $quantity = 1, $mts)
	{
		$sheet_metal_cutting = new ProductStock();
		$sheet_metal_cutting->product_id = $product->id;
		$sheet_metal_cutting->quantity = $quantity;
		$sheet_metal_cutting->mts = $mts;
		$sheet_metal_cutting->save();
		$sheet_metal_cutting->fresh();
	}

	public function update_stock($remito)
	{
		$remito->items->map(function ($remito_item) {

			$product = Product::find($remito_item->product->id);

			if ($product->isCHP) {

				$product->stocks->delete();

				$remito_item->sheet_metal_cuttings->map(function ($smc) use ($product) {

					if ((float)$smc->mts != (float)$product->mts_by_unity) {

						$this->add_stock($product, $smc->quantity, $smc->mts);
					} else {
						$product->update(['stock' => $smc->quantity]);
					}
				});
			} else {
				$stock = $product->stock - $remito_item->stock;
				Log::info(" Stock del producto {$product->stock} actual, le descuento {$stock} - remito item {$remito_item->stock}");
				$product->stock = $stock;
				$product->save();
			}

			event('eloquent.updated: ' . Product::class, $product);
		});
	}

	public function check_available_stock($remito)
	{
		$error_message = collect();
		$error_product = collect();

		$remito->items->map(function ($remito_item) use ($error_message, $error_product) {

			$product = $remito_item->product;

			if ($remito_item->is_chp) {

				// acá controlo recortes
				if (floatval($remito_item->mts) < floatval($product->mts_by_unity)) {
					$product->stocks->map(function ($smc) {
					});
				}
			} else {

				\Log::info('$remito_item->product->name');
				\Log::info($remito_item->product->name);
				\Log::info('stock actual ' . (int)$remito_item->product->stock . ' se resta ' . (int)$remito_item->quantity . ' quedan ' . ((int)$remito_item->product->stock - (int)$remito_item->quantity));

				/*  if((int)$remito_item->product->stock == 0){
                    throw new \Exception("No hay stock para el producto: {$remito_item->product->name}", 431);
                } */

				$stock = ((int)$remito_item->product->stock - (int)$remito_item->quantity);

				if ($stock < 0) {
					$this->error_stock = true;
					$error_product->push(['produc_id' => $remito_item->product->id, 'stock' => $remito_item->product->stock]);

					if ((int)$remito_item->product->stock == 0) {
						$error_message->push("No hay stock para el producto: {$remito_item->product->name}. cut_flag");
					} else {
						$error_message->push("{$remito_item->product->name} stock actual {$remito_item->product->stock} , el pedido requiere {$remito_item->quantity} unidades. Hacen falta " . ((int)$remito_item->quantity - (int)$remito_item->product->stock) . ' unidades para completar el pedido. cut_flag');
					}
				}
			}
		});

		if ($this->error_stock) throw new \Exception("{$error_message->implode('-')} | {$error_product->toJson()}", 431);

		return true;
	}
}
