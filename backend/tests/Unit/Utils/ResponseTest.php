<?php

namespace Tests\Unit\Utils;

use PHPUnit\Framework\TestCase;
use App\Utils\Response;

class ResponseTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Response::ativarModoTeste();
    }

    protected function tearDown(): void
    {
        Response::desativarModoTeste();
        parent::tearDown();
    }

    public function testJsonRetornaDadosComStatus(): void
    {
        ob_start();
        Response::json(['mensagem' => 'ok'], 201);
        $output = ob_get_clean();

        $this->assertJson($output);
        $this->assertStringContainsString('"mensagem": "ok"', $output);
        $this->assertEquals(201, Response::getStatus());
    }

    public function testErrorRetornaMensagemDeErro(): void
    {
        ob_start();
        Response::error('Algo deu errado', 422);
        $output = ob_get_clean();

        $this->assertJson($output);
        $this->assertStringContainsString('"error":"Algo deu errado"', $output);
        $this->assertEquals(422, Response::getStatus());
    }

    public function testNoContentRetornaStatus204(): void
    {
        ob_start();
        Response::noContent();
        ob_end_clean();

        $this->assertEquals(204, Response::getStatus());
    }
}
