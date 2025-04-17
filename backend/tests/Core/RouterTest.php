<?php

namespace Tests\Core;

use PHPUnit\Framework\TestCase;
use App\Core\Router;

class RouterTest extends TestCase
{
    protected function setUp(): void
    {
        // Reset de URI e METHOD antes de cada teste
        $_SERVER['REQUEST_URI'] = '/';
        $_SERVER['REQUEST_METHOD'] = 'GET';
    }

    public function testRotaGetExecutaController()
    {
        $_SERVER['REQUEST_URI'] = '/hello';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $router = new Router();
        $router->get('/hello', [FakeController::class, 'hello']);

        ob_start();
        $result = $router->dispatch();
        $output = ob_get_clean();

        $this->assertTrue($result);
        $this->assertJson($output);
        $this->assertStringContainsString('Hello World', $output);
    }

    public function testRotaPostExecutaController()
    {
        $_SERVER['REQUEST_URI'] = '/create';
        $_SERVER['REQUEST_METHOD'] = 'POST';

        $router = new Router();
        $router->post('/create', [FakeController::class, 'created']);

        ob_start();
        $result = $router->dispatch();
        $output = ob_get_clean();

        $this->assertTrue($result);
        $this->assertJson($output);
        $this->assertStringContainsString('created', $output);
    }

    public function testRotaPutExecutaController()
    {
        $_SERVER['REQUEST_URI'] = '/update';
        $_SERVER['REQUEST_METHOD'] = 'PUT';

        $router = new Router();
        $router->put('/update', [FakeController::class, 'updated']);

        ob_start();
        $result = $router->dispatch();
        $output = ob_get_clean();

        $this->assertTrue($result);
        $this->assertJson($output);
        $this->assertStringContainsString('updated', $output);
    }

    public function testRotaDeleteExecutaController()
    {
        $_SERVER['REQUEST_URI'] = '/delete';
        $_SERVER['REQUEST_METHOD'] = 'DELETE';

        $router = new Router();
        $router->delete('/delete', [FakeController::class, 'deleted']);

        ob_start();
        $result = $router->dispatch();
        $output = ob_get_clean();

        $this->assertTrue($result);
        $this->assertJson($output);
        $this->assertStringContainsString('deleted', $output);
    }

    public function testRotaNaoEncontradaRetornaFalse()
    {
        $_SERVER['REQUEST_URI'] = '/naoexiste';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $router = new Router();

        ob_start();
        $result = $router->dispatch();
        ob_end_clean();

        $this->assertFalse($result);
    }

    public function testRotaComClosure()
    {
        $_SERVER['REQUEST_URI'] = '/ping';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $router = new Router();
        $router->get('/ping', [new class {
            public function __invoke()
            {
                return ['pong' => true];
            }
        }, '__invoke']);

        ob_start();
        $result = $router->dispatch();
        $output = ob_get_clean();

        $this->assertTrue($result);
        $this->assertJson($output);
        $this->assertStringContainsString('pong', $output);
    }
}