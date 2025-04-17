<?php

use App\Core\Router;
use App\Controller\AlunoController;
use App\Controller\AuthController;
use App\Controller\TurmaController;
use App\Controller\MatriculaController;

$router = new Router();

// Health
$router->get('/health', [\App\Controller\HealthController::class, 'health']);

// Auth
$router->post('/login', [AuthController::class, 'login']);
$router->get('/me', [AuthController::class, 'me']);

// Aluno
$router->post('/alunos', [AlunoController::class, 'criar']);
$router->get('/alunos', [AlunoController::class, 'listar']);
$router->get('/alunos/busca', [AlunoController::class, 'buscarPorNome']);
$router->get('/alunos/{id}', [AlunoController::class, 'buscar']);
$router->put('/alunos/{id}', [AlunoController::class, 'atualizar']);
$router->delete('/alunos/{id}', [AlunoController::class, 'remover']);

// Turma
$router->post('/turmas', [TurmaController::class, 'criar']);
$router->get('/turmas', [TurmaController::class, 'listar']);
$router->get('/turmas/busca', [TurmaController::class, 'buscarPorNome']);
$router->get('/turmas/{id}', [TurmaController::class, 'buscar']);
$router->put('/turmas/{id}', [TurmaController::class, 'atualizar']);
$router->delete('/turmas/{id}', [TurmaController::class, 'remover']);

// Matrícula
$router->post('/matriculas', [MatriculaController::class, 'matricular']);
$router->get('/matriculas/turma/{id}', [MatriculaController::class, 'listarPorTurma']);
$router->delete('/matriculas', [MatriculaController::class, 'remover']); // precisa do alunoId e turmaId no body

return $router;
