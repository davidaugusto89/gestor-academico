<?php

namespace App\Domain\Usuario;

use App\Core\BaseRepository;
use PDO;

class RepositoryImpl extends BaseRepository implements Repository
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
        $this->tabela = 'usuarios';
    }

    public function criar(Entity $usuario): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO usuarios (nome, email, senha, papel)
            VALUES (:nome, :email, :senha, :papel)
        ");

        $stmt->execute([
            ':nome'  => $usuario->getNome(),
            ':email' => $usuario->getEmail(),
            ':senha' => $usuario->getSenha(),
            ':papel' => $usuario->getPapel()
        ]);
    }

    public function buscarPorEmail(string $email): ?Entity
    {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new Entity(
            $row['nome'],
            $row['email'],
            $row['senha'],
            (int) $row['id'],
            $row['papel']
        );
    }

    public function existePorEmail(string $email, ?int $ignorarId = null): bool
    {
        $sql = "SELECT COUNT(*) FROM usuarios WHERE email = :email";

        if ($ignorarId) {
            $sql .= " AND id != :id";
        }

        $stmt = $this->pdo->prepare($sql);
        $params = [
            ':email' => $email
        ];

        if ($ignorarId) {
            $params[':id'] = $ignorarId;
        }

        $stmt->execute($params);

        return (int) $stmt->fetchColumn() > 0;
    }

    protected function mapearParaEntidade(array $dados): object
    {
        return new Entity(
            $dados['nome'],
            $dados['email'],
            $dados['senha'],
            (int) $dados['id'],
            $dados['papel']
        );
    }
}
