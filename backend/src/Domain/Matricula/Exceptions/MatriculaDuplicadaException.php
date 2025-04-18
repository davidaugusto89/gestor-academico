<?php

namespace App\Domain\Matricula\Exceptions;

use App\Core\Exceptions\BadRequestException;

/**
 * Exceção lançada quando um aluno já está matriculado na turma informada.
 *
 * Usada para impedir matrículas duplicadas.
 */
class MatriculaDuplicadaException extends BadRequestException
{
    /**
     * @param string $mensagem Mensagem da exceção
     * @param int $codigo Código da exceção (opcional)
     */
    public function __construct(string $mensagem = "Aluno já está matriculado nesta turma.", int $codigo = 0)
    {
        parent::__construct($mensagem, $codigo);
    }
}
