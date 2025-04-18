<?php

namespace App\Domain\Matricula;

use Dotenv\Parser\Entry;

interface Repository
{
    /**
     * @param Entity $matricula
     */
    public function matricular(Entity $matricula): void;

    public function listarPorTurma(int $turmaId): array;

    public function alunoJaMatriculado(int $alunoId, int $turmaId): bool;

    /**
     * @param Entity $matricula
     */
    public function remover(Entity $matricula): void;
}
