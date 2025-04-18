<?php

use PHPUnit\Framework\TestCase;
use App\Domain\Aluno\Service;
use App\Domain\Aluno\Repository;
use App\Domain\Aluno\Entity;
use App\Domain\Aluno\DTO;
use App\Core\Exceptions\NotFoundException;
use App\Domain\Aluno\Exceptions\AlunoJaExisteException;
use PHPUnit\Framework\MockObject\MockObject;

class AlunoServiceTest extends TestCase
{
    /** @var Repository&MockObject */
    private Repository $repositorio;

    private Service $service;

    protected function setUp(): void
    {
        $this->repositorio = $this->createMock(Repository::class);
        $this->service = new Service($this->repositorio);
    }

    public function testCriarAlunoComDadosValidos()
    {
        $dto = DTO::fromArray([
            'nome' => 'João da Silva',
            'nascimento' => '2000-01-01',
            'cpf' => '12345678909',
            'email' => 'joao@email.com',
            'senha' => 'SenhaSegura!123'
        ]);

        $this->repositorio
            ->expects($this->once())
            ->method('emailOuCpfExiste')
            ->with('joao@email.com', '12345678909')
            ->willReturn(false);

        $this->repositorio
            ->expects($this->once())
            ->method('criar')
            ->with($this->isInstanceOf(Entity::class));

        $this->service->criar($dto);
    }

    public function testCriarAlunoComEmailOuCpfDuplicado()
    {
        $this->expectException(AlunoJaExisteException::class);

        $dto = DTO::fromArray([
            'nome' => 'Maria',
            'nascimento' => '1990-02-01',
            'cpf' => '98765432100',
            'email' => 'maria@email.com',
            'senha' => 'Senha123!'
        ]);

        $this->repositorio
            ->method('emailOuCpfExiste')
            ->willReturn(true);

        $this->service->criar($dto);
    }

    public function testBuscarAlunoPorIdComSucesso()
    {
        $aluno = new Entity('Teste', '2000-01-01', '11144477735', 'email@test.com', 'hash');

        $this->repositorio
            ->method('buscarPorId')
            ->with(1)
            ->willReturn($aluno);

        $result = $this->service->buscarPorId(1);

        $this->assertSame($aluno, $result);
    }

    public function testBuscarAlunoPorIdNaoEncontrado()
    {
        $this->expectException(NotFoundException::class);

        $this->repositorio
            ->method('buscarPorId')
            ->willReturn(null);

        $this->service->buscarPorId(99);
    }

    public function testBuscarPorNomeComMenosDeTresCaracteres()
    {
        $this->expectException(NotFoundException::class);
        $this->service->buscarPorNome('Jo');
    }

    public function testBuscarPorNomeComSucesso()
    {
        $this->repositorio
            ->method('buscarPorNome')
            ->with('João')
            ->willReturn([
                new Entity('João', '1999-01-01', '11144477735', 'joao@email.com', 'senha')
            ]);

        $resultado = $this->service->buscarPorNome('João');

        $this->assertCount(1, $resultado);
    }

    public function testAtualizarAlunoComSucesso()
    {
        $dto = DTO::fromArray([
            'nome' => 'Ana',
            'nascimento' => '1995-05-05',
            'cpf' => '98765432100',
            'email' => 'ana@email.com',
            'senha' => ''
        ]);

        $aluno = $this->createMock(Entity::class);
        $aluno->method('getId')->willReturn(1);
        $aluno->method('getNome')->willReturn($dto->getNome());
        $aluno->method('getNascimento')->willReturn($dto->getNascimento());
        $aluno->method('getCpf')->willReturn($dto->getCpf());
        $aluno->method('getEmail')->willReturn($dto->getEmail());
        $aluno->method('getSenha')->willReturn('senhaOriginal');

        $aluno->expects($this->once())->method('setNome');
        $aluno->expects($this->once())->method('setNascimento');
        $aluno->expects($this->once())->method('setCpf');
        $aluno->expects($this->once())->method('setEmail');

        $this->repositorio->method('emailOuCpfExiste')->willReturn(false);
        $this->repositorio->method('buscarPorId')->willReturn($aluno);

        $this->repositorio
            ->expects($this->once())
            ->method('atualizar')
            ->with(1, $this->arrayHasKey('nome'));

        $this->service->atualizar(1, $dto);
    }

    public function testAtualizarAlunoJaExistente()
    {
        $this->expectException(AlunoJaExisteException::class);

        $dto = DTO::fromArray([
            'nome' => 'João',
            'nascimento' => '2000-01-01',
            'cpf' => '12345678909',
            'email' => 'joao@email.com',
            'senha' => ''
        ]);

        $this->repositorio->method('emailOuCpfExiste')->willReturn(true);

        $this->service->atualizar(1, $dto);
    }

    public function testRemoverAlunoComSucesso()
    {
        $aluno = new Entity('Carlos', '1990-01-01', '12345678909', 'carlos@email.com', 'senha');

        $this->repositorio->method('buscarPorId')->with(1)->willReturn($aluno);
        $this->repositorio->expects($this->once())->method('remover')->with(1);

        $this->service->remover(1);
    }

    public function testRemoverAlunoInexistente()
    {
        $this->expectException(NotFoundException::class);

        $this->repositorio->method('buscarPorId')->willReturn(null);

        $this->service->remover(99);
    }
}
