<?php

namespace Tests\Unit\Core;

use PHPUnit\Framework\TestCase;
use App\Core\EnvLoader;
use App\Core\TokenManager;

class TokenManagerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Limpa qualquer JWT_SECRET anterior
        putenv('JWT_SECRET');
        unset($_ENV['JWT_SECRET'], $_SERVER['JWT_SECRET']);

        // Carrega o .env.test que deve conter JWT_SECRET
        $envPath = __DIR__ . '/../../../';
        EnvLoader::load($envPath, '.env.test');
    }

    public function testGerarERetornarTokenValido(): void
    {
        $payload = ['user_id' => 123];

        $token = TokenManager::gerar($payload);
        $this->assertIsString($token);

        $decoded = TokenManager::validar($token);
        $this->assertIsArray($decoded);
        $this->assertEquals(123, $decoded['user_id']);
        $this->assertArrayHasKey('iat', $decoded);
        $this->assertArrayHasKey('exp', $decoded);
    }

    public function testTokenInvalidoRetornaNull(): void
    {
        $tokenInvalido = 'invalid.token.structure';
        $decoded = TokenManager::validar($tokenInvalido);
        $this->assertNull($decoded);
    }
}
