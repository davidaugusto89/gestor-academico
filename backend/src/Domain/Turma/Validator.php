<?php

namespace App\Domain\Turma;

use App\Domain\Turma\Exceptions\TurmaInvalidaException;

/**
 * Validador de dados da entidade Turma.
 */
class Validator
{
    /**
     * Valida os dados fornecidos para uma turma.
     *
     * @param DTO $dto Dados a serem validados
     * @throws TurmaInvalidaException Se nome ou descrição forem inválidos
     */
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
