<?php

namespace App\Domain\Matricula;

class Entity implements \JsonSerializable
{
    public function __construct(
        private int $alunoId,
        private int $turmaId,
        private ?string $dataMatricula = null,
        private ?int $id = null
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAlunoId(): int
    {
        return $this->alunoId;
    }

    public function getTurmaId(): int
    {
        return $this->turmaId;
    }

    public function getDataMatricula(): string
    {
        return $this->dataMatricula;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setAlunoId(int $alunoId): void
    {
        $this->alunoId = $alunoId;
    }

    public function setTurmaId(int $turmaId): void
    {
        $this->turmaId = $turmaId;
    }

    public function setDataMatricula(string $dataMatricula): void
    {
        $this->dataMatricula = $dataMatricula;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'aluno_id' => $this->alunoId,
            'turma_id' => $this->turmaId,
            'data_matricula' => $this->dataMatricula,
        ];
    }
}
