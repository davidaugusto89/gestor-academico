<?php

namespace App\Domain\Turma\Exceptions;

use App\Core\Exceptions\BadRequestException;

class TurmaJaExisteException extends BadRequestException
{
    public function __construct(string $message = "Turma já existe.", int $code = 0)
    {
        parent::__construct($message, $code);
    }
}
