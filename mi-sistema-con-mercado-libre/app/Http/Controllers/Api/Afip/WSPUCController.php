<?php

namespace App\Http\Controllers\Api\Afip;

use Illuminate\Http\Request;
use App\Src\Afip\WS\AfipWSPadron;
use App\Http\Controllers\Controller;
use App\Exceptions\Afip\NotFoundPerson;
use App\Src\Afip\WS\Source\WSPUCManager;
use App\Src\Afip\WS\Responses\WSPUCResponse;
use App\Exceptions\Afip\AfipResponseException;

class WSPUCController extends Controller
{
    protected $afip_padron;

    public function __construct()
    {
        $this->afip_padron = new AfipWSPadron('production');
    }

    public function getPersona()
    {
        $afip_data = $this->afip_padron->getPersona(request()->cuit);

        //dd($person);
        /* $response = new WSPUCResponse($person);

        if ($response->hasErrorConstancia() || $response->hasErrorRegimenGeneral()){

            $err = collect([
                'Code' => 0,
                'Msg' => $response->getMsgError(),
            ]);

            throw new AfipResponseException($err->toJson(), 444);
        } */  

        return response()->json($afip_data, 200);
    }
}
