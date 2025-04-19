<?php

namespace Tests\Unit\Core;

use PHPUnit\Framework\TestCase;
use App\Core\Router;
use Tests\Fake\ControllerFactoryStub;
use Tests\Fake\FakeController;

class RouterTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Define valores padrão para variáveis de ambiente simuladas
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/';
        $_GET = [];
        $_POST = [];
    }

    public function testGetRouteDispatchesCorrectly(): void
    {
        $factoryStub = new ControllerFactoryStub();
        $router = new Router($factoryStub);

        // Registra rota
        $router->get('/teste', [FakeController::class, 'index']);

        // Simula requisição
        $_SERVER['REQUEST_URI'] = '/teste';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        ob_start();
        $result = $router->dispatch();
        $output = ob_get_clean();

        $this->assertTrue($result);
        $this->assertEquals('ok', $output);
    }

    public function testReturnsFalseForUnmatchedRoute(): void
    {
        $factoryStub = new ControllerFactoryStub();
        $router = new Router($factoryStub);

        // Simula requisição inválida
        $_SERVER['REQUEST_URI'] = '/rota-que-nao-existe';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $result = $router->dispatch();

        $this->assertFalse($result);
    }
}
