<?php

use PHPUnit\Framework\TestCase;
use App\Core\TokenManager;

class TokenManagerTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        $_ENV['JWT_SECRET'] = '123abc';
        TokenManager::init();
    }

    public function testTokenGeradoPodeSerValidado()
    {
        $payload = ['sub' => 1, 'email' => 'user@email.com'];
        $token = TokenManager::gerar($payload, 3600);
        $dados = TokenManager::validar($token);

        $this->assertEquals($payload['sub'], $dados['sub']);
        $this->assertEquals($payload['email'], $dados['email']);
    }

    public function testTokenInvalidoRetornaNull()
    {
        $invalido = 'abc.def.ghi';
        $this->assertNull(TokenManager::validar($invalido));
    }
}
