<?php
// Evita exibir erros diretamente na tela (boas práticas de produção)
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL);

ob_start();

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\EnvLoader;
use App\Core\HttpStatus;
use App\Core\AuthMiddleware;
use App\Core\Exceptions\HttpException;
use App\Core\Exceptions\NotFoundException;
use App\Utils\Response;
use App\Utils\Http;
use App\Utils\Logger;

// Carrega variáveis de ambiente
EnvLoader::load(__DIR__ . '/../');

// Instancia o roteador e dependências da aplicação
$router = require_once __DIR__ . '/../src/bootstrap.php';

// Registra as rotas
require_once __DIR__ . '/../src/Routes/api.php';

// Verifica a URI atual
$rotaAtual = Http::getNormalizedUri();
$rotasPublicas = ['/login', '/health', '/docs', '/coverage-report'];

// Rota para documentação
if ($rotaAtual === '/docs') {
    require_once __DIR__ . '/../public/docs/index.html';
    exit;
}

// Rota para relatório de cobertura
if ($rotaAtual === '/coverage-report') {
    require_once __DIR__ . '/../coverage-report/index.html';
    exit;
}

// Captura exceções não tratadas
set_exception_handler(function (Throwable $e) {
    Logger::erro($e);
    Response::error('Erro interno no servidor.', HttpStatus::INTERNAL_SERVER_ERROR);
});

// Captura erros fatais
register_shutdown_function(function () {
    $erro = error_get_last();

    if ($erro && in_array($erro['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        Logger::erro($erro['message'] . " em {$erro['file']}:{$erro['line']}");
        Response::error('Erro inesperado no servidor.', HttpStatus::INTERNAL_SERVER_ERROR);
        exit;
    }
});

try {
    // Aplica middleware de autenticação em rotas protegidas
    if (!in_array($rotaAtual, $rotasPublicas)) {
        AuthMiddleware::proteger(getallheaders());
    }

    // Despacha a rota
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
