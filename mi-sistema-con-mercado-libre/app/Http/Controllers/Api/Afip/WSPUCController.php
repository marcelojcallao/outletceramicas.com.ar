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

		return response()->json($afip_data, 200);
	}
}
