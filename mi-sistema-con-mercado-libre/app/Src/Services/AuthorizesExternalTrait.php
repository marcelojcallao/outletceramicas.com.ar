<?php

namespace App\Src\Services;

use App\Src\Services\MercadoLibre\MeliAuthenticationServices;
use Illuminate\Database\Eloquent\Model;

trait AuthorizesExternalTrait
{
    /**
     * resolveAuthorization
     *
     * @param  mixed $queryParams
     * @param  mixed $formParams
     * @param  mixed $headers
     * @return void
     */
    public function resolveAuthorization(&$queryParams, &$formParams, &$headers)
    {
        $accessToken = $this->resolveAccessToken();

        $headers['Authorization'] = $accessToken;
    }
    
    /**
     * resolveAccessToken
     *
     * @return void
     */
    public function resolveAccessToken()
    {
        $authenticationService = resolve(MeliAuthenticationServices::class);

        return $authenticationService->getClientCredentialsToken();
    }
}
