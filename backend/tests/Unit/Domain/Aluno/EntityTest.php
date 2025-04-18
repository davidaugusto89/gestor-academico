<?php

use PHPUnit\Framework\TestCase;
use App\Domain\Aluno\Entity;

class AlunoEntityTest extends TestCase
{
    public function testAcessoAtributosViaGetters()
    {
        $aluno = new Entity(
            nome: 'João da Silva',
            nascimento: '2000-01-01',
            cpf: '12345678900',
            email: 'joao@email.com',
            senha: 'SenhaSegura!',
            id: 10
        );

        $this->assertSame(10, $aluno->getId());
        $this->assertSame('João da Silva', $aluno->getNome());
        $this->assertSame('2000-01-01', $aluno->getNascimento());
        $this->assertSame('12345678900', $aluno->getCpf());
        $this->assertSame('joao@email.com', $aluno->getEmail());
        $this->assertSame('SenhaSegura!', $aluno->getSenha());
    }

    public function testAtualizacaoAtributosViaSetters()
    {
        $aluno = new Entity('a', 'b', 'c', 'd', 'e');

        $aluno->setNome('Maria');
        $aluno->setNascimento('1990-12-31');
        $aluno->setCpf('98765432100');
        $aluno->setEmail('maria@email.com');
        $aluno->setSenha('NovaSenha123');

        $this->assertSame('Maria', $aluno->getNome());
        $this->assertSame('1990-12-31', $aluno->getNascimento());
        $this->assertSame('98765432100', $aluno->getCpf());
        $this->assertSame('maria@email.com', $aluno->getEmail());
        $this->assertSame('NovaSenha123', $aluno->getSenha());
    }

    public function testJsonSerialize()
    {
        $aluno = new Entity(
            nome: 'Lucas',
            nascimento: '1995-05-05',
            cpf: '32165498700',
            email: 'lucas@email.com',
            senha: 'Senha!',
            id: 5
        );

        $json = $aluno->jsonSerialize();

        $this->assertIsArray($json);
        $this->assertSame(5, $json['id']);
        $this->assertSame('Lucas', $json['nome']);
        $this->assertSame('1995-05-05', $json['nascimento']);
        $this->assertSame('32165498700', $json['cpf']);
        $this->assertSame('lucas@email.com', $json['email']);
        $this->assertArrayNotHasKey('senha', $json); // senha não deve aparecer
    }
}
