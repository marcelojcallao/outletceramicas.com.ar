<?php

namespace App\Http\Controllers\Api;

use App\Events\PedidoClienteBlockedWhenIsOpen;
use App\Events\PedidoClienteCreated;
use App\Events\PedidoClienteUnLockWhenIsClosed;
use Exception;

use Jenssegers\Date\Date;
use App\Src\Models\Status;

use App\Http\Requests\DeliveryAddressRequest;
use App\Src\Models\Address;
use App\Src\Models\SaleInvoice;
use App\Src\Models\PedidoCliente;
use Illuminate\Support\Facades\DB;
use App\Src\Traits\DateFormatTrait;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Src\Models\PedidoClienteItem;
use App\Http\Requests\Order\OrderFormRequest;
use App\Src\Models\Product;
use App\Src\Models\Remito;
use App\Src\Traits\PedidoCliente\CheckStockTrait;
use App\Transformers\SaleInvoice\PdfSaleInvoiceTransformer;
use App\Transformers\PedidoCliente\CotizacionPdfTransformer;
use App\Transformers\PedidoCliente\PedidoClienteListTransformer;
use App\Transformers\PedidoCliente\PedidoClienteDetailShowTransformer;
use phpDocumentor\Reflection\Types\This;

class PedidoClienteController extends Controller
{
	use DateFormatTrait, CheckStockTrait;

	const PENDIENTE = 2;
	const ELIMINADO = 10;
	const CUMPLIDO = 13;
	const PEDIDO_CLIENTE = 101;

	private $error_stock;

	public function __construct()
	{
		$this->error_stock = false;
	}

	public function index()
	{
		$pcs = PedidoCliente::query();

		if (request()->has('code')) {

			$code = request()->code;

			$isRemito = substr($code, 0, 2);

			if ($isRemito === 'RT') {
				$remito = Remito::where('code', $code)->get()->first();

				$pcs = $pcs->where('id', $remito->pedido_cliente_id);
			} else {
				$pcs = $pcs->where('code', $code);
			}

			$pcs = $pcs->select('id', 'code', 'customer_id', 'created_at', 'delivery_date', 'total', 'status_id', 'meli_data', 'voucher_id', 'date', 'is_editing', 'is_editing_by_user')
				->orderBy('created_at', 'desc')
				->paginate(20);

			$pedidos = fractal($pcs, new PedidoClienteListTransformer())->toArray()['data'];

			$response = [
				'pagination' => [
					'total' => $pcs->total(),
					'per_page' => $pcs->perPage(),
					'current_page' => $pcs->currentPage(),
					'last_page' => $pcs->lastPage(),
					'from' => $pcs->firstItem(),
					'to' => $pcs->lastItem()
				],
				'data' => $pedidos,
			];

			return response()->json($response, 200);
		}

		if (request()->date_from && request()->date_to) {
			$pcs = $pcs->whereBetween('created_at', [request()->date_from, request()->date_to]);
		}
		if (request()->customer) {
			$pcs = $pcs->where('customer_id', request()->customer);
		}

		if (request()->has('status')) {
			if (request()->status == 'null') {
				$pcs = $pcs->whereBetween('status_id', [Status::PENDIENTE, Status::COTIZADO]);
			} else {
				$pcs = $pcs->whereBetween('status_id', [request()->status, request()->status]);
			}
		} else {
			$pcs = $pcs->whereBetween('status_id', [Status::PENDIENTE, Status::COTIZADO]);
		}

		if (request()->has('product')) {

			$pcs = $pcs->whereHas('items', function ($item) {
				$item->where('product_id', request()->product);
			});
		}

		$pcs = $pcs
			->select('id', 'code', 'customer_id', 'created_at', 'delivery_date', 'total', 'status_id', 'meli_data', 'voucher_id', 'date', 'is_editing', 'is_editing_by_user')
			->orderBy('created_at', 'desc')
			->paginate(20);

		$pedidos = fractal($pcs, new PedidoClienteListTransformer())->toArray()['data'];

		$response = [
			'pagination' => [
				'total' => $pcs->total(),
				'per_page' => $pcs->perPage(),
				'current_page' => $pcs->currentPage(),
				'last_page' => $pcs->lastPage(),
				'from' => $pcs->firstItem(),
				'to' => $pcs->lastItem()
			],
			'data' => $pedidos,
		];

		return response()->json($response, 200);
	}

