<?php

namespace App\Domain\Matricula;

use App\Domain\Matricula\Exceptions\MatriculaDuplicadaException;
use App\Core\Exceptions\NotFoundException;

class Service
{
    public function __construct(
        private readonly Repository $repo
    ) {}

    public function matricular(DTO $dto): void
    {
        Validator::validar($dto);

        if ($this->repo->alunoJaMatriculado($dto->aluno_id, $dto->turma_id)) {
            throw new MatriculaDuplicadaException("Aluno já matriculado nesta turma.");
        }

        $matricula = new Entity(
            $dto->aluno_id,
            $dto->turma_id,
            date('Y-m-d')
        );

        $this->repo->criar($matricula);
    }

    public function listarPorTurma(int $turmaId): array
    {
        return $this->repo->listarPorTurma($turmaId);
    }

    public function remover(int $alunoId, int $turmaId): void
    {
        if (!$this->repo->alunoJaMatriculado($alunoId, $turmaId)) {
            throw new NotFoundException("Matrícula não encontrada.");
        }

        $this->repo->remover($alunoId, $turmaId);
    }

    // Opcional, caso queira alterar uma matrícula
    public function atualizar(DTO $dto): void
    {
        Validator::validar($dto);

        if (!$this->repo->alunoJaMatriculado($dto->aluno_id, $dto->turma_id)) {
            throw new NotFoundException("Matrícula não encontrada.");
        }

        $matricula = new Entity(
            $dto->aluno_id,
            $dto->turma_id,
            date('Y-m-d')
        );

        $this->repo->atualizar($matricula);
    }
}
