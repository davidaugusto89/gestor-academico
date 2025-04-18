<?php

use PHPUnit\Framework\TestCase;
use App\Domain\Turma\Exceptions\TurmaInvalidaException;

class TurmaInvalidaExceptionTest extends TestCase
{
    public function testMensagemPadrao()
    {
        $exception = new TurmaInvalidaException();
        $this->assertSame("Turma inválida.", $exception->getMessage());
    }

    public function testMensagemCustomizada()
    {
        $exception = new TurmaInvalidaException("Nome muito curto.");
        $this->assertSame("Nome muito curto.", $exception->getMessage());
    }
}
