<?php

use PHPUnit\Framework\TestCase;
use App\Domain\Usuario\Validator;
use App\Domain\Usuario\DTO;
use App\Domain\Usuario\Exceptions\UsuarioInvalidoException;

class UsuarioValidatorTest extends TestCase
{
    public function testValidaUsuarioComDadosValidos()
    {
        $dto = DTO::fromArray([
            'nome' => 'João Silva',
            'email' => 'joao@email.com',
            'senha' => 'Senha@123',
            'papel' => 'user'
        ]);

        // Não deve lançar exceção
        Validator::validar($dto);
        $this->assertTrue(true);
    }

    public function testNaoValidaNomeMenorQueTresCaracteres()
    {
        $this->expectException(UsuarioInvalidoException::class);
        $this->expectExceptionMessage('Nome deve ter ao menos 3 caracteres.');

        $dto = DTO::fromArray([
            'nome' => 'Jo', // Nome com menos de 3 caracteres
            'email' => 'joao@email.com',
            'senha' => 'Senha@123',
            'papel' => 'user'
        ]);

        Validator::validar($dto);
    }

    public function testNaoValidaEmailInvalido()
    {
        $this->expectException(UsuarioInvalidoException::class);
        $this->expectExceptionMessage('E-mail inválido.');

        $dto = DTO::fromArray([
            'nome' => 'João Silva',
            'email' => 'joaoemail.com', // E-mail inválido
            'senha' => 'Senha@123',
            'papel' => 'user'
        ]);

        Validator::validar($dto);
    }

    public function testNaoValidaSenhaFraca()
    {
        $this->expectException(UsuarioInvalidoException::class);
        $this->expectExceptionMessage('Senha fraca. Use letras maiúsculas, minúsculas, números e símbolos.');

        $dto = DTO::fromArray([
            'nome' => 'João Silva',
            'email' => 'joao@email.com',
            'senha' => 'senha', // Senha fraca
            'papel' => 'user'
        ]);

        Validator::validar($dto);
    }

    public function testValidaSenhaForte()
    {
        $dto = DTO::fromArray([
            'nome' => 'João Silva',
            'email' => 'joao@email.com',
            'senha' => 'Senha@123',  // Senha forte
            'papel' => 'user'
        ]);

        // Não deve lançar exceção
        Validator::validar($dto);
        $this->assertTrue(true);
    }

    public function testNaoValidaPapelInvalido()
    {
        $this->expectException(UsuarioInvalidoException::class);
        $this->expectExceptionMessage('Papel inválido.');

        $dto = DTO::fromArray([
            'nome' => 'João Silva',
            'email' => 'joao@email.com',
            'senha' => 'Senha@123',
            'papel' => 'adminstrator'  // Papel inválido
        ]);

        Validator::validar($dto);
    }

    public function testValidaPapelValido()
    {
        $dto = DTO::fromArray([
            'nome' => 'João Silva',
            'email' => 'joao@email.com',
            'senha' => 'Senha@123',
            'papel' => 'admin'  // Papel válido
        ]);

        // Não deve lançar exceção
        Validator::validar($dto);
        $this->assertTrue(true);
    }
}
