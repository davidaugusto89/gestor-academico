<?php

namespace App\Domain\Matricula;

use App\Domain\Matricula\Exceptions\MatriculaDuplicadaException;
use App\Core\Exceptions\NotFoundException;

class Service
{
    public function __construct(
        private readonly repository $repositorio
    ) {}

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

    public function listarTodos(array $params, string $ordem = 'nome:asc'): array
    {
        return $this->repositorio->listarTodos($params, $ordem, Filter::camposPermitidos());
    }


    public function listarPorTurma(int $turmaId): array
    {
        return $this->repositorio->listarPorTurma($turmaId);
    }

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
