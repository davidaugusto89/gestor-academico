<?php

namespace App\Domain\Matricula;

use App\Domain\Matricula\Exceptions\MatriculaInvalidaException;

class Validator
{
    public static function validar(DTO $dto): void
    {
        if ($dto->getAlunoId() <= 0 || $dto->getTurmaId() <= 0) {
            throw new MatriculaInvalidaException("Aluno ou turma inválidos.");
        }
    }
}
