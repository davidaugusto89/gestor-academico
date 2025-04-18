<?php

namespace Tests\Unit\Core;

use PHPUnit\Framework\TestCase;
use App\Core\EnvLoader;

class EnvLoaderTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Limpa variável de ambiente antes do teste
        putenv('TEST_VARIABLE');
        unset($_ENV['TEST_VARIABLE'], $_SERVER['TEST_VARIABLE']);
    }

    public function testLoadEnvFromExistingEnvTestFile(): void
    {
        $envPath = __DIR__ . '/../../../';
        $envFile = '.env.test';

        $this->assertFileExists($envPath . $envFile);

        EnvLoader::load($envPath, $envFile);

        $this->assertEquals('dev', $_ENV['APP_ENV']);

        $this->assertEquals('host_teste', $_ENV['DB_HOST']);
        $this->assertEquals('3306', $_ENV['DB_PORT']);
        $this->assertEquals('db_teste', $_ENV['DB_NAME']);
        $this->assertEquals('user_teste', $_ENV['DB_USER']);
        $this->assertEquals('pass_teste', $_ENV['DB_PASS']);

        $this->assertEquals('teste', $_ENV['JWT_SECRET']);
    }
}
