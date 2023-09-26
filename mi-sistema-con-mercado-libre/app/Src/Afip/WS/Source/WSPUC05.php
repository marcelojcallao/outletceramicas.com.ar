<?php namespace App\Src\Afip\WS\Source;

use App\Src\Afip\WS\AfipWebService;
use Illuminate\Support\Facades\Log;
use App\Src\Afip\Traits\WSPadronTrait;

class WSPUC05 extends AfipWebService{

    const SERVICE = 'ws_sr_padron_a5';

    use WSPadronTrait;
    
    public function __construct($environment='PRODUCTION') 
    {
        parent::__construct(self::SERVICE, $environment);
    }
        
} 

?>
