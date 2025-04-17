<?php

use PHPUnit\Framework\TestCase;
use App\Support\CpfValidator;

class CpfValidatorTest extends TestCase
{
    public function testCpfValido()
    {
        $this->assertTrue(CpfValidator::isValido('529.982.247-25'));
        $this->assertTrue(CpfValidator::isValido('11144477735')); // sem pontuação
    }

    public function testCpfInvalidoComDiferentesNumeros()
    {
        $this->assertFalse(CpfValidator::isValido('123.456.789-00'));
        $this->assertFalse(CpfValidator::isValido('111.111.111-11'));
        $this->assertFalse(CpfValidator::isValido('00000000000'));
    }

    public function testCpfComTamanhoErrado()
    {
        $this->assertFalse(CpfValidator::isValido('1234567890'));    // 10 dígitos
        $this->assertFalse(CpfValidator::isValido('123456789012'));  // 12 dígitos
    }

    public function testCpfVazio()
    {
        $this->assertFalse(CpfValidator::isValido(''));
    }
}
