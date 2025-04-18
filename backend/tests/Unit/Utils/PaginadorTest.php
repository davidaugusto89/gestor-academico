<?php

namespace Tests\Unit\Utils;

use PHPUnit\Framework\TestCase;
use App\Utils\Paginador;

/**
 * Testes para a classe Paginador.
 */
class PaginadorTest extends TestCase
{
    public function testExtrairParametrosPadrao(): void
    {
        $params = [];
        $resultado = Paginador::extrairParametros($params);

        $this->assertEquals(1, $resultado['page']);
        $this->assertEquals(10, $resultado['itemsPerPage']);
        $this->assertEquals(0, $resultado['offset']);
    }

    public function testExtrairParametrosComDados(): void
    {
        $params = [
            'page' => 2,
            'itemsPerPage' => 5,
            'nome' => 'João',
            'ativo' => true
        ];

        $resultado = Paginador::extrairParametros($params);

        $this->assertEquals(2, $resultado['page']);
        $this->assertEquals(5, $resultado['itemsPerPage']);
        $this->assertEquals(5, $resultado['offset']);
        $this->assertEquals('João', $resultado['nome']);
        $this->assertSame(true, $resultado['ativo']);
    }

    public function testPageZeroOuNegativa(): void
    {
        $resultado = Paginador::extrairParametros(['page' => 0]);
        $this->assertEquals(1, $resultado['page']);

        $resultado = Paginador::extrairParametros(['page' => -5]);
        $this->assertEquals(1, $resultado['page']);
    }

    public function testFormatarResultado(): void
    {
        $dados = [['id' => 1], ['id' => 2]];
        $total = 10;

        $resultado = Paginador::formatarResultado($dados, $total);

        $this->assertEquals($dados, $resultado['data']);
        $this->assertEquals(10, $resultado['total']);
    }
}
