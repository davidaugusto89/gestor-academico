<?php

namespace App\Core;

use App\Controller\AlunoController;
use App\Controller\AuthController;
use App\Controller\ExampleController;
use App\Controller\TurmaController;
use App\Controller\MatriculaController;
use App\Controller\HealthController;
use App\Controller\UsuarioController;

use App\Domain\Aluno\RepositoryImpl as AlunoRepository;
use App\Domain\Aluno\Service as AlunoService;

use App\Domain\Turma\RepositoryImpl as TurmaRepository;
use App\Domain\Turma\Service as TurmaService;
use App\Domain\Turma\Validator as TurmaValidator;

use App\Domain\Matricula\RepositoryImpl as MatriculaRepository;
use App\Domain\Matricula\Service as MatriculaService;

use App\Domain\Usuario\RepositoryImpl as UsuarioRepository;
use App\Domain\Usuario\Service as UsuarioService;
use App\Domain\Usuario\Validator as UsuarioValidator;

use App\Utils\Response;
use App\Core\TokenManager;

/**
 * Fábrica de controllers responsável por instanciar os controladores com suas dependências.
 */
class ControllerFactory
{
    /**
     * ControllerFactory constructor.
     *
     * @param \PDO $pdo Conexão com o banco de dados.
     * @param Response $response Instância responsável por retornar respostas HTTP.
     * @param TokenManager $tokenManager Gerenciador de tokens de autenticação.
     */
    public function __construct(
        private readonly \PDO $pdo,
        private readonly Response $response,
        private readonly TokenManager $tokenManager
    ) {}

    /**
     * Cria e retorna uma instância do controller especificado.
     *
     * @param string $controllerClass Nome totalmente qualificado da classe do controller.
     * @return object Instância do controller.
     *
     * @throws \RuntimeException Se o controller não estiver registrado.
     */
    public function make(string $controllerClass): object
    {
        return match ($controllerClass) {
            AlunoController::class => new AlunoController(
                new AlunoService(
                    new AlunoRepository($this->pdo)
                ),
                $this->response
            ),

            TurmaController::class => new TurmaController(
                new TurmaService(
                    new TurmaRepository($this->pdo),
                    new TurmaValidator()
                ),
                $this->response
            ),

            MatriculaController::class => new MatriculaController(
                new MatriculaService(
                    new MatriculaRepository($this->pdo)
                ),
                $this->response
            ),

            AuthController::class => new AuthController(
                new UsuarioService(
                    new UsuarioRepository($this->pdo),
                    new UsuarioValidator()
                ),
                $this->tokenManager,
                $this->response
            ),

            HealthController::class => new HealthController(
                $this->response
            ),

            UsuarioController::class => new UsuarioController(
                new UsuarioService(
                    new UsuarioRepository($this->pdo),
                    new UsuarioValidator()
                ),
                $this->response
            ),

            ExampleController::class => new ExampleController(),

            default => throw new \RuntimeException("Controller não registrado: $controllerClass")
        };
    }
}
