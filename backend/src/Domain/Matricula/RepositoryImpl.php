<?php

namespace App\Domain\Matricula;

use App\Core\Database;
use PDO;

class RepositoryImpl implements Repository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::connect();
    }

    public function matricular(Entity $matricula): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO matriculas (aluno_id, turma_id, data_matricula)
            VALUES (:aluno_id, :turma_id, :data)
        ");

        $stmt->execute([
            ':aluno_id' => $matricula->getAlunoId(),
            ':turma_id' => $matricula->getTurmaId(),
            ':data'     => $matricula->getDataMatricula() ?: date('Y-m-d')
        ]);
    }


    public function alunoJaMatriculado(int $alunoId, int $turmaId): bool
    {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM matriculas
            WHERE aluno_id = :aluno AND turma_id = :turma
        ");

        $stmt->execute([
            ':aluno' => $alunoId,
            ':turma' => $turmaId
        ]);

        return (int) $stmt->fetchColumn() > 0;
    }

    public function listarPorTurma(int $turmaId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT a.nome, a.email, a.cpf
            FROM matriculas m
            JOIN alunos a ON m.aluno_id = a.id
            WHERE m.turma_id = :turma
            ORDER BY a.nome ASC
        ");

        $stmt->execute([':turma' => $turmaId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function remover(Entity $matricula): void
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM matriculas
            WHERE aluno_id = :aluno_id AND turma_id = :turma_id
        ");
        $stmt->execute([
            ':aluno_id' => $matricula->getAlunoId(),
            ':turma_id' => $matricula->getTurmaId(),
        ]);
    }
}
