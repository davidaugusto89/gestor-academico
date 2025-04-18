<?php

use PHPUnit\Framework\TestCase;
use App\Domain\Aluno\Filter;

class AlunoFilterTest extends TestCase
{
    public function testCamposPermitidosRetornaArrayEsperado()
    {
        $esperado = ['id', 'nome'];
        $this->assertSame($esperado, Filter::camposPermitidos());
    }

    public function testCamposPermitidosNaoVazio()
    {
        $this->assertNotEmpty(Filter::camposPermitidos());
    }

    public function testCamposPermitidosContemNomeEId()
    {
        $campos = Filter::camposPermitidos();
        $this->assertContains('id', $campos);
        $this->assertContains('nome', $campos);
    }
}
