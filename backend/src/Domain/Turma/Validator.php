<?php

namespace App\Domain\Turma;

use App\Domain\Turma\Exceptions\TurmaInvalidaException;

class Validator
{
    public static function validar(DTO $dto): void
    {
        if (strlen(trim($dto->getNome())) < 3) {
            throw new TurmaInvalidaException("O nome da turma deve ter no mínimo 3 caracteres.");
        }

        if (empty($dto->getDescricao())) {
            throw new TurmaInvalidaException("A descrição da turma é obrigatória.");
        }
    }
}
