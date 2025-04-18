<?php

use PHPUnit\Framework\TestCase;
use App\Domain\Matricula\Validator;
use App\Domain\Matricula\DTO;
use App\Domain\Matricula\Exceptions\MatriculaInvalidaException;

class MatriculaValidatorTest extends TestCase
{
    public function testValidaMatriculaComIdsValidos()
    {
        $dto = DTO::fromArray(['aluno_id' => 1, 'turma_id' => 2]);

        Validator::validar($dto);

        $this->assertTrue(true);
    }

    public function testLancaExcecaoSeAlunoIdInvalido()
    {
        $this->expectException(MatriculaInvalidaException::class);
        $this->expectExceptionMessage("Aluno ou turma inválidos.");

        $dto = DTO::fromArray(['aluno_id' => 0, 'turma_id' => 1]);
        Validator::validar($dto);
    }

    public function testLancaExcecaoSeTurmaIdInvalido()
    {
        $this->expectException(MatriculaInvalidaException::class);
        $this->expectExceptionMessage("Aluno ou turma inválidos.");

        $dto = DTO::fromArray(['aluno_id' => 1, 'turma_id' => -5]);
        Validator::validar($dto);
    }
}
