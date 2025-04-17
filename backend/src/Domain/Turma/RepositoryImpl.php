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

    public function salvar(Entity $turma): void
    {
        $sql = "INSERT INTO {$this->tabela} (nome, descricao) VALUES (:nome, :descricao)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'nome'      => $turma->getNome(),
            'descricao' => $turma->getDescricao(),
        ]);
    }

    public function contar(): int
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->tabela}";
        $stmt = $this->pdo->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) $row['total'];
    }

    public function buscarPorNome(string $nome): array
    {
        $sql = "SELECT * FROM {$this->tabela} WHERE nome LIKE :nome ORDER BY nome ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['nome' => "%{$nome}%"]);

        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new Entity(
                $row['id'],
                $row['nome'],
                $row['descricao']
            );
        }

        return $result;
    }

    public function existeComMesmoNome(string $nome): bool
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->tabela} WHERE nome = :nome";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['nome' => $nome]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int) $row['total'] > 0;
    }
}
