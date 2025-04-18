<?php

use PHPUnit\Framework\TestCase;
use App\Domain\Matricula\Entity;

class MatriculaEntityTest extends TestCase
{
    public function testConstrutorEGetters()
    {
        $matricula = new Entity(1, 2, '2024-04-01', 10);

        $this->assertSame(1, $matricula->getAlunoId());
        $this->assertSame(2, $matricula->getTurmaId());
        $this->assertSame('2024-04-01', $matricula->getDataMatricula());
        $this->assertSame(10, $matricula->getId());
    }

    public function testSettersAtualizamValores()
    {
        $matricula = new Entity(0, 0);

        $matricula->setAlunoId(5);
        $matricula->setTurmaId(8);
        $matricula->setDataMatricula('2024-04-15');
        $matricula->setId(99);

        $this->assertSame(5, $matricula->getAlunoId());
        $this->assertSame(8, $matricula->getTurmaId());
        $this->assertSame('2024-04-15', $matricula->getDataMatricula());
        $this->assertSame(99, $matricula->getId());
    }

    public function testJsonSerializeRetornaArrayCorreto()
    {
        $matricula = new Entity(3, 4, '2024-04-10', 7);

        $json = $matricula->jsonSerialize();

        $this->assertIsArray($json);
        $this->assertSame(7, $json['id']);
        $this->assertSame(3, $json['aluno_id']);
        $this->assertSame(4, $json['turma_id']);
        $this->assertSame('2024-04-10', $json['data_matricula']);
    }
}
