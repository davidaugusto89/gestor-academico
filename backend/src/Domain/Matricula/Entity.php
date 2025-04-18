<?php

namespace App\Domain\Matricula;

/**
 * Entidade que representa a matrícula de um aluno em uma turma.
 *
 * @property int|null $id
 * @property int $alunoId
 * @property int $turmaId
 * @property string|null $dataMatricula
 */
class Entity implements \JsonSerializable
{
    /**
     * @param int $alunoId
     * @param int $turmaId
     * @param string|null $dataMatricula
     * @param int|null $id
     */
    public function __construct(
        private int $alunoId,
        private int $turmaId,
        private ?string $dataMatricula = null,
        private ?int $id = null
    ) {}

    /** @return int|null */
    public function getId(): ?int
    {
        return $this->id;
    }

    /** @return int */
    public function getAlunoId(): int
    {
        return $this->alunoId;
    }

    /** @return int */
    public function getTurmaId(): int
    {
        return $this->turmaId;
    }

    /** @return string|null */
    public function getDataMatricula(): ?string
    {
        return $this->dataMatricula;
    }

    /** @param int $id */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /** @param int $alunoId */
    public function setAlunoId(int $alunoId): void
    {
        $this->alunoId = $alunoId;
    }

    /** @param int $turmaId */
    public function setTurmaId(int $turmaId): void
    {
        $this->turmaId = $turmaId;
    }

    /** @param string $dataMatricula */
    public function setDataMatricula(string $dataMatricula): void
    {
        $this->dataMatricula = $dataMatricula;
    }

    /**
     * @return array<string, mixed>
     */
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