	public function delete()
	{
		$pd = PedidoCliente::find(request()->pedido_cliente_id);

		if ($pd->status_id > 4) {
			throw new \Exception('Éste pedido tiene asignado un comprobantes de ventas, no se eliminará', 400);
		}

		$pd->items()->delete();
		$pd->statuses()->delete();
		$pd->comments()->delete();
		$pd->payment_type_aditional()->delete();
		$pd->shipping()->delete();
		$pd->delete();

		pedido_cliente_history($pd, 'eliminado', auth()->user()->id);

		return response()->json($pd, 204);
	}

	public function save_order(PedidoCliente $pc, $pedido)
	{

		DB::beginTransaction();

		try {

			$pc->customer_id = $pedido['customer']['id'];
			$pc->delivery_date = substr($pedido['delivery_date'], 0, 10);
			$pc->date = substr($pedido['date'], 0, 10);
			$pc->total = $pedido['total'];
			$pc->status_id = ($pedido['type']['code'] == "CZ-") ? Status::COTIZADO : Status::PENDIENTE;
			$pc->voucher_id = $pedido['type']['id'];
			$pc->user_id = auth()->user()->id;
			$pc->save();

			$pc->code = $pedido['type']['code'] . $this->code() . '-' . $pc->customer_id . '-' . $pc->id;
			$pc->number = $pc->id;
			$pc->save();

			$pc->refresh();

			collect($pedido['products'])->each(function ($p) use ($pc) {

				if (!is_null($p['product']['id'])) {

					$pc->items()->create(
						[
							'pedido_cliente_id' => $pc->id,
							'product_id' => $p['product']['id'],
							'pricelist_id' => $p['price_list']['pricelist_id'],
							'unit_price' => $p['unit_price'],
							'quantity' => $p['quantity'],
							'is_chp' => $p['isCHP'],
							'rounded_mts' => $p['rounded_mts'],
							'real_mts' => $p['real_mts'],
							'mts_to_invoiced' => $p['mts_to_invoiced'],
							'mts' => $p['mts_by_unity'], //metros por cada chapa
							'discount_import' => $p['descuento'],
							'iva_id' => $p['iva']['id'],
							'iva_percentage' => $p['iva']['percentage'],
							'iva_import' => $p['iva_import'],
							'neto_import' => $p['neto'],
							'total' => $p['total'],
						]
					);
				}
			});

			if ($pedido['required_shipping'] || !is_null($pedido['address']['city']['cp']) && !is_null($pedido['address']['street'])) {

				$address = new Address();
				$address->country_id = 1;
				$address->province_id = $pedido['address']['state']['id'];
				$address->city = strtoupper($pedido['address']['city']['name']);
				$address->address = strtoupper($pedido['address']['street']);
				$address->number = $pedido['address']['number'];
				$address->cp = strtoupper($pedido['address']['city']['cp']);
				$address->obs = strtoupper($pedido['address']['obs']);
				$address->between_streets = strtoupper($pedido['address']['between']);
				$address->addressable_id = $pc->id;
				$address->addressable_type = 'App\Src\Models\PedidoCliente';
				$address->save();
			}

			$pc->payment_type_aditional()->create([
				'pedido_cliente_id'  => $pc->id,
				'payment_type_id'  => $pedido['payment']['id'],
				'percentage'  => $pedido['payment']['percentage'],
				'value'  => $pedido['payment']['value'],
			]);

			if ((int) $pedido['shipping']['percentage'] > 0) {
				$pc->shipping()->create([
					'pedido_cliente_id'  => $pc->id,
					'shipping' => '',
					'geocoder' => '',
					'percentage' => $pedido['shipping']['percentage'],
					'value' => $pedido['shipping']['value'],
				]);
			}

			$pc->fresh();

			$pedidoCliente = fractal($pc, new PedidoClienteDetailShowTransformer())->toArray()['data'];

			pedido_cliente_history($pc, 'creación', auth()->user()->id, $pedidoCliente);

			DB::commit();

			return $pedidoCliente;
		} catch (\Exception  $e) {
			DB::rollback();
			Log::info("Error al generar pedido");
			Log::info($e);
			Log::info("Error al generar pedido");
			Log::info("");
			throw $e;
		}
	}

