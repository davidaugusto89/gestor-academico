<?php

namespace App\Domain\Matricula\Exceptions;

use App\Core\Exceptions\BadRequestException;

class MatriculaInvalidaException extends BadRequestException
{
    public function __construct(string $mensagem = "Matrícula inválida.", int $codigo = 0)
    {
        parent::__construct($mensagem, $codigo);
    }
}
