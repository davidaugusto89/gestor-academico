<?php

namespace Tests\Unit\Core;

use App\Core\QueryBuilderHelper;
use PDO;
use PHPUnit\Framework\TestCase;

class QueryBuilderHelperTest extends TestCase
{
    private PDO $pdo;

    protected function setUp(): void
    {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Cria tabela e insere dados
        $this->pdo->exec("
            CREATE TABLE usuarios (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nome TEXT,
                email TEXT
            );
        ");

        $stmt = $this->pdo->prepare("INSERT INTO usuarios (nome, email) VALUES (?, ?)");

        $dados = [
            ['Ana', 'ana@email.com'],
            ['Bruno', 'bruno@email.com'],
            ['Carlos', 'carlos@email.com'],
            ['Ana Paula', 'ana.paula@email.com'],
        ];

        foreach ($dados as $d) {
            $stmt->execute($d);
        }
    }

    public function testPaginacaoSimples(): void
    {
        $sqlBase = "SELECT * FROM usuarios";
        $params = ['page' => 1, 'itemsPerPage' => 2];
        $camposPermitidos = ['nome'];

        $resultado = QueryBuilderHelper::paginarResultado(
            $this->pdo,
            $sqlBase,
            $params,
            $camposPermitidos,
            'id:asc',
            'usuarios'
        );

        $this->assertCount(2, $resultado['data']);
        $this->assertEquals(4, $resultado['total']);
    }

    public function testFiltroPorNome(): void
    {
        $sqlBase = "SELECT * FROM usuarios";
        $params = ['nome' => 'Ana'];
        $camposPermitidos = ['nome'];

        $resultado = QueryBuilderHelper::paginarResultado(
            $this->pdo,
            $sqlBase,
            $params,
            $camposPermitidos,
            'id:asc',
            'usuarios'
        );

        $this->assertEquals(2, $resultado['total']);
        $this->assertEquals('Ana', $resultado['data'][0]['nome']);
    }

    public function testOrdenacaoDesc(): void
    {
        $sqlBase = "SELECT * FROM usuarios";
        $params = [];
        $camposPermitidos = [];

        $resultado = QueryBuilderHelper::paginarResultado(
            $this->pdo,
            $sqlBase,
            $params,
            $camposPermitidos,
            'nome:desc',
            'usuarios'
        );

        $this->assertEquals('Carlos', $resultado['data'][0]['nome']);
    }
}
