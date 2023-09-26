<?php

namespace App\Src\Traits;

use AddTypeFieldToDailyMovements;
use App\Src\Models\PriceListProduct;
use App\User;
use App\Src\Models\Status;
use App\Src\Models\WebHookQuestion;
use App\Src\Traits\MoneyFormatTrait;
use App\Src\Traits\Product\SheetMetalCuttingsTrait;
use Illuminate\Support\Facades\Log;

trait PedidoClientesTransformerTrait
{
	use MoneyFormatTrait, ZeroLeftTrait, DateFormatTrait, SheetMetalCuttingsTrait;

	/* public function first_contact($pc)
    {
        return $pc->statuses->sortBy('created_at')->map(function($status){
            if ($status->status_id == Status::PRIMER_CONTACTO && $status->description != null) { //
                //dd($pc->status);
                return $status->description;
            }
        })->last();
    } */

	public function status($pc)
	{
		if ($pc->status_id == 13) {
			return $pc->status_id;
		}

		if ($pc->voucher->name == 'COTIZACION') {
			return 2;
		}

		return $pc->status_id;
	}

	public function user_on_list_status($pc)
	{
		$user_id = $pc->statuses->map(function ($status) {
			return $status->user_id;
		})->filter()->values()->toArray();

		return collect($user_id)->map(function ($id) {

			$user = User::find($id);
			return $user->name();
		});
	}

	public function questions($item_id, $customer_id)
	{
		$questions = WebHookQuestion::where('item_id', $item_id)->where('from->id', $customer_id)->get();

		if ($questions->isNotEmpty()) {

			return $questions->toArray();
		}

		return false;
	}

	public function payment_data($pc)
	{
		$pc->load('payment_type_aditional');

		return [
			'id' => $pc->payment_type_aditional->id,
			'name' => ($pc->payment_type_aditional()->exists()) ? $pc->payment_type_aditional->type->name : 'www',
			'percentage' => $pc->payment_type_aditional['percentage'],
			'value' => $pc->payment_type_aditional['value'],
		];
	}

	public function price_list($product)
	{
		return $product->pricelists->map(function ($pr, $index) {

			if ($pr->pivot->enabledPriceListToProduct) {
				return [
					'pricelist_id' => $pr->id,
					'product_id' => $pr->pivot->product_id,
					'name' => $pr->name,
					'price' => '$ ' . number_format($pr->pivot->price, 2, ',', '.'),
					'price_raw' => $pr->pivot->price,
				];
			}
		})->filter()->values()->toArray();
	}

	public function gains($product_id, $price_list)
	{
		return PriceListProduct::where('pricelist_id', $price_list)
			->where('product_id', $product_id)
			->get()
			->first();
	}

	public function items($pc)
	{
		return collect($pc->items)->map(function ($i) {
			//dd($i);
			if ($i->product()->exists()) {

				$quantity = ($i->quantity == 0) ? 1 : $i->quantity;

				return [
					'product_id' => $i->product->id,
					'product_name' => $i->product->name,
					'product_attributes' => $i->product->search_by,
					'stock' => $i->product->stock,
					'critical_stock' => $i->product->critical_stock,
					'pricelist_id' => $i->pricelist_id,
					'iva' => [
						'id' => $i->iva->id,
						'percentage' =>  $i->iva->percentage
					],
					'iva_id' => $i->iva->id,
					'iva_afip_code' => $i->iva->code,
					'iva_name' => $i->iva->name,
					'quantity' => $quantity,
					'mts_to_invoiced' => $i->mts_to_invoiced,
					'mts_by_unity' => $i->mts,
					'isCHP' => $i->is_chp,
					'sheet_metal_cuttings' => $this->unionSheetMetalCuttingByMts($i->product),
					'real_mts' => $i->real_mts,
					'rounded_mts' => $i->rounded_mts,
					'unit_price' => $i->unit_price,
					'unit_price_invoiceA' => ($i->neto_import - $i->discount_import) / $quantity,
					'unit_price_invoiceB' => ($i->neto_import - $i->discount_import + $i->iva_import) / $quantity,
					'unit_price_Presupuesto' => ($i->neto_import + $i->discount_import) / $quantity,
					'total_invoiceA' => ($i->neto_import + $i->iva_import),
					'total_invoiceB' => ($i->neto_import + $i->iva_import),
					'total_Presupuesto' => ($i->neto_import - $i->discount_import),
					'discount_import' => $i->discount_import,
					'discount' => $i->discount_import,
					'iva_import' => $i->iva_import,
					'neto_import' => $i->neto_import,
					'total' => $this->DisplayToUserCurrency($i->neto_import - $i->discount_import),
					'total_raw' => $i->neto_import - $i->discount_import,
					'price_list' => $this->price_list($i->product),
					'gains' => $this->gains($i->product->id, $i->pricelist_id)
				];
			}

			return null;
		});
	}

	public function comments($pc)
	{
		Log::info('################################');
		Log::info('Pedido con comentarios -> ' . $pc);
		Log::info('');
		if ($pc->comments()->exists()) {

			if (!$pc->comments->isEmpty()) {
				return $pc->comments->map(function ($c) {
					return [
						'name' => $c->user_name,
						'description' => $c->description,
						'date' => $this->LongDateToArgentinaFormat($c->created_at)
					];
				});
			}
		}

		return false;
	}

	public function pedidoHasAddress($pc)
	{
		return $pc->addresses()->exists();
	}

	public function cotizacionHasAddress($pc)
	{
		if ($pc->cotizacion()->exists()) {
			return $pc->cotizacion->addresses()->exists();
		}
	}

