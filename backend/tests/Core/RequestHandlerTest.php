<?php

use PHPUnit\Framework\TestCase;
use App\Core\RequestHandler;
use App\Core\Router;
use App\Core\Exceptions\BadRequestException;
use App\Core\Exceptions\NotFoundException;

class RequestHandlerTest extends TestCase
{
    protected function setUp(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        unset($_SERVER['CONTENT_TYPE']);
    }

    public function testPostSemJsonDisparaExcecao()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['CONTENT_TYPE'] = 'text/plain';

        $router = $this->createMock(Router::class);
        $router->method('dispatch')->willReturn(true);

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('O corpo da requisição deve ser application/json');

        RequestHandler::handle($router);
    }

    public function testRotaNaoEncontradaDisparaExcecao()
    {
        $router = $this->createMock(Router::class);
        $router->method('dispatch')->willReturn(false);

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('Rota não encontrada');

        RequestHandler::handle($router);
    }

    public function testRotaValidaNaoDisparaErro()
    {
        $router = $this->createMock(Router::class);
        $router->method('dispatch')->willReturn(true);

        $this->expectNotToPerformAssertions();

        RequestHandler::handle($router);
    }
}
