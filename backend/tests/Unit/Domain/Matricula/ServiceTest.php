<?php

use PHPUnit\Framework\TestCase;
use App\Domain\Matricula\Service;
use App\Domain\Matricula\Repository;
use App\Domain\Matricula\DTO;
use App\Domain\Matricula\Entity;
use App\Core\Exceptions\NotFoundException;
use App\Domain\Matricula\Exceptions\MatriculaDuplicadaException;

class MatriculaServiceTest extends TestCase
{
    private Repository $repo;
    private Service $service;

    protected function setUp(): void
    {
        $this->repo = $this->createMock(Repository::class);
        $this->service = new Service($this->repo);
    }

    public function testMatricularAlunoComSucesso()
    {
        $dto = DTO::fromArray(['aluno_id' => 1, 'turma_id' => 2]);

        $this->repo->method('alunoJaMatriculado')->willReturn(false);
        $this->repo->expects($this->once())->method('matricular')->with($this->isInstanceOf(Entity::class));

        $this->service->matricular($dto);
    }

    public function testMatricularAlunoDuplicadoLancaExcecao()
    {
        $this->expectException(MatriculaDuplicadaException::class);

        $dto = DTO::fromArray(['aluno_id' => 1, 'turma_id' => 2]);

        $this->repo->method('alunoJaMatriculado')->willReturn(true);

        $this->service->matricular($dto);
    }

    public function testListarPorTurmaRetornaArray()
    {
        $this->repo->method('listarPorTurma')->with(2)->willReturn([['aluno_id' => 1, 'turma_id' => 2]]);

        $resultado = $this->service->listarPorTurma(2);
        $this->assertIsArray($resultado);
    }

    public function testRemoverMatriculaExistente()
    {
        $dto = DTO::fromArray(['aluno_id' => 1, 'turma_id' => 2]);

        $this->repo->method('alunoJaMatriculado')->willReturn(true);
        $this->repo->expects($this->once())->method('remover')->with($this->isInstanceOf(Entity::class));

        $this->service->remover($dto);
    }

    public function testRemoverMatriculaInexistenteLancaExcecao()
    {
        $this->expectException(NotFoundException::class);

        $dto = DTO::fromArray(['aluno_id' => 1, 'turma_id' => 2]);

        $this->repo->method('alunoJaMatriculado')->willReturn(false);

        $this->service->remover($dto);
    }
}
