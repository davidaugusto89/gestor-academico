<?php

namespace App\Domain\Aluno;

/**
 * Filtro utilizado para a listagem e busca de alunos.
 */
class Filter
{
    /**
     * Retorna os campos permitidos para filtros e ordenação de alunos.
     *
     * @return string[]
     */
    public static function camposPermitidos(): array
    {
        return ['id', 'nome'];
    }
}
