<?php

namespace App\Transformers\SaleInvoice;

use App\Src\Models\SaleInvoice;
use App\Src\Afip\WS\AfipWSPadron;
use App\Src\Traits\ZeroLeftTrait;
use App\Src\Traits\DateFormatTrait;
use League\Fractal\TransformerAbstract;
use App\Src\Traits\PedidoClientesTransformerTrait;

class PdfSaleInvoiceTransformer extends TransformerAbstract
{
	use ZeroLeftTrait, DateFormatTrait, PedidoClientesTransformerTrait;

	private function items($si)
	{

		return $si->items->map(function ($item) use ($si) {
			$quantity = (is_null($item->quantity)) ? 1 : $item->quantity;

			return [
				'product_id' => ($item->product()->exists()) ? $item->product->id : 'ver que pasa',
				'product_name' => ($item->product()->exists()) ? $item->product->name  : $item->obs,
				'rounded_mts' => (is_null($item->rounded_mts)) ? 0 : $item->rounded_mts,
				'isCHP' => $item->isCHP,
				'real_mts' => $item->real_mts,
				'mts_by_unity' => $item->mts_by_unity,
				'mts_to_invoiced' => $item->mts_to_invoiced,
				'neto_import' => $item->neto_import,
				'iva_import' => $item->iva_import,
				'quantity' => $quantity,
				'discount_import' => $item->discount_import,
				'unit_price_invoiceA' => ($item->neto_import + $item->discount) / $quantity,
				'unit_price_invoiceB' => ($item->neto_import + $item->discount + $item->iva_import) / $quantity,
				'unit_price_Presupuesto' => ($item->neto_import) / $quantity,
				'total_invoiceA' => ($item->neto_import + $item->iva_import),
				'total_invoiceB' => ($item->neto_import + $item->iva_import),
				'total_Presupuesto' => ($item->neto_import),
				'iva_percentage' => $item->iva['percentage'],
				'iva_name' => $item->iva['name'],
				'total' => $item->total,
				'about_voucher' => $item->sale_invoice->voucher->name . ' ' . str_pad($item->sale_invoice->pto_vta, 5, "0", STR_PAD_LEFT) . ' - ' . str_pad($item->sale_invoice->cbte_desde, 8, "0", STR_PAD_LEFT),
			];
		});
	}

	private function totals($invoice)
	{
		$totals = collect();

		if ((int)$invoice->voucher_id == 8) {

			$totals->push([
				[
					'name' => 'Subtotal',
					'value' => $invoice->items->sum('neto_import')
				]
			]);

			$totals->push([
				[
					'name' => 'Total',
					'value' => $invoice->items->sum('neto_import') + $invoice->items->sum('iva_import') + $invoice->percerp_iibb
				]
			]);
		}

		if ((int)$invoice->voucher_id == 6 || (int)$invoice->voucher_id == 11) {

			$totals->push([
				[
					'name' => 'Subtotal',
					'value' => $invoice->items->sum('neto_import') + $invoice->items->sum('iva_import')
				]
			]);

			$totals->push([
				[
					'name' => 'Total',
					'value' => $invoice->items->sum('neto_import') + $invoice->items->sum('iva_import') + $invoice->percerp_iibb
				]
			]);
		}

		if (
			(int)$invoice->voucher_id == 1 ||
			(int)$invoice->voucher_id == 2 ||
			(int)$invoice->voucher_id == 3 ||
			(int)$invoice->voucher_id == 92 ||
			(int)$invoice->voucher_id == 93 ||
			(int)$invoice->voucher_id == 94
		) {

			$totals->push([
				[
					'name' => 'Subtotal',
					'value' => $invoice->items->sum('neto_import')
				]
			]);

			$iva21 = $invoice->items->filter(function ($item) {
				if ($item->iva_id == 6) {
					return $item;
				}
			});

			if (!$iva21->isEmpty()) {
				$totals->push([
					[
						'name' => 'Iva 21 %',
						'value' => $iva21->sum('iva_import')
					]
				]);
			}

			$iva105 = $invoice->items->filter(function ($item) {
				if ($item->iva_id == 5) {
					return $item;
				}
			});

			if (!$iva105->isEmpty()) {
				$totals->push([
					[
						'name' => 'Iva 10,5 %',
						'value' => $iva105->sum('iva_import')
					]
				]);
			}

			$totals->push([
				[
					'name' => 'Percepción de IIBB ' . $invoice->iibb_percentage . ' %',
					'value' => $invoice->percerp_iibb
				]
			]);

			$totals->push([
				[
					'name' => 'Total',
					'value' => $invoice->items->sum('neto_import') + $invoice->items->sum('iva_import') + $invoice->percerp_iibb
				]
			]);
		}

		if ((int)$invoice->voucher_id == 88) {
			$totals->push([
				[
					'name' => 'Subtotal',
					'value' => $invoice->items->sum('neto_import')
				]
			]);
		}

		return $totals->toArray();
	}

