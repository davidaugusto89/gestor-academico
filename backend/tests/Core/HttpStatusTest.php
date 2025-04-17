<?php

use PHPUnit\Framework\TestCase;
use App\Core\HttpStatus;

class HttpStatusTest extends TestCase
{
    public function testStatusCodesBasicos()
    {
        $this->assertSame(200, HttpStatus::OK);
        $this->assertSame(201, HttpStatus::CREATED);
        $this->assertSame(204, HttpStatus::NO_CONTENT);
    }

    public function testStatusDeErroCliente()
    {
        $this->assertSame(400, HttpStatus::BAD_REQUEST);
        $this->assertSame(401, HttpStatus::UNAUTHORIZED);
        $this->assertSame(403, HttpStatus::FORBIDDEN);
        $this->assertSame(404, HttpStatus::NOT_FOUND);
        $this->assertSame(405, HttpStatus::METHOD_NOT_ALLOWED);
        $this->assertSame(409, HttpStatus::CONFLICT);
    }

    public function testStatusDeErroServidor()
    {
        $this->assertSame(500, HttpStatus::INTERNAL_SERVER_ERROR);
        $this->assertSame(503, HttpStatus::SERVICE_UNAVAILABLE);
    }
}
