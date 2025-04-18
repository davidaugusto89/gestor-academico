<?php

use PHPUnit\Framework\TestCase;
use App\Domain\Turma\Exceptions\TurmaJaExisteException;

class TurmaJaExisteExceptionTest extends TestCase
{
    public function testMensagemPadrao()
    {
        $exception = new TurmaJaExisteException();
        $this->assertSame("Turma já existe.", $exception->getMessage());
    }

    public function testMensagemCustomizada()
    {
        $exception = new TurmaJaExisteException("Turma 'Back-end' já cadastrada.");
        $this->assertSame("Turma 'Back-end' já cadastrada.", $exception->getMessage());
    }
}
