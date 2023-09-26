<?php

namespace App\Transformers\Cash;

use App\Src\Models\PettyCash;
use App\Src\Traits\DateFormatTrait;
use League\Fractal\TransformerAbstract;

class CashTransformer extends TransformerAbstract
{
    use DateFormatTrait;
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(PettyCash $cash)
    {
        return [
            'id' => $cash->id,
            'type' => ($cash->type_id === 1) ? 'ENTRADA' : 'SALIDA',
            'description' => strtoupper($cash->description) ,
            'date' => $this->ShortDateToArgentinaFormat($cash->date),
            'import' => $cash->import,
        ];
    }
}