	private function detail_of_totals($si)
	{
		$totals = $si->items->groupBy('iva_id')->map(function ($iva) use ($si) {

			$neto = collect($iva)->reduce(function ($acc, $item) {
				return $acc + $item['neto_import'];
			});

			$iva_import = collect($iva)->reduce(function ($acc, $item) {
				return $acc + $item['iva_import'];
			});

			$name = collect($iva)->reduce(function ($acc, $item) {
				return $item->iva->name;
			});

			$discount_import = collect($iva)->reduce(function ($acc, $pedido_cliente_item) {
				return $acc + $pedido_cliente_item['discount_import'];
			});

			if ($si->pedidos()->exists()) {
				$pedido = $si->pedidos->first();
			} else {
				$pedido = false;
			}

			return [
				'neto_name' => "Subtotal",
				'neto_import' => $neto,
				'iva_name' => "IVA ${name}",
				'iva_import' => $iva_import,
				'discount_name' => 'Des. por cant..',
				'discount_import' => $discount_import,
				'aditional_payment_name' => ($pedido && $pedido->payment_type_aditional()->exists()) ? $pedido->payment_type_aditional->type->name . ' ' . $pedido->payment_type_aditional->percentage . ' %' : '',
				'aditional_payment_value' => ($pedido && $pedido->payment_type_aditional()->exists()) ? $pedido->payment_type_aditional->value : '',
				'iibb_name' => ($si->iibb_percentage > 0 && $si->percerp_iibb > 0) ? 'Percep. IIBB ' . $si->iibb_percentage . ' %' : '',
				'iibb_import' => ($si->iibb_percentage > 0 && $si->percerp_iibb > 0) ? $si->percerp_iibb : ''
			];
		})->values()->all();

		$totals_collection = collect($totals);

		$total = 0;
		$payment_type_aditional = 0;

		if ($si->pedidos()->exists()) {
			$pedido = $si->pedidos->first();
			if ($pedido->payment_type_aditional()->exists()) {
				$payment_type_aditional = $pedido->payment_type_aditional->value;
			}
		}

		if ((int)$si->voucher_id == 88) {
			$total = $totals_collection->sum('neto_import') - $totals_collection->sum('discount_import') + $payment_type_aditional + $si->percerp_iibb;
		} else {
			$total = $totals_collection->sum('neto_import') - $totals_collection->sum('discount_import') + $totals_collection->sum('iva_import') + $payment_type_aditional + $si->percerp_iibb;
		}

		if ($si->iibb_percentage > 0 && $si->percerp_iibb > 0) {
			$totals['iibb_name'] = 'Percep. IIBB ' . $si->iibb_percentage . ' %';

			$totals['iibb_import'] = $si->percerp_iibb;
		}

		$totals['total_name'] = 'Total';

		$totals['total_import'] = $total;

		return $totals;
	}

	public function bill_type($si)
	{
		if ($si->voucher_id == 1) {
			return '003';
		}
		if ($si->voucher_id == 6) {
			return '008';
		}
		if ($si->voucher_id == 11) {
			return '013';
		}
		if ($si->voucher_id == 92) {
			return '203';
		}
	}

