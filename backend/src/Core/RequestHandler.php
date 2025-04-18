<?php

namespace App\Core;

use App\Core\Exceptions\BadRequestException;
use App\Core\Exceptions\NotFoundException;

/**
 * Classe responsável por processar a requisição HTTP e delegar ao roteador.
 */
class RequestHandler
{
    /**
     * Manipula a requisição HTTP, validando o método e conteúdo, e executa o roteador.
     *
     * @param Router $router Instância do roteador a ser utilizada para despachar a rota.
     *
     * @throws BadRequestException Se o conteúdo da requisição não for JSON nos métodos POST ou PUT.
     * @throws NotFoundException Se a rota não for encontrada.
     * @return void
     */
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
