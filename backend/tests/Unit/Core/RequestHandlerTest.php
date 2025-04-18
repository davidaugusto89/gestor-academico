<?php

namespace Tests\Unit\Core;

use App\Core\RequestHandler;
use App\Core\Router;
use App\Core\Exceptions\BadRequestException;
use App\Core\Exceptions\NotFoundException;
use PHPUnit\Framework\TestCase;

class RequestHandlerTest extends TestCase
{
    protected function setUp(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        unset($_SERVER['CONTENT_TYPE']);
    }

    public function testHandleThrowsBadRequestOnInvalidJsonContentType(): void
    {
        $this->expectException(BadRequestException::class);

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['CONTENT_TYPE'] = 'text/plain';

        $router = $this->createMock(Router::class);
        $router->method('dispatch')->willReturn(true);

        RequestHandler::handle($router);
    }

    public function testHandleThrowsNotFoundWhenRouteIsNotFound(): void
    {
        $this->expectException(NotFoundException::class);

        $_SERVER['REQUEST_METHOD'] = 'GET';

        $router = $this->createMock(Router::class);
        $router->method('dispatch')->willReturn(false);

        RequestHandler::handle($router);
    }

    public function testHandleRunsSuccessfullyWhenRouteIsFound(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $router = $this->createMock(Router::class);
        $router->method('dispatch')->willReturn(true);

        // Espera que não lance nenhuma exceção
        $this->expectNotToPerformAssertions();

        RequestHandler::handle($router);
    }
}
