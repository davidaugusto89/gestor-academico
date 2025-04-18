<?php

use PHPUnit\Framework\TestCase;
use App\Domain\Aluno\Validator;
use App\Domain\Aluno\DTO;
use App\Domain\Aluno\Exceptions\AlunoInvalidoException;

class AlunoValidatorTest extends TestCase
{
    public function testValidaAlunoComDadosCorretosParaCriacao()
    {
        $dto = DTO::fromArray([
            'nome' => 'João da Silva',
            'nascimento' => '2000-01-01',
            'cpf' => '12345678909', // CPF válido
            'email' => 'joao@email.com',
            'senha' => 'Senha@123'
        ]);

        Validator::validar($dto, null);

        $this->assertTrue(true);
    }

    public function testLancaExcecaoSeNomeForCurto()
    {
        $this->expectException(AlunoInvalidoException::class);
        $this->expectExceptionMessage('Nome deve ter ao menos 3 caracteres');

        $dto = DTO::fromArray([
            'nome' => 'Jo',
            'nascimento' => '2000-01-01',
            'cpf' => '12345678909',
            'email' => 'joao@email.com',
            'senha' => 'Senha@123'
        ]);

        Validator::validar($dto, null);
    }

    public function testLancaExcecaoSeEmailInvalido()
    {
        $this->expectException(AlunoInvalidoException::class);
        $this->expectExceptionMessage('E-mail inválido');

        $dto = DTO::fromArray([
            'nome' => 'João',
            'nascimento' => '2000-01-01',
            'cpf' => '12345678909',
            'email' => 'email_invalido',
            'senha' => 'Senha@123'
        ]);

        Validator::validar($dto, null);
    }

    public function testLancaExcecaoSeCpfInvalido()
    {
        $this->expectException(AlunoInvalidoException::class);
        $this->expectExceptionMessage('CPF inválido');

        $dto = DTO::fromArray([
            'nome' => 'João',
            'nascimento' => '2000-01-01',
            'cpf' => '12345678900', // inválido
            'email' => 'joao@email.com',
            'senha' => 'Senha@123'
        ]);

        Validator::validar($dto, null);
    }

    public function testLancaExcecaoSeSenhaForFracaNaCriacao()
    {
        $this->expectException(AlunoInvalidoException::class);
        $this->expectExceptionMessage('Senha fraca');

        $dto = DTO::fromArray([
            'nome' => 'João',
            'nascimento' => '2000-01-01',
            'cpf' => '12345678909',
            'email' => 'joao@email.com',
            'senha' => '123'
        ]);

        Validator::validar($dto, null);
    }

    public function testNaoValidaSenhaFracaEmAtualizacaoSeSenhaVazia()
    {
        $dto = DTO::fromArray([
            'nome' => 'João',
            'nascimento' => '2000-01-01',
            'cpf' => '12345678909',
            'email' => 'joao@email.com',
            'senha' => ''
        ]);

        Validator::validar($dto, 1);

        $this->assertTrue(true);
    }

    public function testValidaSenhaForteEmAtualizacaoSePreenchida()
    {
        $dto = DTO::fromArray([
            'nome' => 'João',
            'nascimento' => '2000-01-01',
            'cpf' => '12345678909',
            'email' => 'joao@email.com',
            'senha' => 'Senha@123'
        ]);

        Validator::validar($dto, 1);

        $this->assertTrue(true);
    }

    public function testLancaExcecaoSeNascimentoInvalido()
    {
        $this->expectException(AlunoInvalidoException::class);
        $this->expectExceptionMessage('Data de nascimento inválida');

        $dto = DTO::fromArray([
            'nome' => 'João',
            'nascimento' => 'data_errada',
            'cpf' => '12345678909',
            'email' => 'joao@email.com',
            'senha' => 'Senha@123'
        ]);

        Validator::validar($dto, null);
    }
}
