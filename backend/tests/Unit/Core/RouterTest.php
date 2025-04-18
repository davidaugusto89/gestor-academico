<?php

namespace Tests\Unit\Core;

use PHPUnit\Framework\TestCase;
use App\Core\Router;
use App\Controller\ExampleController;

class RouterTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET = [];
        $_POST = [];

        // Registra uma classe fake para simular o controller
        if (!class_exists(ExampleController::class)) {
            eval('
                namespace App\Controllers;
                class ExampleController {
                    public static string $executadoCom = "";

                    public function show($id, $query = []) {
                        self::$executadoCom = "show: $id / query: " . json_encode($query);
                    }
                }
            ');
        }
    }

    public function testGetRouteDispatchesCorrectly(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/usuarios/42';

        $router = new Router();
        $router->get('/usuarios/{id}', [ExampleController::class, 'show']);

        $executado = $router->dispatch();

        $this->assertTrue($executado);
        $this->assertEquals('show: 42 / query: []', ExampleController::$executadoCom);
    }

    public function testReturnsFalseForUnmatchedRoute(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/nao-existe';

        $router = new Router();
        $router->get('/usuarios/{id}', [ExampleController::class, 'show']);

        $executado = $router->dispatch();

        $this->assertFalse($executado);
    }
}
