<?php

namespace Tests\Unit\Core;

use App\Core\Database;
use App\Core\Exceptions\DatabaseConnectionException;
use PDO;
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    private array $envBackup = [];

    protected function setUp(): void
    {
        parent::setUp();

        // Salva os valores originais do .env.test
        $this->envBackup = [
            'DB_HOST' => $_ENV['DB_HOST'] ?? null,
            'DB_PORT' => $_ENV['DB_PORT'] ?? null,
            'DB_NAME' => $_ENV['DB_NAME'] ?? null,
            'DB_USER' => $_ENV['DB_USER'] ?? null,
            'DB_PASS' => $_ENV['DB_PASS'] ?? null,
        ];
    }

    protected function tearDown(): void
    {
        // Restaura o .env.test original após cada teste
        foreach ($this->envBackup as $key => $value) {
            if ($value !== null) {
                $_ENV[$key] = $value;
            } else {
                unset($_ENV[$key]);
            }
        }

        parent::tearDown();
    }

    public function testConexaoValidaUsandoEnvExample(): void
    {
        // Simula valores do .env.example
        $_ENV['DB_HOST'] = '127.0.0.1';
        $_ENV['DB_PORT'] = '3306';
        $_ENV['DB_NAME'] = 'gestor_academico';
        $_ENV['DB_USER'] = 'root';
        $_ENV['DB_PASS'] = 'root'; // ou vazio se for o seu caso

        $pdo = Database::connect();
        $this->assertInstanceOf(PDO::class, $pdo);
    }

    public function testConexaoInvalidaLancaExcecao(): void
    {
        // Usa os valores originais do .env.test (que agora terão dados inválidos)
        $_ENV['DB_HOST'] = 'host_falso';

        $this->expectException(DatabaseConnectionException::class);
        Database::connect();
    }
}
