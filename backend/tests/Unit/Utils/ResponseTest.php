<?php

namespace Tests\Unit\Utils;

use PHPUnit\Framework\TestCase;
use App\Utils\Response;
use App\Core\HttpStatus;

class ResponseTest extends TestCase
{
    protected function setUp(): void
    {
        // Ativa modo de teste para evitar exit()
        Response::ativarModoTeste();
    }

    protected function tearDown(): void
    {
        // Desativa modo de teste
        Response::desativarModoTeste();
    }

    public function testJsonDefaultStatusAndContent(): void
    {
        $data = ['foo' => 'bar'];
        $expected = json_encode(
            $data,
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
        );

        ob_start();
        (new Response())->json($data);
        $output = ob_get_clean();

        $this->assertSame($expected, $output);
        $this->assertSame(HttpStatus::OK, Response::getStatus());
    }

    public function testJsonCustomStatus(): void
    {
        $data = ['a' => 1];
        $status = HttpStatus::CREATED;
        $expected = json_encode(
            $data,
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
        );

        ob_start();
        (new Response())->json($data, $status);
        $output = ob_get_clean();

        $this->assertSame($expected, $output);
        $this->assertSame($status, Response::getStatus());
    }

    public function testNoContent(): void
    {
        ob_start();
        Response::noContent();
        $output = ob_get_clean();

        $this->assertSame('', $output);
        $this->assertSame(HttpStatus::NO_CONTENT, Response::getStatus());
    }

    public function testErrorDefaultStatusAndContent(): void
    {
        $message = 'Bad request';
        $expected = json_encode(['error' => $message], JSON_UNESCAPED_UNICODE);

        ob_start();
        Response::error($message);
        $output = ob_get_clean();

        $this->assertSame($expected, $output);
        $this->assertSame(HttpStatus::BAD_REQUEST, Response::getStatus());
    }

    public function testErrorCustomStatusAndContent(): void
    {
        $message = 'Unauthorized';
        $status = HttpStatus::UNAUTHORIZED;
        $expected = json_encode(['error' => $message], JSON_UNESCAPED_UNICODE);

        ob_start();
        Response::error($message, $status);
        $output = ob_get_clean();

        $this->assertSame($expected, $output);
        $this->assertSame($status, Response::getStatus());
    }
}
