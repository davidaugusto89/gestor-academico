<?php

namespace App\Core\Exceptions;

use App\Core\HttpStatus;

class BadRequestException extends HttpException
{
    public function __construct(string $message = 'Bad Request')
    {
        parent::__construct($message, HttpStatus::BAD_REQUEST);
    }
}