	public function store(OrderFormRequest $request)
	{
		$pedido = $request->order;

		$pc = new PedidoCliente;

		$pc = $this->save_order($pc, $pedido);

		broadcast(new PedidoClienteCreated($pc));

		return response()->json($pc, 201);
	}

	public function status_update()
	{
		$pc = request()->pedido;

		$pedido = PedidoCliente::find($pc['id']);
		$pedido->status_id = (int) request()->new_status;
		$pedido->save();
		$pedido->refresh();

		$pedidoCliente = fractal($pedido, new PedidoClienteDetailShowTransformer())->toArray()['data'];

		pedido_cliente_history($pedido, 'cambio de estado', auth()->user()->id, $pedidoCliente);

		return response()->json($pedidoCliente, 200);
	}

	public function show()
	{
		$pedido = PedidoCliente::where('code', request()->code)->get()->first();

		$pedidoCliente = fractal($pedido, new PedidoClienteDetailShowTransformer())->toArray()['data'];

		//pedido_cliente_history($pedido, 'ver detalle', auth()->user()->id, $pedidoCliente);



		return response()->json($pedidoCliente, 200);
	}

	public function save_comment()
	{
		$pedido_data = request()->all();

		$pedido = PedidoCliente::find($pedido_data['pedido_id']);

		$pedido->comments()->create([
			'description' => $pedido_data['comment'],
			'user_id' => auth()->user()->id,
			'user_name' => auth()->user()->name . ' ' . auth()->user()->last_name,
			'pedido_status_id' => $pedido->status_id,
		]);

		$pedido->refresh();

		$comments = null;

		if ($pedido->comments()->exists()) {
			$comments = $pedido->comments->map(function ($c) {
				return [
					'name' => $c->user_name,
					'description' => $c->description,
					'date' => $this->LongDateToArgentinaFormat($c->created_at)
				];
			});
		}

		pedido_cliente_history($pedido, 'se agregan comentarios', auth()->user()->id, $pedido->comments->toArray());

		return response()->json($comments->toArray(), 200);
	}

	public function update_delivery_date()
	{
		$pedido = PedidoCliente::find(request()->pedido_id);

		$pedido->delivery_date = $this->ShortDateTo_yyyy_mm_dd(request()->date);

		$pedido->save();

		$pedido->refresh();

		pedido_cliente_history($pedido, 'se actualiza fecha de entrega', auth()->user()->id, ['FECHA' => $pedido->delivery_date]);

		return response()->json($pedido, 201);
	}

	public function who_prepared_update()
	{
		$order = PedidoCliente::find(request()->order_id);

		$pc = $order->update(['who_prepared' => strtoupper(request()->name)]);

		return response()->json($pc, 201);
	}

	public function who_dispatch_update()
	{
		$order = PedidoCliente::find(request()->order_id);

		$res = $order->update(['who_delivered' => strtoupper(request()->name)]);

		return response()->json($res, 201);
	}

	public function restore_pedido()
	{
		$order = PedidoCliente::find(request()->order_id);

		$pedido = $order->update(['status_id' => self::PENDIENTE]);

		$pedidoCliente = fractal($order, new PedidoClienteDetailShowTransformer())->toArray()['data'];

		pedido_cliente_history($pedido, 'se vuelve a pendiente', auth()->user()->id, $pedidoCliente);

		return response()->json($pedidoCliente, 201);
	}

