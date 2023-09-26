<?php

namespace App\Http\Controllers\Api\Afip;

use Exception;
use App\Src\Models\Company;
use App\Src\Models\Customer;
use Illuminate\Http\Request;
use App\Src\Models\SaleInvoice;
use App\Src\Afip\WS\AfipWSPadron;
use App\Src\Models\PedidoCliente;
use App\Src\Traits\DateFormatTrait;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Src\Afip\FacturacionElectronica\WSCONST;
use App\Src\Repositories\App\SaleInvoiceRepository;
use App\Transformers\Afip\GetCaeOnAFipToSaveTransformer;
use App\Transformers\SaleInvoice\PdfSaleInvoiceTransformer;
use App\Src\Afip\FacturacionElectronica\AfipFactoryCreators;

class SaleInvoiceController extends Controller
{
	use DateFormatTrait;

	const DNI = 96;

	const CONSUMIDOR_FINAL = 5;

	private $SIRepo;

	public function __construct(SaleInvoiceRepository $SIRepo)
	{
		$this->SIRepo = $SIRepo;
	}

	public function generateInvoiceStep1()
	{
		$company = Company::find(request()->company);

		$customer = Customer::find(request()->customer);

		$voucher_type = request()->type;

		$customer_inscription = ($customer->inscription()->exists()) ? $customer->inscription->id : self::CONSUMIDOR_FINAL;

		$invoiceCreator = AfipFactoryCreators::createInstance($voucher_type, $company->inscription_id, $customer_inscription);

		$purchaser_document_afip_code = ($customer->purchaserDocument()->exists()) ? $customer->purchaserDocument->afip_code : self::DNI;

		$FECAESolicitarResult_y_tributos = $invoiceCreator->generate_invoice($purchaser_document_afip_code, $customer->number, request()->items, request()->invoice_date, request()->environment);

		if ($FECAESolicitarResult_y_tributos['FECAESolicitarResult']->FECAESolicitarResult->FeCabResp->Resultado == "R") {
			activity('ERROR')
				->withProperties(['AFIP' => collect($FECAESolicitarResult_y_tributos['FECAESolicitarResult'])->toArray()])
				->log('GENERA COMPROBANTE DE VENTA');

			$error_message = '';

			$FECAESolicitarResult_y_tributos  = json_decode(json_encode($FECAESolicitarResult_y_tributos['FECAESolicitarResult']), true);

			if (array_key_exists('Errors', $FECAESolicitarResult_y_tributos['FECAESolicitarResult'])) {

				foreach ($FECAESolicitarResult_y_tributos['FECAESolicitarResult']['Errors'] as $err) {
					$error_message = "{$error_message} {$err['Msg']}";
				}
			}
			if (array_key_exists('Observaciones', $FECAESolicitarResult_y_tributos['FECAESolicitarResult']['FeDetResp']['FECAEDetResponse'])) {

				foreach ($FECAESolicitarResult_y_tributos['FECAESolicitarResult']['FeDetResp']['FECAEDetResponse']['Observaciones']['Obs'] as $key => $value) {
					if ($key === 'Msg') {
						$error_message = "{$error_message} {$value}";
					}
				}
			}

			throw new \Exception($error_message, 431);
		}

		$FECAESolicitarResult = fractal($FECAESolicitarResult_y_tributos['FECAESolicitarResult'], new GetCaeOnAFipToSaveTransformer())->toArray()['data'];

		$si = $this->SIRepo->create($FECAESolicitarResult, request()->pedido_cliente_id, request()->items, null, $FECAESolicitarResult_y_tributos['tributos'], request()->comments);

		$pc = PedidoCliente::find(request()->pedido_cliente_id);

		$pc->sale_invoice_id = $si->id;

		$pc->save();

		$si =  fractal($si, new PdfSaleInvoiceTransformer())->toArray()['data'];

		return response()->json($si, 201); //acá retornA LA RESPUESTA DE AFIP FECAE SOLICITAR RESULT

	}

