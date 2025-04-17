<?php

use PHPUnit\Framework\TestCase;
use App\Support\PasswordManager;

class PasswordManagerTest extends TestCase
{
    public function testSenhaForte()
    {
        $this->assertTrue(PasswordManager::ehForte('SenhaForte123!'));
        $this->assertTrue(PasswordManager::ehForte('A1b2c3d4$'));
    }

    public function testSenhaFraca()
    {
        $this->assertFalse(PasswordManager::ehForte('123456'));
        $this->assertFalse(PasswordManager::ehForte('senha'));
        $this->assertFalse(PasswordManager::ehForte('Senha123')); // sem símbolo
        $this->assertFalse(PasswordManager::ehForte('Senha!'));   // muito curta
    }

    public function testGerarHash()
    {
        $hash = PasswordManager::gerarHash('MinhaSenha123!');
        $this->assertNotEmpty($hash);
        $this->assertTrue(password_verify('MinhaSenha123!', $hash));
    }

    public function testVerificarSenha()
    {
        $senha = 'Teste123!';
        $hash = password_hash($senha, PASSWORD_BCRYPT);

        $this->assertTrue(PasswordManager::verificar($senha, $hash));
        $this->assertFalse(PasswordManager::verificar('Errado123!', $hash));
    }
}
