<?php

namespace App\Core\Exceptions;

use App\Core\HttpStatus;

class UnauthorizedException extends HttpException
{
    public function __construct(string $mensagem = "Acesso não autorizado", int $code = HttpStatus::UNAUTHORIZED)
    {
        parent::__construct($mensagem, $code);
    }
}
