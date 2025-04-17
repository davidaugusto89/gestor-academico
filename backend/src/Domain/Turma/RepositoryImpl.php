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

    public function listarTodos(string $colunaOrdenacao = 'nome'): array
    {
        $sql = "
        SELECT t.*, COUNT(m.aluno_id) AS total_alunos
        FROM turmas t
        LEFT JOIN matriculas m ON m.turma_id = t.id
        GROUP BY t.id
        ORDER BY t.{$colunaOrdenacao} ASC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        return new Entity(
            $row['nome'],
            $row['descricao'],
            (int) $row['id']
        );
    }
}
