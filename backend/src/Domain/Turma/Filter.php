<?php

namespace App\Domain\Turma;

/**
 * Classe responsável por definir os campos permitidos para filtros de Turma.
 *
 * @package App\Domain\Turma
 */
class Filter
{
    /**
     * Retorna os campos permitidos para filtragem de turmas.
     *
     * @return array
     */
    public static function camposPermitidos(): array
    {
        return ['id', 'nome'];
    }
}
