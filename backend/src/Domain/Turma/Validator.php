<?php

namespace App\Domain\Turma;

use App\Domain\Turma\Exceptions\TurmaInvalidaException;

class Validator
{
    public static function validar(DTO $dto): void
    {
        if (strlen(trim($dto->nome)) < 3) {
            throw new TurmaInvalidaException("O nome da turma deve ter no mínimo 3 caracteres.");
        }

        if (empty($dto->descricao)) {
            throw new TurmaInvalidaException("A descrição da turma é obrigatória.");
        }
    }
}
