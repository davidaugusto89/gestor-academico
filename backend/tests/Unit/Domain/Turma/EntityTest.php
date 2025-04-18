<?php

use PHPUnit\Framework\TestCase;
use App\Domain\Turma\Entity;

class TurmaEntityTest extends TestCase
{
    public function testCriaTurmaComDadosValidos()
    {
        $turma = new Entity('Back-end PHP', 'Curso avançado', 1, 15);

        $this->assertSame(1, $turma->getId());
        $this->assertSame('Back-end PHP', $turma->getNome());
        $this->assertSame('Curso avançado', $turma->getDescricao());
        $this->assertSame(15, $turma->getTotalAlunos());
    }

    public function testAtualizaDadosDaTurma()
    {
        $turma = new Entity('Turma Antiga', 'Descrição', 5);
        $turma->setNome('Nova Turma');
        $turma->setDescricao('Nova descrição');
        $turma->setTotalAlunos(20);

        $this->assertSame('Nova Turma', $turma->getNome());
        $this->assertSame('Nova descrição', $turma->getDescricao());
        $this->assertSame(20, $turma->getTotalAlunos());
    }

    public function testJsonSerializeRetornaArrayCorreto()
    {
        $turma = new Entity('Turma X', 'Descrição X', 99, 42);

        $json = $turma->jsonSerialize();

        $this->assertIsArray($json);
        $this->assertSame(99, $json['id']);
        $this->assertSame('Turma X', $json['nome']);
        $this->assertSame('Descrição X', $json['descricao']);
        $this->assertSame(42, $json['total_alunos']);
    }
}
