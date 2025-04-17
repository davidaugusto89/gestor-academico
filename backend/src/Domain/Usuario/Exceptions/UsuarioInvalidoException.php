<?php

namespace App\Domain\Usuario\Exceptions;

use App\Core\Exceptions\BadRequestException;

class UsuarioInvalidoException extends BadRequestException
{
    public function __construct(string $mensagem = "Usuário inválido.", int $codigo = 0)
    {
        parent::__construct($mensagem, $codigo);
    }
}