	public function print_invoice()
	{
		$si = SaleInvoice::find(request()->sale_invoice_id);

		$si =  fractal($si, new PdfSaleInvoiceTransformer())->toArray()['data'];

		return response()->json($si, 200);
	}

	public function changeToPedido()
	{
		$cotizacion = PedidoCliente::find(request()->pedido);

		if ($this->check_available_stock($cotizacion)) {

			$d = new Date();
			$pc = $cotizacion->replicate();
			$pc->save();
			$pc->refresh();

			$pc->voucher_id = self::PEDIDO_CLIENTE;
			$pc->date = $this->now();
			$pc->code = 'PD-' .  $this->code() . '-' . $pc->customer_id . '-' . $pc->id;
			$pc->status_id = Status::PENDIENTE;
			$pc->parent_id = $cotizacion->id;
			$pc->save();

			$cotizacion->items->map(function ($item) use ($pc) {
				$pc_item = $item->replicate();
				$pc_item->save();
				$pc_item->refresh();
				$pc_item->pedido_cliente_id = $pc->id;
				$pc_item->save();
			});

			if ($cotizacion->payment_type_aditional()->exists()) {

				$payment_type_aditional = $cotizacion->payment_type_aditional->replicate();
				$payment_type_aditional->save();
				$payment_type_aditional->refresh();
				$payment_type_aditional->pedido_cliente_id = $pc->id;
				$payment_type_aditional->save();
			}

			if ($cotizacion->shipping()->exists()) {

				$shipping = $cotizacion->shipping->replicate();
				$shipping->save();
				$shipping->refresh();
				$shipping->pedido_cliente_id = $pc->id;
				$shipping->save();
			}

			$cotizacion->status_id = self::CUMPLIDO;
			$cotizacion->parent_id = $pc->id;
			$cotizacion->save();

			$pc->refresh();

			$pedido = fractal($pc, new PedidoClienteListTransformer())->toArray()['data'];

			return response()->json($pedido, 201);
		}
	}

	public function cotizacion_print()
	{
		$ctz = PedidoCliente::find(request()->cotizacion);

		$cotizacion = fractal($ctz, new CotizacionPdfTransformer())->toArray()['data'];

		return response()->json($cotizacion, 200);
	}

	public function add_delivery_address_to_pedido(DeliveryAddressRequest $request)
	{
		$add = request()->address;
		$address = '';
		$exists_address = Address::where('addressable_id', $add['pedido_id'])->get();

		if ($exists_address->isNotEmpty()) {
			$address = $exists_address->first();
		} else {
			$address = new Address;
		}

		$address->country_id = 1;
		$address->province_id = $add['address']['state']['id'];
		$address->city = strtoupper($add['address']['city']);
		$address->address = strtoupper($add['address']['address']);
		$address->cp = strtoupper($add['address']['cp']);
		$address->obs = strtoupper($add['address']['obs']);
		$address->addressable_id = $add['pedido_id'];
		$address->addressable_type = 'App\Src\Models\PedidoCliente';
		$address->between_streets = strtoupper($add['address']['between']);
		$address->save();

		$addrs = [
			'state_id' => $address->state->id,
			'state' => $address->state->name,
			'city' => $address->city,
			'cp' => $address->cp,
			'street' => $address->address,
			'between_streets' => $address->between_streets,
			'number' => $address->number,
			'obs' => $address->obs
		];

		$pedido = PedidoCliente::find($add['pedido_id']);

		pedido_cliente_history($pedido, 'se agrega domicilio de entrega', auth()->user()->id, $address->toArray());

		return response()->json($addrs, 201);
	}

	public function delete_product()
	{
		$pedido_cliente_id = request()->pedido_id;

		$product_id = request()->product_id;

		$item = PedidoClienteItem::where('pedido_cliente_id', $pedido_cliente_id)
			->where('product_id', $product_id)->get();

		$item->first()->delete();

		$pedido = PedidoCliente::find($pedido_cliente_id);

		pedido_cliente_history($pedido, 'se elimina un producto', auth()->user()->id, $item->toArray());

		return response()->json(['pedido_id' => $pedido_cliente_id, 'product_id' => $product_id], 201);
	}

