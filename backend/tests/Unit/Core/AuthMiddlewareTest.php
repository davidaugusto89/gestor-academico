<?php

namespace Tests\Unit\Core;

use PHPUnit\Framework\TestCase;
use App\Core\AuthMiddleware;
use App\Core\Exceptions\UnauthorizedException;

class AuthMiddlewareTest extends TestCase
{
    protected function tearDown(): void
    {
        unset($_REQUEST['auth'], $_SERVER['__MOCK_TOKEN_RETURN']);
    }

    public function testTokenAusenteLancaExcecao()
    {
        $this->expectException(UnauthorizedException::class);
        $this->expectExceptionMessage('Token de autenticação ausente');

        AuthMiddleware::proteger([]);
    }

    public function testTokenInvalidoLancaExcecao()
    {
        $_SERVER['__MOCK_TOKEN_RETURN'] = null;

        $this->expectException(UnauthorizedException::class);
        $this->expectExceptionMessage('Token inválido ou expirado');

        AuthMiddleware::proteger([
            'Authorization' => 'Bearer token_invalido'
        ]);
    }
}
