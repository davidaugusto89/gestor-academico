<?php

namespace App\Core;

use App\Controller\AlunoController;
use App\Controller\AuthController;
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

/**
 * Fábrica de controllers responsável por instanciar os controladores com suas dependências.
 */
class ControllerFactory
{
    /**
     * Cria e retorna uma instância do controller especificado.
     *
     * @param string $controllerClass Nome totalmente qualificado da classe do controller.
     * @return object Instância do controller.
     *
     * @throws \RuntimeException Se o controller não estiver registrado.
     */
    public static function make(string $controllerClass): object
    {
        return match ($controllerClass) {
            AlunoController::class => new AlunoController(
                new AlunoService(
                    new AlunoRepository(Database::connect())
                )
            ),

            TurmaController::class => new TurmaController(
                new TurmaService(
                    new TurmaRepository(Database::connect()),
                    new TurmaValidator()
                )
            ),

            MatriculaController::class => new MatriculaController(
                new MatriculaService(
                    new MatriculaRepository(Database::connect())
                )
            ),

            AuthController::class => new AuthController(
                new UsuarioService(
                    new UsuarioRepository(Database::connect()),
                    new UsuarioValidator()
                )
            ),

            HealthController::class => new HealthController(),

            UsuarioController::class => new UsuarioController(
                new UsuarioService(
                    new UsuarioRepository(Database::connect()),
                    new UsuarioValidator()
                )
            ),

            default => throw new \RuntimeException("Controller não registrado: $controllerClass")
        };
    }
}
