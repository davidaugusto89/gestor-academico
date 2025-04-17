<?php
// Evita exibir erros diretamente na tela (boas práticas de produção)
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL);

ob_start();

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\EnvLoader;
use App\Core\Router;
use App\Core\HttpStatus;
use App\Core\AuthMiddleware;
use App\Core\Exceptions\HttpException;
use App\Core\Exceptions\NotFoundException;
use App\Utils\Response;
use App\Utils\Http;
use App\Utils\Logger;

EnvLoader::load();

$router = require_once __DIR__ . '/../src/Routes/api.php';

$rotaAtual = Http::getNormalizedUri();
$rotasPublicas = ['/login', '/health'];

/**
 * Captura exceções não tratadas
 */
set_exception_handler(function (Throwable $e) {
    Logger::erro($e);
    Response::error('Erro interno no servidor.', HttpStatus::INTERNAL_SERVER_ERROR);
});

/**
 * Captura erros fatais (ex: parse error, type error)
 */
register_shutdown_function(function () {
    $erro = error_get_last();

    if ($erro && in_array($erro['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        Logger::erro($erro['message'] . " em {$erro['file']}:{$erro['line']}");
        Response::error('Erro inesperado no servidor.', HttpStatus::INTERNAL_SERVER_ERROR);
        exit;
    }
});

try {
    if (!in_array($rotaAtual, $rotasPublicas)) {
        AuthMiddleware::proteger(getallheaders());
    }

    if (!$router->dispatch()) {
        throw new NotFoundException();
    }
} catch (HttpException $e) {
    Logger::erro($e);
    Response::error($e->getMessage(), $e->getCode() ?: HttpStatus::BAD_REQUEST);
} catch (Throwable $e) {
    Logger::erro($e);
    Response::error('Erro inesperado no servidor.', HttpStatus::INTERNAL_SERVER_ERROR);
} finally {
    ob_end_flush();
}
