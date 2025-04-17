<?php

ob_start(); // Inicia o buffer de saída para evitar envio prematuro de headers

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\EnvLoader;
use App\Core\Router;
use App\Core\RequestHandler;
use App\Core\AuthMiddleware;
use App\Core\Exceptions\HttpException;
use App\Core\Exceptions\NotFoundException;
use App\Core\HttpStatus;
use App\Utils\Http;
use App\Utils\Response;

EnvLoader::load();

$router = require_once __DIR__ . '/../src/Routes/api.php';

$rotaAtual = Http::getNormalizedUri();

$rotasPublicas = [
    '/login',
    '/health'
];

try {
    if (!in_array($rotaAtual, $rotasPublicas)) {
        AuthMiddleware::proteger(getallheaders());
    }

    if (!$router->dispatch()) {
        throw new NotFoundException();
    }
} catch (HttpException $e) {
    Response::error($e->getMessage(), $e->getCode() ?: HttpStatus::BAD_REQUEST);
} catch (Throwable $e) {
    print_r($e);
    Response::error('Erro inesperado no servidor.', HttpStatus::INTERNAL_SERVER_ERROR);
}