	public function update_items_of_pedido()
	{
		try {
			$pedido = request()->pedido;

			$pedido_cliente_id = $pedido['pedido_id']; //es la cotización

			$products = json_decode($pedido['products']); //son los productos que cambian

			$pedidoOCotiz = PedidoCliente::find($pedido_cliente_id);

			collect($products)->map(function ($product) use ($pedido_cliente_id, $pedidoOCotiz) {

				$p = (array) $product;

				$item = PedidoClienteItem::where('pedido_cliente_id', $pedido_cliente_id)
					->where('product_id', $p['produc_id'])
					->get()
					->first();

				if ($p['stock'] == 0) {
					$item->delete();
				} else {

					$neto = 0;
					$item->rounded_mts = 0;
					$item->real_mts = $p['stock'] * $item->mts;
					$item->mts_to_invoiced = $p['stock'] * $item->mts;
					$item->quantity = $p['stock'];
					//dd($item->unit_price, $p['stock'], $item->mts, $item->unit_price * $p['stock'] * $item->mts)
					if ($item->is_chp) {

						$neto = $item->unit_price * $p['stock'] * $item->mts;
					} else {
						$neto = $item->unit_price * $p['stock'];
					}
					$item->neto_import = $neto;
					$item->iva_import = $neto * $item->iva_percentage / 100;
					$item->total = $neto + $neto * $item->iva_percentage / 100;

					$item->save();
				}

				pedido_cliente_history($pedidoOCotiz, 'se actualiza produto', auth()->user()->id, $item->toArray());
			});

			$cotizacion = fractal($pedidoOCotiz, new PedidoClienteDetailShowTransformer())->toArray()['data'];

			return response()->json($cotizacion, 201);
		} catch (\Exception $e) {
			Log::error(' ------------------------------- ');
			Log::error(" Mensaje: {$e->getMessage()} - Code: {$e->getCode()}");
			Log::error(' ------------------------------- ');
			throw new \Exception('Ha ocurrido un error al actualizar el pedido', 500);
		}
	}

	public function add_item_to_pedido()
	{
		$item = request()->item;

		$newItem = new PedidoClienteItem;
		$newItem->pedido_cliente_id = $item['pedido_id'];
		$newItem->product_id = $item['produc_id'];
		$newItem->pricelist_id = $item['price_list']['id'];
		$newItem->unit_price = $item['unit_price'];
		$newItem->quantity = $item['quantity'];
		$newItem->discount_import = $item['discount'];
		$newItem->iva_id = $item['iva']['id'];
		$newItem->iva_percentage = $item['iva']['percentage'];
		$newItem->iva_import = $item['iva_import'];
		$newItem->neto_import = $item['neto'];
		$newItem->total = $item['total'];
		$newItem->is_chp = false;
		/* $newItem->mts = $item['mts'];
        $newItem->rounded_mts = $item['rounded_mts'];
        $newItem->real_mts = $item['real_mts'];
        $newItem->mts_to_invoice = $item['mts_to_invoice']; */

		$newItem->save();

		$pedido = PedidoCliente::find($newItem->pedido_cliente_id);

		$pedido = fractal($pedido, new PedidoClienteDetailShowTransformer())->toArray()['data'];

		pedido_cliente_history($pedido, 'se agrega un producto', auth()->user()->id, $newItem->toArray());

		return response()->json($pedido, 201);
	}

	public function row_product_update_quantity()
	{
		$data = request()->payload;

		$item = PedidoClienteItem::where('pedido_cliente_id', $data['pedido_id'])
			->where('product_id', $data['product_id'])->get()->first();

		$neto = ((float) $item->unit_price * (float) $data['quantity']) - (float) $item->discount_import;

		$iva_import = $neto * (float) $item->iva_percentage / 100;

		$item->quantity = (float) $data['quantity'];
		$item->neto_import = $neto;
		$item->iva_import = $iva_import;
		$item->total = $neto + $iva_import;
		$item->save();

		return response()->json('ok', 201);
	}