	public function AlicIva($si)
	{
		$Byiva =  $si->items->groupBy('iva_id');

		return collect($Byiva)->map(function ($i) {

			$BaseImp = 0;
			$Importe = 0;

			foreach ($i as $saleInvoiceItem) {
				$BaseImp += $saleInvoiceItem['neto_import'];
				$Importe += $saleInvoiceItem['iva_import'];
			}

			return [
				'Id' => ($saleInvoiceItem->iva()->exists()) ? $saleInvoiceItem->iva->code : '0',
				'BaseImp' => $BaseImp,
				'Importe' => $Importe,
			];
		});
	}

	public function getDataFromAfip($customer)
	{
		$environment = 'production';

		$wsPadronAfip = new AfipWSPadron($environment);

		if (!$wsPadronAfip->isCUITorCUIL($customer->number)) {

			$afip_data = $wsPadronAfip->getPersona($customer->number);

			$customer->number = $afip_data['idPersonaListReturn']['idPersona'];
			$customer->afip_data = $afip_data;
			$customer->save();
			$customer->fresh();

			return $customer->number;
		}

		return $customer->number;
	}

	public function voucherHasNotaCredito($si)
	{
		$nc = SaleInvoice::where('parent_id', $si->id)->get();

		if ($nc->isNotEmpty()) {
			return true;
		}

		return false;
	}

	public function payment_data($si)
	{
		//$pc->load('payment_type_aditional');
		if ($si->pedidos()->exists()) {

			if ($si->pedidos->first()->payment_type_aditional()->exists()) {
				return [
					'id' => $si->pedidos->first()->payment_type_aditional->id,
					'name' => ($si->pedidos->first()->payment_type_aditional()->exists()) ? $si->pedidos->first()->payment_type_aditional->type->name : 'www',
					'percentage' => $si->pedidos->first()->payment_type_aditional['percentage'],
					'value' => $si->pedidos->first()->payment_type_aditional['value'],
				];
			}

			return false;
		}

		/* return [
            'id' => '',
            'name' => '',
            'percentage' => '',
            'value' => '',
        ]; */

		return false;
	}

