<?php

namespace App\Domain\Aluno\Exceptions;

use App\Core\Exceptions\BadRequestException;

class AlunoJaExisteException extends BadRequestException
{
    public function __construct(string $mensagem = "Aluno já cadastrado com esse email ou CPF.", int $codigo = 0)
    {
        parent::__construct($mensagem, $codigo);
    }
}
