<?php

namespace Tests\Unit\Utils;

use PHPUnit\Framework\TestCase;
use App\Utils\Normalizer;

/**
 * Testes para a classe Normalizer.
 */
class NormalizerTest extends TestCase
{
    public function testCpfRemoveMascara(): void
    {
        $this->assertEquals('12345678900', Normalizer::cpf('123.456.789-00'));
        $this->assertEquals('98765432100', Normalizer::cpf('98765432100'));
        $this->assertEquals('00000000000', Normalizer::cpf('000.000.000-00'));
    }

    public function testDataFormatoBrasileiroParaIso(): void
    {
        $this->assertEquals('2024-12-31', Normalizer::data('31/12/2024'));
        $this->assertEquals('2000-01-01', Normalizer::data('01/01/2000'));
    }

    public function testDataInvalidaRetornaOriginal(): void
    {
        $this->assertEquals('2024-12-31', Normalizer::data('2024-12-31')); // já formatada
        $this->assertEquals('31-12-2024', Normalizer::data('31-12-2024')); // delimitador incorreto
        $this->assertEquals('texto', Normalizer::data('texto')); // texto aleatório
    }
}
