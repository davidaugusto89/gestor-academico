<?php

use App\Core\ControllerFactory;
use App\Core\Database;
use App\Utils\Response;
use App\Core\Router;
use App\Core\TokenManager;

// Autoload (caso não seja feito no index.php)
require_once __DIR__ . '/../vendor/autoload.php';

// Carregar variáveis de ambiente, se ainda não estiver sendo feito
App\Core\EnvLoader::load(__DIR__ . '/../');

// Conexão com o banco
$pdo = Database::connect();

// Dependências compartilhadas
$response = new Response();
$tokenManager = new TokenManager();

// Controller factory
$controllerFactory = new ControllerFactory($pdo, $response, $tokenManager);

// Router com todas as dependências resolvidas
return new Router($controllerFactory);
