<?php

namespace App\Domain\Matricula;

/**
 * Filtros disponíveis para listagem de matrículas.
 */
class Filter
{
    /**
     * Retorna os campos permitidos para filtro e ordenação.
     *
     * @return string[]
     */
    public static function camposPermitidos(): array
    {
        return ['aluno_id', 'turma_id', 'data_matricula'];
    }
}
