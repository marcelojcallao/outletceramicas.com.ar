<?php

namespace App\Http\Controllers\Api;

use Jenssegers\Date\Date;
use App\Src\Models\Status;
use App\Src\Models\Product;
use App\Src\Models\Receipt;
use App\Src\Models\SaleInvoice;
use App\Src\Models\PedidoCliente;
use App\Src\Traits\DateFormatTrait;
use App\Http\Controllers\Controller;
use App\Src\Repositories\App\SaleInvoiceRepository;
use App\Transformers\SaleInvoiceByCustomerTransformer;
use App\Transformers\Afip\GetCaeOnAFipToSaveTransformer;
use App\Transformers\Receipt\ReceiptWithDebtTransformer;
use App\Transformers\SaleInvoice\SaleInvoiceTransformer;
use App\Transformers\SaleInvoice\PdfSaleInvoiceTransformer;
use App\Transformers\PresupuestoVenta\PresupuestoVentaTransformer;
use Illuminate\Http\Request;

class SaleInvoiceController extends Controller
{
	use DateFormatTrait;

	protected $sirepo;

	public function __construct()
	{
		$this->sirepo = new SaleInvoiceRepository();
	}

	public function converterFecha($fecha)
	{
		$anio = substr($fecha, 0, 4);
		$mes = substr($fecha, 5, 2);
		$dia = substr($fecha, 8, 2);

		return $anio . $mes . $dia;
	}

	public function search($request)
	{
		$invoices = SaleInvoice::query();

		if ($request->has('customer')) {

			$invoices = $invoices->where('customer_id', $request->customer);
		}

		if ($request->has('voucher')) {
			$invoices = $invoices->where('voucher_id', $request->voucher);
		}

		if ($request->date_from && $request->date_to) {
			$invoices = $invoices->whereBetween('cbte_fch', [$request->date_from, $request->date_to]);
		}

		if (request()->has('product')) {
			$invoices = $invoices->whereHas('items', function ($item) {
				$item->where('product_id', request()->product);
			});
		}

		return $invoices;
	}

	public function index()
	{

		$invoices = $this->search(request());

		$invoices = $invoices->orderBy('created_at', 'desc')->paginate(50);

		$invoices_list = fractal($invoices, new PdfSaleInvoiceTransformer())->toArray()['data'];

		$response = [
			'pagination' => [
				'total' => $invoices->total(),
				'per_page' => $invoices->perPage(),
				'current_page' => $invoices->currentPage(),
				'last_page' => $invoices->lastPage(),
				'from' => $invoices->firstItem(),
				'to' => $invoices->lastItem()
			],
			'data' => $invoices_list
		];
		return response()->json($response, 200);
	}

	public function excel()
	{
		$invoices = SaleInvoice::query();

		if (request()->has('customer')) {

			$invoices = $invoices->where('customer_id', request()->customer);
		}

		if (request()->has('voucher')) {
			$invoices = $invoices->where('voucher_id', request()->voucher);
		} else {
			$invoices = $invoices->where('voucher_id', 88);
		}

		//$invoices = $invoices->where('voucher_id', 101);

		//$invoices = $invoices->where('status_id', '>', 2);
		/* $from = Date::createFromFormat( 'Y-m-d', request()->from )->hour(0)->minute(0)->second(0)->format('Y-m-d H:i:s');
        $to = Date::createFromFormat( 'Y-m-d', request()->to )->hour(23)->minute(59)->second(59)->format('Y-m-d H:i:s'); */
		$from = Date::createFromFormat('Y-m-d H:i:s', request()->from)->format('Y-m-d');

		$to = Date::createFromFormat('Y-m-d H:i:s', request()->to)->format('Y-m-d');

		$invoices = $invoices->whereDate('cbte_fch', '>=', $from)->whereDate('cbte_fch', '<=', $to);

		$invoices = $invoices->orderBy('created_at')->get();

		$invoices = fractal($invoices, new SaleInvoiceTransformer())->toArray()['data'];

		return response()->json($invoices, 200);
	}

	public function store()
	{
		//dd(request()->all());
		activity()
			->withProperties('SaleInvoice')
			->log(collect(request()->invoice)->toJson());
		//dd(request()->all());
		$bill_data = fractal(request()->invoice, new GetCaeOnAFipToSaveTransformer())->toArray()['data'][0];
		//dd( request()->invoices);
		$si = $this->sirepo->create($bill_data, request()->invoice, request()->invoice['products'], request()->invoices, request()->invoice['percep_iibb']);

		return response()->json($si, 201);
	}

