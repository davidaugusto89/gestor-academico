<?php

namespace App\Domain\Matricula;

class Filter
{
    public static function camposPermitidos(): array
    {
        return ['aluno_id', 'turma_id', 'data_matricula'];
    }
}
