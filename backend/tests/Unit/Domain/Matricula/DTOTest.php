<?php

use PHPUnit\Framework\TestCase;
use App\Domain\Matricula\DTO;

class MatriculaDTOTest extends TestCase
{
    public function testFromArrayMapeiaCorretamente()
    {
        $dto = DTO::fromArray(['aluno_id' => '1', 'turma_id' => '5']);

        $this->assertSame(1, $dto->getAlunoId());
        $this->assertSame(5, $dto->getTurmaId());
    }

    public function testFromArrayComValoresPadrao()
    {
        $dto = DTO::fromArray([]); // sem dados

        $this->assertSame(0, $dto->getAlunoId());
        $this->assertSame(0, $dto->getTurmaId());
    }
}
