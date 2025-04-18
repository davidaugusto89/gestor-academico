<?php

namespace Tests\Unit\Support;

use PHPUnit\Framework\TestCase;
use App\Support\CpfValidator;

/**
 * Testes para validação de CPF com a classe CpfValidator.
 */
class CpfValidatorTest extends TestCase
{
    public function testCpfValido(): void
    {
        $this->assertTrue(CpfValidator::isValido('529.982.247-25')); // válido
        $this->assertTrue(CpfValidator::isValido('52998224725'));     // válido sem máscara
    }

    public function testCpfInvalidoPorFormato(): void
    {
        $this->assertFalse(CpfValidator::isValido('111.111.111-11')); // dígitos repetidos
        $this->assertFalse(CpfValidator::isValido('123.456.789-00')); // dígitos verificadores inválidos
        $this->assertFalse(CpfValidator::isValido('123456789'));      // menos de 11 dígitos
        $this->assertFalse(CpfValidator::isValido('529.982.247-2A')); // caractere inválido
    }

    public function testCpfInvalidoPorDigitoVerificador(): void
    {
        $this->assertFalse(CpfValidator::isValido('529.982.247-24')); // dígito final alterado
    }
}
