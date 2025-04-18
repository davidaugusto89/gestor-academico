<?php

namespace Tests\Unit\Domain\Turma;

use PHPUnit\Framework\TestCase;
use App\Domain\Turma\Filter;

class TurmaFilterTest extends TestCase
{
    public function testCamposPermitidosRetornaCamposCorretos(): void
    {
        $esperado = ['id', 'nome'];
        $this->assertEquals($esperado, Filter::camposPermitidos());
    }
}
