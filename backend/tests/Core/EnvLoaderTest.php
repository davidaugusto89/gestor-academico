<?php

use PHPUnit\Framework\TestCase;
use App\Core\EnvLoader;

class EnvLoaderTest extends TestCase
{
    private string $envTestPath;

    protected function setUp(): void
    {
        $this->envTestPath = __DIR__ . '/env_test_dir';

        // Cria diretório temporário com .env isolado
        mkdir($this->envTestPath);
        file_put_contents($this->envTestPath . '/.env', "APP_ENV=test\nMY_SECRET=abc123");
    }

    protected function tearDown(): void
    {
        unlink($this->envTestPath . '/.env');
        rmdir($this->envTestPath);

        unset($_ENV['APP_ENV'], $_ENV['MY_SECRET']);
    }

    public function testVariaveisSaoCarregadas()
    {
        EnvLoader::load($this->envTestPath);

        $this->assertEquals('test', $_ENV['APP_ENV']);
        $this->assertEquals('abc123', $_ENV['MY_SECRET']);
    }
}