	public function transform(SaleInvoice $si): array
	{
		if ($si->pedidos()->exists()) {
			$pedido = $si->pedidos->first();
		} else {
			$pedido = false;
		}

		$totals = $this->detail_of_totals($si);
		$company_address = '';

		$total_raw = $si->items->sum('neto_import') + $si->items->sum('iva_import') + $si->percerp_iibb;

		if (($si->company->addresses()->exists())) {
			$first = $si->company->addresses->first();
			$company_address = "{$first->address} - {$first->city}";
		}
		/* $aditional = '';
        $percentage = '';
        if ($pedido->payment_type_aditional()->exists()) {

        } */

		/* if ($si->id === 8695) {
			dd($si->comments->first()->description);
		} */
		return [
			'environment' => $si->company->settings['afip_environment'],
			'invoice_comments' => ($si->comments()->exists()) ? strtoupper($si->comments->first()->description) : '',

			'company_id' => $si->company->id,
			'company_name' => $si->company->name,
			'company_cuit' => $si->company->number,
			'company_address' => $company_address,
			'company_inscription_id' => $si->company->inscription_id,
			'company_inscription_name' => $si->company->inscription->name,
			'company_iibb' => $si->company->iibb_conv,
			'company_activity_init' => $this->ShortDateToArgentinaFormat($si->company->activity_init),
			'company_purchaserDocument' => $si->company->purchaserDocument->name,

			'pedido_cliente_delivery_date' => ($pedido) ? $pedido->delivery_date : '',

			'customer_name' => $si->customer->name,
			'customer_cuit' => $si->customer->number,
			'customer_address' => $this->customer_address($si->pedidos->first()),
			'customer_purchaser_document' => ($si->customer->purchaserDocument()->exists()) ? $si->customer->purchaserDocument->name : '',
			'customer_inscription' => ($si->customer->inscription()->exists()) ? $si->customer->inscription->name : '',

			'voucher_percep_iibb' => $si->percerp_iibb,
			'voucher_percentage_percep_iibb' => $si->iibb_percentage,
			//'voucher_modo_pago' => ($pedido)?$pedido->payment_type->name : '',
			'voucher_vto_pago' => (is_null($si->vto_payment)) ? '' : $si->vto_payment,
			'voucher_id' => $si->voucher_id,
			'voucher_name' => $si->voucher->name,
			'voucher_number' => "{$this->zeroLeft($si->pto_vta, 5)} - {$this->zeroLeft($si->cbte_desde, 8)}",
			'voucher_date' => $si->cbte_fch,
			'voucher_cae' => $si->cae,
			'voucher_vto_cae' => $si->cae_fch_vto,
			'voucher_items' => $this->items($si),
			'voucher_total_import' => '$ ' . number_format($totals['total_import'], 2, ',', '.'),
			'voucher_afip_code' => $si->voucher->afip_code,
			'voucher_has_nota_credito' => $this->voucherHasNotaCredito($si),

			'payment_data' => $this->payment_data($si),

			'totals' => $totals,

			'qr_afip' => [
				'ver' => 1,
				'date' => $si->cbte_fch,
				'cuit' => $si->company->number,
				'ptoVta' => $si->pto_vta,
				'CbteTipo' => $si->voucher_id,
				'nroCbte' => $si->cbte_desde,
				'importe' => $si->items->sum('neto_import') + $si->items->sum('iva_import') + $si->percerp_iibb,
				'money' => 'PES',
				'ctz' => 1,
				'tipoDocRec' => $si->customer->purchaserDocument->name,
				'nroDocRec' => $si->customer->number,
				'tipoCodAut' => 'E',
				'codAut' => $si->cae
			],

			'nota_credito' => [
				'pedido_cliente_id' => ($pedido) ? $pedido->id : '',
				'customer' => $si->customer,
				'text' => [
					'quantity' => 1,
					'sale_invoice_id' => $si->id,
					'detail' => 'Sobre: ' . $si->voucher->name . ' ' . str_pad($si->pto_vta, 5, "0", STR_PAD_LEFT) . ' - ' . str_pad($si->cbte_desde, 8, "0", STR_PAD_LEFT),
				],
				'BillSale' => [
					'bill_date' => null,
					'bill_date_vto' => null,
					'bill_number' => null,
					'bill_type' => $this->bill_type($si),
					'customer' => [
						'id' => $si->customer->id,
						'address' => ($si->customer->addresses()->exists()) ? $si->customer->addresses->first()->address : '',
						'id_number' => $si->customer->number,
						'key_type' => ($si->customer->purchaserDocument()->exists()) ? $si->customer->purchaserDocument->name : 'Sin definir',
						'name' => $si->customer->name,
					],
					'percep_iibb' => $si->percerp_iibb,
					'iibb_percentage' => $si->iibb_percentage,
				],
				'ImpTotal' => $total_raw,
				'Neto' =>  $si->items->sum('neto_import'),
				'IvaImport' =>  $si->items->sum('iva_import'),
				'AlicIva' => $this->AlicIva($si),
				'iva_id' => $si->items->first(), // lo pongo así por que no funciona
				'CbtesAsoc' =>
				[
					[
						'Tipo' => (string)$si->voucher->afip_code,
						'PtoVta' => $si->pto_vta,
						'Nro' => $si->cbte_desde,
						'Cuit' => (int) $si->customer->number + 0,
						'CbteFch' => $si->cbte_fch
					]
				],
				'impoTotConc' => 0, //importe neto no gravado
				'ImpTrib' => ($si->percerp_iibb > 0) ? $si->percerp_iibb : '',
				'Tributos' => ($si->percerp_iibb > 0) ? [
					'Tributo' => [
						'Id' => '7',
						'Desc' => 'Percepción de IIBB',
						'BaseImp' => $si->items->sum('neto_import'),
						'Alic' => floatval($si->percentage_iibb),
						'Importe' => $si->percerp_iibb
					]
				] : '',
			],

			'comments' => ($pedido) ? $this->comments($pedido) : []

		];
	}
}
