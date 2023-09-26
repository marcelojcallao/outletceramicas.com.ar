<?php

namespace App\Src\Afip\WS;


interface AfipWSCheckErrorInterface
{
    public function __checkErrors($operation, $results);
}
