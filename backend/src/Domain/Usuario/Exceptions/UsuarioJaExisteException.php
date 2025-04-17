<?php

namespace App\Domain\Usuario\Exceptions;

use App\Core\Exceptions\BadRequestException;

class UsuarioJaExisteException extends BadRequestException
{
    public function __construct(string $mensagem = "Usuário já cadastrado com esse email.", int $codigo = 0)
    {
        parent::__construct($mensagem, $codigo);
    }
}
