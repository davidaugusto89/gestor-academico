<?php

namespace Tests\Unit\Domain\Usuario;

use App\Domain\Usuario\Entity;
use App\Domain\Usuario\RepositoryImpl;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class UsuarioRepositoryImplTest extends TestCase
{
    private PDO $pdo;
    private PDOStatement $statement;
    private RepositoryImpl $repositorio;

    protected function setUp(): void
    {
        parent::setUp();

        $this->pdo = $this->createMock(PDO::class);
        $this->statement = $this->createMock(PDOStatement::class);

        $this->pdo
            ->method('prepare')
            ->willReturn($this->statement);

        $this->repositorio = new RepositoryImpl($this->pdo);
    }

    public function testCriarUsuarioExecutaInsertCorretamente(): void
    {
        $usuario = new Entity('Carlos', 'carlos@email.com', 'senha123', null, 'user');

        $this->statement
            ->expects($this->once())
            ->method('execute')
            ->with([
                ':nome'  => 'Carlos',
                ':email' => 'carlos@email.com',
                ':senha' => 'senha123',
                ':papel' => 'user'
            ]);

        $this->repositorio->criar($usuario);
    }

    public function testBuscarPorEmailRetornaEntidadeQuandoEncontrado(): void
    {
        $dados = [
            'id' => 1,
            'nome' => 'Maria',
            'email' => 'maria@email.com',
            'senha' => 'hashsenha',
            'papel' => 'admin'
        ];

        $this->statement
            ->method('execute')
            ->with([':email' => 'maria@email.com']);

        $this->statement
            ->method('fetch')
            ->willReturn($dados);

        $this->pdo
            ->method('prepare')
            ->willReturn($this->statement);

        $repositorio = new RepositoryImpl($this->pdo);
        $usuario = $repositorio->buscarPorEmail('maria@email.com');

        $this->assertInstanceOf(Entity::class, $usuario);
        $this->assertEquals('Maria', $usuario->getNome());
        $this->assertEquals('admin', $usuario->getPapel());
    }

    public function testBuscarPorEmailRetornaNullSeNaoEncontrado(): void
    {
        $this->statement
            ->method('fetch')
            ->willReturn(false);

        $this->repositorio = new RepositoryImpl($this->pdo);
        $resultado = $this->repositorio->buscarPorEmail('naoexiste@email.com');

        $this->assertNull($resultado);
    }

    public function testExistePorEmailRetornaTrueQuandoExiste(): void
    {
        $this->statement
            ->method('fetchColumn')
            ->willReturn(1);

        $resultado = $this->repositorio->existePorEmail('existe@email.com');

        $this->assertTrue($resultado);
    }

    public function testExistePorEmailRetornaFalseQuandoNaoExiste(): void
    {
        $this->statement
            ->method('fetchColumn')
            ->willReturn(0);

        $resultado = $this->repositorio->existePorEmail('novo@email.com');

        $this->assertFalse($resultado);
    }

    public function testExistePorEmailComIgnorarId(): void
    {
        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('AND id != :id'))
            ->willReturn($this->statement);

        $this->statement
            ->method('execute')
            ->with([
                ':email' => 'teste@email.com',
                ':id' => 5
            ]);

        $this->statement
            ->method('fetchColumn')
            ->willReturn(1);

        $resultado = $this->repositorio->existePorEmail('teste@email.com', 5);

        $this->assertTrue($resultado);
    }

    public function testMapearParaEntidadeConverteDadosCorretamente(): void
    {
        // Dados de exemplo que seriam retornados do banco de dados
        $dados = [
            'id' => '42', // string que será convertida para int
            'nome' => 'João Silva',
            'email' => 'joao@email.com',
            'senha' => '$2y$10$hashedpassword',
            'papel' => 'admin'
        ];

        // Usando Reflection para testar método protegido
        $reflection = new \ReflectionClass(RepositoryImpl::class);
        $method = $reflection->getMethod('mapearParaEntidade');
        $method->setAccessible(true);

        /** @var Entity $entidade */
        $entidade = $method->invokeArgs($this->repositorio, [$dados]);

        // Verificações
        $this->assertInstanceOf(Entity::class, $entidade);
        $this->assertEquals(42, $entidade->getId()); // Verifica conversão para int
        $this->assertEquals('João Silva', $entidade->getNome());
        $this->assertEquals('joao@email.com', $entidade->getEmail());
        $this->assertEquals('$2y$10$hashedpassword', $entidade->getSenha());
        $this->assertEquals('admin', $entidade->getPapel());
    }
}
