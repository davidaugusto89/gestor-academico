<?php

use PHPUnit\Framework\TestCase;
use App\Domain\Matricula\Filter;

class MatriculaFilterTest extends TestCase
{
    public function testCamposPermitidosRetornaArrayEsperado()
    {
        $esperado = ['aluno_id', 'turma_id', 'data_matricula'];
        $this->assertSame($esperado, Filter::camposPermitidos());
    }

    public function testCamposPermitidosContemCamposBasicos()
    {
        $campos = Filter::camposPermitidos();
        $this->assertContains('aluno_id', $campos);
        $this->assertContains('turma_id', $campos);
        $this->assertContains('data_matricula', $campos);
    }
}
