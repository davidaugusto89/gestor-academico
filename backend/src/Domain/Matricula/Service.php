<?php

namespace App\Domain\Matricula;

use App\Domain\Matricula\Exceptions\MatriculaDuplicadaException;
use App\Core\Exceptions\NotFoundException;

class Service
{
    public function __construct(
        private readonly Repository $repository
    ) {}

    public function matricular(DTO $dto): void
    {
        Validator::validar($dto);

        if ($this->repository->alunoJaMatriculado($dto->getAlunoId(), $dto->getTurmaId())) {
            throw new MatriculaDuplicadaException("Aluno já matriculado nesta turma.");
        }

        $matricula = new Entity(
            $dto->getAlunoId(),
            $dto->getTurmaId(),
            date('Y-m-d')
        );

        $this->repository->matricular($matricula);
    }

    public function listarPorTurma(int $turmaId): array
    {
        return $this->repository->listarPorTurma($turmaId);
    }

    public function remover(DTO $dto): void
    {
        Validator::validar($dto);

        if (!$this->repository->alunoJaMatriculado($dto->getAlunoId(), $dto->getTurmaId())) {
            throw new NotFoundException("Matrícula não encontrada.");
        }

        $matricula = new Entity(
            $dto->getAlunoId(),
            $dto->getTurmaId(),
        );

        $this->repository->remover($matricula);
    }
}
