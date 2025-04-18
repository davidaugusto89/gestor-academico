<?php

namespace App\Domain\Turma\Exceptions;

use App\Core\Exceptions\BadRequestException;

/**
 * Exceção lançada quando já existe uma turma com o mesmo nome.
 */
class TurmaJaExisteException extends BadRequestException
{
    /**
     * @param string $message Mensagem de erro personalizada
     * @param int $code Código do erro (opcional)
     */
    public function __construct(string $message = "Turma já existe.", int $code = 0)
    {
        parent::__construct($message, $code);
    }
}