	public function generate_nota_credito()
	{
		$data = request()->payload;

		$customer = Customer::find($data['nota_credito']['customer']['id']);

		$afip_padron = new AfipWSPadron('production');

		if (!$afip_padron->isCUITorCUIL($customer->number)) {

			if (!($customer->number == '11111111') || !($customer->number == 11111111)) {
				# code...
				$afip_data = $afip_padron->getPersona($customer->number);
				Log::debug('pppppppppppppppp');
				Log::debug(serialize($afip_data));
				Log::debug('pppppppppppppppp');
				Log::debug('');

				if (array_key_exists('personaReturn', $afip_data)) {

					if (array_key_exists('errorConstancia', $afip_data['personaReturn'])) {

						$number = '';

						if (array_key_exists('datosGenerales', $afip_data['personaReturn']['errorConstancia'])) {
							$number = $afip_data['personaReturn']['datosGenerales']['idPersona'];
						}
						if (array_key_exists('idPersona', $afip_data['personaReturn']['errorConstancia'])) {
							$number = $afip_data['personaReturn']['errorConstancia']['idPersona'];
						}

						$customer->number = $number;
						$customer->save();
						$customer->refresh();
					}
				} else {

					if (array_key_exists('idPersonaListReturn', $afip_data)) {

						$customer->number = $afip_data['idPersonaListReturn']['idPersona'];
						$customer->save();
						$customer->refresh();
					}
				}
			}
		}

		$data['customer_cuit'] = $customer->number;
		$data['nota_credito']['CbtesAsoc'][0]['Cuit'] = $customer->number + 0;
		$data['nota_credito']['CbtesAsoc'][0]['CbteFch'] = $this->ShortDateToYyyymmddFormat($data['nota_credito']['CbtesAsoc'][0]['CbteFch']);

		Log::debug("-----------------------------------------");
		Log::debug(collect($data)->toJson());
		Log::debug("-----------------------------------------");

		$nota_credito_creator = AfipFactoryCreators::createInstance(WSCONST::NOTA_CREDITO, $data['company_inscription_id'], $data['nota_credito']['customer']['inscription_id']);

		Log::debug("---------------nota_credito_creator--------------------------");
		Log::debug(serialize($nota_credito_creator));
		Log::debug("------------------nota_credito_creator-----------------------");

		$nota_credito = $nota_credito_creator->generate_nota_credito($data['environment'], $data);

		Log::debug("-----------------------------------------");
		Log::debug(serialize($nota_credito));
		Log::debug("-----------------------------------------");

		if ($nota_credito['FECAESolicitarResult']->FECAESolicitarResult->FeCabResp->Resultado == "R") {
			activity('ERROR')
				->withProperties(['AFIP' => collect($nota_credito['FECAESolicitarResult'])->toArray()])
				->log('GENERA COMPROBANTE DE VENTA');

			throw new \Exception("Ha ocurrido un error con el Web Service de Afip", 431);
		}

		$si = new SaleInvoice;
		$si->company_id = $data['company_id'];
		$si->customer_id = $customer->id;
		$si->doc_nro = $customer->number;
		$si->voucher_id = $nota_credito['FECAESolicitarResult']->FECAESolicitarResult->FeCabResp->CbteTipo;
		$si->pto_vta = $nota_credito['FECAESolicitarResult']->FECAESolicitarResult->FeCabResp->PtoVta;
		$si->cbte_desde = $nota_credito['FECAESolicitarResult']->FECAESolicitarResult->FeDetResp->FECAEDetResponse->CbteDesde;
		$si->cbte_hasta = $nota_credito['FECAESolicitarResult']->FECAESolicitarResult->FeDetResp->FECAEDetResponse->CbteHasta;
		$si->cbte_fch = $nota_credito['FECAESolicitarResult']->FECAESolicitarResult->FeDetResp->FECAEDetResponse->CbteFch;
		$si->cae = $nota_credito['FECAESolicitarResult']->FECAESolicitarResult->FeDetResp->FECAEDetResponse->CAE;
		$si->cae_fch_vto = $nota_credito['FECAESolicitarResult']->FECAESolicitarResult->FeDetResp->FECAEDetResponse->CAEFchVto;
		$si->iibb_percentage = ((float)$data['nota_credito']['ImpTrib'] > 0) ? $data['nota_credito']['Tributos']['Alic'] : 0;
		$si->percerp_iibb = ((float)$data['nota_credito']['ImpTrib'] > 0) ? $data['nota_credito']['Tributos']['Importe'] : 0;
		$si->status_id = WSCONST::PENDIENTE;
		$si->pedido_cliente_id = $data['nota_credito']['pedido_cliente_id'];
		$si->user_id = auth()->user()->id;
		$si->parent_id = $data['nota_credito']['text']['sale_invoice_id'];
		$si->afip_data = collect($nota_credito['FECAESolicitarResult'])->toArray();
		$si->save();

		$si->items()->create([
			'sale_invoice_id' => $si->id,
			'quantity' => 1,
			'neto_import' => $data['nota_credito']['Neto'],
			'iva_import' => $data['nota_credito']['IvaImport'],
			'iva_id' => $data['nota_credito']['iva_id']['iva_id'],
			'discount' => 0,
			'unit_price' => 0,
			'discount_import' => 0,
			'total' => $data['nota_credito']['ImpTotal'],
			'obs' => $data['nota_credito']['text']['detail']
		]);

		activity('OK')
			->withProperties(['AFIP' => collect($nota_credito['FECAESolicitarResult'])->toArray()])
			->log('GENERA COMPROBANTE DE VENTA');

		$order_to_restore = PedidoCliente::find($data['nota_credito']['pedido_cliente_id']);
		$order_to_restore->status_id = 4; //presupuestado
		$order_to_restore->save();

		return response()->json($si, 201);
	}
}
