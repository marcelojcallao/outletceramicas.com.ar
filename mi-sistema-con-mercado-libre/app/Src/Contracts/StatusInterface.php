<?php

namespace App\Src\Contracts;


interface StatusInterface
{
        const ACTIVO = 1;
        const PENDIENTE = 2;
        const REMITIDO = 3;
        const PRESUPUESTADO = 4;
        const FACTURADO = 5; 
        const PREPARADO = 6;
        const RETIRADO = 7;
        const DESPACHADO = 8;
        const ENTREGADO = 9;
}
