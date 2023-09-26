<?php

namespace App\Src\Repositories\App;

use App\Src\Models\Comment;
use App\Src\Models\Status;
use App\Src\Models\Product;
use App\Src\Models\SaleInvoice;
use App\Src\Traits\MoneyFormatTrait;
use App\Src\Repositories\App\AppBaseRepository;
use App\Src\Traits\DateFormatTrait;
use Jenssegers\Date\Date;

class SaleInvoiceRepository extends AppBaseRepository
{
	use MoneyFormatTrait, DateFormatTrait;

	public function create($FECAESolicitarResult, $pedido_cliente_id = null, $products, $invoices = null, $tributos, $comments = null)
	{
		$cbt_fch = Date::createFromFormat('Ymd', $FECAESolicitarResult['cbte_fch'])->format('Y-m-d');

		$si = new SaleInvoice;
		$si->company_id = $FECAESolicitarResult['company_id'];
		$si->customer_id = $FECAESolicitarResult['customer_id'];
		$si->doc_nro = $FECAESolicitarResult['doc_nro'];
		$si->voucher_id = $FECAESolicitarResult['voucher_id'];
		$si->pto_vta = $FECAESolicitarResult['pto_vta'];
		$si->cbte_desde = $FECAESolicitarResult['cbte_desde'];
		$si->cbte_hasta = $FECAESolicitarResult['cbte_hasta'];
		$si->cbte_fch = $cbt_fch;
		$si->cae = $FECAESolicitarResult['cae'];
		$si->cae_fch_vto = $FECAESolicitarResult['cae_fch_vto'];
		$si->iibb_percentage = ($tributos == '' || $tributos == []) ? 0 : floatval(str_replace(',', '.', $tributos['Tributo']['Alic']));
		$si->percerp_iibb = ($tributos == '' || $tributos == []) ? 0 : floatval($tributos['Tributo']['Importe']);
		$si->status_id = Status::PENDIENTE; // $FECAESolicitarResult['status_id'];
		$si->pedido_cliente_id = $pedido_cliente_id;
		$si->user_id = auth()->user()->id;
		$si->afip_data = collect($FECAESolicitarResult)->toArray();

		$si->save();

		$si->refresh();

		if (array_key_exists('detail', $products)) {
			$si->items()->create([
				'sale_invoice_id' => $si->id,
				'quantity' => $products['quantity'],
				'neto_import' => $products['Neto'],
				'iva_import' => $products['IvaImport'],
				'iva_id' => $products['iva_id']['iva_id'],
				'discount' => 0,
				'unit_price' => $products['unit_price'],
				'discount_import' => $products['discount_import'],
				'total' => $products['ImpTotal'],
				'obs' => $products['detail'],

			]);

			$s = SaleInvoice::find($products['iva_id']['sale_invoice']['id']); //Ver por que viene de iva_id
			$s->status_id = 19; //19 = cerrada
			$s->save();

			$si->status_id = 19;
			$si->save();
		} else {

			collect($products)->each(function ($item) use ($si) {
				//dd($item);
				$product_id = null;
				$from_meli = substr($item['product_id'], 0, 3);
				if ($from_meli == 'MLA') {
					$product = Product::where('meli_id', $item['product_id'])->get();
					$product_id = $product->first()->id;
				} else {
					$product_id = $item['product_id'];
				}
				if ($item['product_id'] != '') {
					if ($item['quantity'] == 0 || is_null($item['quantity'])) {
						$quantity = 1;
					} else {
						$quantity = $item['quantity'];
					}

					$costo = (float)$item['gains']['costo'] * $quantity;

					$si->items()->create([
						'sale_invoice_id' => $si->id,
						'product_id' => $product_id,
						'quantity' => $item['quantity'],
						'neto_import' => $item['neto_import'],
						'iva_import' => $item['iva_import'],
						'iva_id' => ($item['iva_id']),
						'unit_price' => $item['unit_price'],
						'isCHP' => $item['isCHP'],
						'real_mts' => (float) $item['real_mts'],
						'mts_by_unity' => ($quantity == 0) ? 0 : (float) $item['real_mts'] / $quantity,
						'mts_to_invoiced' => (float) $item['mts_to_invoiced'],
						//'iva_id' => ($item['iva_id']) ? $item['iva_id'] : $item['iva_afip_code'],
						'discount' => $item['discount'],
						'discount_import' => $item['discount_import'],
						'total' => ($item['neto_import'] +  $item['iva_import']),
						//'total' => $this->CurrencyToMySqlFormat($item['total']),
						'price_list_id' => $item['gains']['pricelist_id'],
						'benefit' => $item['gains']['benefit'],
						'costo' => $item['isCHP'] ? (float)$item['gains']['costo'] * (float) $item['mts_to_invoiced'] : $costo,
						'earning' => $item['isCHP'] ? (float)$item['neto_import'] - (float)$item['gains']['costo'] * (float) $item['mts_to_invoiced'] : (float)$item['neto_import'] - (float)$costo,
						'voucher_id' => $si->voucher_id
					]);
				}
			});
		}

		collect($invoices)->each(function ($item) use ($si) {
			if ($item['id'] != '') {
				$si->items()->create([
					'sale_invoice_id' => $si->id,
					'neto_import' => $item['description']['neto'],
					'iva_import' => $item['description']['iva'],
					// corregir iva_id, puede haber dos comprobantes
					// con distintos ivas en una nota crédito/débito
					'iva_id' => 6, //21%
					'total' => $item['description']['total'],
					'obs' => $item['description']['detail'],
				]);
			}
		});

		if ($comments) {
			$comment = new Comment();

			$comment->description = $comments;
			$comment->commentable_id = $si->id;
			$comment->commentable_type = 'App\Src\Models\SaleInvoice';
			$comment->user_id = auth()->user()->id;
			$comment->user_name = auth()->user()->name;
			$comment->save();
		}

		return $si;
	}
}
