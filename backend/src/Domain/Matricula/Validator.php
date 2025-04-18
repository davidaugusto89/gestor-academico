<?php

namespace App\Domain\Matricula;

use App\Domain\Matricula\Exceptions\MatriculaInvalidaException;

/**
 * Classe responsável pela validação dos dados de matrícula.
 */
class Validator
{
    /**
     * Valida os dados da matrícula.
     *
     * @param DTO $dto DTO contendo IDs do aluno e da turma.
     * @throws MatriculaInvalidaException Se os IDs forem inválidos.
     */
    public static function validar(DTO $dto): void
    {
        if ($dto->getAlunoId() <= 0 || $dto->getTurmaId() <= 0) {
            throw new MatriculaInvalidaException("Aluno ou turma inválidos.");
        }
    }
}
