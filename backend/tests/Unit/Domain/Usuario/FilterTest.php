<?php

namespace Tests\Unit\Domain\Usuario;

use PHPUnit\Framework\TestCase;
use App\Domain\Usuario\Filter;

class UsuarioFilterTest extends TestCase
{
    public function testCamposPermitidosRetornaCamposCorretos(): void
    {
        $esperado = ['id', 'nome'];
        $this->assertEquals($esperado, Filter::camposPermitidos());
    }
}
