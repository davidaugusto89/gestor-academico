<?php

namespace App\Domain\Matricula;

use App\Domain\Matricula\Exceptions\MatriculaDuplicadaException;
use App\Core\Exceptions\NotFoundException;

/**
 * Serviço responsável por gerenciar matrículas de alunos em turmas.
 */
class Service
{
    /**
     * @param Repository $repositorio Repositório de matrícula
     */
    public function __construct(
        private readonly repository $repositorio
    ) {}

    /**
     * Realiza a matrícula de um aluno em uma turma.
     *
     * @param DTO $dto Dados da matrícula
     * @throws MatriculaDuplicadaException Se o aluno já estiver matriculado
     */
    public function matricular(DTO $dto): void
    {
        Validator::validar($dto);

        if ($this->repositorio->alunoJaMatriculado($dto->getAlunoId(), $dto->getTurmaId())) {
            throw new MatriculaDuplicadaException("Aluno já matriculado nesta turma.");
        }

        $matricula = new Entity(
            $dto->getAlunoId(),
            $dto->getTurmaId(),
            date('Y-m-d')
        );

        $this->repositorio->matricular($matricula);
    }

    /**
     * Lista todas as matrículas com filtros e ordenação.
     *
     * @param array $params Filtros (aluno, turma, etc.)
     * @param string $ordem Campo de ordenação
     * @return array Lista paginada de matrículas
     */
    public function listarTodos(array $params, string $ordem = 'nome:asc'): array
    {
        return $this->repositorio->listarTodos($params, $ordem, Filter::camposPermitidos());
    }

    /**
     * Lista alunos matriculados em uma turma específica.
     *
     * @param int $turmaId ID da turma
     * @return array Lista de alunos
     */
    public function listarPorTurma(int $turmaId): array
    {
        return $this->repositorio->listarPorTurma($turmaId);
    }

    /**
     * Remove a matrícula de um aluno em uma turma.
     *
     * @param DTO $dto Dados da matrícula
     * @throws NotFoundException Se não houver matrícula registrada
     */
    public function remover(DTO $dto): void
    {
        Validator::validar($dto);

        if (!$this->repositorio->alunoJaMatriculado($dto->getAlunoId(), $dto->getTurmaId())) {
            throw new NotFoundException("Matrícula não encontrada.");
        }

        $matricula = new Entity(
            $dto->getAlunoId(),
            $dto->getTurmaId(),
        );

        $this->repositorio->remover($matricula);
    }
}