	public function customerHasAddress($pc)
	{
		if ($pc) {

			if ($pc->customer()->exists()) {
				if ($pc->customer->addresses()->exists()) {
					return true;
				}

				return false;
			}

			return false;
		}

		return false;
	}

	public function fetch_address_from_customer($pc)
	{
		$address = $pc->customer->addresses->first();

		$add = [
			'id' => $address->id,
			'state_id' => $address->state->id,
			'state' => $address->state->name,
			'city' => $address->city,
			'cp' => $address->cp,
			'street' => $address->address,
			'between_streets' => $address->between_streets,
			'number' => $address->number,
			'obs' => $address->obs
		];

		return collect($add)->toArray();
	}

	public function fetch_address($pc)
	{
		return $pc->addresses->map(function ($address) {
			return [
				'id' => $address->id,
				'state_id' => $address->state->id,
				'state' => $address->state->name,
				'city' => $address->city,
				'cp' => $address->cp,
				'street' => $address->address,
				'between_streets' => $address->between_streets,
				'number' => $address->number,
				'obs' => $address->obs
			];
		})->toArray();
	}

	public function customer_address($pc)
	{
		if ($this->customerHasAddress($pc)) {
			return $this->fetch_address_from_customer($pc);
		}

		return false;
	}

	public function addresses($pc) //lugar de entrega
	{
		if (is_null($pc)) return null;

		if ($this->pedidoHasAddress($pc)) {
			return $this->fetch_address($pc);
		}

		if ($this->cotizacionHasAddress($pc)) {
			return $this->fetch_address($pc->cotizacion);
		}

		return null;
	}

	public function hasDeliveryDate($pc)
	{
		if (!is_null($pc->delivery_date)) {
			return true;
		}

		return false;
	}

	public function detail_of_totals($pc)
	{
		return $pc->items->groupBy('iva_id')->map(function ($iva) use ($pc) {

			$neto = collect($iva)->reduce(function ($acc, $pedido_cliente_item) {
				return $acc + $pedido_cliente_item['neto_import'];
			});

			$iva_import = collect($iva)->reduce(function ($acc, $pedido_cliente_item) {
				return $acc + $pedido_cliente_item['iva_import'];
			});
			$discount_import = collect($iva)->reduce(function ($acc, $pedido_cliente_item) {
				return $acc + $pedido_cliente_item['discount_import'];
			});

			$name = collect($iva)->reduce(function ($acc, $pedido_cliente_item) {
				return $pedido_cliente_item->iva->name;
			});

			$payment_type_aditional_name = '';
			$payment_type_aditional_percentage = '';
			$payment_type_aditional_value = 0;

			if ($pc->payment_type_aditional()->exists()) {
				$payment_type_aditional_name = $pc->payment_type_aditional->type->name;
				$payment_type_aditional_percentage = $pc->payment_type_aditional->percentage . ' %';
				$payment_type_aditional_value = $pc->payment_type_aditional->value;
			}

			return [
				'neto_name' => "Neto ${name}",
				'neto_import' => $neto,
				'iva_name' => "IVA ${name}",
				'iva_import' => $iva_import,
				'discount_name' => 'Des. por cant..',
				'discount_import' => $discount_import,
				'aditional_payment_name' => $payment_type_aditional_name . ' ' . $payment_type_aditional_percentage,
				'aditional_payment_value' => $payment_type_aditional_value
			];
		})->values()->all();
	}

	public function total($pc)
	{
		$statuses = [
			Status::PENDIENTE,
			Status::REMITIDO,
			Status::PRESUPUESTADO,
			Status::COTIZADO
		];

		if (collect($statuses)->contains($pc->status_id)) {
			return $this->DisplayToUserCurrency($pc->items->sum('neto_import') - $pc->items->sum('discount_import'));
		}

		$total = $pc->items->sum('neto_import') - $pc->items->sum('discount_import');

		return $this->DisplayToUserCurrency($total);
	}

	public function remito_code($pc)
	{
		/** TODO
		 * Un pedido puede tener varios remitos,
		 * hacer loop para listar los números de remmitos
		 */
		return ($pc->remitos()->exists())
			? $pc->remitos->first()->code
			: false;
	}

	public function invoices($pc)
	{
		$cotizaciones = $pc->pedido->map(function ($cotizacion) {

			return [
				'id' => $cotizacion->id,
				'date' => $this->LongDateToToArgentinaFormat($cotizacion->created_at),
				'voucher' => 'COTIZACION',
				'number' => $cotizacion->code,
				'import' => ''
			];
		});

		$remitos = $pc->remitos->map(function ($remito) {

			return [
				'id' => $remito->id,
				'date' => $this->LongDateToToArgentinaFormat($remito->created_at),
				'voucher' => 'REMITO',
				'number' => $remito->code,
				'import' => ''
			];
		});

		$aditional = 0;
		/* if ($pc->payment_type_aditional()->exists()) {
            if ($pc->payment_type_aditional->value < 0) {
                $aditional = $pc->payment_type_aditional->value *-1;
            }else{
                $aditional = $pc->payment_type_aditional->value;
            }
        } */
		$invoices = $pc->invoices->map(function ($invoice) use ($aditional) {

			return [
				'id' => $invoice->id,
				'date' => $invoice->cbte_fch,
				'voucher' => $invoice->voucher->name,
				'number' => "{$this->zeroLeft($invoice->pto_vta, 5)} - {$this->zeroLeft($invoice->cbte_desde, 8)}",
				'import' => ($invoice->voucher->name === 'PRESUPUESTO VENTA') ? $invoice->items->sum('neto_import') - $invoice->items->sum('discount_import') - $aditional : $invoice->items->sum('total')
			];
		});

		return $cotizaciones->concat($remitos)->concat($invoices)->toArray();
	}
}
