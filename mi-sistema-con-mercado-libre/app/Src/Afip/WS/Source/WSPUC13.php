<?php namespace App\Src\Afip\WS\Source;

use App\Src\Afip\WS\AfipWebService;
use App\Exceptions\Afip\OwnerSoapFaultException;

class WSPUC13 extends AfipWebService {

    const SERVICE = 'ws_sr_padron_a13';

    public function __construct($environment='PRODUCTION') 
    {
        parent::__construct(self::SERVICE, $environment);
    }

    public function getPersonaByDocumento($dni)
    {
        $consulta =  [
            'token' => $this->token,
            'sign'  => $this->sign,
            'cuitRepresentada'  => $this->cuitRepresentada,
            'documento'         => $dni
        ];
        \Log::info('WSPUC13->getPersonaByDocumento ' . collect($consulta)->toJson());
        try {

            $result = $this->client->getIdPersonaListByDocumento($consulta);
            //dd($result);
            if (is_soap_fault($result)) 
            
                throw new OwnerSoapFaultException; 

            $r =  json_decode(json_encode($result), true);
            
            return $r;
            

        } catch (OwnerSoapFaultException $e) {
            return response()->json(['message' => $e->getMessage()], 409);
        }
        catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 413);
        }
    }
        
}

?>
