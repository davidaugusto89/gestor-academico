<?php

namespace App\Domain\Usuario;

class Filter
{
    public static function camposPermitidos(): array
    {
        return ['id', 'nome'];
    }
}