	public function byCustomer()
	{
		$customer = request()->customer['id'];

		$r = request()->all();

		$invoices = SaleInvoice::where('customer_id', $customer)
			->where('searching', false)->whereHas('voucher', function ($query) use ($r) {
				//$query->where('name', 'LIKE', '%'. 'FACTURA'.'%');
				$query->where('name', 'LIKE', '%' . $r['query'] . '%');
			})->get();

		$invoices =  fractal($invoices, new SaleInvoiceByCustomerTransformer())->toArray()['data'];

		return response()->json($invoices, 200);
	}

	public function byCustomerWithDebt()
	{
		$customer = request()->customer['id'];

		$r = request()->all();

		$invoices = SaleInvoice::where('customer_id', $customer)
			->where('status_id', Status::PENDIENTE)
			->whereHas('voucher', function ($query) use ($r) {
				//$query->where('name', 'LIKE', '%'. 'FACTURA'.'%');
				$query->where('name', 'LIKE', '%' . $r['query'] . '%');
			})->get();

		$rs = Receipt::where('customer_id', $customer)
			->where('status_id', Status::PENDIENTE)->get();

		$receipt =  fractal($rs, new ReceiptWithDebtTransformer())->toArray()['data'];

		$invoices =  fractal($invoices, new SaleInvoiceByCustomerTransformer())->toArray()['data'];

		$invoices = collect($invoices);

		$invoices = $invoices->merge($receipt);

		return response()->json($invoices->toArray(), 200);
	}

	public function isSearching()
	{
		$invoice = SaleInvoice::find(request()->invoice);
		$invoice->searching = !$invoice->searching;
		$invoice->save();
	}

	public function save_presupuesto()
	{
		$presupuesto = request()->presupuesto;

		$si = new SaleInvoice;
		$si->company_id = $presupuesto['company_id'];
		$si->customer_id = $presupuesto['customer_id'];
		$si->doc_nro = $presupuesto['customer_document_number']; //cuit
		$si->voucher_id = 88; //Presupuesto de venta
		$si->pto_vta = 1;
		$si->cbte_desde = $si->getNextNumber();
		$si->cbte_hasta = $si->getNextNumber();
		$si->cbte_fch = date('Y-m-d');
		$si->cae = null;
		$si->cae_fch_vto = null;
		$si->iibb_percentage = 0;
		$si->percerp_iibb = 0;
		$si->status_id = Status::PENDIENTE; // $presupuesto['status_id'];
		$si->user_id = auth()->user()->id;
		$si->afip_data = null;
		$si->pedido_cliente_id = $presupuesto['id'];

		$si->save();
		$si->refresh();

		collect($presupuesto['items'])->map(function ($item) use ($si) {
			if ($item['quantity'] == 0 || is_null($item['quantity'])) {
				$quantity = 1;
			} else {
				$quantity = $item['quantity'];
			}

			$costo = (float)$item['gains']['costo'] * $quantity;

			$si->items()->create([
				'sale_invoice_id' => $si->id,
				'product_id' => $item['product_id'],
				'quantity' =>  $quantity,
				'rounded_mts' => (float) $item['rounded_mts'],
				'isCHP' => $item['isCHP'],
				'real_mts' => (float) $item['real_mts'],
				'mts_by_unity' => ($quantity == 0) ? 0 : (float) $item['real_mts'] / $quantity,
				'mts_to_invoiced' => (float) $item['mts_to_invoiced'],
				'neto_import' => $item['neto_import'],
				'iva_import' => $item['iva_import'],
				'unit_price' => $item['unit_price'],
				'iva_id' => $item['iva_id'],
				'discount' => $item['discount'],
				'discount_import' => $item['discount_import'],
				'total' => $item['total_raw'],
				'obs' => null,
				'price_list_id' => $item['gains']['pricelist_id'],
				'benefit' => $item['gains']['benefit'],
				'costo' => $item['isCHP'] ? (float)$item['gains']['costo'] * (float) $item['mts_to_invoiced'] : $costo,
				'earning' => $item['isCHP'] ? (float)$item['neto_import'] - (float)$item['gains']['costo'] * (float) $item['mts_to_invoiced'] : (float)$item['neto_import'] - (float)$costo,
				'voucher_id' => $si->voucher_id
			]);

			$product = Product::find($item['product_id']);

			$array = [];

			if ($item['isCHP']) {
				$array = ['cantidad' => $quantity, 'mts_by_unity' => (float) $item['real_mts'] / $quantity, 'mts_totales' => $item['real_mts']];
			} else {
				$array = ['cantidad' => $quantity];
			}

			//$actual_stock = $product->stock - $quantity;

			product_history($product, 'descuenta stock', auth()->user()->id, $array, $product->stock);
		});

		$pc = PedidoCliente::find($presupuesto['id']);
		$pc->sale_invoice_id = $si->id;
		$pc->save();

		$new_presupuesto =  fractal($si, new PresupuestoVentaTransformer())->toArray()['data'];

		return response()->json($new_presupuesto, 201);
	}
}
