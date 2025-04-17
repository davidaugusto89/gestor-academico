<?php

use PHPUnit\Framework\TestCase;
use App\Core\AuthMiddleware;
use App\Core\TokenManager;
use App\Core\Exceptions\UnauthorizedException;

class AuthMiddlewareTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        $_ENV['JWT_SECRET'] = 'supersecret';
        TokenManager::init();
    }

    public function testTokenAusenteLancaExcecao()
    {
        $headers = []; // nenhum header enviado

        $this->expectException(UnauthorizedException::class);
        $this->expectExceptionMessage('Token de autenticação ausente');

        AuthMiddleware::proteger($headers);
    }

    public function testTokenInvalidoLancaExcecao()
    {
        $headers = [
            'Authorization' => 'Bearer token.invalido'
        ];

        $this->expectException(UnauthorizedException::class);
        $this->expectExceptionMessage('Token inválido ou expirado');

        AuthMiddleware::proteger($headers);
    }

    public function testTokenValidoPopulaRequest()
    {
        $payload = ['sub' => 1, 'email' => 'teste@email.com'];
        $token = TokenManager::gerar($payload);

        $headers = [
            'Authorization' => 'Bearer ' . $token
        ];

        AuthMiddleware::proteger($headers);

        $this->assertArrayHasKey('auth', $_REQUEST);
        $this->assertEquals(1, $_REQUEST['auth']['sub']);
        $this->assertEquals('teste@email.com', $_REQUEST['auth']['email']);
    }
}
