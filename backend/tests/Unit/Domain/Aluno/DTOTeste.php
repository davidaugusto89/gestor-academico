<?php

use PHPUnit\Framework\TestCase;
use App\Domain\Aluno\DTO;

class AlunoDTOTest extends TestCase
{
    public function testFromArrayFormataCorretamente()
    {
        $dto = DTO::fromArray([
            'nome' => ' João da Silva ',
            'nascimento' => '01/02/2000',
            'cpf' => '123.456.789-09',
            'email' => ' aluno@email.com ',
            'senha' => ' 123Senha! '
        ]);

        $this->assertEquals('João da Silva', $dto->getNome());
        $this->assertEquals('2000-02-01', $dto->getNascimento()); // considerando DateHelper converta para ISO
        $this->assertEquals('12345678909', $dto->getCpf());       // considerando Normalizer limpa
        $this->assertEquals('aluno@email.com', $dto->getEmail());
        $this->assertEquals('123Senha!', $dto->getSenha());
    }

    public function testFromArrayComValoresVazios()
    {
        $dto = DTO::fromArray([]);

        $this->assertSame('', $dto->getNome());
        $this->assertSame('', $dto->getNascimento());
        $this->assertSame('', $dto->getCpf());
        $this->assertSame('', $dto->getEmail());
        $this->assertSame('', $dto->getSenha());
    }
}
