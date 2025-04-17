<?php

namespace App\Core;

use App\Core\Exceptions\BadRequestException;
use App\Core\Exceptions\NotFoundException;

class RequestHandler
{
    public static function handle(Router $router): void
    {
        header('Content-Type: application/json');

        if (in_array($_SERVER['REQUEST_METHOD'], ['POST', 'PUT'])) {
            $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
            if (stripos($contentType, 'application/json') === false) {
                throw new BadRequestException('O corpo da requisição deve ser application/json');
            }
        }

        $found = $router->dispatch();

        if (!$found) {
            throw new NotFoundException('Rota não encontrada');
        }
    }
}
