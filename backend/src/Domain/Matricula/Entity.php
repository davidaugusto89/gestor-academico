<?php

namespace App\Domain\Matricula;

class Entity
{
    public function __construct(
        private int $alunoId,
        private int $turmaId,
        private string $dataMatricula,
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
}
