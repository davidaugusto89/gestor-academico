<?php

use PHPUnit\Framework\TestCase;
use App\Core\ControllerFactory;
use App\Utils\Response;
use App\Core\TokenManager;
use App\Controller\AlunoController;
use App\Controller\TurmaController;
use App\Controller\MatriculaController;
use App\Controller\AuthController;
use App\Controller\HealthController;
use App\Controller\UsuarioController;
use App\Controller\ExampleController;

class ControllerFactoryTest extends TestCase
{
    private ControllerFactory $factory;

    protected function setUp(): void
    {
        $pdo = new PDO('sqlite::memory:');
        $response = new Response();
        $tokenManager = new TokenManager();

        $this->factory = new ControllerFactory($pdo, $response, $tokenManager);
    }

    public function testAlunoControllerInstanciado(): void
    {
        $controller = $this->factory->make(AlunoController::class);
        $this->assertInstanceOf(AlunoController::class, $controller);
    }

    public function testTurmaControllerInstanciado(): void
    {
        $controller = $this->factory->make(TurmaController::class);
        $this->assertInstanceOf(TurmaController::class, $controller);
    }

    public function testMatriculaControllerInstanciado(): void
    {
        $controller = $this->factory->make(MatriculaController::class);
        $this->assertInstanceOf(MatriculaController::class, $controller);
    }

    public function testAuthControllerInstanciado(): void
    {
        $controller = $this->factory->make(AuthController::class);
        $this->assertInstanceOf(AuthController::class, $controller);
    }

    public function testUsuarioControllerInstanciado(): void
    {
        $controller = $this->factory->make(UsuarioController::class);
        $this->assertInstanceOf(UsuarioController::class, $controller);
    }

    public function testExampleControllerInstanciado(): void
    {
        $controller = $this->factory->make(ExampleController::class);
        $this->assertInstanceOf(ExampleController::class, $controller);
    }

    public function testControllerNaoRegistradoLancaExcecao(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Controller não registrado: App\Controller\FakeController');

        $this->factory->make('App\Controller\FakeController');
    }
}
