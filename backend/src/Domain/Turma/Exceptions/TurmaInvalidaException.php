<?php

namespace App\Domain\Turma\Exceptions;

use App\Core\Exceptions\BadRequestException;

class TurmaInvalidaException extends BadRequestException
{
    public function __construct(string $message = "Turma inválida.", int $code = 0)
    {
        parent::__construct($message, $code);
    }
}
