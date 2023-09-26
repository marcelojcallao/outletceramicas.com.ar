<?php

namespace App\Http\Controllers\Api;

use App\Src\Models\City;
use App\Src\Models\Address;
use App\Src\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Validator;
use App\Exceptions\Afip\NotFoundPerson;
use App\Src\Afip\WS\Responses\WSPUCResponse;
use App\Transformers\Customer\CustomerTransformer;
use App\Http\Requests\Customer\CustomerFromModalRequest;
use App\Http\Requests\Customer\StoreCustomerFormRequest;
use Exception;

class CustomerController extends Controller
{
    const CONSUMIDOR_FINAL = 5;
    
    const DNI = 35;

    const ACTIVE = 1;

    public function index()
    {
        $ctmrs = Customer::query();

        if(request()->customer)
        {
            $ctmrs = $ctmrs->where('id', request()->customer);
        }

        $ctmrs = $ctmrs->where('status_id', self::ACTIVE)->paginate(20);

        $customers = fractal($ctmrs, new CustomerTransformer())->toArray()['data'];
        
        $response = [
            'pagination' => [
                'total' => $ctmrs->total(),
                'per_page' => $ctmrs->perPage(),
                'current_page' => $ctmrs->currentPage(),
                'last_page' => $ctmrs->lastPage(),
                'from' => $ctmrs->firstItem(),
                'to' => $ctmrs->lastItem()
            ],
            'data' => $customers
        ];
        
        return response()->json($response, 200);
    }
    

    public function store_basic_data(CustomerFromModalRequest $request)
    {
        $customer = new Customer;
        $customer->name = strtoupper($request->customer['name']);
        $customer->number = $request->customer['dni'];
        $customer->inscription_id = self::CONSUMIDOR_FINAL;
        $customer->purchaser_document_id = self::DNI;
        $customer->save();
        $customer->fresh();
        
        return response()->json($customer, 201);

    }


    public function search_customer()
    {
        $customers = Customer::where('name', 'like', '%' . request()->customer . '%')
            ->orWhere('number', 'like', '%' . request()->customer . '%')
            ->where('status_id', self::ACTIVE)
            ->take(51)->get(['id', 'name', 'number']);

        return response()->json($customers, 200);
    }

    public function show()
    {
        $cstmr = Customer::find(request()->customer_id);

        $customer = fractal($cstmr, new CustomerTransformer())->toArray()['data'];

        return response()->json($customer, 200);
    }

    public function delete()
    {
        $customer = Customer::find(request()->customer['id']);

        if ($customer->sale_invoices()->exists()) {
            throw new \Exception('Éste cliente ya posee comprobantes de ventas asignados a su nombre, no se eliminará', 400);
        }

        if($customer->pedidos()->exists()){
            $customer->pedidos()->detele();
        }
        
        $customer->delete();

        return response()->json($customer, 200);
    }

    public function store(StoreCustomerFormRequest $request)
    {
        $customer = new Customer;

        $customer = $this->saveCustomer($customer, $request->customer, __FUNCTION__);

        return response()->json($customer, 201);
    }

    public function update(StoreCustomerFormRequest $request)
    {
        $customer_request_data = $request->customer;

        $customer = Customer::find($customer_request_data['id']);

        $customer = $this->saveCustomer($customer, $customer_request_data, __FUNCTION__);

        return response()->json($customer, 201);
    }

    public function saveCustomer($customer, $customer_request_data, $method = null)
    {
        $customer->name = strtoupper($customer_request_data['name']);
        $customer->number = $customer_request_data['number'];
        $customer->inscription_id = $customer_request_data['inscription']['id'];
        $customer->purchaser_document_id = $customer_request_data['purchase_document']['id'];
        $customer->cell_phone = $customer_request_data['contact']['cell_phone'];
        $customer->phone_1 = $customer_request_data['contact']['phone_1'];
        $customer->contact = strtoupper($customer_request_data['contact']['name']) ;
        $customer->email = $customer_request_data['contact']['email'];
        $customer->afip_data = $customer_request_data['afip_data'];

        $customer->save();
        $customer->fresh();

        if ($customer_request_data['address']['state'] != null && $customer_request_data['address']['city'] != null && $customer_request_data['address']['address'] != null) {

            if ($method == "store") {
                $address = new Address();
                $this->saveAddress($customer_request_data, $address, $customer);
            }

            if ($method == "update") {
                $address = Address::where('addressable_id', $customer_request_data['id'])->get()->first();
                if ($address) {
                    $this->saveAddress($customer_request_data, $address, $customer);
                }else{
                    $address = new Address();
                    $this->saveAddress($customer_request_data, $address, $customer);
                }
            }
            
        }

        return $customer;
    }

    public function saveAddress($customer_request_data, $address, $customer)
    {
        $address->country_id = 1;
        $address->province_id = $customer_request_data['address']['state']['id'];
        $address->city = strtoupper($customer_request_data['address']['city']) ;
        $address->address = strtoupper($customer_request_data['address']['address']);
        $address->number = null;
        $address->cp = strtoupper($customer_request_data['address']['cp']);
        $address->obs = strtoupper($customer_request_data['address']['obs']);
        $address->between_streets = strtoupper($customer_request_data['address']['between']);
        $address->addressable_id = $customer->id;
        $address->addressable_type = 'App\Src\Models\Customer';
        $address->save();

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

    public function save_customer_address()
    {   
        $data = request()->all();

        $address = new Address();

        $customer = Customer::find($data['customer_id']);

        $address_exists = Address::where('addressable_type', 'App\Src\Models\Customer')->where('addressable_id', $data['customer_id'])->get()->first();

        if ($address_exists) {
            unset($address);
            if ($address = $this->saveAddress(request()->all(), $address_exists, $customer)) {
                return response()->json($address, 201);
            }
        }

        if ($address = $this->saveAddress(request()->all(), $address, $customer)) {
            return response()->json($address, 201);
        }

        throw new Exception('No se puedo guardar el domicilio del cliente', 431);
    }
}
