<?php

namespace App\Transformers\CheckAccount;

use App\Src\Models\CheckingAccount;
use League\Fractal\TransformerAbstract;

class CheckAccountTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(CheckingAccount $cha)
    {
        return [
            'id' => $cha->id,
            'bank' => $cha->bank->name,
            'code' => $cha->code,
            'active' => ($cha->active) ? 'Activo' : 'Desactivado',
        ];
    }
}
