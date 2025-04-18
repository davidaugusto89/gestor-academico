<?php

namespace Tests\Unit\Support;

use PHPUnit\Framework\TestCase;
use App\Support\PasswordManager;

/**
 * Testes unitários para a classe PasswordManager.
 */
class PasswordManagerTest extends TestCase
{
    public function testGerarHashRetornaHashValido(): void
    {
        $senha = 'SenhaForte123!';
        $hash = PasswordManager::gerarHash($senha);

        $this->assertIsString($hash);
        $this->assertTrue(password_verify($senha, $hash));
    }

    public function testVerificarSenhaCorretaRetornaTrue(): void
    {
        $senha = 'Senha123!';
        $hash = password_hash($senha, PASSWORD_BCRYPT);

        $this->assertTrue(PasswordManager::verificar($senha, $hash));
    }

    public function testVerificarSenhaIncorretaRetornaFalse(): void
    {
        $senhaCorreta = 'Senha123!';
        $senhaErrada = 'SenhaErrada456';
        $hash = password_hash($senhaCorreta, PASSWORD_BCRYPT);

        $this->assertFalse(PasswordManager::verificar($senhaErrada, $hash));
    }

    public function testSenhaForteValidaCombinacoesValidas(): void
    {
        $this->assertTrue(PasswordManager::ehForte('Abcdef1!'));
        $this->assertTrue(PasswordManager::ehForte('A@123qwe'));
        $this->assertTrue(PasswordManager::ehForte('SenhaF0rte!'));
    }

    public function testSenhaFracaFalhaNaValidacao(): void
    {
        $this->assertFalse(PasswordManager::ehForte('12345678'));         // só números
        $this->assertFalse(PasswordManager::ehForte('abcdefgh'));         // só letras minúsculas
        $this->assertFalse(PasswordManager::ehForte('ABCDEFGH'));         // só letras maiúsculas
        $this->assertFalse(PasswordManager::ehForte('Abcdefgh'));         // sem número e símbolo
        $this->assertFalse(PasswordManager::ehForte('Abcdef1'));          // menos de 8 caracteres
        $this->assertFalse(PasswordManager::ehForte('Abcdef12'));         // sem caractere especial
    }
}
