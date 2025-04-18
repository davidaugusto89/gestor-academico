<?php

use PHPUnit\Framework\TestCase;
use App\Core\BaseRepository;
use PDO;

class BaseRepositoryTest extends TestCase
{
    private PDO $pdo;
    private BaseRepository $repo;

    protected function setUp(): void
    {
        // SQLite em memória para testes isolados
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Cria tabela fake
        $this->pdo->exec('
            CREATE TABLE itens (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nome TEXT,
                ativo INTEGER
            )
        ');

        // Insere alguns dados
        $this->pdo->exec("
            INSERT INTO itens (nome, ativo) VALUES
            ('Item A', 1),
            ('Item B', 0),
            ('Item C', 1)
        ");

        // Cria uma instância anônima do repositório
        $this->repo = new class($this->pdo) extends BaseRepository {
            protected string $tabela = 'itens';

            protected function mapearParaEntidade(array $dados): object
            {
                return (object) $dados;
            }
        };
    }

    public function testBuscarPorId()
    {
        $item = $this->repo->buscarPorId(1);
        $this->assertEquals('Item A', $item->nome);

        $this->assertNull($this->repo->buscarPorId(999));
    }

    public function testAtualizar()
    {
        $this->repo->atualizar(2, ['nome' => 'Atualizado', 'ativo' => 1]);

        $item = $this->repo->buscarPorId(2);
        $this->assertEquals('Atualizado', $item->nome);
        $this->assertEquals(1, $item->ativo);
    }

    public function testRemover()
    {
        $this->repo->remover(3);

        $item = $this->repo->buscarPorId(3);
        $this->assertNull($item);
    }
}
