<?php

use PHPUnit\Framework\TestCase;
use App\Domain\Turma\Service;
use App\Domain\Turma\Repository;
use App\Domain\Turma\Validator;
use App\Domain\Turma\DTO;
use App\Domain\Turma\Entity;
use App\Domain\Turma\Exceptions\TurmaJaExisteException;
use App\Core\Exceptions\NotFoundException;

class TurmaServiceTest extends TestCase
{
    private Repository $repo;
    private Validator $validador;
    private Service $service;

    protected function setUp(): void
    {
        $this->repo = $this->createMock(Repository::class);
        $this->validador = $this->createMock(Validator::class);
        $this->service = new Service($this->repo, $this->validador);
    }

    public function testCriaTurmaComSucesso()
    {
        $dto = DTO::fromArray(['nome' => 'Back-end PHP', 'descricao' => 'Avançado']);
        $this->repo->method('existeComMesmoNome')->willReturn(false);
        $this->repo->expects($this->once())->method('criar');

        $this->service->criar($dto);
    }

    public function testCriaTurmaDuplicadaLancaExcecao()
    {
        $this->expectException(TurmaJaExisteException::class);

        $dto = DTO::fromArray([
            'nome' => 'Back-end PHP',
            'descricao' => 'Turma duplicada'
        ]);

        $this->repo->method('existeComMesmoNome')->willReturn(true);

        $this->service->criar($dto);
    }

    public function testBuscarPorIdRetornaTurma()
    {
        $entidade = new Entity('Nome', 'Descrição');
        $this->repo->method('buscarPorId')->willReturn($entidade);

        $resultado = $this->service->buscarPorId(1);

        $this->assertSame($entidade, $resultado);
    }

    public function testBuscarPorIdInvalidoLancaExcecao()
    {
        $this->expectException(NotFoundException::class);
        $this->repo->method('buscarPorId')->willReturn(null);

        $this->service->buscarPorId(999);
    }

    public function testAtualizarTurmaComSucesso()
    {
        $dto = DTO::fromArray(['nome' => 'Nova Turma', 'descricao' => 'Atualizada']);
        $entidade = new Entity('Antigo', 'Antiga');

        $this->repo->method('buscarPorId')->willReturn($entidade);
        $this->repo->method('existeComMesmoNome')->willReturn(false);
        $this->repo->expects($this->once())->method('atualizar');

        $this->service->atualizar(1, $dto);
        $this->assertSame('Nova Turma', $entidade->getNome());
    }

    public function testAtualizarTurmaDuplicadaLancaExcecao()
    {
        $this->expectException(TurmaJaExisteException::class);

        $dto = DTO::fromArray([
            'nome' => 'Turma A',
            'descricao' => 'Já existente'
        ]);
        $this->repo->method('buscarPorId')->willReturn(new Entity('x', 'y'));
        $this->repo->method('existeComMesmoNome')->willReturn(true);

        $this->service->atualizar(1, $dto);
    }

    public function testRemoverComSucesso()
    {
        $this->repo->method('buscarPorId')->willReturn(new Entity('x', 'y'));
        $this->repo->expects($this->once())->method('remover')->with(1);

        $this->service->remover(1);
    }

    public function testRemoverTurmaInexistente()
    {
        $this->expectException(NotFoundException::class);
        $this->repo->method('buscarPorId')->willReturn(null);

        $this->service->remover(1);
    }
}
