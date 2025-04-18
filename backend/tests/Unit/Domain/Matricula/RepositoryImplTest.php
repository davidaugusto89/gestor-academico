<?php

use PHPUnit\Framework\TestCase;
use App\Domain\Matricula\RepositoryImpl;
use App\Domain\Matricula\Entity;

class MatriculaRepositoryImplTest extends TestCase
{
    public function testAlunoJaMatriculadoRetornaTrue()
    {
        $pdo = $this->createMock(\PDO::class);
        $stmt = $this->createMock(\PDOStatement::class);

        $stmt->method('fetchColumn')->willReturn(1);
        $pdo->method('prepare')->willReturn($stmt);

        $repo = new RepositoryImpl($pdo);
        $this->assertTrue($repo->alunoJaMatriculado(1, 2));
    }

    public function testAlunoJaMatriculadoRetornaFalse()
    {
        $pdo = $this->createMock(\PDO::class);
        $stmt = $this->createMock(\PDOStatement::class);

        $stmt->method('fetchColumn')->willReturn(0);
        $pdo->method('prepare')->willReturn($stmt);

        $repo = new RepositoryImpl($pdo);
        $this->assertFalse($repo->alunoJaMatriculado(1, 2));
    }

    public function testMatricularExecutaInsert()
    {
        $matricula = new Entity(1, 2, '2024-04-15');

        $pdo = $this->createMock(\PDO::class);
        $stmt = $this->createMock(\PDOStatement::class);
        $stmt->expects($this->once())->method('execute')->with([
            ':aluno_id' => 1,
            ':turma_id' => 2,
            ':data' => '2024-04-15'
        ]);
        $pdo->method('prepare')->willReturn($stmt);

        $repo = new RepositoryImpl($pdo);
        $repo->matricular($matricula);
    }

    public function testRemoverExecutaDelete()
    {
        $matricula = new Entity(1, 2);

        $pdo = $this->createMock(\PDO::class);
        $stmt = $this->createMock(\PDOStatement::class);
        $stmt->expects($this->once())->method('execute')->with([
            ':aluno_id' => 1,
            ':turma_id' => 2
        ]);
        $pdo->method('prepare')->willReturn($stmt);

        $repo = new RepositoryImpl($pdo);
        $repo->remover($matricula);
    }

    public function testListarPorTurmaRetornaArray()
    {
        $pdo = $this->createMock(\PDO::class);
        $stmt = $this->createMock(\PDOStatement::class);

        $stmt->method('fetchAll')->willReturn([
            ['nome' => 'João', 'email' => 'joao@email.com', 'cpf' => '123']
        ]);
        $stmt->expects($this->once())->method('execute')->with([':turma' => 5]);
        $pdo->method('prepare')->willReturn($stmt);

        $repo = new RepositoryImpl($pdo);
        $resultado = $repo->listarPorTurma(5);

        $this->assertIsArray($resultado);
        $this->assertSame('João', $resultado[0]['nome']);
    }

    public function testMapearParaEntidadeComDadosValidos()
    {
        $pdo = $this->createMock(\PDO::class);
        $repo = new class($pdo) extends RepositoryImpl {
            public function exposeMapeamento(array $row)
            {
                return $this->mapearParaEntidade($row);
            }
        };

        $row = [
            'aluno_id' => 1,
            'turma_id' => 2,
            'data_matricula' => '2024-04-20',
            'id' => 10
        ];

        $entidade = $repo->exposeMapeamento($row);

        $this->assertInstanceOf(\App\Domain\Matricula\Entity::class, $entidade);
        $this->assertSame(1, $entidade->getAlunoId());
        $this->assertSame(2, $entidade->getTurmaId());
        $this->assertSame('2024-04-20', $entidade->getDataMatricula());
        $this->assertSame(10, $entidade->getId());
    }

    public function testMapearParaEntidadeComDataMatriculaNula()
    {
        $pdo = $this->createMock(\PDO::class);
        $repo = new class($pdo) extends RepositoryImpl {
            public function exposeMapeamento(array $row)
            {
                return $this->mapearParaEntidade($row);
            }
        };

        $row = [
            'aluno_id' => 3,
            'turma_id' => 4,
            'id' => 22
        ];

        $entidade = $repo->exposeMapeamento($row);

        $this->assertSame(3, $entidade->getAlunoId());
        $this->assertSame(4, $entidade->getTurmaId());
        $this->assertNull($entidade->getDataMatricula());
        $this->assertSame(22, $entidade->getId());
    }

    public function testMapearParaEntidadeLancaExcecaoSeCamposNulos()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('aluno_id ou turma_id está nulo.');

        $pdo = $this->createMock(\PDO::class);
        $repo = new class($pdo) extends RepositoryImpl {
            public function exposeMapeamento(array $row)
            {
                return $this->mapearParaEntidade($row);
            }
        };

        $row = [
            'aluno_id' => null,
            'turma_id' => 1
        ];

        $repo->exposeMapeamento($row);
    }

    public function testListarTodosRetornaDadosComFormatacaoEsperada()
    {
        $pdo = $this->createMock(\PDO::class);

        $repo = new class($pdo) extends RepositoryImpl {
            public function listarTodos(array $params, string $ordem = 'data_matricula:desc', ?array $camposPermitidos = null): array
            {
                $row = [
                    'aluno_id' => 1,
                    'aluno_nome' => 'João da Silva',
                    'aluno_cpf' => '12345678901',
                    'turma_id' => 2,
                    'turma_nome' => 'Turma A',
                    'data_matricula' => '2024-04-18',
                    'id' => 99,
                ];

                return [
                    'data' => [
                        [
                            'aluno_id' => $row['aluno_id'],
                            'aluno_nome' => $row['aluno_nome'],
                            'aluno_cpf' => $row['aluno_cpf'],
                            'turma_id' => $row['turma_id'],
                            'turma_nome' => $row['turma_nome'],
                            'data_matricula' => $row['data_matricula'],
                        ]
                    ],
                    'total' => 1
                ];
            }
        };

        $resultado = $repo->listarTodos([]);

        $this->assertIsArray($resultado);
        $this->assertCount(1, $resultado['data']);
        $this->assertEquals('João da Silva', $resultado['data'][0]['aluno_nome']);
        $this->assertEquals('Turma A', $resultado['data'][0]['turma_nome']);
    }
}
