<?php

namespace App\Domain\Aluno;

class Filter
{
    public static function camposPermitidos(): array
    {
        return ['id', 'nome'];
    }
}
