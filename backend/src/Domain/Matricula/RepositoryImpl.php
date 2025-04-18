<?php

namespace App\Domain\Matricula;

use App\Core\QueryBuilderHelper;
use PDO;

/**
 * Implementação concreta do repositório de matrículas.
 */
class RepositoryImpl implements Repository
{
    private PDO $pdo;

    /**
     * Construtor que inicializa a conexão com o banco de dados.
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Insere uma nova matrícula no banco de dados.
     *
     * @param Entity $matricula
     */
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

    /**
     * Verifica se um aluno já está matriculado em uma turma.
     *
     * @param int $alunoId
     * @param int $turmaId
     * @return bool
     */
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

    /**
     * Lista todas as matrículas com paginação e filtros.
     *
     * @param array $params
     * @param string $ordem
     * @param array|null $camposPermitidos
     * @return array
     */
    public function listarTodos(array $params, string $ordem = 'data_matricula:desc', ?array $camposPermitidos = null): array
    {
        $sql = "
            SELECT a.nome AS aluno_nome, a.cpf AS aluno_cpf, t.nome AS turma_nome, m.*
            FROM matriculas m
            JOIN alunos a ON m.aluno_id = a.id
            JOIN turmas t ON m.turma_id = t.id
        ";

        return QueryBuilderHelper::paginarResultado(
            $this->pdo,
            $sql,
            $params,
            Filter::camposPermitidos(),
            $ordem,
            'm',
            function ($row) {
                $entidade = $this->mapearParaEntidade($row);

                return [
                    'aluno_id' => $entidade->getAlunoId(),
                    'aluno_nome' => $row['aluno_nome'],
                    'aluno_cpf' => $row['aluno_cpf'],

                    'turma_id' => $entidade->getTurmaId(),
                    'turma_nome' => $row['turma_nome'],

                    'data_matricula' => $entidade->getDataMatricula(),

                ];
            }
        );
    }

    /**
     * Lista os alunos matriculados em uma turma.
     *
     * @param int $turmaId
     * @return array
     */
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

    /**
     * Remove a matrícula de um aluno em uma turma.
     *
     * @param Entity $matricula
     */
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


    /**
     * Mapeia um array de dados para a entidade Matricula.
     *
     * @param array $row
     * @return Entity
     */
    protected function mapearParaEntidade(array $row): Entity
    {
        if (is_null($row['aluno_id']) || is_null($row['turma_id'])) {
            throw new \RuntimeException('aluno_id ou turma_id está nulo.');
        }

        return new Entity(
            (int) $row['aluno_id'],
            (int) $row['turma_id'],
            $row['data_matricula'] ?? null,
            isset($row['id']) ? (int) $row['id'] : null
        );
    }
}
