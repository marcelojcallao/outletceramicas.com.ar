<?php

namespace App\Src\Services\MercadoLibre;

use Exception;
use App\Src\Services\AuthorizesExternalTrait;
use App\Src\Services\ConsumesExternalServices;
use App\Src\Services\InteracWithExternalResponsesTrait;

class MeliServices
{
    use ConsumesExternalServices, InteracWithExternalResponsesTrait, AuthorizesExternalTrait;

    protected $baseUri;

    public function __construct()
    {
        $this->baseUri = config('services.meli.base_uri');
    }
    
    
}
