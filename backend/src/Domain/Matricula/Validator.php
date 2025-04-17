<?php

namespace App\Domain\Matricula;

use App\Domain\Matricula\Exceptions\MatriculaInvalidaException;

class Validator
{
    public static function validar(DTO $dto): void
    {
        if ($dto->aluno_id <= 0 || $dto->turma_id <= 0) {
            throw new MatriculaInvalidaException("Aluno ou turma inválidos.");
        }
    }
}
