<?php

use PHPUnit\Framework\TestCase;
use App\Core\BaseController;
use App\Core\Exceptions\UnauthorizedException;
use App\Domain\Usuario\Entity;
use App\Domain\Usuario\Repository;

class BaseControllerTest extends TestCase
{
    private function mockHeaders(string $token = null): void
    {
        // Define headers diretamente simulando getallheaders()
        // Você pode usar o override global para `getallheaders()` se necessário, ou mockar via $_SERVER
        if (!function_exists('getallheaders')) {
            function getallheaders(): array
            {
                return $_SERVER['__MOCK_HEADERS'] ?? [];
            }
        }

        $_SERVER['__MOCK_HEADERS'] = [
            'Authorization' => $token ? 'Bearer ' . $token : ''
        ];
    }

    public function testUsuarioAutenticadoUsuarioNaoEncontrado()
    {
        $this->mockHeaders('token_valido');

        $mockTokenManager = $this->createMock(\App\Core\TokenManager::class);
        $mockTokenManager->method('validar')->willReturn(['id' => 99]);

        $mockRepo = $this->createMock(Repository::class);
        $mockRepo->method('buscarPorId')->with(99)->willReturn(null);

        $controller = new BaseController($mockTokenManager, $mockRepo);

        $this->expectException(UnauthorizedException::class);
        $this->expectExceptionMessage('Token ausente ou inválido.');

        $reflection = new ReflectionClass($controller);
        $method = $reflection->getMethod('usuarioAutenticado');
        $method->setAccessible(true);
        $method->invoke($controller);
    }
}
