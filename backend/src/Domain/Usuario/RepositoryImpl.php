<?php

namespace App\Domain\Usuario;

use App\Core\Database;
use PDO;

class RepositoryImpl implements Repository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::connect();
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
            $row['papel'],
            (int) $row['id']
        );
    }

    public function buscarPorId(int $id): ?Entity
    {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new Entity(
            $row['nome'],
            $row['email'],
            $row['senha'],
            $row['papel'],
            (int) $row['id']
        );
    }

    public function existePorEmail(string $email): bool
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return (int) $stmt->fetchColumn() > 0;
    }
}
