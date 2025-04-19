<?php

use PHPUnit\Framework\TestCase;
use App\Core\BaseRepository;

class BaseRepositoryTest extends TestCase
{
    private PDO $pdo;
    private BaseRepository $repo;

    protected function setUp(): void
    {
        $this->pdo = new \PDO('sqlite::memory:');
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->pdo->exec('
            CREATE TABLE itens (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nome TEXT,
                ativo INTEGER
            )
        ');

        $this->pdo->exec("
            INSERT INTO itens (nome, ativo) VALUES
            ('Item A', 1),
            ('Item B', 0),
            ('Item C', 1)
        ");

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
    public function testListarTodos()
    {
        $result = $this->repo->listarTodos([
            'page' => 1,
            'itemsPerPage' => 999
        ]);

        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('total', $result);
        $this->assertCount(3, $result['data']);
        $this->assertEquals(3, $result['total']);

        $this->assertEquals('Item A', $result['data'][0]->nome);
        $this->assertEquals('Item B', $result['data'][1]->nome);
        $this->assertEquals('Item C', $result['data'][2]->nome);
    }
}
