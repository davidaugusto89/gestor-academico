<?php

namespace App\Domain\Matricula\Exceptions;

use App\Core\Exceptions\BadRequestException;

class MatriculaDuplicadaException extends BadRequestException
{
    public function __construct(string $mensagem = "Aluno já está matriculado nesta turma.", int $codigo = 0)
    {
        parent::__construct($mensagem, $codigo);
    }
}
