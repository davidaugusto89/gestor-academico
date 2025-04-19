<?php

use PHPUnit\Framework\TestCase;
use App\Core\TokenManager;

class TokenManagerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $_ENV['JWT_SECRET'] = 'test_secret';
    }

    public function testGerarRetornaTokenValido(): void
    {
        $payload = ['id' => 123, 'nome' => 'Fulano'];
        $tokenManager = new TokenManager();

        $token = $tokenManager->gerar($payload, 3600); // 1h

        $this->assertIsString($token, 'O token gerado deve ser uma string JWT');

        // Valida se o token pode ser decodificado
        $dados = TokenManager::validar($token);

        $this->assertIsArray($dados);
        $this->assertArrayHasKey('id', $dados);
        $this->assertEquals(123, $dados['id']);
        $this->assertArrayHasKey('nome', $dados);
        $this->assertEquals('Fulano', $dados['nome']);
        $this->assertArrayHasKey('iat', $dados);
        $this->assertArrayHasKey('exp', $dados);
        $this->assertGreaterThan($dados['iat'], $dados['exp']);
    }

    public function testGerarLancaExcecaoSeJwtSecretNaoDefinido(): void
    {
        unset($_ENV['JWT_SECRET']);
        putenv('JWT_SECRET'); // limpa fallback getenv também

        // limpa o cache de TokenManager::$secret
        $reflection = new ReflectionClass(\App\Core\TokenManager::class);
        $property = $reflection->getProperty('secret');
        $property->setAccessible(true);
        $property->setValue(null, null);

        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Chave secreta não configurada');

        $tokenManager = new TokenManager();
        $tokenManager->gerar(['id' => 1]);
    }
}