	public function findCode()
	{
		$code = request()->code;

		$pedido_codes = PedidoCliente::where('code', 'like', '%' . $code . '%')
			->select('code')
			->orderBy('code', 'desc')->get();

		$remito_codes = Remito::where('code', 'like', '%' . $code . '%')
			->select('code')
			->orderBy('code', 'desc')->get();

		return response()->json($pedido_codes->concat($remito_codes)->toArray(), 200);
	}

	public function order_update_save_current_order()
	{

		$pedido = request()->order;

		$pedido['products'] = $pedido['items'];

		$pc = PedidoCliente::find($pedido['id']);

		DB::beginTransaction();

		try {

			$pc->delivery_date = $this->ShortDateTo_yyyy_mm_dd($pedido['delivery_date']);
			$pc->total = $pedido['total_neto'];
			$pc->user_id = auth()->user()->id;
			$pc->save();
			$pc->save();

			$pc->items()->delete();

			$pc->refresh();

			collect($pedido['products'])->each(function ($p) use ($pc) {

				$pc->items()->create(
					[
						'pedido_cliente_id' => $pc->id,
						'product_id' => array_key_exists('produc_id', $p) ? $p['produc_id'] : $p['product_id'],
						'pricelist_id' => $p['pricelist_id'],
						'unit_price' => $p['unit_price'],
						'quantity' => $p['quantity'],
						'is_chp' => $p['isCHP'],
						'rounded_mts' => $p['rounded_mts'],
						'real_mts' => $p['real_mts'],
						'mts_to_invoiced' => $p['mts_to_invoiced'],
						'mts' => $p['mts_by_unity'], //metros de cada chapa
						'discount_import' => $p['discount'],
						'iva_id' => $p['iva']['id'],
						'iva_percentage' => $p['iva']['percentage'],
						'iva_import' => $p['iva_import'],
						'neto_import' => array_key_exists('neto', $p) ? $p['neto'] : $p['neto_import'],
						'total' => (float) $p['total_raw'],
					]
				);
			});

			$pc->fresh();

			$pc->load('items');

			$pedidoCliente = fractal($pc, new PedidoClienteDetailShowTransformer())->toArray()['data'];

			pedido_cliente_history($pc, 'se actualiza', auth()->user()->id, $pedidoCliente);

			DB::commit();
		} catch (\Exception  $e) {
			DB::rollback();
			Log::info("Error al actualizar pedido");
			Log::info($e);
			Log::info("Error al actualizar pedido");
			Log::info("");
			throw $e;
		}
		return response()->json($pedidoCliente, 201);
	}

	public function isEditing()
	{
		try {


			$pedido = PedidoCliente::find(request()->pedido_id);

			$pedido->is_editing = request()->isEditing;
			$pedido->is_editing_by_user = (request()->isEditing) ? request()->user_id :  null;
			$pedido->save();

			$pedidoCliente = [
				'id' => $pedido->id,
				'code' => $pedido->code,
				'user' => request()->user_name,
			];

			(request()->isEditing)
				? broadcast(new PedidoClienteBlockedWhenIsOpen($pedidoCliente))->toOthers()
				: broadcast(new PedidoClienteUnLockWhenIsClosed($pedidoCliente))->toOthers();
		} catch (\Exception $th) {
			throw new Exception('error al bloquear el pedido');
		}
	}

	public function restoreIsEditing()
	{
		try {
			$pedido = PedidoCliente::find(request()->pedido_id);

			$pedido->is_editing = false;
			$pedido->is_editing_by_user =  null;
			$pedido->save();

			return response()->json($pedido, 200);
		} catch (\Exception $th) {
			throw new Exception('error al restaurar isEditing');
		}
	}
}
