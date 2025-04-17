<?php

namespace App\Domain\Turma;

use App\Core\BaseRepository;
use PDO;

class RepositoryImpl extends BaseRepository implements Repository
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
        $this->tabela = 'turmas';
    }

    public function criar(Entity $turma): void
    {
        $sql = "INSERT INTO {$this->tabela} (nome, descricao) VALUES (:nome, :descricao)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'nome'      => $turma->getNome(),
            'descricao' => $turma->getDescricao(),
        ]);
    }

    public function listarTodos(array $params, string $ordem = 'nome:asc', ?array $camposPermitidos = null): array
    {
        list($colunaOrdenacao, $ordem) = explode(':', $ordem);
        $ordem = $ordem === 'asc' ? 'ASC' : 'DESC';

        $page = isset($params['page']) ? (int) $params['page'] : 1;
        $itemsPerPage = isset($params['itemsPerPage']) ? (int) $params['itemsPerPage'] : 10;
        $offset = isset($params['offset']) ? (int) $params['offset'] : ($page - 1) * $itemsPerPage;

        $where = '';
        $binds = [];

        if ($params) {
            foreach ($params as $key => $value) {
                if (in_array($key, $camposPermitidos) && $value !== null) {
                    switch ($key) {
                        case 'nome':
                            $where .= " AND t.{$key} LIKE :{$key}";
                            $binds[":{$key}"] = "%{$value}%";
                            break;
                        default:
                            $where .= " AND t.{$key} = :{$key}";
                            $binds[":{$key}"] = $value;
                            break;
                    }
                }
            }
        }

        if ($where) {
            $where = ' WHERE ' . substr($where, 4);
        }

        // Query principal com total de alunos por turma
        $sql = "
            SELECT t.*, COUNT(m.aluno_id) AS total_alunos
            FROM turmas t
            LEFT JOIN matriculas m ON m.turma_id = t.id
            {$where}
            GROUP BY t.id
            ORDER BY t.{$colunaOrdenacao} {$ordem}
            LIMIT {$itemsPerPage} OFFSET {$offset}
        ";

        $stmt = $this->pdo->prepare($sql);
        foreach ($binds as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Contagem total de registros (sem LIMIT) para paginação
        $sql2 = "
            SELECT COUNT(DISTINCT t.id) AS total
            FROM turmas t
            LEFT JOIN matriculas m ON m.turma_id = t.id
            {$where}
        ";

        $stmt2 = $this->pdo->prepare($sql2);
        foreach ($binds as $key => $value) {
            $stmt2->bindValue($key, $value);
        }
        $stmt2->execute();
        $total = $stmt2->fetch(PDO::FETCH_ASSOC)['total'];

        return [
            'data' => array_map([$this, 'mapearParaEntidade'], $data),
            'total' => (int) $total,
        ];
    }

    public function buscarPorNome(string $nome): array
    {
        $sql = "SELECT * FROM {$this->tabela} WHERE nome LIKE :nome ORDER BY nome ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['nome' => "%{$nome}%"]);

        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->mapearParaEntidade($row);
        }

        return $result;
    }

    public function existeComMesmoNome(string $nome, ?int $ignorarId = null): bool
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->tabela} WHERE nome = :nome";

        $params = ['nome' => $nome];

        if ($ignorarId !== null) {
            $sql .= " AND id != :id";
            $params['id'] = $ignorarId;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int) $row['total'] > 0;
    }

    public function atualizar(int $id, array $dados): void
    {
        $sql = "UPDATE {$this->tabela} SET nome = :nome, descricao = :descricao WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id'        => $id,
            'nome'      => $dados['nome'],
            'descricao' => $dados['descricao'],
        ]);
    }

    public function remover(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->tabela} WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    public function contar(): int
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM {$this->tabela}");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) $row['total'];
    }

    protected function mapearParaEntidade(array $row): object
    {
        $turma = new Entity(
            $row['nome'],
            $row['descricao'],
            $row['id'],
            $row['total_alunos'] ?? 0
        );

        return $turma;
    }
}
