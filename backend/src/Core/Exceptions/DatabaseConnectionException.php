<?php

namespace App\Core\Exceptions;

use App\Core\HttpStatus;
use Exception;
use Throwable;

class DatabaseConnectionException extends Exception
{
    protected array $context;

    public function __construct(
        string $message = 'Erro ao conectar ao banco de dados.',
        int $code = HttpStatus::INTERNAL_SERVER_ERROR,
        ?Throwable $previous = null,
        array $context = []
    ) {
        $this->context = $context;
        parent::__construct($message, $code, $previous);
    }
}
