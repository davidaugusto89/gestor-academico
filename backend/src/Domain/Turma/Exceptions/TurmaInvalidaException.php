<?php

namespace App\Domain\Turma\Exceptions;

use App\Core\Exceptions\BadRequestException;

/**
 * Exceção lançada quando os dados de uma turma são inválidos.
 */
class TurmaInvalidaException extends BadRequestException
{
    /**
     * @param string $message Mensagem de erro personalizada
     * @param int $code Código do erro (opcional)
     */
    public function __construct(string $message = "Turma inválida.", int $code = 0)
    {
        parent::__construct($message, $code);
    }
}
