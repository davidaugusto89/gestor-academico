<?php

use PHPUnit\Framework\TestCase;
use App\Core\Database;
use App\Core\Exceptions\DatabaseConnectionException;
use PDO;

class DatabaseTest extends TestCase
{
    protected function setUp(): void
    {
        // Dados inválidos para simular erro de conexão
        $_ENV['DB_HOST'] = '127.0.0.1';
        $_ENV['DB_PORT'] = '3306';
        $_ENV['DB_NAME'] = 'banco_invalido';
        $_ENV['DB_USER'] = 'usuario_falso';
        $_ENV['DB_PASS'] = 'senha_errada';
    }

    public function testErroConexaoDisparaExcecao()
    {
        $this->expectException(DatabaseConnectionException::class);
        $this->expectExceptionMessage('Erro ao conectar ao banco de dados.');

        Database::connect();
    }

    public function testErroConexaoTemPdoExceptionComoAnterior()
    {
        try {
            Database::connect();
        } catch (DatabaseConnectionException $e) {
            $this->assertInstanceOf(PDOException::class, $e->getPrevious());
            $this->assertStringContainsString('could not find driver', $e->getPrevious()->getMessage());
        }
    }

    /**
     * Teste opcional para simular conexão válida
     * Só execute se tiver um banco MySQL real com credenciais corretas
     */
    public function testConexaoValidaRetornaPdo()
    {
        // Defina credenciais válidas para testar conexão real
        $_ENV['DB_HOST'] = '127.0.0.1';
        $_ENV['DB_PORT'] = '3306';
        $_ENV['DB_NAME'] = 'gestor_academico';
        $_ENV['DB_USER'] = 'root';
        $_ENV['DB_PASS'] = '';

        try {
            $pdo = Database::connect();
            $this->assertInstanceOf(PDO::class, $pdo);
        } catch (DatabaseConnectionException $e) {
            $this->markTestSkipped('Banco de dados não disponível para teste de conexão real.');
        }
    }
}
