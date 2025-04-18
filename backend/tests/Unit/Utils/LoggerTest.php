<?php

namespace Tests\Unit\Utils;

use PHPUnit\Framework\TestCase;
use App\Utils\Logger;

/**
 * Testes para a classe Logger.
 */
class LoggerTest extends TestCase
{
    private string $logFile;

    protected function setUp(): void
    {
        parent::setUp();

        $this->logFile = __DIR__ . '/../../../storage/logs/error.log';

        // Garante que o log esteja limpo antes de cada teste
        if (file_exists($this->logFile)) {
            unlink($this->logFile);
        }
    }

    public function testLogComString(): void
    {
        Logger::erro('Erro de teste em string');

        $conteudo = file_get_contents($this->logFile);
        $this->assertStringContainsString('Erro de teste em string', $conteudo);
        $this->assertStringContainsString(date('Y-m-d'), $conteudo);
    }

    public function testLogComExcecao(): void
    {
        $erro = new \Exception('Exceção de teste');
        Logger::erro($erro);

        $conteudo = file_get_contents($this->logFile);
        $this->assertStringContainsString('Exceção de teste', $conteudo);
        $this->assertStringContainsString('Trace:', $conteudo);
    }

    protected function tearDown(): void
    {
        // Remove o log após os testes
        if (file_exists($this->logFile)) {
            unlink($this->logFile);
        }

        parent::tearDown();
    }
}
