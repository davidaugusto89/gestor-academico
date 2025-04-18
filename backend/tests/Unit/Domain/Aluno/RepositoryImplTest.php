<?php

use PHPUnit\Framework\TestCase;
use App\Domain\Aluno\RepositoryImpl;
use App\Domain\Aluno\Entity;

class AlunoRepositoryImplTest extends TestCase
{
    public function testEmailOuCpfExisteRetornaTrueQuandoEncontrado()
    {
        $pdoMock = $this->createMock(\PDO::class);
        $stmtMock = $this->createMock(\PDOStatement::class);

        $stmtMock->method('fetch')->willReturn(['total' => 1]);
        $stmtMock->expects($this->once())->method('execute');

        $pdoMock->method('prepare')->willReturn($stmtMock);

        $repo = new RepositoryImpl($pdoMock);
        $existe = $repo->emailOuCpfExiste('email@email.com', '12345678909');

        $this->assertTrue($existe);
    }

    public function testEmailOuCpfExisteRetornaFalseQuandoNaoEncontrado()
    {
        $pdoMock = $this->createMock(\PDO::class);
        $stmtMock = $this->createMock(\PDOStatement::class);

        $stmtMock->method('fetch')->willReturn(['total' => 0]);
        $stmtMock->expects($this->once())->method('execute');

        $pdoMock->method('prepare')->willReturn($stmtMock);

        $repo = new RepositoryImpl($pdoMock);
        $existe = $repo->emailOuCpfExiste('teste@email.com', '00000000000');

        $this->assertFalse($existe);
    }

    public function testBuscarPorNomeMapeiaParaEntidade()
    {
        $pdoMock = $this->createMock(\PDO::class);
        $stmtMock = $this->createMock(\PDOStatement::class);

        $stmtMock->method('fetchAll')->willReturn([
            [
                'id' => 1,
                'nome' => 'Lucas',
                'nascimento' => '2000-01-01',
                'cpf' => '12345678909',
                'email' => 'lucas@email.com',
                'senha' => 'hash123'
            ]
        ]);

        $stmtMock->expects($this->once())->method('execute');
        $pdoMock->method('prepare')->willReturn($stmtMock);

        $repo = new RepositoryImpl($pdoMock);
        $resultado = $repo->buscarPorNome('Lucas');

        $this->assertCount(1, $resultado);
        $this->assertEquals('Lucas', $resultado[0]->getNome());
        $this->assertEquals('12345678909', $resultado[0]->getCpf());
    }
}
