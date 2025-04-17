<?php

namespace App\Domain\Turma;

class Filter
{
    public static function camposPermitidos(): array
    {
        return ['id', 'nome'];
    }
}
