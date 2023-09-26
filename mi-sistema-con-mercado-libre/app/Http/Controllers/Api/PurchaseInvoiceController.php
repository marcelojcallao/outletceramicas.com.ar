<?php

namespace App\Http\Controllers\Api;

use App\Src\Models\PurchaseInvoice;
use App\Events\PurchaseInvoiceSaved;
use App\Http\Controllers\Controller;
use App\Rules\UniquePurchaseInvoice;
use App\Src\Traits\PurchaseInvoiceTrait;
use App\Events\WebHookMessageWasReceived;
use App\Http\Requests\PurchaseInvoiceRequest;
use App\Src\Models\Status;
use App\Src\Repositories\App\PurchaseInvoiceRepository;
use App\Transformers\PurchaseInvoice\PurchaseInvoiceTransformer;
use App\Transformers\PurchaseInvoice\PurchaseInvoiceListTransformer;

class PurchaseInvoiceController extends Controller
{
    use PurchaseInvoiceTrait;
    
    const NOTA_CREDITO_A = 3;

    const NOTA_CREDITO_B = 8;

    const NOTA_CREDITO_C = 13;

    const TICKET_NOTA_CREDITO_A = 76;

    const TICKET_NOTA_CREDITO_B = 77;

    const TICKET_NOTA_CREDITO_C = 78;

    const NOTA_CREDITO_A_MI_PYME = 94;

    const NOTA_CREDITO_B_MI_PYME = 97;

    const NOTA_CREDITO_C_MI_PYME = 100;

    private $purchase_invoice_repo;
    
    public function __construct()
    {
        $this->purchase_invoice_repo = new PurchaseInvoiceRepository;
    }

    public function index()
    {
        $pi = $this->search(request());
        
        $pi = $pi->paginate(20);
        
        $purchase_invoices = fractal($pi, new PurchaseInvoiceListTransformer())->toArray()['data'];

        $response = [
            'pagination' => [
                'total' => $pi->total(),
                'per_page' => $pi->perPage(),
                'current_page' => $pi->currentPage(),
                'last_page' => $pi->lastPage(),
                'from' => $pi->firstItem(),
                'to' => $pi->lastItem()
            ],
            'data' => $purchase_invoices,
        ];

        return response()->json($response, 200);
    }


    public function store(PurchaseInvoiceRequest $request)
    {
        $invoice = request()->all();

        $pi = $this->purchase_invoice_repo->create($invoice);

        $purchase_invoice = $this->purchase_invoice_repo->create_items($pi, $invoice['invoice']['products']);
        
        $taxes = $this->purchase_invoice_repo->create_tax($pi, $invoice['invoice']['taxes']);

        $pi = fractal($purchase_invoice, new PurchaseInvoiceTransformer())->toArray()['data'];
        
        return response()->json($pi, 201);
    }

    public function get_notas_credito_from_supplier()
    {
        
        $invoices = PurchaseInvoice::where('provider_id', (int) request()->supplier_id)
            ->where('status_id', (int) Status::PENDIENTE)
            ->whereIn('voucher_id', [
                self::NOTA_CREDITO_A,
                self::NOTA_CREDITO_B,
                self::NOTA_CREDITO_C,
                self::TICKET_NOTA_CREDITO_A,
                self::TICKET_NOTA_CREDITO_B,
                self::TICKET_NOTA_CREDITO_C,
                self::NOTA_CREDITO_A_MI_PYME,
                self::NOTA_CREDITO_B_MI_PYME,
                self::NOTA_CREDITO_C_MI_PYME,
            ])
            ->get();
        
        $invoices = fractal($invoices, new PurchaseInvoiceTransformer())->toArray()['data'];

        return response()->json($invoices, 200);
    }
}
