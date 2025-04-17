<?php

namespace App\Core\Exceptions;

use App\Core\HttpStatus;

class NotFoundException extends HttpException
{
    public function __construct(string $message = 'Not Found')
    {
        parent::__construct($message, HttpStatus::NOT_FOUND);
    }
}
