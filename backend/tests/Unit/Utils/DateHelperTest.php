<?php

namespace Tests\Unit\Utils;

use PHPUnit\Framework\TestCase;
use App\Utils\DateHelper;

/**
 * Testes para conversão de datas com a classe DateHelper.
 */
class DateHelperTest extends TestCase
{
    public function testConversaoDataValida(): void
    {
        $this->assertEquals('2024-12-31', DateHelper::paraFormatoBanco('31/12/2024'));
        $this->assertEquals('2000-01-01', DateHelper::paraFormatoBanco('01/01/2000'));
    }

    public function testRetornoOriginalSeDataInvalida(): void
    {
        $this->assertEquals('2024-12-31', DateHelper::paraFormatoBanco('2024-12-31')); // já no formato do banco
        $this->assertEquals('31-12-2024', DateHelper::paraFormatoBanco('31-12-2024')); // delimitador inválido
        $this->assertEquals('data qualquer', DateHelper::paraFormatoBanco('data qualquer')); // não é data
    }
}
