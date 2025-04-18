<?php

namespace Tests\Unit\Utils;

use PHPUnit\Framework\TestCase;
use App\Utils\Http;

/**
 * Testes para normalização de URI pela classe Http.
 */
class HttpTest extends TestCase
{
    protected function tearDown(): void
    {
        unset($_SERVER['REQUEST_URI']);
    }

    public function testUriSemApiNemBarraFinal(): void
    {
        $_SERVER['REQUEST_URI'] = '/usuarios';
        $this->assertEquals('/usuarios', Http::getNormalizedUri());
    }

    public function testUriComPrefixoApi(): void
    {
        $_SERVER['REQUEST_URI'] = '/api/usuarios';
        $this->assertEquals('/usuarios', Http::getNormalizedUri());
    }

    public function testUriComApiEBarraFinal(): void
    {
        $_SERVER['REQUEST_URI'] = '/api/alunos/';
        $this->assertEquals('/alunos', Http::getNormalizedUri());
    }

    public function testUriRaizSimples(): void
    {
        $_SERVER['REQUEST_URI'] = '/';
        $this->assertEquals('/', Http::getNormalizedUri());
    }

    public function testUriApiRaiz(): void
    {
        $_SERVER['REQUEST_URI'] = '/api/';
        $this->assertEquals('/', Http::getNormalizedUri());
    }
}
