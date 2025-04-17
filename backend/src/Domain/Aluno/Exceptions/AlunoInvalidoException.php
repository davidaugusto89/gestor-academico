<?php

namespace App\Domain\Aluno\Exceptions;

use App\Core\Exceptions\BadRequestException;

class AlunoInvalidoException extends BadRequestException
{
    public function __construct(string $mensagem = "Aluno inválido.", int $codigo = 0)
    {
        parent::__construct($mensagem, $codigo);
    }
}
