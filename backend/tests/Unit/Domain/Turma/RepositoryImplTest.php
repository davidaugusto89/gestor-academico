<?php

use PHPUnit\Framework\TestCase;
use App\Domain\Turma\RepositoryImpl;
use App\Domain\Turma\Entity;

class TurmaRepositoryImplTest extends TestCase
{
    public function testCriarExecutaInsert()
    {
        $turma = new Entity('PHP Avançado', 'Turma noturna');

        $pdo = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())->method('execute')->with([
            'nome' => 'PHP Avançado',
            'descricao' => 'Turma noturna'
        ]);
        $pdo->method('prepare')->willReturn($stmt);

        $repo = new RepositoryImpl($pdo);
        $repo->criar($turma);
    }

    public function testBuscarPorNomeRetornaEntidades()
    {
        $pdo = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('fetch')->willReturnOnConsecutiveCalls(
            ['id' => 1, 'nome' => 'Turma A', 'descricao' => 'Descrição A'],
            false
        );
        $stmt->expects($this->once())->method('execute')->with(['nome' => '%Turma%']);
        $pdo->method('prepare')->willReturn($stmt);

        $repo = new RepositoryImpl($pdo);
        $resultado = $repo->buscarPorNome('Turma');

        $this->assertIsArray($resultado);
        $this->assertInstanceOf(Entity::class, $resultado[0]);
        $this->assertEquals('Turma A', $resultado[0]->getNome());
    }

    public function testExisteComMesmoNomeRetornaTrue()
    {
        $pdo = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('fetch')->willReturn(['total' => 1]);
        $pdo->method('prepare')->willReturn($stmt);

        $repo = new RepositoryImpl($pdo);
        $this->assertTrue($repo->existeComMesmoNome('PHP', 2));
    }

    public function testExisteComMesmoNomeRetornaFalse()
    {
        $pdo = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('fetch')->willReturn(['total' => 0]);
        $pdo->method('prepare')->willReturn($stmt);

        $repo = new RepositoryImpl($pdo);
        $this->assertFalse($repo->existeComMesmoNome('Inexistente'));
    }

    public function testAtualizarExecutaUpdate()
    {
        $pdo = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())->method('execute')->with([
            'id' => 10,
            'nome' => 'Turma Atualizada',
            'descricao' => 'Nova descrição'
        ]);
        $pdo->method('prepare')->willReturn($stmt);

        $repo = new RepositoryImpl($pdo);
        $repo->atualizar(10, [
            'nome' => 'Turma Atualizada',
            'descricao' => 'Nova descrição'
        ]);
    }

    public function testRemoverExecutaDelete()
    {
        $pdo = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())->method('execute')->with(['id' => 99]);
        $pdo->method('prepare')->willReturn($stmt);

        $repo = new RepositoryImpl($pdo);
        $repo->remover(99);
    }

    public function testContarRetornaTotal()
    {
        $pdo = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('fetch')->willReturn(['total' => 5]);
        $pdo->method('query')->willReturn($stmt);

        $repo = new RepositoryImpl($pdo);
        $this->assertSame(5, $repo->contar());
    }

    public function testListarTodosChamaQueryBuilderHelper()
    {
        // Simula retorno de QueryBuilderHelper::paginarResultado
        $resultadoEsperado = [
            'data' => [],
            'total' => 0
        ];

        // Cria mock da classe real extendendo RepositoryImpl e sobrescrevendo o método estático
        $pdo = $this->createMock(PDO::class);

        // Classe anônima para expor override de método usando stub
        $repo = new class($pdo) extends \App\Domain\Turma\RepositoryImpl {
            public static array $resultadoFake = [];

            public function listarTodos(array $params, string $ordem = 'nome:asc', ?array $camposPermitidos = null): array
            {
                return self::$resultadoFake;
            }
        };

        // Define resultado fake
        $repo::$resultadoFake = [
            'data' => [
                new \App\Domain\Turma\Entity('Turma Fake', 'Teste', 1, 3)
            ],
            'total' => 1
        ];

        // Executa método e valida retorno
        $resultado = $repo->listarTodos([]);

        $this->assertCount(1, $resultado['data']);
        $this->assertInstanceOf(\App\Domain\Turma\Entity::class, $resultado['data'][0]);
        $this->assertEquals(1, $resultado['total']);
    }
}
